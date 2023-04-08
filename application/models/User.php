<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model{

	private $db2;
	public function __construct()
	{
		parent::__construct();
		
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}


	public function recordInsert($tbl,$data){
		$this->db2->insert($tbl,$data);
		return $this->db2->insert_id();
	}

	public function recordUpdate($tbl,$data,$where){
		$this->db2->update($tbl, $data, $where);
	}

	public function recordDelete($tbl,$where){
		$this->db2->delete($tbl,$where);
	}

	public function countAllRecord($tbl){
		$query="SELECT COUNT(*) as total FROM ".$tbl;
		$result=$this->db2->query($query);
		if($result->num_rows() > 0)
		{
			return $result->row_array();	
		}
	}

	public function getAllRecord($tbl=null,$where=null){
		$this->db2->select('*')->from($tbl);
		if(!empty($where)){
			$this->db2->where($where);
		}
	    $query = $this->db2->get();
	    if ($query->num_rows() > 0) {
	    	if(!empty($where)){
	    		return $query->row_array();
	    	}else{
	    		return $query->result_array();
	    	}
		} else {
			return false;
		}
	}

	public function gettingBiliingData($dbname, $EID, $billId){

		// $billData = array(
  //                   array("ItemAmt"=>"350","ItemNm"=>"Indonesian Style Paneer","ItmRate"=>350,"Qty"=>"1","Tx"=>1,"TaxType"=>2,"Stat"=>9,"Name"=>"Tequila Sunrise","Addr"=>"41 Ardeshir Mension, Station Road,","city"=>"Mumbai","pincode"=>"400054","cinno"=>"-","fssaino"=>"-","GSTno"=>"27ACZFS7957F1Z3","BillPrefix"=>"","BillSuffix"=>"","TaxInclusive"=>0,"PhoneNos"=>"02226494782","Remarks"=>"-","Tagline"=>"Visit again","BillNo"=>20,"TotItemDisc"=>0,"BillDiscAmt"=>0,"TotPckCharge"=>0,"DelCharge"=>0,"totamt"=>"1085.70","bservecharge"=>"10.00","tip"=>"0.00","BillDt"=>"2023-01-21 22=>08=>00","Portion"=>"Std","Itm_Portion"=>1),
  //                   array("ItemAmt"=>"240","ItemNm"=>"Murg Yakhni Shorba","ItmRate"=>240,"Qty"=>"1","Tx"=>1,"TaxType"=>2,"Stat"=>9,"Name"=>"Tequila Sunrise","Addr"=>"41 Ardeshir Mension, Station Road,","city"=>"Mumbai","pincode"=>"400054","cinno"=>"-","fssaino"=>"-","GSTno"=>"27ACZFS7957F1Z3","BillPrefix"=>"","BillSuffix"=>"","TaxInclusive"=>0,"PhoneNos"=>"02226494782","Remarks"=>"-","Tagline"=>"Visit again","BillNo"=>20,"TotItemDisc"=>0,"BillDiscAmt"=>0,"TotPckCharge"=>0,"DelCharge"=>0,"totamt"=>"1085.70","bservecharge"=>"10.00","tip"=>"0.00","BillDt"=>"2023-01-21 22=>08=>00","Portion"=>"Std","Itm_Portion"=>1),
  //                   array("ItemAmt"=>"350","ItemNm"=>"Paneer Tikka  Lahori","ItmRate"=>350,"Qty"=>"1","Tx"=>1,"TaxType"=>2,"Stat"=>9,"Name"=>"Tequila Sunrise","Addr"=>"41 Ardeshir Mension, Station Road,","city"=>"Mumbai","pincode"=>"400054","cinno"=>"-","fssaino"=>"-","GSTno"=>"27ACZFS7957F1Z3","BillPrefix"=>"","BillSuffix"=>"","TaxInclusive"=>0,"PhoneNos"=>"02226494782","Remarks"=>"-","Tagline"=>"Visit again","BillNo"=>20,"TotItemDisc"=>0,"BillDiscAmt"=>0,"TotPckCharge"=>0,"DelCharge"=>0,"totamt"=>"1085.70","bservecharge"=>"10.00","tip"=>"0.00","BillDt"=>"2023-01-21 22=>08=>00","Portion"=>"Std","Itm_Portion"=>1)
  //                   );
		// return $billData;
		// Repository : billing/bill_print.repo.php
		$q = "SELECT SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as ItemAmt, (if (k.ItemTyp > 0, (CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm, k.ItmRate, SUM(k.Qty) as Qty, (COUNT(k.TaxType)) as Tx, k.TaxType, k.Stat, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno, b.BillPrefix, b.BillSuffix, b.TaxInclusive, e.PhoneNos, e.Remarks, e.Tagline, b.BillNo, b.TotItemDisc, b.BillDiscAmt, b.TotPckCharge,  b.DelCharge, b.TotAmt, b.SerCharge as bservecharge, b.Tip, b.PymtTime as BillDt,ip.Name as Portions, k.Itm_Portion  from ".$dbname."Kitchen k, ".$dbname."KitchenMain km, ".$dbname."Billing b, ".$dbname."MenuItem m, ".$dbname."Eatary e , ".$dbname."ItemPortions ip where k.Itm_Portion = ip.IPCd and k.ItemId = m.ItemId and (k.Stat<>4 and k.Stat<>6 and k.Stat<>7 and k.Stat<>99) and km.CNo = k.CNo AND km.BillStat = b.BillId and e.EID = km.EID and k.EID = km.EID  and b.EID = km.EID  and b.ChainId = km.ChainId and km.EID = $EID and b.BillId = $billId Group By m.ItemNm, k.ItmRate, k.ItemTyp, k.CustItemDesc, k.TaxType, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno,  e.PhoneNos, e.Remarks, k.Stat, e.Tagline, b.BillNo, b.TotAmt, b.TaxInclusive, b.SerCharge, b.Tip, b.PymtTime ,k.Itm_Portion,ip.Name Order By k.TaxType, m.ItemNm";
		
		return $this->db2->query($q)->result_array();

	}

	
	
}
