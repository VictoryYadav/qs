<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->model('User', 'auth');
	}

	public function index()
	{
		if($this->input->method(true)=='POST'){
            $data = $_POST;
            // echo "<pre>";print_r($data);die;
            if (($data['phone'] == "") && ($data['password']== "")) {
                $this->session->set_flashdata('error','Enter Phone or Password'); 
                $this->load->view('login');
            } else {
                $data = $this->security->xss_clean(array(
                    'phone' => trim($data['phone']),
                    'password' => trim($data['password'])
                ));

                $my_db = $this->session->userdata('my_db');
                $db2 = $this->load->database($my_db, TRUE);
                
                $checkNumber = $db2->select('u.Passwd, u.RUserId, u.EID, u.ChainId, u.UTyp, c.AutoAllot, c.AutoDeliver, c.MultiKitchen, c.Kitchen,  c.TableReservation, c.Ops, c.CustAddr, c.EType, c.AutoAllot, c.AutoDeliver, c.Decline, c.Move,c.Fest,c.ECash ,e.Name, c.CustAssist, c.TableAcceptReqd,c.OrderWithoutTable,c.BillMergeOpt,c.AutoSettle,c.Dispense_OTP,c.DelCharge,c.DeliveryOTP')
                            ->join('Eatary e',' u.EID = e.EID', 'inner')
                            ->join('Config c','u.EID = c.EID','inner')
                            // u.ChainId = c.ChainId 
                            ->get_where('UsersRest u', array('u.MobileNo' => $data['phone']))->row_array();
                            // echo "<pre>";
                            // print_r($checkNumber);
                            // die;

                if (!empty($checkNumber)) {
                    
                    if($checkNumber['Passwd'] == $data['password']){

                        $this->session->set_userdata('RUserId', $checkNumber['RUserId']);
                        // $this->session->set_userdata('EID', $checkNumber['EID']);
                        // $this->session->set_userdata('ChainId', $checkNumber['ChainId']);
                        $this->session->set_userdata('UTyp', $checkNumber['UTyp']);
                        $this->session->set_userdata('Ops', $checkNumber['Ops']);
                        $this->session->set_userdata('EType', $checkNumber['EType']);
                        $this->session->set_userdata('AutoAllot', $checkNumber['AutoAllot']);
                        $this->session->set_userdata('AutoDeliver', $checkNumber['AutoDeliver']);
                        $this->session->set_userdata('MultiKitchen', $checkNumber['MultiKitchen']);
                        $this->session->set_userdata('Kitchen', $checkNumber['Kitchen']);
                        $this->session->set_userdata('Fest', $checkNumber['Fest']);
                        $this->session->set_userdata('Cash', $checkNumber['ECash']);

                        // $this->session->set_userdata('Accept', $checkNumber['Accept']);

                        // $this->session->set_userdata('Reject', $checkNumber['Reject']);
                        $this->session->set_userdata('Decline', $checkNumber['Decline']);
                        $this->session->set_userdata('Reassign', '');
                        $this->session->set_userdata('Move', $checkNumber['Move']);
                        $this->session->set_userdata('TableReservation', $checkNumber['TableReservation']);
                        $this->session->set_userdata('RestName', $checkNumber['Name']);
                        $this->session->set_userdata('CustAssist', $checkNumber['CustAssist']);
                        $this->session->set_userdata('TableAcceptReqd', $checkNumber['TableAcceptReqd']);
                        $this->session->set_userdata('BillMergeOpt', $checkNumber['BillMergeOpt']);
                        $this->session->set_userdata('AutoSettle', $checkNumber['AutoSettle']);
                        $this->session->set_userdata('Dispense_OTP', $checkNumber['Dispense_OTP']);
                        $this->session->set_userdata('DelCharge', $checkNumber['DelCharge']);
                        $this->session->set_userdata('OrderWithoutTable', $checkNumber['OrderWithoutTable']);
                        $this->session->set_userdata('DeliveryOTP', $checkNumber['DeliveryOTP']);

                        $session_data = array(
                        'EID' => $checkNumber['EID'],
                        'RestName' => $checkNumber['Name'],
                        'RUserId' => $checkNumber['RUserId'],
                        'mobile' => $data['phone'],
                        'ChainId' => $checkNumber['ChainId'],
                        'UTyp' => $checkNumber['UTyp'],
                    );
                    $this->session->set_userdata('logged_in', $session_data);

                        // if pass = QS1234 go to change password page_not_found
                        if($checkNumber['Passwd'] == 'QS1234'){
                            redirect(base_url('restaurant/change_password'));    
                        }
                        redirect(base_url('dashboard'));
                    }else{
                       $this->session->set_flashdata('error','Unable to Validate User'); 
                       $url = 'login?o='.$this->session->userdata('EID').'&c='.$this->session->userdata('ChainId');
                        redirect(base_url() . $url, 'refresh'); 
                    }
                    
                }else {
                        $this->session->set_flashdata('error','Fail to Validate User'); 
                        $url = 'login?o='.$this->session->userdata('EID').'&c='.$this->session->userdata('ChainId');
                        redirect(base_url() . $url, 'refresh');
                    }
            }
        }

		if (!isset($_GET['o']) && !isset($_GET['c'])) {
			redirect(base_url('page_not_found'));
		}

		$this->session->set_userdata('EID', $_GET['o']);
		$this->session->set_userdata('ChainId', $_GET['c']);

		$my_db = $_GET['o'].'e';
        $this->session->set_userdata('my_db', $my_db);
        $data['o'] = $_GET['o'];
        $data['c'] = $_GET['c'];
		$this->load->view('login',$data);
	}



	public function logout(){
		// $this->session->unset_userdata('logged_in');
        $url = 'login?o='.$this->session->userdata('EID').'&c='.$this->session->userdata('ChainId');
		$this->session->sess_destroy();
		redirect(base_url() . $url, 'refresh');
	}

	public function getpost()
	{
		if (empty($_POST)) {
			$this->load->view('errors/html/error_method');
		} else {
			return json_decode(json_encode($_POST));
		}
	}
}
