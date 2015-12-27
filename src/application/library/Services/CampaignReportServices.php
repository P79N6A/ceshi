<?php namespace Services;


use Base\DB;
use Base\Request;
use Models\CustomerReport;
use Models\CampaignReport;
use Api\AppApi;
use \UserInfo;
use \Result;
use \Type\CreativeType;

class CampaignReportServices
{

    public function getOverview()
    {
        $condition = CustomerReport::where('customer_id', UserInfo::getTargetUserId());

        $start_time = Request::input('start_time');

        if (isset($start_time)) {
            $condition = $condition->where('post_date', '>=', $start_time);
            $end_time = Request::input('end_time');
            if (isset($end_time)) {
                $condition = $condition->where('post_date', '<=', $end_time);
            }
        }
        $result = $condition->orderBy('updated_at', 'desc')->get()->toArray();

        if (empty($result)) {
            return [];
        }

        $data = [];
        $data['x_axis']['categories'] = array_column($result, 'post_date');
        $data['back_data']['pv'] = ['y_axis' => array_column($result, 'pv'), 'unit' => '次', 'name' => '曝光量'];
        $data['back_data']['iv'] = ['y_axis' => array_column($result, 'iv'), 'unit' => '次', 'name' => '互动量'];
        $data['back_data']['click'] = ['y_axis' => array_column($result, 'click'), 'unit' => '次', 'name' => '点击量'];
        $data['back_data']['click_img_cnt'] = ['y_axis' => array_column($result, 'click_img_cnt'), 'unit' => '次', 'name' => '图文区点击量'];
        $data['back_data']['click_button_cnt'] = ['y_axis' => array_column($result, 'click_button_cnt'), 'unit' => '次', 'name' => '下载区点击量'];
        $data['back_data']['shorturl_clked_cnt'] = ['y_axis' => array_column($result, 'shorturl_clked_cnt'), 'unit' => '次', 'name' => '短链点击量'];
        $data['back_data']['follow'] = ['y_axis' => array_column($result, 'follow'), 'unit' => '次', 'name' => '关注量'];
        $data['back_data']['favorite'] = [
            'y_axis' => array_column($result, 'favorite'),
            'unit' => '次',
            'name' => '收藏量'
        ];
        $data['back_data']['forward'] = ['y_axis' => array_column($result, 'forward'), 'unit' => '次', 'name' => '转发量'];
        $data['back_data']['comment'] = ['y_axis' => array_column($result, 'comment'), 'unit' => '次', 'name' => '评论量'];
        $data['back_data']['like'] = [
            'y_axis' => array_map("floatval", array_column($result, 'like')),
            'unit' => '次',
            'name' => '赞量'
        ];
        $data['back_data']['consume'] = [
            'y_axis' => array_map("floatval", array_column($result, 'consume')),
            'unit' => '元',
            'name' => '消耗(元)'
        ];
        $data['back_data']['click_rate'] = [
            'y_axis' => array_map(
                function ($v) {
                    return floatval($v) * 100;
                },
                array_column($result, 'click_rate')
            ),
            'unit' => '%',
            'name' => '点击率'
        ];
        $data['back_data']['iv_rate'] = [
            'y_axis' => array_map(
                function ($v) {
                    return floatval($v) * 100;
                },
                array_column($result, 'iv_rate')
            ),
            'unit' => '%',
            'name' => '互动率'
        ];
        $data['back_data']['pv_cost'] = [
            'y_axis' => array_map("floatval", array_column($result, 'pv_cost')),
            'unit' => '元',
            'name' => '千次曝光成本'
        ];
        $data['back_data']['iv_cost'] = [
            'y_axis' => array_map("floatval", array_column($result, 'iv_cost')),
            'unit' => '元',
            'name' => '单次互动成本'
        ];

        $pv = array_sum(array_column($result, 'pv'));
        $iv = array_sum(array_column($result, 'iv'));
        $consume = array_sum(array_column($result, 'consume'));

        $data['table_data'] = [
            'pv' => $pv,
            'iv' => $iv,
            'consume' => number_format($consume, 2, '.', ''),
            'iv_rate' => number_format(($iv / $pv) * 100, 2, '.', ''),
            'pv_cost' => number_format($consume * 1000 / $pv, 2, '.', ''),
            'iv_cost' => number_format($consume / $iv, 2, '.', '')
        ];

        $data['table_data']['updated_at'] = (isset($result[0]['updated_at'])) ? $result[0]['updated_at'] : date(
            'Y-m-d H:i:s'
        );

        return $data;
    }


    public function getList()
    {
        $pagination = Request::getPagination();
        $result = new Result();

        $condition = CampaignReport::where('customer_id', UserInfo::getTargetUserId());

        $column = Request::input("column");
        $app_id = Request::input("app_id");
        $keywords = Request::input("keywords");


        if (isset($keywords)) {
            $condition = $condition->where(
                function ($query) use ($keywords) {
                    $query->where('campaign_name', 'like', "%$keywords%")
                        ->orWhere('app_name', 'like', "%$keywords%");
                }
            );
        }
        if (isset($app_id)) {
            $condition = $condition->where('app_id', $app_id);
        }

        $start_time = Request::input('start_time');

        if (isset($start_time)) {
            $condition = $condition->where('post_date', '>=', $start_time);
            $end_time = Request::input('end_time');
            if (isset($end_time)) {
                $condition = $condition->where('post_date', '<=', $end_time);
            }
        }

        $column_array = null;
        if (isset($column)) {
            $column_array = explode(',', trim($column));
        }

        $total = $condition->count();

        $data = $condition->orderBy('updated_at', 'desc')->forPage($pagination[0], $pagination[1])->get(
            $column_array
        )->toArray();

        array_walk($data, function(&$row) {
            if (isset($row['iv_rate'])) {
                $row['iv_rate'] *= 100;
            }

            $row['iv_rate'] = number_format($row['iv_rate'], 2);
        });

        $result->data = [
            $data
        ];

        $result->total_count = $total;

        return $result->toArray();
    }

    public function download()
    {
        $condition = CampaignReport::where('customer_id', UserInfo::getTargetUserId());
        $keywords = Request::input("keywords");

        if (isset($keywords)) {
            $condition = $condition->where(
                function ($query) use ($keywords) {
                    $query->where('campaign_name', 'like', "%$keywords%")
                        ->orWhere('app_name', 'like', "%$keywords%");
                }
            );
        }
        $start_time = Request::input('start_time');

        if (isset($start_time)) {
            $condition = $condition->where('post_date', '>=', $start_time);
            $end_time = Request::input('end_time');
            if (isset($end_time)) {
                $condition = $condition->where('post_date', '<=', $end_time);
            }
        }

        $data = $condition->orderBy('post_date', 'desc')->limit(10000)->get()->toArray();
        init_csv('计划数据');
        echo  iconv("UTF-8", "gbk","计划ID,计划名称,推广应用,日期,消耗/元,曝光量,互动量,互动率,图文区点击量,下载区点击量,短链点击量,转发量,关注量,赞量,收藏量,评论量,激活量Talking Data,千次曝光成本,单次互动成本,激活成本Talking Data\n");
        foreach ($data as $value) {
            echo iconv("UTF-8", "gbk", "{$value['campaign_id']},{$value['campaign_name']},{$value['app_name']},{$value['post_date']},{$value['consume']},{$value['pv']},{$value['iv']},{$value['iv_rate']},{$value['click_img_cnt']},{$value['click_button_cnt']},{$value['shorturl_clked_cnt']},{$value['forward']},{$value['follow']},{$value['like']},{$value['favorite']},{$value['comment']},{$value['activate1']},{$value['pv_cost']},{$value['iv_cost']},{$value['activate_cost1']}\n");
        }
    }
}