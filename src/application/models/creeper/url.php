<?php
use Snoopy\Snoopy;

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
        $snoopy = new Snoopy;
        $snoopy->proxy_port = "80";
        $snoopy->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)";
        $snoopy->rawheaders["Pragma"] = "no-cache"; //cache 的http头信息
        $snoopy->read_timeout = 10;
        $snoopy->fetchlinks($start_url);

        log_message('debug', var_export($snoopy->results, true));
        exit;
        return 'hello model.';
    }

}