<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restorent extends CI_Controller {

    private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Rest', 'rest');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

    public function add_user(){
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $res = $this->rest->addUser($_POST);
            $this->session->set_flashdata('success',$res); 
            redirect(base_url('restorent/add_user'));
        }

		$data['title'] = 'Add User';
        $data['restorent'] = $this->rest->getRestorentList(authuser()->ChainId);
		$this->load->view('rest/add_user',$data);
    }

    public function user_disable(){
    	$status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
        	$status = 'success';
        	$response = $this->rest->userDisableEnable($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

		 $data['title'] = 'User Disable';
		 $data['users'] = $this->rest->getDisableUserList(authuser()->ChainId, authuser()->EID);
		 // echo "<pre>";
		 // print_r($data);
		 // die;
		 $this->load->view('rest/disable_user',$data);
    }

    public function user_access(){
        if($this->input->method(true)=='POST'){
            $res = $this->rest->getUserAccessRole($_POST);
            echo json_encode($res);
            die;
        }
    	$data['title'] = 'User Access';
		$this->load->view('rest/access_users',$data);
    }

    public function offers_list(){
		 $data['title'] = 'Offers List';
		 $data['offers'] = $this->rest->getOffersList();
		 $this->load->view('rest/offer_lists',$data);
    }

    public function new_offer(){
    	$data['title'] = 'Add New Offer';
		$this->load->view('rest/add_new_offer',$data);	
    }

    public function item_list(){
        $data['EID'] = authuser()->EID;
        $data['ChainId'] = authuser()->ChainId;
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $data['CID'] = '';
        $data['catid'] = '';
        $data['cuisine'] = $this->db2->get('Cuisines')->result_array();
        $data['menucat'] = $this->db2->get('MenuCatg')->result_array();
        // $q = "SELECT mi.ItemId, mi.ItemNm, ip.Name as Portion, md.ItemId as deactive FROM ItemPortions ip, MenuItem mi, MenuItemRates mr LEFT JOIN MenuItem_Disabled md ON (md.ItemId = mr.ItemId) and (mr.Itm_Portion = md.IPCd) Where mi.ItemId = mr.ItemId and ip.IPCd=mr.Itm_Portion and mr.EID = $EID and mr.ChainId = $ChainId and mi.Stat=0";
        if($this->input->method(true)=='POST'){
            if($_POST){
                if(isset($_POST['cuisine']) && !empty($_POST['cuisine'])){
                    $CID = $_POST['cuisine'];
                    $c = $_POST['cuisine'];
                    $q.=" and mi.CID = '$c'";
                    $data['menucat'] = $this->db2->query("SELECT * from MenuCatg where CID = '$CID'")->result_array();
                }
                if(isset($_POST['menucat']) && !empty($_POST['menucat'])){
                    $catid = $_POST['menucat'];
                    $m = $_POST['menucat'];
                    $q.=" and mi.MCatgId = '$m'";
                }
            }
            
        }
        // $q.=" GROUP by mi.ItemId, ip.Name, md.ItemId order by mi.MCatgId, mi.ItemId, ip.Name, mi.Rank";
        // $data['menuItemData'] = $this->db2->query($q)->result_array();
        $data['menuItemData'] = array();
    	$data['title'] = 'Item Details';
        // echo "<pre>";
        // print_r($data);
        // die;
		$this->load->view('rest/item_lists',$data);	
    }

    public function order_dispense(){
    	$data['title'] = 'Order Dispense';
        $data['DispenseAccess'] = $this->rest->getDispenseAccess();

		$this->load->view('rest/dispense_orders',$data);	
    }

    public function order_delivery(){
        if($this->input->method(true) == 'POST'){
            $AutoAllot = $this->session->userdata('AutoAllot');
            $AutoDeliver = $this->session->userdata('AutoDeliver');
            $EType = $this->session->userdata('EType');
            
            $EID = authuser()->EID;
            if (isset($_POST['getKtichenItem'])) {

                if ($AutoDeliver == 1 && $AutoAllot == 1) {
                    // Auto auto Mode
                    $kitchenData = $this->db2->query("SELECT k.ItemId, sum(k.Qty - k.AQty) as Qty, k.TA, k.CustRmks, i.ItemNm FROM `Kitchen` k, Eat_Kit ek, MenuItem i where i.ItemId = k.ItemId AND (k.Stat <= 3 AND k.Stat > 0) AND k.EID = $EID AND  ek.EID = k.EID AND k.KitCd = ek.KitCd AND (k.Qty - k.AQty) > 0 and (DateDiff(Now(),k.LstModDt) < 2)  Group by i.ItemId, k.TA, k.CustRmks Order by i.ItemNm")->result_array();
                    //and (DateDiff(CurrDate(),k.LstModDt) < 2)
                } elseif ($AutoDeliver == 0 && $AutoAllot == 1) {
                    // Auto Manual Mode
                    $kitchenData = $this->db2->query("SELECT k.ItemId, sum(k.Qty - k.AQty) as Qty, k.TA, k.CustRmks, i.ItemNm FROM `Kitchen` k, Eat_Kit ek, MenuItem i where i.ItemId = k.ItemId AND (k.Stat <= 3 AND k.Stat > 0) AND k.EID = $EID AND  ek.EID = k.EID AND k.KitCd = ek.KitCd  AND (k.Qty - k.AQty) > 0 and (DateDiff(Now(),k.LstModDt) < 2) Group by i.ItemId, k.TA, k.CustRmks Order by i.ItemNm")->result_array();
                    //and (DateDiff(CurrDate(),k.LstModDt) < 2)
                }


                if (empty($kitchenData)) {
                    $response = [
                        "status" => 0,
                        "msg" => "No Item Pending"
                    ];
                } else {
                    $response = [
                        "status" => 1,
                        "kitchenData" => $kitchenData
                    ];
                }

                echo json_encode($response);
                die();
            }

            if (isset($_POST['autoItemPrepare'])) {
                // print_r($_POST);
                $itemId = $_POST['itemId'];
                $itemQty = $_POST['itemQty'];
                $itemPortion = $_POST['itemPortion'];
                $customerRemarks = $_POST['customerRemarks'];

                //$autoDeliver = 0;
                //$autoAllot = 1;

                while ($itemQty > 0) {
                    if ($AutoDeliver == 1 && $AutoAllot == 1) {
                        // Auto auto Mode
                        $singleRow = $this->db2->query("SELECT OrdNo, (Qty - DQty) as PQty from Kitchen where ItemId = $itemId AND Itm_Portion = '$itemPortion' AND CustRmks = '$customerRemarks' AND EID = $EID AND (Qty - DQty) > 0 order by OrdNo ASC limit 1")->row_array();

                        // 5 > 3
                        if ($singleRow['PQty'] > $itemQty) {
                            $orderNo = $singleRow['OrdNo'];
                            $temp = $this->db2->query("UPDATE Kitchen set DQty = (DQty + $itemQty), DelTime = NOW(), AQty = $itemQty where OrdNo = $orderNo  AND EID = $EID and DateDiff(Now(),LstModDt) < 2");
                            $itemQty = 0;
                        } else {
                            // 2 <= 3
                            $requireQty = $singleRow['PQty'];
                            $orderNo = $singleRow['OrdNo'];
                            $temp = $this->db2->query("UPDATE Kitchen set DQty = (DQty + $requireQty), DelTime = NOW(), AQty = $requireQty, Stat = 5 where OrdNo = $orderNo AND EID = $EID and DateDiff(Now(),LstModDt) < 2");
                            $itemQty = $itemQty - $requireQty;
                        }
                    } elseif ($AutoDeliver == 0 && $AutoAllot == 1) {
                        // Auto Manual Mode

                        if ($EType == 5) {

                            $singleRow = $this->db2->query("SELECT OrdNo, (Qty - DQty) as PQty from Kitchen where ItemId = $itemId AND CustRmks = '$customerRemarks' AND EID = $EID and DateDiff(Now(),LstModDt) < 2 AND (Qty - DQty) > 0 order by OrdNo ASC limit 1")->row_array();

                            // 5 > 3
                            if ($singleRow['PQty'] > $itemQty) {
                                $orderNo = $singleRow['OrdNo'];
                                $temp = $this->db2->query("UPDATE Kitchen set AQty = $itemQty, Stat = 2 where OrdNo = $orderNo AND EID = $EID and DateDiff(Now(),LstModDt) < 2");
                                $itemQty = 0;
                            } else {
                                // 2 <= 3
                                $requireQty = $singleRow['PQty'];
                                $orderNo = $singleRow['OrdNo'];
                                $temp = $this->db2->query("UPDATE Kitchen set AQty = $requireQty, Stat = 2 where OrdNo = $orderNo AND EID = $EID and DateDiff(Now(),LstModDt) < 2");
                                $itemQty = $itemQty - $requireQty;
                            }
                        } else {
                            $singleRow = $this->db2->query("SELECT OrdNo, (Qty - AQty) as PQty from Kitchen where ItemId = $itemId AND CustRmks = '$customerRemarks' AND EID = $EID and DateDiff(Now(),LstModDt) < 2 AND (Qty - AQty) > 0 order by OrdNo ASC limit 1")->row_array();

                            // 5 > 3
                            if ($singleRow['PQty'] > $itemQty) {
                                // echo 1;
                                $orderNo = $singleRow['OrdNo'];
                                $temp = $this->db2->query("UPDATE Kitchen set AQty = (AQty + $itemQty), Stat = 2 where OrdNo = $orderNo AND EID = $EID and DateDiff(Now(),LstModDt) < 2");
                                $itemQty = 0;
                            } else {
                                // 2 <= 3
                                // echo 2;
                                $requireQty = $singleRow['PQty'];
                                $orderNo = $singleRow['OrdNo'];
                                $temp = $this->db2->query("UPDATE Kitchen set AQty = (AQTY + $requireQty), Stat = 2 where OrdNo = $orderNo AND EID = $EID and DateDiff(Now(),LstModDt) < 2");
                                $itemQty = $itemQty - $requireQty;
                            }
                        }
                    }
                }


                $response = [
                    "status" => 1,
                    "msg" => "updated"
                ];

                echo json_encode($response);
                die();
            }


            if (isset($_POST['assignOrder'])) {

                $reassign = 2;

                $orderId = $_POST['orderId'];
                $assignToOrderId = $_POST['assignToOrderId'];
                $assignQty = $_POST['assignQty'];

                $kitchenRemoveAllocate = $this->db2->query("UPDATE Kitchen set AQty = 0 where OrdNo = :orderId", ["orderId" => $orderId]);

                if ($reassign == 1) {
                    $kitchenReassignOrder = $this->db2->query("UPDATE Kitchen set AQty = $assignQty, Stat = 2 where OrdNo = $assignToOrderId", ["assignQty" => $assignQty, "assignToOrderId" => $assignToOrderId]);
                } elseif ($reassign == 2) {
                    $kitchenReassignOrder = $this->db2->query("UPDATE Kitchen set AQty = $assignQty, Stat = IF((DQty + $assignQty) < Qty, 2, 5), DQty = (DQty + $assignQty) where OrdNo = $assignToOrderId", ["assignQty" => $assignQty, "assignToOrderId" => $assignToOrderId]);
                }

                $response = [
                    "status" => 1,
                    "msg" => "Update Success"
                ];

                echo json_encode($response);
                die();
            }


            if ($_POST['getOrderDetails']) {
                // print_r($_POST);exit();
                if (isset($_POST['DispCd'])) {
                    $DCd = $_POST['DispCd'];
                } else {
                    $DCd = 0;
                }

                //This is for the main data table details... need to know why we are using ukotno and kitcd as this is for dispensing
                $DCdType = $this->db2->query("SELECT * from Eat_DispOutlets where DCd = ".$DCd)->row_array();
                if ($EType == 5) {
                    

                    if($DCdType['DCdType'] == 1){
                        $q = "SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * k.ItmRate) as KOTAmt, k.kitcd,  km.TPRefNo, km.TPId, km.CustId, km.CustNo, km.CellNo, km.EID,km.CNo  FROM KitchenMain km, Kitchen k,  Billing b , Eat_DispOutlets ed, Eat_DispOutletsDet edd where km.EID=b.EID  AND b.CNo = km.CNo and km.BillStat=b.BillId AND km.CNo = k.CNo AND ed.EID = km.EID and ed.DCd=edd.DCd and k.KitCd = edd.KitCd and km.EID = ".$EID."  AND ed.DCd = ".$DCd." Group by b.BillNo, k.KitCd, km.TPRefNo, km.TPId, km.CustId, km.EID, km.CustNo, km.CellNo,km.CNo Order by b.BillNo Asc";
                    }elseif($DCdType['DCdType'] == 2){
                        $q = "SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * k.ItmRate) as KOTAmt, k.OType,  km.TPRefNo, km.TPId, km.CustId, km.CustNo, km.CellNo, km.EID,km.CNo  FROM KitchenMain km, Kitchen k,  Billing b , Eat_DispOutlets ed, Eat_DispOutletsDet edd where km.EID=b.EID  AND b.CNo = km.CNo and km.BillStat=b.BillId AND km.CNo = k.CNo AND ed.EID = km.EID and ed.DCd=edd.DCd and k.OType = edd.OType and km.EID = ".$EID."  AND ed.DCd = ".$DCd." Group by b.BillNo, k.OType, km.TPRefNo, km.TPId, km.CustId, km.EID, km.CustNo, km.CellNo,km.CNo Order by b.BillNo Asc";
                    }
                    // print_r($q);exit();
                    $kitchenData = $this->db2->query($q)->result_array();
                } else {
                    // $Fest=1;
                    if ($Fest == 0) {
                        $kitchenData = $this->db2->query("SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * i.Value) as KOTAmt, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.CustNo, k.CellNo, k.EID, km.DCd, ed.Name FROM `Kitchen` k, MenuItem i, Billing b, Eat_DispOutlets ed where i.ItemId = k.ItemId AND k.OType <> 7 AND k.Stat <= 3 AND k.Stat > 0  AND k.EID=b.EID and ed.DCd = km.DCd and k.BillStat= b.BillId AND k.EID =$EID AND ed.EID = k.EID AND ed.DCd = $DCd and (DateDiff(Now(),k.LstModDt) < 2) Group by b.BillNo, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.EID, k.CustNo, k.CellNo  Order by b.BillNo")->result_array();  //And k.payRest=1
                        //if this is not fest and has multiple counters for dispense, it is grouped by DCd, else we do not need ukotno. undelss item details are shown thru ukotno

                    } else {
                        $kitchenData = $this->db2->query("SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * i.Value) as KOTAmt, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.CustNo, k.CellNo, k.EID, km.DCd, ed.Name FROM `Kitchen` k, MenuItem i, Billing b , Eat_DispOutlets ed where i.ItemId = k.ItemId AND k.OType <> 7  AND k.Stat > 0 AND k.Stat <= 3 AND k.EID=b.EID and ed.DCd = km.DCd and k.BillStat=b.BillId AND ed.EID = k.EID and k.EID = $EID AND ed.KitCd=k.KitCd AND ed.DCd = $DCd and date(k.LstModDt) = date(Now()) Group by b.BillNo, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.EID, k.CustNo, k.CellNo Order by b.BillNo Asc")->result_array();       //And k.payRest=1 
                    }
                }
                // echo "<pre>";print_r($kitcheData);exit();

                if (empty($kitchenData)) {
                    $response = [
                        "status" => 0,
                        "msg" => "No Item Pending"
                    ];
                } else {
                    $response = [
                        "status" => 1,
                        "kitchenData" => $kitchenData
                    ];
                }

                echo json_encode($response);
                die();
            }

            if ($_POST['getOrderList']) {
                if (isset($_POST['DispCd'])) {
                    $DCd = $_POST['DispCd'];
                } else {
                    $DCd = 0;
                }
                $CNo = $_POST['CNo'];
                if ($Fest == 0) {
                    $q = "SELECT i.ItemNm, sum(k.Qty) as Qty,if(k.TA = 0,'No','Yes') as TA,k.CustRmks, sum(k.AQty) as AQty, sum(k.Qty * k.ItmRate) as Amt from Kitchen k, MenuItem i where i.ItemId = k.ItemId AND k.CNo = ".$CNo." AND k.EID = $EID Group By i.ItemNm,k.Qty,k.TA,k.CustRmks";
                } else {
                    $q = "SELECT i.ItemNm, sum(k.Qty) as Qty,if(k.TA = 0,'No','Yes') as TA,k.CustRmks, sum(k.AQty) as AQty, sum(k.Qty * k.ItmRate) as Amt from Kitchen k, MenuItem i, Eat_DispOutlets ed where i.ItemId = k.ItemId AND k.CNo = ".$CNo." AND ed.EID = k.EID AND ed.KitCd=k.KitCd AND ed.DCd = $DCd AND k.EID = $EID Group By i.ItemNm,k.Qty,k.TA,k.CustRmks";
                    
                }
                // print_r($q);exit();
                $orderList = $this->db2->query($q);
                // print_r($orderList);exit();
                if (count($orderList) > 0) {
                    $response = [
                        "status" => 1,
                        "orderList" => $orderList
                    ];
                } else {
                    $response = [
                        "status" => 0,
                        "msg" => "No results"
                    ];
                }

                echo json_encode($response);
                die();
            }

            if ($_POST['deliverOrder']) {
                $CNo = $_POST['CNo'];
                
                $updateKitcheMain = $this->db2->query("UPDATE KitchenMain SET Delivered = 1 where EID = $EID AND CNo = ".$CNo." AND BillStat > 0", ["CNo" => $CNo]);
                    $kitcheData = $kitchenObj->exec("SELECT SUM(m.Value*k.Qty) as OrdAmt, k.KOTNo, k.UKOTNo, date(k.LstModDt) as OrdDt, c.CGSTRate, c.SGSTRate, c.GSTInclusiveRates, c.ServChrg from Kitchen k, MenuItem m, Config c where c.EID=k.EID and k.ItemId=m.ItemId and (k.Stat<>4 and k.Stat<>6 and k.stat<>7 and k.Stat<>9 and k.Stat<>99) AND k.CNo=".$CNo." and k.EID=$EID AND k.BillStat=0 group by k.UKOTNo, k.KOTNo, date(k.LstModDt), c.CGSTRate, c.SGSTRate, c.GSTInclusiveRates, c.ServChrg order by date(k.LstModDt)Asc, KotNo Asc", ["CNo" => $CNo]);

                    $response = [
                        "status" => 1,
                        "msg" => "success"
                    ];
                echo json_encode($response);
                die();
            }

            if (isset($_POST['otpUpdate']) && $_POST['otpUpdate'] == 1) {
                $otp = rand(1000, 9999);
                $billNo = $_POST['billNo'];
                $updateQuery = $this->db2->query("UPDATE `KitchenMain` SET `DispenseOTP`=$otp WHERE `BillStat`=$billNo");
                if ($updateQuery) {
                    echo $otp;
                }
            }
        }
    }

    public function change_password(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = 'success';
            $response = $this->rest->passwordUpdate($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Change Password';
        $this->load->view('rest/password_change',$data);   
    }

    public function merge_table(){
        if($this->input->method(true)=='POST'){

            $res = $this->rest->mergeTables($_POST);
            echo json_encode($res);
            die;
        }

        $data['title'] = 'Table - Join/Unjoin';
        $this->load->view('rest/table_merge',$data);   
    }

    public function set_theme(){
        if($this->input->method(true)=='POST'){

            $q = "SELECT c.* from Cuisines as c, EatCuisine as ec where ec.EID = ".authuser()->EID." and ec.CID=c.CID and ec.stat = 0";
            $cuisines = $this->db2->query($q)->result_array();
            // print_r($cuisines);exit();

            $item_types = $this->db2->get('ItemTypes')->result_array();

            if($_POST){
                // echo "<pre>";print_r($_POST);exit;
                if(isset($_POST['apply_default']) && $_POST['apply_default']){
                    $this->db2->query("UPDATE ConfigTheme set Status = 0");
                    $this->db2->query("UPDATE ConfigTheme set Status = 1 where ThemeId = 1");
                }else{
                    if(isset($_POST['apply']) && $_POST['apply'] == 1){
                        $this->db2->query("UPDATE ConfigTheme set Status = 0");
                        $Theme['Status'] = 1;
                    }

                    $Theme['Sec1Background'] = $_POST['Sec1Background'];
                    $Theme['Sec1TextColor'] = $_POST['Sec1TextColor'];
                    $Theme['Sec2Background'] = $_POST['Sec2Background'];
                    $Theme['Sec2TextColor'] = $_POST['Sec2TextColor'];
                    $Theme['Sec3Background'] = $_POST['Sec3Background'];
                    $Theme['Sec3TextColor'] = $_POST['Sec3TextColor'];
                    $Theme['BodyBackground'] = $_POST['BodyBackground'];
                    $Theme['BodyTextColor'] = $_POST['BodyTextColor'];
                    $Theme['Sec2BtnText'] = $_POST['Sec2BtnText'];
                    $Theme['Sec2BtnColor'] = $_POST['Sec2BtnColor'];
                    $Theme['Button1Color'] = $_POST['Button1Color'];
                    $Theme['Button1TextColor'] = $_POST['Button1TextColor'];
                    $Theme['Button2Color'] = $_POST['Button2Color'];
                    $Theme['Button2TextColor'] = $_POST['Button2TextColor'];
                    // $Theme->ThemeId = 1;
                echo "<pre>";
                print_r($Theme);
                die;
                    $this->db2->insert('ConfigTheme',$Theme);
                }
            }
            $data['data11'] = $this->db2->query("SELECT * from ConfigTheme order by ThemeId desc")->result_array();
            if(!empty($data11)){
                $data['data11'] = $data['data11'][0];
            }else{
                $data['data11'] = $this->db2->query("SELECT * from ConfigTheme order by ThemeId")->result_array();
                $data['data11'] = $data['data11'][0];
            }
        }

        $data['title'] = 'Theme Setting';
        $this->load->view('rest/theme_setting',$data);   
    }

    public function stock_list(){
        $data['trans_id'] = NULL;
        $data['trans_type_id'] = NULL;
        $data['from_date'] = date('Y-m-d');
        $data['to_date'] = date('Y-m-d');
        $data['stock'] = $this->rest->getStockList();
        if($this->input->method(true)=='POST'){
            $data['stock'] = $this->rest->getStockList($_POST);
        }

        $data['trans_type'] = array(1=>'Transfer To EID', 6=>'Purchase Return', 9=>'Issue to Kit', 11=>'Return From EID', 16 =>'Purchase', 19=>'Return from Kit', 25=>'Inward Adjust', 26=>'Outward Adjust', 27 => 'Stock Adjust');
        
        $data['title'] = 'Stock List';
        $this->load->view('rest/stocks_list',$data);     
    }

    public function add_stock(){
        if($this->input->method(true)=='POST'){
            echo "<pre>";print_r($_POST);exit();
            if($_POST['add_stock'] && $_POST['add_stock'] == 1){
                if($_POST){
                    // echo "<pre>";print_r($_POST);exit();
                    $RMStock['TransType'] = $_POST['trans_type'];
                    $RMStock['FrmSuppCd'] = !empty($_POST['to_store']) && !empty($_POST['supplier'])?$_POST['supplier']:0;
                    $RMStock['FrmEID'] = !empty($_POST['to_store']) && !empty($_POST['eatary'])?$_POST['eatary']:0;
                    $RMStock['FrmKitCd'] = !empty($_POST['to_store']) && $_POST['kit']?$_POST['kit']:0;

                    $RMStock['ToSuppCd'] = !empty($_POST['from_store']) && !empty($_POST['supplier'])?$_POST['supplier']:0;
                    $RMStock['ToEID'] = !empty($_POST['from_store']) && !empty($_POST['eatary'])?$_POST['eatary']:0;
                    $RMStock['ToKitCd'] = !empty($_POST['from_store']) && !empty($_POST['kit'])?$_POST['kit']:0;
                    
                    $RMStock['FrmStoreId'] = !empty($_POST['from_store'])?$_POST['from_store']:0;
                    
                    $RMStock['ToStoreId'] = !empty($_POST['to_store'])?$_POST['to_store']:0;
                    $RMStock['Stat'] = 0;
                    $RMStock['LoginId'] = 0;
                    $RMStock['TransDt'] = !empty($_POST['TransDt'])?$_POST['TransDt']:date('Y-m-d');
                    // $this->db2->insert('RMStock', $RMStock);
                    // $TransId = $this->db2->insert_id();
                    if($RMStock->create()){
                        $TransId = $RMStock->lastInsertId();
                        // $TransId = $this->db2->insert_id();
                        $num = sizeof($_POST['ItemId']);
                        for($i = 0;$i<$num;$i++){
                            $RMStockDet['TransId'] = $TransId;
                            $detid = !empty($_POST['RMDetId'][$i])?$_POST['RMDetId'][$i]:NULL;
                            $RMStockDet['RMCd'] = !empty($_POST['ItemId'][$i])?$_POST['ItemId'][$i]:0;
                            $RMStockDet['UOMCd'] = !empty($_POST['UOM'][$i])?$_POST['UOM'][$i]:0;
                            $RMStockDet['Qty'] = !empty($_POST['Qty'][$i])?$_POST['Qty'][$i]:0;
                            $RMStockDet['Rate'] = !empty($_POST['Rate'][$i])?$_POST['Rate'][$i]:0;
                            $RMStockDet['Rmks'] = !empty($_POST['Remarks'][$i])?$_POST['Remarks'][$i]:"";
                            
                            // echo "<pre>";print_r($RMStock);exit();
                            if(!empty($RMStockDet['variables']['RMCd']) && !empty($RMStockDet['variables']['Qty']) && !empty($RMStockDet['variables']['UOMCd'])){
                                // echo "<pre>";print_r($RMStock);exit();
                                if(!empty($detid)){
                                    // $RMStockDet->RMDetId = $detid;
                                    // $RMStockDet->save();
                                    // $this->db2->update('RMStockDet',$RMStockDet, array('RMDetId' =>$detid));
                                }else{
                                    // $RMStockDet->create();
                                    // $this->db2->insert('RMStockDet',$RMStockDet);
                                }
                            }
                        }
                    }
                    header("Location: ../stock_list.php");
                }

            }
            if($_POST['edit_stock'] && $_POST['edit_stock'] == 1){
                if($_POST){
                    // echo "<pre>";print_r($_POST);exit();
                   
                    $TransId = $_POST['trans_id'];
                    $num = sizeof($_POST['ItemId']);
                    for($i = 0;$i<$num;$i++){
                        $RMStockDet['TransId'] = $TransId;
                        $detid = !empty($_POST['RMDetId'][$i])?$_POST['RMDetId'][$i]:NULL;
                        $RMStockDet['RMCd'] = !empty($_POST['ItemId'][$i])?$_POST['ItemId'][$i]:0;
                        $RMStockDet['UOMCd'] = !empty($_POST['UOM'][$i])?$_POST['UOM'][$i]:0;
                        $RMStockDet['Qty'] = !empty($_POST['Qty'][$i])?$_POST['Qty'][$i]:0;
                        $RMStockDet['Rate'] = !empty($_POST['Rate'][$i])?$_POST['Rate'][$i]:0;
                        
                        // echo "<pre>";print_r($RMStock);exit();
                        if(!empty($RMStockDet['variables']['RMCd']) && !empty($RMStockDet['variables']['Qty']) && !empty($RMStockDet['variables']['Rate']) && !empty($RMStockDet['variables']['UOMCd'])){
                            // echo "<pre>";print_r($RMStock);exit();
                            if(!empty($detid)){
                                // $RMStockDet['RMDetId'] = $detid;
                                // $RMStockDet->save();
                                // $this->db2->update('RMStockDet',$RMStockDet, array('RMDetId' =>$detid));

                            }else{
                                // $RMStockDet->create();
                                // $this->db2->insert('RMStockDet',$RMStockDet);
                            }
                        }
                    }
                    
                    header("Location: ../stock_list.php");
                }

            }
            if(isset($_POST['delete_details'])){
                $this->db2->query("DELETE from RMStockDet where RMDetId=".$_POST['RMDetId']);
                echo 1;
            }
            if(isset($_POST['delete_trans'])){
                // print_r($_POST);exit();
                $this->db2->query("DELETE from RMStock where TransId=".$_POST['TransId']);
                echo 1;
            }

        }
        $data['title'] = 'Add Stock';
        $data['trans_type'] = array(1=>'Transfer To EID', 6=>'Purchase Return', 9=>'Issue to Kit', 11=>'Return From EID', 16 =>'Purchase', 19=>'Return from Kit', 25=>'Inward Adjust', 26=>'Outward Adjust', 27 => 'Stock Adjust');

        $q = "SELECT rm.* from RMItems as rm join RMCatg as rc on rm.RMCatg = rc.RMCatgCd join RMItemsUOM as riu on rm.RMCd = riu.RMCd join RMUOM as ru on ru.UOMCd = riu.UOMCd";
        $data['items'] = $this->db2->query($q)->result_array();

        $data['eatary'] = $this->db2->query("SELECT EID, Name from Eatary")->result_array();
        $data['kit'] = $this->db2->query("SELECT KitCd, KitName from Eat_Kit")->result_array();
        $data['suppliers'] = $this->db2->query("SELECT SuppCd, SuppName from RMSuppliers")->result_array();

        $this->load->view('rest/stock_add',$data);
    }

    public function stock_report(){
        $data['title'] = 'Stock Report';
        $data['report'] = $this->rest->getStockReport();
        $this->load->view('rest/stock_report',$data);
    }

    public function stock_consumption(){
        $data['title'] = 'Stock Consumption';
        $data['report'] = $this->rest->getStockConsumption();
        $this->load->view('rest/stock_consumptions',$data);   
    }

    public function itemstockreport(){
        if($this->input->method(true)=='POST'){
            $res = $this->rest->getItemStockReportList($_POST);
            $data['report'] = $res['report'];
            $data['op_stock'] = $res['op_stock'];
        }
        $data['title'] = 'Item Stock Report';
        $this->load->view('rest/itemstockreports',$data); 
    }

    public function edit_stock(){
        if(isset($_GET['TransId'])){
            $TransId = $_GET['TransId'];
            $data['TransId'] = $TransId;
            $q = "SELECT rm.* from RMItems as rm join RMCatg as rc on rm.RMCatg = rc.RMCatgCd join RMItemsUOM as riu on rm.RMCd = riu.RMCd join RMUOM as ru on ru.UOMCd = riu.UOMCd";
            $data['items'] = $this->db2->query($q)->result_array();

            $q = "SELECT * from RMStock where TransId=".$TransId;
            $data['stock'] = $this->db2->query($q)->result_array();
            $data['stock_details'] = $this->db2->query("SELECT * from RMStockDet where TransId=".$TransId)->result_array();
            $data['eatary'] = $this->db2->query("SELECT EID, Name from Eatary")->result_array();
            $data['kit'] = $this->db2->query("SELECT KitCd, KitName from Eat_Kit")->result_array();
            $data['suppliers'] = $this->db2->query("SELECT SuppCd, SuppName from RMSuppliers")->result_array();
        }
        $data['title'] = 'Edit Stock';
        // echo "<pre>";
        // print_r($data);exit();
        $this->load->view('rest/stock_edit',$data);    
    }

    public function rm_ajax(){
       if(isset($_POST['getUOM'])){
            $item_id = $_POST['RMCd'];
            $q = "SELECT riu.*, ru.Name from RMItemsUOM as riu join RMUOM as ru on riu.UOMCd = ru.UOMCd where riu.RMCd = ".$item_id;
            $uoms = $this->db2->query($q)->result_array();
            echo json_encode($uoms);
        } 
    }





}
