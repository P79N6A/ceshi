<?php namespace Validator;

use Exception\HttpException;
use Sirius\Validation\Rule\AbstractRule;


/**
 * 中文字数验证
 * Class Chinese
 * @package Validator
 */
class Chinese extends AbstractRule
{

    // first define the error messages
    const MESSAGE = '';
    const LABELED_MESSAGE = '';

    // create constants for the options names help your IDE help you


    // specify default options if you want
    protected $options = array(
        'min' => 0,
        'max' => 9999999
    );

    // if you want to let the user pass the options as a CSV (eg: 'this,that')
    // you need to provide a `optionsIndexMap` property which will convert the options list
    // into an associative array of options
    protected $optionsIndexMap = array(
        0 => 'min',
        1 => 'max'
    );


    function validate($value, $valueIdentifier = null)
    {
        if (empty($value)) {
            $value = '';
        }
        $length = get_chinese_string_length($value);
        $min = $this->options['min'];
        $max = $this->options['max'];

        if ($length >= $min && $length <= $max) {
            return true;
        }

        return false;
    }

}