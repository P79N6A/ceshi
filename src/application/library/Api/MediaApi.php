<?php
namespace Api;

use Type\AppType;

/**
 * build tags for photo from api
 * Class MediaApi
 * @package Api
 */
class MediaApi
{

    /**
     * build tags
     * @param $customer_id
     * @param $photo_tag
     * @return bool|mixed
     */
    public static function build_tags($customer_id, $photo_tag){

        if(empty($customer_id)){
            return false;
        }

        $creative_config = \Config::get('creative');
        $retry_count = \Config::get('api')['retry_count'];

        $params =array(
            'source' => $creative_config['media']['source'],
            'cuid' => $customer_id,
            'photo_tag' => $photo_tag,
        );
        while (($retry_count--) > 0) {
            $result = \Curl::get($creative_config['media']['url'], $params);
            if (\Curl::getHttpCode() === 200) {
                break;
            }
        }
        $data = json_decode($result, true);

        if (!is_array($data)) {
            \Alert::send("创建图片标签失败, 返回内容格式错误!", [$result]);
            return false;
        }

        if (isset($data['error_code']) && isset($data['error'])) {
            \Alert::send("创建图片标签失败, 错误码: {$data['error_code']}({$data['error']})!", [$result]);
            \logFile::error("创建图片标签失败, 错误码: {$data['error_code']}({$data['error']})!", [$result]);
            return false;
        }
        return $data;
    }

}

