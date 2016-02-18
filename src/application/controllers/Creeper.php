<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creeper extends CI_Controller {

	/**
	 * 电影天堂的电影下载爬取
	 * @param string $start_url
	 */
	public function dytt($start_url = 'http://dytt8.net/'){
		//初试变量
		$this->load->model('creeper/urler_model');
		$this->load->model('creeper/downloader_model');
		$this->load->model('creeper/htmlpaser_model');

		//通过初试链接获取合法爬取地址
		$this->urler_model->init($start_url);
		//开始死循环
		while($this->urler_model->hasUrls()){
			//获取urls
			$urls = $this->urler_model->getUrls();
			//保存html
			$this->downloader_model->saveHtml($urls);
			//分析网页并保存
			$pages = $this->downloader_model->getHtml();
			$this->htmlpaser_model->paserHtml($pages);
			//获取新的url
			$urls = $this->htmlpaser_model->paserUrls($pages);
			$this->urler_model->saveUrls($urls);
		}
		echo 'done';
		exit();
	}

	 

}