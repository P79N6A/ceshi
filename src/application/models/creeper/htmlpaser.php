<?php
use Snoopy\Snoopy;

/**
 * Class Url_model
 * url model
 */
class htmlpaser extends CI_Model
{

    private $title;
    private $download_url;
    private $category;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 解析html，获取其中的urls
     * @return string
     */
    public function paserUrls($pages)
    {
        if (empty($pages) || !is_array($pages)) {
            return null;
        }
        $ret = array();
        $html = new simple_html_dom();
        foreach ($pages as $page) {
            $html->load_file($page);
            foreach ($html->find('a') as $element) {
                if (strpos($element->href, 'http:') === false) {
                    $ret[] = 'http://www.ygdy8.net/' . $element->href;
                } else {
                    $ret[] = $element->href;
                }
            }
        }
        return $ret;
    }

    /**
     * 解析html，获取其中的urls
     * @return string
     */
    public function paserHtml($pages)
    {
        if (empty($pages) || !is_array($pages)) {
            return null;
        }
        $html = new simple_html_dom();
        foreach ($pages as $page) {
            $ret = array();
            $html->load_file($page);
            foreach ($html->find('a') as $element) {
                if (strpos($element->href, 'ftp:') !== false) {
                    $ret[] = $element->plaintext;
                }
            }
            $this->title = $html->find('title', 0)->plaintext;
            $this->download_url = json_encode($ret);
            $this->category = $html->find('div[class=path]', 0)->plaintext;
            if (!empty($this->title) || !empty($this->download_url) || !empty($this->category)) {
                $data = array(
                    'title' => trim($this->title),
                    'download_url' => $this->download_url,
                    'category' => $this->category,
                );
                $this->setMovie($data);
            }
            unlink($page);
        }
    }

    /**
     * setMovie
     * @param $data
     * @return bool
     * @internal param $url
     */
    public function setMovie($data)
    {
        $query = $this->db->get_where('movies', array('title' => $data['title']), 1);
        if (!$query->result()) {
            $this->db->set($data);
            $this->db->insert('movies');
        }
    }

}