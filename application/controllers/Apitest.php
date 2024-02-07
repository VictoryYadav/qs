<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apitest extends CI_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Testapp', 'api_model');

    }

    function users(){
        $data = $this->api_model->getCustUsers();

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'message' => 'customer list data',
            'data' => $data
          ));
         die;
    }
    
}
