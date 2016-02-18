<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creeper extends CI_Controller {

	/**
	 * 电影天堂的电影下载爬取
	 * @param string $start_url
	 */
	public function dytt($start_url = 'http://dytt8.net/'){
		//初试变量
		$this->load->model('creeper/url');
		$this->load->model('creeper/downloader');
		$this->load->model('creeper/htmlpaser');

		//通过初试链接获取合法爬取地址
		$this->url->init($start_url);
		//开始死循环
		while($this->url->hasUrls()){
			//获取urls
//			$urls = $this->url->getUrls();
//			//保存html
//			$this->downloader->saveHtml($urls);
			//分析网页并保存
			$pages = $this->downloader->getHtml();
			//获取新的url
//			$urls = $this->htmlpaser->paserUrls($pages);
//			$this->url->setUrls($urls);
			$this->htmlpaser->paserHtml($pages);
			break;
		}
		echo 'done';
		exit();
	}

	 

}