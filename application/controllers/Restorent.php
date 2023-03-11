<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restorent extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Rest', 'rest');
	}

    public function add_user(){
		 $data['title'] = 'Add User';
		 $this->load->view('rest/add_user',$data);
    }

    public function user_disable(){
    	$status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
        	$status = 'success';
        	$response = $this->rest->userDisableEnable($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

		 $data['title'] = 'User Disable';
		 $data['users'] = $this->rest->getDisableUserList(authuser()->ChainId, authuser()->EID);
		 // echo "<pre>";
		 // print_r($data);
		 // die;
		 $this->load->view('rest/disable_user',$data);
    }

    public function user_access(){
    	$data['title'] = 'User Disable';
		$this->load->view('rest/add_user',$data);
    }

    public function offers_list(){
		 $data['title'] = 'Offers List';
		 $data['offers'] = $this->rest->getOffersList();
		 $this->load->view('rest/offer_lists',$data);
    }

    public function new_offer(){
    	$data['title'] = 'Add New Offer';
		$this->load->view('rest/add_new_offer',$data);	
    }

    public function item_list(){
    	$data['title'] = 'Item Details';
		$this->load->view('rest/item_lists',$data);	
    }

    public function order_dispense(){
    	$data['title'] = 'Order Dispense';
		$this->load->view('rest/dispense_orders',$data);	
    }



}
