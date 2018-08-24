<?php

class base {
	
	private static $_number = null;
	
	public function __construct() {
		
		self::$_number = 1;
	}
	
	public function getIt() {
		var_dump(self::$_number);
	}
	public function setIt() {
		self::$_number = 456;
	}
}

class a extends base{
	
}
class b extends base{
	
}


$a = new a();
$a->getIt();
$a->setIt();
$a->getIt();

$b = new b();
$b->getIt();