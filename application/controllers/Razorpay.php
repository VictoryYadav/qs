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
        $ChainId = authuser()->ChainId;
        $CustId = $this->session->userdata('CustId');
        $ONo = $this->session->userdata('ONo');
        $EType = $this->session->userdata('EType');
        $CNo = $this->session->userdata('CNo');
        $PymtOpt = $this->session->userdata('PymtOpt');
        $KOTNo = $this->session->userdata('KOTNo');
        $TableNo = authuser()->TableNo;
        $COrgId = $this->session->userdata('COrgId');
        $CustNo = $this->session->userdata('CustNo');
        $Tips = $this->session->userdata('Tips');
        $CellNo = $this->session->userdata('CellNo');

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
            $res = getBillingDataByEID_CNo($EID, $CNo);
            
            if (empty($res['kitcheData'])) {
                $response = [
                    "status" => 0,
                    "msg" => "No BILL CREATION REQUIRED "
                ];
                echo json_encode($response);
                die();
            } else {

                $lastBillNo = $this->db2->query("SELECT max(BillNo) as BillNo from Billing where EID = $EID")->row_array();

                if ($lastBillNo['BillNo'] == '') {
                    $newBillNo = 1;
                } else {
                    $newBillNo = $lastBillNo['BillNo'] + 1;
                }

                $TotItemDisc    = $res['kitcheData'][0]['TotItemDisc'];
                $TotPckCharge   = $res['kitcheData'][0]['TotPckCharge'];
                $DelCharge      = $res['kitcheData'][0]['DelCharge'];
                $BillDiscAmt    = $res['kitcheData'][0]['BillDiscAmt'];
                
                $TipAmount = $this->session->userdata('TipAmount');
                $itemTotalGross = $this->session->userdata('itemTotalGross');
                // FOR ONLINE PAYMENTS
                $billingObj['EID'] = $EID;
                $billingObj['TableNo'] = $TableNo;
                $billingObj['ChainId'] = $ChainId;
                $billingObj['ONo'] = $ONo;
                $billingObj['CNo'] = $CNo;
                $billingObj['BillNo'] = $newBillNo;
                $billingObj['CustId'] = $CustId;
                $billingObj['COrgId'] = $COrgId;
                $billingObj['CustNo'] = $CustNo;
                $billingObj['TotAmt'] = $itemTotalGross;
                $billingObj['PaidAmt'] = $totalAmount;
                $billingObj['SerCharge'] = $res['kitcheData'][0]['ServChrg'];
                $billingObj['SerChargeAmt'] = round(($itemTotalGross * $res['kitcheData'][0]['ServChrg']) /100 ,2);
                $billingObj['Tip'] = $TipAmount;
                $billingObj['PaymtMode'] = $paymentMode;
                $billingObj['PymtRef'] = $orderId;
                $billingObj['TotItemDisc'] = $TotItemDisc;
                $billingObj['BillDiscAmt'] = $BillDiscAmt;
                $billingObj['TotPckCharge'] = $TotPckCharge;
                $billingObj['DelCharge'] = $DelCharge;
                $billingObj['PymtType'] = 0;
                $billingObj['Stat'] = 1;

                $this->db2->trans_start();
            
                    $lastInsertBillId = insertRecord('Billing', $billingObj);

                    foreach ($res['taxDataArray'] as $key => $value1) {
                        foreach ($value1 as $key => $value) {
                            $BillingTax['BillId'] = $lastInsertBillId;
                            $BillingTax['TNo'] = $value['TNo'];
                            $BillingTax['TaxPcent'] = $value['TaxPcent'];
                            $BillingTax['TaxAmt'] = $value['SubAmtTax'];
                            $BillingTax['EID'] = $EID;
                            $BillingTax['TaxIncluded'] = $value['Included'];
                            $BillingTax['TaxType'] = $value['TaxType'];
                            insertRecord('BillingTax', $BillingTax);
                            // $BillingTax['create();
                        }
                    }

                    $genTblDb = $this->load->database('GenTableData', TRUE);
                    // store to gen db
                    $custPymtObj['BillId'] = $lastInsertBillId;
                    $custPymtObj['CustId'] = $CustId;
                    $custPymtObj['BillNo'] = $newBillNo;
                    $custPymtObj['EID'] = $EID;
                    $custPymtObj['PaidAmt'] = $totalAmount;
                    $custPymtObj['PaymtMode'] = $paymentMode;
                    $genTblDb->insert('CustPymts', $custPymtObj);
                    
                    $as = ($this->session->userdata('AutoSettle') == 1)?0:1;
                    $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.BillStat = $lastInsertBillId, k.Stat = 9, k.payRest = ".$as."  WHERE (k.Stat<>4 AND k.Stat<>6 AND k.Stat<>7  AND k.Stat<>99)  AND k.EID = $EID AND km.EID = k.EID AND ( (km.CNo = $CNo OR km.MCNo = $CNo) AND ((km.TableNo = $TableNo AND km.CustId = $CustId) OR (k.MergeNo = km.MergeNo)) )  AND km.BillStat = 0 AND k.CNo = km.CNo");

                    $this->db2->query("UPDATE KitchenMain SET BillStat = $lastInsertBillId, Stat = 9, payRest = ".$as." WHERE (CNo = $CNo OR MCNo = $CNo) AND ((TableNo = $TableNo AND CustId = $CustId) OR (MergeNo  = $TableNo)) AND BillStat = 0 AND EID = $EID ");

                    if ($EType == 5) {
                        $stat = 9;
                         $this->db2->query("DELETE from Eat_tables_Occ where EID=$EID and CNo = $CNo AND ((TableNo = '$TableNo' AND CustId = $CustId) OR (MergeNo = '$TableNo'))");

                         $this->db2->query("UPDATE Eat_tables SET Stat = 0 WHERE EID = $EID AND ((TableNo = '$TableNo') OR (MergeNo = '$TableNo'))");
                    }

                    // gen db
                    $genCheckid = $genTblDb->query("SELECT RCd  FROM `Ratings` WHERE EID = $EID AND BillId = $lastInsertBillId AND CustId = $CustId AND CellNo = $CellNo")->result_array();

                    // gen db
                    if (!empty($genCheckid)) {
                        $RCd = $genCheckid[0]['RCd'];
                        $genTblDb->query("DELETE FROM `Ratings` WHERE EID = $EID AND BillId = $lastInsertBillId AND CustId = $CustId AND CellNo = $CellNo");

                        $genTblDb->query("DELETE FROM `RatingDet` WHERE RCd = $RCd");
                    }
                    // gen db
                    $gndbRat['EID']     =   $EID;
                    $gndbRat['ChainId'] =   $ChainId; 
                    $gndbRat['BillId']  =   $lastInsertBillId; 
                    $gndbRat['CustId']  =   $CustId;
                    $gndbRat['CellNo']  =   $CellNo; 
                    $gndbRat['Remarks'] =   '-'; 
                    $gndbRat['ServRtng']=   0;
                    $gndbRat['AmbRtng'] =   0;
                    $gndbRat['VFMRtng'] =   0;
                    $gndbRat['LstModDt']=   date('Y-m-d H:i:s');
                    $genTblDb->insert('Ratings', $gndbRat);
                    $genRCd = $genTblDb->insert_id();

                    $kitcheItemData = $this->db2->where_not_in('Stat', array(4,6,7,99,10))
                                                ->get_where('Kitchen', array(
                                                'BillStat' => $lastInsertBillId,
                                                'EID' => $EID, 
                                                'CNo' => $CNo)
                                            )->result_array();
                    // gen table
                    $queryStringGen = '';
                    for ($i = 0; $i < count($kitcheItemData); $i++) {
                        if ($i >= 1) {
                            $queryStringGen .= ',';
                        }
                        $queryStringGen .= '(' . $genRCd . ',' . $kitcheItemData[$i]['ItemId'] . ',' . 0 . ')';
                    }
                    // gen table
                    $RatingDetQuery = $genTblDb->query("INSERT INTO `RatingDet`(RCd,ItemId,ItemRtng) VALUES $queryStringGen ");

                    // header("Location: bill_rcpt.php?billId=$lastInsertBillId");
                $this->db2->trans_complete();

                $this->session->set_userdata('KOTNo', 0);
                $this->session->set_userdata('CNo', 0);
                $this->session->set_userdata('itemTotalGross', 0);

                redirect(base_url('customer/bill/'.$lastInsertBillId));
            }
        } else {
            echo "Payment Fail";
        }

    }

}
