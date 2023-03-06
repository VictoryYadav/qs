<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	function email_check($email){
		$CI = & get_instance();
		$CI->load->model('User');
	    return $CI->User->get_by_email($email) ? TRUE : FALSE;
	}

	function contact_check($no){
	    $CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->get_by_contactno($no) ? TRUE : FALSE;
	}

	function insertRecord($tbl,$data){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->recordInsert($tbl,$data);
	}

	function updateRecord($tbl,$where,$data){
		$CI = & get_instance();
		$CI->load->model('User');
		$CI->User->recordUpdate($tbl,$where,$data);
	}

	function deleteRecord($tbl,$where){
		$CI = & get_instance();
		$CI->load->model('User');
		$CI->User->recordDelete($tbl,$where);	
	}

	function countRecord($tbl){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->countAllRecord($tbl);
	}

	function getRecords($tbl=null,$where=null){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getAllRecord($tbl,$where);	
	}

	function send_mail($to, $from, $subject, $body, $cc = null, $bcc = null){
		$CI =& get_instance();
		$CI->load->library('email');
		$config = array(
		            'protocol' => 'smtp', 
		            'smtp_host' => 'ssl://smtp.gmail.com', 
		            'smtp_port' => 465, 
		            'smtp_user' => 'xxx@gmail.com', 
		            'smtp_pass' => 'xxx', 
		            'mailtype' => 'html', 
		            'charset' => 'iso-8859-1'
		);
		$CI->email->initialize($config);
		$CI->email->set_mailtype("html");
		$CI->email->set_newline("\r\n");
		$CI->email->to($to);
		$CI->email->from($from);
		$CI->email->subject($subject);
		$CI->email->message($body);
		if($CI->email->send()){
			return 1;
		}else{
			print_r($CI->email->print_debugger());exit();
			return 0;
		}
	}

