<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creeper extends CI_Controller
{

    /**
     * 电影天堂的电影下载爬取
     * @param string $start_url
     */
    public function dytt($start_url = 'http://dytt8.net/')
    {
        //初试变量
        $this->load->model('creeper/url');
        $this->load->model('creeper/downloader');
        $this->load->model('creeper/htmlpaser');

        //通过初试链接获取合法爬取地址
        $this->url->init($start_url);
        //开始死循环
        while ($this->url->hasUrls()) {
            echo "循环开始了..." . "\n";
            //获取urls
            $urls = $this->url->getUrls();
            log_message('debug', 'urls count ' . count($urls));
            echo "找到了" . count($urls) . "个链接，准备下载..." . "\n";

            //保存html
            $this->downloader->saveHtml($urls);
            //分析网页并保存
            $pages = $this->downloader->getHtml();
            log_message('debug', 'pages count ' . count($pages));
            echo "下载终于完成了，准备分析网页..." . "\n";

            //获取新的url
            $urls_new = $this->htmlpaser->paserUrls($pages);
            $this->url->setUrls($urls);
            log_message('debug', 'urls_new count ' . count($urls_new));
            echo "又找到了" . count($urls_new) . "个新的链接地址..." . "\n";

            $this->htmlpaser->paserHtml($pages);

        }
        echo 'done';
        exit();
    }


}