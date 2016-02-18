<?php

/**
 * Class Url_model
 * url model
 */
class downloader extends CI_Model
{

    private $path;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $cf = get_config_file('creeper');
        $this->path = $cf['html_path'];
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
            foreach ($urls as $key => $url) {
                echo "正在下载第" . ($key + 1) . "个页面..." . "\n";
                if (!file_exists($this->path . $url['url_md5'] . '.html')) {
                    $html = read_file($url['url']);
                    write_file($this->path . $url['url_md5'] . '.html', iconv('gbk', 'utf-8//IGNORE', $html));
                }
                $this->is_view = 1;
                $this->db->update('urls', $this, array('url_md5' => $url['url_md5']));
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