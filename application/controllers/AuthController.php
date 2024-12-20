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
        // print_r(base_url());die;
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

                        $checkNumber = $db2->select('u.Passwd, u.RUserId, u.EID, u.ChainId, u.UTyp, c.AutoAllot, c.AutoDeliver, c.MultiKitchen, c.multiCustTable, c.MultiLingual, c.kds,  c.TableReservation, c.Ops, c.CustAddr, e.EType, e.aggEID, c.AutoAllot, c.AutoDeliver, c.Decline, c.Move,e.Name, e.CountryCd, e.Logo, c.CustAssist, c.TableAcceptReqd,c.BillMergeOpt, c.billSplitOpt, c.AutoSettle,c.Dispense_OTP,c.DelCharge, c.Charity, c.CharityCharge, c.DeliveryOTP, c.EDT ,c.Discount, c.IMcCdOpt, c.billPrintTableNo,c.sitinKOTPrint, c.JoinTable, c.tableSharing, c.Bill_KOT_Print, c.SchType, c.restBilling, c.recommend, c.addItemLock, c.AutoPrintKOT')
                            ->join('Eatary e',' u.EID = e.EID', 'inner')
                            ->join('Config c','u.EID = c.EID','inner')
                            // u.ChainId = c.ChainId 
                            ->get_where('UsersRest u', array('u.MobileNo' => $data['phone']))
                            ->row_array();

                        $this->session->set_userdata('RUserId', $checkNumber['RUserId']);
                        $this->session->set_userdata('COrgId', 0);
                        $this->session->set_userdata('CustNo', 0);
                        $this->session->set_userdata('CustId', 0);
                        $this->session->set_userdata('AutoPrintKOT', $checkNumber['AutoPrintKOT']);
                        $this->session->set_userdata('UTyp', $checkNumber['UTyp']);
                        $this->session->set_userdata('Ops', $checkNumber['Ops']);
                        $this->session->set_userdata('EType', $checkNumber['EType']);
                        $this->session->set_userdata('AutoAllot', $checkNumber['AutoAllot']);
                        $this->session->set_userdata('AutoDeliver', $checkNumber['AutoDeliver']);
                        $this->session->set_userdata('MultiKitchen', $checkNumber['MultiKitchen']);
                        $this->session->set_userdata('MultiLingual', $checkNumber['MultiLingual']);
                        
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
                        $this->session->set_userdata('billSplitOpt', $checkNumber['billSplitOpt']);
                        
                        $this->session->set_userdata('AutoSettle', $checkNumber['AutoSettle']);
                        $this->session->set_userdata('Dispense_OTP', $checkNumber['Dispense_OTP']);
                        $this->session->set_userdata('DelCharge', $checkNumber['DelCharge']);
                        $this->session->set_userdata('DeliveryOTP', $checkNumber['DeliveryOTP']);
                        $this->session->set_userdata('Charity', $checkNumber['Charity']);
                        $this->session->set_userdata('CharityCharge', $checkNumber['CharityCharge']);
                        
                        $this->session->set_userdata('EDT', $checkNumber['EDT']);
                        $this->session->set_userdata('billFlag',0);
                        $this->session->set_userdata('Discount',$checkNumber['Discount']);
                        $this->session->set_userdata('IMcCdOpt',$checkNumber['IMcCdOpt']); 
                        $this->session->set_userdata('billPrintTableNo',$checkNumber['billPrintTableNo']); 
                        $this->session->set_userdata('sitinKOTPrint',$checkNumber['sitinKOTPrint']);      

                        $this->session->set_userdata('multiCustTable',$checkNumber['multiCustTable']); 
                        $this->session->set_userdata('tableSharing',$checkNumber['tableSharing']); 
                        $this->session->set_userdata('Bill_KOT_Print',$checkNumber['Bill_KOT_Print']);         
                        $this->session->set_userdata('SchType', $checkNumber['SchType']); 
                        $this->session->set_userdata('CountryCd', $checkNumber['CountryCd']);
                        $this->session->set_userdata('pCountryCd', 0);
                        $this->session->set_userdata('aggEID', $checkNumber['aggEID']);

                        $this->session->set_userdata('restBilling',$checkNumber['restBilling']); 
                        $this->session->set_userdata('recommend',$checkNumber['recommend']);
                        $this->session->set_userdata('addItemLock',$checkNumber['addItemLock']);
                        $this->session->set_userdata('kds',$checkNumber['kds']);
                        $this->session->set_userdata('Logo',$checkNumber['Logo']);
                        
                        $session_data = array(
                        'EID' => $checkNumber['EID'],
                        'RestName' => $checkNumber['Name'],
                        'RUserId' => $checkNumber['RUserId'],
                        'mobile' => $data['phone'],
                        'ChainId' => $checkNumber['ChainId'],
                        'UTyp' => $checkNumber['UTyp'],
                    );
                    $this->session->set_userdata('logged_in', $session_data);
                    $this->session->set_userdata('cur_password', $checkNumber['Passwd']);
                    // set site_lang = 1 => english
                    $this->session->set_userdata('site_lang', 1);
                    $this->session->set_userdata('site_langName', 'english');

                        // if pass = eo1234 go to change password page_not_found
                    if($checkNumber['Passwd'] == 'eo1234'){
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

                    redirect($url);
                    
                }else {
                        $this->session->set_flashdata('error','Password is incorrect!'); 
                        $url = 'login?o='.$this->session->userdata('EID');
                        redirect(base_url() . $url, 'refresh');
                    }
            }
        }

        // session destroy for comes link from another sites
            // $this->session->sess_destroy();
        // end of session

        if ($this->session->userdata('logged_in')) {
            redirect(base_url('restaurant'));
        } else {
            if (!isset($_GET['o'])) {
                redirect(base_url('error'));
            }

            if($_GET['o'] > 0){

                $this->session->set_userdata('EID', $_GET['o']);
                $this->session->set_userdata('CatgID', 0);

                $EID = $_GET['o'];
                $genTblDb = $this->load->database('GenTableData', TRUE);
                $checkDB =  $genTblDb->select('EID, CNo')
                            ->get_where('EIDDet', array('EID' => $EID))
                            ->row_array();
                if(!empty($checkDB)){
                    $my_db = $_GET['o'].'e';
                    $this->session->set_userdata('my_db', $my_db);
                    $data['o'] = $_GET['o'];
                    $this->load->view('login',$data);
                }else{
                    redirect(base_url('error'));    
                }

            }else{
                redirect(base_url('error'));
            }

        }

    }

    public function logout(){
        $url = 'login?o='.$this->session->userdata('EID');
        $this->session->sess_destroy();
        redirect(base_url() . $url, 'refresh');
    }

    public function error(){
        $data['title'] = "Error!";
        $this->load->view('error',$data);
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
