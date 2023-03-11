<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'user');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

    public function index(){
        $e = $this->db2->get_where('Eatary', array('EID' => 51))->row_array();
        echo "vijay";
        echo "<pre>";
        print_r($e);die;
    }

}
