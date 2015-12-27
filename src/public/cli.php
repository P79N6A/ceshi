<?php
/* example: php cli.php "request_uri=/command/campaign/index" */

define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APPLICATION_PATH', ROOT_PATH.'/application');

require __DIR__.'/../vendor/autoload.php';


$app = new Yaf_Application(__DIR__.'/../config/application.ini');

$response = $app->bootstrap()->getDispatcher()->dispatch(new Yaf_Request_Simple());