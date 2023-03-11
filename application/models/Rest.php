<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Model{

	private $db2;
	public function __construct()
	{
		parent::__construct();
		
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

	public function getDisableUserList($ChainId, $EID){
		return $this->db2->select('RUserId, FName, LName, MobileNo, Stat')
					->order_by('FName','LName')
					->get_where('UsersRest', array('ChainId' => $ChainId,'EID' => $EID))
					->result_array();
	}

	public function userDisableEnable($data){
		$res = '';
		if($data['type'] == 'disable'){
			$this->db2->update('UsersRest', array('Stat' => 3 ), array('RUserId' => $data['id']));
			$res = "User Disabled";
		}else{
			$this->db2->update('UsersRest', array('Stat' => 0 ), array('RUserId' => $data['id']));
			$res = "User Enabled";
		}
		return $res;
	}

	public function getOffersList(){
		return $this->db2->order_by('SchCd', 'desc')->get('CustOffers')->result_array();
	}
	
}
