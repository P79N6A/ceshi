<?php

/*
 */

class Pageclass
{
    private $data = array();
    private $page = array();
    private $row = 10;


    function __construct($data, $rows = 10)
    {
        $this->date = $data;
        $this->row = $rows;
    }

    function handle()
    {

    }
}