<?php
//$fp = fopen("php://memory", 'r+');
//fputs($fp, "hello world!\n");
//rewind($fp);
//while (!feof($fp)) {
//    echo fread($fp, 1024);
//}
//fclose($fp);
//
//if(isset($argv)){
//    print_r($argv);
//}

echo date("Y-m-d H:m:s", time());
echo " ";
echo floor(microtime() * 1000);
echo "\n";
$mtime = explode(" ", microtime());
$mtime = $mtime[1] . ($mtime[0] * 1000);
$mtime2 = explode(".", $mtime);
$mtime = $mtime2[0];
echo $mtime;
echo "\n";
$urls = array(
    'http://www.webkaka.com',
    'http://www.webkaka.com',
    'http://www.webkaka.com',
    'http://www.webkaka.com',
    'http://www.webkaka.com');
async_get_url($urls); // [0] => example1, [1] => example2
echo "\n";
echo date("Y-m-d H:m:s", time());
echo " ";
echo floor(microtime() * 1000);
echo "\n";
$mtime_ = explode(" ", microtime());
$mtime_ = $mtime_[1] . ($mtime_[0] * 1000);
$mtime2_ = explode(".", $mtime_);
$mtime_ = $mtime2_[0];
echo $mtime_;
echo "\n";
echo $mtime_ - $mtime;

function async_get_url($url_array, $wait_usec = 0)
{
    if (!is_array($url_array))
        return false;
    $wait_usec = intval($wait_usec);
    $data = array();
    $handle = array();
    $running = 0;
    $mh = curl_multi_init(); // multi curl handler
    $i = 0;
    foreach ($url_array as $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
        curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
        curl_multi_add_handle($mh, $ch); // 把 curl resource 放进 multi curl handler 里
        $handle[$i++] = $ch;
    }
    /* 执行 */
    do {
        curl_multi_exec($mh, $running);
        if ($wait_usec > 0) /* 每个 connect 要间隔多久 */
            usleep($wait_usec); // 250000 = 0.25 sec
    } while ($running > 0);
    /* 读取资料 */
    foreach ($handle as $i => $ch) {
        $content = curl_multi_getcontent($ch);
        $data[$i] = (curl_errno($ch) == 0) ? $content : false;
    }
    /* 移除 handle*/
    foreach ($handle as $ch) {
        curl_multi_remove_handle($mh, $ch);
    }
    curl_multi_close($mh);
    return $data;
}