<?php
require_once __DIR__."/TestCase.php";

class WeiboTokenTest extends TestCase {


    public function testToken() {
        $_app_key    = '4009338982';
        $_app_secret = 'ffac76a449f5f2ec5a05e14097dcdee5';

        $post_data   = array('app_secret' => $_app_secret);
        $retry_count = 3;
        $url         = 'http://i2.api.weibo.com/auth/tauth_token.json?source=' . $_app_key;
        while (($retry_count--) > 0) {
            $result = Curl::post($url, $post_data);
            if (Curl::getHttpCode() === 200) {
                break;
            }
        }

        //code返回
        //$this->assertEquals(200, Curl::getHttpCode());

        $data = json_decode($result, true);
        $this->assertArrayHasKey('error_code', $data);
    }

    public function testToken2() {
        $_app_key    = '4009338982';
        $_app_secret = 'ffac76a449f5f2ec5a05e14097dcdee5';

        $post_data   = array('app_secret' => $_app_secret);
        $retry_count = 3;
        $url         = 'http://i2.api.weibo.com/auth/tauth_token.json?source=' . $_app_key;
        while (($retry_count--) > 0) {
            $result = Curl::post($url, $post_data);
            if (Curl::getHttpCode() === 200) {
                break;
            }
        }

        //code返回
        $this->assertEquals(200, Curl::getHttpCode());
    }
}
