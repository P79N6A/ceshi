<?php

require 'vendor/autoload.php';

$xml = '<?xml version="1.0" encoding="utf-8"?>     <result>         <imgurl>sinaweibo://searchall?containerid=231522&q=%23%E4%BB%A4%E4%BA%BA%E7%AA%92%E6%81%AF%E7%9A%84%E8%84%B8%23</imgurl>         <err>0</err>         <desc>成功</desc>         <adid>ad_5b3050db60901</adid>     </result>';

$service = new Sabre\Xml\Service();
$result = $service->parse($xml);

print_r($result);