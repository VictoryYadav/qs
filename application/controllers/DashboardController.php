<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// MY_AuthController
class DashboardController extends CI_Controller
{
	private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'user');

		$my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

	public function index(){
		$data['title'] = "Dashboard";
		$this->load->view('dashboard/index',$data);
	}

	public function getpost()
	{
		if (empty($_POST)) {
			$this->load->view('errors/html/error_method');
		} else {
			return json_decode(json_encode($_POST));
		}
	}

}
