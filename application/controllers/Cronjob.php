<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

    private $db2;
    public function __construct()
    {
        parent::__construct();

        // $my_db = $this->session->userdata('my_db');
        // $this->db2 = $this->load->database($my_db, TRUE);

        // $this->load->model('Cust', 'cust');
    }
    
    private $count = 0;
    public function everyMorning6am($mydb){

        $mydb = $mydb.'e';
        $this->session->set_userdata('my_db', $mydb);
        $this->db2 = $this->load->database($mydb, TRUE);
        $fileLocation = getenv("DOCUMENT_ROOT") . "/uploads/myfile.txt";
        $file = fopen($fileLocation,"w");
        $content = "Your text here";
        fwrite($file,$content);
        fclose($file);
die;
        $this->db2->delete('OTP', array('stat' => 1));
        $dd['mobileNo'] = '7869068343';
        $dd['stat'] = '1';
        $dd['otp'] = '8343';
        $dd['pageRequest'] = 'test';

        $this->db2->insert('OTP', $dd);

        $msgText = "1211 is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
        
        sendSMS('7869068343', $msgText);
        sendSMS('8850876764', $msgText);
        die;

        $genTblDb = $this->load->database('GenTableData', TRUE);
        $rests = $genTblDb->select("CNo, EID, DBName")
                        ->get_where('EIDDet', array('Stat' => 1))
                        ->result_array();
        echo "<pre>";
        print_r($rests);
        die;

    }

    public function everyMorning4am(){

        $fileLocation = getenv("DOCUMENT_ROOT") . "/uploads/myfile.txt";
        $file = fopen($fileLocation,"w");
        $content = "Your text here";
        fwrite($file,$content);
        fclose($file);

        $msgText = "1211 is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
        
        sendSMS('7869068343', $msgText);
        sendSMS('8850876764', $msgText);

        // deleteOTPSMS();
        // dailyBilling($dbName, $EID)
        // dailyRevenue($dbName, $EID);

    }

    public function deleteOTPSMS(){
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-2 day", strtotime($today)));

        $dbList = $this->getDBList();

        if(!empty($dbList)){
            foreach ($dbList as $key) {
                $tmpDB = $this->load->database($key['DBName'], TRUE);
                $tmpDB->delete('OTP', array('created_date <=' => $yesterday));
            }
        }
    }

    private function getDBList(){
        $genDB = $this->load->database('GenTableData', TRUE);
        return $genDB->select("EID, DBName, CellNo, Email")->get_where('EIDDet', array('Stat' => 0))->result_array(); 
    }

    public function dailyBilling($dbName, $EID){

        $localDB = $this->load->database($dbName, TRUE);

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 day", strtotime($today)));

        return $localDB->select("sum(PaidAmt) as PaidAmount")->get_where('Billing', array('EID' => $EID, 'billTime' => $yesterday))->row_array();
    }

    public function dailyRevenue($dbName, $EID){

        $localDB = $this->load->database($dbName, TRUE);

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 day", strtotime($today)));

        return $localDB->select("sum(PaidAmt) as PaidAmount")
                    ->group_by('PaymtMode')
                    ->get_where('BillPayments', array('EID' => $EID, 'PymtDate' => $yesterday))->result_array();
    }

   
}

// https://hostadvice.com/how-to/web-hosting/ubuntu/how-to-setup-a-cron-job-on-ubuntu-18-04/
