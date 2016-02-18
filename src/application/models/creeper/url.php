<?php

/**
 * Class Url_model
 * url model
 */
class url extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 初始化
     * @param string $start_url
     * @return string
     */
    public function init($start_url = '')
    {
        log_message('error', 'Some variable did not contain a value.');
        exit;
        return 'hello model.';
    }

}