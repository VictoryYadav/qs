<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

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

        $this->load->model('Cust', 'cust');
	}

    public function index(){

        $data['cuisinList'] = $this->cust->getCuisineList();
        $this->session->set_userdata('cuisine', $data['cuisinList'][0]['CID']);
        $cid = $data['cuisinList'][0]['CID'];
        $data['cid'] = $cid;
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            
            
            if(isset($_POST['cid']) && !empty($_POST['cid'])){
                $cid = $_POST['cid'];
            }
            $res['list'] = $this->cust->getMcatandCtypList($cid);
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }

        $data['title'] = 'Order Details';
        $data['language'] = languageArray();
        $data['EType'] = $this->session->userdata('EType');
        $data['Charity'] = $this->session->userdata('Charity');
        $data['Itm_Portion'] = 1;
        
        $this->load->view('cust/index', $data);
    }

    public function getItemDetailsData(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            extract($_POST);
            $res = $this->cust->getItemDetailLists($cid, $mcatId, $filter);           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }        
    }

    public function get_item_portion_ajax(){
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $TableNo = authuser()->TableNo;
            $data['EType'] = $this->session->userdata('EType');
            extract($_POST);

            $MenuItemRates = $this->cust->getMenuItemRates($EID, $itemId, $TableNo, $cid, $MCatgId, $ItemTyp );
            print_r(json_encode($MenuItemRates));
            
        }        
    }

    public function offer_cust_ajax(){
        if($this->input->method(true)=='POST'){
            $res = $this->cust->getOfferCustAjax($_POST);
            echo $res;
        }
    }


}
