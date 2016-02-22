<?php

/**
 * Class Url_model
 * url model
 */
class downloader extends CI_Model
{

    private $path;
    private $rows = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $cf = get_config_file('creeper');
        $this->path = $cf['html_path'];
        $this->load->library('multicurlclass');
    }

    /**
     * 保存HTML源码
     * @return int
     */
    public function saveHtml($urls)
    {
        if (empty($urls) || !is_array($urls)) {
            return 0;
        }
        if (!empty($urls)) {
            //将数组分组，分批下载
            $urls_array = array_chunk($urls, $this->rows);
            foreach($urls_array as $array_key => $urls_item){
                echo "正在下载第" . ($this->rows * $array_key + 1) . "~" . ($this->rows * ($array_key + 1)) . "的页面..." . "\n";
                $urls_item_val = array_column($urls_item, 'url');
                $urls_key = array_column($urls_item, 'url_md5');
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

    /**
     * 获取HTML源码
     * @return int
     */
    public function getHtml()
    {
        $files = get_filenames($this->path);
        return array_map(function ($val) {
            return $this->path . $val;
        }, $files);
    }


}