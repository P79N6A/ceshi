<?php
use Snoopy\Snoopy;

/**
 * Class Url_model
 * url model
 */
class htmlpaser extends CI_Model {

    private $title;

    private $download_url;

    private $category;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 解析html，获取其中的urls
     *
     * @return string
     */
    public function paserUrls($pages) {
        if (empty($pages) || !is_array($pages)) {
            return null;
        }
        $ret  = array();
        $html = new simple_html_dom();
        foreach ($pages as $page) {
            $html->load_file($page);
            foreach ($html->find('a') as $element) {
                if (strpos($element->href, 'ftp:') === false) {
                    if (strpos($element->href, 'http:') === false) {
                        $ret[] = 'http://www.ygdy8.net/' . $element->href;
                    } else {
                        $ret[] = $element->href;
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * 解析html，获取其中的urls
     *
     * @return string
     */
    public function paserHtml($pages) {
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
            if (empty($ret)) {
                continue;
            }
            $this->title        = $html->find('title', 0)->plaintext;
            $this->download_url = json_encode($ret);
            $cate_el            = $html->find('div[class=path]', 0);
            if (empty($cate_el)) {
                continue;
            }
            $this->category = $cate_el->plaintext;
            if (!empty($this->title) && !empty($this->download_url) && !empty($this->category)) {
                $data = array(
                    'title'        => trim($this->title),
                    'download_url' => trim($this->download_url),
                    'category'     => trim($this->category),
                );
                $this->setMovie($data);
            }
            unlink($page);
        }
    }

    /**
     * 解析html，获取其中的urls
     *
     * @return string
     */
    public function paserMenuMovieHtml($pages) {
        if (empty($pages) || !is_array($pages)) {
            return null;
        }
        $html = new simple_html_dom();
        $ret = array();
        foreach ($pages as $page) {
            $html->load_file($page);

            $zoom = $html->find('#Zoom', 0);
            if (empty($zoom)) {
                continue;
            }

            foreach ($zoom->find('a') as $element) {
                $url = $element->plaintext;
                preg_match('/\b(([\w-]+:\/\/?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))/', $url, $arr);
                if (!empty($arr[1])) {
                    $ret[] = $element->plaintext;
                }
            }
        }

        return $ret;
    }

    public function paserMovieHtml($pages) {
        if (empty($pages) || !is_array($pages)) {
            return null;
        }
        $html = new simple_html_dom();
        $ret = array();
        foreach ($pages as $page) {
            $html->load_file($page);

            $zoom = $html->find('#Zoom', 0);
            if (empty($zoom)) {
                continue;
            }
            $tables = $html->find('table');
            foreach ($tables as $element) {
                foreach ($element->find('a') as $elementa) {
                    if (!empty($elementa->plaintext) && (false !== strpos( $elementa->plaintext, 'ftp') || false !== strpos( $elementa->plaintext, 'thunder'))) {
                        $ret[] = $elementa->plaintext;
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * setMovie
     *
     * @param $data
     *
     * @return bool
     * @internal param $url
     */
    public function setMovie($data) {
        $query = $this->db->get_where('movies', array('title' => $data['title']), 1);
        if (!$query->result()) {
            $this->db->set($data);
            $this->db->insert('movies');
        }
    }

}