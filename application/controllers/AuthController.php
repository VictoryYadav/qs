<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'auth');
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function checklogin(){
		$req = $this->getpost();
		$email = $req->email;
		$pass = $req->password;
		if (($email == "") && ($pass == "")) {
			$this->session->set_flashdata('error','Enter Username or Password'); 
			$this->load->view('login', $data);
		} else {
			$data = array(
				'email' => trim($this->input->post('email')),
				'password' => md5(trim($this->input->post('password')))
			);
			
			$result = $this->auth->checkUserLogin($data);
			if ($result != false) {
				$session_data = array(
					'id' => $result[0]->id,
					'name' => $result[0]->name,
					'email' => $result[0]->email,
					'mobile' => $result[0]->mobile,
					'userType' => $result[0]->role
				);
				$this->session->set_userdata('logged_in', $session_data);

				if($session_data['userType'] == 1){
					redirect(base_url() . 'admin/home', 'refresh');
				}else {
					$this->session->set_flashdata('error','Invalid Username or Password'); 
					redirect(base_url() . 'login', 'refresh');
				}
				
				
			} else {
				$this->session->set_flashdata('error','Invalid Username or Password'); 
				redirect(base_url() . 'login', 'refresh');
			}
		}
		
	}

	public function logout(){
		// $this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		redirect(base_url() . 'login', 'refresh');
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
