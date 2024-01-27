<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supp extends CI_Model{

	private $db2;
	public function __construct()
	{
		parent::__construct();
		
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

	public function getUitemCodeList(){
		$genDB = $this->load->database('GenTableData', TRUE);

		return $genDB->get('AI_Items')->result_array();
	}

	public function getCuisineList(){
		$genDB = $this->load->database('GenTableData', TRUE);

		return $genDB->get('Cuisines')->result_array();	
	}

	public function getRestaurantList(){
		$genDB = $this->load->database('GenTableData', TRUE);

		return $genDB->select("CNo, EID, Name")->get('EIDDet')->result_array();	
	}

	public function getMenuListByEID($EID){
		$dbName = $EID.'e';
		$localDB = $this->load->database($dbName, TRUE);

		$data['list'] = $this->db2->query("select i.ItemId, i.ItemNm1, i.UItmCd, ai.ItemName, i.EID from $dbName.MenuItem i, GenTableData.ai_items ai where i.UItmCd = ai.UItmCd and i.EID = $EID and i.Stat = 0")->result_array();
		$data['UitemList'] = $this->getUitemCodeList();
		return $data;


		// return $localDB->select("ItemId, EID, UItmCd, IMcCd, CTyp, CID, FID, ItemNm1")
		// 				->get_where('MenuItem', array('Stat' => 0, 'EID' => $EID))
		// 				->result_array();	
	}

	public function updateUItemCode($data){
		$dbName = $data['EID'].'e';
		$localDB = $this->load->database($dbName, TRUE);

		$localDB->update('MenuItem', array('UItmCd' => $data['UItmCd']),
								array('EID' => $data['EID'], 'ItemId' => $data['ItemId'])
								);
		return 'UItem code updated';
	}


}
