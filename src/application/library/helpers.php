<?php

/**
 * Method  get_current_page_url
 * 获取当前页面的URL
 *
 * @author yangyang3
 * @return bool|string
 */
if (!function_exists('get_current_page_url')) {
    function get_current_page_url()
    {
        if (!isset($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $_SERVER['REQUEST_URI'])) {
            return false;
        }

        $url = 'http';

        if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') {
            $url .= 's';
        }
        $url .= '://';

        if ((int)$_SERVER['SERVER_PORT'] !== 80) {
            $url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }

        return $url;
    }
}

if (!function_exists('get_current_page_uri')) {
    function get_current_page_uri()
    {
        $prefix = getenv('PROJECT_NAME');
        $uri = preg_replace('/\?.*/e', '', preg_replace("/^\\/$prefix/e", '', $_SERVER['REQUEST_URI']));
        return empty($uri) ? '/' : $uri;
    }
}

if (!function_exists('array_rebuild')) {
    /**
     * @name array_rebuild
     * @desc 通过新的Key重建数组索引
     * @author yangyang3
     * @param array $array
     * @param string $key
     * @return array $data
     */
    function array_rebuild(array $array, $key) {
        $data = array();

        if (empty($array) || empty($key)) {
            return $data;
        }

        foreach ($array as $info) {
            if (isset($info[$key])) {
                $data[$info[$key]] = $info;
            }
        }

        return $data;
    }
}

/**
 * 判断是否是cli
 */
if (!function_exists('is_cli')) {
    function is_cli()
    {
        return (php_sapi_name() === 'cli') ? true : false;
    }
}

/**
 * Method  get_client_ip
 * 获取客户端IP
 *
 * @author yangyang3
 * @return bool|string
 */
if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        //验证HTTP头中是否有REMOTE_ADDR
        if (!isset($_SERVER['REMOTE_ADDR'])) {
            return '127.0.0.1';
        }

        //验证是否为非私有IP
        if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            return $_SERVER['REMOTE_ADDR'];
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
}

if (!function_exists('array_column')) {
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                         a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                         the returned array. This value may be the integer key
     *                         of the column, or it may be the string key name.
     *
     * @return array
     * @link http://www.php.net/manual/en/function.array-column.php  since php 5.5.0
     * @link https://github.com/ramsey/array_column/blob/master/src/array_column.php
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);

            return null;
        }

        //add by wenyue1
        if (empty($params[0]) || empty($params[1])) {
            return array();
        }
        //added

        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );

            return null;
        }

        if (!is_int($params[1]) && !is_float($params[1]) && !is_string(
                $params[1]
            ) && $params[1] !== null && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);

            return false;
        }

        if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2]) && !(is_object(
                    $params[2]
                ) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);

            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string)$params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int)$params[2];
            } else {
                $paramsIndexKey = (string)$params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {

            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string)$row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }
}

if (!function_exists('is_ajax')) {
    function is_ajax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' || Base\Request::input(
                '_is_ajax'
            )
        ) {
            return true;
        }
        return false;
    }
}

if (!function_exists('set_status')) {
    /**
     * @param $code
     */
    function set_status($code)
    {
        switch ($code) {
            case 200:
                @header("HTTP/1.1 $code OK");
                break;
            case 201:
                @header("HTTP/1.1 $code Created");
                break;
            case 202:
                @header("HTTP/1.1 $code Accepted");
                break;
            case 204:
                @header("HTTP/1.1 $code No Content");
                break;
            case 400:
                @header("HTTP/1.1 $code Bad Request");
                break;
            case 401:
                @header("HTTP/1.1 $code Unauthorized");
                break;
            case 403:
                @header("HTTP/1.1 $code Forbidden");
                break;
            case 404:
                @header("HTTP/1.1 $code Not Found");
                break;
            case 406:
                @header("HTTP/1.1 $code Not Acceptable");
                break;
            case 410:
                @header("HTTP/1.1 $code Gone");
                break;
            case 422:
                @header("HTTP/1.1 $code Unprocessable Entity");
                break;
            case 500:
                @header("HTTP/1.1 $code Internal Server Error");
                break;
            default:
                @header("HTTP/1.1 $code");
        }
    }
}

if (!function_exists('abort')) {
    function abort($code, $message = '', array $headers = array())
    {
        set_status($code);
        if (is_ajax()) {
            @header('Content-type: application/json');
            foreach ($headers as $header) {
                @header($header);
            }
            echo json_encode(
                array(
                    'error' => $message
                )
            );
            exit;
        } else {
            $view = new Base\View();
            if ($code === 404) {
                $view->display('errors.404', array('message' => $message));
            } elseif ($code === 403) {
                $view->display('errors.403', array('message' => $message));
            } elseif ($code === 405) {
                $view->display('errors.405', array('message' => $message));
            } else {
                $view->display('errors.other', array('message' => $message));
            }
        }
        exit;
    }
}

if (!function_exists('redirect')) {
    function redirect($path, $code=302)
    {
        set_status($code);
        @header("Location: $path");
        exit;
    }
}

if (!function_exists('init_csv')) {
    function init_csv($name)
    {
        header("Content-Type: text/csv" );
        header("Content-Disposition: attachment; filename=" .  iconv("UTF-8", "gbk", $name) . ".csv");
        header('Content-Transfer-Encoding: binary');
    }
}

/**
 * Method  underline_to_camel
 * 下划线转驼峰
 *
 * @author yangyang3
 *
 * @param $string
 *
 * @return string
 */
function underline_to_camel($string) {
    return preg_replace('/_([a-zA-Z])/e', "strtoupper('\\1')", $string);
}


/**
 * 字符串长度
 */
if (!function_exists('get_chinese_string_length')) {
    function get_chinese_string_length($string)
    {
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
}
