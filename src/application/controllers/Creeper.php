<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creeper extends CI_Controller {

    /**
     * 电影天堂的电影下载爬取
     *
     * @param string $start_url
     */
    public function dytt($start_url = 'http://dytt8.net/') {
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
            $this->url->setUrls($urls_new);
            log_message('debug', 'urls_new count ' . count($urls_new));
            echo "又找到了" . count($urls_new) . "个新的链接地址..." . "\n";

            //拔取文件中的有用数据（下载地址）
            $this->htmlpaser->paserHtml($pages);
        }
        echo 'done';
        exit();
    }

    /**
     * 输出列表到txt

     */
    public function explode() {
        $this->load->model('creeper/app');
        $this->load->helper('file');

        $movies = $this->app->lists();
        $ftps   = '';
        foreach ($movies as $movie) {
            $download_url = json_decode($movie['download_url'], true);
            if (count($download_url) == 1) {
                $ftps .= $download_url[0] . "\r\n";
            }
        }
        write_file('/Users/haicheng/developer/wwwroot/ceshi/src/cache/movies.txt', $ftps);

        echo 'done';
        unset($movies, $ftps);
    }

    /**
     * 爬取高分电影

     */
    public function crawHighScoreMovies() {

        $this->load->model('creeper/downloader');
        $this->load->model('creeper/htmlpaser');

        //分页列表
        $urls = array(
            'http://www.dytt8.net/html/gndy/jddy/20160320/50523.html',
            'http://www.dytt8.net/html/gndy/jddy/20160320/50523_2.html',
            'http://www.dytt8.net/html/gndy/jddy/20160320/50523_3.html',
            'http://www.dytt8.net/html/gndy/jddy/20160320/50523_4.html',
        );

        //保存列表页面
        //抓取电影列表中url
        $this->downloader->downloadPage($urls);
        $pages = $this->downloader->getHtml();

        $movies_urls = $this->htmlpaser->paserMenuMovieHtml($pages);
        $this->downloader->removePage($pages);

        //下载电影页面
        $this->downloader->downloadPage($movies_urls);
        $pages_movies = $this->downloader->getHtml();

        //分析页面、爬取地址
        $movies = $this->htmlpaser->paserMovieHtml($pages_movies);
        $this->downloader->removePage($pages_movies);

        $ftps = implode("\r\n", $movies);
        write_file('/Users/haicheng/developer/wwwroot/ceshi/src/cache/movies.txt', $ftps);
        echo 'done';
        exit();
    }

    public function test() {
        //        $urls = array("http://baidu.com", "http://sina.com.cn", "http://sohu.com");
        //        $this->load->library('multicurlclass', $urls);
        //        $data = $this->multicurlclass->start();
        //        echo var_dump($data);
        //增加多线程下载

        $urls       = array(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9
        );
        $urls_array = array_chunk($urls, 4);
        var_dump($urls_array);
        exit;

    }


}