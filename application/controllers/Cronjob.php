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

    }

    public function everyNight11PM(){
        
    }

    // minute hour day_of_month month day_of_week command_to_run
    // 30 17 * * 2 curl http://www.google.com


    // 2 * * * *  https://eo.vtrend.org/cronjob/everyMorning6am

}
