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

        $msgText = "1211 is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
        
        // sendSMS('7869068343', $msgText);
        // sendSMS('8850876764', $msgText);

        // $fileLocation = getenv("DOCUMENT_ROOT") . "/uploads/myfile.txt";
        // $file = fopen($fileLocation,"w");
        // $content = "Your text here";
        // fwrite($file,$content);
        // fclose($file);

        // die;
        $genTblDb = $this->load->database('GenTableData', TRUE);
        $rests = $genTblDb->select("CNo, EID, DBName")
                        ->get_where('EIDDet', array('Stat' => 1))
                        ->result_array();
        echo "<pre>";
        print_r($rests);
        die;

    }

    public function everyNight11PM(){
        
    }

   
}

// https://hostadvice.com/how-to/web-hosting/ubuntu/how-to-setup-a-cron-job-on-ubuntu-18-04/
