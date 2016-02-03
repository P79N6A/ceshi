<?php
/**
 * Created by IntelliJ IDEA.
 * User: haicheng
 * Date: 15/11/27
 * Time: 上午11:43
 */

$file = array(
    'code.log'
);
$data =array();
foreach($file as $f){
    $get = fopen($f, 'r');
    while (!feof($get)) {
        $row = trim(fgets($get));
        $tmp = explode(' 	',$row);
        if(count($tmp) === 3){
            $data[] = array(
                trim($tmp[0]),
                trim($tmp[2]),
            );
        }
    }
    fclose($get);
}
header('content-type:text/html;charset=utf-8;');
foreach($data as $code){
    echo '\'CODE_'.$code[0].'\' => \''.$code[1].'\',<br />';
}

//require 'vendor/autoload.php';
//
//
//$logger = Logger::getLogger("default");
//Logger::configure('config.xml');
//
//$logger->info("This is an informational message.数遍测试线中文");
//$logger->info("数遍测试线中文数遍测试线中文数遍测试线中文数遍测试线中文数遍测试线中文数遍测试线中文数遍测试线中文数遍测试线中文数遍测试线中文");
//$logger->info("遍测试线中文数遍测试线中文数遍测试线中文");

