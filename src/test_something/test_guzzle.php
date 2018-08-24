<?php
/**
 * 数据库伪造
 */
require '../vendor/autoload.php';

use GuzzleHttp\Client;

$cookies = new GuzzleHttp\Cookie\FileCookieJar();
$cookies->setCookie(GuzzleHttp\Cookie\SetCookie::fromString('SINAGLOBAL=9352636269803.89.1485228667367; __gads=ID=9a9ef2bdc0988ee0:T=1488350135:S=ALNI_MZsEDScNaUH6nx33dIb3qsOjui6Hw; _ga=GA1.2.1069114177.1488350135; pgv_pvi=7246213120; user_id=201192; _s_tentry=login.sina.com.cn; Apache=1635755892221.4531.1532050355874; ULV=1532050355927:47:2:1:1635755892221.4531.1532050355874:1531188070756; ULOGIN_IMG=15335209347627; login_sid_t=941e940745300b12455d6254b0990692; cross_origin_proto=SSL; SSOLoginState=1533781399; hash=f76ab39b172fc077b8f5d0b503c9e56f; UOR=,,login.sina.com.cn; SCF=AqUNHsIuHAz2zzznAwCFYPOSu_kWb-Xi8q4-axXZCOB23w9KRZzvEJZF24NT4CxlxaSUg4zJIumF7QUtkG3Tmm0.; SUB=_2A252ekhLDeRhGedN7lET9yfPwjiIHXVVDj6DrDV8PUJbmtAKLUfBkW9NWkYZ3IPfsSCjRAW00TFgq2stqZwhnNdb; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WW72LhCBF8Pk15F7LC7Qmxc5JpX5K-hUgL.Fo20SKeES0.01KB2dJLoI7yoIsSQdgp_d5tt; SUHB=0A0YruT3N45mDv; ALF=1566534554; wvr=6'));

$client = new Client([
    'base_uri' => 'http://ka.test.weibo.com/',
    'timeout'  => 20,
    'debug'    => true,
    'cookies'  => $cookies
]);

$response = $client->request('GET', '/ajax/creative/list.json');

echo $response->getBody();