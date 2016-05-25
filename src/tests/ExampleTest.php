<?php
require_once __DIR__."/TestCase.php";


class ExampleTest extends TestCase {
	public function testExample() {
		//echo Curl::get('http://www.baidu.com');

		$this->assertJson('{', '这不是一个json');
	}

}
