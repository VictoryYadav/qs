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
        $this->output->delete_cache();
	}

    function switchLang() {
        // https://www.codexworld.com/multi-language-implementation-in-codeigniter/
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            extract($_POST);
            $langId = ($langId != "") ? $langId : 1;
            $langName = ($langName != "") ? $langName : 'english';
            $this->session->set_userdata('site_lang', $langId);
            $this->session->set_userdata('site_langName', $langName);
            $response = $langId;
            
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

        // $ll = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        // print_r($ll);die;

        // print_r(base64_decode('ZT01MSZjPTAmdD0yMiZvPTA'));
        // die;

        // $site_lang = $this->session->set_userdata('site_lang', 3);

        // $input = '111';
        // $dd = convertToUnicodeNumber($input);
        // echo "<pre>";
        // print_r($dd);
        // die;
        

        echo "<pre>";
        print_r($_SESSION);
        die;
        
    }

    public function outlets(){

        $data['title'] = $this->lang->line('multiOutlets');
        $dbEID = $this->session->userdata('aggEID');

        $data['outlets'] = $this->db2->select('EID, Name, Stall, QRLink')
                                    ->order_by('Stall', 'ASC')
                                    ->get_where('Eatary', 
                                        array('CatgId' => 3, 
                                            'Stat' => 0,
                                            'dbEID' => $dbEID)
                                        )
                                    ->result_array();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('cust/multi_outlets', $data);
    }

    public function gotoOutlet(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $qr_code = $this->session->userdata('qr_code');
            $response = base_url('qr?qr_data='.$qr_code);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function index(){
       $data['cuisinList'] = $this->cust->getCuisineList();
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

        $data['title'] = $this->lang->line('main');
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
            
            extract($_POST);

            $filter = array();
            $mcat_ctyp = $this->db2->select('MCatgId, CTyp, CID')
             ->get_where('MenuCatg', array('MCatgId' => $mcatId))
             ->row_array();

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as LngName";

            $select_sql = "FID, Rank, $lname";
            $filter_list = $this->db2->select($select_sql)
                                ->order_by('Rank', 'ASC')
                                ->get_where('FoodType', array('CTyp' => $mcat_ctyp['CTyp'], 'Stat' => 0))
                                ->result_array();
            if(!empty($filter_list)){
                $filter = $filter_list;
             } 
                    
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $filter,
                'all' => $this->lang->line('all')
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

    public function searchItemList(){
        $langId = $this->session->userdata('site_lang');
        $EID  = authuser()->EID;

        if (isset($_POST['searchItemCust']) && $_POST['searchItemCust']) {
            $itemName = $_POST['itemName'];

            $lname = "i.ItemNm$langId ItemNm";
            $ItmDesc = "i.ItmDesc$langId ItmDesc";
           
            $items = $this->db2->query("SELECT i.ItemId, $lname, mir.Itm_Portion, mir.OrigRate, AvgRtng, $ItmDesc, i.ItemNm1 as imgSrc, ItemTyp, KitCd, MCatgId, i.FID, i.CID, i.PrepTime, i.NV FROM MenuItem i, MenuItemRates mir where i.ItemNm1 like '$itemName%' AND i.Stat = 0 AND i.EID = $EID AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId = mir.ItemId and mir.OrigRate > 0 and mir.EID = $EID group by i.ItemId order by Rank")->result_array();
            
            if (!empty($items)) {

                foreach ($items as $key => $data) {
                    $imgSrc = "uploads/e$EID/" . trim($data['imgSrc']) . ".jpg";

                    if (!file_exists($imgSrc)) {
                        $imgSrc = "uploads/general/" . trim($data['imgSrc']) . ".jpg";
                    }

                    $items[$key]['imgSrc'] =  base_url().$imgSrc;
                }

                $response = [
                    "status" => 1,
                    "items" => $items
                ];
            } else {
                $response = [
                    "status" => 0,
                    "msg" => "NO Item Found"
                ];
            }
            echo json_encode($response);
            die();
        }
    }

    // cart details
    public function cart(){
        $sts = 'error';
        $response = 'Something went wrong';
        $EID = authuser()->EID;
        $EType = $this->session->userdata('EType');
        if($this->input->method(true)=='POST'){

            $CustId = $this->session->userdata('CustId');
            $TempCustId = $this->session->userdata('TempCustId');
            $ChainId = authuser()->ChainId;
            $CNo = $this->session->userdata('CNo');
            
            $TableNo = authuser()->TableNo;
            $KOTNo = $this->session->userdata('KOTNo');
            $MultiKitchen = $this->session->userdata('MultiKitchen');
            $Kitchen = $this->session->userdata('Kitchen');

            if ($CustId != '') {

                if (isset($_POST['getSendToKitchenList']) && $_POST['getSendToKitchenList']) {

                    $qry = " k.Stat = 1";
                    if($EType == 5){
                        $qry = " (k.Stat = 1 or k.Stat = 2)";
                    }

                    $langId = $this->session->userdata('site_lang');
                    $lname = "mi.ItemNm$langId";
                    $ipName = "ip.Name$langId as Portions";

                    // Get all Temp Item list
                    $kitcheData = $this->db2->query("SELECT k.OrdNo, k.ItemId, k.Qty, k.TA, k.Itm_Portion, (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,  k.ItmRate as Value, mi.PckCharge, k.OType, k.OrdTime , $ipName, k.Stat from Kitchen k, MenuItem mi,ItemPortions ip where k.Itm_Portion = ip.IPCd and k.CustId = $CustId AND k.EID = $EID AND k.TableNo = $TableNo AND k.ItemId = mi.ItemId AND k.BillStat = 0 AND $qry and k.CNo = $CNo")
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
            redirect(base_url('customer'));
        }

        $data['cId'] = $this->session->userdata('cId');
        $data['mCatgId'] = $this->session->userdata('mCatgId');
        $data['cType'] = $this->session->userdata('cType');
        $data['EID'] = $EID;
        $data['EType'] = $EType;

        $data['title'] = $this->lang->line('cart');
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

            $langId = $this->session->userdata('site_lang');
            $lname = "mi.ItemNm$langId as ItemNm";
            $iDesc = "mi.ItmDesc$langId as ItmDesc";
            $ingeredients = "mi.Ingeredients$langId as Ingeredients";
            $Rmks = "mi.Rmks$langId as Rmks";

            $select = "mc.TaxType, mc.KitCd, mi.ItemId, $lname, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, $iDesc, $ingeredients, $Rmks, mi.PrepTime, mi.AvgRtng, mi.FID, ItemNm1 as imgSrc, mi.UItmCd,mi.CID,mi.Itm_Portion,mi.Value,mi.MCatgId,  (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate,(select mir.Itm_Portion FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as Itm_Portions, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$EID' and et1.TableNo = '$TableNo') as TblTyp";
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
                            // $OType = 8;
                        }else if($TblTyp == 105){
                            // personal TakeAway
                            $OType = 105;
                        }else if($TblTyp == 110){
                            // Rest Delivery
                            $OType = 110;
                        }else if($TblTyp == 101){
                            // 3P Delivery - swiggy/zomato
                            $OType = 101;
                        }else if($TblTyp == 115){
                            // Drive-In
                            $OType = 115;
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
                            $stat = 2;
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

        if ($this->session->userdata('CustId') > 0) {
            redirect(base_url('customer'));
        } else {
            $status = 'error';
            $response = 'Something went wrong plz try again!';
            if($this->input->method(true)=='POST'){
                $emailMobile = $_POST['emailMobile'];
                $check = $this->db2->select('MobileNo')
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
                    
                    $genData = $this->cust->checkUserFromGenDb($emailMobile);
                    if(!empty($genData)){
                        $otp = generateOTP($emailMobile, 'login');
                        $status = 'success';
                        $response = "Your otp is ";
                        $this->session->set_userdata('emailMobile', $emailMobile);

                        $CustId = $genData['CustId'];
                    
                        $data1['CustId']    = $CustId;
                        $data1['FName']     = $genData['FName'];
                        $data1['LName']     = $genData['LName'];
                        // $data1['email']     = $genData['email'];
                        $data1['MobileNo']  = $genData['MobileNo'];
                        $data1['DOB']       = $genData['DOB'];
                        $data1['Gender']    = $genData['Gender'];
                        $data1['PWDHash']   = md5('eatout246');
                        insertRecord('Users',$data1);
                    }else{
                        $response = "Username is not found!";
                    }
                }

                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
                 die;
            }

            $data['title'] = $this->lang->line('log_in');
            $this->load->view('cust/login', $data);
        }

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

        if ($this->session->userdata('CustId') > 0) {
            redirect(base_url('customer'));
        }else{
            $status = 'error';
            $response = 'Something went wrong plz try again!';
            if($this->input->method(true)=='POST'){
                $this->session->set_userdata('signup', $_POST);
                // echo "<pre>";
                // print_r($_SESSION);
                // die;
                $checkUser = $this->db2->select('MobileNo')
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
            $this->load->view('cust/signup', $data);
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

                $this->session->set_userdata('CellNo', $ses_data['MobileNo']);

                $EType = $this->session->userdata('EType');
                $EID = authuser()->EID;
                $TableNo = authuser()->TableNo;
                $CustId = 0;

                $gen_check = $this->cust->checkUserFromGenDb($ses_data['MobileNo']);

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
                    $data1['Passwd']    = 'eo1234';
                    $data1['PWDHash']   = md5($data1['Passwd']);
                    insertRecord('Users',$data1);    
                }else{
                    $genTblDb = $this->load->database('GenTableData', TRUE);
                    $data = $ses_data;

                    $Adata = $data;
                    $Adata['EID'] = authuser()->EID;
                    $Adata['page'] = 'signup';
                    $genTblDb->insert('AllUsers', $Adata);
                    $CustId = $genTblDb->insert_id();
                    $this->session->set_userdata('CustId', $CustId);
                    $data['CustId'] = $CustId;
                    $data['Passwd'] = 'eo1234';
                    $data['PWDHash'] = md5($data['Passwd']);
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

    public function resend_payment_OTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $mobileNO = $this->session->userdata('CellNo');

            $otp = rand(9999,1000);
            $this->session->set_userdata('payment_otp', $otp);
            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            sendSMS($mobileNO, $msgText);

            $status = "success";
            $res = 'Resend OTP Successfully.';

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

                $stat = ($EType == 5)?3:2;

                $i=0;
                foreach ($_POST['OrdNo'] as $OrdNo ) {
                    $temp['OrdNo'] = $OrdNo;
                    $temp['qty'] = $_POST['qty'][$i];
                    // $data[] = $temp;
                    updateRecord('Kitchen', array('Qty' => $temp['qty'],'Stat' => $stat), array('OrdNo' => $OrdNo, 'EID' => $EID));
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

                        $billOfferAmt = $this->db2->select("c.SchNm, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.Disc_ItemId,if(cod.Disc_ItemId > 0,(select ItemNm1 from MenuItem where ItemId = cod.Disc_ItemId),'-') as itemName, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent ")
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

            // if ($_POST['getPaymentList']) {
            //     $kitcheData = $this->db2->query("SELECT k.OrdNo,k.ItemId, sum(k.Qty) as Qty, k.TA, k.Itm_Portion, (if (k.ItemTyp > 0,(CONCAT(mi.ItemNm1, ' - ' , k.CustItemDesc)),(mi.ItemNm1 ))) as ItemNm, k.ItmRate as Value, k.PckCharge, ip.Name as Portion from Kitchen k, KitchenMain km, MenuItem mi,ItemPortions ip where k.Itm_Portion = ip.IPCd and km.EID = $EID AND km.EID = k.EID and (km.CNo = $CNo OR km.MCNo = $CNo) and km.CNo = k.CNo AND k.ItemId = mi.ItemId AND km.BillStat = 0 AND k.Stat = 3 GROUP BY k.OrdNo,k.ItemId, k.Itm_Portion, k.TA, k.ItemTyp, k.CustItemDesc, k.ItmRate, k.PckCharge,ip.Name order BY mi.ItemNm1")->result_array();

            //     if (empty($kitcheData)) {
            //         $response = [
            //             "status" => 0,
            //             "msg" => "No Categary is available"
            //         ];
            //     } else {
            //         $response = [
            //             "status" => 1,
            //             "kitcheData" => $kitcheData
            //         ];
            //     }

            //     echo json_encode($response);
            //     die();
            // }
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

                        $mi = $this->db2->query("SELECT `m`.`ItemNm1`,`m`.`ItemTyp`, `m`.`KitCd`, `mr`.`ItmRate` as `origRate`
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

        $kitchenData = $this->db2->select('MCNo, MergeNo')->order_by('CNo', 'DESC')->get_where('KitchenMain', array('EID' => $data['EID'], 'CustId' => $data['CustId'], 'CNo' => $CNo))->row_array();

        $data['MergeNo'] = $kitchenData['MergeNo'];

        $bilchk = billCheck($CNo);
        if(!empty($bilchk)){
            redirect(base_url('customer/pay/'.$bilchk['BillId'].'/'.$bilchk['CNo']));
        }else{
            $stat = ($data['EType'] == 5)?3:2;
            $valCheck = checkCheckout($data['CustId'], $CNo, $stat);
            if(empty($valCheck)){
                // after alert
                redirect(base_url('customer/cart'));
            }
        }

        $data['title'] = $this->lang->line('checkout');
        $this->load->view('cust/checkout', $data);
    }

    public function go_checkout(){
        $CustId = $this->session->userdata('CustId');
        $CNo = $this->session->userdata('CNo');
        $EID = authuser()->EID;
        $TableNo = authuser()->TableNo;

        $orderOption = $_POST['orderOption'];
        $status = 0;
        $resp = 1;
        if($orderOption == 'yes'){
            $status = 3;
            $resp = 2;
        }else if($orderOption == 'no'){
            $status = 7;
            $resp = 2;
        }

        if($resp > 1){
            updateRecord('Kitchen', array('Stat' => $status), array('CustId' => $CustId,'EID' => $EID , 'TableNo' => $TableNo, 'Stat' => 2, 'CNo' => $CNo));
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
        $MergeNo = $this->session->userdata('MergeNo');
        $COrgId = $this->session->userdata('COrgId');
        $CustNo = $this->session->userdata('CustNo');
        $Fest = $this->session->userdata('Fest');
        $CellNo = $this->session->userdata('CellNo');

        if ($CustId != '') {

            if (isset($_POST['getBillAmount']) && $_POST['getBillAmount']) {

                $kitcheData = $this->cust->getBillAmount($EID, $CNo);
                if(!empty($kitcheData)){
                   $res = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo);

                    $response['kitcheData'] = $kitcheData;
                    $response['TaxData'] = $res['taxDataArray'];
                }
                
                $d = $this->db2->select('BillDiscAmt')->get_where('KitchenMain', 
                                    array('CNo' => $CNo))
                                ->result_array();
                $km = 0;
                if(!empty($d)){
                    $km = $d[0];
                }   
                $response['discountDT'] = getDiscount($CellNo);
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
                $TaxData = $this->db2->query("SELECT t.ShortName,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included, sum(k.ItmRate) as ItemAmt, (sum(k.ItmRate)*t.TaxPcent/100) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where k.EID = km.EID and k.CNo = km.CNo and (km.CNo = $CNo or km.MCNo = $CNo) and t.TaxType = k.TaxType and k.TaxType = $value group by t.ShortName,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.Rank")->result_array();   // and CurDate() between FrmDt and EndDt

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
            $data['BillName'] = $billData[0]['BillerName'];
            $data['Fullname'] = getName($billData[0]['CustId']);
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
            $data['title'] = 'Bills';
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
        $MCNo = $_POST['MCNo'];
        $MergeNo = $this->session->userdata('MergeNo');
        $MergeNo = $this->session->userdata('MergeNo');

        if($this->session->userdata('AutoSettle') > 0){
            autoSettlePayment($billId, $MergeNo, $MCNo);
        }else{
            $this->db2->query("UPDATE KitchenMain km, Billing b SET km.custPymt = 1, km.payRest = 1 WHERE b.BillId = $billId and km.EID=b.EID and km.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo) and km.MergeNo = b.MergeNo");
        }

        // $this->session->set_userdata('CNo', 0);
    }

    public function merge_order($MergeNo){
        
        $data['title'] = $this->lang->line('mergeorder');
        $data['orders'] = $this->cust->getOrderDetailsByTableNo($MergeNo);
        // echo "<pre>";
        // print_r($data);
        // die;
        $data['MergeNo'] = $MergeNo;
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

            $langId = $this->session->userdata('site_lang');
            $lname = "m.ItemNm$langId as ItemNm";

            $res = $this->db2->select("m.ItemId, $lname, k.Qty, k.ItmRate, k.CellNo")
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
        $CNo = $this->session->userdata('CNo');

        if($this->input->method(true)=='POST'){
            
            $AllCNo = explode(",",$_POST['AllCNo']);
            $cl = implode(",", $_POST['CNo']);
            $in_CNo = '('.$cl.')';

            $MergeNo = $_POST['MergeNo'];

            $langId = $this->session->userdata('site_lang');
            $lname = "m.ItemNm$langId";
            $ipName = "ip.Name$langId as Portions";

            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate,k.ItmRate,  SUM(if (k.TA=1,((k.OrigRate)*k.Qty),(k.OrigRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where k1.MergeNo = km.MergeNo and k1.MergeNo = $MergeNo  and k1.EID=km.EID AND (k1.Stat = 3) and k1.BillStat = km.BillStat GROUP BY k1.EID) as TotPckCharge, $ipName, km.CNo,km.MergeNo, km.MCNo,sum(km.BillDiscAmt) as totBillDiscAmt, sum(km.DelCharge) as totDelCharge, sum(km.RtngDiscAmt) as totRtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType, k.TA,  c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND km.MergeNo = $MergeNo group by km.CNo, k.TA,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.ItemNm1 Asc")->result_array();
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
                // (km.CNo=$CNo or km.MCNo =$CNo)
                $taxName = "t.ShortName$langId as ShortName";
                foreach ($tax_type_array as $key => $value) {
                    $q = "SELECT $taxName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.ItmRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.ItmRate*k.Qty)) - ((sum(k.ItmRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.ItmRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat = 3) and k.EID=km.EID and k.CNo=km.CNo and km.MergeNo = $MergeNo and t.TaxType = k.TaxType and t.TaxType = $value AND km.BillStat = 0 and k.BillStat = 0 group by t.ShortName1, t.TNo,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.rank";
                    
                    $TaxData = $this->db2->query($q)->result_array();
                    $taxDataArray[$value] = $TaxData;
                }

            }

            $response['kitcheData'] = $kitcheData;
            $response['TaxData'] = $taxDataArray;

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
        $EType = $this->session->userdata('EType');
        $stat = ($EType == 5)?3:2;
        for ($i=0; $i < sizeof($_POST['mobile']) ; $i++) { 
            $ccno = $_POST['CNo'][$i];
            updateRecord('KitchenMain', array('MCNo' => $CNo), array('CNo' => $ccno, 'EID' => $EID));
            updateRecord('Kitchen', array('MCNo' => $CNo), array('CNo' => $ccno, 'EID' => $EID, 'Stat' => $stat));
        }
        $data['mobile'] = $this->session->userdata('split_mobile');
        $data['payable'] = $_POST['payable'];
        $data['grossItemAmt'] = $_POST['totalAmt'];
        $data['tip'] = $_POST['tip'];
        $data['MCNo'] = $CNo;
        $data['tot_sgst'] = $_POST['tot_sgst'];
        $data['cust_id'] = $_POST['cust_id'];
        
        $data['title'] = $this->lang->line('splitbill');
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
            $res = billCreate($EID, $CNo, $pData);
            if($res['status'] == 1){
                $BillId = $res['billId'];
                $this->session->set_userdata('BillId', $BillId);
                redirect(base_url('customer/pay/'.$BillId.'/'.$data['MCNo']));
            }else{
                $this->session->set_flashdata('error','Bill Not Generated.');
                redirect(base_url('customer/splitOrder/'.$MergeNo));
            }

        }
    }

    public function splitBill(){
        // echo "<pre>";
        // print_r($_POST);
        // die;
        $CNo = $_POST['MCNo'];
        $MergeNo = $_POST['MergeNo'];
        $EID = authuser()->EID;
        $res = array();
        $CellNo = $this->session->userdata('CellNo');
        $EType = $this->session->userdata('EType');
        $pMCNo = 0;
        $pBillId = 0;
        $linkData = array();
        for ($i=0; $i < sizeof($_POST['mobile']);  $i++) { 
            $pData['paymentMode'] = 'Due';
            $pData['CellNo'] = $_POST['mobile'][$i];
            $pData['CustId'] = $_POST['custid'][$i];
            $pData['CNo'] = $_POST['MCNo'];
            $pData['TotalGross'] = unicodeToEnglish($_POST['totItemAmt'][$i]);
            $pData['orderAmount'] = unicodeToEnglish($_POST['amount'][$i]);
            $pData['per_cent'] = unicodeToEnglish($_POST['percent'][$i]) / 100;
            $pData['TipAmount'] = $_POST['tipAmount'];
            $pData['splitType'] = $_POST['splitType'];
            $pData['MergeNo'] = $_POST['MergeNo'];
            $pData['tot_sgst'] = $_POST['tot_sgst'];

            $pData['itemTotalGross'] = round($pData['TotalGross'] + ($pData['TotalGross'] * $pData['tot_sgst']) / 100);
            // echo "<pre>";
            // print_r($pData);
            // die;

            $discountDT = getDiscount($pData['CellNo']);
            if(!empty($discountDT)){
                // $gt = $totalAmount / (100 - $discountDT['pcent']) * 100;
                $pData['orderAmount'] = $pData['orderAmount'] - ($pData['orderAmount'] * $discountDT['pcent'])/100;
            }

            // $res = array('status' => 1, 'billId' => 2);
            $res = billCreate($EID, $CNo, $pData);
            if($res['status'] == 1){
                $billId = $res['billId'];
                $my_db = $this->session->userdata('my_db');
                $url = $EID . "_b_" . $billId . "_" .$my_db. "_" . $CNo. "_" . $pData['CellNo']. "_" . $pData['MergeNo']. "_" . $pData['orderAmount'];

                $url = base64_encode($url);
                $url = rtrim($url, "=");
                $link = base_url('users?eatout='.$url);

                $temp['mobileNo'] = $pData['CellNo'];
                $temp['link'] = $link;
                $temp['billId'] = $billId;
                $temp['EID'] = $EID;
                $temp['created_by'] = $CellNo;
                $temp['MCNo'] = $CNo;
                $linkData[] = $temp;
                $this->session->set_userdata('blink', $link);
            // link send with bill no, sms or email => pending status
                // for send to pay now to current customer
                if($CellNo == $pData['CellNo']){
                    $pMCNo = $pData['CNo'];
                    $pBillId = $billId;
                }
            }
        }

        $kstat = ($EType == 5)?3:2;
        $billingObjStat = 1;
        $strMergeNo = "'".$MergeNo."'";

        $this->db2->query("UPDATE Kitchen SET BillStat = $billingObjStat  WHERE EID = $EID and (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 and Stat = $kstat ");

       $this->db2->query("UPDATE KitchenMain SET BillStat = $billingObjStat WHERE (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 AND EID = $EID ");

        // echo "<pre>";
        // print_r($linkData);
        // die;
        $this->session->set_userdata('billSplit', 2);
        $this->db2->insert_batch('BillingLinks', $linkData);

        if(!empty($res)){
            if($res['status'] > 0){
                // after bill generate = 1 => status
                if($pBillId > 0 && $pMCNo > 0){
                    redirect(base_url('customer/pay/'.$pBillId.'/'.$pMCNo));
                }else{
                    redirect(base_url('customer/'));
                }
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
        $data['billId'] = $billId;

        $my_db = $this->session->userdata('my_db');

        $url = $EID . "_r_" . $billId . "_" .$my_db;

        $url = base64_encode($url);
        $url = rtrim($url, "=");
        $data['link'] = base_url('users?eatout='.$url);
        $this->session->set_userdata('rlink', $data['link']);
        // print_r($data['link']);die;

        $data['kitchenGetData'] = $this->db2->select('b.BillId,k.ItemId , m.UItmCd, CONCAT(m.ItemNm1, k.CustItemDesc) as ItemNm, k.CustItemDesc')
                                    ->order_by('m.ItemNm1','ASC')
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

                $check = $this->cust->checkUserFromGenDb($_POST['mobile']);
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
        $data['billId'] = $billId;
        $data['shareDetails'] = $this->cust->getShareDetails($billId, $MCNo);
        $this->load->view('cust/share_rating', $data);
    }

    public function transactions(){

        $data['title'] = $this->lang->line('transactions');
        $data['country'] = 91;
        $data['city'] = 0;
        if($this->input->method(true)=='POST'){            
            $data['country'] = $_POST['country'];
            $data['city'] = $_POST['city'];
        }

        $CustId = $this->session->userdata('CustId');
        $genTblDb = $this->load->database('GenTableData', TRUE);
        
        if(!empty($data['country'])){
         $genTblDb->where('ed.CountryCd',$data['country']);
        }
        if(!empty($data['city'])){
         $genTblDb->where('ed.city_id',$data['city']);
        }
        $whr = "cp.BillId = rt.BillId";
        $data['custPymt'] = $genTblDb->select('date(cp.BillDt) as billdt , cp.BillId, cp.BillNo, cp.EID , cp.PaidAmt , cp.CustId , ed.Name , ed.DBName, ed.DBPasswd, rt.avgBillRtng')
                            ->order_by('cp.BillDt', 'DESC')
                            ->join('EIDDet ed', 'ed.EID = cp.EID', 'inner')
                            ->join('Ratings rt', 'rt.EID = cp.EID', 'left')
                            ->where($whr)
                            ->get_where('CustPymts cp', array('cp.CustId' => $CustId))
                            ->result_array();
        $data['countryList'] = $this->cust->getCountryList();
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
        if($this->input->method(true)=='POST'){
            $feedback = $_POST;
            $feedback['EID'] = authuser()->EID;
            $id = insertRecord('Feedback', $feedback);
            if(!empty($id)){
                $this->session->set_flashdata('success','Record Inserted.');
            }
        }

        $this->load->view('contact', $data);
    }

    public function privacy_policy(){
        $data['title'] = $this->lang->line('PrivacyPolicy');
        $this->load->view('privacyPolicy', $data); 
    }

    public function terms_conditions(){
        $data['title'] = $this->lang->line('TermsConditions');
        $this->load->view('terms_conditions', $data); 
    }

    public function cookie_policy(){
        $data['title'] = $this->lang->line('Cookie Policy');
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
            $CellNo = $this->session->userdata('CellNo');
            $MergeNo = $this->session->userdata('MergeNo');
            $res = billCreate($EID, $CNo, $pData);
            
            if(!empty($res)){
                if($res['status'] > 0){
                    $status = 'success';
                    $this->session->set_userdata('BillId', $res['billId']);
                    $dt['BillId'] = $res['billId'];
                    $dt['MCNo'] = $CNo;
                    $response = $dt;

                    $my_db = $this->session->userdata('my_db');
                    $url = $EID . "_b_" . $res['billId'] . "_" .$my_db. "_" . $CNo. "_" . $CellNo. "_" . $MergeNo. "_" . $pData['orderAmount'];

                    $url = base64_encode($url);
                    $url = rtrim($url, "=");
                    $link = base_url('users?eatout='.$url);

                    $linkData['billId'] = $res['billId'];
                    $linkData['mobileNo'] = $CellNo;
                    $linkData['link'] = $link;
                    $linkData['EID'] = $EID;
                    $linkData['created_by'] = $CellNo;
                    $linkData['MCNo'] = $CNo;
                    $this->session->set_userdata('billSplit', 1);
                    $this->db2->insert('BillingLinks', $linkData);
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
        $data["modes"] = $this->cust->getPaymentModes();
        $data['BillId'] = $BillId;
        $data['MCNo'] = $MCNo;
        $bills = getRecords('Billing', array('BillId' => $BillId, 'EID' => authuser()->EID));
        $data['payable'] = $bills['PaidAmt'];
        $data["splitBills"] = $this->cust->getSplitPayments($BillId);
        $data["billLinks"] = $this->cust->getBillLinks($BillId, $MCNo);
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
    
    public function send_payment_otp(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            // echo "<pre>";
            // print_r($_POST);
            // die;

            $mobileNO = $this->session->userdata('CellNo');

            $otp = rand(9999,1000);
            $this->session->set_userdata('payment_otp', $otp);
            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            sendSMS($mobileNO, $msgText);

            $status = "success";
            $response = "OTP send on your mobile no.";
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }
    
    public function settle_bill_without_payment(){
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            // echo "<pre>";
            // print_r($_POST);
            // die;

            $otp = $this->session->userdata('payment_otp');
            $MergeNo = $this->session->userdata('MergeNo');
            $MCNo = $_POST['paymentMCNo'];
            $billId = $_POST['billId'];

            if($_POST['otp'] == $otp){
                $ca['custId']       = $this->session->userdata('CustId');
                $ca['MobileNo']     = $this->session->userdata('CellNo');
                $ca['OTP']          = $otp;
                $ca['billId']       = $billId;
                $ca['billAmount']   = $_POST['billAmount'];
                $ca['EID']          = $EID;
                $ca['mode']         = $_POST['paymentMode'];

                $caId = insertRecord('custAccounts', $ca);
                if(!empty($caId)){

                    $pay = array('BillId' => $billId,'MCNo' => $MCNo,
                                'MergeNo' => $MergeNo,
                                'TotBillAmt' => $ca['billAmount'],
                                'CellNo' => $this->session->userdata('CellNo'),
                                'SplitTyp' => 0 ,'SplitAmt' => 0,'PymtId' => 0,
                                'PaidAmt' => 0 ,'OrderRef' => 0,
                                'PaymtMode'=> $_POST['paymentMode'],'PymtType' => 0,
                                'PymtRef'=>  0, 'Stat'=>  1 ,'EID'=>  $EID,
                                'billRef' => 0);

                    insertRecord('BillPayments', $pay);

                    autoSettlePayment($billId, $MergeNo, $MCNo);
                    updateRecord('Billing', array('Stat' => 25,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));

                    $status = "success";
                    $response = "Bill Settled.";
                }
            }else{
                $response = "OTP Doesn't Matched!!";
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

                    $disRates = $this->db2->query("select mi.ItmRate from MenuItemRates as mi where mi.EID = $EID and mi.ItemId = $Disc_ItemId and mi.Itm_Portion = $Disc_IPCd and mi.SecId = (select et.SecId from Eat_tables et where et.TableNo = $TableNo and et.EID = $EID)")->row_array(); 
                    
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
        
        $this->load->view('cust/profile', $data);
    }

    public function current_order(){
        $data['title'] = $this->lang->line('currentorder');
        $CustId = $this->session->userdata('CustId');
        $CNo = $this->session->userdata('CNo');
        if($CNo == 0){

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

            $billData = $this->db2->select('b.BillId, b.Stat, b.CNo, b.PaidAmt, b.payRest')
                                        ->order_by('b.Billid','DESC')
                                        ->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
                                        ->get_where('Billing b', array('b.EID' => authuser()->EID,
                                            'km.CNo' => $CNo,
                                            'km.CustId' => $CustId,
                                            'b.payRest' => 0,
                                        )
                                                    )->row_array();
            if(!empty($billData)){
                $this->session->set_userdata('payable', $billData['PaidAmt']); 
                $this->session->set_userdata('KOTNo', 0);
                $this->session->set_userdata('CNo', 0);
                $this->session->set_userdata('itemTotalGross', 0);

                        redirect(base_url('customer/pay/'.$billData['BillId'].'/'.$billData['CNo']));
            }else{
                redirect(base_url('customer'));
            }
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

    public function getDiscounts(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $mobile = $this->session->userdata('CellNo');

            $status = 'success';
            $response = getDiscount($mobile);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

}
