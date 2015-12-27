<?php


class Config {

    public static function get($name)
    {
        $cache = Yaf_Registry::get('_config_'.$name);
        $path = ROOT_PATH.'/config/'.$name.'.php';
        if ($cache) {
            return $cache;
        }

        if (file_exists($path)) {
            $cache = require "$path";
        } else {
            $cache = [];
        }
        Yaf_Registry::set('_config_'.$name, $cache);
        return $cache;
    }
}