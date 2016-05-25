<?php
require_once __DIR__ . "/TestCase.php";


class CampaignTest extends TestCase
{

    private $cookies;

    public function __construct()
    {
		$this->cookies = 'Cookie:USRANIME=; WBStore=; SINAGLOBAL=5410088784992.695.1417794825482; __utma=15428400.1900445615.1420527592.1422601103.1428044523.5; __utmz=15428400.1428044523.5.4.utmcsr=verified.weibo.com|utmccn=(referral)|utmcmd=referral|utmcct=/verify; wvr=6; SUS=SID-1963741144-1437275158-JA-i5poe-9424e127581edd06d7b1c4fbf8614e12; SUE=es%3Da8a382152f41404540b3c59ca06ebce8%26ev%3Dv1%26es2%3D6ad074525872c21a37515267618bb7b3%26rs0%3DYQ5aRoTQree5e%252F9N570A%252Fm5M5XZGGRNUMe9eAjdpDR0Y3tdutV%252BKfrAvyDoqLpl%252FXy74Cj8oCF35YZDHPyiYf2DX2SmkIL32RwdTIjzkumhj4RN7WC7YEzwT6ZcBu8n4NK0fYpcdEGK8iFeXFTb8MSqxI2n2or4uL61HRj%252Fk0W8%253D%26rv%3D0; SUP=cv%3D1%26bt%3D1437275158%26et%3D1437361558%26d%3Dc909%26i%3D4e12%26us%3D1%26vf%3D0%26vt%3D0%26ac%3D0%26st%3D0%26uid%3D1963741144%26name%3D258066364%2540qq.com%26nick%3D%25E5%258D%2581%25E4%25B8%2580%26fmp%3D%26lcp%3D2012-10-05%252000%253A52%253A47; SUB=_2A254r2RGDeTxGedH7VEW9C_NzziIHXVb3dKOrDV8PUNbu9AMLXHhkW-e_XZL5pFo4yzio4IR9SR-GN6DnA..; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WWLRy.mGdv_YpyybTqkgKWw5JpX5Kzt; SUHB=0laEEhJfkOO7rK; ALF=1468811157; SSOLoginState=1437275158; _s_tentry=login.sina.com.cn; Apache=7500598500482.738.1437275160887; ULV=1437275160898:209:26:1:7500598500482.738.1437275160887:1437232283594; UOR=www.iguanwang.com,widget.weibo.com,www.yuansir-web.com';
    }

    public function testIndex()
    {

    }

    public function testStoreDelete()
    {
        $data1 = [
            'name' => time(), //名称
            'creative_id' => 60, //创意ID
            'budget' => 500, // 预算
            'price' => 20, //出价
            'start_time' => '2015-08-01 09:40:00', // 开始时间 如果开始时间==1999-01-01 00:00:00  表示立即开始
            'end_time' => '2970-01-01 00:00:00', // 结束时间
            //opc
            'fans' => '["1662047260","2977214740"]', // 定向ID
            'age[start]' => '8', //年龄
            'age[end]' => '78',
            'gender' => '401',
            'location' => '-1',
            'device' => '110201',
            'network_type[]' => '1204',
            'talking_data_url' => 'http://www.baidu.com', //talking data url
            'ge_tui_url' => 'http://www.ba222idu.com', // 个推 url
            'customer_id' => 3779606125,
        ];

        $result = Curl::post('http://local.app.weibo.com/app/campaigns?_is_ajax=1', $data1, [$this->cookies]);
        echo $result;
        $this->assertEquals(201, Curl::getHttpCode());
//
//        $data1 = [
//            'customer_id' => 0,
//            '_method' => 'delete'
//        ];
//
//        $result = Curl::post('http://suchong.fst.weibo.com/creatives/5?_is_ajax=1', $data1, [$this->cookies]);
//        $this->assertEquals(200, Curl::getHttpCode());
//        echo $result;
//
//        $data1 = [
//            'customer_id' => 0,
//            '_method' => 'put',
//            'disable_comment' => 0
//        ];
//
//        $result = Curl::post('http://suchong.fst.weibo.com/creatives/4?_is_ajax=1', $data1, [$this->cookies]);
//        echo $result;
//        $this->assertEquals(200, Curl::getHttpCode());

    }

    public function testUpdate()
    {
//        $data1 = [
////            'type' => 1,//1修改计划状态
//            'name' => 'test', //名称
//            'creative_id' => 46, //创意ID
//            'budget' => 50, // 预算
//            'price' => 2, //出价
//            'start_time' => '1970-01-01 00:00:00', // 开始时间 如果开始时间==1999-01-01 00:00:00  表示立即开始
//            'end_time' => '2970-01-01 00:00:00', // 结束时间
//            //opc
//            'fans' => '["1662047260","2977214740"]', // 定向ID
//            'age[start]' => '8', //年龄
//            'age[end]' => '78',
//            'gender' => '401',
//            'location[0]' => '339',
//            'location[1]' => '340',
//            'device' => '110201',
//            'network_type[]' => '1204',
//            'talking_data_url' => 'http://www.baidu.com', //talking data url
//            'ge_tui_url' => 'http://www.ba222idu.com', // 个推 url
//            'customer_id' => 3779606125,
//            '_method' => 'PUT'
//        ];
////
//        $result = Curl::post('http://suchong.fst.weibo.com/app/campaigns/142?_is_ajax=1', $data1, [$this->cookies]);
//        echo $result;
//        $this->assertEquals(200, Curl::getHttpCode());

//        $data1 = [
//            'type' => 1,//1修改计划状态
//            'status' => 2,
//            'customer_id' => 3779606125,
//            '_method' => 'PUT'
//        ];
//
//        $result = Curl::post('http://suchong.fst.weibo.com/app/campaigns/142?_is_ajax=1', $data1, [$this->cookies]);
//        echo $result;
//        $this->assertEquals(200, Curl::getHttpCode());


//        $data1 = [
//            'type' => 1,//1修改计划状态
//            'customer_id' => 3779606125,
//            '_method' => 'delete'
//        ];
//
//        $result = Curl::post('http://suchong.fst.weibo.com/app/campaigns/153?_is_ajax=1', $data1, [$this->cookies]);
//        echo $result;
//        $this->assertEquals(200, Curl::getHttpCode());

    }

}
