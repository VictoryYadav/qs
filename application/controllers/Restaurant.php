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

        $this->lang->load('message','english');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
        
        $this->output->delete_cache();

	}

    public function index(){
        $data['title'] = 'Dashboard';
        $this->load->view('rest/index',$data);
    }

    function switchLang() {
        // https://www.codexworld.com/multi-language-implementation-in-codeigniter/
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
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

    public function add_user(){

        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            if($_POST['RUserId'] > 0){
                $res = getRecords('UsersRest', array('RUserId' => $_POST['RUserId']));
                $udata = $_POST;
                $udata['DOB'] = date('Y-m-d', strtotime($udata['DOB'])); 
                updateRecord('UsersRest', $udata, array('RUserId' => $_POST['RUserId'], 'EID' => authuser()->EID));
                $genInsert = 0;
                if($res['Stat'] != $udata['Stat']){
                    $genInsert = 1;
                }
                if($res['UTyp'] != $udata['UTyp']){
                    $genInsert = 1;
                }
                if($res['RestRole'] != $udata['RestRole']){
                    $genInsert = 1;
                }

                if($genInsert == 1){
                    unset($udata['UTyp']);
                    unset($udata['RestRole']);
                    $genDB = $this->load->database('GenTableData', TRUE);
                    $genDB->insert('UsersRest', $udata);
                }

                $res = 'Records Updated';
            }else{
                $res = $this->rest->addUser($_POST);
            }
            $this->session->set_flashdata('success',$res); 
            redirect(base_url('restaurant/add_user'));
        }

		$data['title'] = $this->lang->line('addUser');
        $data['EID'] = authuser()->EID;
        $data['users'] = $this->rest->getUserList();
        $data['restRole'] = $this->rest->getUserRestRole();
        $data['userType'] = $this->rest->getUserTypeList();
		$this->load->view('rest/add_user',$data);
    }

    public function user_access(){
        if($this->input->method(true)=='POST'){
            $res = $this->rest->getUserAccessRole($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'success',
                'response' => $res
              ));
             die;
        }
        
        $data['usersRestData'] = $this->rest->getusersRestData(); 
        $data['title'] = $this->lang->line('userAccess');
        $this->load->view('rest/access_users',$data);
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
            $data['LoginCd'] = authuser()->RUserId;
            
            $check = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();

            if(!empty($check)){
                unset($data['RUserId']);
                updateRecord('UsersRoleDaily', $data, array('RUserId' => $RUserId));
                $res = 'Role Assigned Updated.';
            }else{
                insertRecord('UsersRoleDaily',$data);
                $res = 'Role Assigned Successfully.';
            }
            redirect(base_url('restaurant/role_assign'));
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
            
        }
        
        $data['usersRestData'] = $this->rest->getusersRestData();        

        $data['title'] = $this->lang->line('roleAssignment');
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

            $langId = $this->session->userdata('site_lang');
            $KitName = "KitName$langId as KitName";

            $kitData = $this->rest->get_kitchen();
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

            $disData = $this->rest->getDispenseOutletList();
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

            $casherData = $this->rest->getCashierList();

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

            $chefT = $this->lang->line('chef');
            $dispenseT = $this->lang->line('dispense');
            $cashierT = $this->lang->line('cashier');
            $roleT = $this->lang->line('role');
            $ARoleT = $this->lang->line('assignedRoles');
            $submitT = $this->lang->line('submit');
            
            $data['createForm'] = '<form class="mt-2" id="roleAssignForm" method="POST">
                <input type="hidden" name="RUserId" value="'.$RUserId.'">
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>'.$roleT.'</th>
                        <th>'.$ARoleT.'</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>'.$chefT.'</td>
                            <td>'.$kitchen.'</td>
                        </tr>

                        <tr>
                            <td>'.$dispenseT.'</td>
                            <td>'.$dispense.'</td>
                        </tr>

                        <tr>
                            <td>'.$cashierT.'</td>
                            <td>'.$cashier.'</td>
                        </tr>
                    </tbody>
                  </table>
              </div>
              <div class="text-center">
                <button class="btn btn-sm btn-success" onclick="submitData()">'.$submitT.'</button>
              </div>
            </form>';

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
		 $data['title'] = $this->lang->line('offerList');
		 $data['offers'] = $this->rest->getOffersList();

         // echo "<pre>";
         // print_r($data);
         // die;
		 $this->load->view('rest/offer_lists',$data);
    }

    public function new_offer(){
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $ChainId = authuser()->ChainId;

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;
            
            $CustOffers['LoginCd'] = authuser()->RUserId;
            $CustOffers['EID'] = $EID;
            $CustOffers['ChainId'] = $ChainId;
            $CustOffers['SchNm1'] = $_POST['SchNm'];
            $CustOffers['SchNm2'] = '-';
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
            
            $SchCd = insertRecord('CustOffers', $CustOffers);
            if(!empty($SchCd)){
                $updat['PromoCode'] = $SchCd.'~'.$EID.'~'.$ChainId.'~'.$_POST['SchTyp'].'~'.$_POST['SchCatg'];
                updateRecord('CustOffers', $updat, array('SchCd' => $SchCd));
            }

            $CustOffersDet = [];
            $temp = [];

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
                        $temp['SchImg'] = $file;
                    }

                    $temp['SchCd'] = $SchCd;
                    $temp['SchDesc1'] = $_POST['description'][$i];
                    $temp['SchDesc2'] = $_POST['description'][$i];
                    $temp['CID'] = $_POST['description_cid'][$i];
                    $temp['MCatgId'] = $_POST['description_mcatgid'][$i];
                    $temp['ItemTyp'] = $_POST['description_itemtyp'][$i];
                    $temp['ItemId'] = $_POST['description_item'][$i];
                    $temp['IPCd'] = $_POST['description_itemportion'][$i];
                    $temp['Qty'] = $_POST['description_quantity'][$i];
                    $temp['Disc_ItemId'] = $_POST['description_discountitem'][$i];
                    $temp['Disc_IPCd'] = $_POST['description_discountitemportion'][$i];
                    $temp['Disc_Qty'] = $_POST['description_discountquantity'][$i];
                    $temp['DiscItemPcent'] = $_POST['description_discountitempercentage'][$i];
                    $temp['MinBillAmt'] = $_POST['description_minbillamount'][$i];
                    $temp['Disc_pcent'] = $_POST['description_discountpercent'][$i];
                    $temp['Disc_Amt'] = $_POST['description_discountamount'][$i];
                    $temp['Rank'] = 1;
                    $temp['Stat'] = 0;
                    $CustOffersDet[] = $temp;
                }
            }

            $this->db2->insert_batch('CustOffersDet', $CustOffersDet); 
            $this->session->set_flashdata('success','Offer has been added.'); 
            redirect(base_url('restaurant/offers_list'));
        }

    	$data['title'] = $this->lang->line('newOffer');

        $data['sch_typ'] = $this->rest->getOffersSchemeType();
        $data['sch_cat'] = $this->rest->getOffersSchemeCategory(1);
        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['cuisines'] = $this->rest->getCuisineList();
        $data['foodType'] = $this->rest->get_foodType();

		$this->load->view('rest/add_new_offer',$data);	
    }

    public function offer_ajax(){
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;

        if (isset($_POST['updateOffer'])) {
            
            $SchCd = $_POST['SchCd'];
            $CustOffers['SchNm1'] = $_POST['SchNm'];
            $CustOffers['SchNm2'] = '-';
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
            updateRecord('CustOffers',$CustOffers, array('SchCd' => $SchCd, 'EID' => $EID));
            
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
                    $CustOffersDet['SchDesc1'] = $_POST['description'][$i];
                    $CustOffersDet['SchDesc2'] = $_POST['description'][$i];
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
                    $CustOffersDet['DiscItemPcent'] = $_POST['description_discountitempercentage'][$i];
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
            $CID = $_POST['cid'];
            
            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as MCatgNm";

            $categories = $this->rest->getMenuCatListByCID($EID, $CID);

            echo json_encode($categories);
        }
        if(isset($_POST['getItems']) && $_POST['getItems']){

            $langId = $this->session->userdata('site_lang');
            $lname = "ItemNm$langId as ItemNm";

            $mcatgid = $_POST['mcatgid'];
            $items = $this->db2->select("ItemId, $lname")->get_where('MenuItem', array('MCatgId' => $mcatgid, 'EID' => authuser()->EID))->result_array();
            // echo "<pre>";print_r($CustOffers);exit();
            echo json_encode($items);
        }

        if(isset($_POST['getAllItemsList']) && $_POST['getAllItemsList']){
            $items = $this->rest->getAllItemsList();
            echo json_encode($items);
        }

        if(isset($_POST['getItemPortion']) && $_POST['getItemPortion']){
            $item_id = $_POST['item_id'];

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as Name";
            $portions = $this->db2->select("IPCd, $lname")
                        ->join('MenuItemRates mir','mir.Itm_Portion = ip.IPCd', 'inner')
                           ->get_where('ItemPortions ip', array('mir.ItemId' => $item_id))
                           ->result_array();            
            echo json_encode($portions);
        }
        
        if(isset($_POST['delete_offer_description']) && $_POST['delete_offer_description']){
            $SDetCd = $_POST['SDetCd'];
            $this->db2->query("UPDATE CustOffersDet set Stat = 9 where SDetCd = ".$SDetCd);
            echo 1;
        }

    }

    public function edit_offer($SchCd){
        $data['title'] = $this->lang->line('editOffer');
        
        $data['sch_typ'] = $this->rest->getOffersSchemeType();
        $data['sch_cat'] = $this->rest->getOffersSchemeCategory(1);
        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['cuisines'] = $this->rest->getCuisineList();
        $data['foodType'] = $this->rest->get_foodType();
        // $data['itemList'] = $this->rest->getAllItemsList();

        $EID = authuser()->EID;
        $data['SchCd'] = $SchCd;

        $langId = $this->session->userdata('site_lang');

        $scName = "SchNm$langId as SchNm";
        $data['scheme'] = $this->db2->select("$scName, SchCd, SchTyp ,SchCatg,FrmDt, ToDt, FrmDayNo, ToDayNo, FrmTime, ToTime, AltFrmTime, AltToTime")->get_where('CustOffers', array('SchCd' => $SchCd, 'EID' => $EID))->result_array();

        $scDesc = "SchDesc$langId as SchDesc";
        $data['descriptions'] = $this->db2->select("*, $scDesc")->get_where('CustOffersDet', array('SchCd' =>$SchCd,'Stat' => 0))->result_array();

        // echo "<pre>";
        // print_r($data['scheme']);
        // die;

        $this->load->view('rest/offer_edit',$data);  
    }

    public function item_list(){

        $EID = authuser()->EID;
        $data['CID'] = '';
        $data['catid'] = '';
        $data['filter'] = '';
        
        $langId = $this->session->userdata('site_lang');
        $lname = "mi.ItemNm$langId as ItemNm";
        $ipName = "ip.Name$langId as Portions";
        $esName = "es.Name$langId as Sections";

        $data['cuisine'] = $this->rest->getCuisineList();
        $data['menucat'] = $this->rest->get_MCatgId();


        $menuItemData = $this->db2->select("mi.ItemId, $lname, $ipName, mr.OrigRate, mr.ItmRate, mr.IRNo, $esName")
                        ->order_by('mi.CID,mi.MCatgId, mi.ItemNm1', 'ASC')
                        // ->group_by('mi.ItemId')
                        ->join('MenuItemRates mr', 'mr.ItemId = mi.ItemId', 'inner')
                        ->join('ItemPortions ip', 'ip.IPCd = mr.Itm_Portion', 'inner')
                        ->join('Eat_Sections es', 'es.SecId = mr.SecId', 'inner');
                        
        if($this->input->method(true)=='POST'){
            if($_POST){
                if(isset($_POST['cuisine']) && !empty($_POST['cuisine'])){
                    $CID = $_POST['cuisine'];
                    $menuItemData->where('mi.CID', $CID);

                    $data['menucat'] = $this->db2->query("SELECT *, $mcname from MenuCatg where CID = '$CID'")->result_array();
                    $data['CID'] = $CID;
                }
                if(isset($_POST['menucat']) && !empty($_POST['menucat'])){
                    $catid = $_POST['menucat'];
                    $menuItemData->where('mi.MCatgId', $catid);
                    $data['catid'] = $catid;

                }

                if(isset($_POST['filter']) && !empty($_POST['filter'])){
                    $data['filter'] = $_POST['filter'];
                    // print_r($data['filter']);die;
                    if($data['filter'] == 'draft'){
                        $menuItemData->where('mr.OrigRate', 0);
                    }else if($data['filter'] == 'enabled'){
                        $menuItemData->where('mi.Stat', 0);
                    }else if($data['filter'] == 'disabled'){
                        $menuItemData->where('mi.Stat', 1);
                    }
                }
            }
        }

        $menuItemData = $menuItemData->get_where('MenuItem mi', array(
                                        'mi.EID' => $EID,
                                        'mr.EID' => $EID
                                        )
                                )
                        ->result_array();

        $data['menuItemData'] = $menuItemData;
        
    	$data['title'] = $this->lang->line('itemDetails');
		$this->load->view('rest/item_lists',$data);	
    }

    public function item_list_get_category(){
        if($_POST){
            $CID = $_POST['CID'];
            $EID = authuser()->EID;

            $data = $this->rest->getMenuCatListByCID($EID, $CID);

            echo json_encode($data);
        }
    }

    public function order_dispense(){
    	$data['title'] = $this->lang->line('orderDispense');
        $data['RestName'] = authuser()->RestName;
        $data['RUserId'] = authuser()->RUserId;
        $data['Cash'] = $this->session->userdata('Cash');
        $data['EType'] = $this->session->userdata('EType');
        $Fest = $this->session->userdata('Fest');
        $data['CheckOTP'] = $this->session->userdata('DeliveryOTP');
        $data['EID'] = authuser()->EID;
        $data['DispenseAccess'] = $this->rest->getDispenseAccess();
        $data['dispenseMode'] = $this->rest->getDispenseModes();

		$this->load->view('rest/dispense_orders',$data);	
    }

    public function order_delivery(){
        $Fest = $this->session->userdata('Fest');
        $langId = $this->session->userdata('site_lang');
        // echo "<pre>";
        // print_r($_POST);
        // die;
        if($this->input->method(true) == 'POST'){
            $AutoAllot = $this->session->userdata('AutoAllot');
            $AutoDeliver = $this->session->userdata('AutoDeliver');
            $EType = $this->session->userdata('EType');
            
            $EID = authuser()->EID;
            // if (isset($_POST['getKtichenItem'])) {
            //     $ItemNm = "i.ItemNm$langId as ItemNm";
            //     if ($AutoDeliver == 1 && $AutoAllot == 1) {
            //         // Auto auto Mode
            //         $kitchenData = $this->db2->query("SELECT k.ItemId, sum(k.Qty - k.AQty) as Qty, k.TA, k.CustRmks, $ItemNm FROM `Kitchen` k, Eat_Kit ek, MenuItem i where i.ItemId = k.ItemId AND (k.Stat <= 3 AND k.Stat > 0) AND k.EID = $EID AND  ek.EID = k.EID AND k.KitCd = ek.KitCd AND (k.Qty - k.AQty) > 0 and (DateDiff(Now(),k.LstModDt) < 2)  Group by i.ItemId, k.TA, k.CustRmks Order by i.ItemNm1")->result_array();
            //         //and (DateDiff(CurrDate(),k.LstModDt) < 2)
            //     } elseif ($AutoDeliver == 0 && $AutoAllot == 1) {
            //         // Auto Manual Mode
            //         $kitchenData = $this->db2->query("SELECT k.ItemId, sum(k.Qty - k.AQty) as Qty, k.TA, k.CustRmks, $ItemNm FROM `Kitchen` k, Eat_Kit ek, MenuItem i where i.ItemId = k.ItemId AND (k.Stat <= 3 AND k.Stat > 0) AND k.EID = $EID AND  ek.EID = k.EID AND k.KitCd = ek.KitCd  AND (k.Qty - k.AQty) > 0 and (DateDiff(Now(),k.LstModDt) < 2) Group by i.ItemId, k.TA, k.CustRmks Order by i.ItemNm1")->result_array();
            //         //and (DateDiff(CurrDate(),k.LstModDt) < 2)
            //     }


            //     if (empty($kitchenData)) {
            //         $response = [
            //             "status" => 0,
            //             "msg" => "No Item Pending"
            //         ];
            //     } else {
            //         $response = [
            //             "status" => 1,
            //             "kitchenData" => $kitchenData
            //         ];
            //     }

            //     echo json_encode($response);
            //     die();
            // }

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
                
                $dispMode = (isset($_POST['dispMode']) && $_POST['dispMode'] > 0 )?$_POST['dispMode']:0;
                
                if($dispMode > 0){
                    $this->db2->where('km.OType', $dispMode);
                }

                $langId = $this->session->userdata('site_lang');
                $partyName = "p.Name$langId as thirdPartyName";

                $kitchenData = $this->db2->select("b.BillId, b.BillNo, sum(k.Qty) as Qty, k.OType, k.TPRefNo, k.TPId, km.CustId, k.CellNo, k.EID, k.DCd, km.CNo, $partyName")
                                    ->order_by('b.BillId', 'Asc')
                                    ->group_by('b.BillId, k.DCd')
                                    ->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
                                    ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                                    ->join('MenuItem i', 'i.ItemId = k.ItemId', 'inner')
                                    ->join('3POrders p', 'p.3PId = km.TPId', 'left')
                                    ->get_where('Billing b', array(
                                                'k.EID' => $EID,
                                                'k.DCd' => $DCd,
                                                'k.DStat' => 0,
                                                'k.Stat' => 3,
                                                'k.OType >' => 100,
                                                )
                                            )
                                    ->result_array();

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
                $ItemNm = "i.ItemNm$langId as ItemNm";
                $ipName = "ip.Name$langId as ipName";
                $CNo = $_POST['CNo'];
                
                $orderList = $this->db2->select("$ItemNm, k.Qty, k.CustItemDesc, k.CustRmks, k.Itm_Portion, $ipName")
                                    ->order_by('k.DCd', 'Asc')
                                    ->group_by('k.DCd, i.ItemId, k.CustItemDesc, k.CustRmks, k.Itm_Portion')
                                    ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                                    ->join('MenuItem i', 'i.ItemId = k.ItemId', 'inner')
                                    ->join('ItemPortions ip', 'ip.IPCd = k.Itm_Portion', 'inner')
                                    ->get_where('KitchenMain km', array(
                                                'km.EID' => $EID,
                                                'k.EID' => $EID,
                                                'i.EID' => $EID,
                                                'km.CNo' => $CNo,
                                                'k.DCd' => $DCd 
                                                )
                                            )
                                    ->result_array();
                // echo "<pre>";
                // print_r($orderList);die;

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
                        // $this->generateOTP(authuser()->RUserId);
                        $otp = generateOTP(authuser()->mobile, 'Change Password');
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
            $otp = $this->session->userdata('cust_otp');
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
        $data['EID']     = authuser()->EID;

        $status = 'error';
        $response = 'Something went wrong!!';
        if($this->input->method(true)=='POST'){

            // echo "<pre>";
            // print_r($_POST);
            // die;

            $status = 'success';

            $pData = $_POST;
            $ThemeId = $pData['ThemeId'];
            if($ThemeId > 0){
                unset($pData['ThemeId']);
                updateRecord('ConfigTheme', $pData, array('ThemeId' => $ThemeId));
                $response = 'Theme Updated.';
            }else{
                $pData['EID'] = $data['EID'];
                insertRecord('ConfigTheme', $pData);
                $response = 'New Theme Added.';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['themeNames'] = $this->rest->getThemeListName();
        $data['themes']     = $this->rest->getThemeList();
        $data['title'] = $this->lang->line('themeSetting');

        $this->load->view('rest/theme_setting',$data);   
    }

    public function get_theme_data(){
        $status = 'error';
        $response = 'Something went wrong!!';
        if($this->input->method(true)=='POST'){

            $res = $this->rest->getThemeData($_POST['ThemeId']);
            if(!empty($res)){
                $status = 'success';
                $response = $res;
            }else{
                $response = 'No Theme Found!!';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }        
    }

    public function stock_list(){
        $data['trans_id'] = 0;
        $data['trans_type_id'] = 0;
        $data['from_date'] = date('Y-m-d');
        $data['to_date'] = date('Y-m-d');
        $data['stock'] = $this->rest->getStockList();
        if($this->input->method(true)=='POST'){
            $data['stock'] = $this->rest->getStockList($_POST);
        }
        
        $data['title'] = $this->lang->line('stockList');
        $data['trans_type'] = $this->rest->getTransactionType();
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

                    if($_POST['trans_type'] == 21){
                        $RMStock['FrmKitCd'] = !empty($_POST['store_adjust'])?$_POST['store_adjust']:0;
                    }

                    $RMStock['Stat'] = 0;
                    $RMStock['LoginId'] = authuser()->RUserId;
                    $RMStock['EID'] = authuser()->EID;
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
            
            if(isset($_POST['delete_details'])){
                updateRecord('RMStockDet', array('Stat' => 9), array('RMDetId' => $_POST['RMDetId']) );
                echo 1;
            }
            if(isset($_POST['delete_trans'])){
                updateRecord('RMStock', array('Stat' => 9), array('TransId' => $_POST['TransId']) );
                echo 1;
            }

        }
        $data['title'] = $this->lang->line('addStock');

        $data['items'] = $this->rest->getRMItemUOM();
        $data['trans_type'] = $this->rest->getTransactionType();
        $data['eatary'] = $this->rest->getRestaurantList();
        $data['kit'] = $this->rest->get_kitchen();
        $data['suppliers'] = $this->rest->getSupplierList();

        $this->load->view('rest/stock_add',$data);
    }

    public function edit_stock($TransId){

        if($this->input->method(true)=='POST'){

            $TransId = $_POST['trans_id'];
            $num = sizeof($_POST['ItemId']);
            for($i = 0;$i<$num;$i++){
                $RMStockDet['TransId'] = $TransId;
                $detid = !empty($_POST['RMDetId'][$i])?$_POST['RMDetId'][$i]:0;
                $RMStockDet['RMCd'] = !empty($_POST['ItemId'][$i])?$_POST['ItemId'][$i]:0;
                $RMStockDet['UOMCd'] = !empty($_POST['UOM'][$i])?$_POST['UOM'][$i]:0;
                $RMStockDet['Qty'] = !empty($_POST['Qty'][$i])?$_POST['Qty'][$i]:0;
                $RMStockDet['Rate'] = !empty($_POST['Rate'][$i])?$_POST['Rate'][$i]:0;
                $RMStockDet['Rmks'] = !empty($_POST['Rate'][$i])?$_POST['Remarks'][$i]:'';
                
                updateRecord('RMStockDet', $RMStockDet, array('TransId' =>$TransId, 'RMDetId ' => $detid ));
            }
            redirect(base_url('restaurant/stock_list'));
        }
       
        $data['TransId'] = $TransId;
        $data['stock'] = getRecords('RMStock', array('TransId' => $TransId));
        if(!empty($data['stock'])){
            $data['items'] = $this->rest->getRMItemUOM();
            $data['stock_details'] = $this->rest->getRMStockDetList($TransId);
            $data['eatary'] = $this->rest->getRestaurantList();
            $data['kit'] = $this->rest->get_kitchen();
            $data['suppliers'] = $this->rest->getSupplierList();
        }else{
            $this->session->set_flashdata('error','No records found!');
            redirect(base_url('restaurant/stock_list'));   
        }

        $data['title'] = $this->lang->line('editStock');
        $this->load->view('rest/stock_edit',$data);    
    }

    public function stock_report(){
        $data['title'] = $this->lang->line('stockReport');
        $data['report'] = $this->rest->getStockReport();
        $this->load->view('rest/stock_report',$data);
    }

    public function stock_consumption(){
        $data['title'] = $this->lang->line('stockConsumption');
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

    public function rm_ajax(){
       if(isset($_POST['getUOM'])){
            $item_id = $_POST['RMCd'];
            $langId = $this->session->userdata('site_lang');
            $lname = "ru.Name$langId as Name";
            $uoms = $this->db2->select("riu.*, $lname")
                              ->join('RMUOM ru','riu.UOMCd = ru.UOMCd','inner')
                              ->get_where('RMItemsUOM riu', array('riu.RMCd' => $item_id))
                              ->result_array();
            echo json_encode($uoms);
        } 
    }

    public function cash_bill(){
        // tempary solution
        // $_SESSION['DynamicDB'] = $this->session->userdata('my_db');
        // [DynamicDB] => 51e
        // $EID = authuser()->EID;
        // $EType = $this->session->userdata('EType');
        // $RUserId = authuser()->RUserId;
        // $data['RUserId'] = $RUserId;
        // $data['EID'] = $EID;
        // $data['EType'] = $EType;

        // $billData = $this->db2->query("SELECT BillId,BillNo,PaymtMode, DATE(billTime) as BillDate, TotAmt FROM Billing WHERE PaidAmt = 0 AND EID = $EID AND PaymtMode = 'Cash'")->result_array();

        // $GetDCD = $this->db2->query("SELECT CCd FROM `UsersRoleDaily` WHERE RUserId = $RUserId")->result_array();
        // $tempArray =explode(",",$GetDCD[0]['CCd']);
        // $SqlQueryVar = "SELECT CCd, Name FROM Eat_Casher Where EID = $EID AND Stat = 0";
        // if(count($tempArray) >=1 && $tempArray[0] != ''){
        //     $SqlQueryVar .=" AND (";
        // for ($i=0; $i <count($tempArray) ; $i++) { 
        //     if($i>=1){
        //         $SqlQueryVar .=" OR ";
        //     }
        //     $SqlQueryVar .= "CCd =".$tempArray[$i];
            
        // }
        // $SqlQueryVar .= ")";
        // }else{

        // }
        
        // $data['SettingTableViewAccess'] = $this->db2->query($SqlQueryVar)->result_array();

        // $data['title'] = 'Bill Settlement';
        // $this->load->view('rest/bill_settle',$data);
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

        $data['title'] = $this->lang->line('billView');'Bill View';
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
                $q2 = "UPDATE Kitchen k, KitchenMain km SET  k.Stat = $stat, k.payRest=1, km.payRest=1 WHERE (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
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

            $q2 = "UPDATE Kitchen k, KitchenMain km SET  k.Stat = $stat, k.payRest=1, km.payRest=1 WHERE (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
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

            $kitchenUpdate = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET  k.Stat = 99 WHERE km.BillStat = $id AND (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)");


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
        $data['title'] = $this->lang->line('tableView');
        $data['EType'] = $this->session->userdata('EType');
        if($data['EType'] == 5){

            $data['TableAcceptReqd'] = $this->session->userdata('TableAcceptReqd');
            $EID = authuser()->EID;
            $data['EID'] = $EID;
            $data['Kitchen'] = $this->session->userdata('Kitchen');

            $langId = $this->session->userdata('site_lang');
            $cashName = "Name$langId as Name";

            $data['SettingTableViewAccess'] = $this->rest->getCasherList();
            // when calling join unjoin table then load this query
            $data['captured_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 1 and EID = ".$EID)->result_array();
            $data['available_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 0 and EID = ".$EID)->result_array();
            $data['selectMergeTable'] = $this->db2->query("SELECT TableNo , MergeNo FROM `Eat_tables` where EID = $EID")->result_array();
            // end when calling join unjoin table then load this query
            // echo "<pre>";
            // print_r($data);exit();
            $this->load->view('rest/table_sitting',$data);   
        }else{
            $this->load->view('page403', $data);
        }
        
    }

    public function sittin_table_view_ajax(){
        // echo "<pre>";print_r($_POST);die;
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');

        if (isset($_POST['getTableOrderDetails']) && $_POST['getTableOrderDetails']) {
            // print_r($_POST);
            // exit;
            $ccd_qry = " ";
            if (isset($_POST['CCd'])) {
                $CCd = $_POST['CCd'];
                $ccd_qry = " and et.CCd = $CCd and et.MergeNo = km.MergeNo and et.EID = km.EID";
            }

            $stat = ($EType == 5)?3:2;

            $groupby = ' GROUP BY km.MCNo';    
            if($_POST['filter'] == 'tableWise'){
                $groupby = ' GROUP BY km.MergeNo';
            }
            // SUM(k.AQty) as AnyAssigned
            $kitchenData = $this->db2->query("SELECT (SUM(k.Qty) - SUM(k.DQty)) as AllDelivered, km.CNo, km.CustId,  SUM(k.OrigRate * k.Qty) as Amt, TIME_FORMAT(km.LstModDt,'%H:%i') as StTime,   km.MergeNo, km.MCNo, km.BillStat,  km.EID, km.CNo, km.CellNo, IF(km.CellNo > 0,(select count(km2.CellNo) from KitchenMain km2 where km2.CellNo=km.CellNo and km2.EID = km.EID group by km2.CellNo),0) as visitNo, km.TableNo,km.SeatNo, km.OType, km.payRest, km.custPymt, km.CnfSettle FROM Kitchen k, KitchenMain km, Eat_tables et WHERE km.payRest=0 and km.CnfSettle=0 AND (k.Stat = $stat) AND (k.OType = 7 OR k.OType = 8) and (km.CNo = k.CNo) AND k.EID = km.EID AND k.MergeNo = km.MergeNo AND km.EID = $EID $ccd_qry $groupby order by km.MergeNo ASC")->result_array();

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
            $data = $this->db2->query("SELECT k.FKOTNo, ek.KitName, k.UKOTNo FROM Eat_Kit ek, Kitchen k, KitchenMain km WHERE ( k.Stat = 3) AND k.EID=km.EID AND k.CNo = km.CNo AND km.EID = $EID and (km.CNo = $c OR km.MCNo = $c) and k.FKOTNo = $f and k.MergeNo = km.MergeNo and ek.KitCd=k.KitCd and ek.EID=km.EID GROUP BY k.FKOTNo, ek.KitName, k.UKOTNo order by k.FKOTNo, ek.KitName, k.UKOTNo ASC")->result_array();
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
            $kots = $this->db2->query("SELECT km.TableNo, FKOTNo, KOTNo, UKOTNo, KitCd, SUM(Qty) as Qty , KOTPrintNo, km.CellNo, km.CNo, km.MergeNo FROM Kitchen k, KitchenMain km WHERE ( k.Stat = 3 ) AND k.EID=km.EID AND k.CNo = km.CNo  AND km.EID = $EID and (km.CNo = $CNo OR km.MCNo = $CNo)  and k.MergeNo = km.MergeNo GROUP BY FKOTNo, UKOTNo, KOTNo, KitCd, km.TableNo, km.CellNo, km.CNo, KOTPrintNo order by KOTNo DESC, UKOTNO DESC")->result_array();

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
            
            $mergeNo = $_POST['mergeNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];

            $EType = $this->session->userdata('EType');
            $stat = ($EType == 5)?3:2;

            $groupby = ' ,k.CNo';
            $where = " and (k.CNo = $CNo OR k.MCNo = $CNo) ";

            if($_POST['tableFilter'] == 'tableWise'){
                $groupby = '';
                $where = '';
            }

            $langId = $this->session->userdata('site_lang');
            $lname = "i.ItemNm$langId as ItemNm";
            // SUM(k.AQty) as AQty
            $kots = $this->db2->query("SELECT k.MergeNo,k.TableNo, k.FKOTNo, k.KOTNo, k.KitCd, SUM(k.Qty) as Qty , k.KOTPrintNo, k.ItemId, $lname, SUM(k.Qty) as Qty, SUM(k.DQty) as DQty,TIME_FORMAT(ADDTIME(k.OrdTime,k.EDT), '%H:%i') as EDT, k.CellNo, k.CNo,k.MCNo FROM Kitchen k, MenuItem i WHERE k.ItemId = i.ItemId AND ( k.Stat = $stat ) AND k.EID = $EID $where and k.MergeNo = '$mergeNo' and k.payRest = 0  GROUP BY k.FKOTNo, k.KOTNo, k.KitCd, k.ItemId, k.EDT, k.MergeNo $groupby order by k.KOTNo, k.FKOTNo, i.ItemNm1 DESC")->result_array();
            
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
            $itemDetails = $this->db2->query("SELECT  k.ItemId, i.ItemNm, SUM(k.Qty) as Qty, SUM(k.AQty) as AQty, SUM(k.DQty) as DQty FROM Kitchen k, KitchenMain km, MenuItem i WHERE k.ItemId = i.ItemId AND k.EID = km.EID AND (k.Stat = 3)  AND km.CNo = k.CNo AND km.EID = 51 AND (km.CNo = $CNo OR km.MCNo = $CNo) AND km.MergeNo = k.MergeNo  group by i.ItemId ORDER by i.ItemNm")->result_array();
            
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
            $itemDetails = $this->db2->query("SELECT  k.ItemId, i.ItemNm, SUM(k.Qty) as Qty, SUM(k.AQty) as AQty, SUM(k.DQty) as DQty FROM Kitchen k, KitchenMain km, MenuItem i WHERE k.ItemId = i.ItemId AND k.EID = km.EID AND (k.Stat = 3)  AND km.CNo = k.CNo AND km.EID = $EID AND (km.CNo = $CNo OR km.MCNo = $CNo) AND km.MergeNo = k.MergeNo  group by i.ItemId ORDER by i.ItemNm")->result_array();

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
            $declineItem = $this->db2->query("UPDATE Kitchen SET Stat = 6, DReason = $declineReason WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID AND Stat = 3");

            $kitTableItemCount = $this->db2->query("SELECT count(itemid) AS itmcnt FROM Kitchen WHERE custId = $custId AND TableNo ='$tableNo' AND EID =$EID AND Stat 3 ");

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
            $tables = $this->db2->query("SELECT et.TableNo, et.MergeNo from Eat_tables et where et.TableNo = et.MergeNo or (et.TableNo in (select km.TableNo from KitchenMain km where km.BillStat = 0 and km.TableNo = km.MergeNo and km.EID = $EID) and et.EID = $EID) order by et.TableNo ASC")->result_array();
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

                    $result = $this->db2->query("UPDATE Eat_tables set MergeNo = '$mergeNo', Stat = 1 where TableNo in ($selectedTablesString)");
                    
                    $result1 = $this->db2->query("UPDATE KitchenMain km set km.MergeNo = '$mergeNo' where km.TableNo in ($selectedTablesString) and km.BillStat = 0");

                    $this->db2->query("UPDATE Kitchen k set k.MergeNo = '$mergeNo' where k.TableNo in ($selectedTablesString) and k.BillStat = 0");

                    // $this->db2->query("UPDATE Kitchen k set k.MergeNo = '$mergeNo', k.MCNo=(select km1.CNo from KitchenMain km1 where km1.TableNo = $selectedTables[0] and km1.BillStat = 0 ) where k.TableNo in ($selectedTablesString) and k.BillStat = 0");

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
            $tables = $this->db2->query("SELECT MergeNo from Eat_tables where TableNo != MergeNo and EID = $EID group by MergeNo order by MergeNo ASC ")->result_array();

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

        // if (isset($_POST['getMenuCat'])) {

        //     $cId = $_POST['cId'];

        //     $menuCatgData = $this->db2->query("SELECT mc.MCatgId, mc.MCatgNm, mc.CTyp, mc.TaxType, f.FID, f.fIdA, f.Opt, f.AltOpt from MenuCatg mc, MenuItem i, Food f where  i.MCatgId=mc.MCatgId AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) AND mc.CID = :cId AND mc.EID=i.EID AND mc.Stat = 0 AND mc.CTyp = f.CTyp and f.LId = 1 and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID=$EID and md.Chainid=i.ChainId) group by mc.MCatgId, mc.MCatgNm, mc.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt order by mc.Rank " , ["cId" => $cId])->result_array();

        //     if ($ChainId > 0) {
        //         $imgSrc = "uploads/c" . $ChainId . "/" . $cId . ".jpg";
        //     } else {
        //         $imgSrc = "uploads/e" . $EID . "/" . $cId . ".jpg";
        //     }
        //     if (!file_exists("../$imgSrc")) {
        //         $imgSrc = "uploads/general/" . $cId . ".jpg";
        //     }

        //     if (empty($menuCatgData)) {
        //         $response = [
        //             "status" => 0,
        //             "msg" => "No Menu Category Available At This Time"
        //         ];
        //     } else {
        //         $response = [
        //             "status" => 1,
        //             "imgSrc" => $imgSrc,
        //             "menuCatgData" => $menuCatgData
        //         ];
        //     }

        //     echo json_encode($response);
        //     die();
        // }
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

            $q2 = "UPDATE Kitchen k, KitchenMain km SET k.payRest=1, km.payRest=1 WHERE (k.Stat = 3) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $cNo OR km.MCNo = $cNo)";
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
        $this->load->view('rest/offline_order -bkup23Nov', $data);
    }
// 3p_order_ajax
    public function order_ajax_3p(){
        $CustId = $this->session->userdata('CustId');
        $COrgId = $this->session->userdata('COrgId');
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');
        
        $CellNo = $this->session->userdata('CellNo');
        $CustNo = $this->session->userdata('CustNo');
        $MultiKitchen = $this->session->userdata('MultiKitchen');
        
        $CNo = 0;
        $TableNo = 0;
        $KOTNo = 0;
        $ONo = 0;

        $langId = $this->session->userdata('site_lang');
        $itemName1 = "i.ItemNm$langId as LngName";
        $lname = "i.ItemNm$langId";
        $ipname = "ip.Name$langId as portionName";

        if (isset($_POST['searchItem']) && $_POST['searchItem']) {
            $itemName = $_POST['itemName'];

            $likeQry = " ($lname like '$itemName%' or i.ItemId like '$itemName%' or i.IMcCd like '$itemName%') ";
            $order_by = " i.ItemNm1";

            if($this->session->userdata('IMcCdOpt') == 2){
                $likeQry = " ($lname like '$itemName%' or i.IMcCd like '$itemName%' or i.ItemId like '$itemName%') ";
                $order_by = " i.IMcCd";
            }

            $items = $this->db2->query("SELECT i.ItemId, $itemName1, $ipname, mr.OrigRate, i.KitCd, i.PckCharge, i.ItemTyp, i.CID, i.MCatgId, i.IMcCd,i.PrepTime, i.FID, mr.Itm_Portion, mc.TaxType, mc.DCd   FROM MenuItem i ,MenuItemRates mr, MenuCatg mc, ItemPortions ip where mc.MCatgId = i.MCatgId and ip.IPCd = mr.Itm_Portion and $likeQry AND i.Stat = 0 AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and mr.ItemId=i.ItemId and mr.OrigRate > 0 order by $order_by")->result_array();
            // echo "<pre>";print_r($this->db2->last_query());die;
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

        if (isset($_POST['sendToKitchen']) && $_POST['sendToKitchen']) {

            // echo "<pre>";
            // print_r($_POST);
            // die;
            $thirdParty = 0;
            $thirdPartyRef = 0;

            $orderType = $_POST['orderType'];
            $tableNo = $_POST['tableNo'];
            if($orderType == 101){
                $thirdParty = !empty($_POST['thirdParty'])?$_POST['thirdParty']:0;
                $thirdPartyRef = !empty($_POST['thirdParty'])?$_POST['thirdParty']:0;
            }
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
            $prep_time = !empty($_POST['prep_time'])?$_POST['prep_time']:0;
            $seatNo = !empty($_POST['seatNo'])?$_POST['seatNo']:1;
            $DCd = !empty($_POST['DCd'])?$_POST['DCd']:0;
            $customerAddress = !empty($_POST['customerAddress'])?$_POST['customerAddress']:'';
            $CCd = !empty($_POST['CCd'])?$_POST['CCd']:0;

            $SchCd = !empty($_POST['SchCd'])?$_POST['SchCd']:0;
            $SDetCd = !empty($_POST['SDetCd'])?$_POST['SDetCd']:0;

            $CustItem = !empty($_POST['CustItem'])?$_POST['CustItem']:0;
            $CustItemDesc = !empty($_POST['CustItemDesc'])?$_POST['CustItemDesc']:'Std';
            
            if ($CNo == 0) {
                if(!empty($phone)){
                    $CustId = createCustUser($phone);

                    $this->db2->set('visit', 'visit+1', FALSE);
                    $this->db2->set('DelAddress', $customerAddress);
                    $this->db2->where('CustId', $CustId);
                    $this->db2->update('Users');
                }

                $CNo = $this->insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $phone, $EID, $ChainId, $ONo, $tableNo,$data_type, $orderType, $seatNo, $thirdParty, $thirdPartyRef, $CCd);
                if($orderType == 8){
                    updateRecord('Eat_tables', array('Stat' => 1), array('TableNo' => $tableNo, 'EID' => $EID));
                }
            }else{
                $oldSeatNo = getSeatNo($CNo);
                if($oldSeatNo != $seatNo){
                    $CNo = 0;
                    $CNo = $this->insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $phone, $EID, $ChainId, $ONo, $tableNo,$data_type, $orderType, $seatNo, $thirdParty, $thirdPartyRef, $CCd);
                }    
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

            $stat = ($EType == 5)?3:2;

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
                $kitchenObj['CustId'] = $CustId;
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
                $kitchenObj['PckCharge'] = 0;
                if($kitchenObj['TA'] == 1){
                    $kitchenObj['PckCharge'] = $pckValue[$i];
                }
                $kitchenObj['CustRmks'] = $itemRemarks[$i];
                $kitchenObj['ItmRate'] = $ItmRate[$i];
                $kitchenObj['OrigRate'] = $ItmRate[$i];
                $kitchenObj['Stat'] = $stat;
                $kitchenObj['CellNo'] = $phone;
                $kitchenObj['Itm_Portion'] = $Itm_Portion[$i];
                $kitchenObj['TaxType'] = $taxtype[$i];
                $kitchenObj['SeatNo'] = $seatNo;
                $kitchenObj['DCd'] = $DCd[$i];
                $kitchenObj['SchCd'] = $SchCd[$i];
                $kitchenObj['SDetCd'] = $SDetCd[$i];
                $kitchenObj['CustItem'] = $CustItem[$i];
                $kitchenObj['CustItemDesc'] = $CustItemDesc[$i];

                // edt
                $date = date("Y-m-d H:i:s");
                $date = strtotime($date);
                $time = $prep_time[$i];
                $date = strtotime("+" . $time . " minute", $date);
                $edtTime = date('H:i', $date);
                // edt
                $kitchenObj['EDT'] = $edtTime;
                $kitchenObj['LoginCd'] = authuser()->RUserId;
                // echo "<pre>";print_r($kitchenObj);exit();
                insertRecord('Kitchen', $kitchenObj);

                $orderAmount = $orderAmount + $ItmRate[$i];
            }

            $url = base_url('restaurant/kot_print/').$CNo.'/'.$tableNo.'/'.$fKotNo;
            $dArray = array('MCNo' => $CNo, 'MergeNo' => $tableNo,'FKOTNo' => $fKotNo,'sitinKOTPrint' => $this->session->userdata('sitinKOTPrint'), 'url' => $url);

            if ($data_type == 'bill' || $data_type == 'kot_bill') {

                $MergeNo = $tableNo;
                $lname = "m.ItemNm$langId";
                $ipName = "ip.Name$langId as Portions";

                $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  (k.OrigRate*sum(k.Qty)) as OrdAmt, km.MCNo, km.MergeNo, k.FKOTNo,  (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID, k1.MCNo) as TotItemDisc,(SELECT sum(k1.PckCharge*k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID, k1.MCNo) as TotPckCharge, $ipName, km.CNo,km.MergeNo, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.CustId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.MCNo AND km.MCNo IN (Select km1.MCNo from KitchenMain km1 where km1.MergeNo=$MergeNo group by km1.MergeNo) group by km.MCNo, k.ItemId, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by k.TaxType, m.ItemNm1 Asc")->result_array();

                $taxDataArray = array();

                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $SubAmtTax = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    $CNo = $kitcheData[0]['CNo'];

                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo);
                    $taxDataArray = $TaxRes['taxDataArray'];

                    foreach ($kitcheData as $kit ) {

                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                        
                    }

                    //tax calculate
                    for ($index = 0; $index < count($taxDataArray[$initil_value]); $index++) {
                            $element = $taxDataArray[$initil_value][$index];
                            $SubAmtTax = $SubAmtTax + round($element['SubAmtTax'], 2);
                        }

                    $orderAmt = $orderAmt + $SubAmtTax;

                    $this->session->set_userdata('TipAmount', 0);
                    $this->session->set_userdata('itemTotalGross', $orderAmt);

                    $this->session->set_userdata('ONo', 0);
                    $this->session->set_userdata('CustNo', 0);
                    $this->session->set_userdata('COrgId', 0);
                    $this->session->set_userdata('CellNo', '-');
                    
                    $charge =  $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    // grand total
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount;

                    // die;
                    $postData["orderAmount"] = $total;
                    $postData["paymentMode"] = 'RCash';
                    $postData["MergeNo"] = $MergeNo;
                    $postData["TableNo"] = $MergeNo;
                    // ref by billCreateRest()
                    $postData["cust_discount"] = 0;

                    $custId = $kitcheData[0]['CustId'];
                    $this->session->set_userdata('CustId', $custId);
                    $res = billCreate($EID, $CNo, $postData);
                    if($res['status'] > 0){  
                        $response = [
                            "status" => 1,
                            "msg" => "Bill Created.",
                            "data" => array('billId' => $res['billId'], 'MergeNo' => $kitcheData[0]['MergeNo'], 'MCNo' => $kitcheData[0]['MCNo'], 'FKOTNo' => $kitcheData[0]['FKOTNo'])
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
            $stat = ($EType == 5)?3:2;
            $langId = $this->session->userdata('site_lang');
            $lname = "mi.ItemNm$langId as ItemNm";

            $mergeNo = $_POST['mergeNo'];
            $seatNo = $_POST['seatNo'];
            $whr = "mir.Itm_Portion = k.Itm_Portion and mir.SecId = (SELECT et.SecId from Eat_tables et where et.EID = k.EID and et.TableNo = k.TableNo )";
            $data = $this->db2->select("k.CNo, k.TA, k.Qty, k.ItmRate, k.CustRmks,k.CellNo, km.BillStat, $lname, mir.OrigRate, k.SeatNo, k.Itm_Portion, k.ItemId")
                        ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                        ->join('MenuItem mi', 'mi.ItemId = k.ItemId', 'inner')
                        ->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
                        ->where($whr)
                        ->get_where('KitchenMain km', array(
                                            'km.MergeNo' => $mergeNo,
                                            'k.Stat' => $stat,
                                            'km.BillStat' => 0,
                                            'k.SeatNo' => $seatNo ,
                                            'mir.OrigRate >' => 0,
                                            'k.EID' => $EID,
                                            'km.EID' => $EID
                                        )
                                    )
                        ->result_array();
            
            echo json_encode($data);
        }
    }

    // functions
    private function insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $CellNo, $EID, $ChainId, $ONo, $TableNo,$data_type, $orderType, $seatNo, $thirdParty, $thirdPartyRef, $CCd)
    {
        if ($CNo == 0) {

            $TableNo = $TableNo;

            $kitchenMainObj['CustId'] = $CustId;
            $kitchenMainObj['COrgId'] = $COrgId;
            $kitchenMainObj['CustNo'] = $CustNo;
            $kitchenMainObj['CellNo'] = $CellNo;
            $kitchenMainObj['EID'] = $EID;
            $kitchenMainObj['ChainId'] = $ChainId;
            $kitchenMainObj['ONo'] = $ONo;
            $kitchenMainObj['OType'] = $orderType;
            $kitchenMainObj['TPId'] = $thirdParty;
            $kitchenMainObj['TPRefNo'] = $thirdPartyRef;
            $kitchenMainObj['TableNo'] = $TableNo;
            $kitchenMainObj['MergeNo'] = $TableNo;
            $kitchenMainObj['OldTableNo'] = $TableNo;
            $kitchenMainObj['Stat'] = 2;
            $kitchenMainObj['LoginCd'] = authuser()->RUserId;
            $kitchenMainObj['TPRefNo'] = '';
            $kitchenMainObj['MngtRmks'] = '';
            $kitchenMainObj['BillStat'] = 0;
            $kitchenMainObj['payRest'] = 0;
            $kitchenMainObj['SeatNo'] = $seatNo;
            $kitchenMainObj['CCd'] = $CCd;
            
            // echo "<pre>";
            // print_r($kitchenMainObj);
            // die;
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
        $EID = authuser()->EID;
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
                    $cat['EID'] = $EID;
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

        
        $langId = $this->session->userdata('site_lang');
        $RMCatgName = "RMCatgName$langId as RMCatgName";

        $data['catList'] = $this->db2->select("*, $RMCatgName")->get_where('RMCatg', array('EID' => $EID))->result_array();
        $data['title'] ='RMCategory';
        $this->load->view('rest/rm_category',$data);
    }

    public function rmitems_list(){
        $langId = $this->session->userdata('site_lang');

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $RMName = "RMName$langId";
            $RMCd = 0;
            if(isset($_POST['RMCd']) && !empty($_POST['RMCd'])){
                $RMCd = $_POST['RMCd'];
            }

            if(!empty($RMCd)){
                updateRecord('RMItems', array("$RMName" => $_POST['RMName']), array('RMCd' => $RMCd));
                $status = 'success';
                $response = 'RMItem Updated.';
            }else{
                $check = getRecords('RMItems', array("$RMName" => $_POST['RMName'], 'RMCatg' => $_POST['RMCatg']));

                if(!empty($check)){
                    $response = 'RMItem Already Exists';
                }else{
                    $cat["$RMName"] = $_POST['RMName'];
                    $cat['RMCatg'] = $_POST['RMCatg'];
                    $cat['ItemId'] = $_POST['ItemId'];
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

        $RMCatgName = "RMCatgName$langId as RMCatgName";

        $data['catList'] = $this->db2->select("*, $RMCatgName")->get('RMCatg')->result_array();
        $data['rm_items'] = $this->rest->getItemLists();
        $data['itemList'] = $this->rest->getAllItemsList();
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
                $bom['RMQty'] = unicodeToEnglish($_POST['RMQty'][$i]);
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

        $langId = $this->session->userdata('site_lang');
        $cname = "Name$langId as Name";

        $data['cuisine'] = $this->db2->select("$cname, CID")->get('Cuisines')->result_array();
        // $data['bom_dish'] = $this->rest->getBomDishLists();
        $data['rm_items'] = $this->rest->getItemLists();
        $data['title'] = $this->lang->line('billOfMaterial');
        // echo "<pre>";
        // print_r($data);
        // die;
        $this->load->view('rest/bom_dish',$data);
    }

    public function getMenuItemList(){
        if($_POST){
            $mcatid = $_POST['MCatgId'];

            $langId = $this->session->userdata('site_lang');
            $lname = "ItemNm$langId as ItemNm";

            $data = $this->db2->select("ItemId, $lname")
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
            $whr = '';
            if($custId > 0){
                $whr = " and b.CustId = $custId";
            }
            
            // $mergeNo = "'".$mergeNo."'";            
            
            $bill = array();
            $EID = authuser()->EID;
             $bills = $this->db2->query("SELECT b.TableNo,b.MergeNo, b.BillId, b.BillNo, b.CNo, b.TotAmt, b.PaidAmt, b.CustId, b.EID, b.MergeNo,b.CellNo from Billing b where b.EID = $EID and b.MergeNo = '$mergeNo' and b.CNo = $MCNo $whr and b.payRest = 0")->result_array();
            $data['sts'] = 0;
            if(!empty($bills)){
                $bill = $bills;
                $data['sts'] = 1;
            }
                        // print_r($this->db2->last_query());die;
            $data['bills'] = $bill;
            $data['payModes'] = $this->rest->getPaymentType();
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
            // die;

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

            $checkBP = $this->db2->get_where('BillPayments', array('EID' => $pay['EID'],'BillId' => $pay['BillId']))->row_array();

            if(!empty($checkBP)){
                updateRecord('BillPayments', array('PymtType' => $pay['PymtType']), array('EID' => $pay['EID'],'BillId' => $pay['BillId']));
            }else{
                unset($pay['oType']);
                $payNo = insertRecord('BillPayments', $pay);
            }

            $status = 'success';
            $response = $this->lang->line('paymentCollected');
            
            if($this->session->userdata('AutoSettle') == 1){
                autoSettlePayment($pay['BillId'], $pay['MergeNo'], $_POST['MCNo']);
            }else{
                $billId = $pay['BillId'];
                $EID = $pay['EID'];

                if($this->session->userdata('EType') == 1){

                    updateRecord('Billing', array('Stat' => 1,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));

                    updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));

                    $this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, k.Stat = 3, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 2) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");
                }else{
                    updateRecord('Billing', array('Stat' => 1,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));

                    updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));
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

    public function test(){
        echo "<pre>";
        print_r($_SESSION);
        die;

        // SELECT e.Name, mc.Name1, mc.EID as catgEID, mi.ItemNm1, mi.ItemId, mi.EID as itmEID, mi.Ctyp, f.Name1, f.FID as fidno FROM FoodType f, MenuCatg mc, MenuItem mi, Eatary e WHERE f.fid=mi.fid and  mc.EID=mi.EID and mc.EID=e.EID and mc.MCatgId=mi.MCatgId and mc.Stat=0 and mi.Stat=0 order by e.EID, mc.EID, mc.Name1, mi.Itemid
        $whr = "mc.EID=mi.EID and ec.EID=mc.EID";
        $dd = $this->db2->select("e.Name, mc.Name1, mc.EID as catgEID, mi.ItemNm1, mi.ItemId, mi.EID as itmEID, mi.Ctyp, f.Name1, f.FID as fidno, c.Name1 as cuisineName, ec.CID")
                ->order_by('e.EID, mc.EID, mc.Name1, mi.Itemid', 'DESC')
                        ->join('MenuItem mi', 'mi.MCatgId =mc.MCatgId', 'inner')
                        ->join('Eatary e', 'e.EID = mc.EID', 'inner')
                        ->join('FoodType f', 'f.fid=mi.fid', 'inner')
                        ->join('Cuisines c', 'c.CID = mc.CID', 'left')
                        ->join('EatCuisine ec', 'ec.CID = mc.CID', 'left')
                        ->where($whr)
                        ->get_where('MenuCatg mc', array(
                                        'mc.Stat' => 0,
                                        'mi.Stat' => 0
                                        )
                    )->result_array();
                        print_r($this->db2->last_query());die;
        // $db3 = $this->load->database('34e', TRUE);
        // $dd = $db3->get('Eat_Kit')->row_array();
        // print_r($dd);
        // die;
// Define the path to your database.php file
        $dbName= '222e';
$database_file = APPPATH . 'config/database.php';
                    $s = "$";
                    $db1 = "db['".$dbName."']";
                    $text_to_append = "$s"."$db1"." = array('dsn'   => '','hostname' => '139.59.28.122','username' => 'developer','password' => 'pqowie321*','database' => '$dbName','dbdriver' => 'mysqli','dbprefix' => '','pconnect' => FALSE,'db_debug' => (ENVIRONMENT !== 'production'),'cache_on' => FALSE,'cachedir' => '','char_set' => 'utf8','dbcollat' => 'utf8_general_ci','swap_pre' => '','encrypt' => FALSE,'compress' => FALSE,'stricton' => FALSE,'failover' => array(),'save_queries' => TRUE);\n";
                        file_put_contents($database_file, $text_to_append.PHP_EOL , FILE_APPEND |   LOCK_EX);
die;
die;

        

        // menu item rates
        $dd = $this->db2->select("e.Name as RestName, mi.ItemNm, es.Name as Section, ip.Name as Item Portion, mir.ItmRate")
                ->group_by('mi.ItemId')
                ->join('MenuItem mi', 'mi.ItemId = mir.ItemId', 'inner')
                ->join('Eatary e', 'e.EID = mi.EID', 'inner')
                ->join('Eat_Sections es', 'es.SecId = mir.SecId', 'inner')
                ->join('ItemPortions ip', 'ip.IPCd = mir.Itm_Portion', 'inner')
                ->get('MenuItemRates mir')
                ->result_array();

        echo "<pre>";
            print_r($this->db2->last_query());
            print_r($dd);
            die;

        // menu item
        $dd = $this->db2->select('e.Name as RestName, c.Name as Cuisine, mc.MCatgNm,fdd.Usedfor as CatgType, fd.Opt FoodType, mi.ItemNm, mi.IMcCd, mi.PckCharge, it.Name as ItemType, mi.ItemAttrib, mi.ItemSale, mi.ItemTag, mi.NV, mi.MaxDisc, mi.StdDiscount, mi.DiscRate, mi.Rank, mi.ItmDesc, mi.Ingeredients, mi.MaxQty, mi.MTyp, mi.Rmks as Remarks, mi.PrepTime,  mi.FrmTime, mi.ToTime, mi.AltFrmTime, mi.AltToTime, mi.DayNo, mi.KitCd, mi.videoLink')
            ->group_by('mi.ItemId')
                        ->join('Eatary e', 'e.EID = mi.EID', 'inner')
                        ->join('Cuisines c', 'c.CID = mi.CID', 'inner')
                        ->join('MenuCatg mc', 'mc.MCatgId = mi.MCatgId', 'inner')
                        ->join('FoodType fd', 'fd.FID = mi.FID', 'inner')
                        ->join('FoodType fdd', 'fdd.CTyp = mi.CTyp', 'inner')
                        ->join('ItemTypes it', 'it.ItmTyp = mi.ItemTyp')
                        ->get('MenuItem mi')
                        ->result_array();

                        echo "<pre>";
            print_r($this->db2->last_query());
            print_r($dd);
            die;
        // menuCategory
            $dd = $this->db2->select('e.Name as RestName, c.Name as Cuisine, mc.MCatgNm, fd.Usedfor CategoryType')
            ->group_by('mc.MCatgId')
                        ->join('Eatary e', 'e.EID = mc.EID', 'inner')
                        ->join('Cuisines c', 'c.CID = mc.CID', 'inner')
                        ->join('FoodType fd', 'fd.CTyp = mc.CTyp', 'inner')
                        ->get('MenuCatg mc')
                        ->result_array();
            echo "<pre>";
            print_r($this->db2->last_query());
            print_r($dd);
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

        $EType = $this->session->userdata('EType');
        if($this->input->method(true)=='POST'){
            
            $MergeNo = $_POST['mergeNo'];
            $EID = authuser()->EID;
             // SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt,

            $langId = $this->session->userdata('site_lang');
            $lname = "m.ItemNm$langId";
            $ipName = "ip.Name$langId  as Portions";

            $groupby = ' km.MCNo';
            if($_POST['tableFilter'] == 'tableWise'){
                $characterToFind = '~';
                $count = substr_count($MergeNo, $characterToFind);
                if ($count > 0) {
                    $groupby = ' km.MergeNo';
                }
            }

            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate,k.ItmRate,  SUM(if (k.TA=1,((k.OrigRate)*k.Qty),(k.OrigRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where k1.MergeNo = km.MergeNo and k1.MergeNo = $MergeNo  and k1.EID=km.EID AND (k1.Stat = 3) and k1.BillStat = km.BillStat GROUP BY k1.EID) as TotPckCharge, $ipName, km.CNo,km.MergeNo, km.MCNo,sum(km.BillDiscAmt) as BillDiscAmt, sum(km.DelCharge) as DelCharge, sum(km.RtngDiscAmt) as totRtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType, k.TA, km.RtngDiscAmt,km.TableNo, km.CustId, c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND km.MergeNo = $MergeNo group by $groupby, k.TA,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.ItemNm1 Asc")->result_array();

            // remove string
            $MergeNo = str_replace("'","",$MergeNo);
            // echo "<pre>";
            // print_r($MergeNo);
            // print_r($kitcheData);
            // print_r($MergeNo);
            // print_r($this->db2->last_query());
            // die;
            
                $taxDataArray = array();
                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    $CNo = $kitcheData[0]['MCNo'];

                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo);
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
                    
                    $custDiscount = ($orderAmt * $_POST['custDiscPer']) / 100;

                    // check session and remove
                    $this->session->set_userdata('TipAmount', 0);
                    $this->session->set_userdata('itemTotalGross', $orderAmt);
                    $this->session->set_userdata('ONo', 0);
                    $this->session->set_userdata('CustNo', 0);
                    $this->session->set_userdata('COrgId', 0); 

                    // add 16-nov-23
                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    $charge = $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    // add 16-nov-23 end

                    // grand total
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount - $custDiscount;
                    
                    $postData["orderAmount"] = $total;
                    $postData["paymentMode"] = 'RCash';
                    $postData["MergeNo"] = $MergeNo;
                    $postData["TableNo"] = $kitcheData[0]['TableNo'];
                    $postData["cust_discount"] = $custDiscount;

                    $custId = $kitcheData[0]['CustId'];
                    // echo "<pre>";print_r($postData);die;
                    $res = billCreate($EID, $CNo, $postData);
                    if($res['status'] > 0){
                        updateRecord('KitchenMain', array('discount' => $_POST['custDiscPer']), array('CNo' => $CNo, 'MergeNo' => '$MergeNo','EID' => $EID));
                        $status = 'success';
                        $response = $res['billId'];         
                    }
                }else{
                    $response = 'Bill Already Generated.';
                }
            
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
// 8/19/5
    public function kot_billing($billId, $MCNo, $MergeNo, $FKOTNo){

        $data['title'] = 'KOT & Billking';
        $kotList = $this->rest->getKotList($MCNo, $MergeNo, $FKOTNo);
        $group_arr = [];
        foreach ($kotList as $key ) {
            $kot = $key['KitCd'];
            if(!isset($group_arr[$kot])){
                $group_arr[$kot] = [];
            }
            array_push($group_arr[$kot], $key);
        }
        $data['kotList'] = $group_arr;


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
            $data['Fullname'] = getName($billData[0]['CustId']);
            $data['CellNo'] = $billData[0]['CellNo'];
            $data['phone'] = $billData[0]['PhoneNos'];
            $data['gstno'] = $billData[0]['GSTno'];
            $data['fssaino'] = $billData[0]['FSSAINo'];
            $data['cinno'] = $billData[0]['CINNo'];
            $data['billno'] = $billData[0]['BillNo'];
            $data['OType'] = $billData[0]['OType'];
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
        $this->load->view('rest/kot_bill', $data);
    }

    public function payments(){
        // echo "<pre>";
        // print_r($_SESSION);
        // die;
        $data['title'] = $this->lang->line('customerPayments');
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
        $data['title'] = $this->lang->line('customerFeedback');
        $data['list'] = $this->db2->order_by('created_at','DESC')->get_where('Feedback', array('EID' => authuser()->EID))->result_array();
        
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
        $data['title'] = $this->lang->line('qrColdeLink');
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

            // remove all files inside this folder uploads/qrcode/
            $filesPath = glob('uploads/qrcode/*'); // get all file names
            foreach($filesPath as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); // delete file
              }
            }  
            // end remove all files inside folder
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

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;

            $getItem = $this->db2->select('UItmCd, Rank')->order_by('ItemId', 'DESC')->get('MenuItem')->row_array();

            $data = $_POST;

            $langId = $this->session->userdata('site_lang');

            $iname = "ItemNm$langId";
            $descname = "ItmDesc$langId";
            $ingname = "Ingeredients$langId";

            $data[$iname] = $data['ItemNm'];
            $data[$descname] = $data['ItmDesc'];
            $data[$ingname] = $data['Ingeredients'];

            unset($data['sections']);
            unset($data['portions']);
            unset($data['price']);
            unset($data['ItemNm']);
            unset($data['ItmDesc']);
            unset($data['Ingeredients']);

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
            if(isset($_FILES['item_file']['name']) && !empty($_FILES['item_file']['name'])){ 

                $files = $_FILES['item_file'];

                $allowed = array('jpeg', 'jpg');
                $filename_c = $_FILES['item_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only jpg,jpeg format!');
                }
                // less than 1mb size upload
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }

                $_FILES['item_file']['name']= $files['name'];
                $_FILES['item_file']['type']= $files['type'];
                $_FILES['item_file']['tmp_name']= $files['tmp_name'];
                $_FILES['item_file']['error']= $files['error'];
                $_FILES['item_file']['size']= $files['size'];
                $file = $data[$iname];

                $folderPath = 'uploads/e'.authuser()->EID;

                if($flag == 0){
                    $res = do_upload('item_file',$file,$folderPath,'*');
                }
                
              }

            if($flag == 0){
              $ItemId = insertRecord('MenuItem', $data);

              $menuRates = [];
              $tempData = [];
              for ($i=0; $i < sizeof($_POST['sections']); $i++) { 
                  $tempData['EID'] = authuser()->EID;
                  $tempData['ChainId'] = authuser()->ChainId;
                  $tempData['ItemId'] = $ItemId;
                  $tempData['SecId'] = $_POST['sections'][$i];
                  $tempData['Itm_Portion'] = $_POST['portions'][$i];
                  $tempData['OrigRate'] = 0;
                  $tempData['ItmRate'] = $_POST['price'][$i];
                  $menuRates[] = $tempData;
              }
              $this->db2->insert_batch('MenuItemRates', $menuRates); 
              
              if(!empty($ItemId)){
                $this->session->set_flashdata('success','Record Inserted.');
              }
            }
        }
        
        $data['title'] = $this->lang->line('addItem');
        $data['MCatgIds'] = $this->rest->get_MCatgId();
        $data['CuisineList'] = $this->rest->getCuisineList();
        $data['FoodType'] = $this->rest->get_foodType();
        $data['Eat_Kit'] = $this->rest->get_kitchen();
        $data['ctypList'] = $this->rest->getCTypeList();
        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['menuTags'] = $this->rest->getMenuTagList();
        $data['uomList'] = $this->rest->getUOMlist();        
        $data['EatSections'] = $this->rest->get_eat_section();
        $data['ItemPortions'] = $this->rest->get_item_portion();
        
        $this->load->view('rest/add_item', $data);
    }

    public function get_item_name(){
        extract($_POST);
        $data = $this->rest->get_item_name_list($name);
        echo json_encode($data);
        die;
    }

    public function edit_item($ItemId){
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
 
            $updateData = $_POST;

            $langId = $this->session->userdata('site_lang');

            $iname = "ItemNm$langId";
            $descname = "ItmDesc$langId";
            $ingname = "Ingeredients$langId";

            $updateData[$iname] = $updateData['ItemNm'];
            $updateData[$descname] = $updateData['ItmDesc'];
            $updateData[$ingname] = $updateData['Ingeredients'];

            $flag = 0;

            if(isset($_FILES['item_file']['name']) && !empty($_FILES['item_file']['name'])){ 

                // remove existing file
                $folderPath = 'uploads/e'.$EID;
                $filename = $folderPath.'/'.$updateData[$iname].'.jpg'; 
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
                $file = $updateData[$iname];
                if($flag == 0){
                    $res = do_upload('item_file',$file,$folderPath,'*');
                }
              }

            if($flag == 0){
                unset($updateData['ItemId']);
                unset($updateData['sections']);
                unset($updateData['portions']);
                unset($updateData['price']);
                unset($updateData['ItemNm']);
                unset($updateData['ItmDesc']);
                unset($updateData['Ingeredients']);

                updateRecord('MenuItem', $updateData, array('ItemId' => $ItemId));
                $this->db2->delete('MenuItemRates', array('ItemId' => $ItemId, 'EID' => $EID));
                $menuRates = [];
                $tempData = [];
                for ($i=0; $i < sizeof($_POST['sections']); $i++) { 
                  $tempData['EID'] = $EID;
                  $tempData['ChainId'] = authuser()->ChainId;
                  $tempData['ItemId'] = $ItemId;
                  $tempData['SecId'] = $_POST['sections'][$i];
                  $tempData['Itm_Portion'] = $_POST['portions'][$i];
                  $tempData['OrigRate'] = 0;
                  $tempData['ItmRate'] = $_POST['price'][$i];
                  $menuRates[] = $tempData;
                }
                $this->db2->insert_batch('MenuItemRates', $menuRates); 
                $this->session->set_flashdata('success','Record Updated.');
            }
        }
        $data['title'] = 'Edit Item';
        $data['ItemId'] = $ItemId;
        $data['MCatgIds'] = $this->rest->get_MCatgId();
        $data['CuisineList'] = $this->rest->getCuisineList();
        $data['FoodType'] = $this->rest->get_foodType();
        $data['Eat_Kit'] = $this->rest->get_kitchen();
        $data['ctypList'] = $this->rest->getCTypeList();
        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['menuTags'] = $this->rest->getMenuTagList();
        $data['uomList'] = $this->rest->getUOMlist();
        $data['EatSections'] = $this->rest->get_eat_section();
        $data['ItemPortions'] = $this->rest->get_item_portion();
        $data['itemType'] = $this->rest->getItemTypeList();
        $langId = $this->session->userdata('site_lang');
        $lname = "ItemNm$langId as ItemNm";
        $Descname = "ItmDesc$langId as ItmDesc";
        $Ingeredients = "Ingeredients$langId as Ingeredients";
        $data['detail'] = $this->db2->select("*, $lname, $Descname, $Ingeredients")->get_where('MenuItem', array('ItemId' => $ItemId))->row_array();
        $data['itmRates'] = $this->db2->select("*")->get_where('MenuItemRates', array('EID' => $EID,'ItemId' => $ItemId))->result_array();

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
            $data['Fullname'] = getName($billData[0]['CustId']);
            $data['CellNo'] = $billData[0]['CellNo'];
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
        $data['title'] = $this->lang->line('kot');
        // echo "<pre>";
        // print_r($data);
        // die;

        $group_arr = [];
        foreach ($data['kotList'] as $key ) {
            $kot = $key['KitCd'];
            if(!isset($group_arr[$kot])){
                $group_arr[$kot] = [];
            }
            array_push($group_arr[$kot], $key);
        }

        $data['kotList'] = $group_arr;

        // echo "<pre>";
        // print_r($group_arr);
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
        $status = 'error';
        $response = $this->lang->line('notSettled');
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            extract($_POST);
            
            autoSettlePayment($billId, $MergeNo, $CNo);

            $status = 'success';
            $response = $this->lang->line('billingSettled');
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function kds(){
        $EID = authuser()->EID;
        $data['title'] = $this->lang->line('kitchenDisplaySystem');
        $minutes = 0;
        $kitcd = 0;
        if($this->input->method(true)=='POST'){
            $minutes = $_POST['minutes'];
            $minutes = unicodeToEnglish($minutes);
            $kitcd = $_POST['kitchen'];
        }
        $data['minutes'] = $minutes;
        $data['kitcd'] = $kitcd;
        $data['kds'] = $this->rest->getPendingKOTLIST($minutes, $kitcd);

        $data['kitchen'] = $this->rest->getKitchenList();
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
        $data['title'] = $this->lang->line('kitchenPlanner');
        $data['kplanner'] = array();
        $kitcd = 0;
        if($this->input->method(true)=='POST'){
            $kitcd = $_POST['kitchen'];
            $data['kplanner'] = $this->rest->getPendingItemLIST($kitcd);
        }
        
        $data['kitcd'] = $kitcd;
        $data['kitchen'] = $this->rest->getKitchenList();
        
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

    public function thirdParty(){
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        $data['bills'] = $this->rest->getTAPendingBills();
        $data['cashier'] = $this->rest->getCasherList();
        $data['payModes'] = $this->rest->getPaymentType();
        // echo "<pre>";
        // print_r($data);
        // die;
        $data['title'] = $this->lang->line('thirdParty');
        $data['OType'] = 101;
        $this->load->view('rest/offline_order', $data);
    }

    public function takeAway(){
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        $data['bills'] = $this->rest->getTAPendingBills();
        $data['payModes'] = $this->rest->getPaymentType();
        $data['cashier'] = $this->rest->getCasherList();
        // echo "<pre>";
        // print_r($data);
        // die;
        $data['title'] = $this->lang->line('takeAway');
        $data['OType'] = 105;
        $this->load->view('rest/offline_order', $data);
    }

    public function Deliver(){
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        $data['bills'] = $this->rest->getTAPendingBills();
        $data['cashier'] = $this->rest->getCasherList();
        $data['payModes'] = $this->rest->getPaymentType();
        // echo "<pre>";
        // print_r($data);
        // die;
        $data['title'] = $this->lang->line('deliver');
        $data['OType'] = 110;
        $this->load->view('rest/offline_order', $data);
    }

    public function sitIn(){
        $EType = $this->session->userdata('EType');
        $data['title'] = $this->lang->line('sitIn');
        if($EType == 5){
            $EID = authuser()->EID;
            $data['EID'] = $EID;
            $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
            $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
            $data['cashier'] = $this->rest->getCasherList();
            // echo "<pre>";
            // print_r($data);
            // die;
            $data['OType'] = 8;
            $this->load->view('rest/offline_order', $data);
        }else{
            $this->load->view('page403', $data);
        }
    }

    public function get_portions(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = "success";
            $response = $this->rest->getMenuItemRates($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_item_offer(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = "success";
            $response = $this->rest->getItemOfferList($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }
    
    public function get_custom_items(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $customDetails = $this->rest->getCustomItemsList($_POST);

            if(!empty($customDetails)){
                $status = "success";

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
            }else{
              $response =  'No customization available!!';  
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    // csv file upload

    public function csv_file_upload(){
        $EID = $this->session->userdata('EID');
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;
            $folderPath = 'uploads/e'.$EID.'/csv';
            // remove all files inside this folder uploads/qrcode/
            $filesPath = glob($folderPath.'/*'); // get all file names
            foreach($filesPath as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); // delete file
              }
            }  
            // end remove all files inside folder

            $flag = 0;

            switch ($_POST['type']) {
                case 'eatary':

                    if(isset($_FILES['eatary_file']['name']) && !empty($_FILES['eatary_file']['name'])){ 
                        $files = $_FILES['eatary_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['eatary_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['eatary_file']['name']= $files['name'];
                        $_FILES['eatary_file']['type']= $files['type'];
                        $_FILES['eatary_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['eatary_file']['error']= $files['error'];
                        $_FILES['eatary_file']['size']= $files['size'];
                        $file = $files['name'];

                        $eatary_id = 0;
                        if($flag == 0){
                            $res = do_upload('eatary_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='ChainId'){

                                        $eatObj['ChainId'] = $csv_data[0];
                                        $eatObj['ONo'] = $csv_data[1];
                                        $eatObj['Stall'] = $csv_data[2];
                                        $eatObj['Name'] = $csv_data[3];
                                        $eatObj['CatgID'] = $csv_data[4];
                                        $eatObj['CountryCd'] = $csv_data[5];
                                        $eatObj['CityCd'] = $csv_data[6];
                                        $eatObj['Addr'] = $csv_data[7];
                                        $eatObj['Suburb'] = $csv_data[8];
                                        $eatObj['EWNS'] = $csv_data[9];
                                        $eatObj['City'] = $csv_data[10];
                                        $eatObj['Pincode'] = $csv_data[11];
                                        $eatObj['Tagline'] = $csv_data[12];
                                        $eatObj['Remarks'] = $csv_data[13];
                                        $eatObj['GSTNo'] = $csv_data[14];
                                        $eatObj['CINNo'] = $csv_data[15];
                                        $eatObj['FSSAINo'] = $csv_data[16];
                                        $eatObj['PhoneNos'] = $csv_data[17];
                                        $eatObj['Email'] = $csv_data[18];
                                        $eatObj['Website'] = $csv_data[19];
                                        $eatObj['ContactNos'] = $csv_data[20];
                                        $eatObj['ContactAddr'] = $csv_data[21];
                                        $eatObj['BillerName'] = $csv_data[22];
                                        $eatObj['BillerGSTNo'] = $csv_data[23];
                                        $eatObj['BTyp'] = $csv_data[24];
                                        $eatObj['VFM'] = $csv_data[25];
                                        $eatObj['TaxInBill'] = $csv_data[26];
                                        $eatObj['QRLink'] = $csv_data[27];
                                        $eatObj['LstModDt'] = date('Y-m-d H:i:s');
                                        $eatObj['Stat'] = 0;
                                        $eatObj['LoginCd'] = authuser()->RUserId;
                                        $eatary_id = insertRecord('Eatary', $eatObj);
                                    }
                                }
                                fclose($open);
                            }
                        }

                        if(!empty($eatary_id)){
                            $status = 'success';
                            $response = 'Data Inserted';
                            $this->session->set_flashdata('success','Data Inserted.');
                        }
                      }

                break;

                case 'cuisine':
                    if(isset($_FILES['cuisine_file']['name']) && !empty($_FILES['cuisine_file']['name'])){ 
                        $files = $_FILES['cuisine_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['cuisine_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['cuisine_file']['name']= $files['name'];
                        $_FILES['cuisine_file']['type']= $files['type'];
                        $_FILES['cuisine_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['cuisine_file']['error']= $files['error'];
                        $_FILES['cuisine_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('cuisine_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $cuisineData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $temp['EID'] = $this->checkEatary($csv_data[0]);

                                        if($temp['EID'] < 1){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Name1'] =  $csv_data[1];

                                        if(empty($temp['Name1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['CID'] =  $this->checkCuisine($csv_data[2]);
                                        if($temp['CID'] == 0){
                                          $response = $csv_data[2]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['KitCd'] =  $this->checkKitchen($csv_data[3], $temp['EID']);
                                        if($temp['KitCd'] == 0){
                                          $response = $csv_data[3]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Rank'] = $csv_data[4];
                                        $temp['Stat'] = 0;
                                        $temp['LoginId'] = authuser()->RUserId;

                                        if($checker == 0){
                                            $cuisineData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }
                                        $cuisineData[] = $temp;
                                    }
                                }

                                if(!empty($cuisineData)){
                                    $this->db2->insert_batch('EatCuisine', $cuisineData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'menucatg':
                    if(isset($_FILES['mcatg_file']['name']) && !empty($_FILES['mcatg_file']['name'])){ 
                        $files = $_FILES['mcatg_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mcatg_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mcatg_file']['name']= $files['name'];
                        $_FILES['mcatg_file']['type']= $files['type'];
                        $_FILES['mcatg_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['mcatg_file']['error']= $files['error'];
                        $_FILES['mcatg_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mcatg_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                $mcData = [];
                                $mc = [];
                                $count = 1;
                                $checker = 0;
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                            
                                            $mc['EID'] = $this->checkEatary($csv_data[0]);

                                            $mc['CID'] = $this->checkCuisine($csv_data[1]);
                                            $mc['Name1'] = $csv_data[2];
                                            $mc['CTyp'] = $this->checkCTyp($csv_data[3]);
                                            $mc['Rank'] = $csv_data[4];
                                            $mc['KitCd'] = $this->checkKitchen($csv_data[5], $mc['EID']);
                                            $checker = 1;
                                            
                                            if($mc['EID'] == 0){
                                              $response = $csv_data[0]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if($mc['CID'] == 0){
                                              $response = $csv_data[1]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if(empty($mc['Name1'])){
                                              $response = $csv_data[2]. " Field Required in row no: $count";
                                              $checker = 0;
                                            }

                                            // if($mc['CTyp'] == 0){
                                            //   $response = $csv_data[3]. " Not Found in row no: $count";
                                            //   $checker = 0;
                                            // }

                                            if($mc['KitCd'] == 0){
                                              $response = $csv_data[5]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if($checker == 0){
                                                $mcData = [];
                                                header('Content-Type: application/json');
                                                echo json_encode(array(
                                                    'status' => $status,
                                                    'response' => $response
                                                  ));
                                                 die; 
                                            }

                                            $mcData[] = $mc;
                                    }
                                    $count++;
                                }

                                if(!empty($mcData)){
                                    $this->db2->insert_batch('MenuCatg', $mcData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                                
                                fclose($open);
                            }
                        }
                      }
                break;

                case 'itemType':
                    if(isset($_FILES['itemtype_file']['name']) && !empty($_FILES['itemtype_file']['name'])){ 
                        $files = $_FILES['itemtype_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['itemtype_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['itemtype_file']['name']= $files['name'];
                        $_FILES['itemtype_file']['type']= $files['type'];
                        $_FILES['itemtype_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['itemtype_file']['error']= $files['error'];
                        $_FILES['itemtype_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('itemtype_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                $typeData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                            
                                            $temp['EID'] = $this->checkEatary($csv_data[0]);

                                            $temp['Name1'] = $csv_data[1];
                                            $checker = 1;
                                            
                                            if($temp['EID'] < 1){
                                              $response = $csv_data[0]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if(empty($temp['Name1'])){
                                              $response = $csv_data[1]. " Field Required in row no: $count";
                                              $checker = 0;
                                            }

                                            $temp['Stat'] = 0;

                                            if($checker == 0){
                                                $typeData = [];
                                                header('Content-Type: application/json');
                                                echo json_encode(array(
                                                    'status' => $status,
                                                    'response' => $response
                                                  ));
                                                 die; 
                                            }

                                            $typeData[] = $temp;
                                    }
                                    $count++;
                                }

                                if(!empty($typeData)){
                                    $this->db2->insert_batch('ItemTypes', $typeData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                                
                                fclose($open);
                            }
                        }
                      }
                break;

                case 'menuitem':
                    if(isset($_FILES['mitem_file']['name']) && !empty($_FILES['mitem_file']['name'])){ 
                        $files = $_FILES['mitem_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mitem_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mitem_file']['name']= $files['name'];
                        $_FILES['mitem_file']['type']= $files['type'];
                        $_FILES['mitem_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['mitem_file']['error']= $files['error'];
                        $_FILES['mitem_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mitem_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $mItemData = [];
                                $mItem = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                    
                                        $checker = 1;

                                        $mItem['EID'] = $this->checkEatary($csv_data[0]);

                                        if($mItem['EID'] < 1){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }
                                            $mItem['CID'] = $this->checkCuisine($csv_data[1]);

                                            if($mItem['CID'] == 0){
                                              $response = $csv_data[1]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['MCatgId'] = $this->checkMenuCatg($mItem['EID'], $csv_data[2]);

                                            if($mItem['MCatgId'] == 0){
                                              $response = $csv_data[2]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['CTyp'] = $this->checkCTyp($csv_data[3]);
                                            // if($mItem['CTyp'] == 0){
                                            //   $response = $csv_data[3]. " Not Found in row no: $count";
                                            //   $checker = 0;
                                            // }

                                            $mItem['FID'] = $this->checkFoodType($csv_data[4]);

                                            if($mItem['FID'] == 0){
                                              $response = $csv_data[4]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['ItemNm1'] = $csv_data[5];
                                            $mItem['IMcCd'] = $csv_data[6];
                                            $mItem['PckCharge'] = $csv_data[7];
                                            $mItem['ItemTyp'] = $this->checkItemType($csv_data[8], $mItem['EID']);
                                            if($mItem['ItemTyp'] == 0){
                                              $response = $csv_data[8]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['ItemAttrib'] = $this->checkType($csv_data[9], 1);

                                            if($mItem['ItemAttrib'] == 0){
                                              $response = $csv_data[9]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['ItemSale'] = $this->checkType($csv_data[10], 3);

                                            if($mItem['ItemSale'] == 0){
                                              $response = $csv_data[10]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['ItemTag'] = $this->checkType($csv_data[11], 2);

                                            if($mItem['ItemTag'] == 0){
                                              $response = $csv_data[11]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['NV'] = $csv_data[12];
                                            $mItem['MaxDisc'] = $csv_data[13];
                                            $mItem['StdDiscount'] = $csv_data[14];
                                            $mItem['DiscRate'] = $csv_data[15];
                                            $mItem['Rank'] = $csv_data[16];
                                            $mItem['ItmDesc1'] = $csv_data[17];
                                            $mItem['Ingeredients1'] = $csv_data[18];
                                            $mItem['MaxQty'] = $csv_data[19];
                                            $mItem['MTyp'] = $csv_data[20];
                                            $mItem['Rmks1'] = $csv_data[21];
                                            $mItem['PrepTime'] = $csv_data[22];
                                            $mItem['FrmTime'] = $csv_data[23];
                                            $mItem['ToTime'] = $csv_data[24];
                                            $mItem['AltFrmTime'] = $csv_data[25];
                                            $mItem['AltToTime'] = $csv_data[26];
                                            $mItem['DayNo'] = getDayNo($csv_data[27]);

                                            if($mItem['DayNo'] == 0){
                                              $response = $csv_data[27]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['KitCd'] = $this->checkKitchen($csv_data[28], $mItem['EID']);

                                            if($mItem['KitCd'] == 0){
                                              $response = $csv_data[28]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['videoLink'] = $csv_data[29];
                                            $mItem['Stat'] = 0;
                                            $mItem['LoginCd'] = authuser()->RUserId;
                                        

                                        if($checker == 0){
                                            $mItemData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $mItemData[] = $mItem;
                                    }
                                    $count++;    
                                }

                                if(!empty($mItemData)){

                                    $this->db2->insert_batch('MenuItem', $mItemData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                             
                                fclose($open);
                            }
                        }
                      }
                break;

                case 'itemrates':
                    if(isset($_FILES['mitem_rates']['name']) && !empty($_FILES['mitem_rates']['name'])){ 
                        $files = $_FILES['mitem_rates'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mitem_rates']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mitem_rates']['name']= $files['name'];
                        $_FILES['mitem_rates']['type']= $files['type'];
                        $_FILES['mitem_rates']['tmp_name']= $files['tmp_name'];
                        $_FILES['mitem_rates']['error']= $files['error'];
                        $_FILES['mitem_rates']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mitem_rates',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                $mRatesData = [];
                                $mc = [];
                                $count = 1;
                                $checker = 0;
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    if($csv_data[0] !='RestName'){
                                        // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                        $checker = 1;
                                        $mc['EID'] = $this->checkEatary($csv_data[0]);

                                        if($mc['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['ItemId'] = $this->getItemId($mc['EID'], $csv_data[1]);

                                        if($mc['ItemId'] == 0){
                                          $response = $csv_data[1]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['SecId'] = $this->checkSection($csv_data[2]);

                                        if($mc['SecId'] == 0){
                                          $response = $csv_data[2]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['Itm_Portion'] = $this->checkPortion($csv_data[3]);

                                        if($mc['Itm_Portion'] == 0){
                                          $response = $csv_data[3]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['ItmRate'] = $csv_data[4];
                                        $mc['ChainId'] = 0;

                                        if($checker == 0){
                                            $mRatesData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $mRatesData[] = $mc;
                                    }
                                    $count++;
                                }

                                if(!empty($mRatesData)){

                                    $this->db2->insert_batch('MenuItemRates', $mRatesData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'itemRecom':
                    if(isset($_FILES['mitem_recos']['name']) && !empty($_FILES['mitem_recos']['name'])){ 
                        $files = $_FILES['mitem_recos'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mitem_recos']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mitem_recos']['name']= $files['name'];
                        $_FILES['mitem_recos']['type']= $files['type'];
                        $_FILES['mitem_recos']['tmp_name']= $files['tmp_name'];
                        $_FILES['mitem_recos']['error']= $files['error'];
                        $_FILES['mitem_recos']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mitem_recos',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $mRatesData = [];
                                $mc = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $mc['EID'] = $this->checkEatary($csv_data[0]);

                                        if($mc['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['ItemId'] = $this->getItemId($mc['EID'], $csv_data[1]);

                                        if($mc['ItemId'] == 0){
                                          $response = $csv_data[1]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['RcItemId'] = $this->getItemId($mc['EID'], $csv_data[2]);

                                        if($mc['RcItemId'] == 0){
                                          $response = $csv_data[2]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['Remarks'] = '-';
                                        $mc['Stat'] = 0;
                                        $mc['LoginCd'] = authuser()->RUserId;

                                        if($checker == 0){
                                            $mRatesData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $mRatesData[] = $mc;
                                    }
                                }

                                if(!empty($mRatesData)){

                                    $this->db2->insert_batch('MenuItem_Recos', $mRatesData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'kitchen':
                    if(isset($_FILES['kitchen_file']['name']) && !empty($_FILES['kitchen_file']['name'])){ 
                        $files = $_FILES['kitchen_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['kitchen_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['kitchen_file']['name']= $files['name'];
                        $_FILES['kitchen_file']['type']= $files['type'];
                        $_FILES['kitchen_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['kitchen_file']['error']= $files['error'];
                        $_FILES['kitchen_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('kitchen_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $kitchenData = [];
                                $kd = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $kd['EID'] = $this->checkEatary($csv_data[0]);

                                        if($kd['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['KitName1'] =  $csv_data[1];

                                        if(empty($kd['KitName1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['PrinterName'] =  $csv_data[2];
                                        $kd['Stat'] = 0;

                                        if($checker == 0){
                                            $kitchenData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $kitchenData[] = $kd;
                                    }
                                }

                                if(!empty($kitchenData)){
                                    $this->db2->insert_batch('Eat_Kit', $kitchenData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'cashier':
                    if(isset($_FILES['cashier_file']['name']) && !empty($_FILES['cashier_file']['name'])){ 
                        $files = $_FILES['cashier_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['cashier_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['cashier_file']['name']= $files['name'];
                        $_FILES['cashier_file']['type']= $files['type'];
                        $_FILES['cashier_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['cashier_file']['error']= $files['error'];
                        $_FILES['cashier_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('cashier_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $cashierData = [];
                                $kd = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $kd['EID'] = $this->checkEatary($csv_data[0]);

                                        if($kd['EID'] < 1){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['Name1'] =  $csv_data[1];

                                        if(empty($kd['Name1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['PrinterName'] =  $csv_data[2];
                                        $kd['Stat'] = 0;

                                        if($checker == 0){
                                            $cashierData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $cashierData[] = $kd;
                                    }
                                }

                                if(!empty($cashierData)){
                                    $this->db2->insert_batch('Eat_Casher', $cashierData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'dispenseOutlet':
                    if(isset($_FILES['dispense_file']['name']) && !empty($_FILES['dispense_file']['name'])){ 
                        $files = $_FILES['dispense_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['dispense_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['dispense_file']['name']= $files['name'];
                        $_FILES['dispense_file']['type']= $files['type'];
                        $_FILES['dispense_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['dispense_file']['error']= $files['error'];
                        $_FILES['dispense_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('dispense_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $dispData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $temp['EID'] = $this->checkEatary($csv_data[0]);

                                        if($temp['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Name1'] =  $csv_data[1];

                                        if(empty($temp['Name1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }
                                        $temp['Stat'] = 0;

                                        if($checker == 0){
                                            $dispData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }
                                        $dispData[] = $temp;
                                    }
                                }

                                if(!empty($dispData)){
                                    $this->db2->insert_batch('Eat_DispOutlets', $dispData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'table':
                    if(isset($_FILES['table_file']['name']) && !empty($_FILES['table_file']['name'])){ 
                        $files = $_FILES['table_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['table_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['table_file']['name']= $files['name'];
                        $_FILES['table_file']['type']= $files['type'];
                        $_FILES['table_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['table_file']['error']= $files['error'];
                        $_FILES['table_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('table_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $tableData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $temp['EID'] = $this->checkEatary($csv_data[0]);

                                        if($temp['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['TableNo'] =  $csv_data[1];

                                        if(empty($temp['TableNo'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['MergeNo'] = $temp['TableNo'];
                                        $temp['TblTyp'] =  $csv_data[2];
                                        if(empty($temp['TblTyp'])){
                                          $response = $csv_data[2]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Capacity'] =  $csv_data[3];
                                        if(empty($temp['Capacity'])){
                                          $response = $csv_data[3]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['SecId'] =  $this->checkSection($csv_data[4]);
                                        if($temp['SecId'] == 0){
                                          $response = $csv_data[4]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['CCd'] =  $this->checkCashier($temp['EID'], $csv_data[5]);
                                        if($temp['CCd'] == 0){
                                          $response = $csv_data[5]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Stat'] = 0;
                                        $temp['LoginCd'] = authuser()->RUserId;

                                        if($checker == 0){
                                            $tableData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }
                                        $tableData[] = $temp;
                                    }
                                }

                                if(!empty($tableData)){
                                    $this->db2->insert_batch('Eat_tables', $tableData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;    
        }
        $data['title'] = $this->lang->line('file').' '.$this->lang->line('upload');
        
        $this->load->view('rest/csv_file', $data);   
    }

    private function checkEatary($name){
        $EID = 0;
        $data = $this->db2->select('EID')->like('Name', $name)->get('Eatary')->row_array();
        if(!empty($data)){
            $EID = $data['EID'];
        }
        return $EID;
    }

    private function checkMenuCatg($EID, $name){
        $MCatgId = 0;
        $data = $this->db2->select('MCatgId')->like('Name1', $name)->get_where('MenuCatg', array('EID' => $EID))->row_array();
        if(!empty($data)){
            $MCatgId = $data['MCatgId'];
        }
        return $MCatgId;
    }

    private function checkEatCuisine($name){
        $ECID = 0;
        $cuisine = $this->db2->select('ECID')->like('Name1', $name)->get_where('EatCuisine', array('EID' => authuser()->EID))->row_array();
        if(!empty($cuisine)){
            $ECID = $cuisine['ECID'];
        }else{
            $eat['Name1'] = $name;
            $eat['EID'] = authuser()->EID;
            $eat['CID'] = $this->checkCuisine($name);
            $ECID = insertRecord('EatCuisine', $eat);
        }
        return $ECID;
    }

    private function getCIDFromEatCuisine($name){
        $cid = 0;
        $cuisine = $this->db2->select('CID')->like('Name1', $name)->get_where('EatCuisine', array('EID' => authuser()->EID))->row_array();
        if(!empty($cuisine)){
            $cid = $cuisine['CID'];
        }
        return $cid;
    }

    private function checkCuisine($name){
        $cid = 0;
        $cuisine = $this->db2->select('CID')->like('Name1', $name)->get('Cuisines')->row_array();
        if(!empty($cuisine)){
            $cid = $cuisine['CID'];
        }
        return $cid;
    }

    private function checkCTyp($usedFor){
        $CTyp = 0;
        $data = $this->db2->select('CTyp')->like('Usedfor1', $usedFor)->get('FoodType')->row_array();
        if(!empty($data)){
            $CTyp = $data['CTyp'];
        }
        return $CTyp;
    }

    private function checkKitchen($name, $EID){
        $KitCd = 0;
        $data = $this->db2->select('KitCd')->like('KitName1', $name)->get_where('Eat_Kit', array('EID' => $EID))->row_array();
        if(!empty($data)){
            $KitCd = $data['KitCd'];
        }
        return $KitCd;
    }

    private function checkFoodType($name){
        $FID = 0;
        $data = $this->db2->select('FID')->like('Name1', $name)
                        ->get('FoodType')
                        ->row_array();
        if(!empty($data)){
            $FID = $data['FID'];
        }
        return $FID;
    }

    private function checkItemType($name, $EID){
        $id = 0;
        $data = $this->db2->select('ItmTyp')->like('Name1', $name)->get_where('ItemTypes', array('EID' => $EID))
                    ->row_array();
        if(!empty($data)){
            $id = $data['ItmTyp'];
        }
        return $id;   
    }

    private function checkType($name, $type){
        $id = 0;
        $data = $this->db2->select('TagId')->like('TDesc1', $name)->get_where('MenuTags', array('TagTyp' => $type))
                    ->row_array();
        if(!empty($data)){
            $id = $data['TagId'];
        }
        return $id;    
    }

    private function getItemId($EID, $itemName){
        $itemId = 0;
        $data = $this->db2->select('ItemId')->like('ItemNm1', $itemName)->get_where('MenuItem', array('EID' => $EID))
                    ->row_array();
        if(!empty($data)){
            $itemId = $data['ItemId'];
        }
        return $itemId;
    }

    private function checkSection($sectionName){
        $SecId = 0;
        $data = $this->db2->select('SecId')
                        ->like('Name1', $sectionName)
                        ->get('Eat_Sections')
                        ->row_array();
        if(!empty($data)){
            $SecId = $data['SecId'];
        }else{
            $sec['Name1'] = $sectionName;
            $sec['Stat'] = 0;
            $SecId = insertRecord('Eat_Sections', $sec);
        }
        return $SecId;
    }

    private function checkPortion($portionName){
        $IPCd = 0;
        $data = $this->db2->select('IPCd')->like('Name1', $portionName)->get('ItemPortions')
                    ->row_array();
        if(!empty($data)){
            $IPCd = $data['IPCd'];
        }
        return $IPCd;
    }

    private function checkCashier($EID, $cashierName){
        $CCd = 0;
        $data = $this->db2->select('CCd')->like('Name1', $cashierName)->get_where('Eat_Casher', array('EID' => $EID))
                    ->row_array();
        if(!empty($data)){
            $CCd = $data['CCd'];
        }
        return $CCd;
    }

    public function checkCNoForTable(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            extract($_POST);

            // $strMergeNo = "'".$MergeNo."'";
            $whr = "k.CNo = km.CNo";
            $data = $this->db2->select("sum(k.ItmRate * k.Qty) as OrdAmt, k.CNo, km.MergeNo, ")
                            ->group_by('k.CNo')
                            ->join('Kitchen k', 'k.MergeNo = km.MergeNo', 'inner')
                            ->where($whr)
                            ->get_where('KitchenMain km', array('km.MergeNo' => $MergeNo,
                                'km.EID' => authuser()->EID,
                                'k.EID' => authuser()->EID,
                                'km.BillStat' => 0,
                                'k.Stat' => 3))
                            ->result_array();

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }
    }

    function updateMCNoForTable(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            extract($_POST);

            // $strMergeNo = "'".$MergeNo."'";

            $EID = authuser()->EID;
            
            $this->db2->query("UPDATE Kitchen k, KitchenMain km set k.MCNo = $MCNo, km.MCNo = $MCNo where km.MergeNo = k.MergeNo and km.MergeNo = '$MergeNo' and km.EID = $EID and km.BillStat = 0 and k.BillStat = 0");
            $status = 'success';
            $response = $this->lang->line('MCNoUpdated');

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function dispense_notification(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            extract($_POST);

            $RestName = authuser()->RestName;
            $Dispense_OTP = $this->session->userdata('Dispense_OTP');

            if($oType != 101){
                $otpData['mobileNo'] = $mobile;
                $otpData['otp'] = '';
                $otpData['stat'] = 0;
                $otpData['pageRequest'] = 'Dispense';

                if($mobile){
                    $msg = "EAT-OUT: Order of Bill No: $billId from $RestName is ready. Please pick up from $dispCounter";

                    if($Dispense_OTP > 0){
                        $otp = generateOnlyOTP();
                        $msg = "EAT-OUT: Order of Bill No: $billId from $RestName is ready. Your OTP is $otp. Please pick up from $dispCounter";
                        $otpData['otp'] = $otp;
                    }

                    $smsRes = sendSMS($mobile, $msg);
                    print_r($smsRes);print_r($mobile);
                    if($smsRes){
                        $otpData['stat'] = 1;
                        $status = 'success';
                        $response = $this->lang->line('messageSent');
                    }
                    insertRecord('OTP', $otpData);
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

    public function delivery_notification(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            extract($_POST);

            $RestName = authuser()->RestName;
            $Dispense_OTP = $this->session->userdata('Dispense_OTP');

            if($oType != 101){

                updateRecord('kitchen', array('DStat' => 1), array('CNo' => $CNo, 'DCd' => $DCd));

                $otpData['mobileNo'] = $mobile;
                $otpData['otp'] = '';
                $otpData['stat'] = 0;
                $otpData['pageRequest'] = 'Dispense';

                if($mobile){
                    $msg = "Order of Bill No : $billId, Counter : $dispCounter from $RestName has been delivered.";

                    $smsRes = sendSMS($mobile, $msg);
                    if($smsRes){
                        $otpData['stat'] = 1;
                        $status = 'success';
                        $response = $this->lang->line('deliveredSuccessfully');
                    }
                    insertRecord('OTP', $otpData);
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

    public function verifyDelOTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $response = $this->lang->line('OTPNotVerified');
            
            $cust_otp = $this->session->userdata('cust_otp');
            if($cust_otp == $_POST['otp']){
                $status = "success";
                $response = $this->lang->line('OTPVerified');
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }      
    }

    public function get_cust_details(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $response = "Records Not Found!";
            $data = $this->db2->select("uId, FName, LName, DelAddress")->get_where('Users', array('MobileNo' => $_POST['phone']))->row_array();
            if(!empty($data)){
                $status = 'success';
                $response = $data;
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function updateDataItem(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
               
            $itemIdIn = implode(',', $_POST['itemId']);
            $itemIdIn = "(".$itemIdIn.")";
            
            $status = "success";

            switch ($_POST['type']) {
                case 'live':

                    $irnoIn = implode(',', $_POST['irno']);
                    $irnoIn = "(".$irnoIn.")";

                    $this->db2->query("UPDATE MenuItemRates mir, MenuItem mi set mir.OrigRate = mir.ItmRate where mi.EID = $EID and mir.ItemId = mi.ItemId and mir.IRNo IN $irnoIn ");
                $response = "Selected items are Live";
                    break;
                case 'enabled':
                    $this->db2->query("UPDATE MenuItem set Stat = 0 where EID = $EID and ItemId IN $itemIdIn ");
                    $response = "Items are Enabled";
                    break;
                case 'disabled':
                    $this->db2->query("UPDATE MenuItem set Stat = 1 where EID = $EID and ItemId IN $itemIdIn ");
                    $response = "Items are Disabled!";
                    break;
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function splitBill($MCNo, $MergeNo, $tableFilter){

        $data['MergeNo'] = $MergeNo;
        $data['payable'] = 0;
        $data['grossItemAmt'] = 0;
        $data['tip'] = 0;
        $data['MCNo'] = $MCNo;
        $data['tableFilter'] = $tableFilter;

        $EID = authuser()->EID;
        $EType = $this->session->userdata('EType');

        if($this->input->method(true)=='POST'){
               // echo "<pre>";
               // print_r($_POST);
               // die;

               $CNo = $_POST['MCNo'];
               for ($i=0; $i < sizeof($_POST['mobile']) ; $i++) {
                    $pData['paymentMode'] = 'RestSplitBill';
                    $pData['CellNo'] = $_POST['mobile'][$i];
                    $pData['CNo'] = $_POST['MCNo'];
                    $pData['itemTotalGross'] = $_POST['totItemAmt'][$i];
                    $pData['orderAmount'] = $_POST['amount'][$i];
                    $pData['per_cent'] = $_POST['percent'][$i] / 100;
                    $pData['splitType'] = $_POST['splitType'];
                    $pData['MergeNo'] = $_POST['MergeNo'];
                    $pData['cust_discount'] = 0;
                    $pData['TableNo'] = 0;
                    $pData['TipAmount'] = 0;
                    $this->session->set_userdata('CellNo', $pData['CellNo']);
                    $pData['CustId'] = createCustUser($pData['CellNo']);

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
                        $temp['created_by'] = authuser()->RUserId;
                        $temp['MCNo'] = $CNo;
                        $linkData[] = $temp;
                        $this->session->set_userdata('blink', $link);
                    // link send with bill no, sms or email => pending status
                        // for send to pay now to current customer
                    }
               }

               $kstat = ($EType == 5)?3:2;
               $billingObjStat = 5;
               $strMergeNo = "'".$MergeNo."'";
               $this->db2->query("UPDATE Kitchen SET BillStat = $billingObjStat  WHERE EID = $EID and (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 and Stat = $kstat ");

               $this->db2->query("UPDATE KitchenMain SET BillStat = $billingObjStat WHERE (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 AND EID = $EID ");
                    
               redirect(base_url('restaurant/sitting_table'));
        }
            
            $langId = $this->session->userdata('site_lang');
            $lname = "m.ItemNm$langId";
            $ipName = "ip.Name$langId  as Portions";

            $groupby = ' km.MCNo';
            if($tableFilter == 'tableWise'){
                $characterToFind = '~';
                $count = substr_count($MergeNo, $characterToFind);
                if ($count > 0) {
                    $groupby = ' km.MergeNo';
                }
            }

            // add merge no to string
            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate,k.ItmRate,  SUM(if (k.TA=1,((k.OrigRate)*k.Qty),(k.OrigRate*k.Qty))) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge) from Kitchen k1 where k1.MergeNo = km.MergeNo and k1.MergeNo = $MergeNo  and k1.EID=km.EID AND (k1.Stat = 3) and k1.BillStat = km.BillStat GROUP BY k1.EID) as TotPckCharge, $ipName, km.CNo,km.MergeNo, km.MCNo,sum(km.BillDiscAmt) as BillDiscAmt, sum(km.DelCharge) as DelCharge, sum(km.RtngDiscAmt) as totRtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType, k.TA, km.RtngDiscAmt,km.TableNo, km.CustId, c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND km.MergeNo = $MergeNo group by $groupby, k.TA,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.ItemNm1 Asc")->result_array();

            // remove string
            $MergeNo = str_replace("'","",$MergeNo);
            // echo "<pre>";
            // print_r($MergeNo);
            // print_r($kitcheData);
            // print_r($MergeNo);
            // print_r($this->db2->last_query());
            // die;
            
                $taxDataArray = array();
                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    $CNo = $kitcheData[0]['MCNo'];

                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo);
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
                    $data['grossItemAmt'] = $orderAmt;
                    
                    // $custDiscount = ($orderAmt * $_POST['custDiscPer']) / 100;
                    $custDiscount = 0;

                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    $charge = $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    // add 16-nov-23 end

                    // grand total
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount - $custDiscount;
                    
                    $data['payable'] = $total;
                }
        // echo "<pre>";
        // print_r($data);
        // die;
        
        $data['title'] = $this->lang->line('splitbill');
        $this->load->view('rest/split_bill', $data); 
    }

    public function eatary(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
// echo "<pre>";
// print_r($_POST);die;
            switch ($_POST['type']) {
                case 'search':
                        $EID = $_POST['EID'];
                        $response = getRecords('Eatary', array('EID' => $EID));
                    break;

                case 'update':
                        $updateData = $_POST;
                        updateRecord('Eatary', $updateData, array('EID' => $_POST['EID']));
                        $response = 'Updated Records';
                    break;
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('restaurant');
        $data['eatary'] = $this->rest->getRestaurantList();
        $this->load->view('rest/eatary_edit', $data);    
    }

    public function cuisine(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['cuisineName'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['CID'] > 0){
                updateRecord('Cuisines', $updateData, array('CID' => $_POST['CID']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['CID']);
                insertRecord('Cuisines', $updateData);
                $response = 'Insert Records'; 
            }                    

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('cuisine');
        $data['cuisines'] = $this->rest->getCuisineList();
        $this->load->view('rest/cuisine_edit', $data);    
    }

    public function eat_cuisine(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['cuisineName'];
            $updateData['EID'] = $_POST['EID'];
            $updateData['CID'] = $_POST['CID'];
            $updateData['KitCd'] = $_POST['KitCd'];
            $updateData['Rank'] = $_POST['Rank'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['ECID'] > 0){
                updateRecord('EatCuisine', $updateData, array('ECID' => $_POST['MCatgId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ECID']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('EatCuisine', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('cuisine');
        $data['eatCuisine'] = $this->rest->getEatCuisineList();
        $data['kitchens'] = $this->rest->get_kitchen();
        // echo "<pre>";print_r($data);die;
        $this->load->view('rest/eat_cuisine', $data);    
    }

    public function menu_category(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['menuName'];
            $updateData['EID'] = $_POST['EID'];
            $updateData['CID'] = $_POST['CID'];
            $updateData['KitCd'] = $_POST['KitCd'];
            $updateData['Rank'] = $_POST['Rank'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['MCatgId'] > 0){
                updateRecord('MenuCatg', $updateData, array('MCatgId' => $_POST['MCatgId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['MCatgId']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('MenuCatg', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('menuCategory');
        // $data['eatary'] = $this->rest->getRestaurantList();
        $data['cuisines'] = $this->rest->getCuisineList();
        $data['kitchens'] = $this->rest->get_kitchen();
        $data['menuCatList'] = $this->rest->getMenuCatList();
        $this->load->view('rest/menu_category_edit', $data);    
    }

    public function kitchen(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "KitName$langId";

            $updateData[$lname] = $_POST['kitchen'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['KitCd'] > 0){
                updateRecord('Eat_Kit', $updateData, array('KitCd' => $_POST['KitCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['KitCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('Eat_Kit', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('kitchen');
        $data['kitchens'] = $this->rest->get_kitchen();
        $this->load->view('rest/kitchen_edit', $data);    
    }

    public function cashier(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['cashier'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['CCd'] > 0){
                updateRecord('Eat_Casher', $updateData, array('CCd' => $_POST['CCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['CCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('Eat_Casher', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('cashier');
        $data['casherList'] = $this->rest->getCashier();

        $this->load->view('rest/cashier', $data);    
    }

    public function dispense_outlet(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['dispense'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['DCd'] > 0){
                updateRecord('Eat_DispOutlets', $updateData, array('DCd' => $_POST['DCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['DCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('Eat_DispOutlets', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('dispense').' '.$this->lang->line('outlet');
        $data['outlets'] = $this->rest->getDispenseOutletList();
        $this->load->view('rest/dispense_outlets', $data);    
    }

    public function table_list(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $updateData = $_POST;
            $updateData['MergeNo'] = $updateData['TableNo'];
            if($_POST['TId'] > 0){
                updateRecord('Eat_tables', $updateData, array('TId' => $_POST['TId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['TId']);
                $updateData['EID'] = authuser()->EID;
                $updateData['LoginCd'] = authuser()->RUserId;
                insertRecord('Eat_tables', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('table').' '.$this->lang->line('list');
        $data['tables'] = $this->rest->getAllTables();
        $data['sections'] = $this->rest->get_eat_section();
        $data['casherList'] = $this->rest->getCashier();
        
        $this->load->view('rest/table_list', $data);    
    }

    public function menu_list(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['menu'];

            $updateData['pageUrl'] = $_POST['pageUrl'];
            $updateData['Rank'] = $_POST['Rank'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['RoleId'] > 0){
                updateRecord('UserRoles', $updateData, array('RoleId' => $_POST['RoleId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['RoleId']);
                insertRecord('UserRoles', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('menu').' '.$this->lang->line('list');
        $data['menus'] = $this->rest->getAllMenuList();

        $this->load->view('rest/menuList', $data);
    }

    public function scheme_category(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['category'];
            $updateData['SchTyp'] = 2;
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['SchCatg'] > 0){
                updateRecord('CustOfferTypes', $updateData, array('SchCatg' => $_POST['SchCatg']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['SchCatg']);
                insertRecord('CustOfferTypes', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('schemeCategory');
        $data['sch_cat'] = $this->rest->getOffersSchemeCategory(0);

        $this->load->view('rest/schemeCategory', $data);
    }

    public function recommendation(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($updateData['RecNo'] > 0){
                updateRecord('MenuItem_Recos', $updateData, array('RecNo' => $_POST['RecNo']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['RecNo']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('MenuItem_Recos', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('recommendation');
        $data['itemList'] = $this->rest->getAllItemsList();
        $data['recList'] = $this->rest->getRecommendationList();
        // echo "<pre>";print_r($data);die;
        $this->load->view('rest/recommendation', $data);    
    }

    public function paymentMode(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($updateData['PMNo'] > 0){
                updateRecord('PymtModes', $updateData, array('PMNo' => $_POST['PMNo']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['PMNo']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('PymtModes', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('payment').' '.$this->lang->line('mode');
        $data['payList'] = $this->rest->getPaymentType();
        // echo "<pre>";print_r($data);die;
        $this->load->view('rest/pay_modes', $data);    
    }

    public function third_party_list(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['PId'] > 0){
                updateRecord('3POrders', $updateData, array('3PId' => $_POST['PId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['PId']);
                insertRecord('3POrders', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('thirdParty');
        $data['lists'] = $this->rest->getThirdOrderData();
        $this->load->view('rest/third_party', $data);    
    }

    public function config_payment(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['PymtMode'] > 0){
                updateRecord('ConfigPymt', $updateData, array('PymtMode' => $_POST['PymtMode']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['PymtMode']);
                insertRecord('ConfigPymt', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('payment');
        $data['lists'] = $this->rest->getConfigPayment();
        $this->load->view('rest/config_payment', $data);    
    }

    public function sections(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['SecId'] > 0){
                updateRecord('Eat_Sections', $updateData, array('SecId' => $_POST['SecId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['SecId']);
                insertRecord('Eat_Sections', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('section');
        $data['lists'] = $this->rest->getSectionList();
        $this->load->view('rest/section', $data);    
    }

    public function entertainment(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['EntId'] > 0){
                updateRecord('Entertainment', $updateData, array('EntId' => $_POST['EntId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['EntId']);
                insertRecord('Entertainment', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('entertainment');
        $data['lists'] = $this->rest->getEntertainment();
        $this->load->view('rest/entertainment', $data);    
    }

    public function suppliers(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "SuppName$langId";

            $updateData[$lname]         = $_POST['name'];
            $updateData['CreditDays']   = $_POST['CreditDays'];
            $updateData['Remarks']      = $_POST['Remarks'];
            $updateData['Stat']         = $_POST['Stat'];

            if($_POST['SuppCd'] > 0){
                updateRecord('RMSuppliers', $updateData, array('SuppCd' => $_POST['SuppCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['SuppCd']);
                insertRecord('RMSuppliers', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('supplier');
        $data['lists'] = $this->rest->getSuppliers();
        $this->load->view('rest/suppliers', $data);    
    }

    public function itemType(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['item'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['ItmTyp'] > 0){
                updateRecord('ItemTypes', $updateData, array('ItmTyp' => $_POST['ItmTyp']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ItmTyp']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('ItemTypes', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('itemType');
        $data['itemTyp'] = $this->rest->getItemTypeList();

        $this->load->view('rest/itemType', $data);    
    }

    public function item_type_group(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData = $_POST;
            $updateData[$lname]         = $updateData['name'];

            unset($updateData['name']);
            if($_POST['ItemGrpCd'] > 0){
                updateRecord('ItemTypesGroup', $updateData, array('ItemGrpCd' => $_POST['ItemGrpCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ItemGrpCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('ItemTypesGroup', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('item').' '.$this->lang->line('group');
        $data['itemTyp'] = $this->rest->getItemTypeList();
        $data['itemList'] = $this->rest->getAllItemsList();
        $data['lists'] = $this->rest->getItemTypesGroupList();
        $this->load->view('rest/itemtypegroup', $data);    
    }

    public function item_type_det(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($_POST['ItemOptCd'] > 0){
                updateRecord('ItemTypesDet', $updateData, array('ItemOptCd' => $_POST['ItemOptCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ItemOptCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('ItemTypesDet', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('item').' '.$this->lang->line('type');
        $data['itemList'] = $this->rest->getAllItemsList();
        $data['itemGroup'] = $this->rest->getItemTypesGroup();
        $data['lists'] = $this->rest->getItemTypesDet();
        $this->load->view('rest/itemtypedet', $data);    
    }

    public function item_files_upload(){
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        $data['notUpload'] = [];
        if($this->input->method(true)=='POST'){

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;
            $temp = [];
            $notUpload = [];
            
            // If files are selected to upload 
            if(!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0){ 
                $filesCount = count($_FILES['files']['name']); 
                $flag = 0;
                // $allowed = array('jpg');
                $files = $_FILES['files'];
                for($i = 0; $i < $filesCount; $i++){ 
                    $_FILES['files']['name']     = $files['name'][$i]; 
                    $_FILES['files']['type']     = $files['type'][$i]; 
                    $_FILES['files']['tmp_name'] = $files['tmp_name'][$i]; 
                    $_FILES['files']['error']     = $files['error'][$i]; 
                    $_FILES['files']['size']     = $files['size'][$i]; 
                    $file = $files['name'][$i];

                    // $filename_c = $_FILES['files']['name'][$i];
                    // $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                    // if (!in_array($ext, $allowed)) {
                    //     $flag = 1;
                    //     $this->session->set_flashdata('error','Support only JPG format!');
                    // }
                    
                    $folderPath = 'uploads/e'.$EID.'/'; 

                    if($_FILES['files']['size'] < 1048576){
                         $res = do_upload('files',$file,$folderPath,'*');
                         $status = 'success';
                         $response = 'File Uploaded';
                    }else{
                        $temp = $file;
                        $notUpload[] = $temp;
                    }                     
                } 
            }

            if(!empty($notUpload)){
                $status = 'pending';
                $response = $notUpload;
                $fd['files'] = implode(",",$notUpload);
                $fd['EID'] = $EID;
                $fd['created_at'] = date('Y-m-d H:i:s'); 
                insertRecord('fileNotUploaded', $fd);
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['title'] = $this->lang->line('item');

        $this->load->view('rest/item_files', $data);    
    }

    public function abc_report(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $reports = $this->rest->getABCRepots($_POST);
            $response = $reports;

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['cuisine'] = $this->rest->getCuisineList();
        $data['title'] = $this->lang->line('abc').' '.$this->lang->line('report');
        $this->load->view('report/abcReport', $data);    
    }

    public function get_itemList_by_mcatgId(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getAllItemsListByMenuCatgId($_POST['MCatgId']);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function tax_report(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getTaxRepots($_POST);
            
            $new_response = [];
            foreach ($response as $key ) {
                $new_response[$key['BillId']]['BillId'] = $key['BillId'];
                // $new_response[$key['BillId']]['TaxPcent'] = $key['TaxPcent'];
                $new_response[$key['BillId']]['Date'] = $key['Date'];

                if($key['TaxName'] == 'VAT'){
                    
                    $new_response[$key['BillId']]['VAT'] = $key['TaxAmt'];
                    $new_response[$key['BillId']]['VAT_Pcent'] = $key['TaxPcent'];
                }

                if($key['TaxName'] == 'SGST'){
                    $new_response[$key['BillId']]['SGST'] = $key['TaxAmt'];
                    $new_response[$key['BillId']]['SGST_Pcent'] = $key['TaxPcent'];
                }

                if($key['TaxName'] == 'CGST'){
                    $new_response[$key['BillId']]['CGST'] = $key['TaxAmt'];
                    $new_response[$key['BillId']]['CGST_Pcent'] = $key['TaxPcent'];
                }
            }

            $res = [];
            foreach ($new_response as $key) {
                $temp['BillId'] = $key['BillId'];
                // $temp['TaxPcent'] = $key['TaxPcent'];
                $temp['Date'] = $key['Date'];

                $temp['VAT'] = !empty($key['VAT'])?$key['VAT']:0;
                $temp['CGST'] = !empty($key['CGST'])?$key['CGST']:0;
                $temp['SGST'] = !empty($key['SGST'])?$key['SGST']:0;

                $temp['VAT_Pcent'] = !empty($key['VAT_Pcent'])?$key['VAT_Pcent']:0;
                $temp['CGST_Pcent'] = !empty($key['CGST_Pcent'])?$key['CGST_Pcent']:0;
                $temp['SGST_Pcent'] = !empty($key['SGST_Pcent'])?$key['SGST_Pcent']:0;

                $res[] = $temp;
            }
            $data['res'] = $res;
            $data['headers'] = $this->rest->getTaxHead();
            // echo "<pre>";
            // print_r($data);die;

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }

        $data['title'] = $this->lang->line('tax').' '.$this->lang->line('report');
        $this->load->view('report/taxReport', $data);    
    }

    public function income_report(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getIncomeRepots($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('income').' '.$this->lang->line('report');
        $this->load->view('report/incomeReport', $data);    
    }

    public function stock_statement(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getStockStatementRepots($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('stock').' '.$this->lang->line('statement');
        $data['stores'] = $this->rest->getKitchenList();
        $this->load->view('report/stockStatement', $data);    
    }

    public function discount_user(){
        
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            if($_POST['uId'] > 0){
                $userId = $_POST['uId'];
            }else{
                $userId = createCustUser($_POST['MobileNo']);
            }

            $upData['discId'] = $_POST['discId'];
            $upData['FName'] = $_POST['FName'];

            updateRecord('Users', $upData, array('EID' => $EID, 'uId' => $userId));

            $response = 'Discount Updated';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Discount User';
        $data['discounts'] = $this->rest->getUserDiscount();
        $this->load->view('rest/discount_user', $data);    
    }

    public function data_upload(){
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;
            $folderPath = 'uploads/e'.$EID.'/csv';
            $filesPath = glob($folderPath.'/*'); // get all file names
            // foreach($filesPath as $file){ // iterate files
            //   if(is_file($file)) {
            //     unlink($file); // delete file
            //   }
            // }  
            // end remove all files inside folder

            $flag = 0;

            switch ($_POST['type']) {
                case 'items':

                    if(isset($_FILES['items_file']['name']) && !empty($_FILES['items_file']['name'])){ 
                        $files = $_FILES['items_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['items_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['items_file']['name']= $files['name'];
                        $_FILES['items_file']['type']= $files['type'];
                        $_FILES['items_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['items_file']['error']= $files['error'];
                        $_FILES['items_file']['size']= $files['size'];
                        $file = $files['name'];

                        $itemData = [];
                        $temp = [];
                        $count = 1;
                        $checker = 0;

                        if($flag == 0){
                            $res = do_upload('items_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){

                                        $temp['RestName'] = $csv_data[0];
                                        $temp['Cuisine'] = $csv_data[1];
                                        $temp['FID'] = $csv_data[2];
                                        $temp['IMcCd'] = $csv_data[3];
                                        $temp['MenuCatgNm'] = $csv_data[4];
                                        $temp['CTypUsedFor'] = $csv_data[5];
                                        $temp['ItemNm'] = $csv_data[6];
                                        $temp['ItemTyp'] = $csv_data[7];
                                        $temp['NV'] = $csv_data[8];
                                        $temp['Section'] = $csv_data[9];
                                        $temp['PckCharge'] = $csv_data[10];
                                        $temp['Rate'] = $csv_data[11];
                                        $temp['Rank'] = $csv_data[12];
                                        $temp['ItmDesc'] = $csv_data[13];
                                        $temp['Ingeredients'] = $csv_data[14];
                                        $temp['MaxQty'] = $csv_data[15];
                                        $temp['Rmks'] = $csv_data[16];
                                        $temp['PrepTime'] = $csv_data[17];
                                        $temp['DayNo'] = $csv_data[18];
                                        $temp['FrmTime'] = $csv_data[19];
                                        $temp['ToTime'] = $csv_data[20];
                                        $temp['AltFrmTime'] = $csv_data[21];
                                        $temp['AltToTime'] = $csv_data[22];
                                        $temp['videoLink'] = $csv_data[23];
                                        $temp['itemPortion'] = $csv_data[24];
                                        $temp['LoginCd'] = authuser()->RUserId;

                                        $itemData[] = $temp;
                                    }
                                }
                                if(!empty($itemData)){
                                    $this->db2->insert_batch('tempMenuItem', $itemData);
                                    $mmId = 1;

                                    foreach ($itemData as $row) {
                                        $ECID = $this->checkEatCuisine($row['Cuisine']);
                                        $mc['Name1'] = $row['MenuCatgNm'];
                                        $mc['EID']   = $EID;
                                        $mc['ChainId'] = 0;
                                        $mc['CTyp'] = $row['CTypUsedFor'];
                                        $mc['CID'] = $this->getCIDFromEatCuisine($row['Cuisine']);
                                        $mc['TaxType'] = 0;
                                        $mc['KitCd'] = 5;
                                        $mc['Rank'] = 1;
                                        $mc['Stat'] = 0;
                                        $mc['LoginCd'] = $row['LoginCd'];

                                        $MCatgId = insertRecord('MenuCatg', $mc);
                                        if(!empty($MCatgId)){
                                            $mItem['UItmCd'] = 1;
                                            $mItem['IMcCd'] = $row['IMcCd'];
                                            $mItem['EID'] = $EID;
                                            $mItem['ChainId'] = 0;
                                            $mItem['MCatgId'] = $MCatgId;
                                            $mItem['CTyp'] = $mc['CTyp'];
                                            $mItem['CID'] = $mc['CID'];
                                            $mItem['FID'] = $this->checkFoodType($row['FID']);
                                            $mItem['ItemNm1'] = $row['ItemNm'];
                                            $mItem['NV'] = $row['NV'];
                                            $mItem['PckCharge'] = $row['PckCharge'];
                                            $mItem['KitCd'] = 5;
                                            $mItem['Rank'] = 1;
                                            $mItem['MaxQty'] = $row['MaxQty'];
                                            $mItem['ItmDesc1'] = $row['ItmDesc'];
                                            $mItem['Ingeredients1'] = $row['Ingeredients'];
                                            $mItem['Rmks1'] = $row['Rmks'];
                                            $mItem['PrepTime'] = $row['PrepTime'];
                                            $mItem['DayNo'] = getDayNo($row['DayNo']); 
                                            $mItem['FrmTime'] = $row['FrmTime'];
                                            $mItem['ToTime'] = $row['ToTime'];
                                            $mItem['AltFrmTime'] = $row['AltFrmTime'];
                                            $mItem['AltToTime'] = $row['AltToTime'];
                                            $mItem['AltToTime'] = $row['videoLink'];
                                            $mItem['LoginCd'] = $row['LoginCd'];

                                            $ItemId = insertRecord('MenuItem', $mItem);

                                            if(!empty($ItemId)){
                                                $mRates['EID']      = $EID;
                                                $mRates['ChainId'] = 0;
                                                $mRates['ItemId']   = $ItemId;
                                                $mRates['SecId'] = $this->checkSection($row['Section']);
                                                $mRates['Itm_Portion'] = $this->checkPortion($row['itemPortion']);
                                                $mRates['ItmRate'] = $row['Rate'];
                                                insertRecord('MenuItemRates', $mRates);
                                            }
                                        }
                                    }
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                                fclose($open);
                            }
                        }
                      }

                break;
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die; 
        }
        $data['title'] = 'Items Upload';
        $this->load->view('rest/item_upload', $data);    
    }

    public function db_create_old(){
        $destDB = "2e";
        $sourceDatabase = "51e";
        $destinationDatabase = $destDB;
        
        // Create connection
        $conn = new mysqli('139.59.28.122', 'developer', 'pqowie321*');
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to create a new database if it doesn't exist
        $sqlCreateDestinationDB = "CREATE DATABASE IF NOT EXISTS $destinationDatabase";
        if ($conn->query($sqlCreateDestinationDB) === TRUE) {
            echo "Destination database created successfully<br>";
        } else {
            echo "Error creating destination database: " . $conn->error . "<br>";
        }

        // Select source and destination databases
        $conn->select_db($sourceDatabase);

        // Get a list of tables in the source database
        $tables = $conn->query("SHOW TABLES");
        if ($tables) {
            while ($row = $tables->fetch_row()) {
                $table = $row[0];

                // Select the source table
                $conn->select_db($sourceDatabase);
                $result = $conn->query("SELECT * FROM $table");

                if ($result) {
                    // Select the destination database
                    $conn->select_db($destinationDatabase);

                    // Create the table in the destination database if it doesn't exist
                    $conn->query("CREATE TABLE IF NOT EXISTS $table LIKE $sourceDatabase.$table");

                    // Copy data from source table to destination table
                    $conn->query("INSERT INTO $destinationDatabase.$table SELECT * FROM $sourceDatabase.$table");

                    // echo "Table $table copied successfully<br>";
                } else {
                    echo "Error selecting data from table $table: " . $conn->error . "<br>";
                }
            }
        } else {
            echo "Error getting list of tables: " . $conn->error . "<br>";
        }

        // Close connection
        $conn->close();
    }

    public function duplicateDatabase() {
        // Connect to the source database

        $sourceDB = $this->load->database('51e', TRUE);

        // Create the destination database
        $destinationDBName = '2e';
        // $this->db2->query("CREATE DATABASE IF NOT EXISTS $destinationDBName");

        // Connect to the destination database
        $destinationDB = $this->load->database('2e', TRUE);

        // Get list of tables in the source database
        $tables = $sourceDB->list_tables();

        // Copy tables to the destination database
            
        foreach ($tables as $table) {
            $dd = '51e'.'.'.$table;
            $tableStructure = $sourceDB->query("SHOW CREATE TABLE $table")->row_array();
            // echo "<pre>";
            // print_r($tableStructure);
            // die;
            $destinationDB->query($tableStructure['Create Table']);
            $destinationDB->query("INSERT INTO $table SELECT * FROM $dd");
        }

        echo "Database duplicated successfully";
    }





}
