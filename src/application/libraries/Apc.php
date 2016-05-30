<?php

class Apc {
    /**
     *Apc缓存-设置缓存
     *设置缓存key，value和缓存时间
     *
     * @param string $key KEY值
     * @param        $value
     * @param int    $time
     *
     * @internal param string $value值
     * @internal param string $time缓存时间
     * @return array|bool
     */
    public static function set_cache($key, $value, $time = 0) {
        if ($time == 0) {
            $time = null;
        } //null情况下永久缓存
        return apc_store($key, $value, $time);;
    }

    /**
     *Apc缓存-获取缓存
     *通过KEY获取缓存数据
     *
     * @param string $key KEY值
     *
     * @return mixed
     */
    public static function get_cache($key) {
        return apc_fetch($key);
    }

    /**
     *Apc缓存-清除一个缓存
     *从memcache中删除一条缓存
     *
     * @param string $key KEY值
     *
     * @return bool|string[]
     */
    public static function clear($key) {
        return apc_delete($key);
    }

    /**
     *Apc缓存-清空所有缓存
     *不建议使用该功能
     *
     * @return bool
     */
    public static function clear_all() {
        return apc_clear_cache(); //清楚缓存
    }

    /**
     *检查APC缓存是否存在
     *
     * @param string $key KEY值
     *
     * @return bool|string[]
     */
    public static function exists($key) {
        return apc_exists($key);
    }

    /**
     *字段自增-用于记数
     *
     * @param string $key KEY值
     * @param        $step
     *
     * @return bool|int
     * @internal param int $step新增的step值
     */
    public static function inc($key, $step) {
        return apc_inc($key, (int)$step);
    }

    /**
     *字段自减-用于记数
     *
     * @param string $key KEY值
     * @param        $step
     *
     * @return bool|int
     * @internal param int $step新增的step值
     */
    public static function dec($key, $step) {
        return apc_dec($key, (int)$step);
    }

    /**
     *缓存文件
     */
    public static function cache_file($file) {
        return apc_compile_file($file);
    }

    /**
     *返回APC缓存信息
     */
    public static function info() {
        return apc_cache_info();
    }
}
