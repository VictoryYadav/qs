<?php


class MY_AuthController extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		if ($this->session->userdata('logged_in')) {
			$this->authuser = $this->session->userdata('logged_in');
		} else {
			redirect(base_url());
		}
	}



	private function getpost()
	{
		if (empty($_POST)) {
			$this->load->view('errors/html/error_method');
		} else {
			return json_decode(json_encode($_POST));
		}
	}

	
}
