<?php namespace Validator;

use Exception\HttpException;
use Sirius\Validation\Rule\AbstractRule;


/**
 * 验证json
 * Class Chinese
 * @package Validator
 */
class Json extends AbstractRule
{

    // first define the error messages
    const MESSAGE = '';
    const LABELED_MESSAGE = '';

    // create constants for the options names help your IDE help you


    // specify default options if you want
    protected $options = array();

    // if you want to let the user pass the options as a CSV (eg: 'this,that')
    // you need to provide a `optionsIndexMap` property which will convert the options list
    // into an associative array of options
    protected $optionsIndexMap = array();


    function validate($value, $valueIdentifier = null)
    {
        if (empty($value)) {
           return false;
        }
        $result = json_decode($value, true);
        if(is_array($result)){
            return true;
        }
        return false;
    }

}