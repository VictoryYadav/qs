<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/razorpay-php/Razorpay.php';
        

        use Razorpay\Api\Api;

class Razorpay extends CI_Controller {

    private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'user');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

    public function pay(){
        
        $keyId = 'rzp_test_Z50p6ZM95VvlSy';
        $keySecret = 'gtQvvO7aRLLoMefAWCV7Pcwp';
        $displayCurrency = 'INR';

        $api = new Api($keyId, $keySecret);

        //Session Variables;
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $CustId = $this->session->userdata('CustId');
        $EType = $this->session->userdata('EType');
        $CNo = $this->session->userdata('CNo');
        $PymtOpt = $this->session->userdata('PymtOpt');
        $KOTNo = $this->session->userdata('KOTNo');
        $TableNo = $this->session->userdata('TableNo');
        $COrgId = $this->session->userdata('COrgId');
    
        $Fest = $this->session->userdata('Fest');
        $ServChrg = $this->session->userdata('ServChrg');

        // print_r($TipAmount);
        // exit;

        if ($CNo == 0 && $KOTNo == 0) {
            redirect(base_url('customer'));
        }

        // mode decide to this code 19-aug-2023

        $payable = base64_decode(rtrim($_GET['payable'], "="));
        $tips = base64_decode(rtrim($_GET['tips'], "="));
        $itemTotalGross = base64_decode(rtrim($_GET['totAmt'], "="));

        $tips = !empty($tips)?$tips:0;
        $this->session->set_userdata('TipAmount', $tips);
        $this->session->set_userdata('itemTotalGross', $itemTotalGross);

        $totalAmount = round($payable, 2);
        $orderId = "$EID-$TableNo-$CustId-$CNo-$totalAmount";

        $this->session->set_userdata('rez_totalAmount', $totalAmount);

        // We create an razorpay order using orders api
        // Docs: https://docs.razorpay.com/docs/orders
        //
        $orderData = [
            'receipt'         => $orderId,
            'amount'          => $totalAmount * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $api->order->create($orderData);
        $razorpayOrderId = $razorpayOrder['id'];
        $_SESSION['razorpay_order_id'] = $razorpayOrderId;

        $displayAmount = $amount = $orderData['amount'];

        if ($displayCurrency !== 'INR') {
            $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
            $exchange = json_decode(file_get_contents($url), true);

            $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
        }

        $checkout = 'automatic';

        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
            $checkout = $_GET['checkout'];
        }

        $data = [
            "key"               => $keyId,
            "amount"            => $amount,
            "name"              => "Quick Service",
            "description"       => "Food App",
            "image"             => base_url('assets_admin/')."images/QSLogo.png",
            "prefill"           => [
                "name"              => "Test User",
                "email"             => "test@gmail.com",
                "contact"           => "1234567890",
            ],
            "notes"             => [
                "address"           => "Testing",
                "merchant_order_id" => "12312321",
            ],
            "theme"             => [
                "color"             => "#F37254"
            ],
            "order_id"          => $razorpayOrderId,
        ];

        if ($displayCurrency !== 'INR') {
            $data['display_currency']  = $displayCurrency;
            $data['display_amount']    = $displayAmount;
        }

        // echo "<pre>";
        // print_r($data);
        // die;
        $json = json_encode($data);
        require_once APPPATH.'third_party/checkout/'.$checkout.'.php';
        // require("checkout/{$checkout}.php");
    }

    public function verify(){
        $this->load->view('payment/verify');
    }

    public function handle_payment(){

        //         require_once 'crud/Food.class.php';
        // require_once 'class/Session.Class.php';
        // // GenTableData db objec
        // require_once 'db/tables/CustPymt.class.php';
        // require_once 'db/tables/GenRating.class.php';
        // require_once 'db/tables/GenRatingDet.class.php';
        $string_array = array();
        if($_GET){
            $string = str_replace('{','',$_GET['get']);
            $string = str_replace('"','',$string);
            $string_array = explode(",",$string);
        }
            
        if(!empty($string_array)){
            foreach ($string_array as $key => $value) {
                $string_array_1 = explode(":",$value);
                $this->session->set_userdata($string_array_1[0],$string_array_1[1]);
            }
        }

        $EID = authuser()->EID;
        $CNo = $this->session->userdata('CNo');   
        

        $orderId = $_POST["orderId"];
        $totalAmount = $_POST["orderAmount"];
        $referenceId = $_POST["referenceId"];
        $txStatus = $_POST["txStatus"];
        $paymentMode = $_POST["paymentMode"];
        $txMsg = $_POST["txMsg"];
        $txTime = $_POST["txTime"];
        $signature = $_POST["signature"];

        if ($txStatus == "SUCCESS") {

            // get repository  : payment/payment.repo.php
            // include('repository/payment/payment.repo.php');

            $res = billCreate($EID, $CNo, $_POST);
            if($res['status'] == 1){
                redirect(base_url('customer/bill/'.$res['billId']));
            }else{
                echo json_encode($res);
                die();
            }

        } else {
            echo "Payment Fail";
        }

    }

}
