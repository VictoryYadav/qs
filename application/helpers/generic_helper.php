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

	function checkCheckout($custId, $CNo){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->checkCheckoutItem($custId, $CNo);	
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

		$trans_type = array(1=>'Transfer To EID', 6=>'Purchase Return', 9=>'Issue to Kit', 11=>'Return From EID', 16 =>'Purchase', 19=>'Return from Kit', 25=>'Inward Adjust', 26=>'Outward Adjust', 27 => 'Stock Adjust');
		return $trans_type[$trans_type_id];

	}

	function getDay($day_id){
		$days=array(1=>'Sunday', 2=>'Monday', 3=>'Tuesday', 4=>'Wednesday', 5=>'Thursday', 6=>'Friday', 7=>'Saturday');
		return $days[$day_id];
	}

	function getSchemeType($id){
		$sch_typ = array('1'=>'BillBased', '21'=>'CID based', '22'=>'MenuCatg based', '23'=>'ItmTyp Based', '24'=>'ItemID based', '25'=>'Itm_Portion based','26'=>'CID and Itm_Portion based', '27'=>'MenuCatg and Itm_Portion based', '28'=>'ItemTyp and Itm_Portion based','29'=>'ItemID and Itm_Portion based');
		return $sch_typ[$id];
	}

	function getSchemeCat($id){
		$sch_cat = array('1'=>'Bill Discount', '2'=>'Free Item with BillAmt','3'=>'Discount on minBillAmt/month', '4'=>'First time use of QS (2% discount)', '5'=> 'Rating Discount', '21'=>'Gen. Discount', '22'=>'Buy x get y free (1+1) / (2+1) lowest rate', '23'=>'Buy x get y free (1+1) / (2+1) highest rate', '24'=>'Buy x get y discounted; 51-Discounts using promo codes');
		return $sch_cat[$id];
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

	function getBillingDataByEID_CNo($EID, $CNo, $per_cent){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->fetchBiliingData($EID, $CNo, $per_cent);
	}

	function billCreate($EID, $CNo, $postData){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->billGenerated($EID, $CNo, $postData);
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

	function languageArray(){
		return array(
		'about_us' => 'About Us',
		'account' => 'Account',
		'edit_profile' => 'Edit Profile',
		'book_table' => 'Book Table',
		'refer_outlet' => 'Refer Outlet',
		'login' => 'Login',
		'logout' => 'Logout',		
		'transaction' => 'Transaction',		
		'terms_and_conditions' => 'T & C',		
		'testimonials' => 'Testimonials',		
		'contact_us' => 'Contact Us',		
		'order_list' =>  'Order List',		
		'current_order' => 'Current Order',		
		'offers' => 'Offers',		
		'rate_us' => 'Rate Us',	
		'back' => 'Back',
		'add_item' => 'Add Item',
		'take_away' => 'Order Type',
		'portion' => 'Portion',
		'delivery_time' => 'Deliver In (mts)',
		'quantity' => 'Qty',
		'menu' => 'Menu',
		'item' => 'Item',
		'continue' => 'Continue',
		'order_detail' => 'Order Detail',
		'rate' => 'Rate',
		'delete' => 'Delete',
		'bill_later' => 'Bill Later',
		'bill_now' => 'Bill Now',
		'bill_later_text' => '"Bill Later" to continue with Ordering',
		'bill_later_text' => '"Bill Now" if meal is over',
		'else' => 'Else',
		'billing' => 'Billing',
		'bill' => 'Bill',
		'total_discount' => 'Total Discount',
		'total' => 'Total',
		'discount' => 'Discount',
		'packing_charge' => 'Packing Charge',
		'delivery_charge' => 'Delivery Charge',
		'payable' => 'Payable',
		'confirm' => 'Confirm',
		'merge_orders' => 'Merge Orders',
		'odrer' => 'Order',
		'merge' => 'Merge',
		'please_pay_to_cashier' => 'Please Pay To Cashier',
		'merge_bills' => 'Merge Bills',
		'pay_online' => 'Pay Online',
		'item_total' => 'Item Total',
		'sub_total' => 'Sub Total',
		'service_charge' => 'Service Charge',
		'savings' => 'Savings',
		'tips' => 'Tips',
		'pay_cash' => 'Pay Cash',
		'item_total' => 'Item Total',
		'item_total' => 'Item Total',
		'bill_no' => "Bill No",
		'order_no' => 'Order No',
		'date' => 'Date',
		'menu_item' => 'Menu Item',
		'amount' => 'Amount'
		);
	}

	function allOffers(){
		$CI = & get_instance();
		$CI->load->model('Cust');
		return $CI->Cust->getOffers();
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




