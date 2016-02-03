<?php
namespace Db;

class Db
{
    private static $_config = array(
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'dytt',
    );

    private static $_conn = null;

    /**
     * 获取url
     * @param int $dep
     * @return array|null
     */
    public static function getAllUrls($dep = 0)
    {
        if (empty(self::$_conn)) {
            self::$_conn = new \Simplon\Mysql\Mysql(
                self::$_config['host'],
                self::$_config['user'],
                self::$_config['password'],
                self::$_config['database']
            );
        }
        $result = self::$_conn->fetchRowMany('SELECT * FROM urls WHERE dep=:dep', array('dep' => $dep));
        return $result;
    }

    /**
     * 创建 url
     * @param $url
     * @param int $dep
     * @return bool|int
     * @throws \Simplon\Mysql\MysqlException
     */
    public static function createUrl($url, $dep = 0)
    {
        if (empty(self::$_conn)) {
            self::$_conn = new \Simplon\Mysql\Mysql(
                self::$_config['host'],
                self::$_config['user'],
                self::$_config['password'],
                self::$_config['database']
            );
        }

        $data = array(
            'url_md5'   => md5($url),
            'url' => $url,
            'dep'  => $dep,
        );
        $id = self::$_conn->insert('urls', $data);
        return $id;
    }
}
