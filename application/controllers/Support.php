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
  

}
