<?php namespace Api;


use Type\AppType;
use Type\CreativeType;

class ObjectApi
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

    public static function import($app_id, $image_url, $display_name, $summary, $summery_type, $customer_id, $type)
    {
        $app_id = ($type == AppType::IOS) ? 'i'.$app_id : $app_id;

        $container_id = '100404' . $app_id;

        $app_url = sprintf(\Config::get('app')['long_url'], $app_id, $customer_id);

        $object_info = [
            'image_url' => $image_url,
            'display_name' => $display_name,
            'summary' => $summary,
            'summary_type' => $summery_type,
            'py_id' => $customer_id,
            'container_id' => $container_id,
        ];

        $post_data = [
            'signature' => getenv('OBJECT_SIGNATURE'),
            'url' => $app_url,
            'object' => json_encode($object_info)
        ];
        $url = \Config::get('api')['object_url'];

        $retry_count = \Config::get('api')['retry_count'];

        $result = '';

        while (($retry_count--) > 0) {
            //发送POST请求
            $result = \Curl::post($url, $post_data);

            //验证HttpCode
            if (\Curl::getHttpCode() === 200) {
                break;
            }
        }

        $http_code = \Curl::getHttpCode();

        \LogFile::info("{$url}\t{$http_code}\t{$result}");

        \LogFile::info(urldecode(http_build_query($post_data)));

        $data = json_decode($result, true);

        //验证数据类型
        if (!is_array($data)) {
            \Alert::send("导入视频对象失败, 返回内容格式错误!", [$result]);

            return false;
        }

        //验证是否有错误码和错误信息
        if (isset($data['error_code']) && isset($data['error'])) {
            \Alert::send("导入视频对象失败, 错误码: {$data['error_code']}({$data['error']})!", [$result]);

            return false;
        }

        //验证返回结果
        if (empty($data['object_id'])) {
            \Alert::send("导入视频对象失败, 返回结果中object_id为空!", [$result]);

            return false;
        }

        //验证返回结果
        if (empty($data['short_url'])) {
            \Alert::send("导入视频对象失败, 返回结果中short_url为空!", [$result]);

            return false;
        }

        $data['long_url'] = $app_url;

        return $data;
    }

    public static function delete($object_id)
    {
        $url = \Config::get('api')['delete_object'];
        $retry_count = \Config::get('api')['retry_count'];

        $result = '';
        $post_data = [
            'source' => '4009338982', // @todo
            'object_id' => $object_id
        ];

        while (($retry_count--) > 0) {
            //发送POST请求
            $result = \Curl::post($url, $post_data);

            //验证HttpCode
            if (\Curl::getHttpCode() === 200) {
                break;
            }
        }

        $http_code = \Curl::getHttpCode();

        \LogFile::info("{$url}\t{$http_code}\t{$result}");

        \LogFile::info(urldecode(http_build_query($post_data)));

        $data = json_decode($result, true);

        //验证数据类型
        if (!is_array($data)) {
            \Alert::send("删除对象失败!", [$result]);
            return false;
        }

        //验证是否有错误码和错误信息
        if (isset($data['error_code']) && isset($data['error'])) {
            \Alert::send("删除对象失败!!", [$result]);
            return false;
        }

        return true;
    }


    /**
     * 导入标签
     *
     * @param $display_name
     * @param $app_id
     * @param $customer_id
     * @param $type
     * @return array|bool|mixed
     * @internal param iis $post_params
     */
    public static function importTag($display_name,$app_id, $customer_id,$type)
    {

        if($type == AppType::IOS){
            $app_id =  'i'.$app_id;
            $app_url = sprintf(\Config::get('app')['down_url']['ios'], $app_id, $customer_id);
        } else{
            $app_url = sprintf(\Config::get('app')['down_url']['android'], $app_id, $customer_id);
        }
        $container_id = '100404' . $app_id;

        $object_info = array(
            'display_name' => $display_name,
            'container_id' => $container_id,
            'py_id' => $customer_id,
        );

        $post_data = array(
            'signature' => getenv('OBJECT_SIGNATURE'),
            'url' => $app_url,
            'object' => json_encode($object_info)
        );

        $url = \Config::get('api')['object_url_tag'];
        $retry_count = \Config::get('api')['retry_count'];
        $result = '';
        while (($retry_count--) > 0) {
            $result = \Curl::post($url, $post_data);
            if (\Curl::getHttpCode() === 200) {
                break;
            }
        }

        $http_code = \Curl::getHttpCode();
        \LogFile::debug("{$url}\t{$http_code}\t{$result}");
        \LogFile::debug(urldecode(http_build_query($post_data)));
        $data = json_decode($result, true);
        //验证数据类型
        if (!is_array($data)) {
            \LogFile::error("导入视频对象失败, 返回内容格式错误!", [$result]);
            \Alert::send("导入视频对象失败, 返回内容格式错误!", [$result]);
            return false;
        }

        //验证是否有错误码和错误信息
        if (isset($data['error_code']) && isset($data['error'])) {
            \LogFile::error("导入视频对象失败, 错误码: {$data['error_code']}({$data['error']})!", [$result]);
            \Alert::send("导入视频对象失败, 错误码: {$data['error_code']}({$data['error']})!", [$result]);
            return false;
        }
        $data['long_url'] = $app_url;
        return $data;
    }



    /**
     * 导入标签批量
     *
     * @param $post_params iis
     * @return array|bool|mixed
     */
    public static function importTagMultiple($post_params)
    {
        $post_data = array();
        foreach($post_params as $data){
            $display_name  = $data['display_name'];
            $app_id = $data['app_id'];
            $customer_id = $data['customer_id'];
            $type = $data['type'];
            if($type == AppType::IOS){
                $app_id =  'i'.$app_id;
                $app_url = sprintf(\Config::get('app')['down_url']['ios'], $app_id, $customer_id);
            } else{
                $app_url = sprintf(\Config::get('app')['down_url']['android'], $app_id, $customer_id);
            }
            $container_id = '100404' . $app_id;

            $object_info = array(
                'display_name' => $display_name,
                'container_id' => $container_id,
                'py_id' => $customer_id,
            );

            $post_data[] = array(
                'signature' => getenv('OBJECT_SIGNATURE'),
                'url' => $app_url,
                'object' => json_encode($object_info)
            );
        }

        $url = \Config::get('api')['object_url'];
        $retry_count = \Config::get('api')['retry_count'];
        $result = '';
        while (($retry_count--) > 0) {
            $result = \Curl::post($url, $post_data);
            if (\Curl::getHttpCode() === 200) {
                break;
            }
        }

        $http_code = \Curl::getHttpCode();
        \LogFile::debug("{$url}\t{$http_code}\t{$result}");
        \LogFile::debug(urldecode(http_build_query($post_data)));
        $data = json_decode($result, true);
        //验证数据类型
        if (!is_array($data)) {
            \LogFile::error("导入视频对象失败, 返回内容格式错误!", [$result]);
            \Alert::send("导入视频对象失败, 返回内容格式错误!", [$result]);
            return false;
        }

        //验证是否有错误码和错误信息
        if (isset($data['error_code']) && isset($data['error'])) {
            \LogFile::error("导入视频对象失败, 错误码: {$data['error_code']}({$data['error']})!", [$result]);
            \Alert::send("导入视频对象失败, 错误码: {$data['error_code']}({$data['error']})!", [$result]);
            return false;
        }
        return $data;
    }
}

