<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/Apc.php';

class Welcome extends CI_Controller {

    public function index() {
        echo 'give a test';
        exit;
        //$this->load->view('welcome/index');
//        $path = APPPATH . 'controllers/Tools.php';
//        if (file_exists($path)) {
//            apc_clear_cache();
//            Apc::cache_file($path);
//            echo 'cache it';
//        }
    }
}
