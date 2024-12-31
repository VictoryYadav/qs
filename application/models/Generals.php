<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generals extends CI_Model{

    private $genDB;

	public function __construct()
	{
		parent::__construct();

        $this->genDB = $this->load->database('GenTableData', TRUE);
	}


    public function getUserFromGenDb($MobileNo){
        return $this->genDB->get_where('AllUsers', array('MobileNo' => $MobileNo))->row_array();
    }

    public function getCountryList(){
        return $this->genDB->get_where('countries', array('Stat' => 0))->result_array();
    }

    public function generate_otp($mobile, $page){
        // $otp = rand(9999,1000);
        $otp = 1212;
        $this->session->set_userdata('cust_otp', $otp);
        $otpData['mobileNo']    = $mobile;
        $otpData['otp']         = $otp;
        $otpData['stat']        = 0;
        $otpData['EID']         = 0;
        $otpData['pageRequest'] = $page;

        if($mobile){
            $msg = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            $smsRes = sendSMS($mobile, $msg);
            // $smsRes = 1;
            if($smsRes){
                $otpData['stat'] = 1;
            }
            $this->genDB->insert('OTP', $otpData);
        }
        return $otp;
    }

    public function updateData($table, $data, $whr){
        $this->genDB->update($table, $data, $whr);
    }

    public function getCountries(){
        return $this->genDB->select('*')
                    ->order_by('country_name', 'ASC')
                    ->get_where('countries', array('Stat' => 0))->result_array();
    }

    public function getCityListByCountry($phone_code){
        return $this->genDB->get_where('city', array('status' => 0, 'phone_code' => $phone_code))->result_array();
    }

    public function getBills($CustId, $country=null, $city=null){

        $whr = "";
        if(!empty($country)){
            $whr .= " and ed.CountryCd = ".$country;
        }
        if(!empty($city)){
         $whr .= " and ed.city_id = ".$city;
        }
        
        return $this->genDB->query("SELECT DISTINCT cp.BillId, date(cp.BillDt) as billdt, `cp`.`BillNo`, `cp`.`EID`, `cp`.`PaidAmt`, `cp`.`CustId`, `ed`.`Name`, `ed1`.`DBName`, `ed`.`DBPasswd`, `rt`.`avgBillRtng` FROM `CustPymts` `cp` INNER JOIN `EIDDet` `ed` ON `ed`.`EID` = `cp`.`EID` INNER JOIN `EIDDet` `ed1` ON `ed1`.`EID` = `cp`.`aggEID` LEFT JOIN `Ratings` `rt` ON `rt`.`EID` = `cp`.`EID` WHERE `cp`.`CustId` = $CustId AND `rt`.`CustId` = $CustId $whr ORDER BY `cp`.`BillDt` DESC")->result_array();
    }

    public function gettingBiliingData($dbname, $EID, $billId, $CustId, $flag){

        $comDb = $this->load->database($dbname, TRUE);
        

        $bd = $comDb->select('CustId, CTyp, splitTyp')
                ->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))
                ->row_array();

        $wh_ctyp = "";
        if($bd['splitTyp'] == 1){
            $wh_ctyp = " and m.CTyp != 1";
            if($bd['CTyp'] == 1){
                $wh_ctyp = " and m.CTyp = 1";
            }
        }

        if(!empty($bd['CustId'])){
            
        }

        $custAnd = ' ';
        if($flag == 'cust'){
            $custAnd = " and b.CustId = $CustId";
        }

        $EType = $this->session->userdata('EType');
        
        $qry = " (k.Stat = 2 or k.Stat = 3)";
        if($EType == 5){
            $qry = " k.Stat = 3";
        }

        $itmName = "m.Name1";
        $ipname = "ip.Name1";
        $discountName = "dis.Name1";

        $billData = $comDb->query("SELECT ((k.OrigRate)* k.Qty * b.splitPercent) as ItemAmt, (case when $itmName != '-' Then $itmName ELSE m.Name1 end) as ItemNm, k.CustItemDesc, k.TA, k.ItmRate, k.OrigRate, k.Qty * b.splitPercent as Qty, (COUNT(k.TaxType)) as Tx, k.TaxType, k.Stat, e.Name, e.Addr, e.City, e.Pincode, e.CINNo, e.FSSAINo, e.GSTno, e.BillerName, b.BillPrefix, b.BillSuffix, e.PhoneNos, e.Remarks, e.Tagline, b.BillNo,b.BillId, b.TotItemDisc, b.BillDiscAmt,b.custDiscAmt, b.TotPckCharge,  b.DelCharge, b.TotAmt,b.PaidAmt, b.SerCharge as bservecharge,b.SerChargeAmt, (b.Tip * b.splitPercent) as Tip ,b.TableNo,b.MergeNo, b.billTime as BillDt, b.OType, (case when $discountName != '-' Then $discountName ELSE dis.Name1 end) as discountName, b.discId, b.discPcent, b.autoDiscAmt, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as Portions , k.Itm_Portion,b.CustId, b.CellNo, b.splitTyp  from Kitchen k, KitchenMain km, Billing b left join discounts dis on dis.discId = b.discId, MenuItem m, Eatary e , ItemPortions ip where k.Itm_Portion = ip.IPCd and k.ItemId = m.ItemId and $qry and ((km.CNo = k.CNo) or (km.MCNo = k.MCNo)) and ( km.MergeNo = k.MergeNo) and ((km.CNo = b.CNo) or (km.MCNo = b.CNo)) and (km.MergeNo = b.MergeNo) and e.EID = km.EID and k.EID = km.EID  and b.EID = km.EID and km.EID = $EID and km.EID=m.EID and b.BillId = $billId $custAnd $wh_ctyp Group By k.ItemId, k.OrigRate, k.ItmRate, km.MCNo, km.MergeNo, k.CustItemDesc, k.TaxType ,k.Itm_Portion, k.TA Order By k.TaxType, m.Name1")->result_array();

        // echo "<pre>";print_r($this->db2->last_query());die;

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

            $ShortName = "t.Name1";

            $taxDataArray = array();
            foreach ($tax_type_array as $key => $value) {
                $TaxData = $comDb->query("SELECT (case when $ShortName != '-' Then $ShortName ELSE t.Name1 end) as ShortName, t.TaxPcent, t.TaxType, t.Included, Sum(bt.TaxAmt) as SubAmtTax, t.rank from Tax t, BillingTax bt where bt.TNo=t.TNo and bt.EID=$EID and bt.BillId = $billId and bt.TNo=t.TNo and t.TaxType = $value group by t.Name1,t.TaxPcent, t.TaxType, t.Included ,t.rank order by t.rank")->result_array();
                
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

    public function getRestaurants(){
        return $this->genDB->select("EID, Name")
                    ->get_where('EIDDet', array('Stat' => 1))
                    ->result_array();
    }

    public function get_rest_detail($EID){
        $data['details']  = $this->genDB->select("ed.*, c.country_name, ct.city_name")
                    ->join('countries c', 'c.phone_code = ed.CountryCd', 'inner')
                    ->join('city ct', 'ct.city_id = ed.city_id', 'inner')
                    ->get_where('EIDDet ed', array('ed.EID' => $EID))
                    ->row_array();

        $data['configs'] = $this->genDB->select("*")
                    ->get_where('Config', array('EID' => $EID))
                    ->row_array();
        return $data;
    }


}