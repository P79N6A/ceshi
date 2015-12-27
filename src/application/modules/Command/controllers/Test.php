<?php
use Base\Controllers;

class TestController extends Controllers
{
    public function indexAction()
    {
        $start = microtime(true);
        $end = microtime(true);
        echo($end - $start), PHP_EOL;
    }
}