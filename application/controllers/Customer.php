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

        $data['title'] = 'Item Details';
        $data['language'] = languageArray();
        $data['EType'] = $this->session->userdata('EType');
        $data['Charity'] = $this->session->userdata('Charity');
        $data['Itm_Portion'] = 1;
        $data['offers'] = $this->cust->getOffers();
        
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
            echo "<pre>";
            print_r($_POST);
            die;
            $res = $this->cust->getOfferCustAjax($_POST);
            echo $res;
        }
    }

    public  function get_item_offer_ajax()
    {
        $status = 'error';
        $response = 'Someting went wrong!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $res = $this->cust->getItemOfferAjax($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    public function item_details_ajax(){
        if($this->input->method(true)=='POST'){

            $this->cust->getItem_details_ajax($_POST);
            // echo json_encode($res);
            // die;
        }   
    }

    // cart details
    public function cart(){

        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
            // send_to_kitchen_ajax
            $CustId = $this->session->userdata('CustId');
            $TempCustId = $this->session->userdata('TempCustId');
            $ChainId = authuser()->ChainId;
            $CNo = $this->session->userdata('CNo');
            $EType = $this->session->userdata('EType');
            //$Stall = Session::get('Stall');
            //$Ops = Session::get('Ops');
            $TableNo = authuser()->TableNo;
            $KOTNo = $this->session->userdata('KOTNo');
            $MultiKitchen = $this->session->userdata('MultiKitchen');
            $Kitchen = $this->session->userdata('Kitchen');

            if ($CustId != '') {

                if (isset($_POST['getSendToKitchenList']) && $_POST['getSendToKitchenList']) {

                    // Get all Temp Item list
                    $kitcheData = $this->db2->query("SELECT k.OrdNo, k.ItemId, k.Qty, k.TA, k.Itm_Portion, (if (k.ItemTyp > 0,(CONCAT(mi.ItemNm, ' - ' , k.CustItemDesc)),(mi.ItemNm ))) as ItemNm,  k.ItmRate as Value, mi.PckCharge, k.OType, k.OrdTime , ip.Name as Portions from Kitchen k, MenuItem mi,ItemPortions ip where k.Itm_Portion = ip.IPCd and k.CustId = $CustId AND k.EID = $EID AND k.TableNo = $TableNo AND k.ItemId = mi.ItemId AND k.BillStat = 0 AND k.Stat = 10 and k.CNo = $CNo")
                    ->result_array();

                    if(empty($kitcheData)){
                        $response = [
                            "status" => 0,
                            "msg" => "No Categary is available"
                        ];
                    }else {
                        $response = [
                            "status" => 1,
                            "kitcheData" => $kitcheData
                        ];
                    }
                    echo json_encode($response);
                    die();
                }

                if (isset($_POST['cancelOrder']) && $_POST['cancelOrder']) {
                    $orderNo = $_POST['orderNo'];
                    $cancelOrder = $this->db2->query("UPDATE Kitchen set Stat = 7 where OrdNo = $orderNo AND CustId = $CustId AND EID = $EID");

                    $response = [
                        "status" => 1,
                        "msg" => "Order Cancelled"
                    ];
                    echo json_encode($response);
                    die();
                }

                if (isset($_POST['sendToKitchen']) && $_POST['sendToKitchen']) {
                    if($CustId != $TempCustId){
                        // Check any order is already accepted
                        $checkOrderAccepted = $this->db2->query("SELECT OrdNo FROM Kitchen WHERE Stat = 1 AND CustId = $CustId AND EID = $EID AND TableNo = '$TableNo'")->result_array();

                        if (empty($checkOrderAccepted)) {
                            $stat = 0;
                        }else {
                            $stat = 1;
                        }

                        // Update kitchen stat = 10 to stat = 0 or 1. This is for EType=5
                        $updateKitchenStat = $this->db2->query("UPDATE Kitchen set Stat = $stat where Stat = 10 AND CustId = $CustId AND EID = $EID AND TableNo = '$TableNo'");
                        // set Kot to 0
                        $this->session->set_userdata('KOTNo', 0);
                        $response = [
                            "status" => 1,
                            "msg" => "Order Sent To Kitchen Successfully"
                        ];
                        echo json_encode($response);
                        die();
                    }else{
                        $response = [
                            "status" => 11,
                            "msg" => "1"
                        ];
                        echo json_encode($response);
                        die();
                    }   

                }else{
                   $url =  base_url('customer/cust_registration');
                    echo '<script>window.location.assign("$url");</script>';
                }
            }else { 
                // Session Expire
                $response = [
                    "status" => "100",
                    "msg" => "Session Expire Please Rescan QR Code"
                ];

                echo json_encode($response);
                die();
            }
        }

        $CustId = $this->session->userdata('CustId');
        if ($CustId == '') {
            // header('Location: index.php');
            redirect(base_url('customer'));
        }

        $data['cId'] = $this->session->userdata('cId');
        $data['mCatgId'] = $this->session->userdata('mCatgId');
        $data['cType'] = $this->session->userdata('cType');
        $data['EID'] = $EID;

        $data['title'] = 'Order Details';
        $data['language'] = languageArray();
        $this->load->view('cust/cart', $data);
    }


}
