<?php namespace Base;

use Yaf_Registry;

class Request
{


    /**
     * 获取request 数据
     * @param $name
     * @param null $if_not_exist
     * @param bool $security
     * @return null|string
     */
    public static function input($name, $if_not_exist = null, $security = true)
    {
        $request = Yaf_Registry::get('_REQUEST');
        if (isset($request[$name])) {
            $string = $request[$name];
        } else {
            $string = isset($_REQUEST[$name]) ? $_REQUEST[$name] : $if_not_exist;
        }

        if ($security && isset($string)) {
            if (is_array($string)) {
                return $string;
            }
            $string = htmlspecialchars($string, ENT_QUOTES);
        }
        return $string;
    }

    /**
     * 判断param是否存在
     * @param $name
     * @return bool
     */
    public static function has($name)
    {
        $request = Yaf_Registry::get('_REQUEST');
        if (isset($request[$name])) {
            return true;
        } else {
            return isset($_REQUEST[$name]) ? true : false;
        }
    }

    /**
     * 获取全部request数据
     * @return mixed
     */
    public static function all()
    {
        $request = $_REQUEST;
        $storage = Yaf_Registry::get('_REQUEST');
        if (!empty($storage)) {
            foreach ($storage as $key => $value) {
                $request[$key] = $value;
            }
        }

        return $request;
    }

    public static function setParam($name, $value)
    {
        $request = Yaf_Registry::get('_REQUEST');
        $request[$name] = $value;
        Yaf_Registry::set('_REQUEST', $request);
    }

    public static function delete($name)
    {
        $request = Yaf_Registry::get('_REQUEST');
        unset($request[$name]);
        unset($_REQUEST[$name]);
        Yaf_Registry::set('_REQUEST', $request);
    }

    public static function getPagination($default_per_page=30, $max_per_page=1000)
    {
        $page = intval(Request::input('page', 1));
        $per_page = intval(Request::input('per_page', $default_per_page));
        $per_page = ($per_page > $max_per_page) ? $max_per_page : $per_page;

        return [$page, $per_page];
    }

    //@todo get post
}