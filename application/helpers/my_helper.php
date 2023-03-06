<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if(!function_exists('toObject')){
	function toObject($array = ''){
		if(is_array($array )){
			return json_decode(json_encode($array), FALSE);
		}else{
			return $array;
		}
	}
}

	

if (!function_exists('authuser')){
	function authuser(){
		$ci = &get_instance();
		$ci->load->library('session');
		if($ci->session->userdata('logged_in')) {
			return toObject($ci->session->userdata('logged_in'));
		}else{
			return null;
		}
	}
}


if(!function_exists('set_flash')){
	function set_flash($type,$message){
		$ci = &get_instance();
		$data = array(
			'type' => $type,
			'message' => $message
		);
		$ci->session->set_flashdata('flash',$data);
	}
}

if ( ! function_exists('redirect_back'))
{
    function redirect_back()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }
        else
        {
            header('Location: http://'.$_SERVER['SERVER_NAME']);
        }
        exit;
	}
}

if(!function_exists('get_flash')){
	function get_flash(){
		$ci = &get_instance();
		if($ci->session->flashdata('flash')) {
			$flash = json_decode(json_encode($ci->session->flashdata('flash')));
			if($flash->type == 'success')
				return '<div class="alert alert-success">'. $flash->message .'</div>';
			else if($flash->type == 'info')
				return '<div class="alert alert-info">'. $flash->message .'</div>';
			else if($flash->type == 'warning')
				return '<div class="alert alert-warning">'. $flash->message .'</div>';
			else if($flash->type == 'error')
				return '<div class="alert alert-danger">'. $flash->message .'</div>';
		}
		else{
			return null;
		}
	}
}
