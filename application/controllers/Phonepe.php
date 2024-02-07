<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Phonepe extends CI_Controller {

    private $merchantId;
    private $saltKey;
    private $saltIndex;
    private $db2;

    public function __construct()
    {
        parent::__construct();

        // if ($this->session->userdata('logged_in')) {
        //     $this->authuser = $this->session->userdata('logged_in');
        // } else {
        //     redirect(base_url());
        // }

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);

        // $this->load->model('Cust', 'cust');

        // test
        $this->merchantId = 'PGTESTPAYUAT140';
        $this->saltKey = '775765ff-824f-4cc4-9053-c3926e493514';

        // live
        // if($this->session->userdata('pymtENV') > 0){
        //     $this->merchantId = 'VTRENDONLINE';
        //     $this->saltKey = '95d084d8-38f0-4d64-91ff-24449f8e911e';
        // }
        $this->saltIndex = 1;
    }

    public function pay_old(){


$merchantId = 'PGTESTPAYUAT140'; // sandbox or test merchantId
$apiKey="775765ff-824f-4cc4-9053-c3926e493514"; // sandbox or test APIKEY
$redirectUrl = base_url('test/pay_success');

// Set transaction details
$order_id = uniqid(); 
$name="Tutorials Website";
$email="info@tutorialswebsite.com";
$mobile=9999999999;
$amount = 10; // amount in INR
$description = 'Payment for Product/Service';


$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => "MT7850590068188104", // test transactionID
    "merchantUserId"=>"MUID123",
    'amount' => $amount*100,
    'redirectUrl'=>$redirectUrl,
    'redirectMode'=>"POST",
    'callbackUrl'=>$redirectUrl,
    "merchantOrderId"=>$order_id,
   "mobileNumber"=>$mobile,
   "message"=>$description,
   "email"=>$email,
   "shortName"=>$name,
   "paymentInstrument"=> array(    
    "type"=> "PAY_PAGE",
  )
);


 $jsonencode = json_encode($paymentData);
 $payloadMain = base64_encode($jsonencode);
 $salt_index = 1; //key index 1
 $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
 $sha256 = hash("sha256", $payload);
 $final_x_header = $sha256 . '###' . $salt_index;
 $request = json_encode(array('request'=>$payloadMain));
                
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => $request,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
     "X-VERIFY: " . $final_x_header,
     "accept: application/json"
  ],
]);

$response = curl_exec($curl);
echo "<pre>";
print_r($response);
die;

$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res = json_decode($response);
 
if(isset($res->success) && $res->success=='1'){
$paymentCode=$res->code;
$paymentMsg=$res->message;
$payUrl=$res->data->instrumentResponse->redirectInfo->url;

header('Location:'.$payUrl) ;


}
}

    }

    public function pay(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $billId = $_POST['billId'];
            $MCNo = $_POST['MCNo'];
            $totalAmount = $_POST['amount'];
            $mode = $_POST['mode'];

            $EID = authuser()->EID;
            $TableNo = authuser()->TableNo;
            $CustId = $this->session->userdata('CustId');

            $rUrl = base_url('phonepe');

            $orderRef = "$mode-$EID-$TableNo-$MCNo-$CustId-$billId-$totalAmount";

            $data = array(
              "merchantId" => $this->merchantId,
              "merchantTransactionId" => $orderRef,
              "merchantUserId" => "MUID123",
              "amount" => $totalAmount * 100,
              "redirectUrl" => $rUrl.'/pay_success',
              "redirectMode" => "POST",
              "callbackUrl" => $rUrl.'/pay_failed',
              "mobileNumber" => "7869068343",
              "paymentInstrument" => array(
                "type" => "PAY_PAGE"
                )
            );

            $encode = json_encode($data);
            $encoded = base64_encode($encode);

            $string = $encoded."/pg/v1/pay".$this->saltKey;
            $sha256 = hash('sha256', $string);
            $finalHeader = $sha256."###".$this->saltIndex;
            // echo "<pre>";
            // print_r($encode);
        
            // echo "<br>";
            // print_r($finalHeader);
            // die;
            // curl
            $curl = curl_init();
            // test
            curl_setopt($curl, CURLOPT_URL, "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");

            // live
            // curl_setopt($curl, CURLOPT_URL, "https://api.phonepe.com/apis/hermes/pg/v1/pay");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "accept: application/json",
                'X-VERIFY: '.$finalHeader 
              )
            );
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array('request' => $encoded)));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            $result = json_decode($response);

            // echo "<pre>";
            // print_r($result);
            // die;
            if($result->success == 1){
                $status = 'success';
                $payUrl = $result->data->instrumentResponse->redirectInfo->url;
            }else{
                $payUrl = 'Transaction Failed';
            }
            // print_r($payUrl);
            // die;
            // redirect($payUrl);

            // $err = curl_error($curl);
            // curl_close($curl);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $payUrl
              ));
             die;
        }        
    }

    public function pay_success(){
        
        $resp = $_POST;
        // echo "<pre>";
        // print_r($resp);
        // die;

        // $this->payment_status($resp['merchantId'], $resp['transactionId']);
        // print_r($resp['transactionId']);
        // print_r($SESSION);
        // print_r($this->session->userdata('CellNo'));
        // die;
        // $_POST['amount']
        $d = explode('-', $resp['transactionId']);
        echo "<pre>";
        print_r($d);
        die;

        $TableNo = $d[2];
        $MCNo = $d[3];
        $billId = $d[5];
        $TotBillAmt = $d[6];

        // $pay['BillId'] = $billId;
        // $pay['MCNo'] = $MCNo;
        // $pay['MergeNo'] = $TableNo;
        // $pay['TotBillAmt'] = $TotBillAmt / 100;
        // $pay['CellNo'] = $this->session->userdata('CellNo');
        // $pay['SplitTyp'] = 0;
        // $pay['SplitAmt'] = 0;
        // $pay['PymtId'] = 0;
        // $pay['PaidAmt'] = $TotBillAmt;
        // $pay['OrderRef'] = $resp['transactionId'];
        // $pay['PaymtMode'] = 'phonepe';
        // $pay['PymtType'] = 0;
        // $pay['PymtRef'] = $resp['providerReferenceId'];
        // $pay['Stat'] = 1;
        // $pay['EID'] = authuser()->EID;

        // echo "<pre>";
        // print_r($pay);
        // die;
        // $payNo = insertRecord('BillPayments', $pay);

        redirect(base_url('customer/pay/'.$billId.'/'.$MCNo));

    }

    public function pay_failed(){
        echo "failed payment";
        die;
    }

    public function payment_status($merchantId, $transactionId){

        $string = "/pg/v1/status/".$merchantId.'/'.$transactionId.$this->saltKey;
        $sha256 = hash('sha256', $string);
        $finalHeader = $sha256."###".$this->saltIndex;

        // curl
        $curl = curl_init();
        // test
        curl_setopt($curl, CURLOPT_URL, "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/".$merchantId."/".$transactionId);

        // live
        // curl_setopt($curl, CURLOPT_URL, "https://api.phonepe.com/apis/hermes/pg/v1/status/".$merchantId."/".$transactionId);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "accept: application/json",
            'X-VERIFY: '.$finalHeader,
            'X-MERCHANT-ID:'.$merchantId 
          )
        );
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $result = json_decode($response);
        // echo "<pre>";
        // print_r($result);
        // print_r($result->success);
        // die;

        if($result->success == 1){

            $d = explode('-', $transactionId);

            $TableNo = $d[2];
            $MCNo = $d[3];
            $billId = $d[5];
            $TotBillAmt = $d[6];

            $pay['BillId'] = $billId;
            $pay['MCNo'] = $MCNo;
            $pay['MergeNo'] = $TableNo;
            $pay['TotBillAmt'] = $TotBillAmt / 100;
            $pay['CellNo'] = $this->session->userdata('CellNo');
            $pay['SplitTyp'] = 0;
            $pay['SplitAmt'] = 0;
            $pay['PymtId'] = 0;
            $pay['PaidAmt'] = $TotBillAmt;
            $pay['OrderRef'] = $transactionId;
            $pay['PaymtMode'] = 1;
            $pay['PymtType'] = 0;
            $pay['PymtRef'] = $result->data->transactionId;
            $pay['Stat'] = 1;
            $pay['EID'] = $this->session->userdata('EID');
                // echo "<pre>";
                // print_r($pay);
                // die;
            // print_r($this->session->userdata('my_db'));
            
            $payNo = insertRecord('BillPayments', $pay);
        }else{
            echo "Transaction failed!!";
            die;
        }

        redirect(base_url('customer/pay/'.$billId.'/'.$MCNo));
    }

    public function test(){

        $pay['BillId'] = 1;
            $pay['MCNo'] = 2;
            $pay['MergeNo'] = 22;
            $pay['TotBillAmt'] = 2;
            $pay['CellNo'] = '9988766554';
            $pay['SplitTyp'] = 0;
            $pay['SplitAmt'] = 0;
            $pay['PymtId'] = 0;
            $pay['PaidAmt'] = 2;
            // $pay['OrderRef'] = '46-51-22-13-8-13-2';
            $pay['PaymtMode'] = 1;
            $pay['PymtType'] = 0;
            // $pay['PymtRef'] = 'T2311091329142129036638';
            $pay['Stat'] = 1;
            $pay['EID'] = 51;
            echo "<pre>";
            print_r($pay);
            insertRecord('BillPayments', $pay);
    }

}
