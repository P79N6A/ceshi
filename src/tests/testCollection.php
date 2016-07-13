<?php
require_once 'Arr.php';
require_once 'Collection.php';



$foo = array(
    'name' => 'haicheng',
    'age' => '28'
);

$foo_collection = new Collection($foo);
echo $foo_collection->toJson();


$foo_collection->add('foo1', '1');
$foo_collection->add('foo2.foo1-1', '2');
echo $foo_collection->toJson();

var_dump($foo_collection->getIterator());
