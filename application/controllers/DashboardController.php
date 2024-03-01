<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// MY_AuthController
class DashboardController extends CI_Controller
{
	private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'user');

		$my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
        $this->output->delete_cache();
	}

	public function index(){
		$data['title'] = "Dashboard";
		$this->load->view('dashboard/index',$data);
	}

	public function getpost()
	{
		if (empty($_POST)) {
			$this->load->view('errors/html/error_method');
		} else {
			return json_decode(json_encode($_POST));
		}
	}

	public function customer_graph(){

		$ChainId 	= authuser()->ChainId;
    	$EID 		= authuser()->EID;

    	$today = date('Y-m-d');
	    $yesterday = date('Y-m-d', strtotime("-1 day", strtotime($today)));
	    $last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));

    	if (isset($_POST['type']) && $_POST['type'] == 'footfall') {

    		$groupby = ' date(LstModDt)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(LstModDt)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(LstModDt)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(LstModDt)';
		    		break;
		    }

	        $ln_query = "SELECT year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) ) AS t, COUNT(CNo) as tot FROM KitchenMain where time(LstModDt)<'15:00:00' and EID = $EID ";
	        $dn_query = "SELECT year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) ) AS t, COUNT(CNo) as tot FROM KitchenMain where time(LstModDt)>='15:00:00' and EID = $EID ";	        

	        $ln_query.=" and LstModDt >='$last_days' and LstModDt <= '$today' GROUP BY $groupby";
	        $dn_query.=" and LstModDt >='$last_days' and LstModDt <= '$today' GROUP BY $groupby";
	        

	        // $ln_query.=" GROUP BY year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) )";
	        // $dn_query.=" GROUP BY year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) )";

	        $footfalls_lunch = $this->db2->query($ln_query)->result_array();
	        $footfalls_dinner = $this->db2->query($dn_query)->result_array();
	        // echo "<pre>";print_r($this->db2->last_query());exit();
	        $footfalls_lunch_array_labal = [];

	        $footfalls_lunch_array_value = [];

	        $footfalls_dinner_array_value = [];

	        foreach ($footfalls_lunch as $key => $value) {
	            array_push($footfalls_lunch_array_labal,$value['date(LstModDt)'] );
	            array_push($footfalls_lunch_array_value,$value['tot'] );
	        }

	        foreach ($footfalls_dinner as $key => $value) {
	            array_push($footfalls_lunch_array_labal,$value['date(LstModDt)'] );
	            array_push($footfalls_dinner_array_value,$value['tot'] );
	        }

	        $footfall_lunch_label = '[';
	        for ($i=0; $i < count($footfalls_lunch_array_labal); $i++) { 
	            $footfall_lunch_label .=" '" .$footfalls_lunch_array_labal[$i]."' ";
	            if(count($footfalls_lunch_array_labal)-1 != $i){
	                $footfall_lunch_label .= " ,"; 
	            } 
	        } 

	        $footfall_lunch_label .=']' ; 	        
	        $footfalls_lunch_value='[' ; 

	        for ($i=0; $i < count($footfalls_lunch_array_value); $i++) { 
	            $footfalls_lunch_value .=" '" .$footfalls_lunch_array_value[$i]."' ";
	            if(count($footfalls_lunch_array_value)-1 != $i){
	                $footfalls_lunch_value .= " ,"; 
	            } 
	        }

	        $footfalls_lunch_value .=']' ;
	        $footfalls_dinner_value='[' ; 

	        for ($i=0; $i < count($footfalls_dinner_array_value); $i++) { 
	            $footfalls_dinner_value .=" '" .$footfalls_dinner_array_value[$i]."' ";
	            if(count($footfalls_dinner_array_value)-1 != $i){
	                $footfalls_dinner_value .= " ,"; 
	            } 
	        } 

	        $footfalls_dinner_value .=']' ;

	        $response = [
	            'footfalls_lunch_array_labal' => $footfalls_lunch_array_labal,
	            'footfalls_lunch_array_value' => $footfalls_lunch_array_value,
	            'footfalls_dinner_array_value' => $footfalls_dinner_array_value,
	            'status' => 1
	        ];

	        echo json_encode($response);
	        exit;
	    }

	    if (isset($_POST['type']) && $_POST['type'] == 'orderValue') {

	    	$groupby = ' date(billTime)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(billTime)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(billTime)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(billTime)';
		    		break;
		    }

	        $q1 = "SELECT COUNT(BillId), date(billTime), sum(TotAmt) FROM Billing where time(billTime) <'15:00:00' and EID = $EID";
	        $q2 = "SELECT COUNT(BillId), date(billTime), sum(TotAmt) FROM Billing where time(billTime) >='15:00:00' and EID = $EID";

	        $q1.=" and billTime >='$last_days' and billTime <= '$today' group by $groupby ORDER BY date(billTime) DESC";
	        $q2.=" and billTime >='$last_days' and billTime <= '$today' group by $groupby ORDER BY date(billTime) DESC";

	        $order_value_lunch = $this->db2->query($q1)->result_array();
	        $order_value_dinner=$this->db2->query($q2)->result_array();
	        
	        $order_value_lunch_array_labal = [];
	        $order_value_lunch_array_value = [];
	        $order_value_dinner_array_value = [];
	        $payment_mode_lunch_array_labal = [];

	        foreach ($order_value_lunch as $key => $value) {
	            array_push($order_value_lunch_array_labal,$value['date(billTime)'] );
	            array_push($order_value_lunch_array_value,$value['sum(TotAmt)'] );
	            // array_push($payment_mode_lunch_array_labal,$value['PaymtMode'] );
	        }

	        foreach ($order_value_dinner as $key => $value) {
	            array_push($order_value_lunch_array_labal,$value['date(billTime)'] );
	            array_push($order_value_dinner_array_value,$value['sum(TotAmt)'] );
	            // array_push($payment_mode_lunch_array_labal,$value['PaymtMode'] );
	        }

	        $order_value_lunch_label = '[';

	        for ($i=0; $i < count($order_value_lunch_array_labal); $i++) { 
	            $order_value_lunch_label .=" '".$order_value_lunch_array_labal[$i]."' ";
	            if(count($order_value_lunch_array_labal)-1 != $i){
	                $order_value_lunch_label .= " ,"; 
	            } 
	        } 

	        $order_value_lunch_label .=']' ; 
	        $order_value_lunch_value='[' ; 

	        for($i=0; $i < count($order_value_lunch_array_value); $i++) { 
	            $order_value_lunch_value .=" '".$order_value_lunch_array_value[$i]."' ";
	            if(count($order_value_lunch_array_value)-1 != $i){
	                $order_value_lunch_value .= " ,"; 
	            } 
	        } 

	        $order_value_lunch_value .=']' ; 

	        $order_value_dinner_value='[' ; 
	        for($i=0; $i < count($order_value_dinner_array_value); $i++) { 
	            $order_value_dinner_value .=" '".$order_value_dinner_array_value[$i]."' ";

	            if(count($order_value_dinner_array_value)-1 != $i){
	                $order_value_dinner_value .= " ,"; 
	            } 
	        } 

	        $order_value_dinner_value .=']' ;
	        $payment_mode_lunch_label = '[';

	        for ($i=0; $i < count($payment_mode_lunch_array_labal); $i++) {
	            $payment_mode_lunch_label .=" '".$payment_mode_lunch_array_labal[$i]."' ";
	            if(count($payment_mode_lunch_array_labal)-1 != $i){
	                $payment_mode_lunch_label .= " ,"; 
	            } 
	        } 

	        $payment_mode_lunch_label .=']' ;

	        $response = [
	        'order_value_array_labal' => $order_value_lunch_array_labal,
	        'order_value_lunch_array_value' => $order_value_lunch_array_value,
	        'order_value_dinner_array_value' => $order_value_dinner_array_value,
	        'payment_mode_lunch_label' => $payment_mode_lunch_array_labal,
	        'status' => 2
	        ];
	        echo json_encode($response);
	        exit;
	    }

	    if (isset($_POST['type']) && $_POST['type'] == 'paymentMode') {

	    	$groupby = ' date(b.billTime)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(b.billTime)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(b.billTime)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(b.billTime)';
		    		break;
		    }

	        $order_value_lunch = $this->db2->query("SELECT bp.PaymtMode, COUNT(b.BillId) as BillId, date(b.billTime) as billTime FROM Billing b, BillPayments bp where b.BillId = bp.BillId and b.EID = $EID and bp.EID = $EID and bp.PaymtMode != '' and b.billTime >='$last_days' and b.billTime <= '$today' group by bp.PaymtMode,$groupby ORDER BY date(b.billTime) DESC")
	        	->result_array();
	        // echo "<pre>";print_r($order_value_lunch);die;
	        $order_value_lunch_array_labal = [];
	        $order_value_lunch_array_value = [];
	        $order_value_dinner_array_value = [];
	        $payment_mode_lunch_array_labal = [];

	        foreach ($order_value_lunch as $key => $value) {
	            // array_push($order_value_lunch_array_labal,$value['date(billTime)'] );
	            array_push($order_value_lunch_array_labal,$value['billTime'] );
	            array_push($order_value_lunch_array_value,$value['BillId'] );
	            array_push($payment_mode_lunch_array_labal,$value['PaymtMode'] );
	        }

	        $order_value_lunch_label = '[';

	        for ($i=0; $i < count($order_value_lunch_array_labal); $i++) { 
	            $order_value_lunch_label .=" '".$order_value_lunch_array_labal[$i]."' ";
	            if(count($order_value_lunch_array_labal)-1 != $i){
	                $order_value_lunch_label .= " ,"; 
	            } 
	        } 

	        $order_value_lunch_label .=']' ; 

	        $order_value_lunch_value='[' ; 

	        for($i=0; $i < count($order_value_lunch_array_value); $i++) { 
	            $order_value_lunch_value .=" '".$order_value_lunch_array_value[$i]."' ";
	            if(count($order_value_lunch_array_value)-1 != $i){
	                $order_value_lunch_value .= " ,"; 
	            } 
	        } 

	        $order_value_lunch_value .=']' ; 

	        // $order_value_dinner_value .=']' ;


	        $payment_mode_lunch_label = '[';

	        for ($i=0; $i < count($payment_mode_lunch_array_labal); $i++) {

	            $payment_mode_lunch_label .=" '".$payment_mode_lunch_array_labal[$i]."' ";

	            if(count($payment_mode_lunch_array_labal)-1 != $i){

	                $payment_mode_lunch_label .= " ,"; 

	            } 

	        } 

	        $payment_mode_lunch_label .=']' ;

	        $response = [
	        'payment_mode_array_label' => $order_value_lunch_array_labal,
	        'payment_mode_lunch_array_value' => $order_value_lunch_array_value,
	        // 'payment_mode_dinner_array_value' => $order_value_dinner_array_value,
	        'payment_mode_lunch_label' => $payment_mode_lunch_array_labal,
	        'status' => 2
	        ];

	        echo json_encode($response);
	        exit;
	    }
	}

	public function rest_graph(){
		$ChainId 	= authuser()->ChainId;
    	$EID 		= authuser()->EID;

    	$today = date('Y-m-d');
	    $yesterday = date('Y-m-d', strtotime("-1 day", strtotime($today)));
	    $last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));

		if (isset($_POST['type']) && $_POST['type'] == 'TakeAways') {

			$groupby = ' date(km.LstModDt)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(km.LstModDt)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(km.LstModDt)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(km.LstModDt)';
		    		break;
		    }

	        $takeaways = $this->db2->query("SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo) as totorders, SUM(k.Qty), SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where km.CNo=k.CNo and k.TA>0 and km.EID = $EID and k.Stat=3 and date(km.LstModDt)>='$last_days' and date(km.LstModDt) <='$today' GROUP BY $groupby,( HOUR( km.LstModDt ) )")
				->result_array();

	        $takeaways_array_labal = [];

	        $takeaways_totitemdisc_array_value = [];

	        $takeaways_totamt_array_value = [];



	        foreach ($takeaways as $key => $value) {

	            array_push($takeaways_array_labal,$value['t'] );

	            array_push($takeaways_totitemdisc_array_value,$value['SUM(k.Qty)'] );

	            array_push($takeaways_totamt_array_value,$value['SUM(k.ItmRate)'] );

	        }



	        $response = [

	            'takeaways_array_labal' => $takeaways_array_labal,

	            'takeaways_totitemdisc_array_value' => $takeaways_totitemdisc_array_value,

	            'takeaways_totamt_array_value' => $takeaways_totamt_array_value,

	            'status' => 1

	        ];

	        echo json_encode($response);

	        exit;
	    }



	    if (isset($_POST['type']) && $_POST['type'] == 'offers') {

	    	$groupby = ' date(km.LstModDt)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(km.LstModDt)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(km.LstModDt)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(km.LstModDt)';
		    		break;
		    }

	        $offers = $this->db2->query("SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo) as totorders, SUM(k.Qty), SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where km.CNo=k.CNo and k.SchCd>0 and km.EID = $EID and date(km.LstModDt)>='$last_days' and date(km.LstModDt) <='$today' GROUP BY $groupby,( HOUR( km.LstModDt ) )")
	        	->result_array();

	        $offers_array_labal = [];

	        $offers_totitemdisc_array_value = [];

	        $offers_totamt_array_value = [];

	        foreach ($offers as $key => $value) {

	            array_push($offers_array_labal,$value['t'] );

	            array_push($offers_totitemdisc_array_value,$value['SUM(k.Qty)'] );

	            array_push($offers_totamt_array_value,$value['SUM(k.ItmRate)'] );

	        }

	        $response = [

	            'offers_array_labal' => $offers_array_labal,

	            'offers_totitemdisc_array_value' => $offers_totitemdisc_array_value,

	            'offers_totamt_array_value' => $offers_totamt_array_value,

	            'status' => 1

	        ];

	        echo json_encode($response);

	        exit;

	    }
	    
	    if(isset($_POST) && $_POST['type'] == "tableWiseOccupencyLunch"){

	    	$groupby = ' date(b.billTime)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(b.billTime)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(b.billTime)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(b.billTime)';
		    		break;
		    }

	        $lunch = $this->db2->query("SELECT b.TableNo, COUNT(b.CNo) as tot, SUM(b.TotAmt), SUM(b.TotAmt)/200 totnos, sum(b.TotItemDisc), TIME_FORMAT(sum(TIMEDIFF(b.billTime,km.LstModDt)),  '%H %i %s') as totTime FROM Billing b, KitchenMain km where (km.CNo=b.CNo OR km.MCNo=b.CNo) and time(km.LstModDt)<'15:00:00' and km.EID  =$EID and b.EID = $EID and date(b.billTime)>='$last_days' and date(b.billTime) <='$today' GROUP BY $groupby, b.TableNo")
	        	->result_array();

	        $data = [];
	        foreach($lunch as $key) {
	            // print_r($key);exit();
	            $a = [];
	            array_push($a, $key['TableNo']);
	            array_push($a, (double)$key['SUM(b.TotAmt)']);
	            // print_r($a);exit();
	            array_push($data, $a);

	        }
	        // print_r($data);exit();
	        echo json_encode($data);

	        exit;
	    }
	    
	    if(isset($_POST) && $_POST['type'] == "TrendKitchenOrders"){

	    	$groupby = ' date(k.LstModDt)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(k.LstModDt)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(k.LstModDt)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(k.LstModDt)';
		    		break;
		    }

	        $takeaways = $this->db2->query("SELECT date(k.LstModDt), Sum(k.Qty) as totorders, SUM(k.ItmRate*k.Qty) FROM KitchenMain km, Kitchen k where km.CNo=k.CNo AND k.Stat<>4 and k.Stat<>6 and k.Stat<>7 and k.Stat<>99 and km.EID = $EID and k.EID = $EID and date(k.LstModDt)>='$last_days' and date(k.LstModDt) <='$today' GROUP BY $groupby")->result_array();

	        $data = [];
	        foreach($takeaways as $key) {
	            // print_r($key);exit();
	            $d = date('d/m', strtotime($key['date(k.LstModDt)']));
	            $a = [];
	            array_push($a, $d);
	            array_push($a, (double)$key['totorders']);
	            // print_r($a);exit();
	            array_push($data, $a);

	        }
	        // echo "<pre>";
	        // print_r($data);exit();
	        echo json_encode($data);
	        exit;
	    }
	    
	    if(isset($_POST) && $_POST['type'] == "RatingsTrend"){

	    	$groupby = ' date(r.LstModDt)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(r.LstModDt)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(r.LstModDt)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(r.LstModDt)';
		    		break;
		    }

	    	$today = date('Y-m-d');
	    	$yesterday = date('Y-m-d', strtotime("-1 day", strtotime($today)));
	    	$last_7_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));

	        $ratingsData = $this->db2->query("SELECT r.LstModDt, COUNT(r.BillId), AVG(r.ServRtng), AVG(r.AmbRtng), AVG(r.VFMRtng), r.avgBillRtng, DAYNAME(r.LstModDt), date(r.LstModDt) as date1 FROM Ratings r where r.LstModDt >= '$last_days' and r.LstModDt <= '$today' group by $groupby")->result_array();

	        $data = [];
	        foreach($ratingsData as $key) {
	            // print_r($key);exit();
	            $d = $key['COUNT(r.BillId)'];
	            $a = [];
	            // array_push($a, $d);
	            array_push($a, $key['date1']);
	            array_push($a, (double)$key['AVG(r.ServRtng)']);
	            array_push($a, (double)$key['AVG(r.AmbRtng)']);
	            array_push($a, (double)$key['AVG(r.VFMRtng)']);
	            array_push($a, (double)$key['avgBillRtng']);
	            // print_r($a);exit();
	            array_push($data, $a);

	        }
	        // print_r($data);exit();
	        echo json_encode($data);

	        exit;
	    }
	}

	public function food_graph(){
		$ChainId 	= authuser()->ChainId;
    	$EID 		= authuser()->EID;

    	$today = date('Y-m-d');
	    $yesterday = date('Y-m-d', strtotime("-1 day", strtotime($today)));
	    $last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));

		if (isset($_POST['type']) && $_POST['type'] == 'RevenueAndDiscounts') {

			$groupby = ' date(billTime)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(billTime)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(billTime)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(billTime)';
		    		break;
		    }

	        $revenue_and_discounts = $this->db2->query("SELECT date(billTime), SUM(TotAmt), sum(TotItemDisc) FROM Billing where EID = $EID and billTime >='$last_days' and billTime <='$today' GROUP BY $groupby")
	        		->result_array();

	        $revenue_and_discounts_array_labal = [];
	        $revenue_and_discounts_totitemdisc_array_value = [];
	        $revenue_and_discounts_totamt_array_value = [];

	        foreach ($revenue_and_discounts as $key => $value) {

	            array_push($revenue_and_discounts_array_labal,$value['date(billTime)'] );
	            array_push($revenue_and_discounts_totitemdisc_array_value,$value['sum(TotItemDisc)'] );
	            array_push($revenue_and_discounts_totamt_array_value,$value['SUM(TotAmt)'] );
	        }

	        $revenue_and_discounts_labal = '[';

	        for ($i=0; $i < count($revenue_and_discounts_array_labal); $i++) {

	            $revenue_and_discounts_labal .='"' .$revenue_and_discounts_array_labal[$i].'"';

	            if(count($revenue_and_discounts_array_labal)-1 != $i){

	                $revenue_and_discounts_labal .= " ,";

	            } 
	        } 

	        $revenue_and_discounts_labal .=']' ;

	        $revenue_and_discounts_totitemdisc_value='[' ;

	        for ($i=0; $i < count($revenue_and_discounts_totitemdisc_array_value); $i++) {

	            $revenue_and_discounts_totitemdisc_value .='"' .$revenue_and_discounts_totitemdisc_array_value[$i].'"';

	            if(count($revenue_and_discounts_totitemdisc_array_value)-1 != $i){

	                $revenue_and_discounts_totitemdisc_value .= " ,";
	            } 

	        }

	        $revenue_and_discounts_totitemdisc_value .=']' ;

	        

	        $revenue_and_discounts_totamt_value='[' ;

	        for ($i=0; $i < count($revenue_and_discounts_totamt_array_value); $i++) {

	            $revenue_and_discounts_totamt_value .='"' .$revenue_and_discounts_totamt_array_value[$i].'"';



	            if(count($revenue_and_discounts_totamt_array_value)-1 != $i){

	                $revenue_and_discounts_totamt_value .= ",";

	            } 

	        } 

	        $revenue_and_discounts_totamt_value .=']' ;



	        $response = [

	            'revenue_and_discounts_labal' => $revenue_and_discounts_array_labal,

	            'revenue_and_discounts_totitemdisc_value' => $revenue_and_discounts_totitemdisc_array_value,

	            'revenue_and_discounts_totamt_value' => $revenue_and_discounts_totamt_array_value,

	            'status' => 1

	        ];



	        echo json_encode($response);

	        exit;



	    }

	    if (isset($_POST['type']) && $_POST['type'] == 'OrdersByHour') {

	    	$groupby = ' date(km.LstModDt)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(km.LstModDt)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(km.LstModDt)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(km.LstModDt)';
		    		break;
		    }

	        $revenue_and_discounts = $this->db2->query("SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo * k.Qty) as totorders, SUM(k.ItmRate * k.Qty) as itemRate FROM KitchenMain km, Kitchen k where km.CNo=k.CNo and km.EID = $EID and k.EID = $EID and k.Stat = 3 and date(km.LstModDt)>='$last_days' and date(km.LstModDt) <='$today' GROUP BY $groupby,( HOUR( km.LstModDt ) )")
	        		->result_array();

	        $revenue_and_discounts_array_labal=[];
	        $revenue_and_discounts_totitemdisc_array_value=[];
	        $revenue_and_discounts_totamt_array_value=[]; 

	        foreach ($revenue_and_discounts as $key=> $value) {

	            array_push($revenue_and_discounts_array_labal,$value['t'] );

	            array_push($revenue_and_discounts_totitemdisc_array_value,$value['totorders'] );

	            array_push($revenue_and_discounts_totamt_array_value,$value['itemRate'] );

	        }

	        $revenue_and_discounts_labal = '[';

	        for ($i=0; $i < count($revenue_and_discounts_array_labal); $i++) {

	            $revenue_and_discounts_labal .=' "'.$revenue_and_discounts_array_labal[$i].'" ';



	            if(count($revenue_and_discounts_array_labal)-1 != $i){

	                $revenue_and_discounts_labal .= " ,"; 

	            } 

	        } 

	        

	        $revenue_and_discounts_labal .=']' ;



	        $revenue_and_discounts_totitemdisc_value='[' ; 

	        

	        for ($i=0; $i < count($revenue_and_discounts_totitemdisc_array_value); $i++) { 

	            $revenue_and_discounts_totitemdisc_value .=' "' .$revenue_and_discounts_totitemdisc_array_value[$i].'" ';

	            

	            if(count($revenue_and_discounts_totitemdisc_array_value)-1 != $i){

	                $revenue_and_discounts_totitemdisc_value .= " ,"; 

	            }

	        }

	        

	        $revenue_and_discounts_totitemdisc_value .=']' ;

	        

	        $revenue_and_discounts_totamt_value='[' ; 

	        

	        for ($i=0; $i < count($revenue_and_discounts_totamt_array_value); $i++) { 

	            $revenue_and_discounts_totamt_value .=' "' .$revenue_and_discounts_totamt_array_value[$i].'" ';



	            if(count($revenue_and_discounts_totamt_array_value)-1 != $i){

	                $revenue_and_discounts_totamt_value .= " ,"; 

	            } 

	        }

	        

	        $revenue_and_discounts_totamt_value .=']' ;

	        

	        $response = [

	            'revenue_and_discounts_labal' => $revenue_and_discounts_array_labal,

	            'revenue_and_discounts_totitemdisc_value' => $revenue_and_discounts_totitemdisc_array_value,

	            'revenue_and_discounts_totamt_value' => $revenue_and_discounts_totamt_array_value,

	            'status' => 1

	        ];



	        echo json_encode($response);

	        exit;

	    }



	    if (isset($_POST['type']) && $_POST['type'] == 'BillsAndRatings') {

	    	$groupby = ' date(b.billTime)';

		    switch ($_POST['filterModes']) {
		    	case 'weekly':
		    			$last_days = date('Y-m-d', strtotime("-7 day", strtotime($today)));
		    			$groupby = ' date(b.billTime)';
		    		break;
		    	case 'monthly':
		    			$last_days = date('Y-m-d', strtotime("-30 day", strtotime($today)));
		    			$groupby = ' WEEKOFYEAR(b.billTime)';
		    		break;
		    	case 'half_yearly':
		    			$last_days = date('Y-m-d', strtotime("-180 day", strtotime($today)));
		    			$groupby = ' month(b.billTime)';
		    		break;
		    }

	        $revenue_and_discounts = $this->db2->query("SELECT date(b.billTime),( HOUR(b.billTime) ) AS t, COUNT(b.BillId) as totbills, count(r.RCd) FROM Billing b, Ratings r where  b.BillId=r.BillId and b.EID = $EID and date(b.billTime)>='$last_days' and date(b.billTime) <='$today' GROUP BY $groupby,( HOUR(b.billTime) )")
	        	->result_array();

	        $revenue_and_discounts_array_labal=[];

	        $revenue_and_discounts_totitemdisc_array_value=[];

	        $revenue_and_discounts_totamt_array_value=[]; 

	        foreach ($revenue_and_discounts as $key=> $value) {

	            array_push($revenue_and_discounts_array_labal,$value['t'] );

	            array_push($revenue_and_discounts_totitemdisc_array_value,$value['totbills'] );

	            array_push($revenue_and_discounts_totamt_array_value,$value['SUM(r.RCd)'] );

	        }



	        $revenue_and_discounts_labal = '[';



	        for ($i=0; $i < count($revenue_and_discounts_array_labal); $i++) {

	            $revenue_and_discounts_labal .=' "'.$revenue_and_discounts_array_labal[$i].'" ';



	            if(count($revenue_and_discounts_array_labal)-1 != $i){

	                $revenue_and_discounts_labal .= " ,"; 

	            } 

	        } 

	        

	        $revenue_and_discounts_labal .=']' ;



	        $revenue_and_discounts_totitemdisc_value='[' ; 

	        

	        for ($i=0; $i < count($revenue_and_discounts_totitemdisc_array_value); $i++) { 

	            $revenue_and_discounts_totitemdisc_value .=' "' .$revenue_and_discounts_totitemdisc_array_value[$i].'" ';

	            

	            if(count($revenue_and_discounts_totitemdisc_array_value)-1 != $i){

	                $revenue_and_discounts_totitemdisc_value .= " ,"; 

	            }

	        }

	        

	        $revenue_and_discounts_totitemdisc_value .=']' ;

	        

	        $revenue_and_discounts_totamt_value='[' ; 

	        

	        for ($i=0; $i < count($revenue_and_discounts_totamt_array_value); $i++) { 

	            $revenue_and_discounts_totamt_value .=' "' .$revenue_and_discounts_totamt_array_value[$i].'" ';



	            if(count($revenue_and_discounts_totamt_array_value)-1 != $i){

	                $revenue_and_discounts_totamt_value .= " ,"; 

	            } 

	        }

	        

	        $revenue_and_discounts_totamt_value .=']' ;

	        

	        $response = [

	            'revenue_and_discounts_labal' => $revenue_and_discounts_array_labal,

	            'revenue_and_discounts_totitemdisc_value' => $revenue_and_discounts_totitemdisc_array_value,

	            'revenue_and_discounts_totamt_value' => $revenue_and_discounts_totamt_array_value,

	            'status' => 1

	        ];



	        echo json_encode($response);

	        exit;



	    }
	}

	public function csv_report(){
		$EID = authuser()->EID;
		$status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

		    $langId = $this->session->userdata('site_lang');

        	$fromDate = date('Y-m-d',strtotime($_POST['from']));
        	$toDate = date('Y-m-d',strtotime($_POST['to']));
        	$data = [];
        	
        	switch ($_POST['reportType']) {
        		case 'footfalls':
        		case 'orderValue':
        		case 'paymentMode':
        		$mname = "cp.Name$langId as paymentName";
        			$data = $this->db2->select("bp.BillId, bp.PymtDate, sum(bp.PaidAmt) as PaidAmount, $mname, bp.PymtType")
		    		->order_by('bp.BillId', 'DESC')
		    		->group_by('bp.BillId, bp.PaymtMode, bp.PymtType')
		    		->join('ConfigPymt cp', 'cp.PymtMode = bp.PaymtMode', 'inner')
		    		->get_where('BillPayments bp', array(
		    									'bp.EID' => $EID,
		    									'bp.PymtDate >=' => $fromDate,
		    									'bp.PymtDate <=' => $toDate)
		    					)
		    		->result_array();
        			break;
        		case 'revenueDiscount':

        		$tpname = "tp.Name$langId as thirdParty";
        		$uname = "tp.Name$langId as thirdParty";
        			$data = $this->db2->select("b.BillId, b.billTime, b.MergeNo, b.OType, b.3PId, b.PaidAmt, b.SerChargeAmt, b.Tip, b.TotItemDisc, b.BillDiscAmt, b.RtngDiscAmt, b.custDiscAmt, b.TotPckCharge, b.DelCharge, b.PAX, $tpname, CONCAT_WS(',', ur.FName, ur.LName) as Username ")
        						->group_by('b.BillId')
        						->join('3POrders tp', 'tp.3PId = b.3PId', 'left')
        						->join('UsersRest ur', 'ur.RUserId = b.LoginCd', 'inner')
        						->get_where('Billing b', array('b.EID' => $EID, 'b.SerCharge >' => 0))
        						 ->result_array();
        			break;

        			case 'orderByHour':

	        			// $data = $this->db2->query("SELECT h.hr, date(k.LstModDt), sum(k.Qty) , SUM(k.ItmRate * k.Qty) FROM Kitchen k, Hours h WHERE k.EID = $EID AND k.Stat = 3 and hour(k.LstModDt) = h.HR AND k.LstModDt >= '$fromDate' AND k.LstModDt <= '$toDate' group by h.HR, hour(k.LstModDt)")
	        			// 	->result_array();

	        			$data = $this->db2->select("h.HR as Hour, date(k.LstModDt) as Date, sum(k.Qty) as Quantity, SUM(k.ItmRate * k.Qty) as OrderValue")
	        						->group_by('h.HR, HOUR( k.LstModDt )')
	        						->join('Hours h', 'h.HR = hour(k.LstModDt)', 'inner')
	        						->get_where('Kitchen k', array('k.EID' => $EID, 
	        							'k.Stat' => 3,
	        							'k.LstModDt >=' => $fromDate,
	        							'k.LstModDt <=' => $toDate,
	        							)
	        						)
	        					->result_array();
	        			break;

	        		case 'billsAndRating':

	        			$data = $this->db2->select("COUNT(b.BillId) as Bills, count(r.RCd) as Ratings")
	        				->join('Ratings r', 'r.BillId=b.BillId', 'left')
	        				->get_where('Billing b', array('b.EID' => $EID,
	        					'b.billTime >=' => $fromDate,
	        					'b.billTime <=' => $toDate)
	        					)
	        				->result_array();
	        			
	        			break;

	        		case 'takeAway':

	        			$data = $this->db2->select("b.BillId as BillNo, date(b.billTime) as BillDate, sum(k.Qty) as Quantity, SUM(k.ItmRate * k.Qty) as OrderValues, (k.Qty * k.PckCharge) as TotalPackingCharge, b.DelCharge as DeliveryCharge")
	        				->group_by('b.BillId')
	        				->join('Kitchen k', 'k.MCNo =b.CNo', 'inner')
	        				->get_where('Billing b', array('k.TA >' => 0,
	        											'b.EID' => $EID,
	        											'k.EID' => $EID,
	        											'k.Stat' => 3,
	        											'b.billTime >=' => $fromDate,
	        											'b.billTime <=' => $toDate)
	        							)
							->result_array();
	        			
	        			break;

	        		case 'offers':

	        			$data = $this->db2->select("b.BillId as BillNo, date(b.billTime) as BillDate, sum(k.Qty) as Quantity, SUM(k.ItmRate * k.Qty) as OrderValues, SUM(k.OrigRate * k.Qty) as OrigValues, b.TotItemDisc, b.BillDiscAmt, b.RtngDiscAmt as RatingDiscount, b.custDiscAmt as customerDiscount")
	        				->group_by('b.BillId')
	        				->join('Kitchen k', 'k.MCNo =b.CNo', 'inner')
	        				->get_where('Billing b', array('k.TA >' => 0,
	        											'b.EID' => $EID,
	        											'k.EID' => $EID,
	        											'k.Stat' => 3,
	        											'k.SchCd >' => 0,
	        											'b.billTime >=' => $fromDate,
	        											'b.billTime <=' => $toDate)
	        							)
							->result_array();
	        			
	        			break;

	        		case 'tableWiseOccupencyLunch':

	        			$data = $this->db2->query("SELECT et.TableNo, date(b1.billTime) as BillDate, IFNULL((SELECT TIME_FORMAT(sum(TIMEDIFF(b.billTime, km.LstModDt)), '%H %i %s')
							FROM Billing b,KitchenMain km, Eat_tables et1  where km.MCNo = b.CNo
							and b.EID = $EID 
							AND km.EID = $EID
							and et1.EID=b.EID
							and km.MCNo = b.CNo
							and km.TableNo = et1.TableNo
							and km.TableNo = b.TableNo
							and et1.TableNo = b.TableNo
							and et1.TableNo = et.TableNo 
							and date(b.billTime) = date(b1.billTime)
							and date(b.billTime) = date(km.LstModDt)
							GROUP by  b.TableNo, b.EID, date(b.billTime)
							),0) as TotalTime  from Eat_tables et, Billing b1 where  b1.billTime >= '$fromDate'
							AND b1.billTime <= '$toDate' and  et.EID=b1.EID and b1.EID=$EID group by et.TableNo, et.EID, date(b1.billTime)")
	        				->result_array();

	        			break;
	        			
	        		case 'KitchenOrders':

	        			$data = $this->db2->select("sum(Qty) as Quantity, sum(Qty * ItmRate) as OrderValue, date(LstModDt) as Date, HOUR(LstModDt) as Hour")
	        				->group_by('HOUR(LstModDt), date(LstModDt)')
	        				->get_where('Kitchen', array('LstModDt >=' => $fromDate,'LstModDt <=' => $toDate,'EID' => $EID, 'Stat' => 3)
	        					)
	        				->result_array();

	        			break;

	        		case 'Ratings':

	        			$data = $this->db2->select("date(LstModDt) as Date, BillId as BillNo, AVG(ServRtng) as Service, AVG(AmbRtng) as Ambience, AVG(VFMRtng) as VFM, avgBillRtng as Food")
	        				->group_by('BillId')
	        				->get_where('Ratings', array('LstModDt >=' => $fromDate,'LstModDt <=' => $toDate,'EID' => $EID)
	        					)
	        				->result_array();

	        			break;
	        			
        		
        	}

            $status = 'success';
		    $file_name = $_POST['reportType'].'_'.date('d-M-Y',strtotime($fromDate)).'_'.date('d-M-Y',strtotime($toDate));
		    if(!empty($data)){
		    	
		    	$this->export_csv($data, $file_name);
            	$response = 'Reports Downloaded'; 
            	// redirect(base_url('dashboard/'));
		    }else{
		    	$response = 'Record Not Found!';
		    	// $this->session->set_flashdata('error',$response);  
		    	$data[] = array('title' => 'Record Not Found');
		    	$this->export_csv($data, $file_name);
		    }
        }
	}

	// export csv
	private function export_csv($values, $file_name)
	{
	    $values =$values; // values as array 
	    $csv = tmpfile();

	    $bFirstRowHeader = true;
	    foreach ($values as $row) 
	    {
	        if ($bFirstRowHeader)
	        {
	            fputcsv($csv, array_keys($row));
	            $bFirstRowHeader = false;
	        }

	        fputcsv($csv, array_values($row));
	    }

	    rewind($csv);

	    $filename = $file_name.".csv";

	    $fstat = fstat($csv);
	    $this->setHeader($filename, $fstat['size']);

	    fpassthru($csv);
	    fclose($csv);
	}

	private function setHeader($filename, $filesize)
	{
	    // disable caching
	    $now = gmdate("D, d M Y H:i:s");
	    header("Expires: Tue, 01 Jan 2001 00:00:01 GMT");
	    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	    header("Last-Modified: {$now} GMT");

	    // force download  
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");
	    header('Content-Type: text/x-csv');

	    // disposition / encoding on response body
	    if (isset($filename) && strlen($filename) > 0)
	        header("Content-Disposition: attachment;filename={$filename}");
	    if (isset($filesize))
	        header("Content-Length: ".$filesize);
	    header("Content-Transfer-Encoding: binary");
	    header("Connection: close");
	}

}
