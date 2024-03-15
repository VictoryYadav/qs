<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {

    private $db2;

	public function __construct()
	{
		parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->authuser = $this->session->userdata('logged_in');
        } else {
            redirect(base_url());
        }
        $this->load->model('Supp', 'supp');
	}

    public function uitemcd_list(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $genDB = $this->load->database('GenTableData', TRUE);
            $updateData = $_POST;

            if($_POST['UItmCd'] > 0){
                $genDB->update('AI_Items', $updateData, array('UItmCd' => $_POST['UItmCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['UItmCd']);
                $genDB->insert('AI_Items', $updateData);
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

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;

            $folderPath = 'uploads/csv';
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath);
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
        $genDB = $this->load->database('GenTableData', TRUE);
        $cid = 0;
        $cuisine = $genDB->select('CID')->like('Name', $name)->get('Cuisines')->row_array();
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
            $genDB = $this->load->database('GenTableData', TRUE);
            $genDB->insert('EIDDet',$pData);
            $CNo = $genDB->insert_id();

            $response = "Restaurant Created.";
            if($CNo){
                $dbName = $CNo.'e';
                $upData['EID'] = $CNo;
                $upData['DBName'] = $dbName;
                $upData['DBPasswd'] = 'pass';

                $genDB->update('EIDDet',$upData, array('CNo' => $CNo));
                // db creation
                $destDB = $dbName;
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
                
                if(!empty($dbName)){

                    $db3 = $this->load->database($dbName, TRUE);
                    $EID = $CNo;

                    $db3->query("UPDATE UsersRoleDaily SET EID = $EID");
                    $db3->query("UPDATE Eat_Casher SET EID = $EID");
                    $db3->query("UPDATE Eat_Kit SET EID = $EID");
                    $db3->query("UPDATE Eat_DispOutlets SET EID = $EID");
                    $db3->query("UPDATE UsersRest SET EID = $EID, DeputedEID = $EID");
                    $db3->query("UPDATE Config SET EID = $EID");

                    $user['FName'] = $_POST['ContactPerson'];
                    $user['MobileNo'] = $_POST['CellNo'];
                    $user['PEmail'] = $_POST['Email'];
                    $user['UTyp'] = 9;
                    $user['Passwd'] = 'eo1234';
                    $user['DOB'] = date('Y-m-d', strtotime($_POST['DOB']));
                    $user['Stat'] = 0;
                    $user['EID'] = $EID;

                    $genDB->insert('UsersRest', $user);

                    $user['PWDHash'] = md5($user['Passwd']);
                    $db3->insert('UsersRest', $user);
                    $userId = $db3->insert_id();

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

                    $this->session->set_flashdata('success','Please login through the link and upload your restaurant data.');
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die; 
        }
        $data['title'] = 'New Customer';
        $this->load->view('support/new_customer', $data); 
    }
  

}
