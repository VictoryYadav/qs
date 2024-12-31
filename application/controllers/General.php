<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller {

    private $status;
    private $response;
    private $message;
	public function __construct()
	{
		parent::__construct();

        $this->lang->load('message','english');

        $this->load->model('Generals', 'gen');
        $this->output->delete_cache();

        $this->response = [];

	}

	public function index(){
    
        $data['title'] = 'Home';
        $this->load->view('general/index', $data);
    }

    public function login(){

        $this->status = 'error';
        $this->message = 'Something went wrong please try again!!';
        if($this->input->method(true)=='POST'){
            
            $CountryCd  = $_POST['CountryCd'];
            $MobileNo   = $_POST['MobileNo'];

            if(!empty($MobileNo)){
                $MobileNo = $CountryCd.$MobileNo;
                $this->session->set_userdata('GenMobile', $MobileNo);
                $genData = $this->gen->getUserFromGenDb($MobileNo);
                if(!empty($genData)){
                    $otp = $this->gen->generate_otp($MobileNo, 'gen_login');
                    $this->status = 'success';
                    $this->message = $this->lang->line('yourOTPIs').' '.$otp;
                }else{
                    $this->message = $this->lang->line('usernameNotFound');
                }
            }

            $this->getResponse($this->status, $this->response, $this->message); 
        }

        $data['title'] = 'Login';
        $data['country'] = $this->gen->getCountryList();
        $this->load->view('general/login', $data);
    }

    public function loginVerify(){

        $this->status = 'error';
        $this->message = 'Something went wrong please try again!!';
        if($this->input->method(true)=='POST'){

            $otp = $this->session->userdata('cust_otp');
            if($_POST['otp'] == $otp){
                $resp['msg']    = $this->lang->line('OTPMatched');
                $this->status   = 'success';
                $MobileNo       = $this->session->userdata('GenMobile');

                $genData        = $this->gen->getUserFromGenDb($MobileNo);
                if(!empty($genData)){
                    $this->session->set_userdata('GenCustId', $genData['CustId']);
                    $this->session->set_userdata('GenMobile', $genData['MobileNo']);
                    $this->session->set_userdata('GenFullname', $genData['FName']);
                    $this->session->set_userdata('CountryCd', $genData['CountryCd']);
                }

            }else{
                $this->message = $this->lang->line('OTPDoesNotMatch');
            }

            $this->getResponse($this->status, $this->response, $this->message); 
        }

        $data['title'] = 'Login';
        $data['country'] = $this->gen->getCountryList();
        $this->load->view('general/login', $data);
    }

    public function profile(){
        $CustId = $this->session->userdata('GenCustId');
        if($CustId > 0){
            $Mobile = $this->session->userdata('GenMobile');
            $this->status = 'error';
            $this->message = 'Something went wrong please try again!!';
            if($this->input->method(true)=='POST'){

                $profile = $_POST;
                $profile['DOB'] = date('Y-m-d', strtotime($profile['DOB']));

                $this->gen->updateData('AllUsers', $profile, array('CustId' => $CustId));

                $this->status = 'success';
                $this->message = 'Profile Updated';

                $this->getResponse($this->status, $this->response, $this->message); 
            }

            $data['title'] = 'Profile';
            $data['user'] = $this->gen->getUserFromGenDb($Mobile);
            
            $this->load->view('general/profile', $data);
        }else{
            redirect(base_url('general/login'));
        }
    }

    public function bill_history(){
        $CustId = $this->session->userdata('GenCustId');
        if($CustId > 0){
            $country   =    0; 
            $city      =    0;
            if($this->input->method(true)=='POST'){
                $country    = $_POST['country'];
                $city       = $_POST['city'];
            }
            $data['title']  = 'Bill History';
            $data['countryCd']= $country;
            if($country < 1){
                $data['countryCd'] = $this->session->userdata('CountryCd');
            }
            $data['city']   = $city;
            $data['custPymt']  = $this->gen->getBills($CustId, $country, $city);
            $data['country']    = $this->gen->getCountries();
            $this->load->view('general/billing_history', $data);
        }else{
            redirect(base_url('general/login'));   
        }
    }

    public function getCityList(){
        $this->status = "error";
        $this->response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $this->status = "success";
            $this->response = $this->gen->getCityListByCountry($_POST['phone_code']);

            $this->getResponse($this->status, $this->response, $this->message);
        }
    }

    public function bill($billId){
        $CustId = $this->session->userdata('GenCustId');
        if($CustId > 0){
            $data['title'] = $this->lang->line('bill');
            $data['billId'] = $billId;
            $data['CustNo'] = $this->session->userdata('CustNo');

            if(isset($_GET['dbn'])){
                $dbname = $_GET['dbn'];
                $EID = $_GET['EID'];
            }

            $flag = 'cust';
            $res = $this->gen->gettingBiliingData($dbname, $EID, $billId, $CustId, $flag);
            

            if(!empty($res['billData'])){

                $billData = $res['billData'];
                $data['ra'] = $res['ra'];
                $data['taxDataArray'] = $res['taxDataArray'];

                $data['hotelName'] = $billData[0]['Name'];
                $data['BillName'] = $billData[0]['BillerName'];
                $data['Fullname'] = 'kk';
                // $data['Fullname'] = getName($billData[0]['CustId']);
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
                    
                $this->load->view('general/billing', $data);
            }else{
                $data['title'] = $this->lang->line('billing');
                $this->load->view('general/billing_not', $data);
            }

        }else{
            redirect(base_url('general/login'));      
        }
    }

    public function rest_details(){
        $CustId = $this->session->userdata('GenCustId');
        if($CustId > 0){
            if($this->input->method(true)=='POST'){
                
            }
            $data['title']      = 'Restaurant';
            $data['rests']      = $this->gen->getRestaurants();
            $this->load->view('general/restDetails', $data);
        }else{
            redirect(base_url('general/login'));   
        }
    }
    
    public function get_rest_details(){
        $this->status = "error";
        $this->response = $this->lang->line('SomethingSentWrongTryAgainLater');
        if($this->input->method(true)=='POST'){
            $this->status = "success";
            $this->response = $this->gen->get_rest_detail($_POST['EID']);

            $this->getResponse($this->status, $this->response, $this->message);
        }
    }

    private function getResponse($status, $response, $message){
        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'response' => $response
          ));
         die;
    }

    public function qrscan(){
        $this->session->sess_destroy();
        redirect(base_url('qr_scanner'));
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url('general'));
    }

    public function test(){
        echo "<pre>";
        print_r($_SESSION);
        die;
    }


}
