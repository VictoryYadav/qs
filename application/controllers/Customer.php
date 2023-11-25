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

        $this->lang->load('message','english');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);

        $this->load->model('Cust', 'cust');
	}

    function switchLang() {
        // https://www.codexworld.com/multi-language-implementation-in-codeigniter/
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            extract($_POST);
            $language = ($language != "") ? $language : "english";
            $this->session->set_userdata('site_lang', $language);
            $response = $language;
            
            // redirect($_SERVER['HTTP_REFERER']);
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        
    }

    public function index1(){

        // print_r(base64_decode('ZT01MSZjPTAmdD0yMiZvPTA'));
        // die;
        

        echo "<pre>";
        print_r($_SESSION);
        die;
        $CustId = 10;
         $hours_3 = date('Y-m-d H:i:s', strtotime("-3 hours"));
                // if ($EType == 5) {
                //     updateRecord('Kitchen', array('Stat' => 99), array('EID' => $EID,
                //             'CustId' => $CustId,
                //             'TableNo' => $TableNo, 
                //             'Stat' => 10 ,
                //             'Stat' => 0 , 
                //             'BillStat' => 0, 
                //             'LstModDt <' => $hours_3
                //             )
                //         );

        $res = $this->db2->get_where('KitchenMain', array('CustId' => $CustId,'BillStat' => 0,'TableNo' => 22,'LstModDt <' => $hours_3))->row_array();
        // $this->db2->query("SELECT * from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND TableNo = '22' AND timediff(time(Now()),time(LstModDt))  < time('03:00:00') order by CNo desc limit 1")->row_array();
        print_r($this->db2->last_query());die;
        print_r($res);

        die;
        
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

    public function outlets(){

        $data['title'] = $this->lang->line('multiOutlets');
        
        $data['outlets'] = $this->db2->select('EID, Name, Stall, QRLink')
                                    ->order_by('e.Stall', 'ASC')
                                    ->get_where('Eatary e', array('e.CatgId >' => 10, 'Stat' => 0,'dbEID' => authuser()->EID))
                                    ->result_array();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('cust/multi_outlets', $data);
    }

    public function index(){

        $data['cuisinList'] = $this->cust->getCuisineList();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->session->set_userdata('cuisine', $data['cuisinList'][0]['CID']);
        $cid = $data['cuisinList'][0]['CID'];

        $data['cid'] = !empty($this->session->userdata('f_cid'))?$this->session->userdata('f_cid'):$cid;
        $data['fmcat'] = !empty($this->session->userdata('f_mcat'))?$this->session->userdata('f_mcat'):0;
        $data['ffid'] = 0;
        $this->session->set_userdata('f_fid',0);
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $data['ffid'] = 0;
            $this->session->set_userdata('f_fid',0);
            // echo "<pre>";
            // print_r($_SESSION);
            // die;
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

        // $data['title'] = 'Item Details';
        $data['title'] = $this->lang->line('main');
        
        $data['language'] = languageArray();
        $data['EType'] = $this->session->userdata('EType');
        $data['Charity'] = $this->session->userdata('Charity');
        $data['Itm_Portion'] = 1;

        $this->load->view('cust/main', $data);
    }

    public function getFoodTypeList(){
        $this->session->set_userdata('f_fid',0);
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('f_fid',0);
            $status = 'success';
            // echo "<pre>";
            // print_r($_POST);
            // print_r($this->session->userdata('f_fid'));
            // die;
            extract($_POST);

            $filter = array();
            $mcat_ctyp = $this->db2->select('MCatgId, MCatgNm, L1MCatgNm, L2MCatgNm, L3MCatgNm, CTyp, CID')
             ->get_where('MenuCatg', array('MCatgId' => $mcatId))
             ->row_array();

            $filter_list = $this->db2->select('FID, Opt, Rank')
                                ->order_by('Rank', 'ASC')
                                ->get_where('FoodType', array('CTyp' => $mcat_ctyp['CTyp'], 'Stat' => 0))
                                ->result_array();
            if(!empty($filter_list)){
                $filter = $filter_list;
             } 
                    
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $filter
              ));
             die;
        }
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
            // echo "<pre>";
            // print_r($_POST);
            // die;
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
        $EType = $this->session->userdata('EType');
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            // send_to_kitchen_ajax
            $CustId = $this->session->userdata('CustId');
            $TempCustId = $this->session->userdata('TempCustId');
            $ChainId = authuser()->ChainId;
            $CNo = $this->session->userdata('CNo');
            //$Stall = Session::get('Stall');
            //$Ops = Session::get('Ops');
            $TableNo = authuser()->TableNo;
            $KOTNo = $this->session->userdata('KOTNo');
            $MultiKitchen = $this->session->userdata('MultiKitchen');
            $Kitchen = $this->session->userdata('Kitchen');

            if ($CustId != '') {

                if (isset($_POST['getSendToKitchenList']) && $_POST['getSendToKitchenList']) {

                    // Get all Temp Item list
                    $kitcheData = $this->db2->query("SELECT k.OrdNo, k.ItemId, k.Qty, k.TA, k.Itm_Portion, (if (k.ItemTyp > 0,(CONCAT(mi.ItemNm, ' - ' , k.CustItemDesc)),(mi.ItemNm ))) as ItemNm,  k.ItmRate as Value, mi.PckCharge, k.OType, k.OrdTime , ip.Name as Portions, k.Stat from Kitchen k, MenuItem mi,ItemPortions ip where k.Itm_Portion = ip.IPCd and k.CustId = $CustId AND k.EID = $EID AND k.TableNo = $TableNo AND k.ItemId = mi.ItemId AND k.BillStat = 0 AND (k.Stat = 1 or k.Stat = 2) and k.CNo = $CNo")
                    ->result_array();

                    foreach ($kitcheData as &$key) {
                        $key['recom'] = $this->cust->checkRecommendation($key['ItemId']);
                    }

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
        $data['EType'] = $EType;

        $data['title'] = $this->lang->line('cart');
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

            $select = "mc.TaxType, mc.KitCd, mi.ItemId, mi.ItemNm, mi.ItemNm2, mi.ItemNm3, mi.ItemNm4, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, mi.ItmDesc, mi.ItmDesc2, mi.ItmDesc3, mi.ItmDesc4, mi.Ingeredients, mi.Ingeredients2, mi.Ingeredients3, mi.Ingeredients4, mi.Rmks, mi.Rmks2, mi.Rmks3, mi.Rmks4, mi.PrepTime, mi.AvgRtng, mi.FID,ItemNm as imgSrc, mi.UItmCd,mi.CID,mi.Itm_Portion,mi.Value,mi.MCatgId,  (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate,(select mir.Itm_Portion FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as Itm_Portions, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$EID' and et1.TableNo = '$TableNo') as TblTyp";
            $rec = $this->db2->select($select)
                            ->join('MenuItem mi','mi.ItemId = mr.RcItemId', 'inner')
                            ->join('MenuCatg mc', 'mc.MCatgId = mi.MCatgId', 'inner')
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

    public function recomAddCart(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            $CustId = $this->session->userdata('CustId');
            $CNo = $this->session->userdata('CNo');
            $CellNo = $_SESSION['signup']['MobileNo'];
            $EType = $this->session->userdata('EType');

            $COrgId = $this->session->userdata('COrgId');
            $CustNo = $this->session->userdata('CustNo');
            $EID = authuser()->EID;
            $ChainId = authuser()->ChainId;
            $ONo = $this->session->userdata('ONo');
            $TableNo = authuser()->TableNo;
            $KOTNo = $this->session->userdata('KOTNo');
            $MultiKitchen = $this->session->userdata('MultiKitchen');
            $Kitchen = $this->session->userdata('Kitchen');
            $TableAcceptReqd = $this->session->userdata('TableAcceptReqd');
            $AutoSettle = $this->session->userdata('AutoSettle');

            $temp = array();
            $data = array();
            $flag = 0;
            if(!empty($_POST['itemArray'])){
                foreach ($_POST['itemArray'] as $itemId ) {
                    
                    $qty = $_POST['qty'][$itemId][0];
                    $rate = $_POST['rate'][$itemId][0];
                    if($qty > 0){
                        $flag++;
                        $OType = 0;
                        $TblTyp = $_POST['TblTyp'][$itemId][0];
                        
                        if($TblTyp == 0){
                            // QSR
                            $OType = 1;
                        }else if($TblTyp == 5){
                            // Seat no basis - common table like in bars
                            $OType = 5;
                        }else if($TblTyp == 7){
                            // Sit-In customer
                            $OType = 7;
                        }else if($TblTyp == 8){
                            // Sit-In offline
                            $OType = 8;
                        }else if($TblTyp == 15){
                            // personal TakeAway
                            $OType = 15;
                        }else if($TblTyp == 17){
                            // Rest Delivery
                            $OType = 17;
                        }else if($TblTyp == 20){
                            // 3P Delivery - swiggy/zomato
                            $OType = 20;
                        }else if($TblTyp == 25){
                            // Drive-In
                            $OType = 25;
                        }else if($TblTyp == 30){
                            // Charity
                            $OType = 30;
                        }else if($TblTyp == 35){
                            // RoomService
                            $OType = 35;
                        }else if($TblTyp == 40){
                            // Suite Service
                            $OType = 40;
                        }

                        if ($KOTNo == 0) {
                            // To generate new KOTNo
                            $kotNoCount = $this->db2->query("SELECT Max(KOTNo + 1) as tKot from Kitchen where DATE(LstModDt) = CURDATE() AND EID = $EID")->result_array();
                
                            if ($kotNoCount[0]['tKot'] == '') {
                                $kotNo = 1;
                            } else {
                                $kotNo = $kotNoCount[0]['tKot'];
                            }
                            $KOTNo = $kotNo;
                            $oldKitCd = 0;
                            $this->session->set_userdata('KOTNo', $kotNo);
                            $this->session->set_userdata('oldKitCd', 0);
                        }

                        $oldKitCd = $this->session->userdata('oldKitCd');
                        $fKotNo = $KOTNo;

                        $MergeNoGet = $this->db2->query("SELECT MergeNo FROM KitchenMain WHERE EID = $EID AND CNo = $CNo and BillStat = 0")->result_array();
                        $MergeNo = $MergeNoGet[0]['MergeNo'];

                        if ($EType == 5) {
                            // $orderType = 7;
                            if($TableAcceptReqd > 0){
                                $checkStat = $this->db2->select('Stat')->get_where('KitchenMain', array('CNo' => $CNo, 'EID' => $EID, 'BillStat' => 0))->row_array();
                                $stat = $checkStat['Stat'];
                            }else{
                                $stat = 2;
                            }
                            //$newUKOTNO = date('dmy_') . $KOTNo;

                            // Check entry is already inserted in ETO
                            $checkTableEntry = $this->db2->query("SELECT TNo FROM Eat_tables_Occ WHERE EID = $EID AND CNo = $CNo")->row_array();
                    
                            //If Empty insert new record
                            if (empty($checkTableEntry)) {
                                $eatTablesOccObj['EID'] = $EID;
                                $eatTablesOccObj['TableNo'] = $TableNo;
                                $eatTablesOccObj['MergeNo'] = $MergeNo;
                                $eatTablesOccObj['CustId'] = $CustId;
                                $eatTablesOccObj['CNo'] = $CNo;
                                //$eatTablesOccObj->Stat = 0;
                                $eatobj = insertRecord('Eat_tables_Occ', $eatTablesOccObj);
                                if ($eatobj) {
                                    // update Eat_tables for table Allocate
                                    $eatTablesUpdate = $this->db2->query("UPDATE Eat_tables set Stat = 1, MergeNo = $MergeNo where EID = $EID AND TableNo = '$TableNo' AND  Stat = 0");
                                } else {
                                    //alert "Add another customer to occupied table"
                                }
                            }

                        }else{
                            //For ETpye 1 Order Type Will Be 0 and Stat = 1
                            $OType = 0;
                            $stat = 1;
                        }
                        $newUKOTNO = date('dmy_') . $KOTNo;
                        $prepration_time = $_POST['prepration_time'][$itemId][0];

                        $date = date("Y-m-d H:i:s");
                        $date = strtotime($date);
                        $time = $prepration_time;
                        $date = strtotime("+" . $time . " minute", $date);

                        if ($MultiKitchen > 1) {
                            $itemKitCd = $_POST['itemKitCd'][$itemId][0];
                            if ($oldKitCd != $_POST['itemKitCd'][$itemId][0]) {
                                $getFKOT = $this->db2->query("SELECT max(FKOTNO) as FKOTNO FROM Kitchen WHERE EID = $EID AND KitCd = $itemKitCd")
                                ->result_array();
                                $fKotNo = $getFKOT[0]['FKOTNO'];
                                $fKotNo += 1;
                                // new ukot
                                $newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
                                $this->session->set_userdata('oldKitCd', $itemKitCd);
                            } else {
                                // next ukot                    
                                $newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
                            }
                        }

                        $temp['CNo'] = $CNo;
                        $temp['CustId'] = $CustId;
                        $temp['COrgId'] = $COrgId;
                        $temp['CustNo'] = $CustNo;
                        $temp['CellNo'] = $CellNo;
                        $temp['EID'] = $EID;
                        $temp['ChainId'] = $ChainId;
                        $temp['ONo'] = $ONo;
                        $temp['KitCd'] = $_POST['itemKitCd'][$itemId][0];
                        $temp['OType'] = $OType;
                        $temp['FKOTNo'] = $fKotNo;
                        $temp['KOTNo'] = $KOTNo;
                        $temp['UKOTNo'] = $newUKOTNO;         //date('dmy_').$KOTNo;
                        $temp['TableNo'] = $TableNo;
                        $temp['MergeNo'] = $MergeNo;
                        $temp['ItemId'] = $itemId;
                        $temp['TaxType'] = $_POST['tax_type'][$itemId][0];
                        $temp['Qty'] = $qty;
                        $temp['ItmRate'] = $rate;
                        $temp['OrigRate'] = $rate;  
                        $temp['Itm_Portion'] = $_POST['Itm_Portions'][$itemId][0];
                        $temp['CustRmks'] = '';
                        $temp['DelTime'] = date('Y-m-d H:i:s', $date);
                        $temp['TA'] = 0;
                        $temp['Stat'] = $stat;
                        $temp['LoginCd'] = 1;
                        $temp['SDetCd'] = 0;
                        $temp['SchCd'] = 0;

                        $data[] = $temp;
                    }
                }
            }
            // echo "<pre>";print_r($data);die;
            $response = 'Please add atleast one Item';
            if($flag > 0){
                $this->db2->insert_batch('Kitchen', $data);
                $status = 'success';
                $response = 'Item Added';
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function login(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $emailMobile = $_POST['emailMobile'];
            $check = $this->db2->select('token')
                            ->where('MobileNo', $emailMobile)
                            ->get('Users')
                            ->row_array();
            if(!empty($check)){
                // $otp = 1;
                $otp = generateOTP($emailMobile, 'login');
                $status = 'success';
                $response = "Your otp is ";
                $this->session->set_userdata('emailMobile', $emailMobile);
            }else{
                $response = "Username is not found!";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('log_in');
        $data['language'] = languageArray();
        $this->load->view('cust/login', $data);
    }

    public function loginVerify(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('cust_otp');
            if($_POST['otp'] == $otp){
                $resp['msg'] = "OTP Matched!";
                $status = 'success';
                $ses_data = $this->session->userdata('emailMobile');

                $check = $this->db2->select('*')
                                    ->where('MobileNo',  $ses_data)
                                    ->get_where('Users')
                                    ->row_array();

                $EType = $this->session->userdata('EType');
                $EID = authuser()->EID;
                $TableNo = authuser()->TableNo;
                if(!empty($check)){
                    $this->session->set_userdata('CustId', $check['CustId']);
                    $this->session->set_userdata('CellNo', $check['MobileNo']);
                    $CustId = $check['CustId'];
                    $this->session->set_userdata('signup', $check);
                }

                $visit = 0;
                $rating = 0;
                if(!empty($CustId) && $CustId > 0){

                    $res = $this->db2->query("SELECT CNo from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND TableNo = '$TableNo' AND timediff(Now(),LstModDt) < ('03:00:00') order by CNo desc limit 1")->row_array();
                    if(!empty($res)){
                        $this->session->set_userdata('CNo', $res['CNo']);
                    }else{
                        $this->session->set_userdata('CNo', 0);

                        $visited = $this->db2->select('count(CNo) as counts')->get_where('KitchenMain', array('EID' => $EID, 'CustId' => $CustId))->row_array();
                        $visit = $visited['counts'];
                        if($visit > 0){
                            $rating1 = $this->db2->select('avg(avgBillRtng) as rtng')->get_where('Ratings', array('EID' => $EID, 'CustId' => $CustId))->row_array();

                            $rating = $rating1['rtng'];
                        }
                    }
                }

                $resp['visit'] = $visit;
                $resp['rating'] = round($rating,2);
                $resp['name'] = $check['FName'].' '.$check['LName'];

                //Deleting older orders
                $hours_3 = date('Y-m-d H:i:s', strtotime("-3 hours"));
                if ($EType == 5) {
                    // $this->db2->where('Stat', 0);
                    // $this->db2->or_where('Stat', 1);
                    $this->db2->where_in('Stat', array(0,1,2));
                    $this->db2->update('Kitchen', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId,
                            'TableNo' => $TableNo,
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );

                    $this->db2->where_in('Stat', array(0,1,2));
                    // $this->db2->or_where('Stat', 1);
                    $this->db2->update('KitchenMain', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId,
                            'TableNo' => $TableNo,
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );
                    
                    // updateRecord('KitchenMain', array('Stat' => 99), array('EID' => $EID,
                    //         'CustId' => $CustId,
                    //         'TableNo' => $TableNo, 
                    //         'Stat' => 0 , 
                    //         'Stat' => 1 , 
                    //         'BillStat' => 0, 
                    //         'LstModDt <' => $hours_3
                    //         )
                    //     );
                } else {

                    $this->db2->where_in('Stat', array(0,1,2));
                    $this->db2->update('Kitchen', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId,
                            'BillStat' => 0
                            )
                        );

                    updateRecord('KitchenMain', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId, 
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );
                }
                $response = $resp;
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

    public function signup(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('signup', $_POST);
            // echo "<pre>";
            // print_r($_SESSION);
            // die;
            $checkUser = $this->db2->select('token')
                            ->where('MobileNo', $_POST['MobileNo'])
                            ->get('Users')
                            ->row_array();
            if(!empty($checkUser)){
                $response = "User Already Exists!";
            }else{
                $otp = generateOTP($_POST['MobileNo'], 'signup');
                $status = 'success';
                $response = "Your otp is ";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('signup');
        $data['language'] = languageArray();
        $this->load->view('cust/signup', $data);
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

                $this->session->set_userdata('CellNo', $ses_data['MobileNo']);

                $EType = $this->session->userdata('EType');
                $EID = authuser()->EID;
                $TableNo = authuser()->TableNo;
                $CustId = 0;
                $genTblDb = $this->load->database('GenTableData', TRUE);

                $gen_check = $genTblDb->select('*')
                                        ->where('MobileNo',  $ses_data['MobileNo'])
                                        ->get('AllUsers')
                                        ->row_array();

                // $genTblDb->select('*')
                //                         ->group_start() 
                //                             ->where('MobileNo',  $ses_data['MobileNo'])
                //                             ->or_where('email', $ses_data['email'])
                //                         ->group_end()
                //                         ->get('AllUsers')
                //                         ->row_array();
                if(!empty($gen_check)){
                    $this->session->set_userdata('CustId', $gen_check['CustId']);

                    $CustId = $gen_check['CustId'];
                    
                    $data1['CustId']    = $gen_check['CustId'];
                    $data1['FName']     = $gen_check['FName'];
                    $data1['LName']     = $gen_check['LName'];
                    // $data1['Email']     = $gen_check['Email'];
                    $data1['MobileNo']  = $gen_check['MobileNo'];
                    $data1['DOB']       = $gen_check['DOB'];
                    $data1['Gender']    = $gen_check['Gender'];
                    $data1['otp']    = $otp;
                    insertRecord('Users',$data1);    
                }else{
                    $data = $ses_data;

                    $Adata = $data;
                    $Adata['EID'] = authuser()->EID;
                    $Adata['page'] = 'signup';
                    $genTblDb->insert('AllUsers', $Adata);
                    $CustId = $genTblDb->insert_id();
                    $this->session->set_userdata('CustId', $CustId);
                    $data['CustId'] = $CustId;
                    $data['otp']    = $otp;
                    insertRecord('Users',$data);
                }
                
                if(!empty($CustId) && $CustId > 0){
                    $res = $this->db2->query("SELECT CNo from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND TableNo = '$TableNo' AND timediff(Now(),LstModDt) < ('03:00:00') order by CNo desc limit 1")->row_array();
                    if(!empty($res)){
                        $this->session->set_userdata('CNo', $res['CNo']);
                    }
                }

                //Deleting older orders
                    $hours_3 = date('Y-m-d H:i:s', strtotime("-3 hours"));
                if ($EType == 5) {
                    updateRecord('Kitchen', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId,
                            'TableNo' => $TableNo, 
                            'Stat' => 10 ,
                            'Stat' => 0 , 
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );
                    // $this->db2->query("UPDATE Kitchen set Stat = 99 WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 10 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");
                    
                    // $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 0 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");
                    updateRecord('KitchenMain', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId,
                            'TableNo' => $TableNo, 
                            'Stat' => 0 , 
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );

                } else {
                    updateRecord('Kitchen', array('Stat' => 99), array('EID' => $EID,
                        'CustId' => $CustId , 'BillStat' => 0 , 'Stat' => 0 )
                                );

                    // $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE CustId = $CustId AND EID = $EID AND BillStat = 0 AND timediff(time(Now()),time(LstModDt)) > time('03:00:00')");
                    updateRecord('KitchenMain', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId, 
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );
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

    public function order_details_ajax(){

        $CustId = $this->session->userdata('CustId');
        $TempCustId = $this->session->userdata('TempCustId');
        $CellNo = $this->session->userdata('CellNo');
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');
        $KOTNo = $this->session->userdata('KOTNo');
        $CNo = $this->session->userdata('CNo');
        $TableNo = authuser()->TableNo;

        if ($CustId != '') {
            // echo "<pre>";
            // print_r($_POST);
            // die;
            
            if (isset($_POST['goBill']) && $_POST['goBill']) {
                // $temp = array();
                // $data = array();
                $i=0;
                foreach ($_POST['OrdNo'] as $OrdNo ) {
                    $temp['OrdNo'] = $OrdNo;
                    $temp['qty'] = $_POST['qty'][$i];
                    // $data[] = $temp;
                    updateRecord('Kitchen', array('Qty' => $temp['qty'],'Stat' => 3), array('OrdNo' => $OrdNo, 'EID' => $EID));
                    $i++;
                }
                
                $res = $this->db2->query("SELECT mi.ItemId as MItemId, mi.MCatgId as MMCatgId, mi.CID as MCID, k.OrdNo, k.ItemId as KItemId, k.ItemTyp as KItemTyp, k.Itm_Portion, k.ItmRate, k.Qty as KQty, cod.* from Kitchen as k join CustOffersDet as cod on k.SchCd = cod.SchCd and k.SDetCd = cod.SDetCd join MenuItem as mi on mi.ItemId = k.ItemId where k.EID= $EID and k.CNo = '$CNo'")->result_array();
                $price = $this->db2->query("SELECT sum(ItmRate) as total_amount from Kitchen where CNo = '$CNo' and EID = $EID group by CNo")->result_array();

                $total_price = 0;
                if(!empty($price)){
                    $total_price = $price[0]['total_amount'];
                }

                $dis = 0;
                $b= true;
                foreach($res as $key){
                    $item_dis = 0;
                    if($key['CID'] > 0){
                        if($key['MCID'] != $key['CID']){
                            $b = false;
                        }else{
                            $b = true;
                        }
                    }
                    // if($key['CID'] > 0){
                    //  if($key['MCID'] != $key['CID']){
                    //      $b = false;
                    //  }
                    // }
                    if($key['MCatgId'] > 0){
                        if($key['MMCatgId'] != $key['MCatgId']){
                            $b = false;
                        }else{
                            $b = true;
                        }
                    }
                    if($key['ItemTyp'] > 0){
                        if($key['KItemTyp'] != $key['ItemTyp']){
                            $b = false;
                        }else{
                            $b = true;
                        }
                    }
                    if($key['IPCd'] > 0){
                        if($key['Itm_Portion'] != $key['IPCd']){
                            $b = false;
                        }else{
                            $b = true;
                        }
                    }
                    if($b){
                        if(($key['KQty']*$key['ItmRate']) >= $key['MinBillAmt']){
                            if($key['Disc_ItemId'] > 0 && $key['Disc_Qty'] > 0){
                                if($key['Disc_ItemId'] == $key['KItemId'] && $key['Disc_IPCd'] == $key['Itm_Portion']){
                                    if($key['KQty'] >= $key['Qty'] + $key['Disc_Qty']){
                                        $n = $key['KQty'] / ($key['Qty'] + $key['Disc_Qty']);
                                        if($n >= 1){
                                            // if($key['Disc_ItemId'] == $key['KItemId'] && $key['Disc_IPCd'] == $key['Itm_Portion']){
                                                if($key['Disc_pcent'] > 0){
                                                    $item_dis += $key['Disc_pcent']*$key['ItmRate']*$key['Disc_Qty']/100;
                                                }else{
                                                    $item_dis += $key['ItmRate']*$key['Disc_Qty']*$n;
                                                }
                                            // }elseif($key['Disc_ItemId'] != $key['KItemId']){
                                                
                                            // }
                                        }
                                    }
                                }elseif($key['Disc_ItemId'] == $key['KItemId'] && $key['Disc_IPCd'] != $key['Itm_Portion']){
                                    $ch_price = $this->db2->query("SELECT * from Kitchen where CNo=".$CNo." and ItemId = ".$key['Disc_ItemId']." and Itm_Portion = ".$key['Disc_IPCd'])->result_array();
                                    
                                    if(!empty($ch_price)){
                                        if($key['KQty'] >= $key['Qty']){
                                            $n = $key['KQty'] / $key['Qty'];
                                            if($n >= 1){
                                                if($key['Disc_pcent'] > 0){
                                                    $item_dis += $key['Disc_pcent']*$key['ItmRate']*$key['Disc_Qty']/100;
                                                }elseif($ch_price[0]['Qty'] <= $key['Disc_Qty']*$n){
                                                    $item_dis += $ch_price[0]['ItmRate']*$ch_price[0]['Qty'];
                                                }else{
                                                    $item_dis += $ch_price[0]['ItmRate']*$ch_price[0]['Disc_Qty']*$n;
                                                }
                                            }
                                        }
                                    }
                                }elseif($key['Disc_ItemId'] != $key['KItemId']){
                                    $ch_price = $this->db2->query("SELECT * from Kitchen where CNo=".$CNo." and ItemId = ".$key['Disc_ItemId']." and Itm_Portion = ".$key['Disc_IPCd'])->result_array();
                                    
                                    if(!empty($ch_price)){
                                        if($key['KQty'] >= $key['Qty']){
                                            $n = $key['KQty'] / $key['Qty'];
                                            if($n >= 1){
                                                if($key['Disc_pcent'] > 0){
                                                    $item_dis += $key['Disc_pcent']*$key['ItmRate']*$key['Disc_Qty']/100;
                                                }elseif($ch_price[0]['Qty'] <= $key['Disc_Qty']*$n){
                                                    $item_dis += $ch_price[0]['ItmRate']*$ch_price[0]['Qty'];
                                                }else{
                                                    $item_dis += $ch_price[0]['ItmRate']*$ch_price[0]['Disc_Qty']*$n;
                                                }
                                            }
                                        }
                                    }
                                }
                            }elseif($key['Qty'] > 0 && $key['KQty'] >= $key['Qty']){
                                if($key['Disc_pcent'] > 0){
                                    $item_dis += $key['Disc_pcent']*$key['ItmRate']*$key['KQty']/100;
                                }elseif($key['Disc_Amt'] > 0){
                                    $item_dis+=$key['Disc_Amt'];
                                }
                            }elseif($key['Disc_pcent'] > 0){
                                $item_dis += $key['Disc_pcent']*$key['ItmRate']*$key['KQty']/100;
                            }elseif($key['Disc_Amt'] > 0){
                                $item_dis+=$key['Disc_Amt'];
                            }
                        }
                    }
                    $dis+=$item_dis;
                }

                updateRecord('KitchenMain', array('BillDiscAmt' => $dis), array('CNo' => $CNo, 'EID' => $EID));

                $resp1 = '';
                // $statuss = 1;
                $statuss = 2;
                $this->session->set_userdata('KOTNo', 0);
                // if($EType == 5){
                //     $check = $this->db2->select('CNo')
                //                         ->get_where('Kitchen', array('CustId' => $CustId,'EID' => $EID , 'TableNo' => $TableNo, 'Stat' => 1, 'CNo' => $CNo))
                //                         ->row_array();
                //     if(!empty($check)){
                //         $statuss = 2;
                //         updateRecord('Kitchen', array('Stat' => 2), array('CustId' => $CustId,'EID' => $EID , 'TableNo' => $TableNo, 'Stat' => 1, 'CNo' => $CNo));
                //     }
                // }

                // bill based offers

                $SchType = $this->session->userdata('SchType');

                if(in_array($SchType, array(2,3))){

                    $chkOfferKitMain = $this->db2->select('SchCd')->get_where('KitchenMain', array('SchCd' => 0, 'CNo' => $CNo, 'EID' => $EID, 'BillStat' => 0))
                                        ->row_array();
                                        
                    if(!empty($chkOfferKitMain)){
                        $orderValue = $this->db2->select('sum(Qty *  ItmRate) as itmValue')
                                                ->get_where('Kitchen', 
                                                            array('CNo' => $CNo, 
                                                                'Stat <= ' => 5,
                                                                'BillStat' => 0,'EID' => $EID)
                                                            )->row_array();

                        $billOfferAmt = $this->db2->select("c.SchNm, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.Disc_ItemId,if(cod.Disc_ItemId > 0,(select ItemNm from MenuItem where ItemId = cod.Disc_ItemId),'-') as itemName, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent ")
                                    ->order_by('cod.MinBillAmt', 'DESC')
                                    ->join('CustOffers c', 'c.SchCd = cod.SchCd', 'inner')
                                    ->get_where('CustOffersDet cod', 
                                     array('cod.MinBillAmt > ' => $orderValue['itmValue'],
                                        'c.SchCatg <' => 20,
                                        'c.EID' => $EID))
                                    ->result_array();

                        if(!empty($billOfferAmt)){
                            $statuss = 3;
                            $resp1 = $billOfferAmt;
                        }
                    }
                }

                // end bill based offer

                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => $statuss,
                    'resp' => $resp1
                  ));
                 die;
            }

            if ($_POST['getPaymentList']) {
                $kitcheData = $this->db2->query("SELECT k.OrdNo,k.ItemId, sum(k.Qty) as Qty, k.TA, k.Itm_Portion, (if (k.ItemTyp > 0,(CONCAT(mi.ItemNm, ' - ' , k.CustItemDesc)),(mi.ItemNm ))) as ItemNm, k.ItmRate as Value, k.PckCharge, ip.Name as Portion from Kitchen k, KitchenMain km, MenuItem mi,ItemPortions ip where k.Itm_Portion = ip.IPCd and km.EID = $EID AND km.EID = k.EID and (km.CNo = $CNo OR km.MCNo = $CNo) and km.CNo = k.CNo AND k.ItemId = mi.ItemId AND km.BillStat = 0 AND k.Stat = 3 GROUP BY k.OrdNo,k.ItemId, k.Itm_Portion, k.TA, k.ItemTyp, k.CustItemDesc, k.ItmRate, k.PckCharge,ip.Name order BY mi.ItemNm")->result_array();

                if (empty($kitcheData)) {
                    $response = [
                        "status" => 0,
                        "msg" => "No Categary is available"
                    ];
                } else {
                    $response = [
                        "status" => 1,
                        "kitcheData" => $kitcheData
                    ];
                }

                echo json_encode($response);
                die();
            }
        }
    }

    public function billBasedOfferUpdate(){
        $res = '';
        $status = 'error';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $CNo = $this->session->userdata('CNo');
            $orderValue = $this->db2->select('sum(Qty *  ItmRate) as itmValue')
                                        ->get_where('Kitchen', 
                                                    array('CNo' => $CNo, 
                                                        'Stat <= ' => 5,
                                                        'BillStat' => 0)
                                                    )->row_array();
                $billOfferAmt = $this->db2->select('c.SchNm, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.Disc_ItemId, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent ')
                            ->order_by('cod.MinBillAmt', 'DESC')
                            ->join('CustOffers c', 'c.SchCd = cod.SchCd')
                            ->get_where('CustOffersDet cod', 
                             array('cod.MinBillAmt < ' => $orderValue['itmValue'],
                                'c.SchCatg <' => 20))
                            ->row_array();

                if(!empty($billOfferAmt)){
                    updateRecord('KitchenMain', array(
                                         'SchCd' => $billOfferAmt['SchCd'],
                                         'SDetCd' => $billOfferAmt['SDetCd'],
                                         'BillDiscAmt' => $billOfferAmt['Disc_Amt'],
                                         'BillDiscPcent' => $billOfferAmt['Disc_pcent']
                                         ), array('CNo' => $CNo));   
                                    
                     if($billOfferAmt['Disc_ItemId'] > 0){

                        $kitcheData = $this->db2->order_by('OrdNo', 'ASC')
                                                ->get_where('Kitchen', array('CNo' => $CNo))
                                                ->row_array();

                        $Disc_ItemId = $billOfferAmt['Disc_ItemId'];
                        $Disc_IPCd = $billOfferAmt['Disc_IPCd'];
                        $EID = $kitcheData['EID'];
                        $ChainId = $kitcheData['ChainId'];
                        $TableNo = $kitcheData['TableNo'];

                        $mi = $this->db2->query("SELECT `m`.`ItemNm`,`m`.`ItemTyp`, `m`.`KitCd`, `mr`.`ItmRate` as `origRate`
                            FROM `MenuItem` `m`
                            INNER JOIN `MenuItemRates` `mr` ON `mr`.`ItemId` = `m`.`ItemId`
                            WHERE `m`.`ItemId` = $Disc_ItemId
                            AND `mr`.`EID` = $EID
                            AND `mr`.`ChainId` = $ChainId
                            AND `mr`.`Itm_Portion` = $Disc_IPCd
                            AND `mr`.`SecId` = (select SecId from Eat_tables where TableNo = $TableNo and EID = $EID)")
                                ->row_array(); 

                        $newUKOTNO = date('dmy_') . $mi['KitCd'] . "_" . $kitcheData['KOTNo'] . "_" . $kitcheData['FKOTNo'];

                        $kitchenObj['CNo'] = $kitcheData['CNo'];
                        $kitchenObj['MCNo'] = $kitcheData['MCNo'];
                        $kitchenObj['CustId'] = $kitcheData['CustId'];
                        $kitchenObj['COrgId'] = $kitcheData['COrgId'];
                        $kitchenObj['CustNo'] = $kitcheData['CustNo'];
                        $kitchenObj['CellNo'] = $kitcheData['CellNo'];
                        $kitchenObj['EID'] = $kitcheData['EID'];
                        $kitchenObj['ChainId'] = $kitcheData['ChainId'];
                        $kitchenObj['ONo'] = $kitcheData['ONo'];
                        $kitchenObj['KitCd'] = $mi['KitCd'];
                        $kitchenObj['OType'] = $kitcheData['OType'];
                        $kitchenObj['FKOTNo'] = $kitcheData['FKOTNo'];
                        $kitchenObj['KOTNo'] = $kitcheData['KOTNo'];
                        $kitchenObj['UKOTNo'] = $newUKOTNO;         //date('dmy_').$KOTNo;
                        $kitchenObj['TableNo'] = $kitcheData['TableNo'];
                        $kitchenObj['MergeNo'] = $kitcheData['MergeNo'];
                        $kitchenObj['TaxType'] = 0;
                        $kitchenObj['CustRmks'] = 'bill based offer';
                        $kitchenObj['DelTime'] = date('Y-m-d H:i:s');
                        $kitchenObj['TA'] = 0;
                        $kitchenObj['Stat'] = $kitcheData['Stat'];
                        $kitchenObj['LoginCd'] = $kitcheData['CustId'];
                        $kitchenObj['ItemTyp'] = $mi['ItemTyp'];

                        $kitchenObj['ItemId'] = $billOfferAmt['Disc_ItemId'];
                        $kitchenObj['TaxType'] = 0;
                        $kitchenObj['Qty'] = $billOfferAmt['Disc_Qty'];
                        $kitchenObj['ItmRate'] = 0;
                        $kitchenObj['OrigRate'] = $mi['origRate'];    //(m.Value)
                        $kitchenObj['Itm_Portion'] = $billOfferAmt['Disc_IPCd'];
                            
                        $kitchenObj['SchCd'] = $billOfferAmt['SchCd'];
                        $kitchenObj['SDetCd'] = $billOfferAmt['SDetCd'];
                        
                        insertRecord('Kitchen', $kitchenObj);   
                     }
                }
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    public function checkout(){
        // echo "<pre>";
        // print_r($_SESSION);
        // die;

        $data['EID'] = authuser()->EID;
        $data['ChainId'] = authuser()->ChainId;
        $data['TableNo'] = authuser()->TableNo;
        // include_once('config.php');
        $data['CustId'] = $this->session->userdata('CustId');
        $data['ONo'] = $this->session->userdata('ONo');
        $data['EType'] = $this->session->userdata('EType');
        $data['Stall'] = $this->session->userdata('Stall');
        $data['ServChrg'] = $this->session->userdata('ServChrg');
        $data['Tips'] = $this->session->userdata('Tips');
        $data['COrgId'] = $this->session->userdata('COrgId');
        $data['PymtOpt'] = $this->session->userdata('PymtOpt');
        $data['Cash'] = $this->session->userdata('Cash');
        $data['KOTNo'] = $this->session->userdata('KOTNo');
        $CNo = $this->session->userdata('CNo');

        $valCheck = checkCheckout($data['CustId'], $CNo);
        if(empty($valCheck)){
            // after alert
            redirect(base_url('customer/cart'));
        }

        $data['title'] = $this->lang->line('checkout');
        $data['language'] = languageArray();

        $this->load->view('cust/checkout', $data);
    }

    public function go_checkout(){
        $CustId = $this->session->userdata('CustId');
        $CNo = $this->session->userdata('CNo');
        $EID = authuser()->EID;
        $TableNo = authuser()->TableNo;

        $orderOption = $_POST['orderOption'];
        $status = 0;

        if($orderOption == 'yes'){
            $status = 2;
            $resp = 2;
        }else if($orderOption == 'no'){
            $status = 7;
            $resp = 2;
        }else{
            $status = 0;
            $resp = 1;
        }

        if($status > 0){
            updateRecord('Kitchen', array('Stat' => $status), array('CustId' => $CustId,'EID' => $EID , 'TableNo' => $TableNo, 'Stat' => 1, 'CNo' => $CNo));
        }

        header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $resp
              ));
             die;
    }

    public function bill_ajax(){

        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $CustId = $this->session->userdata('CustId');
        $ONo = $this->session->userdata('ONo');
        $EType = $this->session->userdata('EType');
        $CNo = $this->session->userdata('CNo');
        $Cash = $this->session->userdata('Cash');
        $ServChrg = $this->session->userdata('ServChrg');
        $Tips = $this->session->userdata('Tips');
        $PymtOpt = $this->session->userdata('PymtOpt');
        $KOTNo = $this->session->userdata('KOTNo');
        $TableNo = authuser()->TableNo;
        $COrgId = $this->session->userdata('COrgId');
        $CustNo = $this->session->userdata('CustNo');
        $Fest = $this->session->userdata('Fest');

        if ($CustId != '') {

            if (isset($_POST['getBillAmount']) && $_POST['getBillAmount']) {

                // pending for common function 
                // get repository  : billing/getBillAmount.repo.php
                // include('../repository/billing/getBillAmount.repo.php');
                $kitcheData = $this->cust->getBillAmount($EID, $CNo);
                    // echo "<pre>";
                    // print_r($kitcheData);
                    // die;
                $taxDataArray = array();
                if(!empty($kitcheData)){
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

                    foreach ($tax_type_array as $key => $value) {
                        $q = "SELECT t.ShortName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.ItmRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.ItmRate*k.Qty)) - ((sum(k.ItmRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.ItmRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat = 3) and k.EID=km.EID and k.CNo=km.CNo and (km.CNo=$CNo or km.MCNo =$CNo) and t.TaxType = k.TaxType and t.TaxType = $value  and t.EID= $EID AND km.BillStat = 0 group by t.ShortName,t.TNo,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.rank";
                        // print_r($q);exit();
                        $TaxData = $this->db2->query($q)->result_array();
                        $taxDataArray[$value] = $TaxData;
                    }
                }

                $response['kitcheData'] = $kitcheData;
                $response['TaxData'] = $taxDataArray;
                // echo "<pre>";print_r($response);exit();
                $d = $this->db2->get_where('KitchenMain', 
                                    array('CNo' => $CNo))
                                ->result_array();
                $km = 0;
                if(!empty($d)){
                    $km = $d[0];
                }
                $response['kitchen_main_data'] = $km;
                echo json_encode($response);
                die();
                
            }
            if(isset($_POST['get_payment_modes']) && $_POST['get_payment_modes'] == 1){
                $data =  $this->db2->get_where('ConfigPymt', 
                                    array('EID' => $EID, 'stat' => 1))
                                ->result_array();
                $response = [
                        "status" => 1,
                        "payment_modes" => $data
                    ];

                echo json_encode($response);
            }
            if(isset($_POST['getTax']) && $_POST['getTax']){
                $tax_type = $_POST['tax_type'];
                $TaxData = $this->db2->query("SELECT t.ShortName,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included, sum(k.ItmRate) as ItemAmt, (sum(k.ItmRate)*t.TaxPcent/100) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where k.EID = km.EID and k.CNo = km.CNo and (km.CNo = $CNo or km.MCNo = $CNo) and t.TaxType = k.TaxType and k.TaxType = $value and t.EID = $EID group by t.ShortName,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.Rank")->result_array();   // and CurDate() between FrmDt and EndDt

                $response = [
                        "status" => 1,
                        "TaxData" => $TaxData
                    ];

                echo json_encode($response);
                die();
            }

            if (isset($_POST['pay'])) {
                $tips = $_POST['tips'];
                $this->session->set_userdata('TipAmount', $tips);
                $activePaymentModes =  $this->db->get_where('ConfigPymt', array('Stat' => 1))->result_array();
                $response = [
                    "status" => 1,
                    "msg" => "success",
                    "location" => $activePaymentModes[0]['CodePage'],
                ];
                echo json_encode($response);
                die();
            }

            // if (isset($_POST['confirm'])) {
            //     if ($Tips == 1) {
            //         $tips = $_POST['tips'];
            //         $this->session->set_userdata('TipAmount', $tips);
            //     } else {
            //         $this->session->set_userdata('TipAmount', 0);
            //     }

            //     $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7 AND k1.Stat<>9  AND k1.Stat<>99) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7  AND k1.Stat<>9 AND k1.Stat<>99) GROUP BY k1.EID) as TotPckCharge,  ip.Name as Portion, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7  AND k.Stat<>9 AND k.Stat<>10 AND k.Stat<>99) and km.EID = k.EID and km.EID = $EID And k.CNo = km.CNo AND (km.CNo = $CNo OR km.MCNo = $CNo) AND km.BillStat=0 AND TIMEDIFF(Now(), km.LstModDt) < '05:00:00' group by km.CNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm, date(km.LstModDt), k.TaxType, ip.Name, c.ServChrg, c.Tips  order by TaxType, m.ItemNm Asc")->result_array();

            //     include('../repository/payment/payment.repo.php');

            //     if (empty($kitcheData)) {
            //         $response = [
            //             "status" => 0,
            //             "msg" => "No Bill Pending..."
            //         ];
            //         echo json_encode($response);
            //         die();
            //     } else {
            //         $lastBillNo = $this->db2->query("SELECT max(BillNo) as BillNo from Billing where EID = $EID")->result_array();

            //         if ($lastBillNo[0]['BillNo'] == '') {
            //             $newBillNo = 1;
            //         } else {
            //             $newBillNo = $lastBillNo[0]['BillNo'] + 1;
            //         }

            //         $cgst = $kitcheData[0]['CGSTRate'];
            //         $sgst = $kitcheData[0]['SGSTRate'];
            //         $gst = $kitcheData[0]['GSTInclusiveRates'];
            //         $ServChrg = $kitcheData[0]['ServChrg'];
            //         $Tips = $kitcheData[0]['Tips'];
            //         $TotItemDisc = $kitcheData[0]['TotItemDisc'];
            //         $TotPckCharge = $kitcheData[0]['TotPckCharge'];
            //         $DelCharge = $kitcheData[0]['DelCharge'];
            //         $BillDiscAmt = $kitcheData[0]['BillDiscAmt'];

            //         $orderAmount = 0;
            //         foreach ($kitcheData as $key => $data) {
            //             $orderAmount += $data['OrdAmt'];
            //         }

            //         if ($Tips == 0) {
            //             $TipAmount = 0;
            //         } else {
            //             $TipAmount = $this->session->userdata('TipAmount');
            //         }

            //         if ($ServChrg > 0) {
            //             $serviceCharge = $ServChrg * ($orderAmount / 100);
            //         } else {
            //             $serviceCharge = 0;
            //         }

            //         if ($gst == 0) {
            //             if($BillCalc == 0){
            //                 $CGST = number_format((float) ($orderAmount * $cgst / 100), 2, '.', '');
            //                 $SGST = number_format((float) ($orderAmount * $sgst / 100), 2, '.', '');
            //             }else{
            //                 $CGST = number_format((float) (($orderAmount - ($TotItemDisc + $BillDiscAmt)) * $cgst / 100), 2, '.', '');
            //                 $SGST = number_format((float) (($orderAmount - ($TotItemDisc + $BillDiscAmt)) * $sgst / 100), 2, '.', '');
            //             }
            //         } else {
            //             $CGST = 0;
            //             $SGST = 0;
            //         }

            //         switch ($BillCalc) {
            //             case 0:
            //                 $totalAmount =(($orderAmount + $CGST + $SGST) - ($TotItemDisc + $BillDiscAmt) ) + $TotPckCharge + $DelCharge + $TipAmount + $serviceCharge;
            //                 break;
            //             case 1:
            //                 $totalAmount =($orderAmount - ($TotItemDisc + $BillDiscAmt)) + $TotPckCharge + $DelCharge +  $CGST + $SGST + $TipAmount + $serviceCharge;
            //                 break;
            //         }

            //         $billingObj['EID'] = $EID;
            //         $billingObj['TableNo'] = $TableNo;
            //         $billingObj['ChainId'] = $ChainId;
            //         $billingObj['ONo'] = $ONo;
            //         $billingObj['CNo'] = $CNo;
            //         $billingObj['BillNo'] = $newBillNo;
            //         $billingObj['CustId'] = $CustId;
            //         $billingObj['COrgId'] = $COrgId;
            //         $billingObj['CustNo'] = $CustNo;
            //         $billingObj['TotAmt'] = $totalAmount;
            //         $billingObj['SGSTpcent'] = $sgst;
            //         $billingObj['CGSTpcent'] = $cgst;
            //         $billingObj['CGSTAmt'] = $CGST;
            //         $billingObj['SGSTAmt'] = $SGST;
            //         $billingObj['SerCharge'] = $ServChrg;
            //         $billingObj['Tip'] = $tips;
            //         $billingObj['PaymtMode'] = "Post Paid";
            //         $billingObj['PymtRef'] = "NA";
            //         $billingObj['TotItemDisc'] = $TotItemDisc;
            //         $billingObj['BillDiscAmt'] = $BillDiscAmt;
            //         $billingObj['TotPckCharge'] = $TotPckCharge;
            //         $billingObj['DelCharge'] = $DelCharge;

            //         if ($billingObj->create()) {
            //             $lastInsertBillId = $billingObj->lastInsertId();

            //             foreach ($taxDataArray as $key => $value1) {
            //                 foreach ($value1 as $key => $value) {
            //                     $BillingTax['BillId'] = $lastInsertBillId;
            //                     $BillingTax['TNo'] = $value['TNo'];
            //                     $BillingTax['TaxPcent'] = $value['TaxPcent'];
            //                     $BillingTax['TaxAmt'] = $value['SubAmtTax'];
            //                     $BillingTax['EID'] = $EID;
            //                     $BillingTax['TaxIncluded'] = $value['Included'];
            //                     $BillingTax['TaxType'] = $value['TaxType'];
            //                     $BillingTax->create();
            //                 }
            //             }

            //             if ($EType == 1) {
                            
            //                 $stat = 1;
            //             } else {
                              
            //                 $stat = 9;
            //             }

            //             $this->session->set_userdata('KOTNo', 0);
            //             $as = ($this->session->userdata('AutoSettle') == 1)?0:1;
            //             $kitchenUpdate = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.BillStat = $lastInsertBillId, k.Stat = 9, k.payRest = ".$as."  WHERE (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99)  and k.EID = $EID AND km.EID = k.EID and ( (km.CNo = $CNo OR km.MCNo = $CNo) )  AND BillStat = 0 AND k.CNo = km.CNo");

            //             $kitchenMainUpdate = $this->db2->query("UPDATE KitchenMain SET BillStat = $lastInsertBillId, payRest = ".$as." WHERE (CNo = $CNo OR MCNo = $CNo)  AND BillStat = 0 AND EID = $EID ");

            //             $response = [
            //                 "status" => 1,
            //                 "msg" => "success",
            //                 "billId" => $lastInsertBillId
            //             ];
            //         } else {
            //             $response = [
            //                 "status" => 1,
            //                 "msg" => "Fail to insert to Billing  / Billing TAX"
            //             ];
            //         }
            //         echo json_encode($response);
            //         die();
            //     }
            // }

            if (isset($_POST['cash'])) {

                // echo "<pre>";
                // print_r($_POST);
                // die;
                $tips = $_POST['tips'];
                $orderAmount = $_POST['payableAmt'];
                $this->session->set_userdata('TipAmount', $tips);
                $this->session->set_userdata('TipAmount', $tips);
                $itemTotalGross = $_POST['totalAmt'];
                $this->session->set_userdata('itemTotalGross', $itemTotalGross);

                $postData["orderAmount"] = $orderAmount;
                $postData["paymentMode"] = 'cash';

                $res = billCreate($EID, $CNo, $postData);
                echo json_encode($res);
                die();                
            }
        } else {
            // Session Expire
            $response = [
                "status" => "100",
                "msg" => "Session Expired, Please Rescan QR Code"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['checkCasherConfirm'])){
            $billId = $_POST['billId'];

            $checkCasherConfirm = $this->db2->select('CnfSettle')
                                            ->get_where('KitchenMain', array('BillStat' => $billId, 'EID' => $EID, 'CnfSettle >' => 0))
                                            ->row_array();

            if (empty($checkCasherConfirm)) {
                $response = [
                    "status" => 0,
                    "msg" => "Bill is Not Paid"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "msg" => "Bill is confirmed"
                ];
            }

            echo json_encode($response);
            die();
        }

    }

    private function calculatTotalTax($total_tax, $new_tax){
        return $total_tax + $new_tax;
    }

    public function bill($billId){

        $data['title'] = $this->lang->line('bill');
        $data['language'] = languageArray();
        $data['billId'] = $billId;

        $EID = authuser()->EID;
        $CustId = $this->session->userdata('CustId');

        $data['CustNo'] = $this->session->userdata('CustNo');
        $data['CellNo'] = $this->session->userdata('CellNo');

        $dbname = $this->session->userdata('my_db');

        if(isset($_GET['dbn'])){
            $dbname = $_GET['dbn'];
            $EID = $_GET['EID'];
        }
        $flag = 'cust';
        $res = getBillData($dbname, $EID, $billId, $CustId, $flag);
        // echo "<pre>";
        // print_r($res);
        // die;
        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];

            $data['hotelName'] = $billData[0]['Name'];
            $data['BillName'] = $billData[0]['BillName'];
            $data['Fullname'] = $billData[0]['FName'].' '.$billData[0]['LName'];
            $data['phone'] = $billData[0]['PhoneNos'];
            $data['gstno'] = $billData[0]['GSTno'];
            $data['fssaino'] = $billData[0]['FSSAINo'];
            $data['cinno'] = $billData[0]['CINNo'];
            $data['billno'] = $billData[0]['BillNo'];
            $data['dateOfBill'] = date('d-m-Y @ H:i', strtotime($billData[0]['BillDt']));
            $data['address'] = $billData[0]['Addr'];
            $data['pincode'] = $billData[0]['Pincode'];
            $data['city'] = $billData[0]['City'];
            $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
            $data['bservecharge'] = $billData[0]['bservecharge'];
            $data['SerChargeAmt'] = $billData[0]['SerChargeAmt'];

            $data['tipamt'] = $billData[0]['Tip'];
            $Stat = $billData[0]['Stat'];
            $total = 0;
            $sgstamt=0;
            $cgstamt=0;
            $grandTotal = $sgstamt + $cgstamt + $data['bservecharge'] + $data['tipamt'];
            $data['thankuline'] = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

            $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'] + $billData[0]['custDiscAmt'];
            $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
            $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

            $data['billData'] = $res['billData'];
            $this->load->view('cust/billing', $data);
        }else{
            $this->load->view('cust/billing_not', $data);
        }

        
    }

    public function print($billId){

        $data['title'] = $this->lang->line('print');
        $data['billId'] = $billId;

        $EID = authuser()->EID;
        $CustId = $this->session->userdata('CustId');

        $data['CustNo'] = $this->session->userdata('CustNo');
        $data['CellNo'] = $this->session->userdata('CellNo');

        $dbname = $this->session->userdata('my_db');

        $flag = 'cust';
        $res = getBillData($dbname, $EID, $billId, $CustId, $flag);
        // echo "<pre>";
        // print_r($res);
        // die;
        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];

            $data['hotelName'] = $billData[0]['Name'];
            $data['Fullname'] = $billData[0]['FName'].' '.$billData[0]['LName'];
            $data['phone'] = $billData[0]['PhoneNos'];
            $data['gstno'] = $billData[0]['GSTno'];
            $data['fssaino'] = $billData[0]['FSSAINo'];
            $data['cinno'] = $billData[0]['CINNo'];
            $data['billno'] = $billData[0]['BillNo'];
            $data['dateOfBill'] = date('d-m-Y @ H:i', strtotime($billData[0]['BillDt']));
            $data['address'] = $billData[0]['Addr'];
            $data['pincode'] = $billData[0]['Pincode'];
            $data['city'] = $billData[0]['City'];
            $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
            $data['bservecharge'] = $billData[0]['bservecharge'];
            $data['SerChargeAmt'] = $billData[0]['SerChargeAmt'];

            $data['tipamt'] = $billData[0]['Tip'];
            $Stat = $billData[0]['Stat'];
            $total = 0;
            $sgstamt=0;
            $cgstamt=0;
            $grandTotal = $sgstamt + $cgstamt + $data['bservecharge'] + $data['tipamt'];
            $data['thankuline'] = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

            $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'];
            $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
            $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

            $data['billData'] = $res['billData'];
        }
        $this->load->view('print', $data);
    }

    public function updateCustPayment(){
        // $CNo = $this->session->userdata('CNo');
        $EID  = authuser()->EID;
        $billId = $_POST['BillId'];

        $this->db2->query("UPDATE KitchenMain km, Billing b set km.CnfSettle = 1 where b.BillId = $billId  and (km.CNo = b.CNo or km.MCNo = b.CNo) and b.EID=km.EID and b.EID=$EID");
        
        $MergeNo = $this->session->userdata('MergeNo');

        autoSettlePayment($billId, $MergeNo);
        // $this->session->set_userdata('CNo', 0);
    }

    public function merge_order($TableNo){
        // echo "<pre>";
        // print_r($_SESSION);
        // die;
        $data['title'] = $this->lang->line('mergeorder');
        $data['language'] = languageArray();
        $data['orders'] = $this->cust->getOrderDetailsByTableNo($TableNo);
        $data['MergeNo'] = $TableNo;
        $data['Tips'] = 1;
        $data['EType'] = $this->session->userdata('EType');
        $data['Cash'] = $this->session->userdata('Cash');
        $data['CustId'] = $this->session->userdata('CustId');

        if(sizeof($data['orders']) <= 1){
            $this->session->set_flashdata('error','No Orders to Merge from this table.'); 
            redirect(base_url('customer/checkout'));
        }

        $this->load->view('cust/mergeOrder', $data);
    }

    public function get_merge_order(){
        $status = 'error';
        $res = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $TableNo = authuser()->TableNo;
            $CNo = $_POST['CNo'];
            $res = $this->db2->select('m.ItemId,m.ItemNm, k.Qty, k.ItmRate')
                        ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                        ->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
                        ->get_where('KitchenMain km', array('km.MergeNo' => $TableNo,'km.CNo' => $CNo))
                        ->result_array();
            // echo "<pre>";
            // print_r($res);
            // die;
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    public function merge_checkout(){
        $EID = authuser()->EID;

        if($this->input->method(true)=='POST'){
            
            $AllCNo = explode(",",$_POST['AllCNo']);
            $cl = implode(",", $_POST['CNo']);
            $in_CNo = '('.$cl.')';

            $MergeNo = $_POST['MergeNo'];

            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotPckCharge,   ip.Name as Portions,km.CNo,km.MergeNo, km.MCNo,sum(km.BillDiscAmt) as totBillDiscAmt, sum(km.DelCharge) as totDelCharge, sum(km.RtngDiscAmt) as totRtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND km.CNo IN (Select km1.CNo from KitchenMain km1 where km1.CNo in $in_CNo) group by km.CNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm, date(km.LstModDt), k.TaxType, ip.Name, c.ServChrg, c.Tips  order by TaxType, m.ItemNm Asc")->result_array();
                    // echo "<pre>";
                    // print_r($kitcheData);
                    // die;
            $taxDataArray = array();
            if(!empty($kitcheData)){
                $intial_value = $kitcheData[0]['TaxType'];

                $tax_type_array = array();
                $tax_type_array[$intial_value] = $intial_value;

                foreach ($kitcheData as $key => $value) {
                    if($value['TaxType'] != $intial_value){
                        $intial_value = $value['TaxType'];
                        $tax_type_array[$intial_value] = $value['TaxType'];
                    }
                }

                // echo "<pre>";
                // print_r($tax_type_array);
                // die;

                // (km.CNo=$CNo or km.MCNo =$CNo)
                foreach ($tax_type_array as $key => $value) {
                    $q = "SELECT t.ShortName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.ItmRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.ItmRate*k.Qty)) - ((sum(k.ItmRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.ItmRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat = 3) and k.EID=km.EID and k.CNo=km.CNo and km.MergeNo = $MergeNo and t.TaxType = k.TaxType and t.TaxType = $value  and t.EID= $EID AND km.BillStat = 0 and k.BillStat = 0 group by t.ShortName,t.TNo,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.rank";
                    // print_r($q);exit();
                    $TaxData = $this->db2->query($q)->result_array();
                    $taxDataArray[$value] = $TaxData;
                    // print_r($taxDataArray);
                }
                    // print_r($this->db2->last_query());
            }
            // die;

            $response['kitcheData'] = $kitcheData;
            $response['TaxData'] = $taxDataArray;

            // $d = $this->db2->get_where('KitchenMain', 
            //                     array('CNo' => $CNo))
            //                 ->result_array();
            // $km = 0;
            // if(!empty($d)){
            //     $km = $d[0];
            // }
            // $response['kitchen_main_data'] = $km;
            echo json_encode($response);
            die();
        }
    }

    public function splitOrder($MergeNo){
        // echo "<pre>";
        // print_r($_POST);
        // die;
        $EID = authuser()->EID;
        $CNo = $this->session->userdata('CNo');
        for ($i=0; $i < sizeof($_POST['mobile']) ; $i++) { 
            $ccno = $_POST['CNo'][$i];
            updateRecord('KitchenMain', array('MCNo' => $CNo), array('CNo' => $ccno));
            updateRecord('Kitchen', array('MCNo' => $CNo), array('CNo' => $ccno));
        }
        $data['mobile'] = $this->session->userdata('split_mobile');
        $data['payable'] = $_POST['payable'];
        $data['grossItemAmt'] = $_POST['grossItemAmt'];
        $data['tip'] = $_POST['tip'];
        $data['MCNo'] = $CNo;
        $data['tot_sgst'] = $_POST['tot_sgst'];
        
        $data['title'] = $this->lang->line('splitbill');
        $data['language'] = languageArray();
        $data['MergeNo'] = $MergeNo;
        if($_POST['btnName'] == 'splitBill'){
            $this->load->view('cust/split_bill', $data);
        }else if($_POST['btnName'] == 'payNow'){
            
            // bill generate
            $pData["orderAmount"] = $data['payable'];
            $pData["paymentMode"] = 'multiPayment';
            $this->session->set_userdata('payable', $data['payable']);
            $this->session->set_userdata('TipAmount', $data['tip']);
            $this->session->set_userdata('itemTotalGross', $data['grossItemAmt']);
            // $res = billCreate($EID, $CNo, $pData);
            $BillId = 1233;
            $this->session->set_userdata('BillId', $BillId);
            redirect(base_url('customer/pay/'.$BillId.'/'.$data['MCNo']));
        }
    }

    public function splitBill(){
        // echo "<pre>";
        // print_r($_POST);
        // die;
        $CNo = $_POST['MCNo'];
        $EID = authuser()->EID;
        $res = array();
        for ($i=0; $i < sizeof($_POST['mobile']);  $i++) { 
            $pData['paymentMode'] = 'Due';
            $pData['CellNo'] = $_POST['mobile'][$i];
            $pData['CNo'] = $_POST['MCNo'];
            $pData['TotalGross'] = $_POST['totItemAmt'][$i];
            $pData['orderAmount'] = $_POST['amount'][$i];
            $pData['per_cent'] = $_POST['percent'][$i] / 100;
            $pData['TipAmount'] = 0;
            $pData['splitType'] = $_POST['splitType'];
            $pData['MergeNo'] = $_POST['MergeNo'];
            $pData['tot_sgst'] = $_POST['tot_sgst'];

            $pData['itemTotalGross'] = round($pData['TotalGross'] + ($pData['TotalGross'] * $pData['tot_sgst']) / 100);
            // echo "<pre>";
            // print_r($pData);
            // die;
            $res = billCreate($EID, $CNo, $pData);
            // link send with bill no, sms or email => pending status
        }

        if(!empty($res)){
            if($res['status'] > 0){
                // after bill generate = 1 => status
                redirect(base_url('customer'), 'refresh');
            }else{
                redirect(base_url('customer/splitOrder/'.$_POST['MergeNo']), 'refresh');
            }
        }else{
            redirect(base_url('customer/splitOrder/'.$_POST['MergeNo']), 'refresh');
        }
    }

    public function rating($billId){

        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $cId = $this->session->userdata('cId');
        $cType = $this->session->userdata('cType');
        $mCatgId = $this->session->userdata('mCatgId');
        $EType = $this->session->userdata('EType');
        $CustId = $this->session->userdata('CustId');
        $CellNo = $this->session->userdata('CellNo');

        $rat = 0;
        if(isset($_GET['rat'])){
            $rat = base64_decode(rtrim($_GET['rat'], "="));
            $dd = explode("_", $rat);
            $EID = $dd[0];
            $ChainId = $dd[1];
            $billId = $dd[2];
            $CustId = 0;
        }

        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;

            extract($_POST);

            $CellNo = ($mobileR > 0) ? $mobileR : $CellNo;
            $CustId = ($custidR > 0) ? $custidR : $CustId;

            $checkid = $this->db2->query("SELECT RCd  FROM `Ratings` WHERE EID = $EID AND BillId = $billid AND CustId = $CustId AND CellNo = $CellNo")->result_array();

            if (!empty($checkid)) {
                $RCd = $checkid[0]['RCd'];
                $this->db2->query("DELETE FROM `Ratings` WHERE EID = $EID AND BillId = $billid AND CustId = $CustId AND CellNo = $CellNo");
                $this->db2->query("DELETE FROM `RatingDet` WHERE RCd = $RCd");
            }

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
            $genCheckid = $genTblDb->query("SELECT RCd  FROM `Ratings` WHERE EID = $EID AND BillId = $billid AND CustId = $CustId AND CellNo = $CellNo")->result_array();

            // gen db
            if (!empty($genCheckid)) {
                $RCd = $genCheckid[0]['RCd'];
                $deleteRating = $genTblDb->query("DELETE FROM `Ratings` WHERE EID = $EID AND BillId = $billid AND CustId = $CustId AND CellNo = $CellNo");
                $deleteRatingDet = $genTblDb->query("DELETE FROM `RatingDet` WHERE RCd = $RCd");
            }
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
        $data['language'] = languageArray();
        $data['billId'] = $billId;

        $url = $EID . "_" . $ChainId . "_" . $billId;

        $url = base64_encode($url);
        $url = rtrim($url, "=");
        $data['link'] = base_url('customer/rating/'.$billId).'?rat='.$url;
        // print_r($data['link']);die;

        // $data['link'] = "https://qs.vtrend.org/share_rating.php?qs=" . $CustId . "_" . $EID . "_" . $ChainId . "_" . $billId . "_" . $CellNo . "";

        $data['kitchenGetData'] = $this->db2->select('b.BillId,k.ItemId , m.UItmCd, CONCAT(m.ItemNm, k.CustItemDesc) as ItemNm, k.CustItemDesc')
                                    ->order_by('m.ItemNm','ASC')
                                    ->group_by('k.ItemId,k.MCNo')
                                    ->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
                                    ->join('Kitchen k', 'k.MCNo = km.MCNo', 'inner')
                                    ->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
                                    ->get_where('Billing b', array('b.BillId' => $billId, 'k.Stat' => 3, 'b.EID' => $EID))
                                    ->result_array();
        // echo "<pre>";
        // print_r($data);
        // die;

        $this->load->view('cust/rating', $data);
        
    }

    public function genOTPRating(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            
            $otp = 1212;

            $this->session->set_userdata('ratOTP', $otp);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $otp
              ));
             die;
        }
    }
    function verifyOTPRating(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('ratOTP');
            if($_POST['otp'] == $otp){
                $status = 'success';
                
                $genTblDb = $this->load->database('GenTableData', TRUE);

                $check = $genTblDb->select('CustId, FName, LName')
                                    ->get_where('AllUsers', array('MobileNo' => $_POST['mobile']))
                                    ->row_array();
                $resp['msg'] = "not";
                if(!empty($check)){
                    $resp['msg'] = "data";
                    $resp['data'] = $check;
                }
                $response = $resp;

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

    public function share_rating($billId, $MCNo){
        $data['title'] = $this->lang->line('sharerating');
        $data['language'] = languageArray();
        $data['billId'] = $billId;
        $data['shareDetails'] = $this->cust->getShareDetails($billId, $MCNo);
        $this->load->view('cust/share_rating', $data);
    }

    public function transactions(){

        $data['title'] = $this->lang->line('transactions');
        $data['country'] = 0;
        $data['city'] = 0;
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $data['country'] = $_POST['country'];
            $data['city'] = $_POST['city'];
        }

        $CustId = $this->session->userdata('CustId');
        $genTblDb = $this->load->database('GenTableData', TRUE);
        // $data['custPymt'] = $genTblDb->query("SELECT date(cp.BillDt) as billdt , cp.BillId, cp.BillNo, cp.EID , cp.PaidAmt , cp.CustId , ed.Name , ed.DBName, ed.DBPasswd FROM `CustPymts` cp , `EIDDet` ed where cp.CustId = $CustId and ed.EID = cp.EID ORDER BY `BNo`  DESC")->result_array();
        if(!empty($data['country'])){
         $genTblDb->where('ed.CountryCd',$data['country']);
        }
        if(!empty($data['city'])){
         $genTblDb->where('ed.city_id',$data['city']);
        }
        $data['custPymt'] = $genTblDb->select('date(cp.BillDt) as billdt , cp.BillId, cp.BillNo, cp.EID , cp.PaidAmt , cp.CustId , ed.Name , ed.DBName, ed.DBPasswd')
                            ->order_by('BNo', 'DESC')
                            ->join('EIDDet ed', 'ed.EID = cp.EID', 'inner')
                            ->get_where('CustPymts cp', array('cp.CustId' => $CustId))
                            ->result_array();
        $data['language'] = languageArray();
        $data['countryList'] = $this->cust->getCountryList();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('cust/transactions', $data);
    }

    public function getCityList(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->cust->getCityListByCountry($_POST['phone_code']);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function reserve_table(){
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            echo "<pre>";
            print_r($_POST);
            die;

            $TblRsv['GuestNos'] = $_POST['GuestNos'];
            $TblRsv['GuestName'] = $_POST['GuestName'];
            $TblRsv['MobileNo'] = $_POST['MobileNo'];
            $TblRsv['FrmTime'] = $_POST['FrmTime'];
            $TblRsv['ToTime'] = $_POST['ToTime'];
            $TblRsv['RDate'] = date('Y-m-d H:i:s',strtotime($_POST['RDate']));
            $TblRsv['LstModDt'] = date('Y-m-d H:i:s');

            $RsvNo = insertRecord('TableReserve', $TblRsv);

            for($i = 0;$i<sizeof($_POST['CustName']);$i++){
                $TblRsvDet['RsvNo'] = $RsvNo;
                $TblRsvDet['CustName'] = $_POST['CustName'][$i];
                $TblRsvDet['MobileNo'] = $_POST['CustMobileNo'][$i];
                $TblRsvDet['Stat'] = 0;
                insertRecord('TableReserveDet', $TblRsvDet);
            }

            $status = 'success';
            $res = 'Record has been inserted.';
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }

        $data['title'] = $this->lang->line('reservetable');
        $data['language'] = languageArray();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('cust/reserve_table', $data);

    }

    public function logout(){

        $this->session->set_userdata('signup', '');
        $this->session->set_userdata('CustId', '');
        $this->session->set_userdata('CNo', '');
        $this->session->set_userdata('emailMobile', '');
        $this->session->set_userdata('cust_otp', '');
        $this->session->set_userdata('KOTNo', 0);
        $this->session->set_userdata('CellNo', 0);

        redirect(base_url('customer'), 'refresh');
    }


    public function contact(){
        $data['title'] = $this->lang->line('contact');
        $data['language'] = languageArray();
        if($this->input->method(true)=='POST'){
            $feedback = $_POST;
            $id = insertRecord('Feedback', $feedback);
            if(!empty($id)){
                $this->session->set_flashdata('success','Record Inserted.');
            }
        }

        $this->load->view('contact', $data);
    }

    public function privacy_policy(){
       $data['title'] = $this->lang->line('PrivacyPolicy');
        $data['language'] = languageArray();

        $this->load->view('privacyPolicy', $data); 
    }

    public function terms_conditions(){
       $data['title'] = $this->lang->line('TermsConditions');
        $data['language'] = languageArray();

        $this->load->view('terms_conditions', $data); 
    }

    public function cookie_policy(){
       $data['title'] = $this->lang->line('Cookie Policy');
       $data['language'] = languageArray();

        $this->load->view('CookiePolicy', $data); 
    }

    public function checkout_pay(){
        $status = "error";
        $response = "Something went wrong! Try again later.";

        if($this->input->method(true)=='POST'){

            // echo "<pre>";
            // print_r($_POST);
            // die;    

            $pData["orderAmount"] = $_POST['payable'];
            $pData["paymentMode"] = 'multiPayment';
            $this->session->set_userdata('payable', $_POST['payable']);
            $this->session->set_userdata('TipAmount', $_POST['tip']);
            $this->session->set_userdata('itemTotalGross', $_POST['itemGrossAmt']);
            $EID = authuser()->EID;
            $CNo = $this->session->userdata('CNo');
            $res = billCreate($EID, $CNo, $pData);
            
            if(!empty($res)){
                if($res['status'] > 0){
                    $status = 'success';
                    $this->session->set_userdata('BillId', $res['billId']);
                    $dt['BillId'] = $res['billId'];
                    $dt['MCNo'] = $CNo;
                    $response = $dt;
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function pay($BillId, $MCNo){
        $data['title'] = $this->lang->line('payNow');
        $data['language'] = languageArray();
        $data["modes"] = $this->cust->getPaymentModes();
        $data['payable'] = $this->session->userdata('payable');
        $data['BillId'] = $BillId;
        $data['MCNo'] = $MCNo;
        $data["splitBills"] = $this->cust->getSplitPayments($BillId);
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('cust/pay_now', $data);
    }

    public function multi_payment(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $pay['BillId'] = $_POST['BillId'];
            $pay['MCNo'] = $_POST['MCNo'];
            $pay['MergeNo'] = $this->session->userdata('MergeNo');
            $pay['TotBillAmt'] = $_POST['payable'];
            $pay['CellNo'] = $this->session->userdata('CellNo');
            $pay['SplitTyp'] = 0;
            $pay['SplitAmt'] = 0;
            $pay['PymtId'] = 0;
            $pay['PaidAmt'] = $_POST['amount'];
            $pay['OrderRef'] = 0;
            $pay['PaymtMode'] = $_POST['mode'];
            $pay['PymtType'] = 0;
            $pay['PymtRef'] = 0;
            $pay['Stat'] = 0;
            $pay['EID'] = authuser()->EID;

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

    public function check_bill_offer(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $status = "success";
            $amount = round($_POST['payable']);

            $data = $this->db2->select('cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.Disc_ItemId, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent ')
                            ->order_by('cod.MinBillAmt', 'DESC')
                            ->join('CustOffers c', 'c.SchCd = cod.SchCd')
                            ->get_where('CustOffersDet cod', array('cod.MinBillAmt < ' => $amount))
                            ->row_array();
            if(!empty($data)){

                $grand_total = 0;
                $bill_based_saving = 0;
                if($data['Bill_Disc_pcent'] > 0){
                    $dis_amt = $amount * $data['Bill_Disc_pcent'] / 100;
                    $grand_total = $amount - $dis_amt;
                    $bill_based_saving = $dis_amt;
                }

                if($data['Disc_Amt'] > 0){
                    $grand_total = $amount - $data['Disc_Amt'];
                    $bill_based_saving = $data['Disc_Amt'];
                }

                if($data['Disc_ItemId'] > 0){
                    $Disc_ItemId = $data['Disc_ItemId'];
                    $Disc_IPCd = $data['Disc_IPCd'];
                    $TableNo = authuser()->TableNo;
                    $EID = authuser()->EID;
                    $ChainId = authuser()->ChainId;

                    $disRates = $this->db2->query("select mi.ItmRate from MenuItemRates as mi where mi.EID = $EID and mi.ChainId = $ChainId and mi.ItemId = $Disc_ItemId and mi.Itm_Portion = $Disc_IPCd and mi.SecId = (select et.SecId from Eat_tables et where et.TableNo = $TableNo and et.EID = $EID)")->row_array(); 
                    
                    if(!empty($disRates)){
                        $bill_based_saving = $data['Disc_Qty'] * $disRates['ItmRate'];
                    }

                }

                $res['msg'] = 'found';
                $res['grand_total'] = $grand_total;
                $res['bill_based_saving'] = $bill_based_saving;

                // $res['grand_total'] = 2960;
                // $res['bill_based_saving'] = 5;

            }else{
                $res['msg'] = 'not';
            }

            $response = $res;
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_custom_item(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $status = "success";
            
            $itemId = $_POST['itemId'];
            $itemTyp = $_POST['itemTyp'];
            $itemPortionCode = $_POST['itemPortionCode'];
            $FID = $_POST['FID'];
            
            $customDetails = $this->cust->getCustomDetails($itemTyp, $itemId, $itemPortionCode, $FID);

            $grpType = $customDetails[0]['GrpType'];
            $itemGroupCd = $customDetails[0]['ItemGrpCd'];
            $itemGroup = $customDetails[0]['ItemGrpName'];
            $itemReq = $customDetails[0]['Reqd'];

            $returnData = [];

            $temp['GrpType'] = $grpType;
            $temp['ItemGrpCd'] = $itemGroupCd;
            $temp['ItemGrpName'] = $itemGroup;
            $temp['Reqd'] = $itemReq;
            $temp['Details'] = [];

            foreach ($customDetails as $key => $value) {
                if ($value['ItemGrpName'] == $itemGroup) {
                    $temp['Details'][] = [
                        "Name" => $value['Name'],
                        "Rate" => $value['Rate'],
                        "ItemOptCd" => $value['ItemOptCd'],
                    ];
                } else {
                    $returnData[] = $temp;
                    $grpType = $value['GrpType'];
                    $itemGroupCd = $value['ItemGrpCd'];
                    $itemGroup = $value['ItemGrpName'];
                    $itemReq = $value['Reqd'];
                    $temp['GrpType'] = $grpType;
                    $temp['ItemGrpCd'] = $itemGroupCd;
                    $temp['ItemGrpName'] = $itemGroup;
                    $temp['Reqd'] = $itemReq;
                    $temp['Details'] = [];
                    $temp['Details'][] = [
                        "Name" => $value['Name'],
                        "Rate" => $value['Rate'],
                        "ItemOptCd" => $value['ItemOptCd'],
                    ];
                }
            }

            $returnData[] = $temp;
            $response =  $returnData;

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    // copy from sendtoKitchen
    public function kotGenerate(){
        $CustId = $this->session->userdata('CustId');
        $EID = authuser()->EID;
        $CNo = $this->session->userdata('CNo');
        $TableNo = authuser()->TableNo;

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $i=0;
            foreach ($_POST['OrdNo'] as $OrdNo ) {
                $temp['OrdNo'] = $OrdNo;
                $temp['qty'] = $_POST['qty'][$i];
                updateRecord('Kitchen', array('Qty' => $temp['qty']), array('OrdNo' => $OrdNo));
                $i++;
            }

            updateRecord('Kitchen', array('Stat' => 3), array('CustId' => $CustId,'EID' => $EID , 'TableNo' => $TableNo, 'Stat' => 2, 'CNo' => $CNo));
            // set Kot to 0
            $this->session->set_userdata('KOTNo', 0);
            $status = 'success';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => 'Order Sent To Kitchen Successfully'
              ));
             die;
        }    
    }

    public function profile(){
        $data['title'] = $this->lang->line('profile');
        $data['language'] = languageArray();
        $CustId = $this->session->userdata('CustId');

        $data['detail'] = $this->cust->getUserDetails($CustId);

        if($this->input->method(true)=='POST'){

            $updata['FName'] = $_POST['FName'];
            $updata['LName'] = $_POST['LName'];
            $updata['Gender'] = $_POST['Gender'];
            $updata['DOB'] = date('Y-m-d', strtotime($_POST['DOB']));
            updateRecord('Users', $updata, array('CustId' => $CustId));

            $genTblDb = $this->load->database('GenTableData', TRUE);
            $genTblDb->update('AllUsers', $updata, array('CustId' => $CustId));

            $this->session->set_flashdata('success','Profile has been updated.');
        }
        // echo "<pre>";
        // print_r($data);
        // die;

        $this->load->view('cust/profile', $data);
    }

    public function current_order(){
        $data['title'] = $this->lang->line('currentorder');
        $data['language'] = languageArray();
        $CustId = $this->session->userdata('CustId');
        if($this->session->userdata('CNo') > 0){

            $data['detail'] = $this->cust->getCurrenOrderBill($CustId);
            
            if(!empty($data['detail'])){
                if($data['detail'] > 0){
                    $billData = $this->db2->select('BillId, Stat, CNo, PaidAmt')
                                        ->order_by('Billid','DESC')
                                        ->get_where('Billing', array('CustId' => $CustId,
                                                             'EID' => authuser()->EID
                                                            )
                                                    )->row_array();
                    if(!empty($billData)){
                        $this->session->set_userdata('payable', $billData['PaidAmt']); 
                        redirect(base_url('customer/pay/'.$billData['BillId'].'/'.$billData['CNo']));
                    }else{
                        redirect(base_url('customer/checkout'));    
                    }
                }else{
                    redirect(base_url('customer/checkout'));
                }
            }else{
                    redirect(base_url('customer/checkout'));
                }
        }else{
            redirect(base_url('customer'));
        }
    }

    public function tokenGenerate(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $data['token'] = $_POST['token']; 
            $data['updated_token_time'] = date('Y-m-d H:i:s'); 
            $CustId = $this->session->userdata('CustId');
            updateRecord('Users', $data, array('CustId' => $CustId) );
            $status = 'success';
            $response = 'Token Generated.';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function test(){

        $pay['BillId'] = 1;
            $pay['MCNo'] = 2;
            $pay['MergeNo'] = 22;
            $pay['TotBillAmt'] = 2;
            $pay['CellNo'] = '9988766554';
            $pay['SplitTyp'] = 0;
            $pay['SplitAmt'] = 0;
            $pay['PymtId'] = 0;
            $pay['PaidAmt'] = 2;
            $pay['OrderRef'] = '46-51-22-13-8-13-2';
            $pay['PaymtMode'] = 1;
            $pay['PymtType'] = 0;
            $pay['PymtRef'] = 'T2311091329142129036638';
            $pay['Stat'] = 1;
            $pay['EID'] = 51;
            echo "<pre>";
            print_r($pay);
            $this->db2->insert('BillPayments', $pay);
            die;

        $mi = $this->db2->select('ItemId,IMcCd')->order_by('ItemId','ASC')->get('MenuItem')->result_array();
        echo "<pre>";
        print_r($mi);
        die;
        
        $count = 1;
        // foreach ($mi as &$key) {
        //     $key['index'] = $count++;
        //     updateRecord('MenuItem', array('IMcCd' => $key['index']), array('ItemId' => $key['ItemId'], 'EID' => authuser()->EID));
        // }
        print_r($mi);
            die;
        $data['title'] = 'Item Details';
        $data['language'] = languageArray();
        $data['Itm_Portion'] = 1;

        $this->load->view('cust/testing', $data);
    }


}
