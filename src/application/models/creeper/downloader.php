<?php

/**
 * Class Url_model
 * url model
 */
class downloader extends CI_Model {

    private $path;

    private $rows = 10;

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $cf         = get_config_file('creeper');
        $this->path = $cf['html_path'];
        $this->load->library('multicurlclass');
    }

    public function downloadPage($urls) {
        if (empty($urls) || !is_array($urls)) {
            return false;
        }
        if (!empty($urls)) {
            //将数组分组，分批下载
            $urls_array = array_chunk($urls, $this->rows);
            foreach ($urls_array as $array_key => $urls_item) {
                $extend = $this->_formatPage($array_key, count($urls_item));
                echo "正在下载第" . $extend['start_num'] . "~" . $extend['end_num'] . "的页面..." . "\n";
                //下载
                $this->multicurlclass->set_urls($urls_item);
                $data = $this->multicurlclass->start();
                //通过md5保存值
                foreach ($urls_item as $key => $urls_key_val) {
                    $file_path = $this->path . md5($urls_key_val) . '.html';
                    if (file_exists($file_path)) {
                        if((md5_file($file_path) == md5($data[$key]))){
                            continue;
                        }
                    }
                    write_file($file_path, iconv('gbk', 'utf-8//IGNORE', $data[$key]));
                }
            }
        }

        return 0;
    }

    /**
     * 保存HTML源码
     *
     * @return int
     */
    public function saveHtml($urls) {
        if (empty($urls) || !is_array($urls)) {
            return 0;
        }
        if (!empty($urls)) {
            //将数组分组，分批下载
            $urls_array = array_chunk($urls, $this->rows);
            foreach ($urls_array as $array_key => $urls_item) {
                echo "正在下载第" . ($this->rows * $array_key + 1) . "~" . ($this->rows * ($array_key + 1)) . "的页面..." . "\n";
                $urls_item_val = array_column($urls_item, 'url');
                $urls_key      = array_column($urls_item, 'url_md5');
                //下载
                $this->multicurlclass->set_urls($urls_item_val);
                $data = $this->multicurlclass->start();
                //通过md5保存值
                foreach ($urls_key as $key => $urls_key_val) {
                    if (!file_exists($this->path . $urls_key_val . '.html')) {
                        write_file($this->path . $urls_key_val . '.html', iconv('gbk', 'utf-8//IGNORE', $data[$key]));
                    }
                    $this->is_view = 1;
                    $this->db->update('urls', $this, array('url_md5' => $urls_key_val));
                }
            }
        }

        return 0;
    }

    public function _formatPage($index = 0, $total) {

        $extend              = array();
        $extend['start_num'] = $this->rows * $index + 1;
        $current_num         = $this->rows * ($index + 1);
        $extend['end_num']   = ($current_num > $total) ? $total : $current_num;

        return $extend;
    }

    /**
     * 获取HTML源码
     *
     * @return int
     */
    public function getHtml() {
        $files = get_filenames($this->path);

        return array_map(function ($val) {
            return $this->path . $val;
        }, $files);
    }

    public function removePage($urls){
        if(empty($urls)){
            return ;
        }

        array_walk($urls, function($url){
           @unlink($url);
        });
    }

}