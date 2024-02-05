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
				// $custAnd = ' and km.CustId = '.$bd['CustId'];	
			}
		}

		// if there is in error for user table we have to create two query 1 from customer to join user table and another query remove join to users table for restaurant side, currently we are adding user with custid =0 in users table

		$EType = $this->session->userdata('EType');
		
		$qry = " (k.Stat = 2 or k.Stat = 3)";
		if($EType == 5){
			$qry = " k.Stat = 3";
		}

		$langId = $this->session->userdata('site_lang');
        $itmName = "m.ItemNm$langId as ItemNm";
        $ipName = "ip.Name$langId as Portions";

		$billData = $comDb->query("SELECT ((k.ItmRate)* sum(k.Qty) * b.splitPercent) as ItemAmt, $itmName, k.CustItemDesc, k.TA, k.ItmRate, (sum(k.Qty) * b.splitPercent) as Qty, (COUNT(k.TaxType)) as Tx, k.TaxType, k.Stat, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno, e.BillerName, b.BillPrefix, b.BillSuffix, e.PhoneNos, e.Remarks, e.Tagline, b.BillNo,b.BillId, b.TotItemDisc, b.BillDiscAmt,b.custDiscAmt, b.TotPckCharge,  b.DelCharge, b.TotAmt,b.PaidAmt, b.SerCharge as bservecharge,b.SerChargeAmt, b.Tip,b.TableNo,b.MergeNo, b.billTime as BillDt, $ipName , k.Itm_Portion,b.CustId,b.CellNo  from Kitchen k, KitchenMain km, Billing b, MenuItem m, Eatary e , ItemPortions ip where k.Itm_Portion = ip.IPCd and k.ItemId = m.ItemId and $qry and ((km.CNo = k.CNo) or (km.MCNo = k.MCNo)) and ( km.MergeNo = k.MergeNo) and ((km.CNo = b.CNo) or (km.MCNo = b.CNo)) and (km.MergeNo = b.MergeNo) and e.EID = km.EID and k.EID = km.EID  and b.EID = km.EID and km.EID = $EID and km.EID=m.EID and b.BillId = $billId $custAnd Group By k.ItemId, k.ItmRate, km.MCNo, km.MergeNo, k.CustItemDesc, k.TaxType ,k.Itm_Portion Order By k.TaxType, m.ItemNm1")->result_array();

// print_r($this->db2->last_query());die;
		// echo "<pre>";
		// print_r($billData);die;
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

			$taxName = "t.ShortName$langId as ShortName";

			$taxDataArray = array();
			foreach ($tax_type_array as $key => $value) {
				$TaxData = $comDb->query("SELECT $taxName, t.TaxPcent, t.TaxType, t.Included, Sum(bt.TaxAmt) as SubAmtTax, t.rank from Tax t, BillingTax bt where bt.TNo=t.TNo and bt.EID=$EID and bt.BillId = $billId and bt.TNo=t.TNo and t.TaxType = $value group by t.ShortName1,t.TaxPcent, t.TaxType, t.Included ,t.rank order by t.rank")->result_array();
				
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

	public function getMenuList(){

		$EID = authuser()->EID;
        $RUserId = authuser()->RUserId;

        $langId = $this->session->userdata('site_lang');
        $lname = "ur.Name$langId as LngName";
        $select_sql = "$lname, ur.RoleTyp, ur.pageUrl, ur.Rank, ur.PhpPage";

		return $this->db2->select($select_sql)
                        ->order_by('ur.Rank', 'ASC')
                        ->join('UserRolesAccess ura', 'ura.RoleId = ur.RoleId','inner')
                        ->get_where('UserRoles ur', 
                            array('ura.RUserId' => $RUserId,
                                'ura.EID' => $EID,
                                'ur.Stat' => 0)
                                )
                        ->result_array();
	}

	public function getLangMenuList(){
		$EID = authuser()->EID;
		$langId = $this->session->userdata('site_lang');
		$lname = "Name$langId as LngName";
        $select_sql = "$lname, Name1, LCd, EID, Rank";

		return $this->db2->select($select_sql)
                        ->order_by('Rank', 'ASC')
                        ->get_where('Eat_Lang', 
                            				array('Stat' => 0, 'EID' => $EID)
                                )
                        ->result_array();	
	}

	// common for create user at first time enter our app
	public function createCustomerUser($mobile){
		$CustId = 0;
		if(!empty($mobile)){
			$localDb = $this->db2->get_where('Users', array('MobileNo' => $mobile))->row_array();
			if(empty($localDb)){

				$genTblDb = $this->load->database('GenTableData', TRUE);

		        $gen_check = $genTblDb->select('*')
		                            ->get_where('AllUsers', array('MobileNo' => $mobile))
		                            ->row_array();
		        if(!empty($gen_check)){

		            $CustId = $gen_check['CustId'];
		            
		            $data1['CustId']    = $CustId;
		            $data1['FName']     = $gen_check['FName'];
		            $data1['LName']     = $gen_check['LName'];
		            $data1['email']     = $gen_check['email'];
		            $data1['MobileNo']  = $gen_check['MobileNo'];
		            $data1['DOB']       = $gen_check['DOB'];
		            $data1['Gender']    = $gen_check['Gender'];
		            $data1['visit'] = 1;
		            $data['PWDHash'] = md5('eatout246');
		            insertRecord('Users',$data1);    
		        }else{
		        	$data['MobileNo'] = $mobile;

		        	$Adata = $data;
		        	$Adata['EID'] = authuser()->EID;
		        	$Adata['page'] = 'offline order';
		            $genTblDb->insert('AllUsers', $Adata);
		            $CustId = $genTblDb->insert_id();
		            
		            $data['CustId'] = $CustId;
		            $data['visit'] = 1;
		            $data['PWDHash'] = md5('eatout246');
		            insertRecord('Users',$data);
		        }
			}else{
				$CustId = $localDb['CustId'];
			}
		}
		return $CustId;
	}

	public function generate_otp($mobile, $page){
		// $otp = rand(9999,1000);
		$otp = 1212;
        // $this->session->set_userdata('cust_otp', $otp);
        $this->session->set_userdata('cust_otp', '1212');
        $otpData['mobileNo'] = $mobile;
        $otpData['otp'] = $otp;
        $otpData['stat'] = 0;
        $otpData['pageRequest'] = $page;

        if($mobile){
        	$msg = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            // $smsRes = sendSMS($mobile, $msg);
            $smsRes = 1;
            if($smsRes){
                $otpData['stat'] = 1;
            }
            insertRecord('OTP', $otpData);
        }
        return $otp;
	}

	public function generate_only_otp(){
		$otp = rand(9999,1000);
		// $otp = 1212;
        $this->session->set_userdata('cust_otp', $otp);
        return $otp;
	}

	public function SettlePayment($billId, $MergeNo, $MCNo){

		$EID = authuser()->EID;
        $EType = $this->session->userdata('EType');

        $this->db2->trans_start();

        updateRecord('Billing', array('Stat' => 1,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));

        // print_r($q1);
        updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));

        if ($EType == 5) {
        	$billSplit = $this->db2->select('splitTyp')->get_where('Billing', array('BillId' => $billId))->row_array();
        	if($billSplit['splitTyp'] == 0){
	        	$this->db2->query("UPDATE Eat_tables SET MergeNo = TableNo, Stat = 0 where EID = $EID and MergeNo = '$MergeNo'");
	        	// check for split bill payments , for km tables
	        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");
        	}else{

        		$splitbilDT = $this->db2->query("SELECT sum(bp.PaidAmt) as rcvdamt, (SELECT sum(b1.PaidAmt) from Billing b1 where b1.CNo=b.CNo and b1.EID= $EID) as totpayable from Billing b,BillPayments bp     where bp.MCNo=b.CNo and b.BillId=bp.BillId and b.CNo= $MCNo and b.EID = bp.EID and b.EID = $EID")->row_array();
        		if(!empty($splitbilDT)){
        			if($splitbilDT['rcvdamt'] == $splitbilDT['totpayable']){
        				$this->db2->query("UPDATE Eat_tables SET MergeNo = TableNo, Stat = 0 where EID = $EID and MergeNo = '$MergeNo'");
		        	// check for split bill payments , for km tables
		        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");		
        			}
        		}
        	}
        }else{
        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, k.Stat = 3, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 2) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");
        }
        $this->db2->trans_complete();
        
	}

	public function getUserName($custId){
		$name = '';
		if($custId > 0){
			$user = $this->db2->select("FName, LName")->get_where('Users', array('CustId' => $custId))->row_array();
			if(!empty($user)){
				if($user['FName'] !='-'){
					$name = $user['FName'];	
				}
				if($user['LName'] !='-'){
					$name = $name.' '.$user['LName'];	
				}
			}
		}
		return $name;
	}

	public function getRestVist($CellNo){
		$visit = 0;
		if($CellNo > 0){
			$user = $this->db2->select("visit")->get_where('Users', array('MobileNo' => $CellNo))->row_array();
			if(!empty($user)){
				$visit = $user['visit'];
			}
		}
		return $visit;	
	}

	public function getTaxCalculation($kitcheData, $EID, $CNo, $MergeNo){

		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2; 

		$intial_value = $kitcheData[0]['TaxType'];
		$ServChrg = $kitcheData[0]['ServChrg'];
		$Tips = $kitcheData[0]['Tips'];

		$tax_type_array = array();
		$tax_type_array[$intial_value] = $intial_value;
		foreach ($kitcheData as $key => $value) {
		    if($value['TaxType'] != $intial_value){
		        $intial_value = $value['TaxType'];
		        $tax_type_array[$intial_value] = $value['TaxType'];
		    }
		}

		$langId = $this->session->userdata('site_lang');
        $taxName = "t.ShortName$langId as ShortName";

		$taxDataArray = array();
		foreach ($tax_type_array as $key => $value) {

		    $TaxData = $this->db2->query("SELECT $taxName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.OrigRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.OrigRate*k.Qty)) - ((sum(k.OrigRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.OrigRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat = $stat) and k.EID=km.EID and (k.MergeNo = km.MergeNo) and km.MergeNo = '$MergeNo' and (km.CNo=$CNo or km.MCNo =$CNo) and k.CNo = km.CNo and t.TaxType = k.TaxType and t.TaxType = $value AND km.BillStat = 0 group by t.ShortName1,t.TNo,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.rank")->result_array();
		    $taxDataArray[$value] = $TaxData;
		}

		// $orderAmount= 0;
		// foreach ($taxDataArray as $key => $value) {
		//     $total_tax = 0;
		//     $sub_total = 0;		    

		//     foreach ($value as $key1 => $value1) {
		//         $tno = $value[$key1]['TNo'];
		//         if($key1 != 0){
		//             $tno = $value[$key1-1]['TNo'];
		//         }
		//         $total_tax = $this->calculatTotalTax($total_tax,number_format($value1['SubAmtTax'],2));
		        
		//         if($tno == $value1['TNo']){
		//             $sub_total = $sub_total + $value1['ItemAmt'];
		//         }

		//         if(count($value) == ($key1 + 1) && $value1['Included'] >= 5){
		//            $sub_total = $sub_total  + $total_tax;
		//         }
		//     }
		//     $orderAmount = $orderAmount + $sub_total;
		// }

		$data['taxDataArray'] 	 = $taxDataArray;
		// $data['orderAmount'] = $orderAmount;
		return $data;
	}

	private function calculatTotalTax($total_tax, $new_tax){
		return $total_tax + $new_tax;
	}

	public function getSeatNoByCNo($CNo){
		$EID = authuser()->EID;
		$data =  $this->db2->select('SeatNo')->get_where('KitchenMain', array('CNo' => $CNo,'EID' => $EID, 'BillStat' => 0))->row_array();
		return $data['SeatNo']; 
	}

	public function getSchemeTypeCategory($SchCatg){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		$scheme =  $this->db2->select("$lname")
					->get_where('CustOfferTypes', array(
								'Stat' => 0,
								'SchCatg' => $SchCatg))
					->row_array();
		return $scheme['Name'];
	}

	public function getTransactionName($TagId){
		$langId = $this->session->userdata('site_lang');
        $lname = "TDesc$langId as TDesc";
		$data =  $this->db2->select("$lname")
					->get_where('stockTrans', array('TagId' => $TagId))
					->row_array();		
		return $data['TDesc'];
	}

	public function getDayName($DayNo){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		$day =  $this->db2->select("$lname")
					->get_where('WeekDays', array('DayNo' => $DayNo))
					->row_array();	
		return $day['Name'];
	}
	
	public function getDayNumber($name){
		$d = 0;
		if(!empty($name)){
			$day =  $this->db2->select("DayNo")
						->get_where('WeekDays', array('Name1' => $name))
						->row_array();	
			if(!empty($day)){
				$d = $day['DayNo'];
			}
		}
		return $d;
	}

}
