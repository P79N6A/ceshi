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
            foreach ($urls as $url) {
                $html = read_file($url['url']);
                write_file($this->path . $url['url_md5'] . '.html', iconv('gbk', 'utf-8', $html));
                return;
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