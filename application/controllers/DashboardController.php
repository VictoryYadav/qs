<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends MY_AuthController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'user');
		$this->load->helper(array('my_helper','generic_helper'));
	}

	public function index(){
		$this->load->view('dashboard/index');
	}

	public function users(){
		$this->load->view('dashboard/user');	
	}

	public function form(){
		$this->load->view('dashboard/form');
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
