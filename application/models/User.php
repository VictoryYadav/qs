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

	// common function for both sides
	public function gettingBiliingData($dbname, $EID, $billId){

		// $billData = $this->db2->query("SELECT SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as ItemAmt, (if (k.ItemTyp > 0, (CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm, k.ItmRate, SUM(k.Qty) as Qty, (COUNT(k.TaxType)) as Tx, k.TaxType, k.Stat, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno, b.BillPrefix, b.BillSuffix, b.TaxInclusive, e.PhoneNos, e.Remarks, e.Tagline, b.BillNo, b.TotItemDisc, b.BillDiscAmt, b.TotPckCharge,  b.DelCharge, b.TotAmt, b.SerCharge as bservecharge, b.Tip, b.PymtTime as BillDt,ip.Name as Portions, k.Itm_Portion  from ".$dbname."Kitchen k, ".$dbname."KitchenMain km, ".$dbname."Billing b, ".$dbname."MenuItem m, ".$dbname."Eatary e , ".$dbname."ItemPortions ip where k.Itm_Portion = ip.IPCd and k.ItemId = m.ItemId and (k.Stat<>4 and k.Stat<>6 and k.Stat<>7 and k.Stat<>99) and km.CNo = k.CNo AND km.BillStat = b.BillId and e.EID = km.EID and k.EID = km.EID  and b.EID = km.EID  and b.ChainId = km.ChainId and km.EID = $EID and b.BillId = $billId Group By m.ItemNm, k.ItmRate, k.ItemTyp, k.CustItemDesc, k.TaxType, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno,  e.PhoneNos, e.Remarks, k.Stat, e.Tagline, b.BillNo, b.TotAmt, b.TaxInclusive, b.SerCharge, b.Tip, b.PymtTime ,k.Itm_Portion,ip.Name Order By k.TaxType, m.ItemNm")->result_array();
		// Repository : billing/bill_print.repo.php
		$billData = $this->db2->query("SELECT SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as ItemAmt, (if (k.ItemTyp > 0, (CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm, k.ItmRate, SUM(k.Qty) as Qty, (COUNT(k.TaxType)) as Tx, k.TaxType, k.Stat, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno, b.BillPrefix, b.BillSuffix, b.TaxInclusive, e.PhoneNos, e.Remarks, e.Tagline, b.BillNo, b.TotItemDisc, b.BillDiscAmt, b.TotPckCharge,  b.DelCharge, b.TotAmt, b.SerCharge as bservecharge, b.Tip, b.PymtTime as BillDt,ip.Name as Portions, k.Itm_Portion  from Kitchen k, KitchenMain km, Billing b, MenuItem m, Eatary e , ItemPortions ip where k.Itm_Portion = ip.IPCd and k.ItemId = m.ItemId and (k.Stat<>4 and k.Stat<>6 and k.Stat<>7 and k.Stat<>99) and km.CNo = k.CNo AND km.BillStat = b.BillId and e.EID = km.EID and k.EID = km.EID  and b.EID = km.EID  and b.ChainId = km.ChainId and km.EID = $EID and b.BillId = $billId Group By m.ItemNm, k.ItmRate, k.ItemTyp, k.CustItemDesc, k.TaxType, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno,  e.PhoneNos, e.Remarks, k.Stat, e.Tagline, b.BillNo, b.TotAmt, b.TaxInclusive, b.SerCharge, b.Tip, b.PymtTime ,k.Itm_Portion,ip.Name Order By k.TaxType, m.ItemNm")->result_array();

		$intial_value = $billData[0]['TaxType'];
		$tax_type_array = array();
		$tax_type_array[$intial_value] = $intial_value;
		
		foreach ($billData as $key) {
		    if($key['TaxType'] != $intial_value){
		        $intial_value = $key['TaxType'];
		        $tax_type_array[$intial_value] = $key['TaxType'];
		    }
		}

		$taxDataArray = array();
		foreach ($tax_type_array as $key => $value) {
			$TaxData = $this->db2->query("SELECT t.ShortName, t.TaxPcent, t.TaxType, t.Included, Sum(bt.TaxAmt) as SubAmtTax, t.rank from Tax t, BillingTax bt where bt.EID=t.EID and bt.TNo=t.TNo and bt.EID=$EID and bt.BillId = $billId and bt.TNo=t.TNo and t.TaxType = $value group by t.ShortName,t.TaxPcent, t.TaxType, t.Included ,t.rank order by t.rank")->result_array();
			
		    $taxDataArray[$value] = $TaxData;
		}
		$data['billData'] = $billData;
		$data['taxDataArray'] = $taxDataArray;
		return $data;
	}

	private function calculatTotalTax($total_tax, $new_tax){
	    return $total_tax + $new_tax;
	}

	//bill.repo.php
	public function getTaxDataArray($EID, $CNo){

		$q = "SELECT (if (k.ItemTyp > 0,(CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7 AND k1.Stat<>9  AND k1.Stat<>99) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7  AND k1.Stat<>9 AND k1.Stat<>99) GROUP BY k1.EID) as TotPckCharge,  ip.Name as Portion, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,c.OnPymt,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.Stat<>10 AND k.Stat<>99) and km.EID = k.EID and km.EID = $EID And k.CNo = km.CNo AND (km.CNo = $CNo OR km.MCNo = $CNo) and k.TaxType>0 group by km.CNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm, date(km.LstModDt), k.TaxType, ip.Name, c.ServChrg, c.Tips, c.OnPymt  order by TaxType, m.ItemNm Asc";
		$kitcheData = $this->db2->query($q)->result_array();
		// print_r($kitcheData);exit();
		$intial_value = $kitcheData[0]['TaxType'];

		$ServChrg = $kitcheData[0]['ServChrg'];
		$Tips = $kitcheData[0]['Tips'];
		$Resturant_name = $kitcheData[0]['Name'];

		$tax_type_array = array();
		$tax_type_array[$intial_value] = $intial_value;

		foreach ($kitcheData as $key => $value) {
		    if($value['TaxType'] != $intial_value){
		        $intial_value = $value['TaxType'];
		        $tax_type_array[$intial_value] = $value['TaxType'];
		    }
		}

		$taxDataArray = array();

		foreach ($tax_type_array as $key => $value) {
		    $q = "SELECT t.ShortName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included, (sum(k.ItmRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.ItmRate*k.Qty)) - ((sum(k.ItmRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.ItmRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where k.EID=km.EID and k.CNo=km.CNo and (km.CNo=$CNo or km.MCNo =$CNo) and t.TaxType = k.TaxType and t.TaxType = $value  and t.EID= $EID AND km.BillStat = 0 group by t.ShortName,t.TNo,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.rank";
		    // print_r($q);exit();
		    $TaxData = $this->db2->query($q)->result_array();
		    // and CurDate() between FrmDt and EndDt
			// print_r($TaxData);exit();
		    $taxDataArray[$value] = $TaxData;
		}

		return $taxDataArray;

		$orderAmount= 0;
		foreach ($taxDataArray as $key => $value) {
		    $total_tax = 0;
		    $sub_total = 0;
		    foreach ($value as $key1 => $value1) {
		        $tno = $value[$key1]['TNo'];
		        if($key1 != 0){
		            $tno = $value[$key1-1]['TNo'];
		        }

		        $total_tax = calculatTotalTax($total_tax,number_format($value1['SubAmtTax'],2));

		        if($tno == $value1['TNo']){
		            $sub_total = $sub_total + $value1['ItemAmt'];
		        }

		        if(count($value) == ($key1 + 1) && $value1['Included'] >= 5){
		           $sub_total = $sub_total  + $total_tax;
		        }
		    }

		    $orderAmount = $orderAmount + $sub_total;
		}
	}

	public function getMenuList(){
		return $this->db2->select('Name,RoleTyp,pageUrl,Rank')
                                ->order_by('Rank','ASC')
                                ->get_where('UserRoles', array('Stat' => 0))->result_array();
	}



	
	
}
