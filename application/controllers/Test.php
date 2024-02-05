<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {


    public function __construct()
    {
        parent::__construct();

        
    }

    function pay_success(){
        echo "<pre>";print_r($_SESSION);
        print_r($_POST);
    }

    
}
