<?php

define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APPLICATION_PATH', ROOT_PATH . '/application');
header('Access-Control-Allow-Origin:*');

require ROOT_PATH . '/vendor/autoload.php';

$app = new Yaf_Application(ROOT_PATH . '/config/application.ini');
$app->bootstrap()->run();
