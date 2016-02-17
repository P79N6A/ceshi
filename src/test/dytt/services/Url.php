<?php
/**
 * 电影天堂 测试爬取
 */

require "../vendor/autoload.php";
require "Db.php";

use \Snoopy\Snoopy;
use \Db\Db;

class Url
{

    public static function fetch($url = '')
    {
        $preg = '/^http:\/\/www\.dytt8\.net\/html\/[\w\/]+\/[\d]{8}\/[\d]{5}\.html$/i';
        if (preg_match($preg, $url)) {
            try {
                Db::createUrl($url);
            } catch (Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }

    }
}