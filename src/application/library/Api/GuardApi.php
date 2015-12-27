<?php namespace Api;

use Type\CreativeType;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GuardApi {

    public static function send($creative, $app_name='') {
        $url = getenv('GUARD_URL');;
        //重试连接次数
        $retry_count = 3;

        //发送数据的服务器ip
        //$server_ip = ; 部分服务器上本方法取得的ip是0.0.0.0
        $server_ip = Guard::get_server_ip();
        //分配的api_id
        $api_id = getenv('GUARD_ID');
        //分配的hash_key
        $api_hash_key = getenv('GUARD_KEY');
        $data['unique_id'] = $creative['id'];
        $data['title'] = $creative['name'];
        $data['content'] = $creative['content'];
        $data['customer_id'] = $creative['customer_id']; //本条内容对应发布人的微博uid
        $data['auth'] = 'all'; //对应审核权限, 默认为all
        $data['ext'][] = array('customer_name', \UserInfo::getTargetUserName(), Guard::EXT_TYPE_TEXT);
        $data['ext'][] = array('app_name', $app_name, Guard::EXT_TYPE_TEXT);
        $images = json_decode($creative['images'], true);
        $data['ext'][] = array(
            'image',
            $images[0],
            Guard::EXT_TYPE_PIC
        );
        $data['ext'][] = array('display_name', $creative['display_name'], Guard::EXT_TYPE_TEXT);
        $data['ext'][] = array(
            'summery',
            CreativeType::getDesc($creative['summery_type']) . ':' . $creative['summery'],
            Guard::EXT_TYPE_TEXT
        );

        \LogFile::info('通知审核', [$api_hash_key, $server_ip]);
        Guard::init($url, $api_id, $api_hash_key, $server_ip, $retry_count);
        $ret = Guard::send($data); //以json形式post到审核服务器api
        \LogFile::info('通知审核 result', (array)$ret);

        if (!isset($ret['status'])|| $ret['status'] != Guard::STATUS_SUCCESS) {
            \LogFile::alert('通知审核失败', (array)$ret);
            \Alert::send('通知审核失败', var_export($ret, true));
        }

    }
}



/**
 * Description of Guard
 *
 * @author T
 */
class Guard {

    /**
     * 允许的扩展字段类型
     * preview 是一个url , 在审核的时候, 将会打开一个iframe 直接预览这个页面
     * @var type 
     */
    public static $ext_type = array('text', 'pic', 'video', 'preview', 'url');
    
    /**
     * 扩展字段: 文本数据
     */
    CONST EXT_TYPE_TEXT = 'text';
    /**
     * 扩展字段: 图片url
     */
    CONST EXT_TYPE_PIC = 'pic';
    /**
     * 扩展字段:视频url
     */
    CONST EXT_TYPE_VIDEO = 'video';
    /**
     * 扩展字段:预览网址url, 这个在审核的时候会在iframe内直接打开这个网站
     */
    CONST EXT_TYPE_PREVIEW = 'preview';
    /**
     * 扩展字段: 网址, 审核的时候, 可以直接点击
     */
    CONST EXT_TYPE_URL = 'url';

    /**
     * 200标识：数据传输成功
     * 返回：
     */
    const STATUS_SUCCESS = 200;

    /**
     * 500标识：数据传输成功，json串格式错误
     */
    const STATUS_JSON_ERROR = 500;

    /**
     * 501标识：数据传输成功，必要数据不完整，入库失败
     */
    const STATUS_STORAGE_ERROR = 501;

    /**
     * 502标识：数据传输失败，内容为空
     */
    const STATUS_CONTENT_ERROR = 502;

    /**
     * 503标识：数据POST传输地址错误
     */
    const STATUS_ADDRESS_ERROR = 503;

    /**
     * 504标识http请求方式非POST
     */
    const STATUS_METHOD_ERROR = 504;

    /**
     * Variable  _connect_timeout
     *
     * @author   yangyang3
     * @static
     * @var      int
     */
    private static $_connect_timeout = 5;

    /**
     * Variable  _timeout
     *
     * @static
     * @var      int
     */
    private static $_timeout = 5;

    /**
     * Variable  _http_code
     *
     * @static
     * @var
     */
    private static $_http_code;

    /**
     * @var string 用户端向服务端post数据地址
     */
    private static $url;

    /**
     * @var int 请求次数
     */
    private static $retry_count;

    /**
     * @var int 请求次数
     */
    private static $api_id;
    private static $api_hash_key;

    /**
     * @var int 请求次数
     */
    private static $server_ip;
    public static $message = '';

    /**
     * Method 初始化函数
     *
     * @param $url          string  审核中心的api地址
     * @param $api_hash_key     string  用户提供的唯一性ID标识
     * @param $server_ip    string  服务器IP
     * @param $retry_count  int     连接重试次数
     */
    public static function init($url, $api_id, $api_hash_key, $server_ip, $retry_count = 3) {
        self::$api_id = $api_id;
        self::$api_hash_key = self::getHashKey($api_hash_key . $server_ip);
        self::$server_ip = $server_ip;
        self::$url = $url;
        self::$retry_count = intval($retry_count);
    }

    /**
     * 客户端向服务器POST数据
     *
     * @param $data      json              POST的数据
     * @return bool
     */
    public static function send($data) {

        $send_data['api_id'] = self::$api_id;
        $send_data['api_hash'] = self::$api_hash_key;

        if (!is_array(($data))) {

            return array('status' => self::STATUS_CONTENT_ERROR, 'msg' => "data format error ");
        }

        $url = self::$url;
        $send_data['data'] = json_encode($data, JSON_HEX_AMP);
        $result = self::post(self::$url, self::$retry_count, $send_data);

        if (self::$_http_code != 200) {
            return array('status' => self::STATUS_ADDRESS_ERROR, 'msg' => "connect server failed, http error code:" . self::$_http_code);
        }

        $data = json_decode($result, true);
        //return $data;

        if (isset($data['status'])) {
            return $data;
        } else {
            return array('status' => self::STATUS_JSON_ERROR, 'msg' => "return data is not json : " . var_export($result, 1));
        }
    }

    /**
     * Method  post
     * 发送post请求
     *
     *
     * @param      $url
     * @param      $retry_count
     * @param null $data
     * @param null $header
     * @return string
     */
    public static function post($url, $retry_count, $data = null, $header = null) {
        $result = array();
        while (($retry_count--) > 0) {
            //发送POST请求
            $result = self::_sendHttpRequest('POST', $url, $data, $header);
            ;

            //验证HttpCode
            if (self::getHttpCode() === 200) {
                break;
            }
        }
        return $result;
    }

    /**
     * Method  _sendHttpRequest
     * 发送http请求
     *
     * @author yangyang3
     * @static
     *
     * @param       $method
     * @param       $url
     * @param null  $data
     * @param array $header
     *
     * @return mixed
     */
    private static function _sendHttpRequest($method, $url, $data = null, $header = array()) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::$_connect_timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::$_timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_HEADER, false);

        $method = strtoupper($method);
        \LogFile::debug('raw request', $data);
        if ('GET' === $method) {
            if ($data !== null) {
                if (strpos($url, '?')) {
                    $url .= '&';
                } else {
                    $url .= '?';
                }
                $url .= http_build_query($data);
            }
        } elseif ('POST' === $method) {
            curl_setopt($curl, CURLOPT_POST, true);
            if (!empty($data)) {
                if (is_string($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, "data=" . $data);
                } else {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                }
            }
        }


        if (null !== $header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($curl);
        \LogFile::debug('raw response', $response);

        self::$_http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $response;
    }

    /**
     * Method  getHttpCode
     * 获取http状态码
     *
     * @author yangyang3
     * @static
     * @return int
     */
    private static function getHttpCode() {
        return self::$_http_code;
    }

    /**
     * Method 字符串加密
     *
     * @param $string
     * @return mixed
     */
    private static function getHashKey($string) {
        return md5($string);
    }

    /**
     * Method  get_server_ip
     * 获取服务器IP地址
     *
     * @author yangyang3
     * @return string
     */
    public static function get_server_ip() {
        $ip = '';

        for ($index = 1; $index >= 0; --$index) {
            $output = array();

            exec("/sbin/ifconfig | grep eth{$index}", $output);

            if (!empty($output)) {
                $output = array();

                exec("/sbin/ifconfig eth{$index} | grep 'inet addr' | sed -e 's/\\(^ *\\)//' | awk -F '[ :]' '{print $3}'", $output);

                if (isset($output[0])) {
                    $ip = $output[0];
                }

                return $ip;
            }
        }

        return '0.0.0.0';
    }

}
