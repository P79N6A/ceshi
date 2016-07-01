<?php

//class DogDemo
//{
//
//    public function say($word)
//    {
//        return $word;
//    }
//}


class MyClass
{
    private $value;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function foo()
    {
        return $this->value;
    }

    private function fooPrivate()
    {
        return $this->value . ' private';
    }

    public static function fooStatic()
    {
        return 'static';
    }
}