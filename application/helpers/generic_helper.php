<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	function email_check($email){
		$CI = & get_instance();
		$CI->load->model('User');
	    return $CI->User->get_by_email($email) ? TRUE : FALSE;
	}

	function contact_check($no){
	    $CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->get_by_contactno($no) ? TRUE : FALSE;
	}

	function insertRecord($tbl,$data){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->recordInsert($tbl,$data);
	}

	function updateRecord($tbl,$data, $where){
		$CI = & get_instance();
		$CI->load->model('User');
		$CI->User->recordUpdate($tbl,$data, $where);
	}

	function deleteRecord($tbl,$where){
		$CI = & get_instance();
		$CI->load->model('User');
		$CI->User->recordDelete($tbl,$where);	
	}

	function countRecord($tbl){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->countAllRecord($tbl);
	}

	function getRecords($tbl=null,$where=null){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getAllRecord($tbl,$where);	
	}

	function checkCheckout($custId, $CNo, $stat){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->checkCheckoutItem($custId, $CNo, $stat);	
	}

	function billCheck($CNo){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->checkBillCreation($CNo);	
	}

	function send_mail_gmail($to, $from, $subject, $body, $cc = null, $bcc = null){
		$CI =& get_instance();
		$CI->load->library('email');
		$config = array(
		            'protocol' => 'smtp', 
		            'smtp_host' => 'ssl://smtp.gmail.com', 
		            'smtp_port' => 465, 
		            'smtp_user' => 'xxx@gmail.com', 
		            'smtp_pass' => 'xxx', 
		            'mailtype' => 'html', 
		            'charset' => 'iso-8859-1'
		);
		$CI->email->initialize($config);
		$CI->email->set_mailtype("html");
		$CI->email->set_newline("\r\n");
		$CI->email->to($to);
		$CI->email->from($from);
		$CI->email->subject($subject);
		$CI->email->message($body);
		if($CI->email->send()){
			return 1;
		}else{
			print_r($CI->email->print_debugger());exit();
			return 0;
		}
	}

	function getTransType($trans_type_id){

		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getTransactionName($trans_type_id);
	}

	function getDay($dayno){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getDayName($dayno);	
	}

	function getDayNo($name){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getDayNumber($name);
	}

	function schemeType($id){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getSchemeTypeCategory($id);
	}


	function payMode($id){
		$pay = array('1'=>'Cash', '2'=>'Cashfree','3'=>'RazorPay', '4'=>'Stripe', '5'=> 'RazorPay TQ', '6'=>'Sodexo');
		return $pay[$id];
	}

	function do_upload($control,$filename,$upload_path,$allowed_type){
		$CI =& get_instance();
		$config['upload_path'] = $upload_path;
		$config['file_name'] = $filename;
		$config['max_size'] = 0;
		$config['remove_spaces'] = FALSE;
		$config['allowed_types'] = $allowed_type;

		$CI->load->library('upload', $config);

		$CI->upload->initialize($config);

		if (!$CI->upload->do_upload($control)) {
			echo $CI->upload->display_errors();
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function getBillData($dbname, $EID, $billId, $CustId, $flag){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->gettingBiliingData($dbname, $EID, $billId, $CustId, $flag);
	}

	function getBillingDataByEID_CNo($EID, $CNo, $MergeNo, $per_cent){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->fetchBiliingData($EID, $CNo, $MergeNo, $per_cent);
	}

	function billCreate($EID, $CNo, $postData){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->billGenerated($EID, $CNo, $postData);
	}

	function getTableDetail($table){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->getTableDetails($table);
	}

	function firebaseNotification($fcmRegIds, $msg){

		$fields = array(
          'to'  => $fcmRegIds,
          'notification'      => $msg
        );

		$API_ACCESS_KEY = "AAAAZEeZrX8:APA91bHgs5fs23mXqClnQ8-xPTNIg9We1-0nFfWGEi5DQmbs2HRzC0d9MneblYbR1WLNCVR9PYX86Qx6NBZUedIq3lyQ_jYyjRdkOCrk56P_eD26bmGIuk78VbX4ZxrdFKDeiHaJXTcm";
        $headers = array(
          'Authorization: key=' . $API_ACCESS_KEY,
          'Content-Type: application/json'
        );

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // $result = curl_exec($ch);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
          return  "cURL Error #:" . $err;
        } else {
          return $response;
        }

	}

	function menuList(){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getMenuList();
	}

	function langMenuList(){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->getLangMenuList();
	}

	function allOffers(){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->getOffers();
	}

	function allEntertainments(){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->getEntertainmentList();
	}

	// sendgrid email
	function send_email($to, $subject, $msg){

		$apiKey = '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';
        $url = 'https://api.sendgrid.com/v3/mail/send';

        $to = 'vijayyadav132200@gmail.com';
        $from = 'sanjayn@gmail.com';
        $subject = 'Sendgrid testing email email';

        $body = 'You will be prompted to choose the level of access or permissions for this API key. Select the appropriate permissions based on what you need. Typically';

        $data = [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $to]
                    ],
                    'subject' => $subject,
                ]
            ],
            'from' => [
                'email' => $from
            ],
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => $body
                ]
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        echo $response;

	}

	function sendSMS($mobileNO, $msgText)
    {
		$apikey = '7652383520739183947';//if you use apikey then userid and password is not required
		$userId = 'vtrend';
		$password = 'Sn197022';
		$sendMethod = 'simpleMsg'; //(simpleMsg|groupMsg|excelMsg)
		$messageType = 'text'; //(text|unicode|flash)
		$senderId = 'EATOUT';
		$mobile = $mobileNO;//comma separated
		// $msg = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
		$msg = $msgText;
		$scheduleTime = '';//mention time if you want to schedule else leave blank

        $curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://www.smsgateway.center/SMSApi/rest/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "userId=$userId&password=$password&senderId=$senderId&sendMethod=$sendMethod&msgType=$messageType&mobile=$mobile&msg=$msg&duplicateCheck=true&format=json",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "content-type: application/x-www-form-urlencoded"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		$val = 0;
		if ($err) {
		  // echo "cURL Error #:" . $err;
		} else {
		  // echo $response;
			$val = 1;
		}
		return $val;
    }

	function createCustUser($mobile){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->createCustomerUser($mobile);
	}

	function generateOTP($mobile, $page){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->generate_otp($mobile, $page);
	}

	function generateOnlyOTP(){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->generate_only_otp();
	}

	function autoSettlePayment($billId, $MergeNo, $MCNo){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->SettlePayment($billId, $MergeNo, $MCNo);	
	}

	function getName($custId){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->getUserName($custId);	
	}

	function getVisits($cellNo){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->getRestVist($cellNo);	
	}

	function taxCalculateData($kitcheData, $EID, $CNo, $MergeNo){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->getTaxCalculation($kitcheData, $EID, $CNo, $MergeNo);	
	}

	function getSeatNo($CNo){
		$CI = & get_instance();
	    $CI->load->model('User');
	    return $CI->User->getSeatNoByCNo($CNo);	
	}

	function convertToUnicodeNumber($input) {
    	$CI = & get_instance();
    	$site_lang = $CI->session->userdata('site_lang');
    
    	$standard_numsets = array("0","1","2","3","4","5","6","7","8","9");
    	$devanagari_numsets = array("०","१","२","३","४","५","६","७","८","९");
    	$chinese_numsets = array('零','一','二','三','四','五','六','七','八','九');

    	$digits = 0;
    	switch ($site_lang) {
    		// 1=english, 2=hindi, 3=malay, 4=thai
    		case 1:
    			$digits = str_replace($devanagari_numsets, $standard_numsets, $input);
    			break;
    		case 2:
    			$digits = str_replace($standard_numsets, $devanagari_numsets, $input);
    			break;
    		case 3:
    			$digits = str_replace($standard_numsets, $chinese_numsets, $input);
    			break;
    	}

    	return $digits;
  }

  function unicodeToEnglish($input) {
    	$CI = & get_instance();
    	$site_lang = $CI->session->userdata('site_lang');
    
    	$standard_numsets = array("0","1","2","3","4","5","6","7","8","9");
    	$devanagari_numsets = array("०","१","२","३","४","५","६","७","८","९");
    	$chinese_numsets = array('零','一','二','三','四','五','六','七','八','九');

    	$digits = 0;
    	if($input){
	    	switch ($site_lang) {
	    		// 1=english, 2=hindi, 3=malay, 4=thai
	    		case 1:
	    			$digits = $input;
	    			break;
	    		case 2:
	    			$digits = str_replace($devanagari_numsets, $standard_numsets, $input);
	    			break;
	    		case 3:
    				$digits = str_replace($standard_numsets, $chinese_numsets, $input);
    			break;
	    	}
    	}

    	return $digits;

  }

  function convertDigits($number, $sourceLanguage, $targetLanguage) {
    // Define digit mappings for different languages
    $digitMappings = [
        'en' => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], // English
        'zh' => ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'], // Chinese
        'hi' => ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'], // Hindi
        // Add more languages and their digit mappings as needed
    ];

    // Check if the source and target languages are supported
    if (!isset($digitMappings[$sourceLanguage]) || !isset($digitMappings[$targetLanguage])) {
        return "Unsupported languages provided.";
    }

    // Get digit mapping arrays for source and target languages
    $sourceDigits = $digitMappings[$sourceLanguage];
    $targetDigits = $digitMappings[$targetLanguage];

    // Perform conversion using str_replace
    $convertedNumber = str_replace($sourceDigits, $targetDigits, $number);

    return $convertedNumber;
}

function get_lat_long($address)
{
	die;
    $address = str_replace(" ", "+", $address);

    $json = file_get_contents_curl("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=AIzaSyBKO945pSEA3BoRajAB0ZlY8PpQRfo0abw");
    $json = json_decode($json);
    if ($json->status != 'ZERO_RESULTS') {

        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        return $lat . ',' . $long;
    }
    return false;
}

function file_get_contents_curl($url)
{
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($buffer)) {
        return 0;
    } else {
        return $buffer;
    }
}

function getDiscount($mobile){
	$CI = & get_instance();
    $CI->load->model('User');
    return $CI->User->getDiscountDetail($mobile);	
}

function getThemeColor(){
	$CI = & get_instance();
    $CI->load->model('User');
    return $CI->User->getThemeColour();	
}

function saveOTP($mobile, $otp, $page){
	$CI = & get_instance();
    $CI->load->model('User');
    return $CI->User->insertOTP($mobile, $otp, $page);	
}

// Test the function
// $englishNumber = '12345';
// $chineseNumber = convertDigits($englishNumber, 'en', 'zh');
// $hindiNumber = convertDigits($englishNumber, 'en', 'hi');








