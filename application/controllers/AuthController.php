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
                $EID = $this->session->userdata('EID');

                $login_check = $db2->select('*')
                        ->group_start() 
                            ->where('PEmail', $data['phone'])
                            ->or_where('MobileNo',$data['phone'])
                        ->group_end()
                        ->where('Passwd', $data['password'])
                        ->get_where('UsersRest', array('Stat' => 0, 'EID' => $EID))
                        ->row_array();

                if (!empty($login_check)) {

                        $checkNumber = $db2->select('u.Passwd, u.RUserId, u.EID, u.ChainId, u.UTyp, c.AutoAllot, c.AutoDeliver, c.MultiKitchen, c.multiCustTable, c.Kitchen,  c.TableReservation, c.Ops, c.CustAddr, c.EType, c.AutoAllot, c.AutoDeliver, c.Decline, c.Move,e.Name, c.CustAssist, c.TableAcceptReqd,c.BillMergeOpt,c.AutoSettle,c.Dispense_OTP,c.DelCharge,c.DeliveryOTP, c.EDT ,c.Discount, c.IMcCdOpt, c.billPrintTableNo,c.sitinKOTPrint,c.JoinTable, c.tableSharing')
                            ->join('Eatary e',' u.EID = e.EID', 'inner')
                            ->join('Config c','u.EID = c.EID','inner')
                            // u.ChainId = c.ChainId 
                            ->get_where('UsersRest u', array('u.MobileNo' => $data['phone']))
                            ->row_array();

                        $this->session->set_userdata('RUserId', $checkNumber['RUserId']);
                        $this->session->set_userdata('COrgId', 0);
                        $this->session->set_userdata('CustNo', 0);
                        $this->session->set_userdata('CustId', 0);
                        $this->session->set_userdata('UTyp', $checkNumber['UTyp']);
                        $this->session->set_userdata('Ops', $checkNumber['Ops']);
                        $this->session->set_userdata('EType', $checkNumber['EType']);
                        $this->session->set_userdata('AutoAllot', $checkNumber['AutoAllot']);
                        $this->session->set_userdata('AutoDeliver', $checkNumber['AutoDeliver']);
                        $this->session->set_userdata('MultiKitchen', $checkNumber['MultiKitchen']);
                        $this->session->set_userdata('Kitchen', $checkNumber['Kitchen']);
                        $this->session->set_userdata('Decline', $checkNumber['Decline']);
                        $this->session->set_userdata('Reassign', '');
                        $this->session->set_userdata('Move', $checkNumber['Move']);
                        $this->session->set_userdata('JoinTable', $checkNumber['JoinTable']);
                        
                        $this->session->set_userdata('TableReservation', $checkNumber['TableReservation']);
                        $this->session->set_userdata('RestName', $checkNumber['Name']);
                        $this->session->set_userdata('CustAssist', $checkNumber['CustAssist']);
                        $this->session->set_userdata('TableAcceptReqd', $checkNumber['TableAcceptReqd']);
                        $this->session->set_userdata('BillMergeOpt', $checkNumber['BillMergeOpt']);
                        $this->session->set_userdata('AutoSettle', $checkNumber['AutoSettle']);
                        $this->session->set_userdata('Dispense_OTP', $checkNumber['Dispense_OTP']);
                        $this->session->set_userdata('DelCharge', $checkNumber['DelCharge']);
                        $this->session->set_userdata('DeliveryOTP', $checkNumber['DeliveryOTP']);
                        $this->session->set_userdata('EDT', $checkNumber['EDT']);
                        $this->session->set_userdata('billFlag',0);
                        $this->session->set_userdata('Discount',$checkNumber['Discount']);
                        $this->session->set_userdata('IMcCdOpt',$checkNumber['IMcCdOpt']); 
                        $this->session->set_userdata('billPrintTableNo',$checkNumber['billPrintTableNo']); 
                        $this->session->set_userdata('sitinKOTPrint',$checkNumber['sitinKOTPrint']);      

                        $this->session->set_userdata('multiCustTable',$checkNumber['multiCustTable']); 
                        $this->session->set_userdata('tableSharing',$checkNumber['tableSharing']);                  

                        
                        $session_data = array(
                        'EID' => $checkNumber['EID'],
                        'RestName' => $checkNumber['Name'],
                        'RUserId' => $checkNumber['RUserId'],
                        'mobile' => $data['phone'],
                        'ChainId' => $checkNumber['ChainId'],
                        'UTyp' => $checkNumber['UTyp'],
                        'cur_password' => $checkNumber['Passwd'],
                    );
                    $this->session->set_userdata('logged_in', $session_data);
                    // set site_lang = 1 => english
                    $this->session->set_userdata('site_lang', 1);
                    $this->session->set_userdata('site_langName', 'english');

                        // if pass = QS1234 go to change password page_not_found
                    if($checkNumber['Passwd'] == 'QS1234'){
                        redirect(base_url('restaurant/change_password'));    
                    }

                    $userRolesAccessData = $db2->select('ur.PhpPage, ur.pageUrl')
                                                ->order_by('ur.Rank', 'ASC')
                                                ->join('UserRolesAccess ura', 'ura.RoleId = ur.RoleId','inner')
                                                ->get_where('UserRoles ur', 
                                                    array('ura.RUserId' => $checkNumber['RUserId'],
                                                        'ura.EID' => $checkNumber['EID'],
                                                        'ur.Stat' => 0)
                                                        )
                                                ->row_array();
                    $url = base_url('restaurant').'/'.$userRolesAccessData['pageUrl'];
                    
                    if($userRolesAccessData['pageUrl'] == 'dashboard'){
                        redirect(base_url('dashboard'));
                    }

                    redirect($url);
                    
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
		$this->session->set_userdata('CatgID', $_GET['c']);

		$my_db = $_GET['o'].'e';
        $this->session->set_userdata('my_db', $my_db);
        $data['o'] = $_GET['o'];
        $data['c'] = $_GET['c'];
		$this->load->view('login',$data);
	}



	public function logout(){
		// $this->session->unset_userdata('logged_in');
        $url = 'login?o='.$this->session->userdata('EID').'&c='.$this->session->userdata('CatgID');
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
