<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supp extends CI_Model{

	private $genDB;
	public function __construct()
	{
		parent::__construct();

        $this->genDB = $this->load->database('GenTableData', TRUE);
	}

	public function getUitemCodeList(){

		return $this->genDB->get('AI_Items')->result_array();
	}

	public function getCuisineList(){
		return $this->genDB->get('Cuisines')->result_array();	
	}

	public function getRestaurantList(){
		return $this->genDB->select("CNo, EID, Name")->get('EIDDet')->result_array();	
	}

	// public function getMenuListByEID($EID){
	// 	$dbName = $EID.'e';
	// 	$localDB = $this->load->database($dbName, TRUE);

	// 	$data['list'] = $this->db2->query("select i.ItemId, i.ItemNm1, i.UItmCd, ai.ItemName, i.EID from $dbName.MenuItem i, GenTableData.ai_items ai where i.UItmCd = ai.UItmCd and i.EID = $EID and i.Stat = 0")->result_array();
	// 	$data['UitemList'] = $this->getUitemCodeList();
	// 	return $data;

	// }

	public function updateUItemCode($data){
		$dbName = $data['EID'].'e';
		$localDB = $this->load->database($dbName, TRUE);

		$localDB->update('MenuItem', array('UItmCd' => $data['UItmCd']),
								array('EID' => $data['EID'], 'ItemId' => $data['ItemId'])
								);
		return 'UItem code updated';
	}

	// public function getRestList(){
	// 	return $this->db2->select('EID, Name')
	// 					->order_by('EID DESC')
	// 					->get_where('Eatary', array('Stat' => 0))
	// 					->result_array();
	// }

	// public function getPaymentModes(){
	// 	$langId = $this->session->userdata('site_lang');
 //        $lname = "Name$langId as Name";
	// 	return $this->db2->select("PymtMode, $lname,Company, CodePage1")
	// 					->order_by('Rank', 'ASC')
	// 					->get_where('ConfigPymt', array('Stat' => 1, 'EID' => authuser()->EID))->result_array();
	// }

	// public function getLoyalities(){
	// 	return $this->db2->get('LoyaltyConfig')->result_array();
	// }


}