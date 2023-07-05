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
        $Tips = $this->session->userdata('Tips');
        $PymtOpt = $this->session->userdata('PymtOpt');
        $KOTNo = $this->session->userdata('KOTNo');
        $TableNo = $this->session->userdata('TableNo');
        $COrgId = $this->session->userdata('COrgId');
        $TipAmount = $this->session->userdata('TipAmount');
        $Fest = $this->session->userdata('Fest');
        $ServChrg = $this->session->userdata('ServChrg');

        // print_r($TipAmount);
        // exit;

        if ($CNo == 0 && $KOTNo == 0) {
            echo '<script>
            window.location.replace("customer_landing_page.php");
            </script>';
        }

        $payable = base64_decode(rtrim($_GET['payable'], "="));

        $tips = base64_decode(rtrim($_GET['tips'], "="));
        // print_r($payable);exit();
        $totalAmount = round(($payable + $tips), 2);
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
        echo "string";
        die;

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
// print_r($_GET['get']);exit;
    

foreach ($string_array as $key => $value) {
    $string_array_1 = explode(":",$value);
    $this->session->set_userdata($string_array_1[0],$string_array_1[1]);
}

$EID = authuser()->EID;
$ChainId = authuser()->ChainId;
$CustId = $this->session->userdata('CustId');
$ONo = $this->session->userdata('ONo');
$EType = $this->session->userdata('EType');
$CNo = $this->session->userdata('CNo');
$PymtOpt = $this->session->userdata('PymtOpt');
$KOTNo = $this->session->userdata('KOTNo');
$TableNo = $this->session->userdata('TableNo');
$COrgId = $this->session->userdata('COrgId');
$CustNo = $this->session->userdata('CustNo');
$Tips = $this->session->userdata('Tips');
$CellNo = $this->session->userdata('CellNo');

$orderId = $_POST["orderId"];
$paidAmount = $_POST["orderAmount"];
$referenceId = $_POST["referenceId"];
$txStatus = $_POST["txStatus"];
$paymentMode = $_POST["paymentMode"];
$txMsg = $_POST["txMsg"];
$txTime = $_POST["txTime"];
$signature = $_POST["signature"];

if ($txStatus == "SUCCESS") {

    // get repository  : payment/payment.repo.php
    // include('repository/payment/payment.repo.php');

    if (empty($kitcheData)) {
        $response = [
            "status" => 0,
            "msg" => "No BILL CREATION REQUIRED "
        ];
        echo json_encode($response);
        die();
    } else {

        $lastBillNo = $this->db2->query("SELECT max(BillNo) as BillNo from Billing where EID = $EID")->result_array();

        if ($lastBillNo[0]['BillNo'] == '') {
            $newBillNo = 1;
        } else {
            $newBillNo = $lastBillNo[0]['BillNo'] + 1;
        }

        $TotItemDisc = $kitcheData[0]['TotItemDisc'];
        $TotPckCharge = $kitcheData[0]['TotPckCharge'];
        $DelCharge = $kitcheData[0]['DelCharge'];
        $BillDiscAmt = $kitcheData[0]['BillDiscAmt'];

        if ($Tips == 0) {
            $TipAmount = 0;
        } else {
            $TipAmount = $this->session->userdata('TipAmount');
        }

        if ($ServChrg == 0) {
            $serviceCharge = 0;
        } else {
            $serviceCharge = $kitcheData[0]['ServChrg'] * ($orderAmount / 100);
        }

        $totalAmount = $orderAmount + $TipAmount + $serviceCharge;
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
        $billingObj['TotAmt'] = $totalAmount;
        $billingObj['PaidAmt'] = $paidAmount;
        $billingObj['SerCharge'] = $ServChrg;
        $billingObj['Tip'] = $TipAmount;
        $billingObj['PaymtMode'] = $paymentMode;
        $billingObj['PymtRef'] = $orderId;
        $billingObj['TotItemDisc'] = $TotItemDisc;
        $billingObj['BillDiscAmt'] = $BillDiscAmt;
        $billingObj['TotPckCharge'] = $TotPckCharge;
        $billingObj['DelCharge'] = $DelCharge;
    
        $DB->beginTransaction();
        try {
            $billingObj->create();
            $lastInsertBillId = $billingObj->lastInsertId();

            foreach ($taxDataArray as $key => $value1) {
                foreach ($value1 as $key => $value) {
                    $BillingTax['BillId'] = $lastInsertBillId;
                    $BillingTax['TNo'] = $value['TNo'];
                    $BillingTax['TaxPcent'] = $value['TaxPcent'];
                    $BillingTax['TaxAmt'] = $value['SubAmtTax'];
                    $BillingTax['EID'] = $EID;
                    $BillingTax['TaxIncluded'] = $value['Included'];
                    $BillingTax['TaxType'] = $value['TaxType'];
                    // $BillingTax['create();
                }
            }
            
            if ($EType == 1) {
                
                $kitchenUpdate = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.BillStat = $lastInsertBillId, k.Stat = $stat WHERE (km.CNo=$CNo OR km.MCNo = $CNo) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (k.Stat<>4 and k.Stat<>6 and k.Stat<>7 and k.Stat<>99)");

                //STAT updated KitchenMain for QSR as it is not required in Bill Settlement
                $kitchenMainUpdate = $this->db2->query("UPDATE KitchenMain km SET km.BillStat = $lastInsertBillId, km.Stat = 1 WHERE (km.CNo=$CNo OR km.MCNo = $CNo) AND  km.EID = $EID");

                $billData = $this->db2->query("UPDATE Billing SET Stat = 1 WHERE BillId = $lastInsertBillId AND EID = $EID");

                // store to gen db
                $custPymtObj['BillId'] = $lastInsertBillId;
                $custPymtObj['CustId'] = $CustId;
                $custPymtObj['BillNo'] = $newBillNo;
                $custPymtObj['EID'] = $EID;
                $custPymtObj['PaidAmt'] = $totalAmount;
                $custPymtObj['PaymtMode'] = $paymentMode;
                // $custPymtObj->create();
            } else {

                $stat = 9;
                $as = ($this->session->userdata('AutoSettle') == 1)?0:1;
                $kitchenUpdate = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.BillStat = $lastInsertBillId, k.Stat = 9, k.payRest = ".$as."  WHERE (k.Stat<>4 AND k.Stat<>6 AND k.Stat<>7  AND k.Stat<>99)  AND k.EID = $EID AND km.EID = k.EID AND ( (km.CNo = $CNo OR km.MCNo = $CNo) AND ((km.TableNo = $TableNo AND km.CustId = $CustId) OR (k.MergeNo = km.MergeNo)) )  AND km.BillStat = 0 AND k.CNo = km.CNo");
                $kitchenMainUpdate = $this->db2->query("UPDATE KitchenMain SET BillStat = $lastInsertBillId, payRest = ".$as." WHERE (CNo = $CNo OR MCNo = $CNo) AND ((TableNo = $TableNo AND CustId = $CustId) OR (MergeNo  = $TableNo)) AND BillStat = 0 AND EID = $EID ");

                // store to gen db
                $custPymtObj['BillId'] = $lastInsertBillId;
                $custPymtObj['CustId'] = $CustId;
                $custPymtObj['BillNo'] = $newBillNo;
                $custPymtObj['EID'] = $EID;
                $custPymtObj['PaidAmt'] = $totalAmount;
                $custPymtObj['PaymtMode'] = $paymentMode;
                // $custPymtObj->create();
            }

            // insert rating in gen db
            $kitcheItemData = $kitchenObj->search(['BillStat' => $lastInsertBillId]);

            // gen db
            $genCheckid = $this->db2->query("SELECT RCd  FROM `Ratings` WHERE EID = $EID AND BillId = $lastInsertBillId AND CustId = $CustId AND CellNo = $CellNo")->result_array();

            // gen db
            if (!empty($genCheckid)) {
                $RCd = $genCheckid[0]['RCd'];
                $deleteRating = $genRatingObj->exec("DELETE FROM `Ratings` WHERE EID = $EID AND BillId = $lastInsertBillId AND CustId = $CustId AND CellNo = $CellNo");
                $deleteRatingDet = $this->db2->query("DELETE FROM `RatingDet` WHERE RCd = $RCd");
            }

            // gen db
            $RatingInsert = $this->db2->query("INSERT INTO `Ratings`(`EID`, `ChainId`, `BillId`, `CustId`, `CellNo`, `Remarks`, `ServRtng`, `AmbRtng`, `VFMRtng`, `LstModDt`) VALUES ($EID,$ChainId,$lastInsertBillId,$CustId,$CellNo,'-',0,0,0,date(now()))");

            // gen db
            $genRCd = $genRatingObj->lastInsertId();

            // gen table
            $queryStringGen = '';
            for ($i = 0; $i < count($kitcheItemData); $i++) {
                if ($i >= 1) {
                    $queryStringGen .= ',';
                }
                $queryStringGen .= '(' . $genRCd . ',' . $kitcheItemData[$i]['ItemId'] . ',' . 0 . ')';
            }

            // gen table
            $RatingDetQuery = $this->db2->query("INSERT INTO `RatingDet`(RCd,ItemId,ItemRtng) VALUES $queryStringGen ");
            if($_SESSION['AutoSettle'] == 5){
                $paidAmount = $paidAmount;
                $id = $lastInsertBillId;
                $mode = $paymentMode;
                $cNo = $CNo;
                $billData = $this->db2->query("UPDATE Billing SET PaidAmt = $paidAmount, PaymtMode = '".$mode."', PymtType = 0, Stat = 1  WHERE BillId = $id AND EID = $EID");

                if($EType == 1){
                    
                    $stat = 1;

                    //Session::set('KOTNo',0);
                    $kitchenUpdate = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET  k.Stat = $stat WHERE km.BillStat = $id AND (k.Stat<>4 and k.Stat<>6 and k.Stat<>7  AND k.Stat<>99) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = $CNo OR km.MCNo = $CNo)");
                    // print_r($ku);
                    
                }

                if ($EType == 5) {
                   

                    $stat = 9;


                   
                     $this->db2->query("DELETE from Eat_tables_Occ where EID=$EID and CNo = $CNo AND ((TableNo = '$TableNo' AND CustId = $CustId) OR (MergeNo = '$TableNo'))");
                    
                   
                     $this->db2->query("UPDATE Eat_tables SET Stat = 0 WHERE EID = $EID AND ((TableNo = '$TableNo') OR (MergeNo = '$TableNo'))");
                    
                }

                
                $this->db2->query("UPDATE KitchenMain SET  Stat = 1 WHERE BillStat = $id AND EID = $EID AND (CNo = $CNo OR MCNo = $CNo)");
                
                // store to gen db
                $custPymtObj['BillId'] = $id;
                $custPymtObj['BillNo'] = $newBillNo;
                $custPymtObj['EID'] = $EID;
                $custPymtObj['PaidAmt'] = $paidAmount;
                $custPymtObj['PaymtMode'] = $paymentMode;
                // $custPymtObj->create();
            }
            $DB->executeTransaction();

            $this->session->set_userdata('KOTNo', 0);

            header("Location: bill_rcpt.php?billId=$lastInsertBillId");
        } catch (Exception $e) {
            echo $e->getMessage();
            $DB->rollBack();
            exit;
        }
    }
} else {
    echo "Payment Fail";
}






    }

}
