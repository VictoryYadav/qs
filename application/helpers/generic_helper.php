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

	function send_mail($to, $from, $subject, $body, $cc = null, $bcc = null){
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

	function getBillData($dbname, $EID, $billId){
		$CI = & get_instance();
		$CI->load->model('User');
		return $CI->User->gettingBiliingData($dbname, $EID, $billId);
	}




