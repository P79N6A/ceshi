<?php
require_once 'vendor/autoload.php';

//use Overtrue\Pinyin\Pinyin;
//
//// 小内存型
//$pinyin = new Pinyin(); // 默认
//// 内存型
//$pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
//$result = $pinyin->convert('带着希望去旅行，比到达终点更美好');
//// I/O型
//$pinyin = new Pinyin('Overtrue\Pinyin\GeneratorFileDictLoader');



class arr {
	
	public static function map(Closure $callback){
		
		$callback(date('H:i:s'));
	}
	public static function map2(Closure $callback){
		
		sleep(2);
		$callback(date('H:i:s'));
	}
}

arr::map(function($time){
	
	echo $time . ' 1';
});
arr::map2(function($time){
	
	echo $time . ' 2';
});
