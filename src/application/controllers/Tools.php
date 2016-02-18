<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {

	public function message($to = 'me'){

		$this->load->model('creeper/url_model');
		$to = $this->url_model->test();

		echo "hello, {$to}" . PHP_EOL;
	}


}