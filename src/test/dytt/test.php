<?php
/**
 * 电影天堂 测试爬取
 */

require "../vendor/autoload.php";

use \Snoopy\Snoopy;

$snoopy = new Snoopy;
$snoopy->proxy_port = "80";
$snoopy->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)";
$snoopy->rawheaders["Pragma"] = "no-cache"; //cache 的http头信息
$snoopy->read_timeout = 10;
$href = 'http://www.dytt8.net/html/tv/hytv/20151102/49409.html';
$snoopy->fetch($href);
$html = str_get_html($snoopy->results);

$pattern="/.*?<title>(.*?)<\/title>.*?/";
if(preg_match_all($pattern, $html, $matches)){
    var_dump($matches[1]);
}


//http://www.dytt8.net/html/gndy/jddy/20151207/49699.html
//落地页
//$preg = '/^http:\/\/www\.dytt8\.net\/html\/[\w\/]+\/[\d]{8}\/[\d]{5}\.html$/i';
//$source_urls = $snoopy->results;
//if (empty($source_urls)) {
//    echo 'no find';
//    exit;
//}
//$data_url = array();
//foreach ($source_urls as $key => $url) {
//    if (preg_match($preg, $url)) {
//        try {
//            Db::createUrl($url);
//        } catch (Exception $e) {
//            echo $e->getMessage() . "\n";
//        }
//    }
//
//}

//var_dump($data_url);

//$result = Db::getAllUrls();
//var_dump($result);
exit('done');