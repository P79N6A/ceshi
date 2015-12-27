<?php namespace Validator;
use Sirius\Validation\Rule\AbstractRule;


class Opc extends AbstractRule {

    // first define the error messages
    const MESSAGE = '';
    const LABELED_MESSAGE = '';

    // create constants for the options names help your IDE help you


    // specify default options if you want
    protected $options = array(

    );

    // if you want to let the user pass the options as a CSV (eg: 'this,that')
    // you need to provide a `optionsIndexMap` property which will convert the options list
    // into an associative array of options
    protected $optionsIndexMap = array(

    );

    function validate($value, $valueIdentifier = null) {
        //@todo 验证opc输入参数


        return true;
    }

}