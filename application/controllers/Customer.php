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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $status = 'success';
            extract($_POST);
            $langId = ($langId != "") ? $langId : 1;
            $langName = ($langName != "") ? $langName : 'english';
            $this->session->set_userdata('site_lang', $langId);
            $this->session->set_userdata('site_langName', $langName);
            $response = $langId;
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
    }

    public function index1(){

        echo "<pre>";
        print_r($_SESSION);
        die;
        
    }

    public function outlets(){

        $data['title'] = $this->lang->line('multiOutlets');
        $aggEID = $this->session->userdata('aggEID');

        $data['outlets'] = $this->db2->select('EID, Name, Stall, QRLink')
                                    ->order_by('Stall', 'ASC')
                                    ->get_where('Eatary', 
                                        array('CatgId' => 3, 
                                            'Stat' => 0,
                                            'aggEID' => $aggEID)
                                        )
                                    ->result_array();
        $this->load->view('cust/multi_outlets', $data);
    }

    public function gotoOutlet(){

        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $data['ffid'] = 0;
            $this->session->set_userdata('f_fid',0);
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

        $data['title'] = $this->lang->line('itemDetails');
        $data['EType'] = $this->session->userdata('EType');
        $data['Charity'] = $this->session->userdata('Charity');
        $data['Itm_Portion'] = 1;

        $currentTable = $this->cust->getTablesDetails();
        $data['currentTableOffer'] = $currentTable['offerValid'];

        $this->load->view('cust/main', $data);
    }

    public function getFoodTypeList(){
        $this->session->set_userdata('f_fid',0);
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('f_fid',0);
            $status = 'success';
            
            extract($_POST);

            $filter = array();
            $mcat_ctyp = $this->db2->select('MCatgId, CTyp, CID')
             ->get_where('MenuCatg', array('MCatgId' => $mcatId))
             ->row_array();

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $select_sql = "(case when $lname != '-' Then $lname ELSE Name1 end) as LngName, FID, Rank, $lname";
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
            $data['EType'] = $this->session->userdata('EType');
            extract($_POST);

            $MenuItemRates = $this->cust->getMenuItemRates($EID, $itemId, $cid, $MCatgId, $ItemTyp );
            print_r(json_encode($MenuItemRates));
            
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
        }   
    }

    public function searchItemList(){
        $langId = $this->session->userdata('site_lang');
        $EID  = authuser()->EID;
        $tableNo = $this->session->userdata('TableNo');

        if (isset($_POST['searchItemCust']) && $_POST['searchItemCust']) {
            $itemName = $_POST['itemName'];

            $lname = "mi.Name$langId";
            $ItmDesc = "mi.ItmDesc$langId";
            $ingeredients = "mi.Ingeredients$langId";
            $Rmks = "mi.Rmks$langId";
            $ipname = "ip.Name$langId";
           
            $items = $this->db2->query("SELECT mc.TaxType, mc.DCd, mi.KitCd, mc.CTyp, mi.ItemId, mi.ItemTyp, mi.NV, mi.PckCharge, (case when $lname != '-' Then $lname ELSE mi.Name1 end) as ItemNm, (case when $ItmDesc != '-' Then $ItmDesc ELSE mi.ItmDesc1 end) as ItmDesc , (case when $ingeredients != '-' Then $ingeredients ELSE mi.Ingeredients1 end) as Ingeredients, (case when $Rmks != '-' Then $Rmks ELSE mi.Rmks1 end) as Rmks, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as portionName, mir.Itm_Portion, mi.PrepTime, mi.AvgRtng, mi.FID, mi.ItemAttrib, mi.ItemSale, mi.ItemTag, mi.Name1 as imgSrc, mi.UItmCd, mi.CID, mi.MCatgId, mi.videoLink, mir.OrigRate,  et.TblTyp FROM MenuItem mi, MenuCatg mc, ItemPortions ip, MenuItemRates mir, Eat_tables et  where  mi.MCatgId = mc.MCatgId and ip.IPCd = mir.Itm_Portion and mir.ItemId = mi.ItemId and et.SecId = mir.SecId and mir.OrigRate > 0 and et.TableNo = $tableNo AND et.EID = $EID AND mir.EID = $EID AND mir.ItemId = mi.ItemId and mi.Stat = 0 and mi.Name1 like '%$itemName%'  and (DAYOFWEEK(CURDATE()) = mi.DayNo OR mi.DayNo = 8)  AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) AND mc.EID= $EID AND mi.EID=$EID ORDER BY mi.Name1 ASC,mir.OrigRate ASC")->result_array();
            
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
                    "msg" => $this->lang->line('noItemFound')
                ];
            }
            echo json_encode($response);
            die();
        }
    }

    // cart details
    public function cart(){
        $sts = 'error';
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        $EID = authuser()->EID;
        $EType = $this->session->userdata('EType');
        if($this->input->method(true)=='POST'){

            $CustId = $this->session->userdata('CustId');
            $TempCustId = $this->session->userdata('TempCustId');
            $CNo = $this->session->userdata('CNo');
            
            $TableNo = authuser()->TableNo;

            if ($CustId != '') {

                if (isset($_POST['getSendToKitchenList']) && $_POST['getSendToKitchenList']) {

                    $qry = " k.Stat = 1";
                    if($EType == 5){
                        $qry = " (k.Stat = 1 or k.Stat = 2)";
                    }

                    $langId = $this->session->userdata('site_lang');
                    $lname = "mi.Name$langId";
                    $ipName = "ip.Name$langId";

                    $kitcheData = $this->db2->query("SELECT k.OrdNo, k.ItemId, k.Qty, k.TA, k.Itm_Portion, (case when $lname != '-' Then $lname ELSE mi.Name1 end) as ItemNm, k.CustItemDesc,  k.ItmRate as Value, mi.PckCharge, k.OType, k.OrdTime , (case when $ipName != '-' Then $ipName ELSE ip.Name1 end) as Portions, k.Stat, k.SchCd, k.SDetCd, k.ItemTyp, mi.FID, k.tmpOrigRate, k.tmpItmRate from Kitchen k, MenuItem mi,ItemPortions ip where k.Itm_Portion = ip.IPCd and k.CustId = $CustId AND k.EID = $EID AND k.TableNo = $TableNo AND k.ItemId = mi.ItemId AND k.BillStat = 0 AND $qry and k.CNo = $CNo")
                    ->result_array();
                    $recommend = $this->session->userdata('recommend');
                    foreach ($kitcheData as &$key) {
                        $key['recom'] = 0;
                        if($recommend == 1){
                            $key['recom'] = $this->cust->checkRecommendation($key['ItemId']);
                        }
                    }

                    if(empty($kitcheData)){
                        $response = [
                            "status" => 0,
                            "msg" => $this->lang->line('noCategoryAvailable')
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
                    $EType = $this->session->userdata('EType');
                    $stat = ($EType == 5)?2:1;

                    $kd = $this->db2->select('childOrdNo')->get_where('Kitchen', array('OrdNo' => $orderNo, 'CustId' => $CustId, 'EID' => $EID, 'Stat' => $stat, 'BillStat' => 0))->row_array();
                    
                    if(!empty($kd)){
                        if($kd['childOrdNo'] > 0){
                            updateRecord('Kitchen', array('Stat' => 7), array('childOrdNo' => $kd['childOrdNo'],'CustId' => $CustId, 'EID' => $EID));
                        }
                    }

                    $cancelOrder = $this->db2->query("UPDATE Kitchen set Stat = 7 where OrdNo = $orderNo AND CustId = $CustId AND EID = $EID");

                    $response = [
                        "status" => 1,
                        "msg" => $this->lang->line('orderCancelled')
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
                        
                        $this->session->set_userdata('KOTNo', 0);
                        $response = [
                            "status" => 1,
                            "msg" => $this->lang->line('orderSentToKitchen')
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
                
                $response = [
                    "status" => "100",
                    "msg" => $this->lang->line('pleaseRescanQRCode')
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

        $currentTable = $this->cust->getTablesDetails();
        $data['currentTableOffer'] = $currentTable['offerValid'];

        $data['title'] = $this->lang->line('cart');
        $this->load->view('cust/cart', $data);
    }

    public function recommendation(){
        $status = 'error';
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $TableNo = $this->session->userdata('TableNo');

            $status = 'success';
            $itemId = $_POST['itemId'];

            $langId = $this->session->userdata('site_lang');
            $lname = "mi.Name$langId";
            $ItmDesc = "mi.ItmDesc$langId";
            $Ingeredients = "mi.Ingeredients$langId";
            $Rmks = "mi.Rmks$langId";

            $select = "mc.TaxType, mc.KitCd, mi.ItemId, (case when $lname != '-' Then $lname ELSE mi.Name1 end) as ItemNm, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, (case when $ItmDesc != '-' Then $ItmDesc ELSE mi.ItmDesc1 end) as ItmDesc, (case when $ingeredients != '-' Then $ingeredients ELSE mi.Ingeredients1 end) as Ingeredients, (case when $Rmks != '-' Then $Rmks ELSE mi.Rmks1 end) as Rmks, mi.PrepTime, mi.AvgRtng, mi.FID, mi.Name1 as imgSrc, mi.UItmCd,mi.CID ,mi.MCatgId,  (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate,(select mir.Itm_Portion FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as Itm_Portions, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$EID' and et1.TableNo = '$TableNo') as TblTyp";
            $rec = $this->db2->select($select)
                            ->join('MenuItem mi','mi.ItemId = mr.RcItemId', 'inner')
                            ->join('MenuCatg mc', 'mc.MCatgId = mi.MCatgId', 'inner')
                            ->get_where('MenuItem_Recos mr', 
                                        array('mr.ItemId' => $itemId, 
                                            'mr.EID' => $EID, 
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
            $TableNo = $this->session->userdata('TableNo');
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
                        
                        if($TblTyp == 100){
                            // QSR
                            $OType = 100;
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
            
            $response = $this->lang->line('pleaseAddAtleastOneItem');
            if($flag > 0){
                $this->db2->insert_batch('Kitchen', $data);
                $status = 'success';
                $response = $this->lang->line('itemAdded');
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
            $response = $this->lang->line('SomethingSentWrongTryAgainLater');
            if($this->input->method(true)=='POST'){
                
                $emailMobile = $_POST['emailMobile'];
                $emailMobile = $_POST['CountryCd'].$_POST['emailMobile'];
                $check = $this->db2->select('MobileNo')
                                ->where('MobileNo', $emailMobile)
                                ->get('Users')
                                ->row_array();
                if(!empty($check)){
                    // $otp = 1;
                    $otp = generateOTP($emailMobile, 'login');
                    $status = 'success';
                    $response = $this->lang->line('yourOTPIs');
                    $this->session->set_userdata('emailMobile', $emailMobile);
                }else{
                    
                    $genData = $this->cust->checkUserFromGenDb($emailMobile);
                    if(!empty($genData)){
                        $otp = generateOTP($emailMobile, 'login');
                        $status = 'success';
                        $response = $this->lang->line('yourOTPIs');
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
                        $response = $this->lang->line('usernameNotFound');
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
            $data['country']    = $this->cust->getCountries();
            $data['CountryCd']    = $this->session->userdata('CountryCd');
            $this->load->view('cust/login', $data);
        }

    }

    public function loginVerify(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('cust_otp');
            if($_POST['otp'] == $otp){
                $resp['msg'] = $this->lang->line('OTPMatched');
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

                    $res = $this->db2->query("SELECT CNo from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND TableNo = $TableNo AND timediff(Now(),LstModDt) < ('03:00:00') order by CNo desc limit 1")->row_array();
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
                    $this->db2->where_in('Stat', array(0,1,2));
                    $this->db2->update('Kitchen', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId,
                            'TableNo' => $TableNo,
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );

                    $this->db2->where_in('Stat', array(0,1,2));
                    $this->db2->update('KitchenMain', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId,
                            'TableNo' => $TableNo,
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );
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
                $response = $this->lang->line('OTPDoesNotMatch');
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
            $response = $this->lang->line('SomethingSentWrongTryAgainLater');
            if($this->input->method(true)=='POST'){
                $this->session->set_userdata('signup', $_POST);

                $checkUser = $this->db2->select('MobileNo')
                                ->where('MobileNo', $_POST['MobileNo'])
                                ->get('Users')
                                ->row_array();
                if(!empty($checkUser)){
                    $response = $this->lang->line('userAlreadyExists');
                }else{
                    $otp = generateOTP($_POST['MobileNo'], 'signup');
                    $status = 'success';
                    $response = $this->lang->line('yourOTPIs');
                }

                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
                 die;
            }

            $data['title']      = $this->lang->line('signup');
            $data['country']    = $this->cust->getCountries();
            $data['CountryCd']    = $this->session->userdata('CountryCd');
            $this->load->view('cust/signup', $data);
        }
    }

    public function verifyOTP(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('cust_otp');
            if($_POST['otp'] == $otp){
                $res = $this->lang->line('OTPMatched');
                $status = 'success';
                $ses_data = $_SESSION['signup'];
                $this->session->set_userdata('CellNo', $ses_data['MobileNo']);

                $EType = $this->session->userdata('EType');
                $EID = authuser()->EID;
                $TableNo = authuser()->TableNo;
                $CustId = 0;

                $contact_no = $ses_data['CountryCd'].$ses_data['MobileNo'];
                $gen_check = $this->cust->checkUserFromGenDb($contact_no);

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
                    $data['MobileNo'] = $gen_check['CountryCd'].$gen_check['MobileNo']; 
                    insertRecord('Users',$data1);    
                }else{
                    $genTblDb = $this->load->database('GenTableData', TRUE);
                    $data = $ses_data;

                    $Adata = $data;
                    $Adata['MobileNo'] = $data['CountryCd'].$data['MobileNo']; 
                    $Adata['EID'] = authuser()->EID;
                    $Adata['page'] = 'signup';
                    $genTblDb->insert('AllUsers', $Adata);
                    $CustId = $genTblDb->insert_id();
                    $this->session->set_userdata('CustId', $CustId);
                    $data['CustId'] = $CustId;
                    $data['Passwd'] = 'eo1234';
                    $data['PWDHash'] = md5($data['Passwd']);
                    $data['MobileNo'] = $data['CountryCd'].$data['MobileNo']; 
                    insertRecord('Users',$data);
                }
                
                if(!empty($CustId) && $CustId > 0){
                    $res = $this->db2->query("SELECT CNo from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND TableNo = $TableNo AND timediff(Now(),LstModDt) < ('03:00:00') order by CNo desc limit 1")->row_array();
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

                    updateRecord('KitchenMain', array('Stat' => 99), array('EID' => $EID,
                            'CustId' => $CustId, 
                            'BillStat' => 0, 
                            'LstModDt <' => $hours_3
                            )
                        );
                }

            }else{
                $res = $this->lang->line('OTPDoesNotMatch');
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $status = "success";
            $res = $this->lang->line('OTPNotSent');

            extract($_POST);
            if($mobile){
                $otp  = generateOTP($mobile, $page);
                $res = $this->lang->line('resendOTP');
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){

            $mobileNO = $this->session->userdata('CellNo');

            $otp = rand(9999,1000);
            $this->session->set_userdata('payment_otp', $otp);
            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            sendSMS($mobileNO, $msgText);
            saveOTP($mobileNO, $otp, 'payNow');

            $status = "success";
            $res = $this->lang->line('resendOTP');

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }   
    }

    public function order_details_ajax(){
        $statuss = 'error';
        $resp1 = $this->lang->line('SomethingSentWrongTryAgainLater');

        $CustId = $this->session->userdata('CustId');
        $TempCustId = $this->session->userdata('TempCustId');
        $CellNo = $this->session->userdata('CellNo');
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');
        $KOTNo = $this->session->userdata('KOTNo');
        $CNo = $this->session->userdata('CNo');
        $TableNo = authuser()->TableNo;
        $stat = ($EType == 5)?3:2;

        if ($CustId != '') {
            $statuss = 'success';
            $resp1 = '';

            if (isset($_POST['goBill']) && $_POST['goBill']) {

                $SchType = $this->session->userdata('SchType');

                if(in_array($SchType, array(1,3))){
                    $this->cust->updateBillDiscountAmount($CNo);
                }else{
                    $kd = $this->db2->select("sum((OrigRate - ItmRate) * Qty) as ItmDiscount")->get_where('Kitchen', array('CNo' => $CNo, 'EID' => $EID, 'Stat' => $stat, 'BillStat' => 0))->row_array();

                    updateRecord('KitchenMain', array('BillDiscAmt' => $kd['ItmDiscount']), array('CNo' => $CNo, 'EID' => $EID));
                }

                $i=0;
                foreach ($_POST['OrdNo'] as $OrdNo ) {
                    $temp['OrdNo'] = $OrdNo;
                    $temp['qty'] = $_POST['qty'][$i];
                    // $data[] = $temp;
                    updateRecord('Kitchen', array('Qty' => $temp['qty'],'Stat' => $stat), array('OrdNo' => $OrdNo, 'EID' => $EID));
                    $i++;
                }
                
                $resp1 = '';
                $statuss = 2;
                $this->session->set_userdata('KOTNo', 0);

                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => $statuss,
                    'resp' => $resp1
                  ));
                 die;
            }

            if (isset($_POST['billOffer']) && $_POST['billOffer']) {
              
                // bill based offers
                $SchType = $this->session->userdata('SchType');
                if(in_array($SchType, array(1,3))){

                    $chkOfferKitMain = $this->db2->select('SchCd')->get_where('KitchenMain', array('SchCd' => 0, 'CNo' => $CNo, 'EID' => $EID, 'BillStat' => 0))
                                        ->row_array();
                                        
                    if(!empty($chkOfferKitMain)){

                        $orderValue = $this->db2->select('sum(Qty *  OrigRate) as itmValue')
                                                ->get_where('Kitchen', 
                                                            array('CNo' => $CNo, 
                                                                'Stat <= ' => 5,
                                                                'BillStat' => 0,'EID' => $EID)
                                                            )->row_array();
                        $langId = $this->session->userdata('site_lang');
                        $scName = "c.SchNm$langId";

                        $billOfferAmt = $this->db2->select("(case when $scName != '-' Then $scName ELSE c.SchNm1 end) as SchNm, c.offerType, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.DiscItemPcent, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId, cod.IPCd, cod.Disc_CID, cod.Disc_MCatgId, cod.Disc_ItemId, cod.Disc_ItemTyp, cod.Disc_IPCd, if(cod.Disc_ItemId > 0,(select Name1 from MenuItem where ItemId = cod.Disc_ItemId),'-') as itemName, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent ")
                                    ->order_by('cod.MinBillAmt', 'DESC')
                                    ->join('CustOffers c', 'c.SchCd = cod.SchCd', 'inner')
                                    ->get_where('CustOffersDet cod', 
                                     array('cod.MinBillAmt < ' => $orderValue['itmValue'],
                                        'c.SchTyp' => 1,
                                        'c.EID' => $EID,
                                        'c.Stat' => 0))
                                    ->result_array();

                        if(!empty($billOfferAmt)){
                            $resp1 = $billOfferAmt;
                        }
                    }
                }

                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => $statuss,
                    'resp' => $resp1
                  ));
                 die;
            }
        }
    }

    public function billBasedOfferUpdate(){
        $res = '';
        $status = 'error';
        $EID = authuser()->EID;
        
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            $CNo = $this->session->userdata('CNo');

            $SchCd  = $_POST['SchCd'];
            $SDetCd = $_POST['sdetcd'];

            $langId = $this->session->userdata('site_lang');
            $scName = "c.SchNm$langId as SchNm";
            $billOfferAmt = $this->db2->select("(case when $scName != '-' Then $scName ELSE c.SchNm1 end) as SchNm, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.Disc_ItemId, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent, cod.DiscItemPcent")
                        ->order_by('cod.MinBillAmt', 'DESC')
                        ->join('CustOffers c', 'c.SchCd = cod.SchCd')
                        ->get_where('CustOffersDet cod', 
                         array('cod.SchCd' => $SchCd,
                            'cod.SDetCd' => $SDetCd,
                            'c.SchTyp' => 1,
                            'c.EID' => $EID, 
                            'c.Stat' => 0))
                        ->row_array();

                if(!empty($billOfferAmt)){

                    $kitdata = array(
                                 'SchCd' => $billOfferAmt['SchCd'],
                                 'SDetCd' => $billOfferAmt['SDetCd'],
                                 'BillDiscAmt' => $billOfferAmt['Disc_Amt'],
                                 'BillDiscPcent' => $billOfferAmt['Disc_pcent']
                                 );

                    $whr = "CNo = $CNo and EID = $EID and BillStat = 0";
                    $this->db2->where($whr);
                    $this->db2->update('KitchenMain', $kitdata);   
                                    
                     if($billOfferAmt['Disc_ItemId'] > 0 || ($_POST['ItemId'] > 0 ) ){
                        $whre = "CNo = $CNo";
                        $kitcheData = $this->db2->order_by('OrdNo', 'ASC')
                                                ->where($whre)
                                                ->get_where('Kitchen', array('EID' => $EID, 'BillStat' => 0))
                                                ->row_array();
                        $Disc_ItemId = $billOfferAmt['Disc_ItemId'];
                        $Disc_IPCd   = $billOfferAmt['Disc_IPCd'];
                        $Disc_Qty    = $billOfferAmt['Disc_Qty'];
                        if($_POST['ItemId'] > 0){
                            $Disc_ItemId = $_POST['ItemId'];
                            $Disc_IPCd   = $_POST['ipcd'];
                            $Disc_Qty    = ($Disc_Qty > 1)?$Disc_Qty:1;
                        }
                        $ChainId     = $kitcheData['ChainId'];
                        $TableNo     = $kitcheData['TableNo'];

                        $offerRates = $this->db2->query("select mir.Itm_Portion, mir.OrigRate, mc.TaxType, mi.PckCharge, mi.ItemTyp, mi.KitCd, mc.MCatgId from MenuItemRates as mir, MenuItem mi, MenuCatg mc where mi.EID = mir.EID and mc.EID=mir.EID and mi.ItemId = mir.ItemId and mc.MCatgId = mi.MCatgId and mir.EID = $EID and mir.ItemId = $Disc_ItemId and mir.Itm_Portion = $Disc_IPCd and mir.SecId = (select SecId from Eat_tables where TableNo = $TableNo and EID = $EID)")->row_array();
                        if(!empty($offerRates)){
                            $offerRate = $offerRates['OrigRate'] -  ($offerRates['OrigRate'] * $billOfferAmt['DiscItemPcent'] / 100);
                            $offerOrigRate = $offerRates['OrigRate'];

                            $kitchenObj['KitCd']        = $offerRates['KitCd'];
                            $kitchenObj['ItemTyp']      = $offerRates['ItemTyp'];
                            $kitchenObj['Itm_Portion']  = $offerRates['Itm_Portion'];
                            $kitchenObj['TaxType']      = $offerRates['TaxType'];

                            $kitchenObj['TA'] = 0;
                            $kitchenObj['PckCharge']    = 0;
                            $kitchenObj['OType'] = $kitcheData['OType'];
                            if($kitchenObj['OType'] == 105){
                                $kitchenObj['TA'] = 1;
                                $kitchenObj['PckCharge']    = $offerRates['PckCharge'];
                            }
   
                            $newUKOTNO = date('dmy_') . $kitchenObj['KitCd'] . "_" . $kitcheData['KOTNo'] . "_" . $kitcheData['FKOTNo'];

                            $kitchenObj['CNo']    = $kitcheData['CNo'];
                            $kitchenObj['MCNo']   = $kitcheData['MCNo'];
                            $kitchenObj['CustId'] = $kitcheData['CustId'];
                            $kitchenObj['COrgId'] = $kitcheData['COrgId'];
                            $kitchenObj['CustNo'] = $kitcheData['CustNo'];
                            $kitchenObj['CellNo'] = $kitcheData['CellNo'];
                            $kitchenObj['EID']    = $kitcheData['EID'];
                            $kitchenObj['ChainId']= $kitcheData['ChainId'];
                            $kitchenObj['ONo']    = $kitcheData['ONo'];
                            $kitchenObj['FKOTNo'] = $kitcheData['FKOTNo'];
                            $kitchenObj['KOTNo']  = $kitcheData['KOTNo'];
                            $kitchenObj['UKOTNo'] = $newUKOTNO;         
                            $kitchenObj['TableNo']= $kitcheData['TableNo'];
                            $kitchenObj['MergeNo']= $kitcheData['MergeNo'];
                            $kitchenObj['CustRmks']= 'bill based offer';
                            $kitchenObj['DelTime']= date('Y-m-d H:i:s');
                            $kitchenObj['Stat']   = $kitcheData['Stat'];
                            $kitchenObj['LoginCd']= $kitcheData['CustId'];

                            $kitchenObj['ItemId']       = $Disc_ItemId;
                            $kitchenObj['Itm_Portion']  = $Disc_IPCd;
                            $kitchenObj['Qty']          = $Disc_Qty;
                            $kitchenObj['ItmRate']      = $offerRate;
                            $kitchenObj['OrigRate']     = $offerOrigRate;
                                
                            $kitchenObj['SchCd']  = $billOfferAmt['SchCd'];
                            $kitchenObj['SDetCd'] = $billOfferAmt['SDetCd'];
                            
                            insertRecord('Kitchen', $kitchenObj);   
                        }

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

        $data['EID'] = authuser()->EID;
        $data['ChainId'] = authuser()->ChainId;
        $data['TableNo'] = authuser()->TableNo;
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
        $data['mergeCount'] = 0;
        $CNo = $this->session->userdata('CNo');
        $EID = $data['EID'];
        $data['MergeNo'] = $this->session->userdata('MergeNo');

        $kitchenData = $this->db2->select('MCNo, MergeNo')->order_by('CNo', 'DESC')->get_where('KitchenMain', array('EID' => $data['EID'], 'CustId' => $data['CustId'], 'CNo' => $CNo))->row_array();
        if(!empty($kitchenData)){
            $data['MergeNo'] = $kitchenData['MergeNo'];
            $MergeNo = $kitchenData['MergeNo'];
            
            $mergeCount = $this->db2->query("SELECT count(kk.CNo) as count FROM KitchenMain kk WHERE kk.MergeNo='$MergeNo' and kk.EID=$EID and kk.BillStat=0 and kk.MCNo=(SELECT k1.MCNo from KitchenMain k1 where k1.CNo = $CNo and k1.EID=$EID and k1.MergeNo='$MergeNo' and k1.BillStat=0 group by k1.CNo)")->row_array();
            $data['mergeCount'] = $mergeCount['count'];
        }

        $bilchk = billCheck($CNo);
        if(!empty($bilchk)){
            redirect(base_url('customer/pay/'.$bilchk['BillId'].'/'.$bilchk['CNo']));
        }else{
            $stat = ($data['EType'] == 5)?3:2;
            $valCheck = checkCheckout($data['CustId'], $CNo, $stat);
            if(empty($valCheck)){
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
        $TableNo = $this->session->userdata('TableNo');

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
        $CustId = $this->session->userdata('CustId');
        $ONo = $this->session->userdata('ONo');
        $EType = $this->session->userdata('EType');
        $CNo = $this->session->userdata('CNo');
        $TableNo = authuser()->TableNo;
        $MergeNo = $this->session->userdata('MergeNo');

        if ($CustId != '') {

            if (isset($_POST['getBillAmount']) && $_POST['getBillAmount']) {
                $response['status'] = 'error';
                $kitcheData = $this->cust->getBillAmount($EID, $CNo);
                if(!empty($kitcheData)){
                   $per_cent = 1;
                   $res = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);

                    $response['kitcheData'] = $kitcheData;
                    $response['TaxData'] = $res['taxDataArray'];

                    $d = $this->db2->select('BillDiscAmt')->get_where('KitchenMain', 
                                        array('CNo' => $CNo))
                                    ->result_array();
                    $km = 0;
                    if(!empty($d)){
                        $km = $d[0];
                    }   
                    $response['discountDT'] = array();
                    if($this->session->userdata('Discount') > 0){
                        $response['discountDT'] = getDiscount($CustId);
                    }
                    $response['kitchen_main_data'] = $km;
                    $response['status'] = 'success';
                }
                
                echo json_encode($response);
                die();
                
            }

        } else {
            $response = [
                "status" => "100",
                "msg" => $this->lang->line('pleaseRescanQRCode')
            ];

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

        $dbname = $this->session->userdata('my_db');

        if(isset($_GET['dbn'])){
            $dbname = $_GET['dbn'];
            $EID = $_GET['EID'];
        }
        $flag = 'cust';
        $res = getBillData($dbname, $EID, $billId, $CustId, $flag);

        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];

            $data['hotelName'] = $billData[0]['Name'];
            $data['BillName'] = $billData[0]['BillerName'];
            $data['Fullname'] = getName($billData[0]['CustId']);
            $data['phone'] = $billData[0]['PhoneNos'];
            $data['CellNo'] = $billData[0]['CellNo'];
            $data['TableNo']   = $billData[0]['TableNo'];
            $data['MergeNo']   = $billData[0]['MergeNo'];
            $data['gstno'] = $billData[0]['GSTno'];
            $data['fssaino'] = $billData[0]['FSSAINo'];
            $data['cinno'] = $billData[0]['CINNo'];
            $data['billno'] = $billData[0]['BillNo'];
            $data['dateOfBill'] = date('d-M-Y @ H:i', strtotime($billData[0]['BillDt']));
            $data['address'] = $billData[0]['Addr'];
            $data['pincode'] = $billData[0]['Pincode'];
            $data['city'] = $billData[0]['City'];
            $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
            $data['bservecharge'] = $billData[0]['bservecharge'];
            $data['SerChargeAmt'] = $billData[0]['SerChargeAmt'];

            $data['tipamt'] = $billData[0]['Tip'];
            $data['splitTyp'] = $billData[0]['splitTyp'];
            
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
            $data['checkLoyalty'] = 0;
            $checkLoyalty = $this->cust->checkLoyaltyPoints($billId);
            if(!empty($checkLoyalty) && $checkLoyalty['Points'] > 0){
                $data['checkLoyalty'] = 1;
            }
            
            $this->load->view('cust/billing', $data);
        }else{
            $data['title'] = $this->lang->line('billing');
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
        
        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];

            $data['hotelName'] = $billData[0]['Name'];
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

            $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'];
            $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
            $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

            $data['billData'] = $res['billData'];
        }
        $this->load->view('print', $data);
    }

    public function updateCustPayment(){
        $EID  = authuser()->EID;
        $billId = $_POST['BillId'];
        $MCNo = $_POST['MCNo'];
        $MergeNo = $this->session->userdata('MergeNo');

        if($this->session->userdata('AutoSettle') > 0){
            autoSettlePayment($billId, $MergeNo, $MCNo);
        }else{
            $this->db2->query("UPDATE KitchenMain km, Billing b SET km.custPymt = 1, km.payRest = 1 WHERE b.BillId = $billId and km.EID=b.EID and km.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo) and km.MergeNo = b.MergeNo");
        }

        if($this->session->userdata('splitType')==1){
            $bd = $this->db2->select("BillId")
                                ->get_where('Billing', array('EID' =>$EID, 'CNo' => $MCNo, 'splitTyp' => 1, 'payRest' => 0))->row_array();
            $billId = $bd['BillId'];
            autoSettlePayment($billId, $MergeNo, $MCNo);
        }
        $this->session->set_userdata('splitType', 0);
    }

    public function merge_order($MergeNo){
        
        $data['title'] = $this->lang->line('mergeorder');
        $data['orders'] = $this->cust->getOrderDetailsByTableNo($MergeNo);
        $data['MergeNo'] = $MergeNo;
        $data['Tips'] = 1;
        $data['EType'] = $this->session->userdata('EType');
        $data['Cash'] = $this->session->userdata('Cash');
        $data['CustId'] = $this->session->userdata('CustId');

        $EID = authuser()->EID;
        $CNo = $this->session->userdata('CNo');
        $EType = $this->session->userdata('EType');
        $stat = ($EType == 5)?3:2;

        if(sizeof($data['orders']) <= 1){
            $this->session->set_flashdata('error','No Orders to Merge from this table.'); 
            redirect(base_url('customer/checkout'));
        }else{
            foreach ($data['orders'] as $key) {
                updateRecord('KitchenMain', array('MCNo' => $CNo), array('CNo' => $key['CNo'], 'EID' => $EID));
                updateRecord('Kitchen', array('MCNo' => $CNo), array('CNo' => $key['CNo'], 'EID' => $EID, 'Stat' => $stat));
            }
        }

        $this->load->view('cust/mergeOrder', $data);
    }

    public function get_merge_order(){
        $status = 'error';
        $res = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $strTableNo = authuser()->TableNo;
            $TableNo = $this->session->userdata('TableNo');
            $CNo = $_POST['CNo'];

            $langId = $this->session->userdata('site_lang');
            $lname = "m.Name$langId";

            $res = $this->db2->select("m.ItemId, (case when $lname != '-' Then $lname ELSE m.Name1 end) as ItemNm, k.Qty, k.ItmRate, k.CellNo")
                        ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                        ->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
                        ->get_where('KitchenMain km', array('km.TableNo' => $TableNo,'km.CNo' => $CNo))
                        ->result_array();

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
            $lname = "m.Name$langId";
            $ipname = "ip.Name$langId";

            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate,k.ItmRate,  SUM(if (k.TA=1,((k.OrigRate)*k.Qty),(k.OrigRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty) from Kitchen k1 where k1.MergeNo = km.MergeNo and k1.MergeNo = '$MergeNo' and k1.EID=km.EID AND (k1.Stat = 3) and k1.BillStat = km.BillStat GROUP BY k1.EID) as TotPckCharge, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as Portions, km.CNo,km.MergeNo, km.MCNo,sum(km.BillDiscAmt) as totBillDiscAmt, sum(km.DelCharge) as totDelCharge, sum(km.RtngDiscAmt) as totRtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType, k.TA,  c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND km.MergeNo = '$MergeNo' group by km.CNo, k.TA,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.Name1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.Name1 Asc")->result_array();

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
                $taxName = "t.Name$langId as ShortName";
                foreach ($tax_type_array as $key => $value) {
                    $q = "SELECT $taxName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.ItmRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.ItmRate*k.Qty)) - ((sum(k.ItmRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.ItmRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat = 3) and k.EID=km.EID and k.CNo=km.CNo and km.MergeNo = '$MergeNo' and t.TaxType = k.TaxType and t.TaxType = $value AND km.BillStat = 0 and k.BillStat = 0 group by t.Name1, t.TNo,t.TaxPcent, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included order by t.rank";
                    
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

        $EID = authuser()->EID;
        $CNo = $this->session->userdata('CNo');
        $EType = $this->session->userdata('EType');
        $stat = ($EType == 5)?3:2;
        if($this->input->method(true)=='POST'){
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
                $data['country']    = $this->cust->getCountries();
                $data['CountryCd']    = $this->session->userdata('CountryCd');
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
        }else{
            redirect(base_url('customer/merge_order/'.$MergeNo));
        }
    }

    public function splitBill(){
        $status = 'success';

        $EID = authuser()->EID;
        $res = array();
        $CellNo = $this->session->userdata('CellNo');
        $EType = $this->session->userdata('EType');
        $pMCNo = 0;
        $pBillId = 0;
        $linkData = array();
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('splitType', $_POST['splitType']);

            if($_POST['splitType'] == 1){
                $billResp = $this->food_bar_separate_bill($_POST);

                $MergeNo    = $_POST['MergeNo'];
                $CNo        = $_POST['MCNo'];
                $strMergeNo = "'".$MergeNo."'";  

                $this->db2->query("UPDATE Kitchen SET BillStat = 1  WHERE EID = $EID and (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 and Stat = 3 ");

                $this->db2->query("UPDATE KitchenMain SET BillStat = 1 WHERE (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 AND EID = $EID ");

                $status = $billResp['status'];
                $response = $billResp['response'];
            }else{

                $CNo = $_POST['MCNo'];
                $MergeNo = $_POST['MergeNo'];
                for ($i=0; $i < sizeof($_POST['mobile']);  $i++) { 
                    $pData['paymentMode'] = 'Due';
                    $pData['CellNo'] = $_POST['mobile'][$i];
                    $pData['CustId'] = $_POST['custid'][$i];
                    if(empty($pData['CustId'])){
                        $this->session->set_userdata('pCountryCd', $_POST['CountryCd'][$i]);
                        $pData['CustId'] = createCustUser($pData['CellNo']);
                    }
                    $pData['CNo'] = $_POST['MCNo'];
                    $pData['TotalGross'] = unicodeToEnglish($_POST['totItemAmt'][$i]);
                    $pData['orderAmount'] = round(unicodeToEnglish($_POST['amount'][$i]));
                    $pData['per_cent'] = unicodeToEnglish($_POST['percent'][$i]) / 100;
                    $pData['TipAmount'] = $_POST['tipAmount'];
                    $pData['splitType'] = $_POST['splitType'];
                    $pData['MergeNo'] = $_POST['MergeNo'];
                    $pData['tot_sgst'] = $_POST['tot_sgst'];

                    $pData['itemTotalGross'] = $pData['TotalGross'];

                    $pData['CellNo'] = $_POST['CountryCd'][$i].$pData['CellNo'];

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

                $this->session->set_userdata('billSplit', 2);
                $this->db2->insert_batch('BillingLinks', $linkData);

                if(!empty($res)){
                    if($res['status'] > 0){
                        
                        if($pBillId > 0 && $pMCNo > 0){
                            $response = base_url('customer/pay/'.$pBillId.'/'.$pMCNo);
                        }else{
                            $response = base_url('customer/');
                        }
                    }else{
                        $response = base_url('customer/splitOrder/'.$_POST['MergeNo']);
                    }
                }else{
                    $response = base_url('customer/splitOrder/'.$_POST['MergeNo']);
                }
            } // split type
        }else{
            $MergeNo = $this->session->userdata('MergeNo');
            $response = base_url('customer/merge_order/'.$MergeNo);
        }

        header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
    }

    public function rating($billId){

        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
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

        $data['kitchenGetData'] = $this->db2->select("b.BillId,k.ItemId , m.UItmCd, CONCAT(m.Name1,' ', k.CustItemDesc) as ItemNm")
                                    ->order_by('m.Name1','ASC')
                                    ->group_by('k.ItemId,k.MCNo')
                                    ->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
                                    ->join('Kitchen k', 'k.MCNo = km.MCNo', 'inner')
                                    ->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
                                    ->get_where('Billing b', array('b.BillId' => $billId, 'k.Stat' => 3, 'b.EID' => $EID))
                                    ->result_array();

        $this->load->view('cust/rating', $data);
        
    }

    public function genOTPRating(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
                $response = $this->lang->line('OTPDoesNotMatch');
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
        $data['city'] = 0;
        if($this->input->method(true)=='POST'){            
            $data['country'] = $_POST['country'];
            $data['city'] = $_POST['city'];
        }

        $CustId = $this->session->userdata('CustId');
        $genTblDb = $this->load->database('GenTableData', TRUE);

        $whr = "";
        if(!empty($data['country'])){
         // $genTblDb->where('ed.CountryCd',$data['country']);
            $whr .= " and ed.CountryCd = ".$data['country'];
        }
        if(!empty($data['city'])){
         // $genTblDb->where('ed.city_id',$data['city']);
         $whr .= " and ed.city_id = ".$data['city'];
        }
        
        $data['custPymt'] = $genTblDb->query("SELECT DISTINCT cp.BillId, date(cp.BillDt) as billdt, `cp`.`BillNo`, `cp`.`EID`, `cp`.`PaidAmt`, `cp`.`CustId`, `ed`.`Name`, `ed1`.`DBName`, `ed`.`DBPasswd`, `rt`.`avgBillRtng` FROM `CustPymts` `cp` INNER JOIN `EIDDet` `ed` ON `ed`.`EID` = `cp`.`EID` INNER JOIN `EIDDet` `ed1` ON `ed1`.`EID` = `cp`.`aggEID` LEFT JOIN `Ratings` `rt` ON `rt`.`EID` = `cp`.`EID` WHERE `cp`.`CustId` = '6' AND `rt`.`CustId` = '6' $whr ORDER BY `cp`.`BNo` DESC")->result_array();
        // $data['custPymt'] = $genTblDb->select("cp.BillId, date(cp.BillDt) as billdt , cp.BillNo, cp.EID , cp.PaidAmt , cp.CustId , ed.Name , ed1.DBName, ed.DBPasswd, rt.avgBillRtng")
        //                     ->order_by('cp.BNo', 'DESC')
        //                     ->join('EIDDet ed', 'ed.EID = cp.EID', 'inner')
        //                     ->join('EIDDet ed1', 'ed1.EID = cp.aggEID', 'inner')
        //                     ->join('Ratings rt', 'rt.EID = cp.EID', 'left')
        //                     // ->where($whr)
        //                     ->get_where('CustPymts cp', array('cp.CustId' => $CustId, 'rt.CustId' => $CustId))
        //                     ->result_array();
        
        $data['country']    = $this->cust->getCountries();
        $data['CountryCd']    = $this->session->userdata('CountryCd');
        $this->load->view('cust/transactions', $data);
    }

    public function getCityList(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){

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
            $res = $this->lang->line('dataAdded');
           
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

        $data['country']    = $this->cust->getCountries();
        $data['CountryCd']  = $this->session->userdata('CountryCd');

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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');

        if($this->input->method(true)=='POST'){

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
        $EID = authuser()->EID;
        $data["modes"] = $this->cust->getPaymentModes();
        $data['EType'] = $this->session->userdata('EType');
        $data['BillId'] = $BillId;
        $data['EID'] = $EID;
        $CustId = $this->session->userdata('CustId');
        $this->session->userdata('CustId');
        $url = "https://eo.vtrend.org/users/print_api/$CustId/$BillId/$EID";
        file_get_contents_curl($url);
        $data['MCNo'] = $MCNo;
        $data['billAmt'] = 0;
        $bills = getRecords('Billing', array('BillId' => $BillId, 'EID' => $EID));
        if(!empty($bills)){
            $data['payable'] = $bills['PaidAmt'];
            $data['billAmt'] = $bills['PaidAmt'];
            if($this->session->userdata('splitType') == 1){
                $data['payable'] = $this->session->userdata('food_bar_amt');
            }
        }else{
            redirect(base_url('customer/checkout'));    
        }
        $data["splitBills"] = $this->cust->getSplitPayments($BillId);
        $data["pmodes"] = $this->cust->getModesFromBillPayment($BillId);
        $parray = [];
        $data["modes1"] = [];
        if(!empty($data["pmodes"])){
            foreach ($data["pmodes"] as $key) {
                $parray[] = $key['PaymtMode'];
            }

        // for unique modes
            $temp = [];
            foreach ($data["modes"] as $pay ) {
                if($pay['repeatable'] == 0 && in_array($pay['PymtMode'], $parray)){
                    continue;
                }
                $temp[] = $pay;
            }
            $data["modes1"] = $temp;
        }

        $data["billLinks"] = $this->cust->getBillLinks($BillId, $MCNo);

        $this->load->view('cust/pay_now', $data);
    }

    // $this->remove_duplicateKeys("PymtMode",$temp);
    private function remove_duplicateKeys($key,$data){

        $_data = array();

        foreach ($data as $v) {
          if (isset($_data[$v[$key]])) {
            // found duplicate
            continue;
          }
          // remember unique item
          $_data[$v[$key]] = $v;
        }
        // if you need a zero-based array
        // otherwise work with $_data
        $data = array_values($_data);
        return $data;
    }

    public function multi_payment(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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

    public function check_onaccount_cust(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            
            $mode = $_POST['mode'];
            // corporate
            $custType = 3;
            if($mode == 25){
                // onaccount
                $custType = 1;   
            }else if($mode == 26){
                // prepaid
                $custType = 2;   
            }
            
            $mobileNO = $this->session->userdata('CellNo');
            $CustId = $this->session->userdata('CustId');
            $onAccount = checkOnAccountCust($CustId, $custType);
            if(!empty($onAccount)){
                $custAcc = $this->cust->getCustAccount($CustId);
                if(!empty($custAcc)){
                    $total = $custAcc['billAmount'] + $_POST['amount'];
                    
                    if($total <= $onAccount['MaxLimit']){
                        $otp = rand(9999,1000);
                        $this->session->set_userdata('payment_otp', $otp);
                        $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
                        sendSMS($mobileNO, $msgText);
                        saveOTP($mobileNO, $otp, 'payNow');
                        $status = "success";
                        $response = $this->lang->line('OTPSentToYourMobileNo');
                    }else{
                        $response = $this->lang->line('outOfLimit');
                    }
                }else{
                    $total = $_POST['amount'];
                    if($total <= $onAccount['MaxLimit']){
                        $otp = rand(9999,1000);
                        $this->session->set_userdata('payment_otp', $otp);
                        $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
                        sendSMS($mobileNO, $msgText);
                        saveOTP($mobileNO, $otp, 'payNow');
                        $status = "success";
                        $response = $this->lang->line('OTPSentToYourMobileNo');
                    }else{
                        $response = $this->lang->line('outOfLimit');
                    }
                }
            }else{
                $response = $this->lang->line('accountNotCreated');
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_prepaid_details(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $CustId = $this->session->userdata('CustId');
            $status = 'success';
            $response = $this->cust->getPrepaidDetails($CustId, $_POST['pMode']);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function check_prepaid_cust(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            
            $mode = $_POST['mode'];
            // corporate
            $custType = 3;
            if($mode == 26){
                // prepaid
                $custType = 2;   
            }
            
            $mobileNO = $this->session->userdata('CellNo');
            $CustId = $this->session->userdata('CustId');
            $onAccount = checkOnAccountCust($CustId, $custType);

            if(!empty($onAccount)){
                $custAcc = $this->cust->getPrepaidDetails($CustId, $mode);
                
                if(!empty($custAcc)){
                    
                    $total = $custAcc['balance'] + $_POST['amount'];
                    
                    if($total <= $onAccount['MaxLimit']){

                        $balance = $custAcc['prePaidAmt'] - $custAcc['PaidAmt'];
                        
                        if($balance >= $_POST['amount']){
                            
                            $otp = rand(9999,1000);
                            $this->session->set_userdata('payment_otp', $otp);
                            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
                            sendSMS($mobileNO, $msgText);
                            saveOTP($mobileNO, $otp, 'payNow');
                            $status = "success";
                            $response = $this->lang->line('OTPSentToYourMobileNo');
                        }else{
                            $response = $this->lang->line('insufficentBalance');    
                        }
                    }else{
                        $response = $this->lang->line('outOfLimit');
                    }
                }else{
                    $total = $_POST['amount'];
                    if($total <= $onAccount['MaxLimit']){
                        if($custAcc['prePaidAmt'] >= $_POST['amount']){
                            $otp = rand(9999,1000);
                            $this->session->set_userdata('payment_otp', $otp);
                            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
                            sendSMS($mobileNO, $msgText);
                            saveOTP($mobileNO, $otp, 'payNow');
                            $status = "success";
                            $response = $this->lang->line('OTPSentToYourMobileNo');
                        }
                    }else{
                        $response = $this->lang->line('outOfLimit');
                    }
                }
            }else{
                $response = $this->lang->line('accountNotCreated');
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){

            $mobileNO = $this->session->userdata('CellNo');

            $otp = rand(9999,1000);
            $this->session->set_userdata('payment_otp', $otp);
            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            sendSMS($mobileNO, $msgText);
            saveOTP($mobileNO, $otp, 'payNow');

            $status = "success";
            $response = $this->lang->line('OTPSentToYourMobileNo');
            
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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){

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
                    updateRecord('Billing', array('Stat' => $_POST['paymentMode']), array('BillId' => $billId, 'EID' => $EID));

                    $status = "success";
                    $response = $this->lang->line('billSettled');
                }
            }else{
                $response = $this->lang->line('OTPDoesNotMatch');
            }

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
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $status = "success";
            $itemId = $_POST['itemId'];
            $itemTyp = $_POST['itemTyp'];
            $itemPortionCode = $_POST['itemPortionCode'];
            $FID = $_POST['FID'];
            
            $returnData = [];
            $customDetails = $this->cust->getCustomDetails($itemTyp, $itemId, $itemPortionCode, $FID);
            if(!empty($customDetails)){

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
                // unique id logic
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
            }
            else{
                $response =  $returnData;
                // $response =  'No customization available!!';  
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function kotGenerate(){
        $CustId = $this->session->userdata('CustId');
        $EID = authuser()->EID;
        $CNo = $this->session->userdata('CNo');
        $TableNo = $this->session->userdata('TableNo');

        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
                'response' => $this->lang->line('orderSentToKitchen')
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
                                        ->get_where('Billing', array('CustId' => $CustId
                                            ,
                                            'payRest' => 0,
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

    public function getDiscounts(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $mobile = $this->session->userdata('CellNo');
            $CustId = $this->session->userdata('CustId');

            $status = 'success';
            $response = array();
            if($this->session->userdata('Discount') > 0){
                $response = getDiscount($CustId);
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }
    
    public function get_loyality(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            $response = $this->cust->getLoyalityList($_POST['billId']);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }
    
    public function get_onaccount_details(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $CustId = $this->session->userdata('CustId');
            $status = 'success';
            $response = $this->cust->getOnAccountDetails($CustId, $_POST['pMode']);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function update_loyalty_point(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $LNo = $_POST['LNo'];
            $totalPoints = $_POST['totalPoints'][$LNo];
            $billId = $_POST['billId'];
            $CustId = $this->session->userdata('CustId');

            $loyalties = array(
                     'LId'          => 0,
                     'LNo'          => $LNo,
                     'custId'       => $CustId,
                     'EID'          => $EID,
                     'billId'       => $billId,
                     'billDate'     => date('Y-m-d H:i:s'),
                     'billAmount'   => $_POST['billAmount'],
                     'MobileNo'     => $this->session->userdata('CellNo'),
                     'OTP'          => 0,
                     'Points'       => $totalPoints,
                     'earned_used'  => 0
                    );
            insertLoyalty($loyalties);
            $genTblDb = $this->load->database('GenTableData', TRUE);
            $genTblDb->insert('Loyalty', $loyalties);

            $status = 'success';
            $response = $this->lang->line('loyaltyPointsAdded');
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_loyalty_points(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $CustId = $this->session->userdata('CustId');

            $status = 'success';
            $response = $this->cust->getLoyaltiPoints($CustId, $_POST['EatOutLoyalty']);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function loyalty_pay(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
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
            $pay['Stat'] = 1;
            $pay['EID'] = authuser()->EID;

            $payNo = insertRecord('BillPayments', $pay);
            
            $loyalties = array(
                     'LId'          => 0,
                     'LNo'          => $_POST['LNo'],
                     'custId'       => $this->session->userdata('CustId'),
                     'EID'          => $pay['EID'],
                     'billId'       => $pay['BillId'],
                     'billDate'     => date('Y-m-d H:i:s'),
                     'billAmount'   => $_POST['payable'],
                     'MobileNo'     => $this->session->userdata('CellNo'),
                     'OTP'          => 0,
                     'Points'       => $_POST['usedPointsAmt'],
                     'PointsValue'  => $_POST['usedPointsValue'],
                     'earned_used'  => 1
                    );
            insertLoyalty($loyalties);

            $genTblDb = $this->load->database('GenTableData', TRUE);
            $genTblDb->insert('Loyalty', $loyalties);

            if(!empty($payNo)){
                $status = 'success';
                $response = $this->lang->line('loyaltyPayment');
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function loyalty(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){

            $CustId = $this->session->userdata('CustId');
            $status     = 'success';
            $genTblDb = $this->load->database('GenTableData', TRUE);

            $response = $genTblDb->select('l.LNo, l.EID, ed.Name, Sum(Case When l.earned_used = 0 
         Then l.Points Else 0 End) EarnedPoints, Sum(Case When l.earned_used = 1 
         Then l.Points Else 0 End) UsedPoints')
                                ->order_by('l.LNo, ed.Name', 'ASC')
                                ->group_by('l.EID, l.LNo')
                                ->join('EIDDet ed', 'ed.EID = l.EID', 'inner')
                                ->get_where('Loyalty l', array('l.custId' => $CustId))
                                ->result_array();

            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['title'] = $this->lang->line('loyalty');
        $this->load->view('cust/loyalty', $data);
    }

    private function food_bar_separate_bill($postData){

        $dt = [
                "status" => 'error',
                "response" => $this->lang->line('noBillCreation')
            ];

        $payableAmount = $postData['payableAmount'];
        $this->session->set_userdata('food_bar_amt', $payableAmount);
        $MergeNo    = $postData['MergeNo'];
        $CNo        = $postData['MCNo'];
        $per_cent   = 1;
        $EID        = authuser()->EID;

        $paymentMode = 'RestSplitBill';
        $postData['TipAmount'] =0;
        $cust_discount = 0;
        $billingObjStat = 1;
        $CellNo = $postData['mobile'][0];
        $CountryCd = $postData['CountryCd'][0];
        $this->session->set_userdata('pCountryCd', $CountryCd);
        $postData['CellNo'] = $CountryCd.$CellNo;
        $CustId = createCustUser($CellNo);
        $postData['CustId'] = $CustId;
        $EType = $this->session->userdata('EType');
        $ChainId = authuser()->ChainId;

        $CustNo = $this->session->userdata('CustNo');
        $COrgId = $this->session->userdata('COrgId');

        $strMergeNo = "'".$MergeNo."'";
        
        for ($CTyp=1; $CTyp <=2 ; $CTyp++) { 

        $kitcheData = $this->cust->fetchBiliingData_CTyp($EID, $CNo, $MergeNo, $per_cent, $CTyp);
        
        if (empty($kitcheData)) {
                $dt = [
                    "status" => 'error',
                    "response" => $this->lang->line('noBillCreationRequired')
                ];
                
            } else {
                // order amt
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;

                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);
                    $taxDataArray = $TaxRes['taxDataArray'];

                    foreach ($kitcheData as $kit ) {
                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                    }

                    //tax calculate
                    $SubAmtTax = 0;
                    foreach ($taxDataArray as $tax) {
                        foreach ($tax as $key) {
                            if($key['Included'] >= 5){
                                $SubAmtTax = $SubAmtTax + round($key['SubAmtTax'], 2);
                            }
                        }
                    }
                        
                    $orderAmt = $orderAmt + $SubAmtTax;
                    $postData["itemTotalGross"] = $orderAmt;
                    
                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    $charge = $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount;
                    
                    $postData["orderAmount"] = $total;
                    $postData["TableNo"] = $kitcheData[0]['TableNo'];
                    $postData["cust_discount"] = 0;
                    $totalAmount = $postData["orderAmount"];
                // end of order amt

                $res = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);

                $lastBillNo = $this->db2->query("SELECT max(BillNo) as BillNo from Billing where EID = $EID")->row_array();

                if ($lastBillNo['BillNo'] == '') {
                    $newBillNo = 1;
                } else {
                    $newBillNo = $lastBillNo['BillNo'] + 1;
                }

                $TotItemDisc    = $kitcheData[0]['TotItemDisc'];
                $TotPckCharge   = $kitcheData[0]['TotPckCharge'];
                $DelCharge      = $kitcheData[0]['DelCharge'];
                $BillDiscAmt    = $kitcheData[0]['BillDiscAmt'];
                
                $splitTyp = 0; 
                $splitPercent = 1;
                if($paymentMode == 'Due' || $paymentMode == 'RestSplitBill'){
                    $TipAmount = $postData['TipAmount'];
                    $itemTotalGross = $postData['itemTotalGross'];
                    $splitTyp = $postData['splitType']; 
                    $splitPercent = $per_cent;
                }else{
                    $TipAmount = $this->session->userdata('TipAmount');
                    $itemTotalGross = $this->session->userdata('itemTotalGross');
                }
                
                // FOR ONLINE PAYMENTS
                $billingObj['EID'] = $EID;
                $billingObj['CTyp'] = $CTyp;
                $billingObj['TableNo'] = $kitcheData[0]['TableNo'];
                $billingObj['MergeNo'] = $kitcheData[0]['MergeNo'];
                $billingObj['ChainId'] = $ChainId;
                // $billingObj['ONo'] = $ONo;
                $billingObj['ONo'] = 0;
                $billingObj['CNo'] = $CNo;
                $billingObj['BillNo'] = $newBillNo;
                $billingObj['COrgId'] = $COrgId;
                $billingObj['CustNo'] = $CustNo;
                $billingObj['TotAmt'] = $itemTotalGross;
                $billingObj['PaidAmt'] = $totalAmount;
                $billingObj['SerCharge'] = $kitcheData[0]['ServChrg'];
                $billingObj['SerChargeAmt'] = round(($itemTotalGross * $kitcheData[0]['ServChrg']) /100 ,2);
                $billingObj['Tip'] = $TipAmount;
                // $billingObj['PaymtMode'] = $paymentMode;
                // $billingObj['PymtRef'] = $orderId;
                // $billingObj['PymtType'] = 0;
                $billingObj['TotItemDisc'] = $TotItemDisc;
                $billingObj['BillDiscAmt'] = $BillDiscAmt;
                $billingObj['custDiscAmt'] = $cust_discount;
                $billingObj['TotPckCharge'] = $TotPckCharge;
                $billingObj['DelCharge'] = $DelCharge;
                $billingObj['Stat'] = $billingObjStat;
                if($paymentMode == 'Due' || $paymentMode == 'RestSplitBill'){
                    $billingObj['CellNo'] = $postData['CellNo'];
                    $billingObj['CustId'] = $postData['CustId'];
                }else{
                    $billingObj['CellNo'] = $kitcheData[0]['CellNo'];
                    $billingObj['CustId'] = $kitcheData[0]['CustId'];
                }
                $billingObj['splitTyp'] = $splitTyp;
                $billingObj['splitPercent'] = $splitPercent;
                $billingObj['OType'] = $kitcheData[0]['OType'];
                $billingObj['LoginCd'] = $kitcheData[0]['LoginCd'];
                // echo "<pre>";
                // print_r($billingObj);
                $discountDT = array();
                if($this->session->userdata('Discount') > 0){
                    $discountDT = getDiscount($billingObj['CustId']);
                    if(!empty($discountDT)){
                        $billingObj['discPcent'] = $discountDT['pcent'];
                        $billingObj['discId'] = $discountDT['discId'];
                        $gt = $totalAmount / (100 - $discountDT['pcent']) * 100;
                        $billingObj['autoDiscAmt'] = ($billingObj['PaidAmt'] * $discountDT['pcent'])/100;
                        $billingObj['PaidAmt'] = $billingObj['PaidAmt'] - $billingObj['autoDiscAmt'];
                    }
                }

                $billingObj['PaidAmt'] = round($billingObj['PaidAmt']);
                
                $this->db2->trans_start();

                    if(($EType == 1) && ($EDTs > 0)){
                        $edtMax = $this->db2->select('MCNo, ItemId, EDT, max(EDT) as EDT')
                                        ->get_where('Kitchen',array('MCNo' => $CNo, 'EID' => $EID,'Stat' => 2))->row_array();
                        if(!empty($edtMax)){
                            updateRecord('Kitchen', array('EDT' => $edtMax['EDT']), array('MCNo' => $CNo, 'EID' => $EID) );
                        }
                    }
            
                    $lastInsertBillId = insertRecord('Billing', $billingObj);

                    $genTblDb = $this->load->database('GenTableData', TRUE);
                    
                    if(!empty($lastInsertBillId)){
                        // gen db
                        $kitchenSale = $this->db2->select("b.BillId, k.ItemId, k.Qty, k.Itm_Portion, k.OType, k.TA, k.EID, m.UItmCd")
                                    ->join('KitchenMain km', '(km.CNo = b.CNo or km.MCNo = b.CNo)', 'inner')
                                    ->join('Kitchen k', 'k.MCNo = km.MCNo', 'inner')
                                    ->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
                                    ->where_in('k.Stat', array(2,3))
                                    ->get_where('Billing b', array(
                                                'b.EID' => $EID,
                                                'km.EID' => $EID,
                                                'k.EID' => $EID,
                                                'm.EID' => $EID,
                                                'b.BillId' => $lastInsertBillId)
                                                )
                                    ->result_array();
                        if(!empty($kitchenSale)){
                            $kitchenSaleObj = [];
                            $temp = [];
                            foreach ($kitchenSale as $key) {
                                $temp['ItemId'] = $key['ItemId'];
                                $temp['BillId'] = $key['BillId'];
                                $temp['IPCd'] = $key['Itm_Portion'];
                                $temp['Quantity'] = $key['Qty'];
                                $temp['EID'] = $key['EID'];
                                $temp['OType'] = $key['OType'];
                                $temp['TakeAway'] = $key['TA'];
                                $temp['UItmCd'] = $key['UItmCd'];
                                $temp['Created_at'] = date('Y-m-d H:i:s');

                                $kitchenSaleObj[] = $temp;
                            }

                            if(!empty($kitchenSaleObj)){
                                $genTblDb->insert_batch('KitchenSale', $kitchenSaleObj); 
                            }
                        }
                        // end of gen db

                        if($EType == 5){
                            $this->db2->where_in('Stat', array(1,2));
                            $this->db2->update('Kitchen',array('Stat' => 7),array('EID' => $EID, 'MCNo' => $CNo));
                        }
                        // for etype=1 entire cart goes for checkout
                    }

                    foreach ($res['taxDataArray'] as $key => $value1) {
                        foreach ($value1 as $key => $value) {
                            $BillingTax['BillId'] = $lastInsertBillId;
                            $BillingTax['MCNo'] = $CNo;
                            $BillingTax['TNo'] = $value['TNo'];
                            $BillingTax['TaxPcent'] = $value['TaxPcent'];
                            $BillingTax['TaxAmt'] = $value['SubAmtTax'];
                            $BillingTax['EID'] = $EID;
                            $BillingTax['TaxIncluded'] = $value['Included'];
                            $BillingTax['TaxType'] = $value['TaxType'];
                            insertRecord('BillingTax', $BillingTax);
                        }
                    }
                    // store to gen db whenever bill generated
                    $custPymtObj['BillId']      = $lastInsertBillId;
                    $custPymtObj['CustId']      = $CustId;
                    $custPymtObj['BillNo']      = $lastInsertBillId;
                    $custPymtObj['EID']         = $EID;
                    $custPymtObj['aggEID']      = $this->session->userdata('aggEID');
                    $custPymtObj['PaidAmt']     = $totalAmount;
                    $custPymtObj['PaymtMode']   = $paymentMode;
                    $genTblDb->insert('CustPymts', $custPymtObj);
                    
                    $kstat = ($EType == 5)?3:2;

                $this->db2->trans_complete();

                $this->session->set_userdata('KOTNo', 0);
                $this->session->set_userdata('CNo', 0);
                $this->session->set_userdata('itemTotalGross', 0);

                if(!empty($lastInsertBillId)){
                    $this->session->set_userdata('billFlag',1);
                }
                $dt = [
                    "status" => 'success',
                    "response" => base_url('customer/pay/'.$lastInsertBillId.'/'.$CNo)
                ];

            }
        } // end for loop
        
        return $dt;
    }

    public function update_customItem_onKitchen(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            
            $updateData['CustItem'] = 1;
            $updateData['CustItemDesc'] = $_POST['CustItemDesc'];
            $updateData['ItmRate'] = $_POST['ItemRates'];
            $updateData['OrigRate'] = $_POST['OrigRates'];

            updateRecord('Kitchen', $updateData, array('EID' => authuser()->EID, 'OrdNo' => $_POST['OrdNo'], 'ItemId' => $_POST['ItemId']));
            $status = 'success';
            $response = $this->lang->line('itemRateUpdated');

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_customize_items(){
        $status = "error";
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            $response = $this->cust->get_customize_lists($_POST);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_selection_offer(){
        $status = 'error';
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $response = $this->lang->line('selectedOfferDoesNotSatisfyCriteria');
            $langId = $this->session->userdata('site_lang');
            // $scName = "c.SchNm$langId as SchNm";
            // $scDesc = "cod.SchDesc$langId as SchDesc";
            
            $EType = $this->session->userdata('EType');
            $stat = ($EType == 5)?2:1;

            $CNo = $this->session->userdata('CNo');
            $MinBillAmt = $_POST['minbillamt'];
            $EID = authuser()->EID;

            $whr = "";
            if($_POST['offerType'] > 0){
                switch ($_POST['offerType']) {
                    // food
                    case '1':
                        $whr = " and mi.CID NOT IN (10, 0)";
                        break;
                        // food & bar
                    case '2':
                        $whr = "";
                        break;
                        // bar
                    case '3':
                        $whr = " and mi.CID = 10";
                        break;
                }
            }

            if($_POST['itemtyp'] == 4){
                $ItemTyp = $_POST['itemtyp'];
                $whr .= " and k.ItemTyp = $ItemTyp";
                if($_POST['ipcd'] > 0){
                    $Itm_Portion = $_POST['ipcd'];
                    $whr .= " and  k.Itm_Portion = $Itm_Portion";
                }
            }

            if($_POST['cid'] > 0){
                $CID = $_POST['cid'];
                $whr .= " and mi.CID = $CID";
            }

            if($_POST['mcatgid'] > 0){
                $MCatgId = $_POST['mcatgid'];
                $whr .= " and mi.MCatgId = $MCatgId";
            }

            $price = $this->db2->query("SELECT sum(k.OrigRate * k.Qty) as total_amount from Kitchen k, MenuItem mi where k.CNo = $CNo and k.EID = $EID and k.BillStat = 0 and k.Stat= $stat and k.ItemId = mi.ItemId AND mi.EID = $EID $whr group by k.CNo")->row_array();
            
            $origRates = $price['total_amount'];
            if($origRates >= $MinBillAmt){
                $status = 'success';
                // dropdown list for item
                $flag = 0;
                if($_POST['disccid'] > 0){
                    $this->db2->where('mi.CID', $_POST['disccid']);
                    $flag = 1;
                }else if($_POST['discmcatgid'] > 0){
                    $flag = 1;
                    $this->db2->where('mi.MCatgId', $_POST['discmcatgid']);
                }else if($_POST['discitemid'] > 0){
                    $flag = 1;
                    $this->db2->where('mi.ItemId', $_POST['discitemid']);
                }else if($_POST['discitemtyp'] > 0){
                    $flag = 1;
                    $this->db2->where('mi.ItemTyp', $_POST['discitemtyp']);
                } 

                if($_POST['discipcd'] > 0){
                    $flag = 1;
                    $this->db2->where('mir.Itm_Portion', $_POST['discipcd']);
                }
                
                $response = [];
                if($flag > 0){
                    $langId = $this->session->userdata('site_lang');
                    $itemName = "mi.Name$langId";
                    $ipName = "ip.Name$langId";
                    $response = $this->db2->select("mi.ItemId, (case when $itemName != '-' Then $itemName ELSE mi.Name1 end) as itemName, ip.IPCd, (case when $ipName != '-' Then $ipName ELSE ip.Name1 end) as portionName")
                                    ->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
                                    ->join('ItemPortions ip', 'ip.IPCd = mir.Itm_Portion')
                                    ->get_where('MenuItem mi', array('mi.EID' => $EID, 'mir.EID' => $EID, 'mi.Stat' => 0, 'mir.OrigRate >' => 0))->result_array();
                                    
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

    public function kot_print_data(){

        $status = 'error';
        $response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $CNo = $this->session->userdata('CNo');
            $EID = $this->session->userdata('EID');
            $EType = $this->session->userdata('EType');

            $response = $this->db2->select("MCNo, MergeNo, KOTNo, FKOTNo")
                        ->group_by('KOTNo, FKOTNo')
                        ->get_where('Kitchen', array('EID' => $EID, 'CNo' => $CNo, 'BillStat' => 0, 'Stat' => 3))
                        ->result_array();

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

    }

    public function reorder(){
        $EID  = authuser()->EID;
        $TableNo = $this->session->userdata('TableNo');
        $CNo = $this->session->userdata('CNo');
        $stat = ($this->session->userdata('EType') == 5)?2:1;
        if($CNo > 0){
            $orders = $this->cust->getItemListForReorder();
            
            $kit = [];
            if(!empty($orders)){
                foreach ($orders as &$key) {
                    $OrdNo = $key['OrdNo'];
                    unset($key['OrdNo']);
                    $key['Stat']    = $stat;
                    $key['reOrder'] = 1;
                    $kit[] = $key;
                    if($key['SchCd'] > 0){
                        $offerDt = $this->cust->getOfferItemsForReorder($OrdNo, $key['SchCd'], $key['SDetCd']);
                        if(!empty($offerDt)){
                            foreach ($offerDt as &$offer) {
                                unset($offer['OrdNo']);
                                $offer['Stat']      = $stat;
                                $offer['reOrder']   = 1;
                                $kit[] = $offer;
                            }
                        }
                    }
                }
            }
            if(!empty($kit)){
                $this->db2->insert_batch('Kitchen', $kit); 
            }

            redirect(base_url('customer/cart'));
        }
    }

}
