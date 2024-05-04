<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SAuth extends CI_Controller {

	private $genDB;
	public function __construct()
	{
		parent::__construct();

        $this->lang->load('message','english');
        $this->genDB = $this->load->database('GenTableData', TRUE);
        $this->output->delete_cache();

	}

	public function index(){
		$status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('signup', $_POST);
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $mobile 	= $_POST['mobileNo'];
            $pwdHash 	= md5($_POST['pwd']);
            $countryCd 	= $_POST['countryCd'];

            $user = $this->genDB->get_where('usersSupport', array('mobileNo' => $mobile, 'pwdHash' => $pwdHash, 'countryCd' => $countryCd))->row_array();
            if(!empty($user)){
            	$session_data = array(
                        'userId' => $user['userId'],
                        'fullnamemobileNo' => $user['fullnamemobileNo'],
                        'mobileNo' => $user['mobileNo'],
                        'email' => $user['email'],
                        'countryCd' => $user['countryCd']
                    );
                $this->session->set_userdata('logged_in', $session_data);
            	$status = 'success';
            	$response = base_url('support/index');
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('login');
        $data['country'] = $this->genDB->get_where('countries', array('Stat' => 0))->result_array(); 
        $this->load->view('support/login', $data);
    }

    public function signup(){
		$status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('signup', $_POST);
            // echo "<pre>";
            // print_r($_POST);
            // die;

            $supp = $_POST;
            $supp['pwdHash'] = md5($supp['pwd']);

            $userId = $this->genDB->insert('usersSupport', $supp);

            $session_data = array(
                        'userId' => $userId,
                        'fullname' => $supp['fullname'],
                        'mobileNo' => $supp['mobileNo'],
                        'email' => $supp['email'],
                        'countryCd' => $supp['countryCd']
                    );
                $this->session->set_userdata('logged_in', $session_data);

            $status = 'success';
            $response = base_url('support/index');
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('signup');
        $data['country'] = $this->genDB->get_where('countries', array('Stat' => 0))->result_array(); 
        $this->load->view('support/signup', $data);
    }


}
