<?php namespace Services;


use Base\Request;
use Models\AppReport;
use \UserInfo;
use \Result;

class AppReportServices
{
    public function getList()
    {
        $result = new Result();
        $pagination = Request::getPagination();
        $condition = AppReport::where('customer_id', UserInfo::getTargetUserId());
        $keywords = Request::input("keywords");
        $column = Request::input("column");

        if (isset($keywords)) {
            $condition = $condition->where('app_name', 'like', "%$keywords%");
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

        $data = $condition->orderBy('post_date', 'desc')->forPage($pagination[0], $pagination[1])->get(
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
        $condition = AppReport::where('customer_id', UserInfo::getTargetUserId());
        $keywords = Request::input("keywords");

        if (isset($keywords)) {
            $condition = $condition->where('app_name', 'like', "%$keywords%");
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
        init_csv('应用数据');
        // @todo format
        echo  iconv("UTF-8", "gbk","应用ID,应用名称,日期,消耗/元,曝光量,互动量,互动率,图文区点击量,下载区点击量,短链点击量,转发量,关注量,赞量,收藏量,评论量,激活量Talking Data,千次曝光成本,单次互动成本,激活成本Talking Data\n");
        foreach ($data as $value) {
            echo iconv("UTF-8", "gbk", "{$value['app_id']},{$value['app_name']},{$value['post_date']},{$value['consume']},{$value['pv']},{$value['iv']},{$value['iv_rate']},{$value['click_img_cnt']},{$value['click_button_cnt']},{$value['shorturl_clked_cnt']},{$value['forward']},{$value['follow']},{$value['like']},{$value['favorite']},{$value['comment']},{$value['activate1']},{$value['pv_cost']},{$value['iv_cost']},{$value['activate_cost1']}\n");
        }
    }
}