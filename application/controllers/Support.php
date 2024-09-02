<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {

    private $genDB;

	public function __construct()
	{
		parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->authuser = $this->session->userdata('logged_in');
        } else {
            redirect(base_url());
        }

        $this->genDB = $this->load->database('GenTableData', TRUE);
        $this->load->model('Supp', 'supp');
	}

    public function index(){

        $data['title'] = $this->lang->line('dashboard');
        $this->load->view('support/index', $data);
    }

    public function new_user()
    {
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $this->session->set_userdata('signup', $_POST);

            $supp = $_POST;
            $supp['pwdHash'] = md5($supp['pwd']);

            $userId = $this->genDB->insert('usersSupport', $supp);

            $session_data = array(
                        'userId' => $userId,
                        'fullname' => $supp['fullname'],
                        'mobileNo' => $supp['mobileNo'],
                        'email' => $supp['email'],
                        'countryCd' => $supp['countryCd'],
                        'userType' => $supp['userType']
                    );
                $this->session->set_userdata('logged_in', $session_data);

            $status = 'success';
            $response = 'New User Created';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['title'] = 'New User';
        $data['country'] = $this->genDB->get_where('countries', array('Stat' => 0))->result_array();
        $this->load->view('support/add_user', $data);
    }

    public function uitemcd_list(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($_POST['UItmCd'] > 0){
                $this->genDB->update('AI_Items', $updateData, array('UItmCd' => $_POST['UItmCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['UItmCd']);
                $this->genDB->insert('AI_Items', $updateData);
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
        $data['title'] = 'UItem Code';
        $data['cuisine'] = $this->supp->getCuisineList();
        $data['lists'] = $this->supp->getUitemCodeList();
        $this->load->view('support/uitemcode_list', $data);
    }

    public function uitemcd(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            switch ($_POST['type']) {
                case 'get':
                    $response = $this->supp->getMenuListByEID($_POST['EID']); 
                    $status = "success";
                    break;

                case 'update':
                    $response = $this->supp->updateUItemCode($_POST);   
                    $status = "success";
                    break;
                case 'menuItem':
                    $response = $this->supp->getUitemCodeList();   
                    $status = "success";
                    break;
                
                default:
                    # code...
                    break;
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'UItem Code';
        $data['itemLists'] = $this->supp->getUitemCodeList();
        $data['restaurant'] = $this->supp->getRestaurantList();
        $this->load->view('support/uitemcode', $data);
    }

    public function csv_file_upload(){
        $EID = authuser()->EID;

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;

            $folderPath = 'uploads/e'.$EID.'/csv';
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, true);
            }
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

                case 'uitemcd':
                    if(isset($_FILES['uitemcd_file']['name']) && !empty($_FILES['uitemcd_file']['name'])){ 
                        $files = $_FILES['uitemcd_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['uitemcd_file']['name'];
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
                        $_FILES['uitemcd_file']['name']= $files['name'];
                        $_FILES['uitemcd_file']['type']= $files['type'];
                        $_FILES['uitemcd_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['uitemcd_file']['error']= $files['error'];
                        $_FILES['uitemcd_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('uitemcd_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $itemData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    // echo "<pre>";
                                    // print_r($csv_data);
                                    // die;
                                    if($csv_data[0] !='RestName'){
                                    
                                        $checker = 1;

                                        $temp['ItemName'] = $csv_data[0];

                                        if($temp['ItemName'] == ''){
                                          $response = $csv_data[0]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['CID'] = $this->checkCuisine($csv_data[1]);

                                        if($temp['CID'] == 0){
                                          $response = $csv_data[1]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['CTyp'] = $csv_data[2];
                                        $temp['FID'] = $csv_data[3];

                                        if($checker == 0){
                                            $itemData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $itemData[] = $temp;
                                    }
                                    $count++;    
                                }

                                if(!empty($itemData)){
                                    
                                    $this->genDB->insert_batch('AI_Items', $itemData);
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
        $this->load->view('support/csv_file', $data); 
    }

    private function checkCuisine($name){
        
        $cid = 0;
        $cuisine = $this->genDB->select('CID')->like('Name', $name)->get('Cuisines')->row_array();
        if(!empty($cuisine)){
            $cid = $cuisine['CID'];
        }
        return $cid;
    }

    public function new_customer_create(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){

            // $status = 'success';
            // echo "<pre>";
            // print_r($_POST);
            // die;

            $pData = $_POST;
            $pData['StTime'] = date('H:i:s', strtotime($pData['StTime'])); 
            $pData['EndTime'] = date('H:i:s', strtotime($pData['EndTime']));
            $pData['Stat'] = 1;
            unset($pData['DOB']);
            $this->genDB->insert('EIDDet',$pData);
            $CNo = $this->genDB->insert_id();

            // eid = cno
            // $folderPath = 'uploads/e'.$CNo;
            $root = $_SERVER["DOCUMENT_ROOT"];
            // $folderPath = $root . "/uploads/e$CNo";
            $folderPath = "/var/www/html/eat_out_app/uploads/e$CNo";
            
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, TRUE);
                chmod($folderPath, 0777);

                if (file_exists($folderPath)) {
                    $offer = $folderPath."/offers";
                    $qrcode = $folderPath."/qrcode";
                    $csv = $folderPath."/csv";

                    mkdir($offer, 0777, TRUE);
                    chmod($offer, 0777);

                    mkdir($qrcode, 0777, TRUE);
                    chmod($qrcode, 0777);

                    mkdir($csv, 0777, TRUE);
                    chmod($csv, 0777);
                }
            }

            $response = "Restaurant Created.";
            if($CNo){
                $dbName = $CNo.'e';
                // append db in database.php 
                    $database_file = APPPATH . 'config/database.php';
                    $s = "$";
                    $db1 = "db['".$dbName."']";
                    $text_to_append = "$s"."$db1"." = array('dsn'   => '','hostname' => '139.59.28.122','username' => 'developer','password' => 'pqowie321*','database' => '$dbName','dbdriver' => 'mysqli','dbprefix' => '','pconnect' => FALSE,'db_debug' => (ENVIRONMENT !== 'production'),'cache_on' => FALSE,'cachedir' => '','char_set' => 'utf8','dbcollat' => 'utf8_general_ci','swap_pre' => '','encrypt' => FALSE,'compress' => FALSE,'stricton' => FALSE,'failover' => array(),'save_queries' => TRUE);\n";
                        file_put_contents($database_file, $text_to_append.PHP_EOL , FILE_APPEND |   LOCK_EX);
                    // end of append db

                $upData['EID'] = $CNo;
                $upData['DBName'] = $dbName;
                $upData['DBPasswd'] = 'pass';

                $this->genDB->update('EIDDet',$upData, array('CNo' => $CNo));
                // db creation
                $destDB = $dbName;
                $sourceDatabase = "eatout";
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
                    $response = "$dbName Database Created.";
                    // echo "Destination database created successfully<br>";
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

                // create user in usersrest
                
                
                $status = 'success';
                $response = array('EID' =>$CNo, 'CatgId' => $_POST['CatgId']);
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die; 
        }
        $data['title'] = 'New Customer';
        $data['rests'] = $this->genDB->select('CNo, EID, Name, CellNo, Email, CatgId')
        ->order_by('CNo', 'DESC')->get('EIDDet')->result_array();
        $data['country'] = $this->genDB->get_where('countries', array('Stat' => 0))->result_array();

        $data['ECategory'] = $this->genDB->get('ECategory')->result_array();
        $data['Category'] = $this->genDB->get('Category')->result_array();
        
        $this->load->view('support/new_customer', $data); 
    }

    public function update_customer(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $EID = $_POST['EID'];

            if(!empty($EID)){
                    $EIDData = $this->genDB->get_where('EIDDet', array('EID' => $EID))->row_array();

                    $user['FName'] = $EIDData['ContactPerson'];
                    $user['MobileNo'] = $EIDData['CellNo'];
                    $user['PEmail'] = $EIDData['Email'];
                    $user['UTyp'] = 9;
                    $user['RestRole'] = 10;
                    $user['Passwd'] = 'eo1234';
                    // $user['DOB'] = date('Y-m-d', strtotime($EIDData['DOB']));
                    $user['Stat'] = 0;
                    $user['EID'] = $EID;
                    $this->genDB->insert('UsersRest', $user);
                    $GenId = $this->genDB->insert_id();
                    // end of genDB
                    $dbName = $EID.'e';
                    $db3 = $this->load->database($dbName, TRUE);

                    $db3->query("UPDATE UsersRoleDaily SET EID = $EID");
                    $db3->query("UPDATE Eat_Casher SET EID = $EID");
                    $db3->query("UPDATE Eat_Kit SET EID = $EID");
                    $db3->query("UPDATE Eat_DispOutlets SET EID = $EID");
                    $db3->query("UPDATE UsersRest SET EID = $EID, DeputedEID = $EID");
                    $db3->query("UPDATE Config SET EID = $EID");
                    $db3->query("UPDATE ConfigPymt SET EID = $EID");
                    $db3->query("UPDATE Eat_tables SET EID = $EID");
                    $db3->query("UPDATE ConfigTheme SET EID = $EID");
                    $db3->query("UPDATE Eat_Lang SET EID = $EID");
                    $db3->query("UPDATE Masts SET EID = $EID");
                    
                    $user['PWDHash'] = md5($user['Passwd']);
                    $user['Gender'] = 1;
                    $user['DeputedEID'] = $EID;
                    $user['LoginCd'] = 2;
                    $user['GenRUserId'] = $GenId;
                    $db3->insert('UsersRest', $user);
                    $userId = $db3->insert_id();
                    // update eatary table
                    $eatry['Name'] = $EIDData['Name'];
                    $eatry['ECatg'] = $EIDData['ECatg'];
                    $eatry['EType'] = $EIDData['EType'];
                    $eatry['CatgID'] = $EIDData['CatgId'];
                    $eatry['ContactNos'] = $EIDData['ContactPerson'];
                    $eatry['Email'] = $EIDData['Email'];
                    $eatry['PhoneNos'] = $EIDData['CellNo'];
                    $eatry['GSTNo'] = $EIDData['GSTNo'];
                    $eatry['ChainId'] = $EIDData['ChainId'];
                    $eatry['Addr'] = $EIDData['Area'];
                    $eatry['Suburb'] = $EIDData['Suburb'];
                    $eatry['City'] = $EIDData['City'];
                    $eatry['Pincode'] = $EIDData['PIN'];
                    $eatry['ContactAddr'] = $EIDData['HOAddress'];
                    $eatry['lat'] = $EIDData['Lat'];
                    $eatry['lng'] = $EIDData['Lng'];
                    $eatry['CountryCd'] = $EIDData['CountryCd'];
                    $eatry['LoginCd'] = $userId;
                    $eatry['EID'] = $EID;
                    $eatry['aggEID'] = $EID;
                    $db3->update('Eatary', $eatry, array('EID' => 1));

                    $configDT['StTime']     = date('H:i:s', strtotime($EIDData['StTime']));
                    $configDT['CloseTime']  = date('H:i:s', strtotime($EIDData['EndTime']));
                    $db3->update('Config', $configDT, array('EID' => $EID));
                    // end of update eatary table

                    $roles = $db3->get_where('UserRoles', array('Stat' => 0))->result_array();
                    $temp = [];
                    $userRolesAccessObj = [];
                    foreach ($roles as $role) {
                        $temp['EID'] = $EID;
                        $temp['RUserId'] = $userId;
                        $temp['RoleId'] = $role['RoleId'];
                        $temp['Stat'] = 0;    
                        $temp['LoginCd'] =$userId;
                        $userRolesAccessObj[] = $temp;
                    }

                    if(!empty($userRolesAccessObj)){
                        $db3->insert_batch('UserRolesAccess', $userRolesAccessObj); 
                    }

                    // send sms for new restaurant login url

                    $this->session->set_flashdata('success','Please login through the link and upload your restaurant data.');
                    if($userId){
                        $status = 'success';
                        $response = 'Restaurant Basic Setup is Completed.';
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

    public function loyality(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $loyal['Name'] = $_POST['Name'];
            $loyal['MinPaidValue'] = $_POST['MinPaidValue'];
            $loyal['MaxPointsUsage'] = $_POST['MaxPointsUsage'];
            $loyal['billUsagePerc'] = $_POST['billUsagePerc'];
            $loyal['AcrossOutlets'] = $_POST['AcrossOutlets'];
            $loyal['EatOutLoyalty'] = $_POST['EatOutLoyalty'];
            $loyal['Validity'] = $_POST['Validity'];
            $loyal['Stat'] = 0;
            $LNo = insertRecord('LoyaltyConfig', $loyal);
            if(!empty($LNo)){
                for ($i=0; $i < sizeof($_POST['PointsValue']); $i++) { 
                    $loyalDet['PointsValue'] = $_POST['PointsValue'][$i];
                    $loyalDet['PymtMode'] = $_POST['PymtMode'][$i];
                    $loyalDet['Stat'] = 0;
                    $loyalDet['LNo'] = $LNo;
                     insertRecord('LoyaltyConfigDet', $loyalDet);
                }
                $status = 'success';
                $response = 'Loyality Added.';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die; 
        }
        $data['title'] = 'Loyality';
        $data['modes'] = $this->supp->getPaymentModes();
        $data['lists'] = $this->supp->getLoyalities();

        $this->load->view('support/loyality', $data); 
    }

    public function get_rest_list(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->supp->getRestList();

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }
    }

    public function updateLoyalityStats(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $stat = ($_POST['Stat'] == 1)?0:1;
            updateRecord('LoyaltyConfig', array('Stat' => $stat), array('LNo' => $_POST['LNo']));
            
            $status = "success";
            $response = 'Status Updated.';

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }
    }


    public function logout(){
        // $this->session->unset_userdata('logged_in');
        
        $this->session->sess_destroy();
        redirect(base_url('supportlogin'));
    }

    public function rest_login($EID, $CatgId)
    {
        $this->session->sess_destroy();
        $url = base_url('login?o='.$EID.'&c='.$CatgId);
        redirect($url);
    }

    public function users(){

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            $status = 'success';
            $stat = 0;
            $response = 'User Activated.';

            if($_POST['stat'] == 0){
                $stat = 1;
                $response = 'User Deactivated.';

                $dt['suppUserId'] = 0;
                $dt['suppUserIdAlt'] = 0;

                $restList = $this->getRestLists($_POST['userId']);
                if(!empty($restList)){
                    foreach ($restList as $key) {

                        $this->genDB->update('EIDDet', $dt, array('suppUserId' => $_POST['userId'], 'EID' => $key['EID']));
                        $this->genDB->update('EIDDet', $dt, array('suppUserIdAlt' => $_POST['userId'], 'EID' => $key['EID']));

                        $dbName = $key['EID'].'e';
                        $db3 = $this->load->database($dbName, TRUE);
                        $db3->update('UsersRest', array('Stat' => 3), array('MobileNo' => $_POST['mobileNo']));
                    }
                }
            }
            
            $this->genDB->update('usersSupport', array('stat' => $stat), array('userId' => $_POST['userId']));
            

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = 'Support Users';
        $data['users'] = $this->genDB->get('usersSupport')->result_array();
        $this->load->view('support/users', $data); 
    }

    public function user_access(){

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            if ($_POST['type'] == 'available') {
                $userId =  $_POST['userId'];
                $city = $_POST['city'];
                $country = $_POST['countryCd'];

                $status = 'success';
                $response = $this->genDB->select("ed.EID, ed.Name")
                                ->order_by('Name', 'ASC')
                                ->get_where("EIDDet ed", array('suppUserId' => 0, 'Stat' => 0, 'city_id' => $city, 'CountryCd' => $country))
                                ->result_array();
            }

            if ($_POST['type'] == 'assigned') {
                $userId =  $_POST['userId'];
                $city = $_POST['city'];
                $country = $_POST['countryCd'];

                $status = 'success';
                $response = $this->genDB->select("ed.EID, ed.Name")
                                ->order_by('Name', 'ASC')
                                ->get_where("EIDDet ed", array('ed.suppUserId' => $userId, 'city_id' => $city, 'CountryCd' => $country))
                                ->result_array();
            }

            if ($_POST['type'] == 'setRest') {
                $userId =  $_POST['userId'];
                
                foreach ($_POST['EID'] as $key) {
                $this->genDB->update('EIDDet', array('suppUserId' => $userId), array('EID' => $key));
                    
                    $suppUser = $this->getSuppUserDetail($userId);
                    $dbName = $key.'e';
                    $db3 = $this->load->database($dbName, TRUE);
                    $check = $db3->select('MobileNo')->get_where('UsersRest', array('EID' => $key, 'MobileNo'  => $suppUser['mobileNo']))->row_array();
                    if(empty($check)){
                        $restUser = array(
                                        'FName'     => $suppUser['fullname'],
                                        'MobileNo'  => $suppUser['mobileNo'],
                                        'Passwd'    => $suppUser['pwd'],
                                        'PWDHash'   => md5($suppUser['pwd']),
                                        'Gender'    => $suppUser['gender'],
                                        'DOB'       => $suppUser['DOB'],
                                        'PEmail'    => $suppUser['email'],
                                        'EID'       => $key,
                                        'UTyp'      => 10,
                                        'RestRole'  => 10
                                    );
                        $db3->insert('UsersRest', $restUser);
                    }
                }

                $status = 'success';
                $response = 'Restaurant Assigned.';
            }
            
            if ($_POST['type'] == 'removeRest') {
                $userId =  $_POST['userId'];
                
                foreach ($_POST['EID'] as $key) {
                $this->genDB->update('EIDDet', array('suppUserId' => 0), array('EID' => $key, 'suppUserId' => $userId));
                    
                }

                $status = 'success';
                $response = 'Restaurant Assigned.';
            }
            

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = 'Support User Access';
        $data['users'] = $this->genDB->get('usersSupport')->result_array();
        $data['country'] = $this->genDB->get_where('countries', array('Stat' => 0))->result_array(); 
        $this->load->view('support/supp_user_access', $data); 
    }

    public function get_city_by_country(){

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            
            $response = $this->genDB->get_where('city', array('phone_code' => $_POST['phone_code']) )->result_array();
            $status = 'success';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function access_assign(){

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            if ($_POST['type'] == 'main') {
                $userId =  $_POST['userId'];

                $status = 'success';
                $response = $this->genDB->select("EID, Name")
                                ->order_by('Name', 'ASC')
                                ->get_where("EIDDet", array('suppUserId' => $userId))
                                ->result_array();
            }

            if ($_POST['type'] == 'temp') {
                $userId =  $_POST['userId'];

                $status = 'success';
                $response = $this->genDB->select("EID, Name")
                                ->order_by('Name', 'ASC')
                                ->get_where("EIDDet", array('suppUserId' => $userId))
                                ->result_array();
            }

            if ($_POST['type'] == 'setRest') {
                $userId         = $_POST['userId'];
                $tempuserId     = $_POST['tempuserId'];
                $userType       = $_POST['userType'];

                if($userType == 1){
                    // main
                    $updateData['suppUserId'] = $tempuserId;
                }else if($userType == 2){
                    // alternate
                    $updateData['suppUserIdAlt'] = $tempuserId;
                }

                foreach ($_POST['EID'] as $key) {
                    $this->genDB->update('EIDDet', $updateData, array('EID' => $key, 'suppUserId' => $userId));

                    $suppUser = $this->getSuppUserDetail($tempuserId);
                    $dbName = $key.'e';
                    $db3 = $this->load->database($dbName, TRUE);
                    $check = $db3->select('MobileNo')->get_where('UsersRest', array('EID' => $key, 'MobileNo'  => $suppUser['mobileNo']))->row_array();
                    if(empty($check)){
                        $restUser = array(
                                        'FName'     => $suppUser['fullname'],
                                        'MobileNo'  => $suppUser['mobileNo'],
                                        'Passwd'    => $suppUser['pwd'],
                                        'PWDHash'   => md5($suppUser['pwd']),
                                        'Gender'    => $suppUser['gender'],
                                        'DOB'       => $suppUser['DOB'],
                                        'PEmail'    => $suppUser['email'],
                                        'EID'       => $key,
                                        'UTyp'      => 10,
                                        'RestRole'  => 10
                                    );
                        $db3->insert('UsersRest', $restUser);
                    }
                }

                $status = 'success';
                $response = 'Restaurant Assigned.';
            }
            
            if ($_POST['type'] == 'removeRest') {
                $userId         =  $_POST['userId'];
                $tempuserId     = $_POST['tempuserId'];
                $userType       = $_POST['userType'];

                if($userType == 1){
                    // main
                    $updateData['suppUserId'] = $userId;
                }else if($userType == 2){
                    // alternate
                    $updateData['suppUserIdAlt'] = 0;
                }
                
                foreach ($_POST['EID'] as $key) {
                    $this->genDB->update('EIDDet', array('suppUserId' => 0), array('EID' => $key));
                }

                $status = 'success';
                $response = 'Restaurant Assigned.';
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = 'Access Assigend';
        $data['users'] = $this->genDB->get('usersSupport')->result_array();
        $data['alternateUsers'] = $this->genDB->get_where('usersSupport', array('stat' => 0))->result_array();
        $this->load->view('support/user_access_assign', $data); 
    }

    public function rest_list(){
        $userId = authuser()->userId;
        $data['title'] = 'Assign Rest List';
        $data['rests'] = $this->getRestLists($userId);
        
        $this->load->view('support/assgin_rest_list', $data);    
    }

    private function getRestLists($userId){
        return $this->genDB->select("ed.EID, ed.Name, ed.CatgId, ed.suppUserId, ed.suppUserIdAlt, c.country_name, ct.city_name")
                            ->join('countries c', 'c.phone_code = ed.CountryCd', 'left')
                            ->join('city ct', 'ct.city_id = ed.city_id', 'left')
                                ->group_start() 
                                    ->where('ed.suppUserId', $userId)
                                    ->or_where('ed.suppUserIdAlt',$userId)
                                ->group_end()
                            ->get('EIDDet ed')
                            ->result_array();
    }

    private function getSuppUserDetail($userId){
        return $this->genDB->get_where('usersSupport', array('userId' => $userId))
                            ->row_array();
    }

    public function test(){
        echo "<pre>";
        print_r($_SESSION);
        die;
    }
  

}
