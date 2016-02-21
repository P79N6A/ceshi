<?php
use Snoopy\Snoopy;

/**
 * Class Url_model
 * url model
 */
class app extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 导出电影
     * @return mixed
     */
    public function lists()
    {
        $query = $this->db->get_where('movies');
        return $query->result_array();
    }

}