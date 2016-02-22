<?php
use Snoopy\Snoopy;

/**
 * Class Url_model
 * url model
 */
class url extends CI_Model
{

    public $url_md5;
    public $url;

    private $preg = '/html\/[\w\/]+\/[\d]{8}\/[\d]{5}\.html$/i';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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

        log_message('debug', 'init links fetch ' . var_export($snoopy->results, true));
        //正则匹配url
        if ($snoopy->results) {
            foreach ($snoopy->results as $url) {
                if (preg_match($this->preg, $url)) {
                    $this->setUrls($url);
                }
            }
        }
        return 0;
    }

    /**
     * 是否有可用爬取url
     * @return bool
     */
    public function hasUrls()
    {
        if ($this->getUrls()) {
            return true;
        }
        return false;
    }

    /**
     * 获取url
     * @return mixed
     */
    public function getUrls()
    {
        $query = $this->db->get_where('urls', array('is_view' => 0));
        return $query->result_array();
    }

    /**
     * 保存url
     * @param $url
     * @return bool
     */
    public function setUrls($url)
    {
        if (is_array($url)) {
            foreach ($url as $u) {
                $this->url = $u;
                $this->url_md5 = md5($u);
                $query = $this->db->get_where('urls', array('url_md5' => $this->url_md5), 1);
                if (!$query->result()) {
                    $this->db->insert('urls', $this);
                }
            }
            return true;
        } else {
            $this->url = $url;
            $this->url_md5 = md5($url);
            $query = $this->db->get_where('urls', array('url_md5' => $this->url_md5), 1);
            if (!$query->result()) {
                return $this->db->insert('urls', $this);
            }
            return false;
        }

    }

    public function clearUrls()
    {
        $query = $this->db->get_where('urls');
        $ret = $query->result_array();
        $preg = '/^(http:\/\/www\.dytt8\.net\/|\/|http:\/\/dytt8\.net\/)?html\/[\w\/]+\/[\d]{8}\/[\d]{5}\.html$/i';
        foreach ($ret as $url) {
            if (preg_match($preg, $url['url'])) {
                continue;
            }
            $sql = "DELETE FROM urls WHERE url_md5 = ?";
            $this->db->query($sql, array($url['url_md5']));
        }
    }
}