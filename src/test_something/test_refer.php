<?php

require 'vendor/autoload.php';


\Ceshi\Log::info('hello...');
exit;

$url = 'https://busuanzi.ibruce.info/busuanzi?jsonpCallback=BusuanziCallback_904917625059';

$header = [
	'Cookie'     => 'busuanziId=B4913FCD54C1473497E27A39B1E315F1',
	'Host'       => 'busuanzi.ibruce.info',
	'Pragma'     => 'no-cache',
	'Referer'    => 'https://chchmlml.github.io/%E9%9D%A2%E8%AF%95%E6%80%BB%E7%BB%93/2017/08/22/about-interview-question-1.html',
	'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36',
];

$result = \Ceshi\Curl::get($url, null, $header);
var_dump($result);