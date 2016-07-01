<?php

class TestCase extends PHPUnit_Framework_TestCase {

	public function __construct() {
	}

	public function tearDown()
	{
		\Mockery::close();
	}
}
