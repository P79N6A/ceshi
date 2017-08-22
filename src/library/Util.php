<?php
namespace Ceshi;

/**
 * Class     Util
 *
 * @author   luoliang1 wenyue1
 */
class Util {

    static $_service_holder = array();

    static $_model_holder = array();

    /**
     * Method  x
     * 调试方法
     *
     * @author luoliang1
     */
    static function x() {
        $argument_list = func_get_args();

        $called = debug_backtrace();

        echo '<pre>' . PHP_EOL;

        foreach ($argument_list as $variable) {

            echo '<strong>' . $called[0]['file'] . ' (line ' . $called[0]['line'] . ')</strong> ' . PHP_EOL;

            if (is_array($variable)) {
                print_r($variable);
            } else {
                var_dump($variable);
            }

            echo PHP_EOL;
        }

        echo '</pre>' . PHP_EOL;
        exit();
    }

    /**
     * Method  underlineToCamel
     * 下划线转驼峰
     *
     * @author luoliang1 wenyue1
     * @static
     *
     * @param string $string
     * @param bool   $is_ignore_uppercase
     *
     * @return string
     */
    static function underlineToCamel($string, $is_ignore_uppercase = false) {
        if (false === $is_ignore_uppercase) {
            return preg_replace_callback('/_([a-zA-Z])/', function ($m) {
                return strtoupper($m[1]);
            }, $string);
        } else {
            return preg_replace_callback('/_([a-z])/', function ($m) {
                return strtoupper($m[1]);
            }, $string);
        }
    }

    /**
     * Method  camelToUnderline
     * 驼峰转下划线
     *
     * @author luoliang1
     * @static
     *
     * @param $string
     *
     * @return string
     */
    static function camelToUnderline($string) {
        return strtolower(preg_replace('/(?!^)(?=[A-Z])/', '_', $string));
    }

    /**
     * get_server_ip
     * 获取当前server ip
     *
     * @author haicheng wenyue1
     * @return string
     */
    static function getServerIp() {
        if (isset($_SERVER['WEIBO_ADINF_SERVERIP'])) {
            return $_SERVER['WEIBO_ADINF_SERVERIP'];
        } elseif (isset($_SERVER['SINASRV_INTIP'])) { // 动态平台环境变量
            return $_SERVER['SINASRV_INTIP'];
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            return $_SERVER['SERVER_ADDR'];
        }

        return php_uname('n');
    }

    /**
     * Method  isUrl
     * 验证URL
     *
     * @author haicheng
     *
     * @param string $variable
     *
     * @return bool
     */
    static function isUrl($variable = '') {

        $pattern = '/^((https|http|)?:[\/\/]{2})[a-zA-Z0-9]+.[^\s]+/is';

        if (preg_match($pattern, trim($variable))) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Method  getChineseStringLength
     * 获取中文字符长度
     *
     * @author haicheng
     *
     * @param $string
     *
     * @return int
     */
    static function getChineseStringLength($string) {
        $string = trim($string);

        if ('' === $string) {
            return 0;
        }

        $string_length = mb_strlen($string, 'UTF-8');

        $chinese_string_length = mb_strlen(preg_replace('/[0-9a-z\s]+/is', '', $string), 'UTF-8');

        if ($string_length === $chinese_string_length) {
            return $string_length;
        }

        return $chinese_string_length + ceil(($string_length - $chinese_string_length) / 2);
    }

    /**
     * Method  getClientIp
     * 获取客户端IP
     *
     * @author luoliang1
     * @static
     * @return bool|string
     */
    static function getClientIp() {
        //验证HTTP头中是否有REMOTE_ADDR
        if (!isset($_SERVER['REMOTE_ADDR'])) {
            return '127.0.0.1';
        }

        //验证是否为非私有IP
        if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            return $_SERVER['REMOTE_ADDR'];
        }

        //验证HTTP头中是否有HTTP_CIP
        if (isset($_SERVER['HTTP_CIP'])) {
            return $_SERVER['HTTP_CIP'];
        }

        //验证HTTP头中是否有HTTP_X_FORWARDED_FOR
        if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        //定义客户端IP
        $client_ip = '';

        //获取", "的位置
        $position = strrpos($_SERVER['HTTP_X_FORWARDED_FOR'], ', ');

        //验证$position
        if (false === $position) {
            $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $client_ip = substr($_SERVER['HTTP_X_FORWARDED_FOR'], $position + 2);
        }

        //验证$client_ip是否为合法IP
        if (filter_var($client_ip, FILTER_VALIDATE_IP)) {
            return $client_ip;
        } else {
            return false;
        }
    }

    /**
     * Method  isEqualsForNumber
     * 比较两个数是否相等
     *
     * @author luoliang1
     * @static
     *
     * @param $variable1
     * @param $variable2
     *
     * @return bool
     */
    static function isEqualsForNumber($variable1, $variable2) {
        return abs($variable1 - $variable2) < 0.0000000001;
    }

    /**
     * parsePicIdFromImageUrl
     * 从图片url里面获取pid id
     *
     * @author haicheng
     * @return bool
     *
     * @param string $photo
     */
    static function parsePicIdFromImageUrl($photo = '') {

        preg_match('/[0-9a-zA-Z]{21,40}/i', $photo, $match_pic_id);
        if (empty($match_pic_id)) {
            return false;
        }

        return $match_pic_id[0];
    }

    /**
     * Method  getContainerIdByPageUrl
     * 根据Page的URL获取ContainerId
     *
     * @author haicheng
     *
     * @param $url
     *
     * @return bool
     */
    static function getContainerIdByPageUrl($url) {
        if (0 !== strpos(trim($url), 'http://weibo.com/p/')) {
            return false;
        }

        $url_info = parse_url($url);

        if (empty($url_info['path'])) {
            return false;
        }

        $path_list = explode('/', trim($url_info['path'], '/'));

        if (!isset($path_list[1])) {
            return false;
        }

        return $path_list[1];
    }

    /**
     * mkdirForTmp
     * 创建临时目录
     *
     * @author haicheng
     */
    static function mkdirForTmp() {
        $path = Yaf_Registry::get('config')->creative->upload->upload_path;
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
    }


    private static $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private static $encodeBlockSize = 7;

    private static $decodeBlockSize = 4;

    /**
     * 将mid从10进制转换成62进制字符串
     *
     * @param    string $mid
     *
     * @return    string
     */
    static function from10to62($mid) {
        $str      = "";
        $midlen   = strlen($mid);
        $segments = ceil($midlen / self::$encodeBlockSize);
        $start    = $midlen;
        for ($i = 1; $i < $segments; $i += 1) {
            $start -= self::$encodeBlockSize;
            $seg = substr($mid, $start, self::$encodeBlockSize);
            $seg = self::encodeSegment($seg);
            $str = str_pad($seg, self::$decodeBlockSize, '0', STR_PAD_LEFT) . $str;
        }
        $str = self::encodeSegment(substr($mid, 0, $start)) . $str;

        return $str;
    }


    /**
     * 将10进制转换成62进制
     *
     * @param    string $str 10进制字符串
     *
     * @return    string
     */
    private static function encodeSegment($str) {
        $out = '';
        while ($str > 0) {
            $idx = $str % 62;
            $out = substr(self::$string, $idx, 1) . $out;
            $str = floor($str / 62);
        }

        return $out;
    }

    /**
     * Method  arrayKeySort
     * 根据指定key对二维数组重排序
     *
     * @author guangling1<guangling1@staff.weibo.com>
     * @static
     *
     * @param array  $arr
     * @param string $key
     * @param string $sort
     *
     * @return array
     */
    static function arrayKeySort(array $arr, $key, $sort = 'DESC') {
        $keys_value = array();
        $new_array  = array();
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $keys_value[$k] = $v[$key];
            }

            if ($sort == 'DESC') {
                arsort($keys_value);
            } else {
                rsort($keys_value);
            }
            reset($keys_value);

            foreach ($keys_value as $k => $v) {
                $new_array[$k] = $arr[$k];
            }
        }

        return $new_array;
    }

    /**
     * Method  JsonStingToArray
     * 将数组中的string型的json数据转为数组
     * @author guangling1<guangling1@staff.weibo.com>
     * @static
     *
     * @param array $arr
     *
     * @return array
     */
    static function JsonStingToArray(array $arr) {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $arr[$k] = static::JsonStingToArray($v);
            } elseif (is_string($v) && static::is_json($v)) {
                $arr[$k] = json_decode($v,true);
            }
        }

        return $arr;
    }

    /**
     * Method  is_json
     * 判断是否为合法json
     * @author guangling1<guangling1@staff.weibo.com>
     * @static
     *
     * @param $string
     *
     * @return bool
     */
    static function is_json($string) {
        $result = json_decode($string,true);

        return (json_last_error() == JSON_ERROR_NONE) && is_array($result);
    }

    /**
     * Method  SplitKeyValue
     * 将key:value结构数组转为 ["$key"=>key,"$value"=>value] 结构
     * @author guangling1<guangling1@staff.weibo.com>
     * @static
     *
     * @param array  $arr
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    static function splitKeyValue(array $arr,$key = 'name',$value = 'value') {
        $res = array();
        foreach($arr as $k => $v){
            $temp_arr = array();
            if(is_array($v)){
                $temp_arr[$key] = $k;
                $temp_arr[$value] = static::splitKeyValue($v,$key,$value);
                $res[] = $temp_arr;
            }else{
                if(is_int($k) && is_string($v)){
                    $temp_arr[$value] = $k;
                    $temp_arr[$key] = $v;
                }else{
                    $temp_arr[$key] = $k;
                    $temp_arr[$value] = $v;
                }
                $res[] = $temp_arr;
            }
        }
        return $res;
    }

    /**
     * Method  getArrayValueDeep
     * 从多维数组提取kv键值对
     * @author guangling1<guangling1@staff.weibo.com>
     * @static
     *
     * @param array $arr
     *
     * @return array
     */
    static function getArrayValueDeep(array $arr){
        $ret = [];

        foreach($arr as $k => $v){
            if(is_array($v)){
                $ret = $ret + self::getArrayValueDeep($v);
            }else{
                $ret[$k] = $v;
            }
        }

        return $ret;
    }

    /**
     * Method  is_assoc
     * 判断数组是否为关联数组
     * @author guangling1<guangling1@staff.weibo.com>
     * @static
     *
     * @param $arr
     *
     * @return bool
     */
    static function is_assoc($arr){
        return is_array($arr) && (array_keys($arr) !== range(0, count($arr) - 1) || (bool)count(array_filter(array_keys($arr), 'is_string')));
    }

    /**
     * param
     * 格式化参数
     *
     * @author haicheng
     * @return null|string
     *
     * @param        $val
     * @param null   $is_not_exit
     * @param string $type
     */
    static function param($val, $is_not_exit = null, $type = 'STRING') {
        $val = trim($val);
        switch ($type) {
            case 'INT':
                $val = $val + 0;
                $val = (empty($val) && ($val !== 0)) ? $is_not_exit : $val;
                break;
            case 'STRING':
                $val = ( string )$val;
                $val = empty($val) ? $is_not_exit : $val;
                break;
        }

        return $val;
    }

    /**
     * getShortUrlFromText
     * 从文案中取出短链
     *
     * @author haicheng
     * @return bool
     *
     * @param null $text
     */
    static function getShortUrlFromText($text = null){
        $pattern = '/http:\/\/t\.cn\/[a-zA-Z0-9]{7}/i';
        preg_match($pattern, $text, $matches);

        if(isset($matches[0]) && !empty($matches[0])){
            return $matches[0];
        }
        return false;
    }

    static function getConfig($name) {
        $cache = Yaf_Registry::get('_config_'.$name);
        if ($cache) {
            return $cache;
        }

        $path = ROOT_PATH.'/conf/'.$name.'.php';
        //echo $path;exit;
        if (file_exists($path)) {
            $cache = require "$path";
        } else {
            $cache = [];
        }
        Yaf_Registry::set('_config_'.$name, $cache);
        return $cache;
    }
	
	/**
	 * renderStatusOfCreative
	 * 呈现最终创意状态
	 *
	 * @author: haicheng
	 *
	 * @param $configuredStatus
	 * @param $effectiveStatus
	 *
	 * @return null
	 */
    static function renderStatusOfCreative($configuredStatus, $effectiveStatus){
        $result_of_status = $effectiveStatus;
        
        //用户设置暂停，将最终状态中的正常、发布中渲染成暂停
        if($configuredStatus == \Constants\V1\CreativeConfiguredStatus::PAUSE_STATUS){
	        if(in_array($effectiveStatus, [
	        	\Constants\V1\CreativeStatus::NORMAL,
	            \Constants\V1\CreativeStatus::PUBLISHING
	        ])){
		        $result_of_status = \Constants\V1\CreativeStatus::PARSED;
	        }
        }
        
        return $result_of_status;
    }

    /**
     * urlencode
     * 只对参数做encode
     *
     * @author: haicheng
     *
     * @param $url
     *
     * @return string
     */
    static function urlencode ($url) {
        $parsed_url = parse_url($url);

        if (!empty($parsed_url['query'])) {
            $parsed_url['query'] = urlencode($parsed_url['query']);
        }

        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    static function getMid($str) {
        $mid = 0;
        $str = trim($str);
        // 62进制
        $pattern = "/^[a-zA-Z0-9]+$/";
        // 10进制
        $pattern_nums = "/^[0-9]+$/";
        // 传入链接（PC端）,Exp: http://weibo.com/1719071655/Brl296Okw
        $pattern_url = "/weibo\\.(com|cn)\\/[^\\/]+\\/([a-zA-Z0-9]+)/";
        // 传入链接（m.weibo.cn）,Exp: http://m.weibo.cn/1824131445/3765576058623631
        $pattern_url_m = "/m\\.weibo\\.cn\\/[0-9]+\\/([0-9]+)/";
        // 传入链接（weibo.cn, repost|comment|attitude）,Exp: http://weibo.cn/attitude/Brl296Okw?rl=1#attitude
        $pattern_url_wap = "/weibo\\.cn\\/(repost|comment|attitude)\\/([a-zA-Z0-9]+)/";

        // 62进制转化成10进制
        if (preg_match($pattern, $str) && !preg_match($pattern_nums, $str)) {
            $mid = MIDConverter::from62to10($str);
        } elseif (preg_match($pattern_nums, $str)) {
            $mid = $str;
        } elseif (preg_match($pattern_url_wap, $str, $matches)) {
            $mid = MIDConverter::from62to10($matches[2]);
        } elseif (preg_match($pattern_url_m, $str, $matches)) {
            $mid = $matches[1];
        } elseif (preg_match($pattern_url, $str, $matches)) {
            $mid = MIDConverter::from62to10($matches[2]);
        }

        return $mid;
    }
}