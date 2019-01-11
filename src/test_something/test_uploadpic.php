<?php
/**
 * some comment...
 *
 * @author haicheng
 */
function https_request($url, $file = null)
{
    $cfile   = new CURLFile($file, 'image/jpg');
    $imgdata = ['media' => $cfile];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $imgdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $info = curl_exec($ch);
    curl_close($ch);

    return $info;
}

$file_path = realpath('./testupload.jpg');

$PROJECT = 'tuding';                                   //申请服务时分配的项目名称
$expires = time() + 3600;                              // 一小时后此验证失效
$SKEY    = "Lx6leizJnWHISXz1aULPS9LgAgRkrGO9";            // 申请服务时分配的密码

$str_to_sign = "POST\n";                               // HTTP-Verb
$str_to_sign .= md5_file($file_path) . "\n";  // Content-MD5
$str_to_sign .= "image/jpeg\n";                        // Content-Type
$str_to_sign .= "{$expires}\n";                        // 一小时后此签名失效
$str_to_sign .= "/unistore.service.weibo.com/";        // host + uri

/* 计算ssig */
$ssig = substr(base64_encode(hash_hmac('sha1', $str_to_sign, $SKEY, true)), 5, 10);
$ssig = urlencode($ssig);

// POST url
$url = "http://10.77.121.137:9999/?KID=unistore,{$PROJECT}&Expires={$expires}&ssig={$ssig}";

$r = https_request($url, $file_path);
var_dump($r);