<?php
header("Content-type: text/html; charset=utf-8");
/**
 * 处理代理商那个页面
 * User: haicheng
 * Date: 15-9-24
 * Time: 下午2:40
 */
ini_set("display_errors", "off");
include 'PHPExcel_1.8.0/Classes/PHPExcel.php';
include 'PHPExcel_1.8.0/Classes/PHPExcel/Calculation.php';
include 'PHPExcel_1.8.0/Classes/PHPExcel/Cell.php';


$e_target = get_excel_target();
$e_source = get_excel_source();
$all = 0;
$succ = 0;
$new = 0;
$e_new = array();
echo count($e_source),'<br>';
foreach($e_target as $key_t => $val){
    $all++;
    foreach($e_source as $key => $val_source){
        if($val[2] == $val_source[2]){
            $succ++;
            $e_target[$key_t][3] = $val_source[3];
            unset($e_source[$key]);
        }
    }
}
$result = array_merge($e_target, $e_source);
echo $all,'---',$succ,'----',count($e_source),'<br>';
render($result);



function render($result){
    $agent_region = array();
    foreach($result as $val){
        $agent_region[$val[0]][] = array($val[1],$val[2],$val[3]);
    }
    echo '<table>';
    echo '<tbody>';
    foreach($agent_region as $key => $data){

        foreach($data as $line => $info){
            if($line == 0){
                echo "<tr>
                <td id=\"{$key}\"  class=\"region\" rowspan=\"".count($data)."\">{$key}</td>
                <td>{$info[0]}</td>
                <td>{$info[1]}</td>
                <td>{$info[2]}</td>
            </tr>";
            }else{
                echo "<tr>
                <td>{$info[0]}</td>
                <td>{$info[1]}</td>
                <td>{$info[2]}</td>
            </tr>";
            }
        }
    }
    echo '</tbody>';
    echo '<table>';
}


function get_excel_target(){
    $agent_region = null;

    $filePath = '/Users/haicheng/wwwroot/ceshi/excel_tar.xlsx';

    $PHPReader = new PHPExcel_Reader_Excel2007();
    $PHPExcel = $PHPReader->load($filePath);
    /**读取excel文件中的第一个工作表*/
    $currentSheet = $PHPExcel->getSheet(0);
    /**取得最大的列号*/
    $allColumn = $currentSheet->getHighestColumn();
    /**取得一共有多少行*/
    $allRow = $currentSheet->getHighestRow();
    /**从第二行开始输出，因为excel表中第一行为列名*/
    for($currentRow = 1;$currentRow <= $allRow;$currentRow++){
        /**从第A列开始输出*/
        $a = null;
        $column_data = array();
        for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){
            $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/
            $column_data[] = $val;
        }
        $agent_region[] = $column_data;
    }
    foreach($agent_region as $key1 => $val1){
        if(empty($val1[0])){
            $agent_region[$key1][0] = $agent_region[$key1-1][0];
        }
    }
    return $agent_region;
}


function get_excel_source(){
    $agent_region = null;

    $filePath = '/Users/haicheng/wwwroot/ceshi/excel.xlsx';

    $PHPReader = new PHPExcel_Reader_Excel2007();
    $PHPExcel = $PHPReader->load($filePath);
    /**读取excel文件中的第一个工作表*/
    $currentSheet = $PHPExcel->getSheet(0);
    /**取得最大的列号*/
    $allColumn = $currentSheet->getHighestColumn();
    /**取得一共有多少行*/
    $allRow = $currentSheet->getHighestRow();
    /**从第二行开始输出，因为excel表中第一行为列名*/
    for($currentRow = 1;$currentRow <= $allRow;$currentRow++){
        /**从第A列开始输出*/
        $a = null;
        $column_data = array();
        for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){
            $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/
            $column_data[] = $val;
        }
        $agent_region[] = $column_data;
    }
    return $agent_region;
}
