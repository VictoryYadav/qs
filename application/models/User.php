<?phpdefined('BASEPATH') OR exit('No direct script access allowed');class User extends CI_Model{	private $db2;	public function __construct()	{		parent::__construct();		        $my_db = $this->session->userdata('my_db');        $this->db2 = $this->load->database($my_db, TRUE);	}	public function recordInsert($tbl,$data){		$this->db2->insert($tbl,$data);		return $this->db2->insert_id();	}	public function recordUpdate($tbl,$data,$where){		$this->db2->update($tbl, $data, $where);	}	public function recordDelete($tbl,$where){		$this->db2->delete($tbl,$where);	}	public function countAllRecord($tbl){		$query="SELECT COUNT(*) as total FROM ".$tbl;		$result=$this->db2->query($query);		if($result->num_rows() > 0)		{			return $result->row_array();			}	}	public function getAllRecord($tbl=null,$where=null){		$this->db2->select('*')->from($tbl);		if(!empty($where)){			$this->db2->where($where);		}	    $query = $this->db2->get();	    if ($query->num_rows() > 0) {	    	if(!empty($where)){	    		return $query->row_array();	    	}else{	    		return $query->result_array();	    	}		} else {			return false;		}	}	// common function for both sides	public function gettingBiliingData($dbname, $EID, $billId, $CustId, $flag){		$my_db = $this->session->userdata('my_db');		$comDb = $this->db2;		if($dbname != $my_db){			$comDb = $this->load->database($dbname, TRUE);		}		$bd = $comDb->select('CustId, CTyp, splitTyp')				->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))				->row_array();		$wh_ctyp = "";		if($bd['splitTyp'] == 1){			$wh_ctyp = " and m.CTyp != 1";	        if($bd['CTyp'] == 1){	        	$wh_ctyp = " and m.CTyp = 1";	        }		}		if(!empty($bd['CustId'])){					}		$custAnd = ' ';		if($flag == 'cust'){			$custAnd = " and km.CustId = $CustId";		}		$EType = $this->session->userdata('EType');				$qry = " (k.Stat = 2 or k.Stat = 3)";		if($EType == 5){			$qry = " k.Stat = 3";		}		$langId = $this->session->userdata('site_lang');        $itmName = "m.Name$langId";        $ipname = "ip.Name$langId";        $discountName = "dis.Name$langId";		$billData = $comDb->query("SELECT ((k.OrigRate)* k.Qty * b.splitPercent) as ItemAmt, (case when $itmName != '-' Then $itmName ELSE m.Name1 end) as ItemNm, k.CustItemDesc, k.TA, k.ItmRate, k.OrigRate, (k.Qty) * b.splitPercent as Qty, (COUNT(k.TaxType)) as Tx, k.TaxType, k.Stat, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno, e.BillerName, b.BillPrefix, b.BillSuffix, e.PhoneNos, e.Remarks, e.Tagline, b.BillNo,b.BillId, b.TotItemDisc, b.BillDiscAmt,b.custDiscAmt, b.TotPckCharge,  b.DelCharge, b.TotAmt,b.PaidAmt, b.SerCharge as bservecharge,b.SerChargeAmt, (b.Tip * b.splitPercent) as Tip ,b.TableNo,b.MergeNo, b.billTime as BillDt, b.OType, (case when $discountName != '-' Then $discountName ELSE dis.Name1 end) as discountName, b.discId, b.discPcent, b.autoDiscAmt, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as Portions , k.Itm_Portion,b.CustId, b.CellNo, b.splitTyp  from Kitchen k, KitchenMain km, Billing b left join discounts dis on dis.discId = b.discId, MenuItem m, Eatary e , ItemPortions ip where k.Itm_Portion = ip.IPCd and k.ItemId = m.ItemId and $qry and ((km.CNo = k.CNo) or (km.MCNo = k.MCNo)) and ( km.MergeNo = k.MergeNo) and ((km.CNo = b.CNo) or (km.MCNo = b.CNo)) and (km.MergeNo = b.MergeNo) and e.EID = km.EID and k.EID = km.EID  and b.EID = km.EID and km.EID = $EID and km.EID=m.EID and b.BillId = $billId $custAnd $wh_ctyp Group By k.ItemId, k.OrigRate, k.ItmRate, km.MCNo, km.MergeNo, k.CustItemDesc, k.TaxType ,k.Itm_Portion, k.TA Order By k.TaxType, m.Name1")->result_array();		print_r($this->db2->last_query());die;		if(!empty($billData)){			$intial_value = $billData[0]['TaxType'];			$tax_type_array = array();			$tax_type_array[$intial_value] = $intial_value;						foreach ($billData as $key) {			    if($key['TaxType'] != $intial_value){			        $intial_value = $key['TaxType'];			        $tax_type_array[$intial_value] = $key['TaxType'];			    }			}			$ShortName = "t.Name$langId";			$taxDataArray = array();			foreach ($tax_type_array as $key => $value) {				$TaxData = $comDb->query("SELECT (case when $ShortName != '-' Then $ShortName ELSE t.Name1 end) as ShortName, t.TaxPcent, t.TaxType, t.Included, Sum(bt.TaxAmt) as SubAmtTax, t.rank from Tax t, BillingTax bt where bt.TNo=t.TNo and bt.EID=$EID and bt.BillId = $billId and bt.TNo=t.TNo and t.TaxType = $value group by t.Name1,t.TaxPcent, t.TaxType, t.Included ,t.rank order by t.rank")->result_array();							    $taxDataArray[$value] = $TaxData;			}			$data['billData'] = $billData;			$data['taxDataArray'] = $taxDataArray;			$data['ra'] = $comDb->query("SELECT AVG(ServRtng) as serv, AVG(AmbRtng) as amb,avg(VFMRtng) as vfm, AVG(rd.ItemRtng) as itm FROM Ratings r, RatingDet rd WHERE r.BillId= $billId and r.EID=".$EID)->result_array();			return $data;		}else{			$data['billData'] = '';			$data['taxDataArray'] = 0;			return $data;		}	}	public function getMenuList(){		$EID = authuser()->EID;        $RUserId = authuser()->RUserId;        $langId = $this->session->userdata('site_lang');                $lname = "ur.Name$langId";        $select_sql = "(case when $lname != '-' Then $lname ELSE ur.Name1 end) as LngName, ur.RoleTyp, ur.pageUrl, ur.Rank, ur.PhpPage, ur.roleGroup";		return $this->db2->select($select_sql)                        ->order_by('ur.roleGroup, ur.Rank', 'ASC')                        ->join('UserRolesAccess ura', 'ura.RoleId = ur.RoleId','inner')                        ->get_where('UserRoles ur',                             array('ura.RUserId' => $RUserId,                                'ura.EID' => $EID,                                'ur.Stat' => 0)                                )                        ->result_array();	}	public function getLangMenuList(){		$EID = authuser()->EID;		$langId = $this->session->userdata('site_lang');		$lname = "Name$langId";        $select_sql = "(case when $lname != '-' Then $lname ELSE Name1 end) as LngName, Name1, LCd, EID, Rank, langId";		return $this->db2->select($select_sql)                        ->order_by('Rank', 'ASC')                        ->get_where('Eat_Lang',                             				array('Stat' => 0, 'EID' => $EID)                                )                        ->result_array();		}		public function getLngShortName($langId){		$EID = authuser()->EID;		return $this->db2->select("shortName")                        ->get_where('Eat_Lang',                             				array('EID' => $EID, 'LCd' => $langId)                                )                        ->row('shortName');		}	// common for create user at first time enter our app	public function createCustomerUser($mobile){		$EID 		= $this->session->userdata('EID');		$CountryCd 	= $this->session->userdata('CountryCd');		$pCountryCd = $this->session->userdata('pCountryCd');		if($pCountryCd > 0){			$CountryCd = $pCountryCd;		}		$CustId = 0;		$mobile = $CountryCd.$mobile;		if(!empty($mobile)){			$localDb = $this->db2->get_where('Users', array('MobileNo' => $mobile))->row_array();			if(empty($localDb)){				$genTblDb = $this->load->database('GenTableData', TRUE);		        $gen_check = $this->checkUserFromGenDb($mobile);		        if(!empty($gen_check)){		            $CustId = $gen_check['CustId'];		            		            $data1['CustId']    = $CustId;		            $data1['FName']     = $gen_check['FName'];		            $data1['LName']     = $gen_check['LName'];		            $data1['email']     = $gen_check['email'];		            $data1['MobileNo']  = $gen_check['MobileNo'];		            $data1['DOB']       = $gen_check['DOB'];		            $data1['Gender']    = $gen_check['Gender'];		            $data1['visit'] 	= 1;		            $data1['PWDHash'] 	= md5('eatout246');		            $data1['EID'] 		= $EID;		            insertRecord('Users',$data1);    		        }else{		        	$data['MobileNo'] 	= $mobile;		        	$Adata 				= $data;		        	$Adata['EID'] 		= $EID;		        	$Adata['page'] 		= 'offline order';		            $genTblDb->insert('AllUsers', $Adata);		            $CustId 			= $genTblDb->insert_id();		            		            $data['CustId'] 	= $CustId;		            $data['visit'] 		= 1;		            $data['PWDHash'] 	= md5('eatout246');		            $data['EID'] 		= $EID;		            insertRecord('Users',$data);		        }			}else{					$CustId 			= $localDb['CustId'];			}		}		return $CustId;	}	public function checkUserFromGenDb($mobile){		$genTblDb = $this->load->database('GenTableData', TRUE);		return $genTblDb->select('*')		                ->get_where('AllUsers', array('MobileNo' => $mobile))		                ->row_array();	}	public function generate_otp($mobile, $page){		$otp = rand(9999,1000);		// $otp = 1212;        $this->session->set_userdata('cust_otp', $otp);        // $this->session->set_userdata('cust_otp', '1212');        $otpData['mobileNo'] = $mobile;        $otpData['otp'] = $otp;        $otpData['stat'] = 0;        $otpData['EID'] = $this->session->userdata('EID');        $otpData['pageRequest'] = $page;        if($mobile){        	$msg = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";            $smsRes = sendSMS($mobile, $msg);            // $smsRes = 1;            if($smsRes){                $otpData['stat'] = 1;            }            insertRecord('OTP', $otpData);        }        return $otp;	}	public function generate_only_otp(){		$otp = rand(9999,1000);		// $otp = 1212;        $this->session->set_userdata('cust_otp', $otp);        return $otp;	}	public function SettlePayment($billId, $MergeNo, $MCNo){		$EID = $this->session->userdata('EID');        $EType = $this->session->userdata('EType');        $this->db2->trans_start();        updateRecord('Billing', array('Stat' => 1,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));        // print_r($q1);        updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));        if ($EType == 5) {        	$billSplit = $this->db2->select('splitTyp')->get_where('Billing', array('BillId' => $billId))->row_array();        	        	if($billSplit['splitTyp'] == 0){	        	// check for split bill payments , for km tables	        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");        	}else{        		$splitbilDT = $this->db2->query("SELECT sum(bp.PaidAmt) as rcvdamt, (SELECT sum(b1.PaidAmt) from Billing b1 where b1.CNo=b.CNo and b1.EID= $EID) as totpayable from Billing b,BillPayments bp     where bp.MCNo=b.CNo and b.BillId=bp.BillId and b.CNo= $MCNo and b.EID = bp.EID and b.EID = $EID")->row_array();        		if(!empty($splitbilDT)){        			if($splitbilDT['rcvdamt'] == $splitbilDT['totpayable']){		        	// check for split bill payments , for km tables		        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");		        			}        		}        	}        }else{        	$this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, k.Stat = 3, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 2) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");        }        $kmDT = $this->db2->query("SELECT CNo from KitchenMain where EID = $EID and MergeNo = '$MergeNo' and payRest = 0 and CnfSettle = 0")->row_array();        				if(empty($kmDT)){    		$this->db2->query("UPDATE Eat_tables SET MergeNo = TableNo, Stat = 0 where EID = $EID and MergeNo = '$MergeNo'");		}		        $this->db2->trans_complete();        	}	public function getUserName($custId){		$name = '';		if($custId > 0){			$user = $this->db2->select("FName, LName")->get_where('Users', array('CustId' => $custId))->row_array();			if(!empty($user)){				if($user['FName'] !='-'){					$name = $user['FName'];					}				if($user['LName'] !='-'){					$name = $name.' '.$user['LName'];					}			}		}		return $name;	}	public function getRestVist($CellNo){		$visit = 0;		if($CellNo > 0){			$user = $this->db2->select("visit")->get_where('Users', array('MobileNo' => $CellNo))->row_array();			if(!empty($user)){				$visit = $user['visit'];			}		}		return $visit;		}	public function getTaxCalculation($kitcheData, $EID, $CNo, $MergeNo, $per_cent){		$EType = $this->session->userdata('EType');		$stat = ($EType == 5)?3:2; 		$intial_value = $kitcheData[0]['TaxType'];		$ServChrg = $kitcheData[0]['ServChrg'];		$Tips = $kitcheData[0]['Tips'];		$tax_type_array = array();		$tax_type_array[$intial_value] = $intial_value;		foreach ($kitcheData as $key => $value) {		    if($value['TaxType'] != $intial_value){		        $intial_value = $value['TaxType'];		        $tax_type_array[$intial_value] = $value['TaxType'];		    }		}		$langId = $this->session->userdata('site_lang');        $taxName = "t.Name$langId";		$taxDataArray = array();		foreach ($tax_type_array as $key => $value) {		    $TaxData = $this->db2->query("SELECT (case when $taxName != '-' Then $taxName ELSE t.Name1 end) as ShortName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.OrigRate*k.Qty * $per_cent)) as ItemAmt, (if (t.Included <5,((sum(k.OrigRate*k.Qty*$per_cent)) - ((sum(k.OrigRate*k.Qty*$per_cent)) / (1+t.TaxPcent/100))),((sum(k.OrigRate*k.Qty*$per_cent))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat = $stat) and k.EID=km.EID and (k.MergeNo = km.MergeNo) and km.MergeNo = '$MergeNo' and (km.CNo=$CNo or km.MCNo =$CNo) and k.CNo = km.CNo and t.TaxType = k.TaxType and t.TaxType = $value AND km.BillStat = 0 group by t.Name1,t.TNo,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.rank")->result_array();		    $taxDataArray[$value] = $TaxData;		}		$data['taxDataArray'] 	 = $taxDataArray;		// $data['orderAmount'] = $orderAmount;		return $data;	}	private function calculatTotalTax($total_tax, $new_tax){		return $total_tax + $new_tax;	}	public function getSeatNoByCNo($CNo){		$EID = authuser()->EID;		$data =  $this->db2->select('SeatNo')->get_where('KitchenMain', array('CNo' => $CNo,'EID' => $EID, 'BillStat' => 0))->row_array();		return $data['SeatNo']; 	}	public function getSchemeTypeCategory($SchCatg){		$langId = $this->session->userdata('site_lang');        $lname = "Name$langId";		$scheme =  $this->db2->select("(case when $lname != '-' Then $lname ELSE Name1 end) as Name")					->get_where('CustOfferTypes', array(								'Stat' => 0,								'SchCatg' => $SchCatg))					->row_array();		return $scheme['Name'];	}	public function getTransactionName($TagId){		$langId = $this->session->userdata('site_lang');        $lname = "Name$langId";		$data =  $this->db2->select("(case when $lname != '-' Then $lname ELSE Name1 end) as TDesc")					->get_where('stockTrans', array('TagId' => $TagId))					->row_array();				return $data['TDesc'];	}	public function getDayName($DayNo){		$langId = $this->session->userdata('site_lang');        $lname = "Name$langId";		$day =  $this->db2->select("(case when $lname != '-' Then $lname ELSE Name1 end) as Name")					->get_where('WeekDays', array('DayNo' => $DayNo))					->row_array();			return $day['Name'];	}		public function getDayNumber($name){		$d = 0;		if(!empty($name)){			$day =  $this->db2->select("DayNo")						->get_where('WeekDays', array('Name1' => $name))						->row_array();				if(!empty($day)){				$d = $day['DayNo'];			}		}		return $d;	}	public function getDiscountDetail($CustId){		$EID = authuser()->EID;		$langId = $this->session->userdata('site_lang');        $lname = "d.Name$langId";		$discounts = [];		$data =  $this->db2->select("u.uId, d.discId, (case when $lname != '-' Then $lname ELSE d.Name1 end) as name, d.pcent, d.visitNo")						->join('discounts d', 'd.discId = u.discId', 'inner')						->get_where('Users u', 										array('u.CustId' => $CustId,											  'u.EID' => $EID,											  'd.EID' => $EID,											  'd.stat' => 0)								)->row_array();		if(!empty($data)){			$discounts = $data;		}		return $discounts;	}	public function getThemeColour(){		$EID = authuser()->EID;		return $this->db2->select("headerClr, footerClr,footerTxtClr, cuisineClr, cuisineTxtClr, filterClr, filterTxtClr, categoryClr, categoryTxtClr, mainSection, menuBtn, successBtn, orderBtn, menuBtnClr, successBtnClr, orderBtnClr")					->get_where('ConfigTheme', array('EID' => $EID, 'Stat' => 1))					->row_array();	}	public function insertOTP($mobile, $otp, $page){		$otpData = array('mobileNo' => $mobile,        				 'otp' => $otp,        				 'stat' => 1,        				 'pageRequest' => $page        				);        insertRecord('OTP', $otpData);	}	public function saveLoyalty($data){		if($data['LId']> 0){			updateRecord('Loyalty', $data, array('LId' => $data['LId'], 'EID' => $data['EID'], 'billId' => $data['billId']));		}else{        	insertRecord('Loyalty', $data);		}	}	public function getMenuRates($ItemId, $IPCd){		return $this->db2->get_where('MenuItemRates', array('ItemId' => $ItemId, 'Itm_Portion' => $IPCd))->row_array();	}	public function getBillBasedOffer(){		$today = date('Y-m-d');		$tommorow = date('Y-m-d', strtotime("+1 day", strtotime($today)));		return $this->db2->select("MinBillAmt, Disc_pcent, Disc_Amt")					->get_where('CustOffers', 									array('EID' => authuser()->EID, 										'FrmDt >=' => $today, 										'ToDt <=' => $tommorow, 										'SchTyp' => 1, 										'SchCatg' => 1)								)					->row_array();	}	public function getOnAccountCust($CustId, $custType){		return $this->db2->get_where('CustList', 									array('CustId' => $CustId, 										'Stat' => 0,										'custType' => $custType)								)					->row_array();	}	public function createRCHistory($data){		$data['EID'] = $this->session->userdata('EID');		$this->db2->insert('rechargeHist', $data);	}	public function getingNameFromMast($MCd){		$EID = $this->session->userdata('EID');		$dt = $this->db2->get_where('Masts', array('EID' => $EID, 'MCd' => $MCd))->row_array();		return $dt['Name'];	}	}