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

    	if (isset($_POST['type']) && $_POST['type'] == 'footfall') {

	        $ln_query = "SELECT year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) ) AS t, COUNT(CNo) as tot FROM KitchenMain where time(LstModDt)<'15:00:00'";
	        $dn_query = "SELECT year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) ) AS t, COUNT(CNo) as tot FROM KitchenMain where time(LstModDt)>='15:00:00'";

	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $ln_query.=" and LstModDt >='$from_date' and LstModDt <= '$to_date'";
	            $dn_query.=" and LstModDt >='$from_date' and LstModDt <= '$to_date'";
	        }
	        $ln_query.=" GROUP BY year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) )";
	        $dn_query.=" GROUP BY year(LstModDt),month(LstModDt),day(LstModDt),date(LstModDt),( HOUR( LstModDt ) )";
	        // print_r($q1);echo "<br>";print_r($q2);exit();
	        $footfalls_lunch = $this->db2->query($ln_query)->result_array();
	        $footfalls_dinner = $this->db2->query($dn_query)->result_array();

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

	        $q1 = "SELECT COUNT(BillId), date(PymtTime), sum(TotAmt) FROM Billing where time(PymtTime) <'15:00:00'";
	        $q2 = "SELECT COUNT(BillId), date(PymtTime), sum(TotAmt) FROM Billing where time(PymtTime) >='15:00:00'";

	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $q1.=" and PymtTime >='$from_date' and PymtTime <= '$to_date'";
	            $q2.=" and PymtTime >='$from_date' and PymtTime <= '$to_date'";
	        }
	        $q1.=" group by EID,date(PymtTime) ORDER BY date(PymtTime) DESC";
	        $q2.=" group by EID,date(PymtTime) ORDER BY date(PymtTime) DESC";
	        $order_value_lunch = $this->db2->query($q1)->result_array();
	        $order_value_dinner=$this->db2->query($q2)->result_array();
	        // print_r($ln_query);echo '   ';print_r($dn_query);exit();
	        $order_value_lunch_array_labal = [];
	        $order_value_lunch_array_value = [];
	        $order_value_dinner_array_value = [];
	        $payment_mode_lunch_array_labal = [];

	        foreach ($order_value_lunch as $key => $value) {
	            array_push($order_value_lunch_array_labal,$value['date(PymtTime)'] );
	            array_push($order_value_lunch_array_value,$value['sum(TotAmt)'] );
	            // array_push($payment_mode_lunch_array_labal,$value['PaymtMode'] );
	        }

	        foreach ($order_value_dinner as $key => $value) {
	            array_push($order_value_lunch_array_labal,$value['date(PymtTime)'] );
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

	        $q1 = "SELECT PaymtMode, COUNT(BillId), date(PymtTime) FROM Billing where PaymtMode != ''";

	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $q1.=" and PymtTime >='$from_date' and PymtTime <= '$to_date'";
	            // $q2.=" and PymtTime >='$from_date' and PymtTime <= '$to_date'";
	        }
	        $q1.=" group by PaymtMode,date(PymtTime) ORDER BY date(PymtTime) DESC";
	        // $q2.=" group by PaymtMode,date(PymtTime) ORDER BY date(PymtTime) DESC";
	        $order_value_lunch = $this->db2->query($q1)->result_array();
	        // $order_value_dinner=$billingObj->exec($q2);
	        // print_r($q1);exit();
	        // echo '   ';print_r($q2);exit();
	        $order_value_lunch_array_labal = [];
	        $order_value_lunch_array_value = [];
	        $order_value_dinner_array_value = [];
	        $payment_mode_lunch_array_labal = [];

	        foreach ($order_value_lunch as $key => $value) {
	            array_push($order_value_lunch_array_labal,$value['date(PymtTime)'] );
	            array_push($order_value_lunch_array_value,$value['COUNT(BillId)'] );
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

		if (isset($_POST['type']) && $_POST['type'] == 'TakeAways') {

	        $q="SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo) as totorders, SUM(k.Qty), SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where km.CNo=k.CNo and k.TA>0";

	        // $takeaways = $billingObj->exec("SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo) as totorders, SUM(k.Qty), SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where date(km.LstModDt)>='2020-12-29' and date(km.LstModDt)<='2020-12-29' and km.CNo=k.CNo and k.TA>0 GROUP BY date(km.LstModDt),( HOUR( km.LstModDt ) )");


	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $q.=" and date(km.LstModDt)>='$from_date' and date(km.LstModDt) <='$to_date'";
	        }
	        $q.=" GROUP BY date(km.LstModDt),( HOUR( km.LstModDt ) )";
	        $takeaways = $this->db2->query($q)->result_array();

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
	        $q = "SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo) as totorders, SUM(k.Qty), SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where km.CNo=k.CNo and k.SchCd>0";


	        // $offers = $billingObj->exec("SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo) as totorders, SUM(k.Qty), SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where date(km.LstModDt)>='2020-12-29' and date(km.LstModDt)<='2020-12-29' and km.CNo=k.CNo and k.SchCd>0 GROUP BY date(km.LstModDt),( HOUR( km.LstModDt ))");

	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $q.=" and date(km.LstModDt)>='$from_date' and date(km.LstModDt) <='$to_date'";
	        }
	        $q.=" GROUP BY date(km.LstModDt),( HOUR( km.LstModDt ) )";
	        $offers = $this->db2->query($q)->result_array();

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
	    if (isset($_POST['type']) && $_POST['type'] == 'trend90Days') {
	        $q = "SELECT day(b.PymtTime), date(b.PymtTime), COUNT(b.CNo) as tot, SUM(b.TotAmt), SUM(b.TotAmt)/200 totnos, sum(b.TotItemDisc) FROM Billing b where b.PymtTime != ''";
	        $period = 90;
	        if(isset($_POST['period'])){
	            $period = $_POST['period'];
	        }
	        $q.=" and PERIOD_DIFF(curDate(), date(b.PymtTime))<=$period";
	        $q.=" GROUP BY day(b.PymtTime), date(b.PymtTime)";
	        // print_r($q);exit();
	        $trends = $this->db2->query($q)->result_array();

	        $trends_array_labal = [];

	        $trends_totitemdisc_array_value = [];

	        $trends_totamt_array_value = [];

	        // echo "<pre>";print_r($trends);exit();
	        $data = [];
	        foreach($trends as $key) {
	            // print_r($key);exit();
	            $d = date('d/m', strtotime($key['date(b.PymtTime)']));
	            $a = [];
	            array_push($a, $d);
	            array_push($a, (double)$key['SUM(b.TotAmt)']);
	            // print_r($a);exit();
	            array_push($data, $a);

	        }


	        // print_r($data);exit();
	        echo json_encode($data);

	        exit;



	    }
	    if(isset($_POST) && $_POST['type'] == "tableWiseOccupencyLunch"){
	        $q = "SELECT b.TableNo, COUNT(b.CNo) as tot, SUM(b.TotAmt), SUM(b.TotAmt)/200 totnos, sum(b.TotItemDisc), TIME_FORMAT(sum(TIMEDIFF(b.PymtTime,km.LstModDt)),  '%H %i %s') as totTime FROM Billing b, KitchenMain km where (km.CNo=b.CNo OR km.MCNo=b.CNo) and time(km.LstModDt)<'15:00:00'";

	        $period = 90;
	        if(isset($_POST['period'])){
	            $period = $_POST['period'];
	        }
	        $q.=" and PERIOD_DIFF(curDate(), date(b.PymtTime))<=$period";
	        $q.=" GROUP BY day(b.PymtTime), date(b.PymtTime), b.TableNo";
	        // print_r($q);exit();
	        $lunch = $this->db2->query($q)->result_array();

	        // echo "<pre>";print_r($trends);exit();
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
	    if(isset($_POST) && $_POST['type'] == "tableWiseOccupencyDinner"){
	        $q = "SELECT b.TableNo, COUNT(b.CNo) as tot, SUM(b.TotAmt), SUM(b.TotAmt)/200 totnos, sum(b.TotItemDisc), TIME_FORMAT(sum(TIMEDIFF(b.PymtTime,km.LstModDt)),  '%H %i %s') as totTime FROM Billing b, KitchenMain km where (km.CNo=b.CNo OR km.MCNo=b.CNo) and time(km.LstModDt)>='15:00:00'";

	        $period = 90;
	        if(isset($_POST['period'])){
	            $period = $_POST['period'];
	        }
	        $q.=" and PERIOD_DIFF(curDate(), date(b.PymtTime))<=$period";
	        $q.=" GROUP BY day(b.PymtTime), date(b.PymtTime), b.TableNo";
	        // print_r($q);exit();
	        $lunch = $this->db2->query($q)->result_array();

	        // echo "<pre>";print_r($trends);exit();
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

	    if(isset($_POST) && $_POST['type'] == "TakeWays"){
	        $q = "SELECT  date(b.PymtTime), COUNT(b.CNo) as tot, SUM(b.TotAmt), SUM(b.TotAmt)/200 totnos, sum(b.TotItemDisc), sum(k.TA*k.Qty) as takeAways FROM Billing b, Kitchen k, KitchenMain km where k.TA=1 AND  (km.CNo=b.CNo OR km.MCNo=b.CNo) and k.Stat<>4 and k.Stat<>6 and k.Stat<>7 and k.Stat<>99";

	        $period = 90;
	        if(isset($_POST['period'])){
	            $period = $_POST['period'];
	        }
	        $q.=" and PERIOD_DIFF(curDate(), date(b.PymtTime))<=$period";
	        $q.=" GROUP BY day(b.PymtTime), date(b.PymtTime)";
	        // print_r($q);exit();
	        $takeaways = $this->db2->query($q)->result_array();

	        // echo "<pre>";print_r($trends);exit();
	        $data = [];
	        foreach($takeaways as $key) {
	            // print_r($key);exit();
	            $d = date('d/m', strtotime($key['date(b.PymtTime)']));
	            $a = [];
	            array_push($a, $d);
	            array_push($a, (double)$key['SUM(b.TotAmt)']);
	            // print_r($a);exit();
	            array_push($data, $a);

	        }


	        // print_r($data);exit();
	        echo json_encode($data);

	        exit;
	    }
	    if(isset($_POST) && $_POST['type'] == "TrendKitchenOrders"){
	        $q = "SELECT date(k.LstModDt), Sum(k.Qty) as totorders, SUM(k.ItmRate*k.Qty) FROM KitchenMain km, Kitchen k where km.CNo=k.CNo AND k.Stat<>4 and k.Stat<>6 and k.Stat<>7 and k.Stat<>99 ";

	        $period = 90;
	        if(isset($_POST['period'])){
	            $period = $_POST['period'];
	        }
	        $q.=" and PERIOD_DIFF(curDate(), k.LstModDt)<=$period";
	        $q.=" GROUP BY day(k.LstModDt), date(k.LstModDt)";
	        // print_r($q);exit();
	        $takeaways = $this->db2->query($q)->result_array();

	        // echo "<pre>";print_r($trends);exit();
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
	    if(isset($_POST) && $_POST['type'] == "TrendByWeek"){
	        $q = "SELECT WEEKOFYEAR(b.PymtTime), COUNT(b.CNo) as tot, SUM(b.TotAmt), SUM(b.TotAmt)/200 totnos, sum(b.TotItemDisc) FROM Billing b where WEEKOFYEAR(b.PymtTime) != ''";

	        $period = 14;
	        if(isset($_POST['period'])){
	            $period = $_POST['period'];
	        }
	        $q.=" and DATEDIFF(curDate(), date(b.PymtTime))<=$period";
	        $q.=" GROUP BY WEEKOFYEAR(b.PymtTime)";
	        // print_r($q);exit();
	        $takeaways = $this->db2->query($q)->result_array();

	        // echo "<pre>";print_r($trends);exit();
	        $data = [];
	        foreach($takeaways as $key) {
	            // print_r($key);exit();
	            $d = $key['WEEKOFYEAR(b.PymtTime)'];
	            $a = [];
	            array_push($a, $d);
	            array_push($a, (double)$key['tot']);
	            // print_r($a);exit();
	            array_push($data, $a);

	        }


	        // print_r($data);exit();
	        echo json_encode($data);

	        exit;
	    }
	    if(isset($_POST) && $_POST['type'] == "RatingsTrend"){
	        $q = "SELECT r.LstModDt, COUNT(r.BillId), AVG(r.ServRtng), AVG(r.AmbRtng), AVG(r.VFMRtng), AVG(rd.ItemRtng) FROM Ratings r, RatingDet rd where r.RCd=rd.RCd";

	        $period = 14;
	        if(isset($_POST['period'])){
	            $period = $_POST['period'];
	        }
	        $q.=" and DATEDIFF(curDate(), date(r.LstModDt))<=$period";
	        $q.=" GROUP by r.LstModDt ORDER by r.LstModDt DESC";
	        // print_r($q);exit();
	        $takeaways = $this->db2->query($q)->result_array();

	        // echo "<pre>";print_r($trends);exit();
	        $data = [['No of Orders', 'Serv Rating', 'Amb Rating', 'VFM Rating', 'Item Rating']];
	        foreach($takeaways as $key) {
	            // print_r($key);exit();
	            $d = $key['COUNT(r.BillId)'];
	            $a = [];
	            array_push($a, $d);
	            array_push($a, (double)$key['AVG(r.ServRtng)']);
	            array_push($a, (double)$key['AVG(r.AmbRtng)']);
	            array_push($a, (double)$key['AVG(r.VFMRtng)']);
	            array_push($a, (double)$key['AVG(rd.ItemRtng)']);
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
		if (isset($_POST['type']) && $_POST['type'] == 'RevenueAndDiscounts') {

	        $q = "SELECT date(km.LstModDt), COUNT(km.CNo) as tot, SUM(b.TotAmt), sum(b.TotItemDisc) FROM KitchenMain km, Billing b where km.BillStat=b.BillId";

	        // $revenue_and_discounts = $billingObj->exec("SELECT date(km.LstModDt), COUNT(km.CNo) as tot, SUM(b.TotAmt), sum(b.TotItemDisc) FROM KitchenMain km, Billing b where date(km.LstModDt)>='2020-12-23' and date(km.LstModDt) <='2020-12-29' and km.BillStat=b.BillId ");


	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $q.=" and date(km.LstModDt)>='$from_date' and date(km.LstModDt) <='$to_date'";
	        }
	        $q.=" GROUP BY date(km.LstModDt)";
	        // print_r($q);exit();
	        $revenue_and_discounts = $this->db2->query($q)->result_array();
	        $revenue_and_discounts_array_labal = [];

	        $revenue_and_discounts_totitemdisc_array_value = [];

	        $revenue_and_discounts_totamt_array_value = [];



	        foreach ($revenue_and_discounts as $key => $value) {

	            array_push($revenue_and_discounts_array_labal,$value['date(km.LstModDt)'] );

	            array_push($revenue_and_discounts_totitemdisc_array_value,$value['sum(b.TotItemDisc)'] );

	            array_push($revenue_and_discounts_totamt_array_value,$value['SUM(b.TotAmt)'] );

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


	        // $revenue_and_discounts = $billingObj->exec("SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t,

	        // COUNT(k.OrdNo) as totorders, SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where date(km.LstModDt)>='2020-12-29' and date(km.LstModDt)<='2020-12-29' and km.CNo=k.CNo GROUP BY date(km.LstModDt),( HOUR( km.LstModDt ) )");
	        // print_r($_POST);exit();
	        $q = "SELECT date(km.LstModDt),( HOUR( km.LstModDt ) ) AS t, COUNT(k.OrdNo) as totorders, SUM(k.ItmRate) FROM KitchenMain km, Kitchen k where km.CNo=k.CNo";
	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $q.=" and date(km.LstModDt)>='$from_date' and date(km.LstModDt) <='$to_date'";
	        }
	        $q.=" GROUP BY date(km.LstModDt),( HOUR( km.LstModDt ) )";
	        // print_r($q);exit();
	        $revenue_and_discounts = $this->db2->query($q)->result_array();
	        $revenue_and_discounts_array_labal=[];

	        $revenue_and_discounts_totitemdisc_array_value=[];

	        $revenue_and_discounts_totamt_array_value=[]; 

	        

	        foreach ($revenue_and_discounts as $key=> $value) {

	            array_push($revenue_and_discounts_array_labal,$value['t'] );

	            array_push($revenue_and_discounts_totitemdisc_array_value,$value['totorders'] );

	            array_push($revenue_and_discounts_totamt_array_value,$value['SUM(k.ItmRate)'] );

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

	        $q = "SELECT date(b.PymtTime),( HOUR(b.PymtTime) ) AS t, COUNT(b.BillId) as totbills, SUM(r.RCd) FROM Billing b, Ratings r where  b.BillId=r.BillId";

	        // $revenue_and_discounts = $billingObj->exec("SELECT date(b.PymtTime),( HOUR(b.PymtTime) ) AS t, COUNT(b.BillId) as totbills, SUM(r.RCd) FROM Billing b, Ratings r where date(b.PymtTime)>='2020-12-29' and date(b.PymtTime)<='2020-12-29' and b.BillId=r.BillId GROUP BY date(b.PymtTime),( HOUR(b.PymtTime) )");
	        if(isset($_POST['date'])){
	            $range = explode("-", $_POST['date']);
	            $from_date = date('Y-m-d', strtotime(trim($range[0])));
	            $to_date = date('Y-m-d', strtotime(trim($range[1])));
	            // echo $from_date.'    '.$to_date;exit();
	            $q.=" and date(b.PymtTime)>='$from_date' and date(b.PymtTime) <='$to_date'";
	        }
	        $q.=" GROUP BY date(b.PymtTime),( HOUR(b.PymtTime) )";
	        $revenue_and_discounts = $this->db2->query($q)->result_array();

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

}
