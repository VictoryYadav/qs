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
	public function gettingBiliingData($dbname, $EID, $billId, $CustId, $flag){

		$my_db = $this->session->userdata('my_db');
		$comDb = $this->db2;
		if($dbname != $my_db){
			$comDb = $this->load->database($dbname, TRUE);
		}

		$custAnd = ' ';
		if($flag == 'cust'){
			$custAnd = " and km.CustId = $CustId";
		}else{
			$bd = $this->db2->select('CustId')->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))->row_array();
			if(!empty($bd['CustId'])){
				$custAnd = ' and km.CustId = '.$bd['CustId'];	
			}
		}

		// if there is in error for user table we have to create two query 1 from customer to join user table and another query remove join to users table for restaurant side, currently we are adding user with custid =0 in users table

		$EType = $this->session->userdata('EType');
		

		$qry = " (k.Stat = 2 or k.Stat = 3)";
		if($EType == 5){
			$qry = " k.Stat = 3";
		}

		$billData = $comDb->query("SELECT ((k.ItmRate+m.PckCharge)* sum(k.Qty) * b.splitPercent) as ItemAmt, m.ItemNm, k.CustItemDesc, k.ItmRate, sum(k.Qty) * b.splitPercent as Qty, (COUNT(k.TaxType)) as Tx, k.TaxType, k.Stat, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno, e.BillName, b.BillPrefix, b.BillSuffix, b.TaxInclusive, e.PhoneNos, e.Remarks, e.Tagline, b.BillNo, b.TotItemDisc, b.BillDiscAmt,b.custDiscAmt, b.TotPckCharge,  b.DelCharge, b.TotAmt,b.PaidAmt, b.SerCharge as bservecharge,b.SerChargeAmt, b.Tip,b.TableNo, b.billTime as BillDt,ip.Name as Portions, k.Itm_Portion, u.FName, u.LName, u.MobileNo  from Kitchen k, KitchenMain km, Billing b, MenuItem m, Eatary e , ItemPortions ip, Users u where u.CustId = km.CustId and k.Itm_Portion = ip.IPCd and k.ItemId = m.ItemId and $qry and (km.CNo = k.CNo or km.MCNo = k.MCNo) and (km.CNo = b.CNo or km.MCNo = b.CNo) and e.EID = km.EID and k.EID = km.EID  and b.EID = km.EID  and b.ChainId = km.ChainId and km.EID = $EID and b.BillId = $billId $custAnd Group By m.ItemNm, k.ItmRate, k.ItemTyp, k.CustItemDesc, k.TaxType, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno,  e.PhoneNos, e.Remarks, k.Stat, e.Tagline, b.BillNo, b.TotAmt, b.TaxInclusive, b.SerCharge, b.Tip, b.billTime ,k.Itm_Portion,ip.Name Order By k.TaxType, m.ItemNm")->result_array();
// print_r($this->db2->last_query());die;
		if(!empty($billData)){
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
				$TaxData = $comDb->query("SELECT t.ShortName, t.TaxPcent, t.TaxType, t.Included, Sum(bt.TaxAmt) as SubAmtTax, t.rank from Tax t, BillingTax bt where bt.EID=t.EID and bt.TNo=t.TNo and bt.EID=$EID and bt.BillId = $billId and bt.TNo=t.TNo and t.TaxType = $value group by t.ShortName,t.TaxPcent, t.TaxType, t.Included ,t.rank order by t.rank")->result_array();
				
			    $taxDataArray[$value] = $TaxData;
			}
			$data['billData'] = $billData;
			$data['taxDataArray'] = $taxDataArray;
			$data['ra'] = $comDb->query("SELECT AVG(ServRtng) as serv, AVG(AmbRtng) as amb,avg(VFMRtng) as vfm, AVG(rd.ItemRtng) as itm FROM Ratings r, RatingDet rd WHERE r.BillId= $billId and r.EID=".$EID)->result_array();
			return $data;
		}else{
			$data['billData'] = '';
			$data['taxDataArray'] = 0;
			return $data;
		}

	}

	private function calculatTotalTax($total_tax, $new_tax){
	    return $total_tax + $new_tax;
	}

	//bill.repo.php
	public function getTaxDataArray($EID, $CNo){

		$q = "SELECT (if (k.ItemTyp > 0,(CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotPckCharge,  ip.Name as Portion, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.CNo = km.CNo AND (km.CNo = $CNo OR km.MCNo = $CNo) and k.TaxType>0 group by km.CNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm, date(km.LstModDt), k.TaxType, ip.Name, c.ServChrg, c.Tips  order by TaxType, m.ItemNm Asc";
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

		$EID = authuser()->EID;
        $RUserId = authuser()->RUserId;

		return $this->db2->select('ur.Name,ur.RoleTyp,ur.pageUrl,ur.Rank, ur.PhpPage')
                        ->order_by('ur.Rank', 'ASC')
                        ->join('UserRolesAccess ura', 'ura.RoleId = ur.RoleId','inner')
                        ->get_where('UserRoles ur', 
                            array('ura.RUserId' => $RUserId,
                                'EID' => $EID)
                                )
                        ->result_array();
	}

	// common for create user at first time enter our app
	public function createCustomerUser($mobile){

		$localDb = $this->db2->get_where('Users', array('MobileNo' => $mobile))->row_array();
		if(empty($localDb)){

			$genTblDb = $this->load->database('GenTableData', TRUE);

	        $gen_check = $genTblDb->select('*')
	                            ->get_where('AllUsers', array('MobileNo' => $mobile))
	                            ->row_array();
	        if(!empty($gen_check)){

	            $CustId = $gen_check['CustId'];
	            
	            $data1['CustId']    = $gen_check['CustId'];
	            $data1['FName']     = $gen_check['FName'];
	            $data1['LName']     = $gen_check['LName'];
	            $data1['email']     = $gen_check['email'];
	            $data1['MobileNo']  = $gen_check['MobileNo'];
	            $data1['DOB']       = $gen_check['DOB'];
	            $data1['Gender']    = $gen_check['Gender'];
	            insertRecord('Users',$data1);    
	        }else{
	        	$data['MobileNo'] = $mobile;

	        	$Adata = $data;
	        	$Adata['EID'] = authuser()->EID;
	        	$Adata['page'] = 'offline order';
	            $genTblDb->insert('AllUsers', $Adata);
	            $CustId = $genTblDb->insert_id();
	            
	            $data['CustId'] = $CustId;
	            insertRecord('Users',$data);
	        }
		}
	}

	public function generate_otp($mobile, $page){
		$otp = rand(9999,1000);
        $this->session->set_userdata('cust_otp', $otp);
        // $this->session->set_userdata('cust_otp', '1212');
        $otpData['mobileNo'] = $mobile;
        $otpData['otp'] = $otp;
        $otpData['stat'] = 0;
        $otpData['pageRequest'] = $page;

        if($mobile){
            $smsRes = sendSMS($mobile, $otp);
            if($smsRes){
                $otpData['stat'] = 1;
            }
            insertRecord('OTP', $otpData);
        }
        return $otp;
	}

	public function SettlePayment($billId, $MergeNo){

		$EID = authuser()->EID;
        $EType = $this->session->userdata('EType');

        updateRecord('Billing', array('Stat' => 1,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));

        // print_r($q1);
        updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));

        if ($EType == 5) {
        	$this->db2->query("UPDATE Eat_tables SET MergeNo = TableNo, Stat = 0 where EID = $EID and MergeNo = $MergeNo");
        	// check for split bill payments , for km tables
        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");
        }else{
        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, k.Stat = 3, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 2) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");
        }
        
	}

	
}
