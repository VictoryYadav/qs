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
                    $my_db = $e_data[1].'e';
                    $this->session->set_userdata('my_db', $my_db);
                }
                if(isset($req[1])){
                    $c_data = explode("=", $req[1]);
                    if($c_data[0] == 'c'){
                        $_REQUEST['c'] = $c_data[1];
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
            echo "<h1>Incorrect Details !</br></br> Please Scan QR Code again.</h1>";
            die;
        }

        $session_data = array(
            'EID' => $_REQUEST['e'],
            'TableNo' => $_REQUEST['t'],
            'Stall' => $_REQUEST['o'],
            'ChainId' => $_REQUEST['c'],
        );
        $this->session->set_userdata('logged_in', $session_data);

        $this->session->set_userdata('ONo', 0);
        $this->session->set_userdata('KOTNo', 0);
        $this->session->set_userdata('EType', 0);
        $this->session->set_userdata('CustOrgs', 0);
        $this->session->set_userdata('MultiKitchen', 0);
        $this->session->set_userdata('MultiScan', 0);
        $this->session->set_userdata('OrgType', 0);
        $this->session->set_userdata('CNo', 0);
        $this->session->set_userdata('Cash', 0);
        $this->session->set_userdata('PostPaid', 0);
        $this->session->set_userdata('PrePaid', 0);
        $this->session->set_userdata('PymtOpt', 0);
        $this->session->set_userdata('Ops', 0);
        $this->session->set_userdata('TipAmount', 0);
        $this->session->set_userdata('SchPop', 0);
        $this->session->set_userdata('CustNo', 0);
        $this->session->set_userdata('COrgId', 0);

        if (!isset($_SESSION['CustId'])) {
            $temp_custId = mt_rand(500001, 9999999);
            $this->session->set_userdata('CustId', $temp_custId);
            $this->session->set_userdata('TempCustId', $temp_custId);
            $this->session->set_userdata('CellNo', '1111111111');         
        }

        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;

        // load  GenTableData db
        $genTblDb = $this->load->database('GenTableData', TRUE);

        $eidDetData = $genTblDb->select('PymtGateway, DBName')->get_where('EIDDet', array('EID' => $EID))->row_array();

        $this->session->set_userdata('paymentGateway', $eidDetData['PymtGateway']);
        $this->session->set_userdata('DynamicDB', $eidDetData['DBName']);
        
        // load db
        $db2 = $this->load->database($my_db, TRUE);

        $orgCheck = $db2->query("SELECT e.CatgID, e.ONo, c.StTime, c.CloseTime, c.EType, c.ECash, c.PostPaid, c.PrePaid,  c.CustOrgs, c.MultiKitchen, c.MultiScan, c.Kitchen, c.AutoAllot, c.AutoDeliver, c.SchPop, c.SchType, c.ServChrg, c.Tips, c.EDT, c.TableReservation, c.Fest, c.Hostel,c.Deliver, c.Itm_Portion, c.CustAssist, c.TableAcceptReqd,c.OrderWithoutTable,c.BillMergeOpt,c.AutoSettle,c.Dispense_OTP,c.DelCharge, c.menuCatg, c.foodTyp, c.Charity FROM Config c, Eatary e where e.EID = $EID AND e.ChainId = $ChainId and e.EID = c.EID And c.ChainId = e.ChainId")->row_array();

        $this->session->set_userdata('CatgID', $orgCheck['CatgID']);
        $this->session->set_userdata('ONo', $orgCheck['ONo']);
        $this->session->set_userdata('EType', $orgCheck['EType']);
        $this->session->set_userdata('CustOrgs', $orgCheck['CustOrgs']);
        $this->session->set_userdata('Cash', $orgCheck['ECash']);
        $this->session->set_userdata('PostPaid', $orgCheck['PostPaid']);
        $this->session->set_userdata('PrePaid', $orgCheck['PrePaid']);
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
        $this->session->set_userdata('Itm_Portion', $orgCheck['Itm_Portion']);
        $this->session->set_userdata('Tips', $orgCheck['Tips']);
        $this->session->set_userdata('CustAssist', $orgCheck['CustAssist']);
        $this->session->set_userdata('TableAcceptReqd', $orgCheck['TableAcceptReqd']);
        $this->session->set_userdata('BillMergeOpt', $orgCheck['BillMergeOpt']);
        $this->session->set_userdata('AutoSettle', $orgCheck['AutoSettle']);
        $this->session->set_userdata('Dispense_OTP', $orgCheck['Dispense_OTP']);
        $this->session->set_userdata('DelCharge', $orgCheck['DelCharge']);
        $this->session->set_userdata('OrderWithoutTable', $orgCheck['OrderWithoutTable']);

        $this->session->set_userdata('menuCatg', $orgCheck['menuCatg']);
        $this->session->set_userdata('foodTyp', $orgCheck['foodTyp']);
        $this->session->set_userdata('Charity', $orgCheck['Charity']);

        redirect(base_url('customer'));
	}

    public function page_not_found(){
        $data['title'] = "Page Not Found!";
        $this->load->view('page404',$data);
    }

}
