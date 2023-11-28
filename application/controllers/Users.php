<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    private $db2;

	public function __construct()
	{
		parent::__construct();
	}

    public function index(){
        if(isset($_GET['eatout']) && !empty($_GET['eatout'])){
            $eo_data = rtrim(base64_decode($_REQUEST['eatout']), "=");
            if(!empty($eo_data)){
                $req = explode("_", $eo_data);
                // echo "<pre>";print_r($req);exit();
                $this->session->set_userdata('EID', $req[0]);
                $this->session->set_userdata('page', $req[1]);
                $this->session->set_userdata('billId', $req[2]);
                $billId = $req[2];
                // db load
                $this->session->set_userdata('my_db', $req[3]);
                $this->db2 = $this->load->database($req[3], TRUE);
                $this->session->set_userdata('ratingShow', 0);
                
                if($req[1] == 'r'){
                    redirect(base_url('users/rating/'.$billId));    
                }else if($req[1] == 'b'){
                    $MCNo = $req[4];
                    $this->session->set_userdata('bCellNo', $req[5]);
                    $this->session->set_userdata('MergeNo', $req[6]);
                    $this->session->set_userdata('payable', $req[7]);
                    redirect(base_url('users/pay/'.$billId.'/'.$MCNo));    
                }
            }

        }
    }

    public function rating($billId){
        // echo "<pre>";
        // print_r($_SESSION);
        // die;

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);

        $EID = $this->session->userdata('EID');

        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;

            extract($_POST);

            $CellNo = $this->session->userdata('mobile');;
            $CustId = 0;
            $ChainId = 0;

            $RatingInsert['EID']        = $EID;
            $RatingInsert['ChainId']    = $ChainId;
            $RatingInsert['BillId']     = $billid;
            $RatingInsert['CustId']     = $CustId;
            $RatingInsert['CellNo']     = $CellNo;
            $RatingInsert['Remarks']    = 0;
            $RatingInsert['ServRtng']   = $Service;
            $RatingInsert['AmbRtng']    = $Ambience;
            $RatingInsert['VFMRtng']    = $vfm;
            $RatingInsert['LstModDt']   = date('Y-m-d H:i:s');
            $RCd = insertRecord('Ratings', $RatingInsert);

            $queryString = '';
            $totRtng = 0;
            $itemCount = 0;
            for ($i = 0; $i < count($rating); $i++) {
                $itemCount++;
                if ($i >= 1) {
                    $queryString .= ',';
                }
                $totRtng = $totRtng + $rating[$i];
                $queryString .= '(' . $RCd . ',' . $ratingData[$i] . ',' . $rating[$i] . ')';
            }

            $RatingDetQuery = $this->db2->query("INSERT INTO `RatingDet`(RCd,ItemId,ItemRtng) VALUES $queryString ");

            $ravg = $totRtng / $itemCount;
            updateRecord('Ratings',
                        array('avgBillRtng' => round($ravg,2)
                             ),
                array('RCd' => $RCd,'EID' => $EID,'BillID' => $billid)
                        );

            $genTblDb = $this->load->database('GenTableData', TRUE);
            // gen db
            // $genCheckid = $genTblDb->query("SELECT RCd  FROM `Ratings` WHERE EID = $EID AND BillId = $billid AND CustId = $CustId AND CellNo = $CellNo")->result_array();

            // // gen db
            // if (!empty($genCheckid)) {
            //     $RCd = $genCheckid[0]['RCd'];
            //     $deleteRating = $genTblDb->query("DELETE FROM `Ratings` WHERE EID = $EID AND BillId = $billid AND CustId = $CustId AND CellNo = $CellNo");
            //     $deleteRatingDet = $genTblDb->query("DELETE FROM `RatingDet` WHERE RCd = $RCd");
            // }
            // gen db
            $genRatingObj['EID']        = $EID;
            $genRatingObj['ChainId']    = $ChainId;
            $genRatingObj['BillId']     = $billid;
            $genRatingObj['CustId']     = $CustId;
            $genRatingObj['CellNo']     = $CellNo;
            $genRatingObj['Remarks']    = 0;
            $genRatingObj['ServRtng']   = $Service;
            $genRatingObj['AmbRtng']    = $Ambience;
            $genRatingObj['avgBillRtng']= round($ravg,2);
            $genRatingObj['VFMRtng']    = $vfm;
            $genRatingObj['LstModDt']   = date('Y-m-d H:i:s');
            $genTblDb->insert('Ratings', $genRatingObj);
            $genRCd = $genTblDb->insert_id();

            // gen table
            $queryStringGen = '';
            for ($i = 0; $i < count($rating); $i++) {
                if ($i >= 1) {
                    $queryStringGen .= ',';
                }
                $queryStringGen .= '(' . $genRCd . ',' . $ratingData[$i] . ',' . $rating[$i] . ')';
            }
            // gen table
            $RatingDetQuery = $genTblDb->query("INSERT INTO `RatingDet`(RCd,ItemId,ItemRtng) VALUES $queryStringGen ");

            if ($RatingDetQuery >= 1 && $RatingInsert >= 1) {
                echo 1;
            } else {
                echo 0;
            }
            die;

        }

        $data['title'] = $this->lang->line('rating');
        $data['billId'] = $billId;

        $url = $EID . "_" . "_" . $billId;

        $url = base64_encode($url);
        $url = rtrim($url, "=");
        $data['link'] = base_url('customer/rating/'.$billId).'?rat='.$url;

        $data['kitchenGetData'] = $this->db2->select('b.BillId,k.ItemId , m.UItmCd, CONCAT(m.ItemNm, k.CustItemDesc) as ItemNm, k.CustItemDesc')
                                    ->order_by('m.ItemNm','ASC')
                                    ->group_by('k.ItemId,k.MCNo')
                                    ->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
                                    ->join('Kitchen k', 'k.MCNo = km.MCNo', 'inner')
                                    ->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
                                    ->get_where('Billing b', array('b.BillId' => $billId, 'k.Stat' => 3, 'b.EID' => $EID))
                                    ->result_array();

        $this->load->view('user/rating', $data);
        
    }


    public function send_otp(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $mobile = $_POST['mobile'];

            $CustId = createCustUser($mobile);
            
            $otp = generateOTP($mobile, 'rating');
            $status = 'success';
            $response = "Your otp is ";
            $this->session->set_userdata('mobile', $mobile);
            $this->session->set_userdata('CustId', $CustId);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function verifyOTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('cust_otp');
            if($_POST['otp'] == $otp){
                $this->session->set_userdata('ratingShow', 1);

                $status = 'success';
                $response = "OTP Matched";
            }else{
                $response = "OTP Doesn't Matched";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function resendOTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = "success";
            $res = 'OTP Not Send.';

            extract($_POST);
            if($mobile){
                $otp  = generateOTP($mobile, $page);
                $res = 'Resend OTP Successfully.';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }   
    }

    public function pay($BillId, $MCNo){
        // $this->session->set_userdata('ratingShow', 0);
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);

        $EID = $this->session->userdata('EID');
        
        $data['title'] = $this->lang->line('payNow');
        $data["modes"] = $this->db2->select('PymtMode,Name,Company, CodePage1')->get_where('ConfigPymt', array('Stat' => 1))->result_array();
        $data['payable'] = $this->session->userdata('payable');
        $data['BillId'] = $BillId;
        $data['MCNo'] = $MCNo;
        $data["splitBills"] = $this->db2->get_where('BillPayments', array('BillId' => $BillId,'Stat' => 1,'EID' => $EID))->result_array();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('user/pay_now', $data);
    }

    public function multi_payment(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            echo "<pre>";
            print_r($_POST);
            die;
            
            $pay['BillId'] = $_POST['BillId'];
            $pay['MCNo'] = $_POST['MCNo'];
            $pay['MergeNo'] = 0;
            $pay['TotBillAmt'] = $_POST['payable'];
            $pay['CellNo'] = $this->session->userdata('mobile');
            $pay['SplitTyp'] = 0;
            $pay['SplitAmt'] = 0;
            $pay['PymtId'] = 0;
            $pay['PaidAmt'] = $_POST['amount'];
            $pay['OrderRef'] = 0;
            $pay['PaymtMode'] = $_POST['mode'];
            $pay['PymtType'] = 0;
            $pay['PymtRef'] = 'share payment';
            $pay['Stat'] = 0;
            $pay['EID'] = $this->session->userdata('EID');

            // echo "<pre>";
            // print_r($pay);
            // die;
            $payNo = insertRecord('BillPayments', $pay);
            updateRecord('KitchenMain', array('custPymt' => 1), array('MCNo' => $_POST['MCNo'],'EID' => authuser()->EID));

            if(!empty($payNo)){
                $status = 'success';
                $response = $payNo;
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function check_payment_status(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $response = 'Pending';
            $res = $this->db2->get_where('BillPayments', array('BillId' => $_POST['billId'] ,'PymtNo' => $_POST['payNo'], 'Stat' => 1))->row_array();
            if(!empty($res)){
                $status = 'success';
                $response = $res;
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function updateCustPayment(){
        // $CNo = $this->session->userdata('CNo');
        $EID  = $this->session->userdata('EID');
        $billId = $_POST['BillId'];

        $MergeNo = $this->session->userdata('MergeNo');
        // autoSettlePayment($billId, $MergeNo);

        // $this->session->set_userdata('CNo', 0);
    }

  

}
