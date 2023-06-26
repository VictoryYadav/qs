<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    private $db2;
	public function __construct()
	{
		parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->authuser = $this->session->userdata('logged_in');
        } else {
            redirect(base_url());
        }

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);

        $this->load->model('Cust', 'cust');
	}

    public function index1(){

        $data['cuisinList'] = $this->cust->getCuisineList();
        $this->session->set_userdata('cuisine', $data['cuisinList'][0]['CID']);
        $cid = $data['cuisinList'][0]['CID'];
        $data['cid'] = $cid;
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            
            
            if(isset($_POST['cid']) && !empty($_POST['cid'])){
                $cid = $_POST['cid'];
            }
            $res['list'] = $this->cust->getMcatandCtypList($cid);
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }

        $data['title'] = 'Item Details';
        $data['language'] = languageArray();
        $data['EType'] = $this->session->userdata('EType');
        $data['Charity'] = $this->session->userdata('Charity');
        $data['Itm_Portion'] = 1;
        $data['offers'] = $this->cust->getOffers();
        
        $this->load->view('cust/index', $data);
    }

    public function index(){

        $data['cuisinList'] = $this->cust->getCuisineList();
        $this->session->set_userdata('cuisine', $data['cuisinList'][0]['CID']);
        $cid = $data['cuisinList'][0]['CID'];
        $data['cid'] = $cid;
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            
            
            if(isset($_POST['cid']) && !empty($_POST['cid'])){
                $cid = $_POST['cid'];
            }
            $res['list'] = $this->cust->getMcatandCtypList($cid);
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }

        $data['title'] = 'Item Details';
        $data['language'] = languageArray();
        $data['EType'] = $this->session->userdata('EType');
        $data['Charity'] = $this->session->userdata('Charity');
        $data['Itm_Portion'] = 1;
        $data['offers'] = $this->cust->getOffers();

        $this->load->view('cust/main', $data);
    }

    public function getItemDetailsData(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            extract($_POST);
            $res = $this->cust->getItemDetailLists($cid, $mcatId, $filter);           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }        
    }

    public function get_item_portion_ajax(){
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $TableNo = authuser()->TableNo;
            $data['EType'] = $this->session->userdata('EType');
            extract($_POST);

            $MenuItemRates = $this->cust->getMenuItemRates($EID, $itemId, $TableNo, $cid, $MCatgId, $ItemTyp );
            print_r(json_encode($MenuItemRates));
            
        }        
    }

    public function offer_cust_ajax(){
        if($this->input->method(true)=='POST'){
            echo "<pre>";
            print_r($_POST);
            die;
            $res = $this->cust->getOfferCustAjax($_POST);
            echo $res;
        }
    }

    public  function get_item_offer_ajax()
    {
        $status = 'error';
        $response = 'Someting went wrong!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $res = $this->cust->getItemOfferAjax($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    public function item_details_ajax(){
        if($this->input->method(true)=='POST'){

            $this->cust->getItem_details_ajax($_POST);
            // echo json_encode($res);
            // die;
        }   
    }

    // cart details
    public function cart(){
        $sts = 'error';
        $response = 'Something went wrong';
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            // send_to_kitchen_ajax
            $CustId = $this->session->userdata('CustId');
            $TempCustId = $this->session->userdata('TempCustId');
            $ChainId = authuser()->ChainId;
            $CNo = $this->session->userdata('CNo');
            $EType = $this->session->userdata('EType');
            //$Stall = Session::get('Stall');
            //$Ops = Session::get('Ops');
            $TableNo = authuser()->TableNo;
            $KOTNo = $this->session->userdata('KOTNo');
            $MultiKitchen = $this->session->userdata('MultiKitchen');
            $Kitchen = $this->session->userdata('Kitchen');

            if ($CustId != '') {

                if (isset($_POST['getSendToKitchenList']) && $_POST['getSendToKitchenList']) {

                    // Get all Temp Item list
                    $kitcheData = $this->db2->query("SELECT k.OrdNo, k.ItemId, k.Qty, k.TA, k.Itm_Portion, (if (k.ItemTyp > 0,(CONCAT(mi.ItemNm, ' - ' , k.CustItemDesc)),(mi.ItemNm ))) as ItemNm,  k.ItmRate as Value, mi.PckCharge, k.OType, k.OrdTime , ip.Name as Portions from Kitchen k, MenuItem mi,ItemPortions ip where k.Itm_Portion = ip.IPCd and k.CustId = $CustId AND k.EID = $EID AND k.TableNo = $TableNo AND k.ItemId = mi.ItemId AND k.BillStat = 0 AND k.Stat = 10 and k.CNo = $CNo")
                    ->result_array();

                    if(empty($kitcheData)){
                        $response = [
                            "status" => 0,
                            "msg" => "No Categary is available"
                        ];
                    }else {
                        $response = [
                            "status" => 1,
                            "kitcheData" => $kitcheData
                        ];
                    }
                    echo json_encode($response);
                    die();
                }

                if (isset($_POST['cancelOrder']) && $_POST['cancelOrder']) {
                    $orderNo = $_POST['orderNo'];
                    $cancelOrder = $this->db2->query("UPDATE Kitchen set Stat = 7 where OrdNo = $orderNo AND CustId = $CustId AND EID = $EID");

                    $response = [
                        "status" => 1,
                        "msg" => "Order Cancelled"
                    ];
                    echo json_encode($response);
                    die();
                }

                if (isset($_POST['sendToKitchen']) && $_POST['sendToKitchen']) {
                    if($CustId != $TempCustId){
                        // Check any order is already accepted
                        $checkOrderAccepted = $this->db2->query("SELECT OrdNo FROM Kitchen WHERE Stat = 1 AND CustId = $CustId AND EID = $EID AND TableNo = '$TableNo'")->result_array();

                        if (empty($checkOrderAccepted)) {
                            $stat = 0;
                        }else {
                            $stat = 1;
                        }

                        // Update kitchen stat = 10 to stat = 0 or 1. This is for EType=5
                        $updateKitchenStat = $this->db2->query("UPDATE Kitchen set Stat = $stat where Stat = 10 AND CustId = $CustId AND EID = $EID AND TableNo = '$TableNo'");
                        // set Kot to 0
                        $this->session->set_userdata('KOTNo', 0);
                        $response = [
                            "status" => 1,
                            "msg" => "Order Sent To Kitchen Successfully"
                        ];
                        echo json_encode($response);
                        die();
                    }else{
                        $response = [
                            "status" => 11,
                            "msg" => "1"
                        ];
                        echo json_encode($response);
                        die();
                    }   

                }else{
                   $url =  base_url('customer/signup');
                    echo '<script>window.location.assign("$url");</script>';
                }

            }else { 
                // Session Expire
                $response = [
                    "status" => "100",
                    "msg" => "Session Expire Please Rescan QR Code"
                ];

                echo json_encode($response);
                die();
            }
        }

        $CustId = $this->session->userdata('CustId');
        if ($CustId == '') {
            // header('Location: index.php');
            redirect(base_url('customer'));
        }

        $data['cId'] = $this->session->userdata('cId');
        $data['mCatgId'] = $this->session->userdata('mCatgId');
        $data['cType'] = $this->session->userdata('cType');
        $data['EID'] = $EID;

        $data['title'] = 'Order Details';
        $data['language'] = languageArray();

        $this->load->view('cust/cart', $data);
    }

    public function recommendation(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $ChainId = authuser()->ChainId;
            $TableNo = authuser()->TableNo;

            $status = 'success';
            $itemId = $_POST['itemId'];

            $select = "mi.ItemId, mi.ItemNm, mi.ItemNm2, mi.ItemNm3, mi.ItemNm4, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, mi.ItmDesc, mi.ItmDesc2, mi.ItmDesc3, mi.ItmDesc4, mi.Ingeredients, mi.Ingeredients2, mi.Ingeredients3, mi.Ingeredients4, mi.Rmks, mi.Rmks2, mi.Rmks3, mi.Rmks4, mi.PrepTime, mi.AvgRtng, mi.FID,ItemNm as imgSrc, mi.UItmCd,mi.CID,mi.Itm_Portion,mi.Value,mi.MCatgId,  (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$EID' and et1.TableNo = '$TableNo') as TblTyp";
            $rec = $this->db2->select($select)
                            ->join('MenuItem mi','mi.ItemId = mr.RcItemId', 'inner')
                            ->get_where('MenuItem_Recos mr', 
                                        array('mr.ItemId' => $itemId, 
                                            'mr.EID' => $EID,
                                            'mr.ChainId' => $ChainId, 
                                            'mr.Stat' => 0
                                        )
                            )->result_array();
                 
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $rec
              ));
             die;
        }
    }

    public function signup(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('signup', $_POST);
            // echo "<pre>";
            // print_r($_SESSION);
            // die;
            $otp = $this->generateOTP($_POST['MobileNo']);
            $status = 'success';
            $response = "Your otp is $otp";

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Signup';
        $data['language'] = languageArray();
        $this->load->view('cust/signup', $data);
    }

    private function generateOTP($mobile){
        $otp = rand(9999,1000);
        $this->session->set_userdata('cust_otp', '1212');
        $check = $this->db2->select('token')->get_where('Users', array('MobileNo' => $mobile))->row_array();
        if(!empty($check)){
            $msg = 'Your One Time Password is '.$otp;
            $message = array(
              'body'   => $msg,
              'title'   => 'Your OTP',
              'vibrate' => 1,
              'sound'   => 1
            );
            firebaseNotification($check['token'], $message);
        }else{
            $genTblDb = $this->load->database('GenTableData', TRUE);
            $chckGen = $genTblDb->select('token')->get_where('AllUsers', array('MobileNo' => $mobile))->row_array();

            if(!empty($chckGen)){
                $msg = 'Your One Time Password is '.$otp;
                $message = array(
                  'body'   => $msg,
                  'title'   => 'Your OTP',
                  'vibrate' => 1,
                  'sound'   => 1
                );
                firebaseNotification($chckGen['token'], $message);
            }else{
                return $otp;
            // send otp 
            }
        }
    }

    public function verifyOTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('cust_otp');
            if($_POST['otp'] == $otp){
                $res = "OTP Matched!";
                $status = 'success';
                $ses_data = $_SESSION['signup'];

                $check = $this->db2->get_where('Users', array('MobileNo' => $ses_data['MobileNo']))->row_array();

                $EType = $this->session->userdata('EType');
                $EID = authuser()->EID;
                $TableNo = authuser()->TableNo;

                if(!empty($check)){
                    $this->session->set_userdata('CustId', $check['CustId']);
                    $CustId = $check['CustId'];
                }else{
                    $genTblDb = $this->load->database('GenTableData', TRUE);

                    $gen_check = $genTblDb->get_where('AllUsers', array('MobileNo' => $ses_data['MobileNo']))->row_array();
                    if(!empty($gen_check)){
                        $this->session->set_userdata('CustId', $gen_check['CustId']);

                        $CustId = $gen_check['CustId'];
                        
                        $data1['CustId']    = $gen_check['CustId'];
                        $data1['FName']     = $gen_check['FName'];
                        $data1['LName']     = $gen_check['LName'];
                        $data1['Email']     = $gen_check['Email'];
                        $data1['MobileNo']  = $gen_check['MobileNo'];
                        $data1['DOB']       = $gen_check['DOB'];
                        $data1['Gender']    = $gen_check['Gender'];
                        insertRecord('Users',$data1);    
                    }else{
                        $data = $ses_data;
                        $genTblDb->insert('AllUsers', $data);
                        $CustId = $genTblDb->insert_id();
                        $this->session->set_userdata('CustId', $CustId);
                        $data['CustId'] = $CustId;
                        insertRecord('Users',$data);
                    }
                }

                if(!empty($CustId) && $CustId > 0){
                    $res = $this->db2->query("SELECT * from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND TableNo = '$TableNo' AND timediff(time(Now()),time(LstModDt))  < time('03:00:00') order by CNo desc limit 1")->row_array();
                    if(!empty($res)){
                        $this->session->set_userdata('CNo', $res['CNo']);
                    }
                }

                //Deleting older orders
                if ($EType == 5) {
                    $this->db2->query("UPDATE Kitchen set Stat = 99 WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 10 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");
                    
                    $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 0 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");
                } else {
                    $this->db2->query("UPDATE Kitchen set Stat = 99 WHERE EID = $EID AND CustId = $CustId AND BillStat = 0 AND Stat = 0 ");

                    $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE CustId = $CustId AND EID = $EID AND BillStat = 0 AND timediff(time(Now()),time(LstModDt)) > time('03:00:00')");
                }

            }else{
                $res = "OTP Doesn't Matched";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    // order_details_ajax
    public function checkout(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $res = $this->cust->getBillingData($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    public function bill(){

        $data['title'] = 'Billing';
        $data['language'] = languageArray();
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        // include_once('config.php');
        $CustId = $this->session->userdata('CustId');
        $ONo = $this->session->userdata('ONo');
        $EType = $this->session->userdata('EType');
        $Stall = $this->session->userdata('Stall');
        $ServChrg = $this->session->userdata('ServChrg');
        $Tips = $this->session->userdata('Tips');
        $COrgId = $this->session->userdata('COrgId');
        $PymtOpt = $this->session->userdata('PymtOpt');
        $Cash = $this->session->userdata('Cash');
        $KOTNo = $this->session->userdata('KOTNo');
        $this->load->view('cust/billing', $data);
        
    }


}
