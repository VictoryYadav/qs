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
        // test
        $keyId = 'rzp_test_Z50p6ZM95VvlSy';
        $keySecret = 'gtQvvO7aRLLoMefAWCV7Pcwp';
        if($this->session->userdata('pymtENV') > 0){
            // live
            $keyId = 'rzp_live_orBqBQnXLQF7i9';
            $keySecret = 'gFjtLHcPZIZ9S81lrjMHyeDi';
        }

        $displayCurrency = 'INR';

        $api = new Api($keyId, $keySecret);

        $EID = $this->session->userdata('EID');        
        $CustId = $this->session->userdata('CustId');
        $TableNo = $this->session->userdata('TableNo');
        
        $payable = base64_decode(rtrim($_GET['payable'], "="));
        $billId = base64_decode(rtrim($_GET['billId'], "="));
        $MCNo = base64_decode(rtrim($_GET['MCNo'], "="));

        $pageurl = base64_decode(rtrim($_GET['pageurl'], "="));
        $this->session->set_userdata('pageurl', $pageurl);

        if (empty($billId)) {
            redirect(base_url('customer'));
        }

        $totalAmount = round($payable, 2);

        $orderId = "$EID-$TableNo-$CustId-$billId-$totalAmount";

        $this->session->set_userdata('rez_totalAmount', $totalAmount);
        // We create an razorpay order using orders api
        // Docs: https://docs.razorpay.com/docs/orders
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
            "MCNo"              => $MCNo,
            "billId"            => $billId,
            "billRef"           => $orderId,
            "name"              => "Eat-Out",
            "description"       => "Eat-Out",
            "image"             => base_url('theme/')."images/Eat-Out-Icon.png",
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

        $json = json_encode($data);
        require_once APPPATH.'third_party/checkout/'.$checkout.'.php';
        // require("checkout/{$checkout}.php");
    }

    public function verify(){
        $this->load->view('payment/verify');
    }

    public function handle_payment(){

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

        $EID = $this->session->userdata('EID');
        $CNo = $_POST["MCNo"];   

        $orderId = $_POST["orderId"];
        $totalAmount = $_POST["orderAmount"];
        $referenceId = $_POST["referenceId"];
        $txStatus = $_POST["txStatus"];
        $paymentMode = $_POST["paymentMode"];
        // $txMsg = $_POST["txMsg"];
        $txTime = $_POST["txTime"];
        $signature = $_POST["signature"];

        if ($txStatus == "SUCCESS") {

            if($this->session->userdata('splitType')==1){
                $bd = $this->db2->select("BillId, PaidAmt")
                                ->get_where('Billing', array('EID' =>$EID, 'CNo' => $CNo, 'splitTyp' => 1, 'payRest' => 0))
                                ->result_array();
                if(!empty($bd)){
                    foreach ($bd as $key) {
                        $pay['BillId'] = $key['BillId'];
                        $pay['MCNo'] = $CNo;
                        $pay['MergeNo'] = $this->session->userdata('TableNo');
                        $pay['TotBillAmt'] = $key['PaidAmt'];
                        $pay['CellNo'] = $this->session->userdata('CellNo');
                        $pay['SplitTyp'] = $this->session->userdata('splitType');
                        $pay['SplitAmt'] = 0;
                        $pay['PymtId'] = 0;
                        $pay['PaidAmt'] = $key['PaidAmt'];
                        $pay['OrderRef'] = $orderId;
                        $pay['PaymtMode'] = 5;
                        $pay['PymtType'] = 0;
                        $pay['PymtRef'] = $referenceId;
                        $pay['Stat'] = 1;
                        $pay['EID'] = $EID;
                        $pay['billRef'] = $_POST['billRef'];
                        $payNo = insertRecord('BillPayments', $pay);
                    }
                }
            }else{
                $pay['BillId'] = $_POST['billId'];
                $pay['MCNo'] = $CNo;
                $pay['MergeNo'] = $this->session->userdata('TableNo');
                $pay['TotBillAmt'] = $totalAmount;
                $pay['CellNo'] = $this->session->userdata('CellNo');
                $pay['SplitTyp'] = 0;
                $pay['SplitAmt'] = 0;
                $pay['PymtId'] = 0;
                $pay['PaidAmt'] = $totalAmount;
                $pay['OrderRef'] = $orderId;
                $pay['PaymtMode'] = 5;
                $pay['PymtType'] = 0;
                $pay['PymtRef'] = $referenceId;
                $pay['Stat'] = 1;
                $pay['EID'] = $EID;
                $pay['billRef'] = $_POST['billRef'];
                $payNo = insertRecord('BillPayments', $pay);
            }

            $pageurl = $this->session->userdata('pageurl');
            if($_POST['billId'] > 0){
                if($pageurl == 'user'){
                    redirect(base_url('users/pay/'.$_POST['billId'].'/'.$_POST["MCNo"]));    
                }
                redirect(base_url('customer/pay/'.$_POST['billId'].'/'.$_POST["MCNo"]));
            }
            redirect(base_url('customer'));

        } else {
            echo "Payment Fail";
        }
    }

}
