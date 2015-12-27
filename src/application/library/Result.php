<?php

/**
 * Created by IntelliJ IDEA.
 * User: aimsam
 * Date: 7/21/15
 * Time: 2:01 PM
 */
class Result extends stdClass
{

    public $code = 200;


    public function __get($property)
    {
        return $this->$property;
    }


    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function toArray()
    {
        return (array)$this;
    }
}