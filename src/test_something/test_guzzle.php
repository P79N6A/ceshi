<?php
/**
 * 数据库伪造
 */
require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://ka.test.weibo.com/',
    'timeout'  => 20,
    'debug'    => true,
]);

$response = $client->request('GET', '/ajax/creative/list.json');

echo $response->geBody();