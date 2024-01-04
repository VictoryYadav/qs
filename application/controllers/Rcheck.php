<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rcheck extends CI_Controller {

	public function index()
	{
        // $db2 = $this->load->database('51e', TRUE);

        if(isset($_GET['qr_data']) && !empty($_GET['qr_data'])){
            $qr_data = rtrim(base64_decode($_REQUEST['qr_data']), "=");
            // echo "<pre>";print_r($qr_data);exit();
            $req = explode("&", $qr_data);
            
            if(!empty($req)){
                $e_data = explode("=", $req[0]);
                if($e_data[0] == 'e'){

                    $_REQUEST['e'] = $e_data[1];
                    // checking category_id  = $req[1]= 11
                    if(isset($req[1])){
                        $c_data = explode("=", $req[1]);
                        if($c_data[0] == 'c'){
                            $_REQUEST['c'] = $c_data[1];
                            if($_REQUEST['c'] != 11){
                                $my_db = $e_data[1].'e';
                                $this->session->set_userdata('my_db', $my_db);
                                $this->session->set_userdata('oldEID', $e_data[1]);
                            }else{
                                $my_db = $this->session->userdata('my_db');
                            }
                        }
                    }
                }
                
                if(isset($req[2])){
                    $t_data = explode("=", $req[2]);
                    if($t_data[0] == 't'){
                        $_REQUEST['t'] = $t_data[1];
                    }
                }
                if(isset($req[3])){
                    $o_data = explode("=", $req[3]);
                    if($o_data[0] == 'o'){
                        $_REQUEST['o'] = $o_data[1];
                    }
                }
            }

            if (!isset($_REQUEST['e']) && !isset($_REQUEST['c']) && !isset($_REQUEST['t']) && !isset($_REQUEST['o'])) {
                echo "<h1>Incorrect Details !</br></br> Please Scan QR Code again.</h1>";
                exit;
            }

        }else{
            echo "<h1>Please Scan QR Code again.</h1>";
            die;
        }


        $this->session->set_userdata('EID', $_REQUEST['e']);
        $this->session->set_userdata('TableNo', $_REQUEST['t']);

        $this->session->set_userdata('MergeNo', $_REQUEST['t']);
        $this->session->set_userdata('ONo', 0);
        $this->session->set_userdata('KOTNo', 0);
        $this->session->set_userdata('EType', 0);
        $this->session->set_userdata('CustOrgs', 0);
        $this->session->set_userdata('MultiKitchen', 0);
        $this->session->set_userdata('MultiScan', 0);
        $this->session->set_userdata('OrgType', 0);
        $this->session->set_userdata('CNo', 0);
        // $this->session->set_userdata('Cash', 0);
        // $this->session->set_userdata('PostPaid', 0);
        // $this->session->set_userdata('PrePaid', 0);
        // $this->session->set_userdata('PymtOpt', 0);
        $this->session->set_userdata('Ops', 0);
        $this->session->set_userdata('TipAmount', 0);
        $this->session->set_userdata('SchPop', 0);
        $this->session->set_userdata('CustNo', 0);
        $this->session->set_userdata('COrgId', 0);

        if (!isset($_SESSION['CustId'])) {
            $temp_custId = mt_rand(500001, 9999999);
            // $this->session->set_userdata('CustId', $temp_custId);
            $this->session->set_userdata('TempCustId', $temp_custId);
            $this->session->set_userdata('CellNo', '1111111111');         
        }

        $EID = $_REQUEST['e'];

        // load db
        $db2 = $this->load->database($my_db, TRUE);

        $orgCheck = $db2->query("SELECT e.CatgID,e.ChainId, e.ONo, c.StTime, c.CloseTime, c.EType, c.CustOrgs, c.MultiKitchen, c.MultiScan, c.Kitchen, c.AutoAllot, c.AutoDeliver, c.SchPop, c.SchType, c.ServChrg, c.Tips, c.EDT, c.TableReservation, c.Fest, c.Hostel,c.Deliver, c.CustAssist, c.TableAcceptReqd,c.OrderWithoutTable,c.BillMergeOpt,c.AutoSettle,c.Dispense_OTP,c.DelCharge, c.Charity, c.Ing_Cals, c.NV,c.WelcomeMsg,c.Ent,c.MultiLingual,c.MultiPayment,c.pymtENV FROM Config c, Eatary e where e.EID = $EID and e.EID = c.EID")->row_array();

        $session_data = array(
            'EID' => $_REQUEST['e'],
            'TableNo' => $_REQUEST['t'],
            'Stall' => $_REQUEST['o'],
            'CatgID' => $_REQUEST['c'],
            'ChainId' => $orgCheck['ChainId'],
        );
        $this->session->set_userdata('logged_in', $session_data);

        $this->session->set_userdata('ChainId', $orgCheck['ChainId']);
        $this->session->set_userdata('ONo', $orgCheck['ONo']);
        $this->session->set_userdata('EType', $orgCheck['EType']);
        $this->session->set_userdata('CustOrgs', $orgCheck['CustOrgs']);
        
        $this->session->set_userdata('MultiKitchen', $orgCheck['MultiKitchen']);
        $this->session->set_userdata('MultiScan', $orgCheck['MultiScan']);
        $this->session->set_userdata('Kitchen', $orgCheck['Kitchen']);
        $this->session->set_userdata('AutoAllot', $orgCheck['AutoAllot']);
        $this->session->set_userdata('AutoDeliver', $orgCheck['AutoDeliver']);
        $this->session->set_userdata('SchPop', $orgCheck['SchPop']);
        $this->session->set_userdata('SchType', $orgCheck['SchType']);
        $this->session->set_userdata('EDT', $orgCheck['EDT']);
        $this->session->set_userdata('Fest', $orgCheck['Fest']);
        $this->session->set_userdata('Deliver', $orgCheck['Deliver']);
        
        $this->session->set_userdata('Tips', $orgCheck['Tips']);
        $this->session->set_userdata('CustAssist', $orgCheck['CustAssist']);
        $this->session->set_userdata('TableAcceptReqd', $orgCheck['TableAcceptReqd']);
        $this->session->set_userdata('BillMergeOpt', $orgCheck['BillMergeOpt']);
        $this->session->set_userdata('AutoSettle', $orgCheck['AutoSettle']);
        $this->session->set_userdata('Dispense_OTP', $orgCheck['Dispense_OTP']);
        $this->session->set_userdata('DelCharge', $orgCheck['DelCharge']);
        $this->session->set_userdata('OrderWithoutTable', $orgCheck['OrderWithoutTable']);

        $this->session->set_userdata('Charity', $orgCheck['Charity']);
        $this->session->set_userdata('Ing_cals', $orgCheck['Ing_Cals']);
        $this->session->set_userdata('NV', $orgCheck['NV']);

        $this->session->set_userdata('WelcomeMsg', $orgCheck['WelcomeMsg']);
        $this->session->set_userdata('Ent', $orgCheck['Ent']);
        $this->session->set_userdata('MultiLingual', $orgCheck['MultiLingual']);    
        $this->session->set_userdata('MultiPayment', $orgCheck['MultiPayment']);
        $this->session->set_userdata('pymtENV', $orgCheck['pymtENV']); 
        // set site_lang = 1 => english
        $this->session->set_userdata('site_lang', 1);
        $this->session->set_userdata('site_langName', 'english');

        if($_REQUEST['c'] > 10){
            redirect(base_url('customer'));
        }else{
            // multi restaurant
            redirect(base_url('customer/outlets'));
        }
	}

    public function page_not_found(){
        $data['title'] = "Page Not Found!";
        $this->load->view('page404',$data);
    }

}
