<?php

/**
 * Class Url_model
 * url model
 */
class htmlpaser extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 解析html，获取其中的urls
     * @return string
     */
    public function paserUrls()
    {
        return 'hello model.';
    }

}