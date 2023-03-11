<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model{


	// public function recordInsert($tbl,$data){
	// 	$this->db->insert($tbl,$data);
	// 	return $this->db->insert_id();
	// }

	// public function recordUpdate($tbl,$where,$data){
	// 	$this->db->update($tbl, $data, $where);
	// }

	// public function recordDelete($tbl,$where){
	// 	$this->db->delete($tbl,$where);
	// }

	// public function countAllRecord($tbl){
	// 	$query="SELECT COUNT(*) as total FROM ".$tbl;
	// 	$result=$this->db->query($query);
	// 	if($result->num_rows() > 0)
	// 	{
	// 		$res = $result->result("array");
	// 		return $res[0];	
	// 	}
	// }

	// public function getAllRecord($tbl=null,$where=null){
	// 	$this->db->select('*')->from($tbl);
	// 	if(!empty($where)){
	// 		$this->db->where($where);
	// 	}
	//     $query = $this->db->get();
	//     if ($query->num_rows() > 0) {
	//     	if(!empty($where)){
	//     		$res =  $query->result("array");
	//     		return $res[0];
	//     	}else{
	//     		return $query->result("array");
	//     	}
	// 	} else {
	// 		return false;
	// 	}
	// }
	
}
