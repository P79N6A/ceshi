<?php
require_once  '../../vendor/autoload.php';
use Workerman\Worker;

$worker = new Worker('tcp://0.0.0.0:8484');
$worker->onWorkerStart = function($worker)
{
    // 将db实例存储在全局变量中(也可以存储在某类的静态成员中)
    global $db;
    $db = new \Workerman\MySQL\Connection('127.0.0.1', '3307', 'root', '', 'ka_delivery');
};
$worker->onMessage = function($connection, $data)
{
    // 通过全局变量获得db实例
    global $db;
    // 执行SQL
    $all_tables = $db->query('show tables');
    $connection->send(json_encode($all_tables));
};
// 运行worker
Worker::runAll();