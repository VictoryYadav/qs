<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restsupp extends CI_Controller {

    private $db2;
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->authuser = $this->session->userdata('logged_in');
        } else {
            redirect(base_url());
        }

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
        
        $this->output->delete_cache();        
    }

    public function user_roles(){
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
            $status = "success";

            $et['EType']  = $_POST['EType'];
            $et['CatgID'] = $_POST['CatgID'];
            $et['ECatg']  = $_POST['ECatg'];

            updateRecord('Eatary', $et, array('EID' => $EID) );
            $roleIds = [];
            foreach ($_POST['role'] as $key => $value) {
                $roleIds[] = $key;
            }       

            $this->db2->where_in('RoleId', $roleIds);
            $this->db2->update('UserRoles', array('Stat' => 0)); 

            $this->db2->where_not_in('RoleId', $roleIds);
            $this->db2->update('UserRoles', array('Stat' => 1)); 

            $response = 'User Roles Upadated';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['title'] = 'User Roles';
        $data['detail'] = $this->db2->select("EType, CatgID, ECatg")->get_where('Eatary',array('EID' => $EID))->row_array();

        $langId = $this->session->userdata('site_lang');
        $Name = "Name$langId as Name";
        $data['roles'] = $this->db2->select("*, $Name")->get('UserRoles')->result_array();
        $data['ECategory'] = $this->db2->get('ECategory')->result_array();
        $data['Category'] = $this->db2->get('Category')->result_array();

        $this->load->view('support/userroles', $data);
    }

    
}
