<?php namespace Api;


use Curl;
use Alert;
use Exception\HttpException;
use LogFile;
use Config;

/**
 * Class     FeedApi
 * FeedApi
 *
 */
class FeedApi
{

    public static function add($mid)
    {
        $result = WeiboApi::getStatusInfoByMid($mid);

        if (empty($result)) {
            return false;
        }
        $uid = $result['user']['id'];

        $url = sprintf(Config::get('api')['feed_status_url'], $mid, time(), 'add', $uid);
        $retry_count = \Config::get('api')['retry_count'];

        while (($retry_count--) > 0) {
            //发送POST请求
            $result = Curl::get($url);

            //验证HttpCode
            if (\Curl::getHttpCode() === 200) {
                break;
            }
            LogFile::info('feed status', [$url, $result]);
        }

        if ($result == '{"code":0,"msg":"Success"}') {
            return true;
        } else {
            Alert::send('通知feed status 添加失败', $result);
            LogFile::alert('通知feed status 添加失败', $result);
            return false;
        }
    }

    public static function delete($mid)
    {
        $result = WeiboApi::getStatusInfoByMid($mid);

        if (empty($result)) {
            return false;
        }
        $uid = $result['user']['id'];

        $url = sprintf(Config::get('api')['feed_status_url'], $mid, time(), 'delete', $uid);
        $retry_count = \Config::get('api')['retry_count'];

        while (($retry_count--) > 0) {
            //发送POST请求
            $result = Curl::get($url);

            //验证HttpCode
            if (\Curl::getHttpCode() === 200) {
                break;
            }
            LogFile::info('feed status', [$url, $result]);
        }

        if ($result == '{"code":0,"msg":"Success"}') {
            return true;
        } else {
            Alert::send('通知feed status 添加失败', $result);
            LogFile::alert('通知feed status 添加失败', $result);
            return false;
        }
    }

    public static function isLegal($content)
    {
        $url = getenv('WORD_PROCESS') . '/pattern_word?type=fansmore';
        $retry_count = \Config::get('api')['retry_count'];

        $result = '';
        $post_data = [
            'data' => urlencode(json_encode([$content])), // @todo
        ];

        while (($retry_count--) > 0) {
            //发送POST请求
            $result = \Curl::post($url, $post_data);

            //验证HttpCode
            if (\Curl::getHttpCode() === 200) {
                break;
            }
        }

        $data = json_decode($result, true);

        //验证数据类型
        if (!is_array($data)) {
            LogFile::error("分词验证接口解析失败", [$result]);
            \Alert::send("分词验证接口解析失败", [$result]);
            //throw new HttpException(500, "分词验证失败");
            //解析失败暂按通过处理
            return true;
        }

        //验证是否有错误码和错误信息
        if (!isset($data['code']) && !isset($data['result'][0]['hit']) && $data['code'] != 200) {
            LogFile::alert("分词验证失败!", [$result]);
            \Alert::send("分词验证失败!", [$result]);
            throw new HttpException(500, "分词验证失败");
        }

        if ($data['result'][0]['hit'] !== 0) {
            throw new HttpException(422, "您的微博内容中有非法内容！");
        }

        return true;
    }
}
