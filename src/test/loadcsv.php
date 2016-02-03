<?php
/**
 * Created by IntelliJ IDEA.
 * User: haicheng
 * Date: 16/1/26
 * Time: 上午10:26
 */

$data = array();
$f = 'uid.csv';
$get = fopen($f, 'r');
while (!feof($get)) {
    $row = trim(fgets($get));
    $data[] = $row;
}
fclose($get);


echo implode(',', $data);
exit;
