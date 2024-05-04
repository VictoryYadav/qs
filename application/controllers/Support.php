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
                                    $genDB = $this->load->database('GenTableData', TRUE);
                                    $genDB->insert_batch('AI_Items', $itemData);
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
            $folderPath = 'uploads/e'.$CNo;
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, true);
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
        $data['rests'] = $this->genDB->select('CNo, EID, Name, CellNo, Email, CatgId')->get('EIDDet')->result_array();
        $data['country'] = $this->genDB->get_where('countries', array('Stat' => 0))->result_array();
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
                    $genDB->insert('UsersRest', $user);
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
                    $db3->query("UPDATE Eat_tables SET EID = $EID");
                    $db3->query("UPDATE ConfigTheme SET EID = $EID");
                    

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
                    $eatry['dbEID'] = $EID;
                    $db3->update('Eatary', $eatry, array('EID' => 1));
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
  

}
