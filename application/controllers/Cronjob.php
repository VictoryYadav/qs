<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

    private $db2;
    public function __construct()
    {
        parent::__construct();

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);

        $this->load->model('Cust', 'cust');
    }

    private $count = 0;
    public function everyMorning6am(){
        $this->count = $this->count + 1;
        echo "Hi ". $this->count;

        $fileLocation = getenv("DOCUMENT_ROOT") . "/myfile.txt";
  $file = fopen($fileLocation,"w");
  $content = "Your text here";
  fwrite($file,$content);
  fclose($file);

    }

    public function everyNight11PM(){
        
    }

    // minute hour day_of_month month day_of_week command_to_run
    // 30 17 * * 2 curl http://www.google.com


    // 30 17 * * 2 curl https://eo.vtrend.org/cronjob

    // 30 17 * * 2 curl /var/www/eo.vtrend.org/public_html/application/controllers/cronjob/everyMorning6am
}
