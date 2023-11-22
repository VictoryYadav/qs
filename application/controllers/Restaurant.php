<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurant extends CI_Controller {
// Restaurant 
    private $db2;
	public function __construct()
	{
		parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->authuser = $this->session->userdata('logged_in');
        } else {
            redirect(base_url());
            // redirect(base_url('login?o='.authuser()->EID.'&c='.authuser()->ChainId));
        }
		$this->load->model('Rest', 'rest');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

    public function index(){
        $data['title'] = 'Dashboard';
        $this->load->view('rest/index',$data);
    }

    public function add_user(){
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $res = $this->rest->addUser($_POST);
            $this->session->set_flashdata('success',$res); 
            redirect(base_url('restaurant/add_user'));
        }

		$data['title'] = 'Add User';
        $data['EID'] = authuser()->EID;
        $data['restaurant'] = $this->rest->getrestaurantList(authuser()->ChainId);
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
        $EID = authuser()->EID;
        $data['usersRestData'] = $this->db2->select('RUserId, FName, LName, MobileNo')->get_where('UsersRest', array('DeputedEID' => $EID, 'Stat' => 0 ))->result_array(); 
    	$data['title'] = 'User Access';
		$this->load->view('rest/access_users',$data);
    }

    public function role_assign1(){
        $EID = authuser()->EID;
        $data['usersRestData'] = $this->db2->query("SELECT RUserId, FName, LName FROM UsersRest WHERE DeputedEID = $EID AND UTyp = 1 Order By FName ASC")->result_array();
        $data['kitData'] = $this->db2->query("SELECT KitCd, KitName FROM Eat_Kit WHERE EID = $EID")->result_array();
        $data['disData'] = $this->db2->query("SELECT DCd, Name FROM Eat_DispOutlets WHERE EID = $EID")->result_array();
        $data['casherData'] = $this->db2->query("SELECT CCd, Name FROM Eat_Casher WHERE EID = $EID")->result_array();

        $data['title'] = 'Role Assignment';
        $this->load->view('rest/assign_role_old',$data);   
    }

    public function role_assign(){
        $staus = 'error';
        $response = 'Something went wrong please try again!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $data = $_POST;
            $RUserId = $data['RUserId'];
            
            $data['KitCd'] = !empty($data['KitCd'])?implode(",",$data['KitCd']):'';
            $data['DCd'] = !empty($data['DCd'])?implode(",",$data['DCd']):'';
            $data['CCd'] = !empty($data['CCd'])?implode(",",$data['CCd']):'';
            
            $check = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();
            if(!empty($check)){
                unset($data['RUserId']);
                updateRecord('UsersRoleDaily', $data, array('RUserId' => $RUserId));
                $res = 'Role Assigned Updated.';
            }else{
                insertRecord('UsersRoleDaily',$data);
                $res = 'Role Assigned Successfully.';
            }
            redirect(base_url('restaurant/role_assign1'));
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
            
        }
        $EID = authuser()->EID;
        $data['usersRestData'] = $this->db2->select('RUserId, FName, LName, MobileNo')->get_where('UsersRest', array('DeputedEID' => $EID, 'Stat' => 0 ))->result_array();        

        $data['title'] = 'Role Assignment';
        $this->load->view('rest/assign_role',$data);   
    }

    public function getRoleData(){
        $EID = authuser()->EID;
        $status = 'error';
        $response = 'Some thing went wrong Please try again!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $RUserId = $_POST['RUserId'];

            $roleData = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();
            $kitval = array();
            $disval = array();
            $cashval = array();
            if(!empty($roleData)){
                $kitval = !empty($roleData['KitCd'])?explode(",",$roleData['KitCd']):array();
                $disval = !empty($roleData['DCd'])?explode(",",$roleData['DCd']):array();
                $cashval = !empty($roleData['CCd'])?explode(",",$roleData['CCd']):array();
            }

            $kitData = $this->db2->query("SELECT KitCd, KitName FROM Eat_Kit WHERE EID = $EID")->result_array();
            $kitchen = '';
            foreach ($kitData as $kit) {
                $checked_kit = '';
                if(in_array($kit['KitCd'], $kitval))
                { 
                    $checked_kit = "checked"; 
                }
                $kitchen .='<div class="form-check-inline">
                  <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="'.$kit['KitCd'].'" name="KitCd[]" '.$checked_kit.'>'.$kit['KitName'].'
                  </label>
                </div>';
            }
            $disData = $this->db2->query("SELECT DCd, Name FROM Eat_DispOutlets WHERE EID = $EID")->result_array();
            $dispense = '';
            foreach ($disData as $kit) {
                $checked_dis = '';
                if(in_array($kit['DCd'], $disval))
                { 
                    $checked_dis = "checked"; 
                }

                $dispense .='<div class="form-check-inline">
                  <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="'.$kit['DCd'].'" name="DCd[]" '.$checked_dis.'>'.$kit['Name'].'
                  </label>
                </div>';
            }

            $casherData = $this->db2->query("SELECT CCd, Name FROM Eat_Casher WHERE EID = $EID")->result_array();

            $cashier = '';
            foreach ($casherData as $kit) {
                $checked_cash = '';
                if(in_array($kit['CCd'], $cashval))
                { 
                    $checked_cash = "checked"; 
                }

                $cashier .='<div class="form-check-inline">
                  <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="'.$kit['CCd'].'" name="CCd[]" '.$checked_cash.'>'.$kit['Name'].'
                  </label>
                </div>';
            }
            
            $data['createForm'] = '<form class="mt-2" id="roleAssignForm" method="POST">
                <input type="hidden" name="RUserId" value="'.$RUserId.'">
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>Role</th>
                        <th>Assigned Role</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Chef</td>
                            <td>'.$kitchen.'</td>
                        </tr>

                        <tr>
                            <td>Dispense</td>
                            <td>'.$dispense.'</td>
                        </tr>

                        <tr>
                            <td>Cashier</td>
                            <td>'.$cashier.'</td>
                        </tr>
                    </tbody>
                  </table>
              </div>
              <div class="text-center">
                <button class="btn btn-sm btn-success" onclick="submitData()">Submit</button>
              </div>
            </form>';

            // echo "<pre>";
            // print_r($data);
            // die;
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }
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
            redirect(base_url('restaurant/offers_list'));
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
            redirect(base_url('restaurant/offers_list'));
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
            $this->db2->query("update CustOffersDet set Stat = 9 where SchCd = ".$SchCd);
            $this->db2->query("update CustOffers set Stat = 9 where SchCd = ".$SchCd);
        }
        if(isset($_POST['delete_offer_description']) && $_POST['delete_offer_description']){
            $SDetCd = $_POST['SDetCd'];
            $this->db2->query("update CustOffersDet set Stat = 9 where SDetCd = ".$SDetCd);
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
        $data['descriptions'] = $this->db2->get_where('CustOffersDet', array('SchCd' =>$SchCd,'Stat' => 0))->result_array();

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
        $data['dispenseMode'] = $this->rest->getDispenseModes();

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
                // echo "<pre>"; print_r($_POST);exit();
                if (isset($_POST['DispCd'])) {
                    $DCd = $_POST['DispCd'];
                } else {
                    $DCd = 0;
                }

                //This is for the main data table details... need to know why we are using ukotno and kitcd as this is for dispensing
                $DCdType = $this->db2->query("SELECT * from Eat_DispOutlets where DCd = ".$DCd)->row_array();
                $dispMode = (isset($_POST['dispMode']) && $_POST['dispMode'] > 0 )?$_POST['dispMode']:0;
                $qry = '';
                if($dispMode > 0){
                    $qry = ' and k.TPId = '.$dispMode;
                }

                if ($EType == 5) {                    

                    if($DCdType['DCdType'] == 1){
                        $q = "SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * k.ItmRate) as KOTAmt, k.kitcd,  km.TPRefNo, km.TPId, km.CustId, km.CustNo, km.CellNo, km.EID,km.CNo  FROM KitchenMain km, Kitchen k,  Billing b , Eat_DispOutlets ed, Eat_DispOutletsDet edd where km.EID=b.EID  AND b.CNo = km.CNo and km.CNO=b.CNo AND km.CNo = k.CNo AND ed.EID = km.EID and ed.DCd=edd.DCd and k.KitCd = edd.KitCd and km.Delivered = 0 ".$qry." and km.EID = ".$EID."  AND ed.DCd = ".$DCd." Group by b.BillNo, k.KitCd, km.TPRefNo, km.TPId, km.CustId, km.EID, km.CustNo, km.CellNo,km.CNo Order by b.BillNo Asc";
                    }elseif($DCdType['DCdType'] == 2){
                        $q = "SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * k.ItmRate) as KOTAmt, k.OType,  km.TPRefNo, km.TPId, km.CustId, km.CustNo, km.CellNo, km.EID,km.CNo  FROM KitchenMain km, Kitchen k,  Billing b , Eat_DispOutlets ed, Eat_DispOutletsDet edd where km.EID=b.EID  AND b.CNo = km.CNo and km.CNO=b.CNo AND km.CNo = k.CNo AND ed.EID = km.EID and ed.DCd=edd.DCd and k.OType = edd.OType and km.Delivered = 0 ".$qry." and km.EID = ".$EID."  AND ed.DCd = ".$DCd." Group by b.BillNo, k.OType, km.TPRefNo, km.TPId, km.CustId, km.EID, km.CustNo, km.CellNo,km.CNo Order by b.BillNo Asc";
                    }
                    $kitchenData = $this->db2->query($q)->result_array();
                    // print_r($this->db2->last_query());exit();
                } else {
                    // $Fest=1;
                    if ($Fest == 0) {
                        $kitchenData = $this->db2->query("SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * i.Value) as KOTAmt, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.CustNo, k.CellNo, k.EID, km.DCd, ed.Name FROM `Kitchen` k, MenuItem i, Billing b, Eat_DispOutlets ed,KitchenMain km  where i.ItemId = k.ItemId AND k.OType <> 7 AND k.Stat <= 3 AND k.Stat > 0  AND k.EID=b.EID and ed.DCd = km.DCd and k.CNO= b.CNO and k.CNo = km.CNo and km.Delivered = 0 ".$qry." AND k.EID =$EID AND ed.EID = k.EID AND ed.DCd = $DCd and (DateDiff(Now(),k.LstModDt) < 2) Group by b.BillNo, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.EID, k.CustNo, k.CellNo  Order by b.BillNo")->result_array();  //And k.payRest=1
                        //if this is not fest and has multiple counters for dispense, it is grouped by DCd, else we do not need ukotno. undelss item details are shown thru ukotno

                    } else {
                        $kitchenData = $this->db2->query("SELECT b.BillNo, sum(k.Qty) as Qty, sum(k.AQty) as AQty, sum(k.Qty * i.Value) as KOTAmt, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.CustNo, k.CellNo, k.EID, km.DCd, ed.Name FROM `Kitchen` k, MenuItem i, Billing b , Eat_DispOutlets ed, KitchenMain km where i.ItemId = k.ItemId AND k.OType <> 7  AND k.Stat > 0 AND k.Stat <= 3 AND k.EID=b.EID and ed.DCd = km.DCd and k.CNO=b.CNO AND ed.EID = k.EID and k.CNo = km.CNo and km.Delivered = 0 ".$qry." and k.EID = $EID AND ed.KitCd=k.KitCd AND ed.DCd = $DCd and date(k.LstModDt) = date(Now()) Group by b.BillNo, k.UKOTNo, k.OType, k.TPRefNo, k.TPId, k.CustId, k.EID, k.CustNo, k.CellNo Order by b.BillNo Asc")->result_array();       //And k.payRest=1 
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

                updateRecord('Kitchen', array('DStat' => 5), array('EID' => $EID, 'CNo' => $CNo, 'DStat' => 0));

                // $kitcheData = $this->db2->query("SELECT SUM(m.Value*k.Qty) as OrdAmt, k.KOTNo, k.UKOTNo, date(k.LstModDt) as OrdDt, c.CGSTRate, c.SGSTRate, c.GSTInclusiveRates, c.ServChrg from Kitchen k, MenuItem m, Config c where c.EID=k.EID and k.ItemId=m.ItemId and (k.Stat<>4 and k.Stat<>6 and k.stat<>7 and k.Stat<>9 and k.Stat<>99) AND k.CNo=".$CNo." and k.EID=$EID AND k.BillStat=0 group by k.UKOTNo, k.KOTNo, date(k.LstModDt), c.CGSTRate, c.SGSTRate, c.GSTInclusiveRates, c.ServChrg order by date(k.LstModDt)Asc, KotNo Asc", ["CNo" => $CNo])->result_array();

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
        // echo "<pre>";
        // print_r($_GET);
        // die;
        // this function is not completed

        if ($_GET['CustId'] != null) {
          $CustId = $_GET['CustId'];
          $message = $_GET['message'];
          $title = $_GET['title'];
          $flag = $_GET['flag'];
          $billno = $_GET['billno'];

          $userToken = $this->db2->select('token')->get_where('Users', array('CustId' => $CustId))->result_array();

          if (empty($userToken)) {
            $response = [
              "status" => 0,
              "msg" => "Invalid User Token"
            ];

            echo json_encode($response);
            die();
          } else {
            $token = $userToken[0]['token'];
            
            if (empty($token)) {
                $response = [
                  "status" => 0,
                  "msg" => "Invalid User Token"
                ];
                echo json_encode($response);
                die();
            }
            $fcmRegIds = $token;

            if ($flag != 0) {

              // insert notification to database
              $sql = $this->db2->query("INSERT INTO `Notification` (`user_id`, `title`, `message`,`billno`)VALUES ($CustId, '$title', '$message',$billno)");
            } else if ($flag == 0) {
              // sql to delete a record
              $sql = $this->db2->query("DELETE FROM `Notification` WHERE `user_id`=$CustId AND `billno` = $billno");
            }

            $msg = array(
              'body'   => $message,
              'title'   => $title,
              // 'click_action'   => $request->input('notification_url'),
              'vibrate' => 1,
              'sound'   => 1
              // 'icon' => $request['n_icon']
            );
            // notification
            $response = firebaseNotification($fcmRegIds, $msg);
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

            $res = '';
            $data = $_POST;
            if($data['password'] == $data['c_password']){
                $check = $this->db2->select('Passwd')->get_where('UsersRest', array('RUserId' => authuser()->RUserId))->row_array();
                 if(!empty($check)){
                    if($check['Passwd'] == $data['old_password']){
                        $status = 'success';
                        $this->session->set_userdata('new_pwd', $data['password']);
                        $this->session->set_userdata('old_pwd', $data['old_password']);
                        $this->generateOTP(authuser()->RUserId);
                        $res = "OTP has been send.";
                    }else{
                        $res = "OLD Password does not matched.";
                    }
                 }else{
                    $res = "Failed to Validate User";
                 }
            }else{
                $res = "Passwords Don't Match";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }

        $data['title'] = 'Change Password';
        $this->load->view('rest/password_change',$data);   
    }

    private function generateOTP($RUserId){
        $check = $this->db2->select('token')->get_where('UsersRest', array('RUserId' => $RUserId))->row_array();
        if(!empty($check)){
            $otp = rand(9999,1000);
            $this->session->set_userdata('new_otp', $otp);
            $msg = 'Your One Time Password is '.$otp;
            $message = array(
              'body'   => $msg,
              'title'   => 'Your OTP',
              'vibrate' => 1,
              'sound'   => 1
            );
            firebaseNotification($check['token'], $message);
        }
    }

    public function verifyOTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('new_otp');
            if($_POST['otp'] == $otp){
                $password = $this->session->userdata('new_pwd');
                $this->rest->passwordUpdate($password);
                $res = "Password has been updated.";
                $status = 'success';
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
                    insertRecord('ConfigTheme',$Theme);
                }
            }
        }

        $data['data11'] = $this->db2->query("SELECT * from ConfigTheme order by ThemeId desc")->result_array();
        if(!empty($data['data11'])){
            $data['data11'] = $data['data11'][0];
        }else{
            $data['data11'] = $this->db2->query("SELECT * from ConfigTheme order by ThemeId")->result_array();
            $data['data11'] = $data['data11'][0];
        }

        // echo "<pre>";
        // print_r($data['data11']);
        // die;

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
            
            if(isset($_POST['add_stock']) && $_POST['add_stock'] == 1){
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
                    $TransId = insertRecord('RMStock', $RMStock);
                    if($TransId){
                        // $TransId = $this->db2->insert_id();
                        $num = sizeof($_POST['ItemId']);
                        for($i = 0;$i<$num;$i++){
                            $RMStockDet['TransId'] = $TransId;

                            $RMStockDet['RMCd'] = !empty($_POST['ItemId'][$i])?$_POST['ItemId'][$i]:0;
                            $RMStockDet['UOMCd'] = !empty($_POST['UOM'][$i])?$_POST['UOM'][$i]:0;
                            $RMStockDet['Qty'] = !empty($_POST['Qty'][$i])?$_POST['Qty'][$i]:0;
                            $RMStockDet['Rate'] = !empty($_POST['Rate'][$i])?$_POST['Rate'][$i]:0;
                            $RMStockDet['Rmks'] = !empty($_POST['Remarks'][$i])?$_POST['Remarks'][$i]:"";
                            
                            if(!empty($RMStockDet['RMCd']) && !empty($RMStockDet['Qty']) && !empty($RMStockDet['UOMCd'])){

                                insertRecord('RMStockDet',$RMStockDet);
                            }
                        }
                    }
                    redirect(base_url('restaurant/stock_list'));
                }

            }
            if(isset($_POST['edit_stock']) && $_POST['edit_stock'] == 1){
                if($_POST){

                    $TransId = $_POST['trans_id'];
                    $num = sizeof($_POST['ItemId']);
                    for($i = 0;$i<$num;$i++){
                        $RMStockDet['TransId'] = $TransId;
                        $detid = !empty($_POST['RMDetId'][$i])?$_POST['RMDetId'][$i]:NULL;
                        $RMStockDet['RMCd'] = !empty($_POST['ItemId'][$i])?$_POST['ItemId'][$i]:0;
                        $RMStockDet['UOMCd'] = !empty($_POST['UOM'][$i])?$_POST['UOM'][$i]:0;
                        $RMStockDet['Qty'] = !empty($_POST['Qty'][$i])?$_POST['Qty'][$i]:0;
                        $RMStockDet['Rate'] = !empty($_POST['Rate'][$i])?$_POST['Rate'][$i]:0;
                        
                        updateRecord('RMStockDet',array('Stat' => 1),array('TransId' =>$TransId));

                        if(!empty($RMStockDet['RMCd']) && !empty($RMStockDet['Qty']) && !empty($RMStockDet['Rate']) && !empty($RMStockDet['UOMCd'])){
                            
                            insertRecord('RMStockDet',$RMStockDet);
                        }else{
                         $this->session->set_flashdata('error','All Fields Are Required!');
                         redirect(base_url('restaurant/edit_stock?TransId='.$TransId));   
                        }
                    }
                    redirect(base_url('restaurant/stock_list'));
                }

            }
            if(isset($_POST['delete_details'])){
                updateRecord('RMStockDet', array('Stat' => 9), array('RMDetId' => $_POST['RMDetId']) );
                echo 1;
            }
            if(isset($_POST['delete_trans'])){
                // print_r($_POST);exit();
                updateRecord('RMStock', array('Stat' => 9), array('TransId' => $_POST['TransId']) );

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

// this is not completed
    public function itemstockreport(){
        $data['CheckOTP'] = $this->session->userdata('DeliveryOTP');
        $data['EID'] = authuser()->EID;
        $data['EType'] = $this->session->userdata('EType');
        $data['RestName'] = authuser()->RestName;

        $data['op_stock'] = 0;
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
            $data['stock_details'] = $this->db2->get_where('RMStockDet', array('TransId' => $TransId, 'Stat' => 0))->result_array();

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

            $uoms = $this->db2->select('riu.*, ru.Name')
                              ->join('RMUOM ru','riu.UOMCd = ru.UOMCd','inner')
                              ->get_where('RMItemsUOM riu', array('riu.RMCd' => $item_id))
                              ->result_array();
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

        $billData = $this->db2->query("SELECT BillId,BillNo,PaymtMode, DATE(billTime) as BillDate, TotAmt FROM Billing WHERE PaidAmt = 0 AND EID = $EID AND PaymtMode = 'Cash'")->result_array();

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
        $this->load->view('rest/bill_settle',$data);
    }

    public function bill_view(){
       
        $data['RUserId'] = authuser()->RUserId;
        $data['EID'] =  authuser()->EID;
        $data['EType'] =  $this->session->userdata('EType');
        $data['from_date'] = date('Y-m-d');
        $data['to_date'] = date('Y-m-d');
        if($this->input->method(true)=='POST'){
            $data['from_date'] = date('Y-m-d', strtotime($_POST['from_date']));
            $data['to_date'] = date('Y-m-d', strtotime($_POST['to_date']));
        }
        $data['bills'] = $this->rest->getBillingData($data['from_date'] , $data['to_date'] );

        // echo "<pre>";
        // print_r($data);
        // die;

        $data['title'] = 'Bill View';
        $this->load->view('rest/bill_view',$data);
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
                $q = "SELECT b.TableNo, BillId, BillNo, DATE_FORMAT(DATE(billTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue, PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u ,Eat_tables et WHERE b.CustId=u.CustId AND b.EID = et.EID AND et.CCd = $STVcd AND b.EID = $EID AND  b.TableNo = et.TableNo";
                if(isset($_POST['BillId']) && $_POST['BillId'] > 0){
                    $bid = $_POST['BillId'];
                    $q.=" and b.BillId = '$bid'";
                }
                $q.=" Order By BillId Asc";
                $billData = $this->db2->query($q)->result_array();
                // print_r($billData);
                // exit;
            }else {
                $q = "SELECT TableNo, BillId, BillNo, DATE_FORMAT(DATE(billTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue , PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u WHERE b.PaymtMode = 'Cash' AND b.CustId=u.CustId AND EID = $EID AND b.Stat = 0";
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
            $q1 = "UPDATE Billing  SET PaidAmt = $paidAmount, PaymtMode = '".$mode."', Stat = 1,payRest=1  WHERE BillId = $id AND EID = $EID";
            // print_r($q1);
            updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $id,'EID' => $EID));
            $billData = $this->db2->query($q1);

            if($EType == 1){
                $stat = 1;
                //Session::set('KOTNo',0);
                $q2 = "UPDATE Kitchen k, KitchenMain km SET  k.Stat = $stat, k.payRest=1, km.payRest=1 WHERE (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
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

            $q2 = "UPDATE Kitchen k, KitchenMain km SET  k.Stat = $stat, k.payRest=1, km.payRest=1 WHERE (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
                // print_r($q2);
            $kitchenUpdate = $this->db2->query($q2);

            // store to gen db
            $custPymtOb['BillId'] = $id;
            $custPymtOb['BillNo'] = $billNo;
            $custPymtOb['EID'] = $EID;
            $custPymtOb['PaidAmt'] = $billAmt;
            $custPymtOb['PaymtMode'] = $pymtMode;
            // $custPymtObj->create();
            // $this->db2->insert('CustPymts', $custPymtOb);

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
                // $deleteETO = $this->db2->query("DELETE from Eat_tables_Occ eto, KitchenMain km where eto.TableNo= km.TableNo and eto.CustId=km.CustId and eto.EID=km.EID AND km.BillStat = $id AND km.EID = $EID and km.CNo = $cNo");

                $updateEatTable = $this->db2->query("UPDATE Eat_tables et, KitchenMain km SET et.Stat = 0 WHERE et.MergeNo= km.MergeNo and et.EID=km.EID AND (km.BillStat = 1 or km.BillStat=5) and km.MCNo = $cNo AND km.EID = $EID");
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
        // $data['CustId'] = $this->session->userdata('CustId');
        if(isset($_GET['CustId'])){
            $data['CustId'] = $_GET['CustId'];
        }
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

        $billData = getBillData($data['dbname'], $data['EID'], $data['billId'], $data['CustId']); 

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
        // when calling join unjoin table then load this query
        $data['captured_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 1 and EID = ".$EID)->result_array();
        $data['available_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 0 and EID = ".$EID)->result_array();
        $data['selectMergeTable'] = $this->db2->query("SELECT TableNo , MergeNo FROM `Eat_tables` where EID = $EID")->result_array();
        // end when calling join unjoin table then load this query
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
            
            $kitchenData = $this->db2->query("SELECT (SUM(k.Qty) - SUM(k.DQty)) as AllDelivered, SUM(k.AQty) as AnyAssigned,km.CNo, km.CustId,  SUM(k.ItmRate * k.Qty) as Amt,  IF((SELECT MIN(k1.KOTPrintNo) FROM Kitchen k1 WHERE k1.KOTPrintNo = 1 AND (km.CNo = k1.CNo OR km.MCNo = k1.CNo)  AND k1.MergeNo = km.MergeNo AND k1.EID = km.EID AND ec.EID = km.EID GROUP BY k1.MergeNo, km.EID)=1,0,1) AS NEW_KOT, TIME_FORMAT(km.LstModDt,'%H:%i') as StTime,   km.MergeNo, km.MCNo, km.BillStat,  km.EID, km.CNo, km.CellNo, (select count(km2.CellNo) from KitchenMain km2 where km2.CellNo=km.CellNo and km2.EID = km.EID group by km2.CellNo) as visitNo, km.TableNo,km.OType,km.payRest,km.custPymt,km.CnfSettle, ec.CCd, ec.Name FROM Kitchen k,  MenuItem i , Eat_tables et, Eat_Casher ec, KitchenMain km WHERE (km.CNo = k.CNo OR km.MCNo = k.CNo) And  et.TableNo = km.TableNo AND k.ItemId = i.ItemId  AND et.EID = km.EID AND km.payRest=0 and km.CnfSettle=0 AND (k.Stat <> 4 and k.Stat <> 6 AND k.Stat <> 7 AND  k.Stat <> 99) AND (k.OType = 7 OR k.OType = 8) AND et.CCd = ec.CCd AND ec.CCd = $STVCd  AND k.EID = km.EID AND k.MergeNo = km.MergeNo AND km.EID = $EID GROUP BY km.CNo,  km.Mergeno, km.MCNo order by MergeNo, km.LstModDt")->result_array();
            // echo "<pre>";
            // print_r($kitchenData);exit();
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
            echo "<pre>";
            print_r($_POST);
            die;
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
            $mergeNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];

            // get kot from kitchen for particular row  - 14/1/20 
            $q = "SELECT k.MergeNo,km.TableNo, k.FKOTNo, k.KOTNo, k.KitCd, SUM(k.Qty) as Qty , k.KOTPrintNo, k.ItemId, i.ItemNm, SUM(k.Qty) as Qty, SUM(k.AQty) as AQty, SUM(k.DQty) as DQty,TIME_FORMAT(ADDTIME(k.OrdTime,k.EDT), '%H:%i') as EDT, km.CellNo, km.CNo,km.MCNo, km.MergeNo FROM Kitchen k, KitchenMain km, MenuItem i WHERE k.ItemId = i.ItemId AND ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.Stat<>99 ) AND k.EID=km.EID AND k.CNo = km.CNo AND km.EID = $EID and (km.CNo = $CNo OR km.MCNo = $CNo) and k.MergeNo = km.MergeNo and km.MergeNo = $mergeNo GROUP BY k.FKOTNo, k.KOTNo, k.KitCd, k.KOTPrintNo, k.ItemId, k.EDT, km.TableNo, km.CellNo, km.CNo order by k.KOTNo, k.FKOTNo, i.ItemNm DESC";
            
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
            $q = "SELECT b.TableNo,b.CustId, BillId, BillNo, DATE_FORMAT(DATE(billTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue, PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u ,Eat_tables et WHERE b.CustId=u.CustId AND b.EID = et.EID AND et.CCd = $STVcd AND b.EID = $EID AND b.Stat = 1 AND  b.TableNo = et.TableNo";
            if(isset($_POST['BillId']) && $_POST['BillId'] > 0){
                $bid = $_POST['BillId'];
                $q.=" and b.BillId = '$bid'";
            }
            $q.=" Order By BillId Asc";
            $billData = $this->db2->query($q)->result_array();
            // echo json_encode($billData[0]);
            // echo "<pre>";
            // print_r($_POST);
            // print_r($billData);
            // print_r($this->db2->last_query());
            // die;
            $pymtModes = $this->db2->query("SELECT PMNo, Name FROM `PymtModes`WHERE Stat = 0 AND Country = 'India' AND PMNo NOT IN (SELECT PMNo FROM PymtMode_Eat_Disable WHERE EID = $EID)")->result_array();
            $a = array('bill' => $billData[0], 'payment_modes' => $pymtModes);
            echo print_r($a);exit();
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
            redirect(base_url('restaurant/sitting_table'));
        }

        if(isset($_POST['get_phone_num']) && !empty($_POST['get_phone_num'])){

            $from = $_POST['from_table'];

            $nums = $this->db2->query("SELECT CellNo from KitchenMain where EID = ".$EID." and TableNo = ".$from." or MergeNo = ".$from)->result_array();
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
                    $result1 = $this->db2->query("UPDATE KitchenMain km set km.MergeNo = '$mergeNo', km.MCNo=(select km1.CNo from KitchenMain km1 where km1.TableNo = $selectedTables[0] and km1.BillStat = 0) where km.TableNo in ($selectedTablesString) and km.BillStat = 0");
                    $this->db2->query("UPDATE Kitchen k set k.MergeNo = '$mergeNo', k.MCNo=(select km1.CNo from KitchenMain km1 where km1.TableNo = $selectedTables[0] and km1.BillStat = 0 ) where k.TableNo in ($selectedTablesString) and k.BillStat = 0");

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

            $q = "SELECT * from call_bell where response_status < 3";
            if($list_id != ''){
                $q.=" and id not in($list_id)";
            }
            $data = $this->db2->query($q)->result_array();
            
            if(!empty($data)){
                $check =  $data;
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

            $datas['respond_time']    = date('Y-m-d H:i:s');
            $datas['response_status'] = $_POST['status'];
            $id                       = $_POST['help_table_text_id'];
            updateRecord('call_bell', $datas, array('id' => $id) );

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

    public function rest_cash_bill_ajax(){
        // GenTableData db objec
        // require_once '../db/tables/CustPymt.class.php';

        // Session Variables
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
    
                $q = "SELECT b.TableNo, BillId, BillNo, DATE_FORMAT(DATE(billTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue, PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u ,Eat_tables et WHERE b.CustId=u.CustId AND b.EID = et.EID AND et.CCd = $STVcd AND b.EID = $EID AND b.Stat = 0 AND  b.TableNo = et.TableNo";
                if(isset($_POST['BillId']) && $_POST['BillId'] > 0){
                    $bid = $_POST['BillId'];
                    $q.=" and b.BillId = '$bid'";
                }
                $q.=" Order By BillId Asc";
                $billData = $this->db2->query($q)->result_array();
                // print_r($billData);
                // exit;
            }else {
                $q = "SELECT TableNo, BillId, BillNo, DATE_FORMAT(DATE(billTime),'%d/%m/%Y') as BillDate, TotAmt, TotAmt as BillValue , PaidAmt, PaymtMode, PymtType, MobileNo, CNo, u.CustId FROM Billing b, Users u WHERE b.PaymtMode = 'Cash' AND b.CustId=u.CustId AND EID = $EID AND b.Stat = 0";
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
                    "PymtModes"=>$PymtModesObj
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
            $id = $_POST['id'];
            $mode = $_POST['mode'];
            $cNo = $_POST['CNo'];

            $q1 = "UPDATE Billing  SET Stat = 1,payRest=1  WHERE BillId = $id AND EID = $EID";
            $billData = $this->db2->query($q1);
            // print_r($q1);
            updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $id,'EID' => $EID));

            $q2 = "UPDATE Kitchen k, KitchenMain km SET k.payRest=1, km.payRest=1 WHERE (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
            $kitchenUpdate = $this->db2->query($q2);

            if ($EType == 5) {
                // $q3 = "DELETE from Eat_tables_Occ where EID=$EID and CNo = $cNo AND ((TableNo = '$TableNo' AND CustId = $CustId) OR (MergeNo = '$TableNo'))";
                // $deleteETO = $this->db2->query($q3);

              $this->db2->query("UPDATE Eat_tables SET Stat = 0 WHERE EID = $EID AND MergeNo = $MergeNo");
            }

            // store to gen db
            $genTblDb = $this->load->database('GenTableData', TRUE);
            $custPymtObj['BillId'] = $id;
            $custPymtObj['BillNo'] = $billNo;
            $custPymtObj['EID'] = $EID;
            $custPymtObj['PaidAmt'] = $billAmt;
            $custPymtObj['PaymtMode'] = $pymtMode;
            $genTblDb->insert('CustPymts', $custPymtObj);

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

            // $kitchenUpdate = $kitchenObj->exec("UPDATE Kitchen SET Stat = 99 WHERE BillStat = $id AND EID = $EID");

            $kitchenMainUpdate = $this->db2->query("UPDATE KitchenMain SET Stat = 99 WHERE BillStat = $id AND EID = $EID and (CNo = $cNo OR MCNo = $cNo)");

            if($EType == 5) {
                $deleteETO = $this->db2->query("DELETE from Eat_tables_Occ eto, KitchenMain km where eto.TableNo= km.TableNo and eto.CustId=km.CustId and eto.EID=km.EID AND km.BillStat = $id AND km.EID = $EID and km.CNo = $cNo");

                //$updateEatTable = $eatTablesObj->exec("UPDATE Eat_tables SET Stat = 0 WHERE EID = $EID AND TableNo = '$TableNo'");
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

    public function savemergedata_ajax(){
        $EID = authuser()->EID;
        $selectmaintb = $this->session->userdata('selectmaintb');

        // select table number which is already mearge with this table
        $updateTable1 = $this->db2->query("UPDATE Eat_tables set MergeNo = 0 where  EID = $EID AND MergeNo = '$selectmaintb' AND TableNo != '$selectmaintb'");

        $query = "UPDATE Eat_tables set MergeNo = $selectmaintb where EID = $EID AND (";

        $count = 1;
        $responseMsg = '';
        $alreadymearge = array();
        foreach ($_POST as $key => $value) {
            $array=explode("&",$key); 
            $child_tableNo  = $array[0];
            $query .= " TableNo = '". $child_tableNo."'";
            $responseMsg .=$child_tableNo;
            $alreadymearge = array($count => "$child_tableNo");
            if($count < count($_POST)){
                $query .= ' OR ';
                $responseMsg .=', ';
            }
            $count ++; 
        }


        $query .= ')';

        $updateTable = $this->db2->query($query);
        echo $responseMsg;

    }

    public function mergetable_ajax(){
        $EID = authuser()->EID;
        
        extract($_POST);
        $selectmaintb = $this->session->set_userdata('selectmaintb', $tableNo);

        $selectTable = $this->db2->query("SELECT TableNo , MergeNo FROM `Eat_tables` where EID = $EID AND TableNo != $tableNo")->result_array();
                    
        foreach ($selectTable as $key => $value) {
            if(($value['MergeNo'] != '0' || $value['MergeNo'] != '-') && $value['MergeNo'] != $tableNo){
                echo '<div class="checkbox"> <label><input onclick="alreadyMearge(this)" name="'.$value['TableNo'].'&'.$EID.'" type="checkbox" value="'.$value['TableNo'].'">  Table No '.$value['TableNo'].'</label>
                </div>';
            }else if($value['MergeNo'] == $tableNo){
                echo '<div class="checkbox"> <label><input name="'.$value['TableNo'].'&'.$EID.'" type="checkbox" value="'.$value['TableNo'].'" checked>  Table No '.$value['TableNo'].'</label>
                </div>';
            }else{
                echo '<div class="checkbox"> <label><input name="'.$value['TableNo'].'&'.$EID.'" type="checkbox" value="'.$value['TableNo'].'">  Table No '.$value['TableNo'].'</label>
                </div>';
            }                   
        }
    }

    public function offline_orders(){
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        // echo "<pre>";
        // print_r($data);
        // die;
        $data['title'] = 'Offline Orders';
        $this->load->view('rest/offline_order', $data);
    }
// 3p_order_ajax
    public function order_ajax_3p(){
        $CustId = $this->session->userdata('CustId');
        $COrgId = $this->session->userdata('COrgId');
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');
        //$Stall = Session::get('Stall');
        //$Ops = Session::get('Ops');
        $CellNo = $this->session->userdata('CellNo');
        $CustNo = $this->session->userdata('CustNo');
        $MultiKitchen = $this->session->userdata('MultiKitchen');
        //$ONo = Session::get('ONo');
        // $CNo = Session::get('CNo');
        // $TableNo = Session::get('TableNo');
        // $KOTNo = Session::get('KOTNo');
        $CNo = 0;
        $TableNo = 0;
        $KOTNo = 0;
        $ONo = 0;

        if (isset($_POST['searchItem']) && $_POST['searchItem']) {
            $itemName = $_POST['itemName'];

            $likeQry = " (ItemNm like '$itemName%' or i.ItemId like '$itemName%' or i.IMcCd like '$itemName%') ";

            if($this->session->userdata('IMcCdOpt') == 2){
                $likeQry = " (ItemNm like '$itemName%' or i.IMcCd like '$itemName%' or i.ItemId like '$itemName%') ";
            }

            $items = $this->db2->query("SELECT i.ItemId, i.ItemNm, i.Value, i.KitCd, i.PckCharge,mr.Itm_Portion, mc.TaxType,i.IMcCd  FROM MenuItem i ,MenuItemRates mr, MenuCatg mc where mc.MCatgId = i.MCatgId and $likeQry AND i.Stat = 0 AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.Itemid from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID=$EID and md.Chainid=i.ChainId) and mr.ItemId=i.ItemId order by i.Rank")->result_array();
            
            if (!empty($items)) {
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

        if (isset($_POST['searchItemCust']) && $_POST['searchItemCust']) {
            $itemName = $_POST['itemName'];

            if ($ChainId == 0) {
                $items = $this->db2->query("SELECT ItemId, ItemNm, Itm_Portion, i.Value, AvgRtng, ItmDesc, ItemNm as imgSrc, ItemTyp, KitCd, MCatgId, i.FID, i.CID, i.PrepTime, i.NV FROM MenuItem i where ItemNm like '$itemName%' AND Stat = 0 AND i.EID = $EID AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.Itemid from MenuItem_Disabled md where md.ItemId=i.ItemId and md.eid=$EID and md.Chainid=i.ChainId) order by Rank")->result_array();
            } else {
                $items = $this->db2->query("SELECT ItemId, ItemNm, Itm_Portion, Value, AvgRtng, ItmDesc, ItemNm as imgSrc, ItemTyp, KitCd, MCatgId, i.FID, i.CID, i.NV FROM MenuItem i where ItemNm like '$itemName%' AND Stat = 0 AND i.ChainId = $ChainId AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.Itemid from MenuItem_Disabled md where md.ItemId=i.ItemId and md.eid=$EID and md.ChainId=i.ChainId) order by Rank")->result_array();
            }
// echo "<pre>";print_r($items);die;
            if (!empty($items)) {

                foreach ($items as $key => $data) {
                    // if ($ChainId > 0) {
                    //     $imgSrc = base_url()."uploads/c$ChainId/" . trim($data['imgSrc']) . ".jpg";
                    // } else {
                    //     $imgSrc = base_url()."uploads/e$EID/" . trim($data['imgSrc']) . ".jpg";
                    // }
                    // // print_r($imgSrc);
                    // if (!file_exists($imgSrc)) {
                    //     $imgSrc = base_url()."uploads/general/" . trim($data['imgSrc']) . ".jpg";
                    // }

                    // $items[$key]['imgSrc'] = $imgSrc;
                    // $items[$key]['imgSrc'] = ltrim($imgSrc, "../");

                    if ($ChainId > 0) {
                        $imgSrc = "uploads/cChainId/" . trim($data['imgSrc']) . ".jpg";
                    } else {
                        $imgSrc = "uploads/e$EID/" . trim($data['imgSrc']) . ".jpg";
                    }

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

        if (isset($_POST['sendToKitchen']) && $_POST['sendToKitchen']) {
            
            $orderType = $_POST['orderType'];
            $tableNo = $_POST['tableNo'];
            $thirdParty = $_POST['thirdParty'];
            $thirdPartyRef = $_POST['thirdPartyRef'];
            $itemIds = !empty($_POST['itemIds'])?$_POST['itemIds']:array();
            $itemKitCds = !empty($_POST['itemKitCds'])?$_POST['itemKitCds']:0;
            $itemQty = !empty($_POST['itemQty'])?$_POST['itemQty']:0;
            $Itm_Portion = !empty($_POST['Itm_Portion'])?$_POST['Itm_Portion']:0;
            $ItmRate = !empty($_POST['ItmRates'])?$_POST['ItmRates']:0;
            $itemRemarks = !empty($_POST['itemRemarks'])?$_POST['itemRemarks']:0;
            $Stat = $_POST['Stat'];
            $phone = $_POST['phone'];
            $pckValue = !empty($_POST['pckValue'])?$_POST['pckValue']:0;
            $data_type = $_POST['data_type'];
            $CNo = $_POST['CNo'];
            $taxtype = !empty($_POST['taxtype'])?$_POST['taxtype']:0;
            $take_away = !empty($_POST['take_away'])?$_POST['take_away']:0;
            // echo "<pre>";
            // print_r($_POST);
            // exit;

            createCustUser($phone);

            if ($CNo == 0) {
                $CNo = $this->insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $phone, $EID, $ChainId, $ONo, $tableNo,$data_type, $orderType);
            }
            // For KOTNo == 0 Generate New KOT
            // echo $KOTNo;
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

            $success = [];

            // For MultiKitchen 
            #code come from order_detail_ajax
            // $fkotArray = array();
            $oldKitCd = 0;
            
            $orderAmount = 0;
            $itemKitCd = 0;
            $newUKOTNO = 0;
            for ($i = 0; $i < sizeof($itemIds); $i++) {
                $itemKitCd = $itemKitCds[$i];

                    if ($MultiKitchen > 1) {
                    } else {
                        $fkotArray = $KOTNo;
                    }
                    if ($oldKitCd != $itemKitCds[$i]) {
                        // $itemKitCd = $itemKitCds[$i];
                        $getFKOT = $this->db2->query("SELECT max(FKOTNO) as FKOTNO FROM Kitchen WHERE EID=$EID AND KitCd = $itemKitCd")->result_array();
                        $fKotNo = $getFKOT[0]['FKOTNO'];
                        $fKotNo = $fKotNo + 1;
                        $fkotArray[$i] = $fKotNo;
                        $newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
                    } 
                    $oldKitCd = $itemKitCd;
                    // $MultiKitchen > 1
                // } else {
                //     $fkotArray = $KOTNo;
                // }

                $kitchenObj['CNo'] = $CNo;
                $kitchenObj['MCNo'] = $CNo;
                $kitchenObj['CustId'] = 0;
                $kitchenObj['EID'] = $EID;
                $kitchenObj['ChainId'] = $ChainId;
                $kitchenObj['OType'] = $orderType;
                if ($orderType == 101) {
                    $kitchenObj['TPRefNo'] = $thirdPartyRef;
                    $kitchenObj['TPId'] = $thirdParty;
                }
                $kitchenObj['KitCd'] = $itemKitCd;        //$itemKitCds[$i];
                $kitchenObj['FKOTNo'] = $fKotNo;          //$fkotArray[$i];
                $kitchenObj['KOTNo'] = $kotNo;
                $kitchenObj['UKOTNo'] = $newUKOTNO;       //$newUKOTNOArray[$i];
                $kitchenObj['TableNo'] = $tableNo;
                $kitchenObj['MergeNo'] = $tableNo;
                $kitchenObj['ItemId'] = $itemIds[$i];
                $kitchenObj['Qty'] = $itemQty[$i];
                $kitchenObj['TA'] = $take_away[$i];
                $kitchenObj['CustRmks'] = $itemRemarks[$i];
                $kitchenObj['ItmRate'] = $ItmRate[$i];
                $kitchenObj['OrigRate'] = $ItmRate[$i];
                $kitchenObj['PckCharge'] = $pckValue[$i];
                $kitchenObj['Stat'] = 2;
                $kitchenObj['CellNo'] = $phone;
                $kitchenObj['Itm_Portion'] = $Itm_Portion[$i];
                $kitchenObj['TaxType'] = $taxtype[$i];
                // echo "<pre>";print_r($kitchenObj);exit();
                insertRecord('Kitchen', $kitchenObj);

                $orderAmount = $orderAmount + $ItmRate[$i];
            }

            $dArray = array('MCNo' => $CNo, 'MergeNo' => $tableNo,'FKOTNo' => $fKotNo);

            if ($data_type == 'bill') {

                // $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7 AND k1.Stat<>9  AND k1.Stat<>99) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7  AND k1.Stat<>9 AND k1.Stat<>99) GROUP BY k1.EID) as TotPckCharge,  ip.Name as Portions, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.MergeNo  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7  AND k.Stat<>9 AND k.Stat<>10 AND k.Stat<>99) and km.EID = k.EID and km.EID = $EID And k.CNo = km.CNo AND (km.CNo = $CNo OR km.MCNo = $CNo) AND km.BillStat=0 AND TIMEDIFF(Now(), km.LstModDt) < '10:00:00' group by km.CNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm, date(km.LstModDt), k.TaxType, ip.Name, c.ServChrg, c.Tips  order by TaxType, m.ItemNm Asc")->result_array();

                $MergeNo = $tableNo;

                $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  (k.OrigRate*sum(k.Qty)) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7 AND k1.Stat<>9  AND k1.Stat<>99) GROUP BY k1.EID, k1.MCNo) as TotItemDisc,(SELECT sum(k1.PckCharge*k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7  AND k1.Stat<>9 AND k1.Stat<>99) GROUP BY k1.EID, k1.MCNo) as TotPckCharge,   ip.Name as Portions,km.CNo,km.MergeNo, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.CustId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.Stat<>10 AND k.Stat<>99) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.MCNo AND km.MCNo IN (Select km1.MCNo from KitchenMain km1 where km1.MergeNo=$MergeNo group by km1.MergeNo) group by km.MCNo, k.ItemId, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm, date(km.LstModDt), k.TaxType, ip.Name, c.ServChrg, c.Tips  order by k.TaxType, m.ItemNm Asc")->result_array();

                $taxDataArray = array();
                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $SubAmtTax = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    foreach ($kitcheData as $kit ) {

                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                        $discount = $discount + $kit['TotItemDisc'] + $kit['RtngDiscAmt'] + $kit['BillDiscAmt']; 
                        $charge = $charge + $kit['TotPckCharge'] + $kit['DelCharge'];

                        $intial_value = $kit['TaxType'];
                        $CNo = $kit['CNo'];

                        $tax_type_array = array();
                        $tax_type_array[$intial_value] = $intial_value;

                        foreach ($kitcheData as $key => $value) {
                            if($value['TaxType'] != $intial_value){
                                $intial_value = $value['TaxType'];
                                $tax_type_array[$intial_value] = $value['TaxType'];
                            }
                        }

                        foreach ($tax_type_array as $key => $value) {
                            $q = "SELECT t.ShortName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.ItmRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.ItmRate*k.Qty)) - ((sum(k.ItmRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.ItmRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat<>4 AND k.Stat<>6 AND k.Stat<>7  AND k.Stat<>9 AND k.Stat<>99 AND k.Stat<>10) and k.EID=km.EID and k.CNo=km.CNo and (km.CNo=$CNo or km.MCNo =$CNo) and t.TaxType = k.TaxType and t.TaxType = $value  and t.EID= $EID AND km.BillStat = 0 group by t.ShortName,t.TNo,t.TaxPcent, t.TaxType, t.TaxOn, t.TaxGroup, t.Included order by t.rank";
                            // print_r($q);exit();
                            $TaxData = $this->db2->query($q)->result_array();
                            $taxDataArray[$value] = $TaxData;
                        }
                        
                    }
                    //tax calculate
                    for ($index = 0; $index < count($taxDataArray[$initil_value]); $index++) {
                            $element = $taxDataArray[$initil_value][$index];
                            $SubAmtTax = $SubAmtTax + round($element['SubAmtTax'], 2);
                        }

                    $orderAmt = $orderAmt + $SubAmtTax;

                    $this->session->set_userdata('TipAmount', 0);
                    $this->session->set_userdata('itemTotalGross', $orderAmt);
                    $this->session->set_userdata('CustId', 0);
                    $this->session->set_userdata('ONo', 0);
                    $this->session->set_userdata('CustNo', 0);
                    $this->session->set_userdata('COrgId', 0);
                    $this->session->set_userdata('CellNo', '-');
                    $this->session->set_userdata('TableNo', $MergeNo);
                    // grand total
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount;

                    // die;
                    $postData["orderAmount"] = $total;
                    $postData["paymentMode"] = 'RCash';
                    $postData["MergeNo"] = $MergeNo;
                    $custId = $kitcheData[0]['CustId'];
                    $this->session->set_userdata('CustId', $custId);
                    $res = billCreate($EID, $CNo, $postData);
                    if($res['status'] > 0){  
                        $response = [
                            "status" => 1,
                            "msg" => "Bill Created.",
                            "data" => array('billId' => $res['billId'])
                        ];      
                    }else{
                        $response = [
                            "status" => 0,
                            "msg" => "Fail to insert to Billing"
                        ];
                    }
                }
                
            }else{
                $response = [
                        "status" => 1,
                        "msg" => "success",
                        "data" => $dArray 
                    ];
            }
            // print_r($response);exit();
            $CNo = 0;
            $KOTNo = 0;
            echo json_encode($response);
            die();
        }
        if(isset($_POST['get_table_order_items'])){

            $tableno = $_POST['table_no'];
            $q = "SELECT k.*,km.BillStat kmBillStat, i.ItemNm,i.Value from KitchenMain as km, Kitchen as k, MenuItem as i where km.CNo = k.CNo and km.MergeNo = '$tableno' and k.stat not in(0,10, 4, 6, 9, 99, 7) and i.Itemid = k.Itemid";
            $data = $this->db2->query($q)->result_array();
            
            echo json_encode($data);
        }
    }

    // functions
    private function insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $CellNo, $EID, $ChainId, $ONo, $TableNo,$data_type, $orderType)
    {
        // global $CNo, $EType, $CustId, $COrgId, $CustNo, $CellNo, $EID, $ChainId, $ONo;
        $CustId = 0;
        $COrgId = 0;
        $CustNo = 0;

        //  // Check CNo is 0 or not
        if ($CNo == 0) {
            // here was there  tableNo not TableNo 
            // pass the parameter
            $TableNo = $TableNo;
            // $kitchenMainObj
            
            $kitchenMainObj['CustId'] = $CustId;
            $kitchenMainObj['COrgId'] = $COrgId;
            $kitchenMainObj['CustNo'] = $CustNo;
            $kitchenMainObj['CellNo'] = $CellNo;
            $kitchenMainObj['EID'] = $EID;
            $kitchenMainObj['ChainId'] = $ChainId;
            $kitchenMainObj['ONo'] = $ONo;
            $kitchenMainObj['OType'] = $orderType;
            $kitchenMainObj['TableNo'] = $TableNo;
            $kitchenMainObj['MergeNo'] = $TableNo;
            $kitchenMainObj['OldTableNo'] = $TableNo;
            $kitchenMainObj['Stat'] = 2;
            $kitchenMainObj['LoginCd'] = 1;
            $kitchenMainObj['TPRefNo'] = '';
            $kitchenMainObj['TPId'] = 0;
            $kitchenMainObj['MngtRmks'] = '';
            $kitchenMainObj['BillStat'] = 0;
            $kitchenMainObj['BillRefNo'] = 0;
            $kitchenMainObj['payRest'] = 0;
            $CNo = insertRecord('KitchenMain', $kitchenMainObj);
            if (!empty($CNo)) {
                updateRecord('KitchenMain', array('MCNo' => $CNo), array('CNo' => $CNo, 'EID' => $EID));
                $this->session->set_userdata('CNo', $CNo);
                return $CNo;
            }
        } else {
            return $CNo = $CNo;
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
            $data['LstModDt'] = date('Y-m-d H:i:s'); 
            updateRecord('UsersRest', $data, array('RUserId' => authuser()->RUserId) );
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


    public function rmcat(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $RMCatgCd = 0;
            if(isset($_POST['RMCatgCd']) && !empty($_POST['RMCatgCd'])){
                $RMCatgCd = $_POST['RMCatgCd'];
            }

            if(!empty($RMCatgCd)){
                updateRecord('RMCatg', array('RMCatgName' => $_POST['RMCatgName']), array('RMCatgCd' => $RMCatgCd));
                $status = 'success';
                $response = 'Category Updated.';
            }else{
                $check = getRecords('RMCatg', array('RMCatgName' => $_POST['RMCatgName']));
                if(!empty($check)){
                    $response = 'Category Already Exists';
                }else{
                    $cat['RMCatgName'] = $_POST['RMCatgName'];
                    $cat['Stat'] = 0;
                    insertRecord('RMCatg', $cat);
                    $status = 'success';
                    $response = 'Category Inserted';
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['catList'] = getRecords('RMCatg', NULL);
        $data['title'] ='RMCategory';
        $this->load->view('rest/rm_category',$data);
    }

    public function rmitems_list(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $RMCd = 0;
            if(isset($_POST['RMCd']) && !empty($_POST['RMCd'])){
                $RMCd = $_POST['RMCd'];
            }

            if(!empty($RMCd)){
                updateRecord('RMItems', array('RMName' => $_POST['RMName']), array('RMCd' => $RMCd));
                $status = 'success';
                $response = 'RMItem Updated.';
            }else{
                $check = getRecords('RMItems', array('RMName' => $_POST['RMName'], 'RMCatg' => $_POST['RMCatg']));
                if(!empty($check)){
                    $response = 'RMItem Already Exists';
                }else{
                    $cat['RMName'] = $_POST['RMName'];
                    $cat['RMCatg'] = $_POST['RMCatg'];
                    $cat['Stat'] = 0;
                    insertRecord('RMItems', $cat);
                    $status = 'success';
                    $response = 'RMItem Inserted';
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['catList'] = getRecords('RMCatg', NULL);
        $data['rm_items'] = $this->rest->getItemLists();
        $data['title'] ='RMItems List';
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('rest/rm_items',$data);
    }

    public function bom_dish(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $bom['ItemId'] = $_POST['ItemId'];
            for ($i=0; $i < sizeof($_POST['RMCd']) ; $i++) { 
                $BOMNo = 0;
                if(isset($_POST['BOMNo'][$i]) && !empty($_POST['BOMNo'][$i])){
                    $BOMNo = $_POST['BOMNo'][$i];
                }
                $bom['RMCd'] = $_POST['RMCd'][$i];
                $bom['RMQty'] = $_POST['RMQty'][$i];
                $bom['RMUOM'] = $_POST['RMUOM'][$i];
                if(!empty($BOMNo)){
                    updateRecord('BOM_Dish', $bom, array('BOMNo' => $BOMNo) );
                    $response = 'Data updated.';
                }else{
                    insertRecord('BOM_Dish', $bom);
                    $response = 'Data inserted.';
                }
            }

            $status = 'success';
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['cuisine'] = $this->db2->get('Cuisines')->result_array();
        // $data['bom_dish'] = $this->rest->getBomDishLists();
        $data['rm_items'] = $this->rest->getItemLists();
        $data['title'] ='Bill Of Material';
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('rest/bom_dish',$data);
    }

    public function getMenuItemList(){
        if($_POST){
            $mcatid = $_POST['MCatgId'];
            $data = $this->db2->select('ItemId, ItemNm')
                              ->get_where('MenuItem', array('MCatgId' => $mcatid))
                              ->result_array();
            echo json_encode($data);
        }
    }

    public function getRMItemsUOMList(){
        if($_POST){
            $RMCd = $_POST['RMCd'];
            $data = $this->rest->getRmUOMlist($RMCd);
            echo json_encode($data);
        }
    }

    public function get_bom_dish(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
           
            $response = '';
            $data = $this->db2->get_where('BOM_Dish', array('ItemId' => $_POST['item']))->result_array();
            $temp = '';
            if(!empty($data)){
                $count = 0;
                foreach ($data as $key) {
                    $count++;
                    $temp .= '<tr>
                            <td>'.$this->getRMName($key['RMCd'], $count).'</td>
                            <td>
                            <input type="number" class="form-control" name="RMQty[]" placeholder="Quantity" required="" id="RMQty" value="'.$key['RMQty'].'">
                            </td>
                            <td>'.$this->getRMUOM($key['RMCd'], $key['RMUOM'], $count).'</td>
                            <td>
                            <input type="hidden" name="BOMNo[]" value="'.$key['BOMNo'].'" />
                            </td>
                        </tr>';
                }
                $response = $temp;
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    private function getRMName($RMCd, $count){
        $rm_items = $this->rest->getItemLists();
        $temp = '<select name="RMCd[]" id="RMCd_'.$count.'" class="form-control" required="" onchange="getRMItemsUOM('.$count.')">
                                <option value="">Select</option>';
        foreach ($rm_items as $row ) {
            $select = '';
            if($row['RMCd'] == $RMCd){
                $select = 'selected';
            }
            $temp .= '<option value="'.$row['RMCd'].'" '.$select.'>'.$row['RMName'].'</option>';
        }
        $temp .= '</select>';
        return $temp;
    }

    private function getRMUOM($RMCd, $UOMCd, $count){
        $data['RMUOM'] = $this->rest->getRmUOMlist($RMCd);
        $temp = '<select name="RMUOM[]" id="RMUOM_'.$count.'" class="form-control" required="">
                                <option value="">Select RMUOM</option>';
        foreach ($data['RMUOM']as $row ) {
            $select = '';
            if($row['UOMCd'] == $UOMCd){
                $select = 'selected';
            }
            $temp .= '<option value="'.$row['UOMCd'].'" '.$select.'>'.$row['Name'].'</option>';
        }
        $temp .= '</select>';
        return $temp;
    }
    
    public function get_billing_details(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            extract($_POST);
            $data = $this->rest->getBillDetailsForSettle($custId, $MCNo, $mergeNo);
            // echo "<pre>";
            // print_r($data);
            // die;
            $status = 'success';
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }
    }

    function get_cash_collect(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            extract($_POST);
            if($custId > 0){
                $this->db2->where('b.CustId', $custId);
            }
            $data['bills'] = $this->db2->select("b.TableNo,b.MergeNo, b.BillId, b.BillNo, b.CNo, b.TotAmt, b.PaidAmt, b.CustId, b.EID, b.MergeNo,b.CellNo")
                        ->order_by('b.BillId','DESC')
                        ->get_where('Billing b', array('b.EID' => authuser()->EID,
                                                        'b.MergeNo' => $mergeNo,
                                                        'b.CNo' => $MCNo
                                                )
                                   )
                        ->row_array();
            $data['payModes'] = $this->db2->get_where('PymtModes', array('Stat' => 0))->result_array();
            // echo "<pre>";
            // print_r($data);
            // die;
            $status = 'success';
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }   
    }

    public function collect_payment()
    {
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            // echo "<pre>";
            // print_r($_POST);

            $pay = $_POST;
            $pay['PaymtMode'] = 1;
            $pay['SplitTyp'] = 0;
            $pay['SplitAmt'] = 0;
            $pay['PymtId'] = 0;
            $pay['OrderRef'] = 0;
            $pay['PymtType'] = 0;
            $pay['PymtRef'] = 0;
            $pay['Stat'] = 0;
            $TableNo = $pay['TableNo'];
            unset($pay['TableNo']);
            // print_r($pay);
            if($pay['oType']==8){
                unset($pay['oType']);
                $payNo = insertRecord('BillPayments', $pay);
                updateRecord('KitchenMain', array('custPymt' => 1), array('MCNo' => $pay['MCNo'],'EID' => $pay['EID']));
                $status = 'success';
                
            }else if($pay['oType']==7){
                
                $checkBP = $this->db2->get_where('BillPayments', array('EID' => $pay['EID'],'BillId' => $pay['BillId']))->row_array();
                if(!empty($checkBP)){
                    updateRecord('BillPayments', array('PymtType' => $pay['PymtType']), array('EID' => $pay['EID'],'BillId' => $pay['BillId']));
                }else{
                    unset($pay['oType']);
                    $payNo = insertRecord('BillPayments', $pay);
                }

                $status = 'success';
            }
            
            if($status == 'success'){
                if($this->session->userdata('AutoSettle') == 1){
                    $this->autoSettlePayment($pay['BillId'], $pay['MCNo'], $pay['MergeNo'], $TableNo);
                }

                $response = 'Payment Collected';
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        } 
    }

    public function test(){
        echo "<pre>";
        print_r($_SESSION);
        die;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            echo "<pre>";
            print_r($_POST);
            die;
            $status = 'success';
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] ='dd';
        $this->load->view('rest/blank',$data);
    }

    public function billCreateRest(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $MergeNo = $_POST['mergeNo'];
            $EID = authuser()->EID;
             // SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt,

            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT(m.ItemNm, ' - ' , k.CustItemDesc)),(m.ItemNm ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  (k.OrigRate*sum(k.Qty)) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7 AND k1.Stat<>9  AND k1.Stat<>99) GROUP BY k1.EID, k1.MCNo) as TotItemDisc,(SELECT sum(k1.PckCharge*k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat<>4 AND k1.Stat<>6 AND k1.Stat<>7  AND k1.Stat<>9 AND k1.Stat<>99) GROUP BY k1.EID, k1.MCNo) as TotPckCharge,   ip.Name as Portions,km.CNo,km.MergeNo, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.CustId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat<>4 and k.Stat<>6 AND k.Stat<>7 AND k.Stat<>10 AND k.Stat<>99) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.MCNo AND km.MCNo IN (Select km1.MCNo from KitchenMain km1 where km1.MergeNo=$MergeNo group by km1.MergeNo) group by km.MCNo, k.ItemId, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm, date(km.LstModDt), k.TaxType, ip.Name, c.ServChrg, c.Tips  order by k.TaxType, m.ItemNm Asc")->result_array();
            // echo "<pre>";
            // print_r($kitcheData);
            // print_r($this->db2->last_query());
            // die;
            
                $taxDataArray = array();
                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $SubAmtTax = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    foreach ($kitcheData as $kit ) {

                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                        // $discount = $discount + $kit['TotItemDisc'] + $kit['RtngDiscAmt'] + $kit['BillDiscAmt']; 
                        // $charge = $charge + $kit['TotPckCharge'] + $kit['DelCharge'];

                        $intial_value = $kit['TaxType'];
                        $CNo = $kit['CNo'];

                        $tax_type_array = array();
                        $tax_type_array[$intial_value] = $intial_value;

                        foreach ($kitcheData as $key => $value) {
                            if($value['TaxType'] != $intial_value){
                                $intial_value = $value['TaxType'];
                                $tax_type_array[$intial_value] = $value['TaxType'];
                            }
                        }

                        foreach ($tax_type_array as $key => $value) {
                            $q = "SELECT t.ShortName,t.TaxPcent,t.TNo, t.TaxType, t.Rank, t.TaxOn, t.TaxGroup, t.Included,k.ItmRate, k.Qty,k.ItemId, (sum(k.ItmRate*k.Qty)) as ItemAmt, (if (t.Included <5,((sum(k.ItmRate*k.Qty)) - ((sum(k.ItmRate*k.Qty)) / (1+t.TaxPcent/100))),((sum(k.ItmRate*k.Qty))*t.TaxPcent/100))) as SubAmtTax from Tax t, KitchenMain km, Kitchen k where (k.Stat<>4 AND k.Stat<>6 AND k.Stat<>7  AND k.Stat<>9 AND k.Stat<>99 AND k.Stat<>10) and k.EID=km.EID and k.CNo=km.CNo and (km.CNo=$CNo or km.MCNo =$CNo) and t.TaxType = k.TaxType and t.TaxType = $value  and t.EID= $EID AND km.BillStat = 0 group by t.ShortName,t.TNo,t.TaxPcent, t.TaxType, t.TaxOn, t.TaxGroup, t.Included order by t.rank";
                            // print_r($q);exit();
                            $TaxData = $this->db2->query($q)->result_array();
                            $taxDataArray[$value] = $TaxData;
                        }
                        
                    }

                    // add 16-nov-23
                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    $charge = $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    // add 16-nov-23 end
                    //tax calculate
                    for ($index = 0; $index < count($taxDataArray[$initil_value]); $index++) {
                            $element = $taxDataArray[$initil_value][$index];
                            $SubAmtTax = $SubAmtTax + round($element['SubAmtTax'], 2);
                        }

                    $orderAmt = $orderAmt + $SubAmtTax;
                    $custDiscount = ($orderAmt * $_POST['custDiscPer']) / 100;

                    $this->session->set_userdata('TipAmount', 0);
                    $this->session->set_userdata('itemTotalGross', $orderAmt);
                    $this->session->set_userdata('CustId', 0);
                    $this->session->set_userdata('ONo', 0);
                    $this->session->set_userdata('CustNo', 0);
                    $this->session->set_userdata('COrgId', 0);
                    $this->session->set_userdata('CellNo', '-');
                    $this->session->set_userdata('TableNo', $MergeNo);
                    // grand total
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount - $custDiscount;

                    // die;
                    $postData["orderAmount"] = $total;
                    $postData["paymentMode"] = 'RCash';
                    $postData["MergeNo"] = $MergeNo;
                    $postData["cust_discount"] = $custDiscount;
                    $custId = $kitcheData[0]['CustId'];
                    $this->session->set_userdata('CustId', $custId);
                    $res = billCreate($EID, $CNo, $postData);
                    if($res['status'] > 0){
                        updateRecord('KitchenMain', array('discount' => $_POST['custDiscPer']), array('CNo' => $CNo, 'MergeNo' => $MergeNo,'EID' => $EID));
                        $status = 'success';
                        $response = $res['billId'];         
                    }
                }else{
                    $response = 'Bill Already Generated.';
                }
                // print_r($taxDataArray);
                // print_r($orderAmt);echo "<br>";
                // print_r($SubAmtTax);echo "<br>";
                // print_r($total);
                // die;
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function bill($billId){

        $data['title'] = 'Billing';
        $data['billId'] = $billId;
        $this->load->view('rest/billing', $data);

    }

    public function payments(){
        // echo "<pre>";
        // print_r($_SESSION);
        // die;
        $data['title'] = 'Customer Payments';
        $data['fdate'] = date('Y-m-d');
        $data['tdate'] = date('Y-m-d');
        $data['pmode'] = '';
        $pdata = array
                    (
                        'fdate' => $data['fdate'],
                        'tdate' => $data['tdate'],
                        'pmode' => $data['pmode']
                    );
        if($this->input->method(true)=='POST'){
            $pdata = $_POST;
            $data['fdate'] = date('Y-m-d', strtotime($pdata['fdate']));
            $data['tdate'] = date('Y-m-d', strtotime($pdata['tdate']));
            $data['pmode'] = $pdata['pmode'];
        }
        $data['details'] = $this->rest->getPaymentList($pdata);
        $data['modes'] = $this->rest->getPaymentModes();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('rest/payments', $data);
    }

    public function feedback(){
        $data['title'] = 'Customer Feedback';
        $data['list'] = $this->db2->order_by('created_at','DESC')->get('Feedback')->result_array();
        
        $this->load->view('rest/feedback_list', $data);
    }

    public function email_send(){
        $apiKey = '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';

        $url = 'https://api.sendgrid.com/v3/mail/send';

        $to = 'vijayyadav132200@gmail.com';
        $from = 'sanjayn@gmail.com';
        $subject = 'Sendgrid testing email email';

        $body = 'You will be prompted to choose the level of access or permissions for this API key. Select the appropriate permissions based on what you need. Typically';

        $data = [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $to]
                    ],
                    'subject' => $subject,
                ]
            ],
            'from' => [
                'email' => $from
            ],
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => $body
                ]
            ]
        ];


        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        echo $response;
    }

    public function link_generate(){
        include APPPATH.'third_party/phpqrcode/qrlib.php'; 
        $data['title'] = 'Link Generate';
        $data['eid'] = '';
        $data['chain'] = '';
        $data['table'] = '';
        $data['stock'] = '';
        
        $listData = array();

        if($this->input->method(true)=='POST'){
            $from = 0;
            $to = 0;

            $data['eid'] = authuser()->EID;
            $data['chain'] = authuser()->ChainId;

            $codesDir = "uploads/qrcode/";   

            if(!empty($_POST['qrcode'])){
                if($_POST['qrcode'] == 'stall'){
                    $from = $_POST['from_stall'];
                    $to = $_POST['to_stall'];

                    for ($i=$from; $i <= $to ; $i++) { 

                        $link = 'e='.$data['eid'].'&c='.$data['chain'].'&t=0&o='.$i;
                        $link64 = base64_encode($link);
                        $temp['link'] = base_url('qr?qr_data=').rtrim($link64, "=");
                        $temp['tblNo'] = $i;

                        $codeFile = date('d-m-Y-h-i-s').'.png';
                        $formData = $temp['link'];
                        // generating QR code
                        QRcode::png($formData, $codesDir.$codeFile, $level = 'H', $size = 5);
                        $temp['img'] = $codeFile;

                        $listData[] = $temp;
                    }
                }else{
                    $from = $_POST['from_table'];
                    $to = $_POST['to_table'];

                    for ($i=$from; $i <= $to ; $i++) { 

                        $link = 'e='.$data['eid'].'&c='.$data['chain'].'&t='.$i.'&o=0';
                        $link64 = base64_encode($link);
                        $temp['link'] = base_url('qr?qr_data=').rtrim($link64, "=");
                        $temp['tblNo'] = $i;

                        $codeFile = date('d-m-Y-h-i-s').'.png';
                        $formData = $temp['link'];
                        // generating QR code
                        QRcode::png($formData, $codesDir.$codeFile, $level = 'H', $size = 5);
                        $temp['img'] = $codeFile;

                        $listData[] = $temp;
                    }
                }
            
            }

        }
        
        // echo "<pre>";
        // print_r($listData);
        // die;
        $data['lists'] = $listData;
        $this->load->view('rest/link_create', $data);   
    }

    public function add_item(){

        if($this->input->method(true)=='POST'){

            $getItem = $this->db2->select('UItmCd, Rank')->order_by('ItemId', 'DESC')->get('MenuItem')->row_array();

            $data = $_POST;

            $data['UItmCd'] = $getItem['UItmCd']+1;
            $data['Rank'] = $getItem['Rank']+1;
            $data['EID'] = authuser()->EID;
            $data['ChainId'] = authuser()->ChainId;
            $data['Stall'] = 0;
            $data['MTyp'] = 0;
            $data['Value'] = 0;
            $data['Itm_Portion'] = 0;
            $data['AvgRtng'] = '3.5';
            $data['Stat'] = 0;
            $data['LoginCd'] = authuser()->RUserId;

            $flag = 0;
            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;
            if(isset($_FILES['item_file']['name']) && !empty($_FILES['item_file']['name'])){ 

                $files = $_FILES['item_file'];

                $allowed = array('jpeg', 'jpg');
                $filename_c = $_FILES['item_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only jpg,jpeg format!');
                }
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }

                $_FILES['item_file']['name']= $files['name'];
                $_FILES['item_file']['type']= $files['type'];
                $_FILES['item_file']['tmp_name']= $files['tmp_name'];
                $_FILES['item_file']['error']= $files['error'];
                $_FILES['item_file']['size']= $files['size'];
                $file = $data['ItemNm'];

                $folderPath = 'uploads/e'.authuser()->EID;

                if($flag == 0){
                    $res = do_upload('item_file',$file,$folderPath,'*');
                }
                
              }

            if($flag == 0){
              $ItemId = insertRecord('MenuItem', $data);
              if(!empty($ItemId)){
                $this->session->set_flashdata('success','Record Inserted.');
              }
            }
        }
        
        $data['title'] = 'Add Item';
        $data['MCatgIds'] = $this->rest->get_MCatgId();
        $data['CuisineList'] = $this->rest->getCuisineList();
        $data['FoodType'] = $this->rest->get_foodType();
        $data['Eat_Kit'] = $this->rest->get_kitchen();
        // $data['EatSections'] = $this->rest->get_eat_section();
        // $data['ItemPortions'] = $this->rest->get_item_portion();
        
        $this->load->view('rest/add_item', $data);
    }

    public function get_item_name(){
        extract($_POST);
        $data = $this->rest->get_item_name_list($name);
        echo json_encode($data);
        die;
    }

    public function edit_item($ItemId){

        if($this->input->method(true)=='POST'){
 
            $updateData = $_POST;

            $flag = 0;

            if(isset($_FILES['item_file']['name']) && !empty($_FILES['item_file']['name'])){ 

                // remove existing file
                $folderPath = 'uploads/e'.authuser()->EID;
                $filename = $folderPath.'/'.$updateData['ItemNm'].'.jpg'; 
                if (file_exists($filename)) {
                    unlink($filename);
                }

                $files = $_FILES['item_file'];

                $allowed = array('jpeg', 'jpg');
                $filename_c = $_FILES['item_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only jpg,jpeg format!');
                }
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }

                $_FILES['item_file']['name']= $files['name'];
                $_FILES['item_file']['type']= $files['type'];
                $_FILES['item_file']['tmp_name']= $files['tmp_name'];
                $_FILES['item_file']['error']= $files['error'];
                $_FILES['item_file']['size']= $files['size'];
                // $file = str_replace(' ', '_', rand('10000','999').'_'.$files['name']);
                $file = $updateData['ItemNm'];
                if($flag == 0){
                    $res = do_upload('item_file',$file,$folderPath,'*');
                }
              }

            if($flag == 0){
                unset($updateData['ItemId']);
                updateRecord('MenuItem', $updateData, array('ItemId' => $ItemId));
                $this->session->set_flashdata('success','Record Updated.');
            }
        }
        $data['title'] = 'Edit Item';
        $data['ItemId'] = $ItemId;
        $data['MCatgIds'] = $this->rest->get_MCatgId();
        $data['CuisineList'] = $this->rest->getCuisineList();
        $data['FoodType'] = $this->rest->get_foodType();
        $data['Eat_Kit'] = $this->rest->get_kitchen();
        // $data['EatSections'] = $this->rest->get_eat_section();
        // $data['ItemPortions'] = $this->rest->get_item_portion();
        $data['detail'] = $this->db2->get_where('MenuItem', array('ItemId' => $ItemId))->row_array();
        $this->load->view('rest/edit_item', $data);
    }

    public function print($billId){

        $data['billId'] = $billId;

        $EID = authuser()->EID;

        $detail = $this->db2->select('CustId,CustNo,CellNo')->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))->row_array();
        $CustId = $detail['CustId'];

        $data['CustNo'] = $detail['CustNo'];

        $dbname = $this->session->userdata('my_db');

        $flag = 'rest';
        $res = getBillData($dbname, $EID, $billId, $CustId, $flag);
        // echo "<pre>";
        // print_r($res);
        // die;
        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];

            $data['hotelName'] = $billData[0]['Name'];
            $data['TableNo'] = $billData[0]['TableNo'];
            $data['Fullname'] = $billData[0]['FName'].' '.$billData[0]['LName'];
            $data['CellNo'] = $billData[0]['MobileNo'];
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
        }
        $this->load->view('print', $data);
    }

    public function kot_print($MCNo, $mergeNo, $FKOTNo){
        $data['kotList'] = $this->rest->getKotList($MCNo, $mergeNo, $FKOTNo);
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('rest/kots_print', $data);
    }

    public function print_pdf(){
        // https://stackoverflow.com/questions/37831516/dompdf-with-codeigniter
        $this->load->library('pdf');
         $billId =17;
        $data['billId'] = $billId;

        $EID = authuser()->EID;

        $detail = $this->db2->select('CustId,CustNo,CellNo')->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))->row_array();
        $CustId = $detail['CustId'];

        $data['CustNo'] = $detail['CustNo'];
        $data['CellNo'] = $detail['CellNo'];

        $dbname = $this->session->userdata('my_db');

        $res = getBillData($dbname, $EID, $billId, $CustId);
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
        $this->pdf->load_view('print', $data);
    }

    public function bill_settle(){
        if ($_POST['setPaidAmount']) {
            // echo "<pre>";
            // print_r($_POST);
            // die;
            extract($_POST);
            $id = $_POST['id'];
            // $mode = $_POST['mode'];
            $cNo = $_POST['CNo'];
            $EID = authuser()->EID;
            $EType = $this->session->userdata('EType');

            $q1 = "UPDATE Billing  SET Stat = 1,payRest=1  WHERE BillId = $id AND EID = $EID";
            $billData = $this->db2->query($q1);
            // print_r($q1);
            updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $id,'EID' => $EID));

            $q2 = "UPDATE Kitchen k, KitchenMain km SET k.payRest=1, km.payRest=1 WHERE (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
            $kitchenUpdate = $this->db2->query($q2);

            if ($EType == 5) {
                // $q3 = "DELETE from Eat_tables_Occ where EID=$EID and CNo = $cNo AND ((TableNo = '$TableNo' AND CustId = $CustId) OR (MergeNo = '$TableNo'))";
                // $deleteETO = $this->db2->query($q3);

              $this->db2->query("UPDATE Eat_tables SET Stat = 0 WHERE EID = $EID AND MergeNo = $MergeNo");
            }

            // store to gen db
            $genTblDb = $this->load->database('GenTableData', TRUE);
            $custPymtObj['BillId'] = $id;
            $custPymtObj['BillNo'] = $billNo;
            $custPymtObj['EID'] = $EID;
            $custPymtObj['PaidAmt'] = $billAmt;
            $custPymtObj['PaymtMode'] = $pymtMode;
            // $genTblDb->insert('CustPymts', $custPymtObj);

            $response = [
                "status" => 1,
                "msg" => "Billing Updated"
            ];

            echo json_encode($response);
            die();
        }
    }

    private function autoSettlePayment($billId, $cNo, $MergeNo, $TableNo){

        $EID = authuser()->EID;
        $EType = $this->session->userdata('EType');

        $q1 = "UPDATE Billing  SET Stat = 1,payRest=1  WHERE BillId = $billId AND EID = $EID";
        $billData = $this->db2->query($q1);
        // print_r($q1);
        updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));

        $q2 = "UPDATE Kitchen k, KitchenMain km SET k.payRest=1, km.payRest=1 WHERE (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
        $kitchenUpdate = $this->db2->query($q2);

        if ($EType == 5) {
            updateRecord('Eat_tables', array('Stat' => 0, 'MergeNo' => $TableNo), array(' EID' => $EID,'MergeNo' => $MergeNo));
        }        
    }

    public function kds(){
        $EID = authuser()->EID;
        $data['title'] = 'Kitchen Display System';
        $minutes = 0;
        $kitcd = 0;
        if($this->input->method(true)=='POST'){
            $minutes = $_POST['minutes'];
            $kitcd = $_POST['kitchen'];
            
        }
        $data['minutes'] = $minutes;
        $data['kitcd'] = $kitcd;
        $data['kds'] = $this->rest->getPendingKOTLIST($minutes, $kitcd);
        $data['kitchen'] = $this->db2->select('KitCd, KitName')->get_where('Eat_Kit', array('Stat' => 0, 'EID' => $EID))->result_array();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('rest/kds', $data);
    }

    public function updateKotStat(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
           
            extract($_POST);
            $EID = authuser()->EID;
            $today = date('Y-m-d H:i:s');
            $this->db2->query("UPDATE Kitchen set KStat = 5, DelTime = '$today' where EID = $EID and OrdNo in ($ordNo) ");
            $status = 'success';
            $response = 'KOT is closed.';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function kitchen_planner(){
        $EID = authuser()->EID;
        $data['title'] = 'Kitchen Planner';
        $data['kplanner'] = array();
        $kitcd = 0;
        if($this->input->method(true)=='POST'){
            $kitcd = $_POST['kitchen'];
            $data['kplanner'] = $this->rest->getPendingItemLIST($kitcd);
        }
        
        $data['kitcd'] = $kitcd;
        $data['kitchen'] = $this->db2->select('KitCd, KitName')->get_where('Eat_Kit', array('Stat' => 0, 'EID' => $EID))->result_array();
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('rest/kitchen_plan', $data);
    }

    public function checkCustDiscount(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $custId = $_POST['custId'];

            $data = $this->db2->select('uId, FName,CustId, Disc, visit')
                            ->get_where('Users', array('CustId' => $custId))
                            ->row_array();
            if(!empty($data)){
                $response = $data;
            }
            // echo "<pre>";
            // print_r($data);die;
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }


    public function sendSMS_test()
    {

//         $curl = curl_init();
// $apikey = '7652383520739183947';//if you use apikey then userid and password is not required
// $userId = 'vtrend';
// $password = 'Sn197022';
// $sendMethod = 'simpleMsg'; //(simpleMsg|groupMsg|excelMsg)
// $messageType = 'text'; //(text|unicode|flash)
// $senderId = 'EATOUT';
// $mobile = '917697807008';//comma separated
// $msg = "12333 is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
// $scheduleTime = '';//mention time if you want to schedule else leave blank

// curl_setopt_array($curl, array(
//   CURLOPT_URL => "http://www.smsgateway.center/SMSApi/rest/send",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//   CURLOPT_POSTFIELDS => "userId=$userId&password=$password&senderId=$senderId&sendMethod=$sendMethod&msgType=$messageType&mobile=$mobile&msg=$msg&duplicateCheck=true&format=json",
//   CURLOPT_HTTPHEADER => array(
//     "cache-control: no-cache",
//     "content-type: application/x-www-form-urlencoded"
//   ),
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   echo $response;
// }
        sendSMS('7697807008', '541');
        // sendSMS('8850876764', '54122');
    }



}
