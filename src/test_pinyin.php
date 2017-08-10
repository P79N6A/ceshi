<?php
require_once 'vendor/autoload.php';

use Overtrue\Pinyin\Pinyin;

// 小内存型
$pinyin = new Pinyin(); // 默认
// 内存型
$pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
$result = $pinyin->convert('带着希望去旅行，比到达终点更美好');
// I/O型
$pinyin = new Pinyin('Overtrue\Pinyin\GeneratorFileDictLoader');