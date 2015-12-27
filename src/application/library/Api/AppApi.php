<?php namespace Api;

use Base\Request;
use Curl;
use Type\AppType;
use Type\CreativeType;


class AppApi
{

    private static $_map = [
        '0' => '[空星][空星][空星][空星][空星]',
        '0.5' => '[半星][空星][空星][空星][空星]',
        '1' => '[星星][空星][空星][空星][空星]',
        '1.5' => '[星星][半星][空星][空星][空星]',
        '2' => '[星星][星星][空星][空星][空星]',
        '2.5' => '[星星][星星][半星][空星][空星]',
        '3' => '[星星][星星][星星][空星][空星]',
        '3.5' => '[星星][星星][星星][半星][空星]',
        '4' => '[星星][星星][星星][星星][空星]',
        '4.5' => '[星星][星星][星星][星星][半星]',
        '5' => '[星星][星星][星星][星星][星星]',
    ];

    public static function getApps($customer_id)
    {
        $url = \Config::get('api')['app_url'] . $customer_id;

        $retry_count = \Config::get('api')['retry_count'];

        $result = '';
        while (($retry_count--) > 0) {
            //发送POST请求
            $result = \Curl::get($url);

            //验证HttpCode
            if (\Curl::getHttpCode() === 200) {
                break;
            }
        }

        $http_code = \Curl::getHttpCode();

        \LogFile::info("{$url}\t{$http_code}\t{$result}");

        $data = json_decode($result, true);

        //验证数据类型
        if (!is_array($data)) {
            \Alert::send("请求APP API, 返回内容格式错误!", [$result]);
            return [];
        }

        //验证是否有错误码和错误信息
        if (isset($data['error_code']) && ($data['error_code'] != 0 || $data['error_code'] != 2)) {
            \Alert::send("请求APP API错误, 错误码: {$data['error_code']}({$data['error']})!", [$result]);
            return [];
        }

        if (empty($data['data'])) {
            return [];
        }

        $app_raw_list = $data['data'];
        $app_list = [];

        foreach ($app_raw_list as $raw_app) {
            $app = [];
            $app['status'] = $raw_app['status'];
            $status = Request::input('status');
            if ($status && $status != $app['status']) {
                continue;
            }

            @$app['package_name'] = empty($raw_app['packageName']) ? $raw_app['bundleId'] : $raw_app['packageName'];
            if (empty($app['package_name'])) {
                continue;
            }
            @$app['status_description'] = $raw_app['sta_desc'];
            @$app['type'] = $raw_app['apptype'];
            @$app['type_name'] = ($raw_app['apptype'] == AppType::IOS) ? 'iOS' : 'Android';
            if ($raw_app['status'] == 2) {
                @$app['app_id'] = empty($raw_app['appid']) ? '' : $raw_app['appid'];
                @$app['description'] = $raw_app['desc'];
                @$app['name'] = $raw_app['name'];
                $app['category'] = empty($raw_app['cat']) ? '' : $raw_app['cat'];
                @$app['score'] = $raw_app['score'];
                @$app['star'] = self::$_map[$raw_app['score']];
                @$app['size'] = round($raw_app['size'] / 1024, 2) . 'MB';
                @$app['raw_size'] = $raw_app['size'];
                $app['raw_download'] = empty($raw_app['download_count']) ? 0 : $raw_app['download_count'];
                $app['download'] = $app['raw_download'] . '人在用';
                @$app['icon'] = $raw_app['iconUrl'];
                @$app['version'] = $raw_app['versionName'];
                @$app['created_at'] = date('Y-m-d', $raw_app['vtime']);
                @$app['package_name'] = empty($raw_app['packageName']) ? $raw_app['bundleId'] : $raw_app['packageName'];
            }

            if (empty($raw_app['appid'])) {
                $app_list[$app['package_name']] = $app;
            } else {
                $app_list[$raw_app['appid']] = $app;
            }
        }
        return $app_list;
    }

    public static function getSummery($app, $summery, $summery_type)
    {
        if (CreativeType::SUMMERY_CUSTOM == $summery_type) {
            return $summery;
        } elseif (CreativeType::SUMMERY_DOWNLOAD == $summery_type) {
            return $app['download'];
        } elseif (CreativeType::SUMMERY_SCORE == $summery_type) {
            return $app['score'];
        } elseif (CreativeType::SUMMERY_SIZE == $summery_type) {
            return $app['size'];
        } elseif (CreativeType::SUMMERY_CATEGORY == $summery_type) {
            return $app['category'];
        } else {
            return $summery;
        }
    }

    public static function getObjectSummery($app, $summery, $summery_type)
    {
        if (CreativeType::SUMMERY_CUSTOM == $summery_type) {
            return $summery;
        } elseif (CreativeType::SUMMERY_DOWNLOAD == $summery_type) {
            return $app['download'];
        } elseif (CreativeType::SUMMERY_SCORE == $summery_type) {
            return self::$_map[$app['score']];
        } elseif (CreativeType::SUMMERY_SIZE == $summery_type) {
            return '大小 : ' . $app['size'];
        } elseif (CreativeType::SUMMERY_CATEGORY == $summery_type) {
            return '分类 : ' . $app['category'];
        } else {
            return $summery;
        }
    }

}

