<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Phonepe extends CI_Controller {

    private $db2;
    private $merchantId;
    private $saltKey;
    private $saltIndex;

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

        $this->merchantId = 'PGTESTPAYUAT140';
        $this->saltKey = '775765ff-824f-4cc4-9053-c3926e493514';
        $this->saltIndex = 1;
    }

    public function pay(){

        $rUrl = base_url('phonepe');

        $data = array(
          "merchantId" => $this->merchantId,
          "merchantTransactionId" => "MT7850590068188104",
          "merchantUserId" => "MUID123",
          "amount" => 10000,
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

        // curl
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");
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

        $payUrl = $result->data->instrumentResponse->redirectInfo->url;
        // print_r($payUrl);
        // die;
        redirect($payUrl);

        // $err = curl_error($curl);
        // curl_close($curl);

    }

    public function pay_success(){
        $resp = $_POST;
        echo "<pre>";
        print_r($resp);
        die;

        $this->payment_status($resp['merchantId'], $resp['transactionId']);
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

        curl_setopt($curl, CURLOPT_URL, "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/".$merchantId."/".$transactionId);
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
        echo "<pre>";
        print_r($result);
        die;
    }

}
