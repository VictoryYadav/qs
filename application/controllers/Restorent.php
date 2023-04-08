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
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $res = $this->rest->getUserAccessRole($_POST);
            echo json_encode($res);
            die;
        }
    	$data['title'] = 'User Access';
		$this->load->view('rest/access_users',$data);
    }

    public function role_assign(){
        $EID = authuser()->EID;
        $data['usersRestData'] = $this->db2->query("SELECT RUserId, FName, LName FROM UsersRest WHERE DeputedEID = $EID AND UTyp = 1 Order By FName ASC")->result_array();
        $data['kitData'] = $this->db2->query("SELECT KitCd, KitName FROM Eat_Kit WHERE EID = $EID")->result_array();
        $data['disData'] = $this->db2->query("SELECT DCd, Name FROM Eat_DispOutlets WHERE EID = $EID")->result_array();
        $data['casherData'] = $this->db2->query("SELECT CCd, Name FROM Eat_Casher WHERE EID = $EID")->result_array();

        $data['title'] = 'Role Assignment';
        $this->load->view('rest/assign_role',$data);   
    }

    public function rest_manager(){
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){

            if (isset($_POST['setKitchen']) && !empty($_POST['setKitchen'])) {
                $kitCd = $_POST['kitCd'];
                $userId = $_POST['userId'];
                $role = $_POST['role'];
                $id = $_POST['id'];

                if ($id == 0) {
                    $usersRoleDailyObj['RUserId'] = $userId;
                    // $usersRoleDailyObj['RoleType'] = $role;
                    $usersRoleDailyObj['KitCd'] = $kitCd;
                     $this->db2->insert('UsersRoleDaily', $usersRoleDailyObj);
                     $iddd = $this->db2->insert_id();
                    if($iddd) {
                        $response = [
                            "status" => 1,
                            "msg" => "role assigned"
                        ];
                    }else {
                        $response = [
                            "status" => 0,
                            "msg" => "Failed to insert in UsersRoleDaily"
                        ];
                    }
                }else {
                    // $usersRoleDailyObj['DNo'] = $id;
                    $usersRoleDailyObj['RUserId'] = $userId;
                    // $usersRoleDailyObj['RoleType'] = $role;
                    $usersRoleDailyObj['KitCd'] = $kitCd;
                    $usersRoleDailyObj['DCd'] = "";
                    $usersRoleDailyObj['CCd'] = "";
                    // $usersRoleDailyObj->save();
                    $this->db2->update('UsersRoleDaily', $usersRoleDailyObj, array('DNo' => $id));
                    $response = [
                        "status" => 1,
                        "msg" => "role assigned"
                    ];
                }

                echo json_encode($response);
                die();
            }

            if (isset($_POST['setDispency']) && !empty($_POST['setDispency'])) {
                $DCd = $_POST['DCd'];
                $userId = $_POST['userId'];
                $role = $_POST['role'];
                $id = $_POST['id'];

                if ($id == 0) {
                    $usersRoleDailyObj['RUserId'] = $userId;
                    // $usersRoleDailyObj['RoleType'] = $role;
                    $usersRoleDailyObj['DCd'] = $DCd;
                    $this->db2->insert('UsersRoleDaily', $usersRoleDailyObj);
                     $iddd = $this->db2->insert_id();
                    if($iddd) {
                        $response = [
                            "status" => 1,
                            "msg" => "role assigned"
                        ];
                    }else {
                        $response = [
                            "status" => 0,
                            "msg" => "Failed to insert in UsersRoleDaily"
                        ];
                    }
                }else {
                    // $usersRoleDailyObj['DNo'] = $id;
                    $usersRoleDailyObj['RUserId'] = $userId;
                    // $usersRoleDailyObj['RoleType'] = $role;
                    $usersRoleDailyObj['KitCd'] = "";
                    $usersRoleDailyObj['CCd'] = "";
                    $usersRoleDailyObj['DCd'] = $DCd;
                    // $usersRoleDailyObj->save();
                    $this->db2->update('UsersRoleDaily', $usersRoleDailyObj, array('DNo' => $id));

                    $response = [
                        "status" => 1,
                        "msg" => "role assigned"
                    ];
                }

                echo json_encode($response);
                die();
            }

            if (isset($_POST['setCasher']) && !empty($_POST['setCasher'])) {
                $CCd = $_POST['CCd'];
                $userId = $_POST['userId'];
                $role = $_POST['role'];
                $id = $_POST['id'];

                if ($id == 0) {
                    $usersRoleDailyObj['RUserId'] = $userId;
                    // $usersRoleDailyObj['RoleType'] = $role;
                    $usersRoleDailyObj['CCd'] = $CCd;
                    $this->db2->insert('UsersRoleDaily', $usersRoleDailyObj);
                     $iddd = $this->db2->insert_id();
                    if($iddd) {
                        $response = [
                            "status" => 1,
                            "msg" => "role assigned"
                        ];
                    }else {
                        $response = [
                            "status" => 0,
                            "msg" => "Failed to insert in UsersRoleDaily"
                        ];
                    }
                }else {
                    // $usersRoleDailyObj['DNo'] = $id;
                    $usersRoleDailyObj['RUserId'] = $userId;
                    // $usersRoleDailyObj['RoleType'] = $role;
                    $usersRoleDailyObj['KitCd'] = "";
                    $usersRoleDailyObj['DCd'] = "";
                    $usersRoleDailyObj['CCd'] = $CCd;
                    // $usersRoleDailyObj->save();
                    $this->db2->update('UsersRoleDaily', $usersRoleDailyObj, array('DNo' => $id));

                    $response = [
                        "status" => 1,
                        "msg" => "role assigned"
                    ];
                }

                echo json_encode($response);
                die();
            }

            if (isset($_POST['setChef']) && !empty($_POST['setChef'])) {
                $chef = explode(',', $_POST['chef']);
                $userId = $_POST['userId'];
                $role = $_POST['role'];

                $deleteUser = $this->db2->query("DELETE FROM UsersRoleDaily WHERE RUserId = $userId");
                
                $result = true;

                foreach ($chef as $KitCd) {
                    $usersRoleDailyObj['RUserId'] = $userId;
                    // $usersRoleDailyObj['RoleType'] = $role;
                    $usersRoleDailyObj['KitCd'] = $KitCd;
                    $this->db2->insert('UsersRoleDaily', $usersRoleDailyObj);
                     $iddd = $this->db2->insert_id();
                    if(empty($iddd)){
                        $result = false;
                    }
                }

                if($result){
                    $response = [
                        "status" => 1,
                        "msg" => "role assigned"
                    ];
                }else{
                    $response = [
                        "status" => 0,
                        "msg" => "Failed to insert in UsersRoleDaily"
                    ];
                }

                echo json_encode($response);
                die();
            }

            if (isset($_POST['getUserRole']) && !empty($_POST['getUserRole'])) {
                $userId = $_POST['userId'];

                $checkUser = $usersRoleDailyObj->search(["RUserId"=>$userId]);
                $checkUser = $this->db2->get_where('UsersRoleDaily', array('RUserId'=>$userId))->result_array();

                if (!empty($checkUser)) {
                    $response = [
                        "status" => 1,
                        "userData" => $checkUser
                    ];
                }else{
                    $response = [
                        "status" => 0,
                        "userData" => "User has no role"
                    ];
                }

                echo json_encode($response);
                die();
            }

            if (isset($_POST['getUsers']) && !empty($_POST['getUsers'])) {
                $userData = $this->db2->query("SELECT ur.RUserId, ur.FName, ur.LName, IF(ISNULL(urd.DNo), 0, urd.DNo) as DNo , urd.KitCd, urd.DCd, urd.CCd FROM UsersRest ur LEFT JOIN UsersRoleDaily urd ON ur.RUserId = urd.RUserId WHERE ur.DeputedEID = $EID AND ur.UTyp = 1 AND ur.Stat = 0 Order By ur.FName, ur.LName")->result_array();

                if(!empty($userData)) {

                    foreach ($userData as $key => $value) {
                        if ($userData[$key]['CCd'] == '') {
                            $userData[$key]['CCd'] = [];
                        }else {
                            $userData[$key]['CCd'] = explode(',', $value['CCd']);
                        }

                        if ($userData[$key]['KitCd'] == '') {
                            $userData[$key]['KitCd'] = [];
                        }else {
                            $userData[$key]['KitCd'] = explode(',', $value['KitCd']);
                        }

                        if ($userData[$key]['DCd'] == '') {
                            $userData[$key]['DCd'] = [];
                        }else {
                            $userData[$key]['DCd'] = explode(',', $value['DCd']);
                        }
                    }

                    // print_r($userData);exit();

                    $response = [
                        "status" => 1,
                        "userData" => $userData
                    ];
                }else {
                    $response = [
                        "status" => 0,
                        "msg" => "no user found"
                    ];
                }

                echo json_encode($response);
                die();
            }
        }

    }

    public function offers_list(){
		 $data['title'] = 'Offers List';
		 $data['offers'] = $this->rest->getOffersList();
		 $this->load->view('rest/offer_lists',$data);
    }

    public function new_offer(){
        $EID = authuser()->EID;
        $data['EID'] = $EID;
    	$data['title'] = 'Add New Offer';
        $data['sch_typ'] = array('1'=>'BillBased', '21'=>'CID based', '22'=>'MenuCatg based', '23'=>'ItmTyp Based', '24'=>'ItemID based', '25'=>'Itm_Portion based','26'=>'CID and Itm_Portion based', '27'=>'MenuCatg and Itm_Portion based', '28'=>'ItemTyp and Itm_Portion based','29'=>'ItemID and Itm_Portion based');
        $data['sch_cat'] = array('1'=>'Bill Discount', '2'=>'Free Item with BillAmt','3'=>'Discount on minBillAmt/month', '4'=>'First time use of QS (2% discount)', '5'=> 'Rating Discount', '21'=>'Gen. Discount', '22'=>'Buy x get y free (1+1) / (2+1) lowest rate', '23'=>'Buy x get y free (1+1) / (2+1) highest rate', '24'=>'Buy x get y discounted; 51-Discounts using promo codes');
        $data['days']=array(1=>'Sunday', 2=>'Monday', 3=>'Tuesday', 4=>'Wednesday', 5=>'Thursday', 6=>'Friday', 7=>'Saturday');

        $data['cuisines'] = $this->db2->query("SELECT c.* from Cuisines as c, EatCuisine as ec where ec.EID = ".$EID." and ec.CID=c.CID and ec.stat = 0")->result_array();
        $data['item_types'] = $this->db2->get('ItemTypes')->result_array();

		$this->load->view('rest/add_new_offer',$data);	
    }

    public function offer_ajax(){
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;

        if (isset($_POST['getItem'])) {
            extract($_POST);
            $MenuItem = $this->db2->query("SELECT ItemId , ItemNm,`Value` FROM `MenuItem` WHERE ItemNm LIKE '$item%' and EID=$EID")->result_array();
            
            print_r(json_encode($MenuItem));
            exit;
        }

        if (isset($_POST['addOffer'])) {
            extract($_POST);
            echo "<pre>";
            
            $CustOffers['SchNm'] = $SchNm;
            $CustOffers['EID'] = $EID;
            $CustOffers['ChainId'] = $ChainId;
            $CustOffers['SchTyp'] = $SchTyp;
            $CustOffers['SchCatg'] = $SchCatg;
            $CustOffers['FrmDayNo'] = $FromDayNo;
            $CustOffers['ToDayNo'] = $ToDayNo;
            $CustOffers['FrmTime'] = $FrmTime;
            $CustOffers['ToTime'] = $ToTime;
            $CustOffers['AltFrmTime'] = $AltFrmTime;
            $CustOffers['AltToTime'] = $AltToTime;
            // $CustOffers['Remarks'] = '';
            $CustOffers['FrmDt'] = $FrmDt; 
            $CustOffers['ToDt'] = $ToDt;
            $CustOffers['LoginCd'] = 0;
            
            $SchCd = insertRecord('CustOffers', $CustOffers);
            $updat['PromoCode'] = $SchCd.'~'.$EID.'~'.$ChainId.'~'.$SchTyp.'~'.$SchCatg;
            updateRecord('CustOffers', $updat, array('SchCd' => $SchCd));

            $CustOffersDetObj['SchCd'] = $SchCd;
            $CustOffersDetObj['SchDesc'] = $description[0];
            $CustOffersDetObj['SchImg'] = '';
            $CustOffersDetObj['CID'] = $description_cid[0];
            $CustOffersDetObj['MCatgId'] = $description_mcatgid[0];
            $CustOffersDetObj['Rank'] = 1;
            $CustOffersDetObj['ItemTyp'] = $description_itemtyp[0];
            $CustOffersDetObj['ItemId'] = $description_item[0];
            $CustOffersDetObj['IPCd'] = $description_itemportion[0];
            $CustOffersDetObj['Qty']= $description_quantity[0];
            $CustOffersDetObj['Disc_ItemId'] = $description_discountitem[0];
            $CustOffersDetObj['Disc_Qty'] = $description_discountquantity[0];
            $CustOffersDetObj['Disc_IPCd']= $description_discountitemportion[0];
            // $CustOffersDetObj['Remarks'] = '';
            $CustOffersDetObj['MinBillAmt'] = $description_minbillamount[0];
            $CustOffersDetObj['Disc_pcent'] = $description_discountpercent[0];
            $CustOffersDetObj['Disc_Amt'] = $description_discountamount[0];

            if(isset($_FILES['description_image']['name']) && !empty($_FILES['description_image']['name'])){ 

                $files = $_FILES['description_image'];

                $_FILES['description_image']['name']= $files['name'];
                $_FILES['description_image']['type']= $files['type'];
                $_FILES['description_image']['tmp_name']= $files['tmp_name'];
                $_FILES['description_image']['error']= $files['error'];
                $_FILES['description_image']['size']= $files['size'];
                $file = str_replace(' ', '_', rand('10000','999').'_'.$files['name']);

                $res = do_upload('description_image',$file,'uploads/offers','*');
                $CustOffersDetObj['SchImg'] = $file;
              }
              insertRecord('CustOffersDet', $CustOffersDetObj);
            $this->session->set_flashdata('success','Offer has been added.'); 
            redirect(base_url('restorent/offers_list'));
        }

        if (isset($_POST['updateOffer'])) {
            
            $SchCd = $_POST['SchCd'];
            $CustOffers['SchNm'] = $_POST['SchNm'];
            $CustOffers['SchTyp'] = $_POST['SchTyp'];
            $CustOffers['SchCatg'] = $_POST['SchCatg']; 
            $CustOffers['FrmDayNo'] = $_POST['FromDayNo'];
            $CustOffers['ToDayNo'] = $_POST['ToDayNo']; 
            $CustOffers['FrmTime'] = $_POST['FrmTime'];
            $CustOffers['ToTime'] = $_POST['ToTime'];
            $CustOffers['AltFrmTime'] = $_POST['AltFrmTime'];
            $CustOffers['AltToTime'] = $_POST['AltToTime'];
            $CustOffers['FrmDt'] = $_POST['FrmDt'];
            $CustOffers['ToDt'] = $_POST['ToDt'];
            $CustOffers['EID'] = authuser()->EID;
            updateRecord('CustOffers',$CustOffers, array('SchCd' => $SchCd, 'EID' => authuser()->EID));
            
            for($i = 0;$i<sizeof($_POST['description']);$i++){
                if(!empty($_POST['description'][$i])){
                    if(isset($_FILES['description_image']['name']) && !empty($_FILES['description_image']['name'])){ 

                        $files = $_FILES['description_image'];
                        $_FILES['description_image']['name']= $files['name'][$i];
                        $_FILES['description_image']['type']= $files['type'][$i];
                        $_FILES['description_image']['tmp_name']= $files['tmp_name'][$i];
                        $_FILES['description_image']['error']= $files['error'][$i];
                        $_FILES['description_image']['size']= $files['size'][$i];
                        $file = str_replace(' ', '_', rand('10000','999').'_'.$files['name'][$i]);

                        $res = do_upload('description_image',$file,'uploads/offers','*');
                        $CustOffersDet['SchImg'] = $file;
                    }

                    $CustOffersDet['SchCd'] = $SchCd;
                    $CustOffersDet['SchDesc'] = $_POST['description'][$i];
                    $CustOffersDet['CID'] = $_POST['description_cid'][$i];
                    $CustOffersDet['MCatgId'] = $_POST['description_mcatgid'][$i];
                    $CustOffersDet['ItemTyp'] = $_POST['description_itemtyp'][$i];
                    $CustOffersDet['ItemId'] = $_POST['description_item'][$i];
                    $CustOffersDet['IPCd'] = $_POST['description_itemportion'][$i];
                    $CustOffersDet['ItemTyp'] = $_POST['description_itemtyp'][$i];
                    $CustOffersDet['Qty'] = $_POST['description_quantity'][$i];
                    $CustOffersDet['Disc_ItemId'] = $_POST['description_discountitem'][$i];
                    $CustOffersDet['Disc_IPCd'] = $_POST['description_discountitemportion'][$i];
                    $CustOffersDet['Disc_Qty'] = $_POST['description_discountquantity'][$i];
                    $CustOffersDet['MinBillAmt'] = $_POST['description_minbillamount'][$i];
                    $CustOffersDet['Disc_pcent'] = $_POST['description_discountpercent'][$i];
                    $CustOffersDet['Disc_Amt'] = $_POST['description_discountamount'][$i];
                    $CustOffersDet['Rank'] = 1;
                    $CustOffersDet['Stat'] = 0;

                    if(isset($_POST['SDetCd'][$i]) && !empty($_POST['SDetCd'][$i])){
                        $SDetCd = $_POST['SDetCd'][$i];
                        updateRecord('CustOffersDet',$CustOffersDet, array('SDetCd' => $SDetCd));
                    }else{
                        recordInsert('CustOffersDet', $CustOffersDet);
                    }
                }
            }
            $this->session->set_flashdata('success','Offer has been updated.'); 
            redirect(base_url('restorent/offers_list'));
        }
        if(isset($_POST['getCategory']) && $_POST['getCategory']){
            $cid = $_POST['cid'];
            
            $categories = $this->db2->get_where('MenuCatg', array('CID' => $cid))->result_array();
            // echo "<pre>";print_r($CustOffers);exit();
            echo json_encode($categories);
        }
        if(isset($_POST['getItems']) && $_POST['getItems']){
            $mcatgid = $_POST['mcatgid'];
            $items = $this->db2->get_where('MenuItem', array('MCatgId' => $mcatgid))->result_array();
            // echo "<pre>";print_r($CustOffers);exit();
            echo json_encode($items);
        }
        if(isset($_POST['getItemPortion']) && $_POST['getItemPortion']){
            $item_id = $_POST['item_id'];
            $q= "SELECT ip.* from ItemPortions as ip, MenuItemRates as mir where mir.Itm_Portion = ip.IPCd and mir.ItemId = ".$item_id;
            $portions = $this->db2->query($q)->result_array();;
            // echo "<pre>";print_r($CustOffers);exit();
            echo json_encode($portions);
        }
        if(isset($_POST['delete_offer']) && $_POST['delete_offer']){
            $SchCd = $_POST['SchCd'];
            $q1 = "DELETE from CustOffersDet where SchCd = ".$SchCd;
            $this->db2->query($q1);
            $q2 = "DELETE from CustOffers where SchCd = ".$SchCd;
            $this->db2->query($q2);
            echo 1;
        }
        if(isset($_POST['delete_offer_description']) && $_POST['delete_offer_description']){
            $SDetCd = $_POST['SDetCd'];
            $q1 = "DELETE from CustOffersDet where SDetCd = ".$SDetCd;
            $this->db2->query($q1);
            echo 1;
        }

    }

    public function edit_offer($SchCd){
        $data['title'] = 'Edit Offer';
        $data['sch_typ'] = array('1'=>'BillBased', '21'=>'CID based', '22'=>'MenuCatg based', '23'=>'ItmTyp Based', '24'=>'ItemID based', '25'=>'Itm_Portion based','26'=>'CID and Itm_Portion based', '27'=>'MenuCatg and Itm_Portion based', '28'=>'ItemTyp and Itm_Portion based','29'=>'ItemID and Itm_Portion based');
        $data['sch_cat'] = array('1'=>'Bill Discount', '2'=>'Free Item with BillAmt','3'=>'Discount on minBillAmt/month', '4'=>'First time use of QS (2% discount)', '5'=> 'Rating Discount', '21'=>'Gen. Discount', '22'=>'Buy x get y free (1+1) / (2+1) lowest rate', '23'=>'Buy x get y free (1+1) / (2+1) highest rate', '24'=>'Buy x get y discounted; 51-Discounts using promo codes');
        $data['days']=array(1=>'Sunday', 2=>'Monday', 3=>'Tuesday', 4=>'Wednesday', 5=>'Thursday', 6=>'Friday', 7=>'Saturday');

        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['SchCd'] = $SchCd;
        $data['cuisines'] = $this->db2->query("SELECT c.* from Cuisines as c, EatCuisine as ec where ec.EID = ".$EID." and ec.CID=c.CID and ec.stat = 0")->result_array();
        $data['item_types'] = $this->db2->get('ItemTypes')->result_array();
        $data['scheme'] = $this->db2->get_where('CustOffers', array('SchCd' => $SchCd))->result_array();
        $data['descriptions'] = $this->db2->get_where('CustOffersDet', array('SchCd' =>$SchCd))->result_array();

        // echo "<pre>";
        // print_r($data);
        // die;

        $this->load->view('rest/offer_edit',$data);  
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
        $q = "SELECT mi.ItemId, mi.ItemNm, ip.Name as Portions, md.ItemId as deactive FROM ItemPortions ip, MenuItem mi, MenuItemRates mr LEFT JOIN MenuItem_Disabled md ON (md.ItemId = mr.ItemId) and (mr.Itm_Portion = md.IPCd) Where mi.ItemId = mr.ItemId and ip.IPCd=mr.Itm_Portion and mr.EID = $EID and mr.ChainId = $ChainId and mi.Stat=0";
        if($this->input->method(true)=='POST'){
            if($_POST){
                if(isset($_POST['cuisine']) && !empty($_POST['cuisine'])){
                    $CID = $_POST['cuisine'];
                    $c = $_POST['cuisine'];
                    $q.=" and mi.CID = '$c'";
                    $data['menucat'] = $this->db2->query("SELECT * from MenuCatg where CID = '$CID'")->result_array();
                    $data['CID'] = $CID;
                }
                if(isset($_POST['menucat']) && !empty($_POST['menucat'])){
                    $catid = $_POST['menucat'];
                    $m = $_POST['menucat'];
                    $q.=" and mi.MCatgId = '$m'";
                    $data['catid'] = $catid;
                }
            }
        }
        $q.=" GROUP by mi.ItemId, ip.Name, md.ItemId order by mi.MCatgId, mi.ItemId, ip.Name, mi.Rank";
        $data['menuItemData'] = $this->db2->query($q)->result_array();
        
    	$data['title'] = 'Item Details';
        // echo "<pre>";
        // print_r($data);
        // die;
		$this->load->view('rest/item_lists',$data);	
    }

    public function rest_item_list(){

        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;

        if (isset($_POST['insertMenuItemDisabled']) && !empty($_POST['insertMenuItemDisabled'])) {
            $id = $_POST['id'];

            $menuItemDisabledObj['ItemId'] = $id;
            $menuItemDisabledObj['EID'] = $EID;
            $menuItemDisabledObj['ChainId'] = $ChainId;

            $idd = insertRecord('MenuItem_Disabled', $menuItemDisabledObj);
            if($idd){
                $response = [
                    "status" => 1,
                    "msg" => "Item Disabled"
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "Failed to Insert Item"
                ];
            }
            echo json_encode($response);
            die();
        }

        if ($_POST['deleteMenuItemDisabled']) {
            $id = $_POST['id'];

            $deleteData = $this->db2->query("DELETE FROM MenuItem_Disabled WHERE ItemId = $id and stat=0 AND EID = $EID AND ChainId = $ChainId");
            if($deleteData) {
                $response = [
                    "status" => 1,
                    "msg" => "Item Enabled"
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "Failed to Delete Item"
                ];
            }
            echo json_encode($response);
            die();
        }
    }

    public function item_list_get_category(){
        if($_POST){
            $cuisine = $_POST['CID'];
            $data = $this->db2->query("SELECT * from MenuCatg where CID = '$cuisine'")->result_array();
            echo json_encode($data);
        }
    }

    public function order_dispense(){
        $data['RestName'] = authuser()->RestName;
        $data['RUserId'] = authuser()->RUserId;
        $data['Cash'] = $this->session->userdata('Cash');
        $data['EType'] = $this->session->userdata('EType');
        $Fest = $this->session->userdata('Fest');
        $data['CheckOTP'] = $this->session->userdata('DeliveryOTP');
        $data['EID'] = authuser()->EID;
    	$data['title'] = 'Order Dispense';
        $data['DispenseAccess'] = $this->rest->getDispenseAccess();

		$this->load->view('rest/dispense_orders',$data);	
    }

    public function order_delivery(){
        $Fest = $this->session->userdata('Fest');
        // echo "<pre>";
        // print_r($_POST);
        // die;
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


            if (isset($_POST['getOrderDetails']) && !empty($_POST['getOrderDetails'])) {
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

            if (isset($_POST['getOrderList']) && !empty($_POST['getOrderList'])) {
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
                $orderList = $this->db2->query($q)->result_array();
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
                    $kitcheData = $this->db2->query("SELECT SUM(m.Value*k.Qty) as OrdAmt, k.KOTNo, k.UKOTNo, date(k.LstModDt) as OrdDt, c.CGSTRate, c.SGSTRate, c.GSTInclusiveRates, c.ServChrg from Kitchen k, MenuItem m, Config c where c.EID=k.EID and k.ItemId=m.ItemId and (k.Stat<>4 and k.Stat<>6 and k.stat<>7 and k.Stat<>9 and k.Stat<>99) AND k.CNo=".$CNo." and k.EID=$EID AND k.BillStat=0 group by k.UKOTNo, k.KOTNo, date(k.LstModDt), c.CGSTRate, c.SGSTRate, c.GSTInclusiveRates, c.ServChrg order by date(k.LstModDt)Asc, KotNo Asc", ["CNo" => $CNo])->result_array();

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

    public function sentNotification(){
        echo "<pre>";
        print_r($_GET);
        die;
        // this function is not completed
        $servername = "139.59.28.122";
        $username = "root";
        $password = "sn9750";
        $dbname = "GenTableData";

        if ($_GET['CustId'] != null) {
          $CustId = $_GET['CustId'];
          $message = $_GET['message'];
          $title = $_GET['title'];
          $flag = $_GET['flag'];
          $billno = $_GET['billno'];

          $userToken = $this->db2->query("SELECT token FROM Users where CustId = $CustId")->result_array();

          if (empty($userToken)) {
            $response = [
              "status" => 0,
              "msg" => "Invalid User"
            ];

            echo json_encode($response);
            die();
          } else {
            $token = $userToken[0]['token'];
            $fcmRegIds = array();
            array_push($fcmRegIds, $token);

            define('API_ACCESS_KEY', 'AAAAZEeZrX8:APA91bHgs5fs23mXqClnQ8-xPTNIg9We1-0nFfWGEi5DQmbs2HRzC0d9MneblYbR1WLNCVR9PYX86Qx6NBZUedIq3lyQ_jYyjRdkOCrk56P_eD26bmGIuk78VbX4ZxrdFKDeiHaJXTcm');
            if ($flag != 0) {

              // insert notification to database
              $sql = mysqli_query($conn, "INSERT INTO `Notification` (`user_id`, `title`, `message`,`billno`)VALUES ($CustId, '$title', '$message',$billno)");
            } else if ($flag == 0) {
              // sql to delete a record
              $sql = mysqli_query($conn, "DELETE FROM `Notification` WHERE `user_id`=$CustId AND `billno` = $billno");
            }

            $msg = array(
              'body'   => $message,
              'title'   => $title,
              // 'click_action'   => $request->input('notification_url'),
              'vibrate' => 1,
              'sound'   => 1
              // 'icon' => $request['n_icon']
            );

            $fields = array(
              'registration_ids'  => $fcmRegIds,
              'notification'      => $msg
            );

            $headers = array(
              'Authorization: key=' . API_ACCESS_KEY,
              'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            // $result = curl_exec($ch);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
              die();
            }
          }
        } else {
          $response = [
            "status" => 0,
            "msg" => "Send User ID"
          ];

          echo json_encode($response);
          die();
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

    public function cash_bill(){
        // tempary solution
        $_SESSION['DynamicDB'] = $this->session->userdata('my_db');
        // [DynamicDB] => 51e
        $EID = authuser()->EID;
        $EType = $this->session->userdata('EType');
        $RUserId = authuser()->RUserId;
        $data['RUserId'] = $RUserId;
        $data['EID'] = $EID;
        $data['EType'] = $EType;

        $billData = $this->db2->query("SELECT BillId,BillNo,PaymtMode, DATE(PymtTime) as BillDate, TotAmt FROM Billing WHERE PaidAmt = 0 AND EID = $EID AND PaymtMode = 'Cash'")->result_array();

        $GetDCD = $this->db2->query("SELECT CCd FROM `UsersRoleDaily` WHERE RUserId = $RUserId")->result_array();
        $tempArray =explode(",",$GetDCD[0]['CCd']);
        $SqlQueryVar = "SELECT CCd, Name FROM Eat_Casher Where EID = $EID AND Stat = 0";
        if(count($tempArray) >=1 && $tempArray[0] != ''){
            $SqlQueryVar .=" AND (";
        for ($i=0; $i <count($tempArray) ; $i++) { 
            if($i>=1){
                $SqlQueryVar .=" OR ";
            }
            $SqlQueryVar .= "CCd =".$tempArray[$i];
            
        }
        $SqlQueryVar .= ")";
        }else{

        }
        // print_r($SqlQueryVar);exit;
        $data['SettingTableViewAccess'] = $this->db2->query($SqlQueryVar)->result_array();


        $data['title'] = 'Bill Settlement';
        // echo "<pre>";
        // print_r($data);exit();
        $this->load->view('rest/bill_settle',$data);
    }

    public function cash_bill_ajax(){
        $RUserId = authuser()->RUserId;
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');

        if(isset($_POST['selectpaymentopt']) && $_POST['selectpaymentopt']){
        $pymtModes = $this->db2->query("SELECT PMNo, Name FROM `PymtModes`WHERE Stat = 0 AND Country = 'India' AND PMNo NOT IN (SELECT PMNo FROM PymtMode_Eat_Disable WHERE EID = $EID)")->result_array();

            $response = [
                "status" => 1,
                "pymntModes" => $pymtModes
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['getBill'])) {
            $STVcd = $_POST['STVcd'];

            if ($EType == 5) {
                $q = "SELECT b.TableNo, BillId, BillNo, DATE_FORMAT(DATE(PymtTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue, PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u ,Eat_tables et WHERE b.CustId=u.CustId AND b.EID = et.EID AND et.CCd = $STVcd AND b.EID = $EID AND b.Stat = 0 AND  b.TableNo = et.TableNo";
                if(isset($_POST['BillId']) && $_POST['BillId'] > 0){
                    $bid = $_POST['BillId'];
                    $q.=" and b.BillId = '$bid'";
                }
                $q.=" Order By BillId Asc";
                $billData = $this->db2->query($q)->result_array();
                // print_r($billData);
                // exit;
            }else {
                $q = "SELECT TableNo, BillId, BillNo, DATE_FORMAT(DATE(PymtTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue , PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u WHERE b.PaymtMode = 'Cash' AND b.CustId=u.CustId AND EID = $EID AND b.Stat = 0";
                if(isset($_POST['BillId']) && $_POST['BillId'] > 0){
                    $bid = $_POST['BillId'];
                    $q.=" and b.BillId = '$bid'";
                }
                $q.=" Order By BillId Asc";
                $billData = $this->db2->query($q)->result_array();
            }

            // for($i=0;$i<count($billData);$i)
            // $temp = 0;

            if (!empty($billData)) {
                $response = [
                    "status" => 1,
                    "billData" => $billData,
                    // "PymtModes"=>$PymtModesObj
                    "PymtModes"=> array()
                    
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "No Bill Found"
                ];
            }

            echo json_encode($response);
            die();
        }

        if ($_POST['setPaidAmount']) {
            extract($_POST);
            $paidAmount = $_POST['paidAmount'];
            $id = $_POST['id'];
            $mode = $_POST['mode'];
            $cNo = $_POST['CNo'];
            $q1 = "UPDATE Billing SET PaidAmt = $paidAmount, PaymtMode = '".$mode."', Stat = 1  WHERE BillId = $id AND EID = $EID";
            // print_r($q1);
            $billData = $this->db2->query($q1);

            if($EType == 1){
                $stat = 1;
                //Session::set('KOTNo',0);
                $q2 = "UPDATE Kitchen k, KitchenMain km SET  k.Stat = $stat, k.payRest=1, km.payRest=1 WHERE km.BillStat = $id AND (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
                // print_r($q2);
                $kitchenUpdate = $this->db2->query($q2);
            }

            if ($EType == 5) {

                $stat = 9;

                $q3 = "DELETE from Eat_tables_Occ where EID=$EID and CNo = $cNo AND ((TableNo = '$TableNo' AND CustId = $CustId) OR (MergeNo = '$TableNo'))";
                $deleteETO = $this->db2->query($q3);

                $q4 = "UPDATE Eat_tables SET Stat = 0 WHERE EID = $EID AND ((TableNo = '$TableNo') OR (MergeNo = '$TableNo'))";
                $updateEatTable = $this->db2->query($q4);
                
            }

            $q2 = "UPDATE Kitchen k, KitchenMain km SET  k.Stat = $stat, k.payRest=1, km.payRest=1 WHERE km.BillStat = $id AND (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
                // print_r($q2);
            $kitchenUpdate = $this->db2->query($q2);

            // store to gen db
            $custPymtOb['BillId'] = $id;
            $custPymtOb['BillNo'] = $billNo;
            $custPymtOb['EID'] = $EID;
            $custPymtOb['PaidAmt'] = $billAmt;
            $custPymtOb['PaymtMode'] = $pymtMode;
            // $custPymtObj->create();
            $this->db->insert('CustPymts', $custPymtOb);

            $response = [
                "status" => 1,
                "msg" => "Billing Updated"
            ];

            echo json_encode($response);
            die();
        }

        if(isset($_POST['rejectBill'])) {
            $id = $_POST['id'];
            $cNo = $_POST['CNo'];
            $tableNo = $_POST['TableNo'];
            $custId = $_POST['CustId'];

            $billData = $this->db2->query("UPDATE Billing SET Stat = 99 WHERE BillId = $id AND EID = $EID");

            $kitchenUpdate = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET  k.Stat = 99 WHERE km.BillStat = $id AND (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)");


            $kitchenMainUpdate = $this->db2->query("UPDATE KitchenMain SET Stat = 99 WHERE BillStat = $id AND EID = $EID and (CNo = $cNo OR MCNo = $cNo)");

            if($EType == 5) {
                $deleteETO = $this->db2->query("DELETE from Eat_tables_Occ eto, KitchenMain km where eto.TableNo= km.TableNo and eto.CustId=km.CustId and eto.EID=km.EID AND km.BillStat = $id AND km.EID = $EID and km.CNo = $cNo");

                $updateEatTable = $this->db2->query("UPDATE Eat_tables et, KitchenMain km SET Stat = 0 WHERE et.TableNo= km.TableNo and et.EID=km.EID AND km.BillStat = $id AND km.EID = $EID");
            }

            $response = [
                "status" => 1,
                "msg" => "Billing Rejected"
            ];

            echo json_encode($response);
            die();
        }

    }

    public function bill_rcpt(){
        $data['title'] = 'Bill Receipt';
        if(isset($_GET['restaurant'])){
            $data['restaurant'] = $_GET['restaurant'];
        }
        if(isset($_GET['billId'])){
            $data['billId'] = $_GET['billId'];
        }
        $data['CustId'] = $this->session->userdata('CustId');
        //cumment by me
        // if ($CustId == '') {
        //     header('Location: index.php');
        // }
        $data['EID'] = authuser()->EID;
        if(isset($_GET['EID']) && !empty($_GET['EID'])){
            $data['EID'] = $_GET['EID'];
        }
        $data['dbname'] = isset($_GET['dbn'])?$_GET['dbn'].".":"";
        $data['ChainId'] = authuser()->ChainId;
        $data['OutletId'] = $this->session->userdata('OutletId');
        $data['EType'] = $this->session->userdata('EType');
        //$Stall = Session::get('Stall');
        //$Ops = Session::get('Ops');
        //$ServChrg = Session::get('ServChrg');
        $data['Tips'] = $this->session->userdata('Tips');
        $data['PymtOpt'] = $this->session->userdata('PymtOpt');
        $data['KOTNo'] = $this->session->userdata('KOTNo');
        $data['TableNo'] = $this->session->userdata('TableNo');
        $data['COrgId'] = $this->session->userdata('COrgId');
        $data['CustNo'] = $this->session->userdata('CustNo');
        $data['CellNo'] = $this->session->userdata('CellNo');
        $data['CNo'] = $this->session->userdata('CNo');

        //menu link
        $data['cId'] = $this->session->userdata('cId');
        $data['mCatgId'] = $this->session->userdata('mCatgId');
        $data['cType'] =$this->session->userdata('cType'); 

        $data['link'] = "https://qs.vtrend.org/item_details.php?cId=" . $data['cId'] . "&mCatgId=" . $data['mCatgId'] . "&cType=" . $data['cType'];

        $billData = getBillData($data['dbname'], $data['EID'], $data['billId']); 

        $data['hotelName'] = $billData[0]['Name'];
        $data['phone'] = $billData[0]['PhoneNos'];
        $data['gstno'] = $billData[0]['GSTno'];
        $data['fssaino'] = $billData[0]['FSSAINo'];
        $data['cinno'] = $billData[0]['CINNo'];
        $data['billno'] = $billData[0]['BillNo'];
        $data['orderdate'] = $billData[0]['BillDt'];
        $data['date'] = date('Y-m-d H:i:s',strtotime($data['orderdate']));
        // $data['dateOfBill'] = $date->format('d-m-Y @ H:i');
        $data['dateOfBill'] = $data['date'];
        $data['address'] = $billData[0]['Addr'];
        $data['pincode'] = $billData[0]['Pincode'];
        $data['city'] = $billData[0]['City'];
        $data['totamt'] = $billData[0]['TotAmt'];
        $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
        $data['bservecharge'] = $billData[0]['bservecharge'];
        $data['tipamt'] = $billData[0]['Tip'];
        $data['Stat'] = $billData[0]['Stat'];
        $data['total'] = 0;
        $data['sgstamt']=0;
        $data['cgstamt']=0;
        $data['grandTotal'] = $data['sgstamt'] + $data['cgstamt'] + $data['bservecharge'] + $data['tipamt'];
        $data['thankuline'] = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

        $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'];
        $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
        $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

        $res = $this->rest->getBillBody($billData, $data['EID'], $data['billId']);
        $data['billBody'] = $res[0];
        $data['itemTotal'] = $res[1];
        // echo "<pre>";
        // print_r($data);
        // die;
        
        $this->load->view('rest/receipt',$data);
    }

    public function sitting_table(){
        $_SESSION['DynamicDB'] = $this->session->userdata('my_db');
        $data['TableAcceptReqd'] = $this->session->userdata('TableAcceptReqd');
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['EType'] = $this->session->userdata('EType');
        $data['Kitchen'] = $this->session->userdata('Kitchen');
        $data['SettingTableViewAccess'] = $this->db2->query("SELECT CCd,Name,Settle FROM `Eat_Casher`WHERE EID=$EID and Stat =0")->result_array();

        $data['captured_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 1 and EID = ".$EID)->result_array();
        $data['available_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 0 and EID = ".$EID)->result_array();
        $data['title'] = 'Table View';
        // echo "<pre>";
        // print_r($data);exit();
        $this->load->view('rest/table_sitting',$data);   
    }

    public function sittin_table_view_ajax(){
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        if (isset($_POST['getTableOrderDetails']) && $_POST['getTableOrderDetails']) {

            // print_r($_POST);
            // exit;

            if (isset($_POST['STVCd'])) {
                $STVCd = $_POST['STVCd'];
            } else {
                $STVCd = 0;
            }
            
            $kitchenData = $this->db2->query("SELECT (SUM(k.Qty) - SUM(k.DQty)) as AllDelivered, SUM(k.AQty) as AnyAssigned,km.CNo, km.CustId,  SUM(k.ItmRate * k.Qty) as Amt,  IF((SELECT MIN(k1.KOTPrintNo) FROM Kitchen k1 WHERE k1.KOTPrintNo = 1 AND (km.CNo = k1.CNo OR km.MCNo = k1.CNo)  AND k1.MergeNo = km.MergeNo AND k1.EID = km.EID AND ec.EID = km.EID GROUP BY k1.MergeNo, km.EID)=1,0,1) AS NEW_KOT, time(km.LstModDt) as StTime,   km.MergeNo, km.MCNo, km.BillStat, km.EID, km.CNo, km.CellNo, km.TableNo, ec.CCd, ec.Name,b.BillId,b.BillNo,b.TotAmt,b.PaymtMode FROM Kitchen k,  MenuItem i , Eat_tables et, Eat_Casher ec, KitchenMain km left outer join Billing b on b.BillId = km.BillStat WHERE (km.CNo = k.CNo OR km.MCNo = k.CNo) And  et.TableNo = km.TableNo AND k.ItemId = i.ItemId  AND et.EID = km.EID AND km.payRest=0 AND (k.Stat <> 4 and k.Stat <> 6 AND k.Stat <> 7 AND  k.Stat <> 99) AND (k.OType = 7 OR k.OType = 8) AND et.CCd = ec.CCd AND ec.CCd = $STVCd  AND k.EID = km.EID AND k.MergeNo = km.MergeNo AND km.EID = 51 GROUP BY km.CNo,  km.Mergeno, km.MCNo order by MergeNo, km.LstModDt")->result_array();
            // print_r($kitchenData);echo "<br>";print_r($q);exit();
            if (empty($kitchenData)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Tables Occupied"
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
        if(isset($_POST['getKitchenData']) && $_POST['getKitchenData']){
            $f = $_POST['FKOTNo'];
            $c = $_POST['CNo'];
            $data = $this->db2->query("SELECT k.FKOTNo, ek.KitName, k.UKOTNo FROM Eat_Kit ek, Kitchen k, KitchenMain km WHERE ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.Stat<>99 ) AND k.EID=km.EID AND k.CNo = km.CNo AND km.EID = $EID and (km.CNo = $c OR km.MCNo = $c) and k.FKOTNo = $f and k.MergeNo = km.MergeNo and ek.KitCd=k.KitCd and ek.EID=km.EID GROUP BY k.FKOTNo, ek.KitName, k.UKOTNo order by k.FKOTNo, ek.KitName, k.UKOTNo ASC")->result_array();
            $response['status'] = 1;
            $response['data'] = $data;
            echo json_encode($response);
            die();
        }
        if (isset($_POST['acceptTable']) && $_POST['acceptTable']) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];
            // update Eat_tables_Occ   - 14/1/19
            $updateETO = $this->db2->query("UPDATE Eat_tables_Occ eto, KitchenMain km SET eto.Stat = 1, eto.MergeNo = '$tableNo' WHERE eto.TableNo = '$tableNo' AND km.CNo = $CNo AND km.EID = eto.EID and km.CNo = eto.CNo AND km.EID = $EID");

            // update Kitchen   - 14/1/19
            $updateKitchen = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.Stat = 1 WHERE km.TableNo = '$tableNo' AND km.CNo = $CNo AND km.EID = k.EID and km.CNo = k.CNo  AND (k.Stat = 0 OR k.Stat = 10) AND km.EID = $EID");

            $response = [
                "status" => 1,
                "msg" => "Table Accepted"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['rejectTable']) && $_POST['rejectTable']) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];
            //To delete all merged tables for that customer  - 14/1/20
            $updateETO = $this->db2->query("DELETE FROM Eat_tables_Occ  where EID = $EID and MergeNo = (Select km.MergeNo from  KitchenMain km WHERE km.EID = $EID and (km.CNo = $CNo OR km.MCNo = $CNo)" );

            // update Kitchen - 14/1/20
            $updateKitchen = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.Stat = 4 WHERE k.EID = km.EID AND k.CNo = km.CNo AND (km.CNo = $CNo OR km.MCNo = $CNo)   AND km.EID = $EID");

            // update KitchenMAIN   - 14/1/20
            $kitchenMainUpdate = $this->db2->query("UPDATE KitchenMain SET Stat = 4 WHERE (km.CNo = $CNo OR km.MCNo = $CNo) AND EID = $EID");

            $response = [
                "status" => 1,
                "msg" => "Table Rejected"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['getKot']) && $_POST['getKot']) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];

            // get kot from kitchen for particular row  - 14/1/20 
            $kots = $this->db2->query("SELECT km.TableNo, FKOTNo, KOTNo, UKOTNo, KitCd, SUM(Qty) as Qty , KOTPrintNo, km.CellNo, km.CNo, km.MergeNo FROM Kitchen k, KitchenMain km WHERE ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.Stat<>99 ) AND k.EID=km.EID AND k.CNo = km.CNo  AND km.EID = $EID and (km.CNo = $CNo OR km.MCNo = $CNo)  and k.MergeNo = km.MergeNo GROUP BY FKOTNo, UKOTNo, KOTNo, KitCd, km.TableNo, km.CellNo, km.CNo, KOTPrintNo order by KOTNo DESC, UKOTNO DESC")->result_array();

            if (empty($kots)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Kot Founds"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "kots" => $kots
                ];
            }
            echo json_encode($response);
            die();
        }
        if (isset($_POST['getKot_data']) && $_POST['getKot_data']) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];

            // get kot from kitchen for particular row  - 14/1/20 
            $q = "SELECT km.TableNo, k.FKOTNo, k.KOTNo, k.KitCd, SUM(k.Qty) as Qty , k.KOTPrintNo, k.ItemId, i.ItemNm, SUM(k.Qty) as Qty, SUM(k.AQty) as AQty, SUM(k.DQty) as DQty,TIME_FORMAT(k.EDT, '%H:%i') as EDT, km.CellNo, km.CNo, km.MergeNo FROM Kitchen k, KitchenMain km, MenuItem i WHERE k.ItemId = i.ItemId AND ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.Stat<>99 ) AND k.EID=km.EID AND k.CNo = km.CNo AND km.EID = $EID and (km.CNo = $CNo OR km.MCNo = $CNo) and k.MergeNo = km.MergeNo GROUP BY k.FKOTNo, k.KOTNo, k.KitCd, k.KOTPrintNo, k.ItemId, k.EDT, km.TableNo, km.CellNo, km.CNo order by k.KOTNo, k.FKOTNo, i.ItemNm DESC";
            // print_r($q);exit();
            $kots = $this->db2->query($q)->result_array();
            
            if (empty($kots)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Kot Founds"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "kots" => $kots
                ];
            }
            echo json_encode($response);
            die();
        }
        if (isset($_POST['getAllItems'])) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];

            // get all Items details from kitchen for table  - 14/1/20
            $itemDetails = $this->db2->query("SELECT  k.ItemId, i.ItemNm, SUM(k.Qty) as Qty, SUM(k.AQty) as AQty, SUM(k.DQty) as DQty FROM Kitchen k, KitchenMain km, MenuItem i WHERE k.ItemId = i.ItemId AND k.EID = km.EID AND (k.Stat<>4 and k.Stat<>6 AND k.Stat<>7  AND k.Stat<>10   AND k.Stat<>99)  AND km.CNo = k.CNo AND km.EID = 51 AND (km.CNo = $CNo OR km.MCNo = $CNo) AND km.MergeNo = k.MergeNo  group by i.ItemId ORDER by i.ItemNm")->result_array();
            
            if (empty($itemDetails)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Items Founds"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "itemDetails" => $itemDetails
                ];
            }
            echo json_encode($response);
            die();
        }

        if (isset($_POST['getItemsByKot'])) {
            $uKotNo = $_POST['uKotNo'];
            $CNo = $_POST['cNo'];
            // get Items details from kitchen - 14/1/20
            $itemDetails = $this->db2->query("SELECT  k.ItemId, i.ItemNm, SUM(k.Qty) as Qty, SUM(k.AQty) as AQty, SUM(k.DQty) as DQty FROM Kitchen k, KitchenMain km, MenuItem i WHERE k.ItemId = i.ItemId AND k.EID = km.EID AND (k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.UKOTNo = '$uKotNo'  AND k.Stat<>10   AND k.Stat<>99)  AND km.CNo = k.CNo AND km.EID = $EID AND (km.CNo = $CNo OR km.MCNo = $CNo) AND km.MergeNo = k.MergeNo  group by i.ItemId ORDER by i.ItemNm")->result_array();

            if (empty($itemDetails)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Items Founds"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "itemDetails" => $itemDetails
                ];
            }
            echo json_encode($response);
            die();
        }

        if (isset($_POST['deliverOrder'])) {

            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $cNo = $_POST['cNo'];

            // update kitchen for updateKitchenDelivery

            $updateKitchenDelivery = $this->db2->query("UPDATE Kitchen SET Stat = IF((Qty = (DQty+AQty)),  5, 2), DQty = (DQty + AQty), AQty = 0 WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID");

            $response = [
                "status" => 1,
                "msg" => "Updated"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['handleReassign'])) {

            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $cNo = $_POST['cNo'];

            $fromReassignOrder = $this->db2->query("SELECT OrdNo, ItemId, CustRmks, Itm_Portion from Kitchen WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID")->result_array();

            $orderId = $fromReassignOrder[0]['OrdNo'];
            $itemId = $fromReassignOrder[0]['ItemId'];
            $custRemarks = $fromReassignOrder[0]['CustRmks'];
            $itemPortion = $fromReassignOrder[0]['Itm_Portion'];

            $kitchenData = $this->db2->query("SELECT OrdNo, (Qty - DQty) as PQty, TableNo from Kitchen where ItemId = :itemId AND CustRmks = :custRemarks AND Itm_Portion = :itemPortion AND (Qty - DQty) > 0 AND (Stat = 1 OR Stat = 2) AND OrdNo != :orderId AND EID = $EID", ["itemId" => $itemId, "custRemarks" => $custRemarks, "itemPortion" => $itemPortion, "orderId" => $orderId])->result_array();

            if (!empty($kitchenData)) {
                $response = [
                    "status" => 1,
                    "kitchenData" => $kitchenData
                ];
            } else {
                $response = [
                    "status" => 0,
                    "msg" => "No tables Found"
                ];
            }

            echo json_encode($response);
            die();
        }

        if (isset($_POST['reassignOrder'])) {

            $reassign = 1;

            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $assignToOrderId = $_POST['assignToOrderId'];
            $assignQty = $_POST['assignQty'];
            $cNo = $_POST['cNo'];

            // Reducing assign quantity in From Table
            $kitchenRemoveAllocate = $this->db2->query("UPDATE Kitchen set AQty = (AQty - $assignQty) WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID");

            // Assigning in To Table
            if ($reassign == 1) {
                $kitchenReassignOrder = $this->db2->query("UPDATE Kitchen set AQty = $assignQty, Stat = 2 where OrdNo = $assignToOrderId AND EID = $EID", ["assignQty" => $assignQty, "assignToOrderId" => $assignToOrderId]);
            } elseif ($reassign == 2) {
                $kitchenReassignOrder = $this->db2->query("UPDATE Kitchen set Stat = IF((DQty + $assignQty + AQty) < Qty, 2, 5), DQty = (DQty + $assignQty + AQty), AQty = 0 where OrdNo = $assignToOrderId AND EID = $EID", ["assignQty" => $assignQty, "assignToOrderId" => $assignToOrderId]);
            }

            $response = [
                "status" => 1,
                "msg" => "Update Success"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['checkStatForDecline'])) {
            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $cNo = $_POST['cNo'];

            // Check Item Stat For Decline
            $checkItemStat = $this->db2->query("SELECT SUM(k.Qty - (k.AQty + k.DQty)) as DeclineQty, k.ItemId, k.Stat, i.ItemNm From Kitchen k, MenuItem i WHERE i.ItemId = k.ItemId AND i.EID = k.EID AND k.ItemId = $itemId AND k.TableNo = '$tableNo' AND k.CustId = $custId AND k.EID = $EID AND k.Stat = 1 GROUP BY k.ItemId, k.Stat")->result_array();

            if (empty($checkItemStat)) {
                $response = [
                    "status" => 0,
                    "msg" => "Item can not be declined"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "checkItemStat" => $checkItemStat[0]
                ];
            }

            echo json_encode($response);
            die();
        }

        if (isset($_POST['declineItem'])) {
            // print_r($_POST);exit();
            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $declineReason = $_POST['declineReason'];
            $cNo = $_POST['cNo'];

            // update kitchen for Decline Item
            $declineItem = $this->db2->query("UPDATE Kitchen SET Stat = 6, DReason = $declineReason WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID AND Stat = 1");

            $kitTableItemCount = $this->db2->query("SELECT count(itemid) AS itmcnt FROM Kitchen WHERE custId = $custId AND TableNo ='$tableNo' AND EID =$EID AND Stat <> 4 AND Stat <> 6 AND Stat <> 7 AND Stat <> 0 AND Stat <> 10");

            if ($kitTableItemCount[0]['itmcnt'] == 0) {
                $res =  $this->db2->query("DELETE FROM Eat_tables_Occ WHERE CustId = $custId AND EID= $EID AND TableNo=  '$tableNo'");
                // print_r($res);
            }

            $itemName = $this->db2->query("SELECT ItemNm From MenuItem WHERE ItemId = $itemId")->result_array();

            $response = [
                "status" => 1,
                "msg" => $itemName[0]['ItemNm']
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['declineItemForEid'])) {
            $itemId = $_POST['itemId'];

            // update kitchen for Decline Item All Table
            $declineItemForEid = $this->db2->query("UPDATE Kitchen SET Stat = 6 WHERE ItemId = $itemId AND EID = $EID AND Stat = 1");

            $response = [
                "status" => 1,
                "msg" => "Updated"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['disableFromMenu'])) {
            $itemId = $_POST['itemId'];

            // Insert MenuItem_Disabled to disabled item
            $menuItemDisabledObj['ItemId'] = $itemId;
            $menuItemDisabledObj['EID'] = $EID;
            $menuItemDisabledObj['ChainId'] = $ChainId;
            $idd = insertRecord('MenuItem_Disabled', $menuItemDisabledObj);
            if ($idd) {
                $response = [
                    "status" => 1,
                    "msg" => "Item Disabled Successfully"
                ];
            } else {
                $response = [
                    "status" => 0,
                    "msg" => "Failed to Disable Item"
                ];
            }

            echo json_encode($response);
            die();
        }
        if(!empty($_POST['getBill'])){
            $STVcd = $_POST['STVCd'];
            $q = "SELECT b.TableNo, BillId, BillNo, DATE_FORMAT(DATE(PymtTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue, PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u ,Eat_tables et WHERE b.CustId=u.CustId AND b.EID = et.EID AND et.CCd = $STVcd AND b.EID = $EID AND b.Stat = 0 AND  b.TableNo = et.TableNo";
            if(isset($_POST['BillId']) && $_POST['BillId'] > 0){
                $bid = $_POST['BillId'];
                $q.=" and b.BillId = '$bid'";
            }
            $q.=" Order By BillId Asc";
            $billData = $this->db2->query($q)->result_array();
            // echo json_encode($billData[0]);
            $pymtModes = $this->db2->query("SELECT PMNo, Name FROM `PymtModes`WHERE Stat = 0 AND Country = 'India' AND PMNo NOT IN (SELECT PMNo FROM PymtMode_Eat_Disable WHERE EID = $EID)")->result_array();
            $a = array('bill' => $billData[0], 'payment_modes' => $pymtModes);
            // print_r($a);exit();
            echo json_encode($a);
        }
        if(!empty($_POST['check_new_orders'])){
            $q = "SELECT * from KitchenMain where (Stat = 0 or Stat = 10) and EID = ".$EID." and ChainId = ".$ChainId." and BillStat = 0 order by CNo asc";
            $data = $this->db2->query($q)->result_array();
            // echo "<pre>";print_r($data);exit();
            $b = "";
            foreach($data as $key){
                $q1 = "SELECT sum(ItmRate*Qty) as amount from Kitchen where CNo=".$key['CNo']." and (stat = 0 or stat = 10)";
                $am = $this->db2->query($q1)->result_array();
                // echo "<pre>";print_r($am);exit();
                $am = $am[0]['amount'];
                $b.="<tr>";
                $b.="<td>".$key['MergeNo']."</td>";
                $b.="<td>".$am."</td>";
                $b.="<td><button class='btn btn-success btn-sm' onclick='accept_order(".$key['CNo'].")'>Accept</button>&nbsp;&nbsp;<button class='btn btn-danger btn-sm' onclick='reject_order(".$key['CNo'].")'>Reject</button></td>";
                $b.="</tr>";
            }
            echo $b;
        }
        if(!empty($_POST['change_order_status'])){
            $cno = $_POST['cno'];
            $status = $_POST['status'];
            $q = "UPDATE KitchenMain set Stat = ".$status." where CNo=".$cno;
            $am = $this->db2->query($q);
            echo 1;
        }
        if(isset($_POST['check_settled_table']) && !empty($_POST['check_settled_table'])){
            $q = "SELECT km.MergeNo, km.CNo, b.BillNo, b.TotAmt from KitchenMain as km, Billing as b where km.CNo = b.CNo and CnfSettle = 0 and BillStat > 0";
            $am = $this->db2->query($q)->result_array();
            // print_r($am);exit();
            if(!empty($am)){
                $b = '';
                foreach($am as $key){
                    $b.="<tr>";
                    $b.="<td>".$key['MergeNo']."</td>";
                    $b.="<td>".$key['BillNo']."</td>";
                    $b.="<td>".$key['TotAmt']."</td>";
                    // $b.="<td><button class='btn btn-success btn-sm' onclick='confirm_settle(".$key['CNo'].")'>OK</button></td>";
                    $b.="</tr>";
                }
                echo $b;
            }else{
                echo 0;
            }
        }
        if(isset($_POST['confirm_settle']) && !empty($_POST['confirm_settle'])){
            if($_POST['CNo'] == 'all'){
                $q = "UPDATE KitchenMain set CnfSettle = 1 where CnfSettle = 0";
            }else{
                $q = "UPDATE KitchenMain set CnfSettle = 1 where CNo = ".$_POST['CNo']." or MCNo = ".$_POST['CNo'];
            }
            $am = $this->db2->query($q);
            echo 1;
        }

        if(isset($_POST['move_table']) && !empty($_POST['move_table'])){
            // echo "<pre>";print_r($_POST);exit();
            $from = $_POST['from_table'];
            $to = $_POST['to_table'];
            $phone = implode(",", $_POST['cell_no']);
            $q1 = "UPDATE KitchenMain set TableNo = '".$to."', Mergeno ='".$to."' where EID = ".$EID." and (TableNo = '".$from."' or MergeNo = '".$from."') and CellNo in(".$phone.")";
            // print_r($q1);
            $update_kitchen_main = $this->db2->query($q1);
            $q2 = "UPDATE Kitchen set TableNo = '".$to."', Mergeno =".$to." where EID = ".$EID." and (TableNo = '".$from."' or MergeNo = '".$from."') and CellNo in(".$phone.")";
            // print_r($q2);
            $update_kitchen = $this->db2->query($q2);
            $q3 = "UPDATE Eat_tables set Stat = 0 where EID = ".$EID." and TableNo = ".$from;
            // print_r($q3);
            $update_et = $this->db2->query($q3);
            $update_et = $this->db2->query("UPDATE Eat_tables set Stat = 1 where EID = ".$EID." and TableNo = ".$to);
            // header('Location: ../sittin_table_view.php');
            redirect(base_url('restorent/sitting_table'));
        }

        if(isset($_POST['get_phone_num']) && !empty($_POST['get_phone_num'])){
            $from = $_POST['from_table'];

            $nums = $this->db2->query("SELECT CellNo from KitchenMain where EID = ".$EID." and TableNo = ".$from." or MergeNo = ".$from);
            if(!empty($nums)){
                $b = '';
                foreach($nums as $key){
                    $b = '<input type="checkbox" value="'.$key['CellNo'].'" name="cell_no[]"> '.$key['CellNo'];
                }
                echo $b;
            }else{
                echo 0;
            }
        }

    }

    public function merge_table_ajax(){
        $RUserId = authuser()->RUserId;
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;

        if (isset($_POST['getUnmergeTables']) && $_POST['getUnmergeTables']) {
            $tables = $this->db2->query("SELECT TableNo, MergeNo from Eat_tables where TableNo = MergeNo and Stat=0 order by TableNo ASC")->result_array();
            if (!empty($tables)) {
                $response = [
                    "status" => 1,
                    "tables" => $tables
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "All Tables are Allocated"
                ];
            }
            echo json_encode($response);
            die;
        }

        // test

        if (isset($_POST['mergeTables']) && $_POST['mergeTables']) {
            $selectedTables = json_decode($_POST['selectedTables']);
            if (count($selectedTables) > 1) {

                    $mergeNo = implode("~", $selectedTables);
                
                    $selectedTablesString = implode(',', $selectedTables); 
                    $q = "UPDATE Eat_tables set MergeNo = '$mergeNo', Stat = 1 where TableNo in ($selectedTablesString)";
                    $result = $this->db2->query($q);
                    $result1 = $this->db2->query("UPDATE KitchenMain set MergeNo = '$mergeNo' where TableNo in ($selectedTablesString) and BillStat = 0");

                    if($result){
                        $response = [
                            "status" => 1,
                            "msg" => "ok"
                        ];
                    }else{
                        $response = [
                            "status" => 3,
                            "msg" => "Fail to update in  Eat_tables table"
                        ];
                    }
                // }else{
                //  $response = [
                //      "status" => 2,
                //      "msg" => "Fali to insert in Tmerge table"
                //  ];
                // }

            }else {
                $response = [
                    "status" => 0,
                    "msg" => "You can select Min 2 and Max 4 Tables"
                ];
            }

            echo json_encode($response);
            die;
        }
        if (isset($_POST['getMergedTables']) && $_POST['getMergedTables']) {
            $q = "SELECT distinct MergeNo from Eat_tables where TableNo != MergeNo order by MergeNo ASC";

            $tables = $this->db2->query($q)->result_array();
            // print_r($tables);exit();
            if (!empty($tables)) {
                $response = [
                    "status" => 1,
                    "tables" => $tables
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "No table is merged"
                ];
            }
            // print_r($response);exit();
            echo json_encode($response);
            die;
        }
        if (isset($_POST['getEachTable']) && $_POST['getEachTable']) {
            $merge_no = $_POST['MergeNo'];
            // $q = "SELECT CNo, TableNo FROM `KitchenMain` where BillStat=0 ORDER BY TableNo ASC";
            $q = "SELECT TableNo from Eat_tables where MergeNo = '$merge_no'";
            $tables = $this->db2->query($q)->result_array();
            // print_r($tables);exit();
            if (!empty($tables)) {
                $response = [
                    "status" => 1,
                    "tables" => $tables
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "No table is merged"
                ];
            }

            echo json_encode($response);
            die;
        }

        if(isset($_POST['unmergeTables']) && $_POST['unmergeTables']){
            $selectedTables = json_decode($_POST['selectedTables']);
            $deselectedTables = json_decode($_POST['deselectedTables']);
            $old_merge_no = $_POST['MergeNo'];
            $q1 = "UPDATE Eat_tables set MergeNo = TableNo, stat=0 where MergeNo = '$old_merge_no'";
            $tables = $this->db2->query($q1);

            $selectedTables = json_decode($_POST['selectedTables']);
            $response = [
                "status" => 4,
                "msg" => "Success"
            ];
            if (count($selectedTables) > 1) {
                $mergeNo = implode("~", $selectedTables);

                $update_merge = "UPDATE KitchenMain set MergeNo = '$mergeNo'where MergeNo = '$old_merge_no'";
                $tables = $this->db2->query($update_merge);

                $deselectedTablesString = implode(',', $deselectedTables);
                $update_table = "UPDATE KitchenMain set MergeNo = TableNo where TableNo in ($deselectedTablesString)";
                $result = $this->db2->query($update_table);


                $selectedTablesString = implode(',', $selectedTables); 
                $q = "UPDATE Eat_tables set MergeNo = '$mergeNo', Stat = 1 where TableNo in ($selectedTablesString)";
                $result = $this->db2->query($q);
                $result1 = $this->db2->query("UPDATE KitchenMain set MergeNo = '$mergeNo' where TableNo in ($selectedTablesString) and BillStat = 0");
                // $eatTableObj->executeTransaction();
                if($result){
                    $response = [
                        "status" => 1,
                        "msg" => "ok"
                    ];
                }else{
                    $response = [
                        "status" => 3,
                        "msg" => "Fail to update in  Eat_tables table"
                    ];
                }

            }
            echo json_encode($response);
            die;
        }
        if(isset($_POST['UnmergeTable']) && $_POST['UnmergeTable']){
            // $selectedTables = json_decode($_POST['selectedTables']);
            // print_r($_POST);exit();
            $mergeNo = $_POST['MergeNo'];
            $table = $_POST['TableNo'];
            $q1 = "UPDATE Eat_tables set MergeNo = TableNo where MergeNo = '$mergeNo'";

            $tables = $this->db2->query($q1);
        }

    }

    public function customer_landing_page_ajax(){
        // $Firebase = new Firebase();

        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');

        if (isset($_POST['getCuisine']) && $_POST['getCuisine']) {

            if ($ChainId == 0) {
                $eatCuisineData = $this->db2->query("SELECT ec.ECID, ec.CID, c.Name, c.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt, ec.CID as imgSrc FROM `EatCuisine` ec, Cuisines c, Food f, Eatary e WHERE e.EID=ec.EID and ec.CID = c.CID AND c.CTyp = f.CTyp AND ec.EID = $EID AND e.Stat = 0 AND ec.Stat = 0 ORDER BY Rank")->result_array();
            } else {
                $eatCuisineData = $this->db2->query("SELECT ec.ECID, ec.CID, c.Name, c.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt, ec.CID as imgSrc FROM `EatCuisine` ec, Cuisines c, Food f, Eatary e WHERE e.EID=ec.EID and ec.CID = c.CID AND c.CTyp = f.CTyp AND  e.ChainId = $ChainId AND e.Stat = 0 AND ec.Stat = 0 ORDER BY Rank")->result_array();
            }

            if (empty($eatCuisineData)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Cuisines Defined"
                ];
            } else {

                foreach ($eatCuisineData as $key => $data) {
                    //if($Ops == 1) {
                    if ($ChainId > 0) {
                        $imgSrc = "uploads/c" . $ChainId . "/" . $data['imgSrc'] . ".jpg";
                    } else {
                        $imgSrc = "uploads/e" . $EID . "/" . $data['imgSrc'] . ".jpg";
                    }

                    if (!file_exists("../$imgSrc")) {
                        $imgSrc = "uploads/general/" . $data['imgSrc'] . ".jpg";
                    }

                    $eatCuisineData[$key]['imgSrc'] = ltrim($imgSrc);
                }

                $response = [
                    "status" => 1,
                    "eatCuisineData" => $eatCuisineData
                ];
            }

            echo json_encode($response);
            die();
        }

        if (isset($_POST['getMenuCat'])) {

            $cId = $_POST['cId'];

            $menuCatgData = $this->db2->query("SELECT mc.MCatgId, mc.MCatgNm, mc.CTyp, mc.TaxType, f.FID, f.fIdA, f.Opt, f.AltOpt from MenuCatg mc, MenuItem i, Food f where  i.MCatgId=mc.MCatgId AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) AND mc.CID = :cId AND mc.EID=i.EID AND mc.Stat = 0 AND mc.CTyp = f.CTyp and f.LId = 1 and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID=$EID and md.Chainid=i.ChainId) group by mc.MCatgId, mc.MCatgNm, mc.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt order by mc.Rank " , ["cId" => $cId])->result_array();

            if ($ChainId > 0) {
                $imgSrc = "uploads/c" . $ChainId . "/" . $cId . ".jpg";
            } else {
                $imgSrc = "uploads/e" . $EID . "/" . $cId . ".jpg";
            }
            if (!file_exists("../$imgSrc")) {
                $imgSrc = "uploads/general/" . $cId . ".jpg";
            }

            if (empty($menuCatgData)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Menu Category Available At This Time"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "imgSrc" => $imgSrc,
                    "menuCatgData" => $menuCatgData
                ];
            }

            echo json_encode($response);
            die();
        }
        if(isset($_POST['call_help'])){
            // $insert_help = $menuCatgModel->call_help();
            $dd['table_no'] = $_SESSION['TableNo'];
            $insert_help = insertRecord('call_bell', $dd);
            echo $insert_help;
        }
        if(isset($_POST['check_call_help'])){
            $list_id = $_POST['list_id'];
            $check = '';

            $q = "SELECT * from call_bell where response_status = 0";
            if($list_id != ''){
                $q.=" and id not in($list_id)";
            }
            $data = $this->db2->query($q)->result_array();
            if(!empty($data)){
                $check =  $data[0];
            }else{
                $check =  array();
            }
            echo json_encode($check);
        }
        if(isset($_POST['view_call_help'])){
            // $check = $menuCatgModel->view_call_help($_POST['help_table_id']);
            $id = $_POST['help_table_id'];
            $t = date('Y-m-d H:i:s');
            $q = "UPDATE call_bell set viewed = 1, viewed_time='$t' where id = '$id'";
            // print_r($q);exit();
            $this->db2->query($q);
            $check = 1;
            echo $check;
        }
        if(isset($_POST['respond_call_help'])){
            // $check = $menuCatgModel->respond_call_help($_POST['help_table_id']);
            $t = date('Y-m-d H:i:s');
            $id = $_POST['help_table_id'];
            $q = "UPDATE call_bell set response_status = 1, respond_time='$t' where id = '$id'";
            // print_r($q);exit();
            $this->db2->query($q);
            $check = 1;
            echo $check;
        }
        if(isset($_POST['check_help'])){
            // $check = $menuCatgModel->check_help($_POST['help_id']);
            $check = '';
            $q = "SELECT * from call_bell where id = '$id'";
            $data = $this->exec($q);
            if(!empty($data)){
                $check = $data[0];
            }else{
                $check = array();
            }

            echo json_encode($check);
        }
        if(isset($_POST['save_firebase_token'])){
            $this->session->set_userdata('FirebaseToken', $_POST['token']);
            $Firebase['Token'] = $_POST['token'];
            $id = insertRecord('FirebaseTokens', $Firebase);
            echo $id;
        }
        if(!empty($_POST['set_lang']) && !empty($_POST['lang'])){
            $lang = $_POST['lang'];
            setcookie('lang', $lang, time() + (3600 * 24 * 365), "/");
        }
    }

    public function test(){
        $data = array(
                    array("ItemAmt"=>"350","ItemNm"=>"Indonesian Style Paneer","ItmRate"=>350,"Qty"=>"1","Tx"=>1,"TaxType"=>2,"Stat"=>9,"Name"=>"Tequila Sunrise","Addr"=>"41 Ardeshir Mension, Station Road,","city"=>"Mumbai","pincode"=>"400054","cinno"=>"-","fssaino"=>"-","GSTno"=>"27ACZFS7957F1Z3","BillPrefix"=>"","BillSuffix"=>"","TaxInclusive"=>0,"PhoneNos"=>"02226494782","Remarks"=>"-","Tagline"=>"Visit again","BillNo"=>20,"TotItemDisc"=>0,"BillDiscAmt"=>0,"TotPckCharge"=>0,"DelCharge"=>0,"totamt"=>"1085.70","bservecharge"=>"10.00","tip"=>"0.00","BillDt"=>"2023-01-21 22=>08=>00","Portion"=>"Std","Itm_Portion"=>1),
                    array("ItemAmt"=>"240","ItemNm"=>"Murg Yakhni Shorba","ItmRate"=>240,"Qty"=>"1","Tx"=>1,"TaxType"=>2,"Stat"=>9,"Name"=>"Tequila Sunrise","Addr"=>"41 Ardeshir Mension, Station Road,","city"=>"Mumbai","pincode"=>"400054","cinno"=>"-","fssaino"=>"-","GSTno"=>"27ACZFS7957F1Z3","BillPrefix"=>"","BillSuffix"=>"","TaxInclusive"=>0,"PhoneNos"=>"02226494782","Remarks"=>"-","Tagline"=>"Visit again","BillNo"=>20,"TotItemDisc"=>0,"BillDiscAmt"=>0,"TotPckCharge"=>0,"DelCharge"=>0,"totamt"=>"1085.70","bservecharge"=>"10.00","tip"=>"0.00","BillDt"=>"2023-01-21 22=>08=>00","Portion"=>"Std","Itm_Portion"=>1),
                    array("ItemAmt"=>"350","ItemNm"=>"Paneer Tikka  Lahori","ItmRate"=>350,"Qty"=>"1","Tx"=>1,"TaxType"=>2,"Stat"=>9,"Name"=>"Tequila Sunrise","Addr"=>"41 Ardeshir Mension, Station Road,","city"=>"Mumbai","pincode"=>"400054","cinno"=>"-","fssaino"=>"-","GSTno"=>"27ACZFS7957F1Z3","BillPrefix"=>"","BillSuffix"=>"","TaxInclusive"=>0,"PhoneNos"=>"02226494782","Remarks"=>"-","Tagline"=>"Visit again","BillNo"=>20,"TotItemDisc"=>0,"BillDiscAmt"=>0,"TotPckCharge"=>0,"DelCharge"=>0,"totamt"=>"1085.70","bservecharge"=>"10.00","tip"=>"0.00","BillDt"=>"2023-01-21 22=>08=>00","Portion"=>"Std","Itm_Portion"=>1)
                    );
        echo "<pre>";
        print_r($data);
        die;
    }





}
