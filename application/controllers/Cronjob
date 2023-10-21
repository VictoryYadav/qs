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

    public function everyMorning6am(){
        echo "Hi";die;

    }

    public function everyNight11PM(){
        
    }


}
