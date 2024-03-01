<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apitest extends CI_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Testapp', 'api_model');

        Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
        Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
        Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

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
