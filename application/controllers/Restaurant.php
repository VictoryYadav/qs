<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurant extends CI_Controller {

    private $db2;
	public function __construct()
	{
		parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->authuser = $this->session->userdata('logged_in');
        } else {
            redirect(base_url());
        }
		$this->load->model('Rest', 'rest');

        $this->lang->load('message','english');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
        
        $this->output->delete_cache();

	}

    public function index1(){
        // $folderPath = '/var/www/eo.vtrend.org/public_html/uploads/e'.authuser()->EID;
        $d = FCPATH.'public_html\uploads\e51';
        print_r($d);
        echo "<br>";
        print_r(BASEPATH);
        echo "<br>";
        print_r(APPPATH);
        echo "<br>";
        die;
        $data['title'] = 'Dashboard';
        $this->load->view('rest/index',$data);
    }

    public function index(){
        $data['title'] = "Dashboard";
        $this->load->view('dashboard/index',$data);
    }


    public function customer_graph(){

        $ChainId    = authuser()->ChainId;
        $EID        = authuser()->EID;

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
            'payment_mode_lunch_label' => $payment_mode_lunch_array_labal,
            'status' => 2
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function rest_graph(){
        $ChainId    = authuser()->ChainId;
        $EID        = authuser()->EID;

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
                $a = [];
                array_push($a, $key['TableNo']);
                array_push($a, (double)$key['SUM(b.TotAmt)']);
                array_push($data, $a);

            }
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
                $d = date('d/m', strtotime($key['date(k.LstModDt)']));
                $a = [];
                array_push($a, $d);
                array_push($a, (double)$key['totorders']);
                array_push($data, $a);
            }
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
                
                $d = $key['COUNT(r.BillId)'];
                $a = [];
                
                array_push($a, $key['date1']);
                array_push($a, (double)$key['AVG(r.ServRtng)']);
                array_push($a, (double)$key['AVG(r.AmbRtng)']);
                array_push($a, (double)$key['AVG(r.VFMRtng)']);
                array_push($a, (double)$key['avgBillRtng']);
                
                array_push($data, $a);

            }
            
            echo json_encode($data);

            exit;
        }
    }

    public function food_graph(){
        $ChainId    = authuser()->ChainId;
        $EID        = authuser()->EID;

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
                        //  ->result_array();

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

    function switchLang() {
        // https://www.codexworld.com/multi-language-implementation-in-codeigniter/
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            extract($_POST);
            $langId = ($langId != "") ? $langId : 1;
            $langName = ($langName != "") ? $langName : 'english';
            $this->session->set_userdata('site_lang', $langId);
            $this->session->set_userdata('site_langName', $langName);
            $response = $langId;
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        
    }

    public function add_user(){
        // $this->check_access();
        $status = 'error';
        $response = 'Something went wrong please try again!!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            
            if($_POST['RUserId'] > 0){
                $res = getRecords('UsersRest', array('RUserId' => $_POST['RUserId']));
                $udata = $_POST;
                $udata['DOB'] = date('Y-m-d', strtotime($udata['DOB'])); 
                updateRecord('UsersRest', $udata, array('RUserId' => $_POST['RUserId'], 'EID' => authuser()->EID));
                $genInsert = 0;
                if($res['Stat'] != $udata['Stat']){
                    $genInsert = 1;
                }
                if($res['UTyp'] != $udata['UTyp']){
                    $genInsert = 1;
                }
                if($res['RestRole'] != $udata['RestRole']){
                    $genInsert = 1;
                }

                if($genInsert == 1){
                    unset($udata['UTyp']);
                    unset($udata['RestRole']);
                    $genDB = $this->load->database('GenTableData', TRUE);
                    $genDB->insert('UsersRest', $udata);
                }

                $res = 'Records Updated';
            }else{
                $this->rest->addUser($_POST);
                $res = 'User Created.';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'success',
                'response' => $res
              ));
             die;
        }

		$data['title'] = $this->lang->line('addUser');
        $data['EID'] = authuser()->EID;
        $data['users'] = $this->rest->getUserList();
        $data['restRole'] = $this->rest->getUserRestRole();
        $data['userType'] = $this->rest->getUserTypeList();
        
		$this->load->view('rest/add_user',$data);
    }

    public function user_access(){
        $this->check_access();
        if($this->input->method(true)=='POST'){
            $res = $this->rest->getUserAccessRole($_POST);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'success',
                'response' => $res
              ));
             die;
        }
        
        $data['usersRestData'] = $this->rest->getusersRestData(); 
        $data['title'] = $this->lang->line('userAccess');
        $this->load->view('rest/access_users',$data);
    }

    public function role_assign(){
        $this->check_access();
        $staus = 'error';
        $response = 'Something went wrong please try again!';
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            $data = $_POST;
            $RUserId = $data['RUserId'];
            
            $data['KitCd'] = !empty($data['KitCd'])?implode(",",$data['KitCd']):'';
            $data['DCd'] = !empty($data['DCd'])?implode(",",$data['DCd']):'';
            $data['CCd'] = !empty($data['CCd'])?implode(",",$data['CCd']):'';
            $data['LoginCd'] = authuser()->RUserId;
            
            $check = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();

            if(!empty($check)){
                unset($data['RUserId']);
                updateRecord('UsersRoleDaily', $data, array('RUserId' => $RUserId));
                $res = 'Role Assigned Updated.';
            }else{
                insertRecord('UsersRoleDaily',$data);
                $res = 'Role Assigned Successfully.';
            }
            redirect(base_url('restaurant/role_assign'));
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
            
        }
        
        $data['usersRestData'] = $this->rest->getusersRestData();        

        $data['title'] = $this->lang->line('roleAssignment');
        $this->load->view('rest/assign_role',$data);   
    }

    public function getRoleData(){
        $EID = authuser()->EID;
        $status = 'error';
        $response = 'Some thing went wrong Please try again!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $RUserId = $_POST['RUserId'];

            $roleData = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();
            $kitval = array();
            $disval = array();
            $cashval = array();
            if(!empty($roleData)){
                $kitval = !empty($roleData['KitCd'])?explode(",",$roleData['KitCd']):array();
                $disval = !empty($roleData['DCd'])?explode(",",$roleData['DCd']):array();
                $cashval = !empty($roleData['CCd'])?explode(",",$roleData['CCd']):array();
            }

            $langId = $this->session->userdata('site_lang');
            $KitName = "Name$langId as KitName";

            $kitData = $this->rest->get_kitchen();
            $kitchen = '';
            foreach ($kitData as $kit) {
                $checked_kit = '';
                if(in_array($kit['KitCd'], $kitval))
                { 
                    $checked_kit = "checked"; 
                }
                $kitchen .='<div class="form-check-inline">
                  <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="'.$kit['KitCd'].'" name="KitCd[]" '.$checked_kit.'>'.$kit['KitName'].'
                  </label>
                </div>';
            }

            $disData = $this->rest->getDispenseOutletList();
            $dispense = '';
            foreach ($disData as $kit) {
                $checked_dis = '';
                if(in_array($kit['DCd'], $disval))
                { 
                    $checked_dis = "checked"; 
                }

                $dispense .='<div class="form-check-inline">
                  <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="'.$kit['DCd'].'" name="DCd[]" '.$checked_dis.'>'.$kit['Name'].'
                  </label>
                </div>';
            }

            $casherData = $this->rest->getCashierList();

            $cashier = '';
            foreach ($casherData as $kit) {
                $checked_cash = '';
                if(in_array($kit['CCd'], $cashval))
                { 
                    $checked_cash = "checked"; 
                }

                $cashier .='<div class="form-check-inline">
                  <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="'.$kit['CCd'].'" name="CCd[]" '.$checked_cash.'>'.$kit['Name'].'
                  </label>
                </div>';
            }

            $chefT = $this->lang->line('chef');
            $dispenseT = $this->lang->line('dispense');
            $cashierT = $this->lang->line('cashier');
            $roleT = $this->lang->line('role');
            $ARoleT = $this->lang->line('assignedRoles');
            $submitT = $this->lang->line('submit');
            
            $data['createForm'] = '<form class="mt-2" id="roleAssignForm" method="POST">
                <input type="hidden" name="RUserId" value="'.$RUserId.'">
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>'.$roleT.'</th>
                        <th>'.$ARoleT.'</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>'.$chefT.'</td>
                            <td>'.$kitchen.'</td>
                        </tr>

                        <tr>
                            <td>'.$dispenseT.'</td>
                            <td>'.$dispense.'</td>
                        </tr>

                        <tr>
                            <td>'.$cashierT.'</td>
                            <td>'.$cashier.'</td>
                        </tr>
                    </tbody>
                  </table>
              </div>
              <div class="text-center">
                <button class="btn btn-sm btn-success" onclick="submitData()">'.$submitT.'</button>
              </div>
            </form>';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }
    }

    public function offers_list(){
        $this->check_access();
		 $data['title'] = $this->lang->line('offerList');
		 $data['offers'] = $this->rest->getOffersList();
		 $this->load->view('rest/offer_lists',$data);
    }

    public function new_offer(){
        // $this->check_access();
        $data['languages'] = langMenuList();
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $ChainId = authuser()->ChainId;
            
            $CustOffers['LoginCd'] = authuser()->RUserId;
            $CustOffers['EID'] = $EID;
            $CustOffers['ChainId'] = $ChainId;
            $CustOffers['SchNm1'] = $_POST['SchNm1'];
            if(!empty($data['languages'])){
                $languages = $data['languages'];
            for ($i = 1; $i < sizeof($languages); $i++) { 
                $LCd = $languages[$i]['LCd'];
                $SchNms = "SchNm$LCd";
                $CustOffers[$SchNms] = $_POST[$SchNms];
            } }
            
            $CustOffers['SchTyp'] = $_POST['SchTyp'];
            $CustOffers['SchCatg'] = $_POST['SchCatg']; 
            $CustOffers['FrmDayNo'] = $_POST['FromDayNo'];
            $CustOffers['ToDayNo'] = $_POST['ToDayNo']; 
            $CustOffers['FrmTime'] = $_POST['FrmTime'];
            $CustOffers['ToTime'] = $_POST['ToTime'];
            $CustOffers['AltFrmTime'] = $_POST['AltFrmTime'];
            $CustOffers['AltToTime'] = $_POST['AltToTime'];
            $CustOffers['FrmDt'] = $_POST['FrmDt'];
            $CustOffers['ToDt'] = $_POST['ToDt'];
            $CustOffers['offerType'] = $_POST['offerType'];
            
            $SchCd = insertRecord('CustOffers', $CustOffers);
            
            if(!empty($SchCd)){
                $updat['PromoCode'] = $SchCd.'~'.$EID.'~'.$ChainId.'~'.$_POST['SchTyp'].'~'.$_POST['SchCatg'];
                updateRecord('CustOffers', $updat, array('SchCd' => $SchCd));
            }

            $CustOffersDet = [];
            $temp = [];
            if(!empty($_POST['SchDesc1'])){
                for($i = 0;$i<sizeof($_POST['SchDesc1']);$i++){
                    if(!empty($_POST['SchDesc1'][$i])){
                        if(isset($_FILES['description_image']['name']) && !empty($_FILES['description_image']['name'])){ 

                            $files = $_FILES['description_image'];
                            $_FILES['description_image']['name']= $files['name'][$i];
                            $_FILES['description_image']['type']= $files['type'][$i];
                            $_FILES['description_image']['tmp_name']= $files['tmp_name'][$i];
                            $_FILES['description_image']['error']= $files['error'][$i];
                            $_FILES['description_image']['size']= $files['size'][$i];
                            $file = str_replace(' ', '_', rand('10000','999').'_'.$files['name'][$i]);

                            $folderPath = "uploads/e$EID/offers/";
                            if (!file_exists($folderPath)) {
                                // Create the directory
                                mkdir($folderPath, 0777, true);
                            }

                            $res = do_upload('description_image',$file, $folderPath,'*');
                            $temp['SchImg'] = $file;
                        }

                        $temp['SchCd'] = $SchCd;
                        $temp['SchDesc1'] = $_POST['SchDesc1'][$i];

                        if(!empty($data['languages'])){
                            $languages = $data['languages'];
                        for ($j = 1; $j < sizeof($languages); $j++) { 
                            $LCd = $languages[$j]['LCd'];
                            $SchDesc = "SchDesc$LCd";
                            $temp[$SchDesc] = $_POST[$SchDesc][$i];
                        } }

                        $temp['CID'] = !empty($_POST['description_cid'][$i])?$_POST['description_cid'][$i]:0;
                        $temp['MCatgId'] = !empty($_POST['description_mcatgid'][$i])?$_POST['description_mcatgid'][$i]:0;

                        $temp['Disc_CID'] = !empty($_POST['description_disc_cid'][$i])?$_POST['description_disc_cid'][$i]:0;
                        $temp['Disc_MCatgId'] = !empty($_POST['description_disc_mcatgid'][$i])?$_POST['description_disc_mcatgid'][$i]:0;

                        $temp['ItemTyp'] = !empty($_POST['description_itemtyp'][$i])?$_POST['description_itemtyp'][$i]:0;
                        $temp['ItemId'] = !empty($_POST['description_item'][$i])?$_POST['description_item'][$i]:0;
                        $temp['IPCd'] = !empty($_POST['description_itemportion'][$i])?$_POST['description_itemportion'][$i]:0;
                        $temp['Qty'] = !empty($_POST['description_quantity'][$i])?$_POST['description_quantity'][$i]:0;
                        $temp['Disc_ItemId'] = !empty($_POST['description_discountitem'][$i])?$_POST['description_discountitem'][$i]:0;
                        $temp['Disc_IPCd'] = !empty($_POST['description_discountitemportion'][$i])?$_POST['description_discountitemportion'][$i]:0;
                        $temp['Disc_Qty'] = !empty($_POST['description_discountquantity'][$i])?$_POST['description_discountquantity'][$i]:0;
                        $temp['DiscItemPcent'] = !empty($_POST['description_discountitempercentage'][$i])?$_POST['description_discountitempercentage'][$i]:0;
                        $temp['ItemSale'] = !empty($_POST['description_itemsales'][$i])?$_POST['description_itemsales'][$i]:0;

                        $temp['Disc_ItemTyp'] = !empty($_POST['description_discitemtyp'][$i])?$_POST['description_discitemtyp'][$i]:0;

                        $temp['DiscMaxAmt'] = !empty($_POST['description_disc_max_amt'][$i])?$_POST['description_disc_max_amt'][$i]:0;

                        $temp['MinBillAmt'] = !empty($_POST['description_minbillamount'][$i])?$_POST['description_minbillamount'][$i]:0;
                        $temp['Disc_pcent'] = !empty($_POST['description_discountpercent'][$i])?$_POST['description_discountpercent'][$i]:0;
                        $temp['Disc_Amt']   = !empty($_POST['description_discountamount'][$i])?$_POST['description_discountamount'][$i]:0; 

                        $temp['Rank'] = 1;
                        $temp['Stat'] = 0;

                        $CustOffersDet[] = $temp;
                    }
                }

                $this->db2->insert_batch('CustOffersDet', $CustOffersDet); 
            }
            $this->session->set_flashdata('success','Offer has been added.'); 
            redirect(base_url('restaurant/offers_list'));
        }

    	$data['title'] = $this->lang->line('newOffer');

        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['cuisines'] = $this->rest->getCuisineList();
        $data['menucat'] = $this->rest->get_MCatgId();
        $data['portions'] = $this->rest->get_item_portion();
        $data['menuTags'] = $this->rest->getMenuTagList();

		$this->load->view('rest/add_new_offer',$data);	
    }

    public function offer_ajax(){
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $data['languages'] = langMenuList();
        if (isset($_POST['updateOffer'])) {
            
            $SchCd = $_POST['SchCd'];
            $CustOffers['SchNm1'] = $_POST['SchNm1'];
            
            if(!empty($data['languages'])){
                $languages = $data['languages'];
            for ($i = 1; $i < sizeof($languages); $i++) { 
                $LCd = $languages[$i]['LCd'];
                $SchNms = "SchNm$LCd";
                $CustOffers[$SchNms] = $_POST[$SchNms];
            } }

            // $CustOffers['SchTyp'] = $_POST['SchTyp'];
            // $CustOffers['SchCatg'] = $_POST['SchCatg']; 
            $CustOffers['FrmDayNo'] = $_POST['FromDayNo'];
            $CustOffers['ToDayNo'] = $_POST['ToDayNo']; 
            $CustOffers['FrmTime'] = $_POST['FrmTime'];
            $CustOffers['ToTime'] = $_POST['ToTime'];
            $CustOffers['AltFrmTime'] = $_POST['AltFrmTime'];
            $CustOffers['AltToTime'] = $_POST['AltToTime'];
            $CustOffers['FrmDt'] = $_POST['FrmDt'];
            $CustOffers['ToDt'] = $_POST['ToDt'];
            $CustOffers['EID'] = authuser()->EID;
            $CustOffers['Stat'] = $_POST['Stat'];
            $CustOffers['offerType'] = $_POST['offerType'];

            updateRecord('CustOffers',$CustOffers, array('SchCd' => $SchCd, 'EID' => $EID));
            if(!empty($_POST['SchDesc1'])){
                for($i = 0;$i<sizeof($_POST['SchDesc1']);$i++){
                    if(!empty($_POST['SchDesc1'][$i])){
                        if(isset($_FILES['description_image']['name']) && !empty($_FILES['description_image']['name'])){ 

                            $files = $_FILES['description_image'];
                            $_FILES['description_image']['name']= $files['name'][$i];
                            $_FILES['description_image']['type']= $files['type'][$i];
                            $_FILES['description_image']['tmp_name']= $files['tmp_name'][$i];
                            $_FILES['description_image']['error']= $files['error'][$i];
                            $_FILES['description_image']['size']= $files['size'][$i];
                            $file = str_replace(' ', '_', rand('10000','999').'_'.$files['name'][$i]);

                            $folderPath = "uploads/e$EID/offers/";
                            if (!file_exists($folderPath)) {
                                // Create the directory with file permission
                                mkdir($folderPath, 0777, true);
                            }

                            $res = do_upload('description_image',$file, $folderPath,'*');
                            $CustOffersDet['SchImg'] = $file;
                        }

                        $CustOffersDet['SchCd'] = $SchCd;
                        $CustOffersDet['SchDesc1'] = $_POST['SchDesc1'][$i];

                        if(!empty($data['languages'])){
                            $languages = $data['languages'];
                        for ($i = 1; $i < sizeof($languages); $i++) { 
                            $LCd = $languages[$i]['LCd'];
                            $description = "SchDesc$LCd";
                            $CustOffersDet[$description] = $_POST[$description];
                        } }

                        $CustOffersDet['CID'] = isset($_POST['description_cid'][$i])?$_POST['description_cid'][$i]:0;
                        $CustOffersDet['MCatgId'] = isset($_POST['description_mcatgid'][$i])?$_POST['description_mcatgid'][$i]:0;
                        $CustOffersDet['ItemTyp'] = isset($_POST['description_itemtyp'][$i])?$_POST['description_itemtyp'][$i]:0;
                        $CustOffersDet['ItemId'] = isset($_POST['description_item'][$i])?$_POST['description_item'][$i]:0;
                        $CustOffersDet['ItemSale'] = isset($_POST['description_itemsales'][$i])?$_POST['description_itemsales'][$i]:0;
                        $CustOffersDet['IPCd'] = $_POST['description_itemportion'][$i];
                        $CustOffersDet['Qty'] = $_POST['description_quantity'][$i];

                        $CustOffersDet['Disc_CID'] = !empty($_POST['description_disc_cid'][$i])?$_POST['description_disc_cid'][$i]:0;
                        $CustOffersDet['Disc_MCatgId'] = !empty($_POST['description_disc_mcatgid'][$i])?$_POST['description_disc_mcatgid'][$i]:0;
                        $CustOffersDet['Disc_ItemId'] = $_POST['description_discountitem'][$i];
                        $CustOffersDet['Disc_IPCd'] = $_POST['description_discountitemportion'][$i];
                        $CustOffersDet['Disc_Qty'] = $_POST['description_discountquantity'][$i];
                        
                        $CustOffersDet['DiscItemPcent'] = $_POST['description_discountitempercentage'][$i];
                        $CustOffersDet['Disc_ItemTyp'] = !empty($_POST['description_discitemtyp'][$i])?$_POST['description_discitemtyp'][$i]:0;

                        $CustOffersDet['DiscMaxAmt'] = $_POST['description_disc_max_amt'][$i];
                        $CustOffersDet['MinBillAmt'] = $_POST['description_minbillamount'][$i];
                        $CustOffersDet['Disc_pcent'] = $_POST['description_discountpercent'][$i];
                        $CustOffersDet['Disc_Amt'] = $_POST['description_discountamount'][$i];

                        $CustOffersDet['Rank'] = 1;
                        $CustOffersDet['Stat'] = 0;

                        if(isset($_POST['SDetCd'][$i]) && !empty($_POST['SDetCd'][$i])){
                            $SDetCd = $_POST['SDetCd'][$i];
                            updateRecord('CustOffersDet',$CustOffersDet, array('SDetCd' => $SDetCd));
                        }else{
                            recordInsert('CustOffersDet', $CustOffersDet);
                        }
                    }
                }
            }
            $this->session->set_flashdata('success','Offer has been updated.'); 
            redirect(base_url('restaurant/offers_list'));
        }
        if(isset($_POST['getCategory']) && $_POST['getCategory']){
            $CID = $_POST['cid'];
            
            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as MCatgNm";

            $categories = $this->rest->getMenuCatListByCID($EID, $CID);

            echo json_encode($categories);
        }
        if(isset($_POST['getItems']) && $_POST['getItems']){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as ItemNm";

            $mcatgid = $_POST['mcatgid'];
            if($mcatgid > 0){
                $this->db2->where('MCatgId', $mcatgid);
            }
            $items = $this->db2->select("ItemId, $lname")->get_where('MenuItem', array('EID' => $EID, 'Stat' => 0))->result_array();
            echo json_encode($items);
        }

        if(isset($_POST['getItemsBySale']) && $_POST['getItemsBySale']){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as ItemNm";

            $ItemSale = $_POST['ItemSale'];
            if($ItemSale > 0){
                $this->db2->where('ItemSale', $ItemSale);
            }
            $items = $this->db2->select("ItemId, $lname")->get_where('MenuItem', array('EID' => $EID, 'Stat' => 0))->result_array();
            echo json_encode($items);
        }

        if(isset($_POST['getAllItemsList']) && $_POST['getAllItemsList']){
            $items = $this->rest->getAllItemsList();
            echo json_encode($items);
        }

        if(isset($_POST['getItemPortion']) && $_POST['getItemPortion']){
            $item_id = $_POST['item_id'];

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as Name";
            $portions = $this->db2->select("IPCd, $lname")
                        ->join('MenuItemRates mir','mir.Itm_Portion = ip.IPCd', 'inner')
                           ->get_where('ItemPortions ip', array('mir.ItemId' => $item_id, 'mir.EID' => $EID))
                           ->result_array();            
            echo json_encode($portions);
        }
        
        if(isset($_POST['delete_offer_description']) && $_POST['delete_offer_description']){
            $SDetCd = $_POST['SDetCd'];
            $this->db2->query("UPDATE CustOffersDet set Stat = 9 where SDetCd = ".$SDetCd);
            echo 1;
        }

    }

    public function edit_offer($SchCd){
        // $this->check_access();
        $data['languages'] = langMenuList();
        $data['title'] = $this->lang->line('editOffer');
        
        $data['sch_typ'] = $this->rest->getOffersSchemeType();
        $data['cuisines'] = $this->rest->getCuisineList();
        $data['menucat'] = $this->rest->get_MCatgId();
        $data['itemList'] = $this->rest->getAllItemsList();
        $data['portions'] = $this->rest->get_item_portion();
        $data['weekDay'] = $this->rest->getWeekDayList();

        $EID = authuser()->EID;
        $data['SchCd'] = $SchCd;

        $langId = $this->session->userdata('site_lang');

        $scName = "SchNm$langId as SchNm";
        $data['scheme'] = $this->db2->select("*, $scName")->get_where('CustOffers', array('SchCd' => $SchCd, 'EID' => $EID))->row_array();

        $scDesc = "SchDesc$langId as SchDesc";
        $data['descriptions'] = $this->db2->select("*, $scDesc")->get_where('CustOffersDet', array('SchCd' =>$SchCd,'Stat' => 0))->result_array();
        $data['menuTags'] = $this->rest->getMenuTagList();

        $this->load->view('rest/offer_edit',$data);  
    }

    public function item_list(){
        $this->check_access();

        $EID = authuser()->EID;
        $data['CID'] = '';
        $data['catid'] = '';
        $data['filter'] = '';
        $data['SecId'] = '';
        
        $langId = $this->session->userdata('site_lang');
        $lname = "mi.Name$langId as ItemNm";
        $ipName = "ip.Name$langId as Portions";
        $esName = "es.Name$langId as Sections";

        $data['cuisine'] = $this->rest->getCuisineList();
        $data['menucat'] = $this->rest->get_MCatgId();

        $menuItemData = $this->db2->select("mi.ItemId, $lname, $ipName, mr.OrigRate, mr.ItmRate, mr.IRNo, $esName, mi.Stat")
                        ->order_by('mi.CID,mi.MCatgId, mi.Name1', 'ASC')
                        // ->group_by('mi.ItemId')
                        ->join('MenuItemRates mr', 'mr.ItemId = mi.ItemId', 'inner')
                        ->join('ItemPortions ip', 'ip.IPCd = mr.Itm_Portion', 'inner')
                        ->join('Eat_Sections es', 'es.SecId = mr.SecId', 'inner');
                        
        if($this->input->method(true)=='POST'){

            if($_POST){
                if(isset($_POST['cuisine']) && !empty($_POST['cuisine'])){
                    $CID = $_POST['cuisine'];
                    $menuItemData->where('mi.CID', $CID);

                    $mcname = "Name$langId as MCatgNm";
                    $data['menucat'] = $this->db2->query("SELECT *, $mcname from MenuCatg where CID = $CID and EID = $EID")->result_array();
                    $data['CID'] = $CID;
                }
                if(isset($_POST['menucat']) && !empty($_POST['menucat'])){
                    $catid = $_POST['menucat'];
                    $menuItemData->where('mi.MCatgId', $catid);
                    $data['catid'] = $catid;
                }

                if(isset($_POST['SecId']) && !empty($_POST['SecId'])){
                    $SecId = $_POST['SecId'];
                    $menuItemData->where('mr.SecId', $SecId);
                    $data['SecId'] = $SecId;
                }

                if(isset($_POST['filter']) && !empty($_POST['filter'])){
                    $data['filter'] = $_POST['filter'];
                    if($data['filter'] == 'draft'){
                        $menuItemData->where('mr.OrigRate', 0);
                    }else if($data['filter'] == 'enabled'){
                        $menuItemData->where('mi.Stat', 0);
                    }else if($data['filter'] == 'disabled'){
                        $menuItemData->where('mi.Stat', 1);
                    }else if($data['filter'] == 'extras'){
                        $menuItemData->where('mi.Stat', 5);
                    }
                }
            }
        }

        $menuItemData = $menuItemData->get_where('MenuItem mi', array(
                                        'mi.EID' => $EID,
                                        'mr.EID' => $EID
                                        )
                                )
                        ->result_array();

        $data['menuItemData'] = $menuItemData;
        $data['sections'] = $this->rest->getSectionList();
        
    	$data['title'] = 'Menu Rate Listing';
		$this->load->view('rest/item_lists',$data);	
    }

    public function item_list_get_category(){
        if($_POST){
            $CID = $_POST['CID'];
            $EID = authuser()->EID;

            $data = $this->rest->getMenuCatListByCID($EID, $CID);

            echo json_encode($data);
        }
    }

    public function order_dispense(){
        $this->check_access();
    	$data['title'] = $this->lang->line('orderDispense');
        $data['RestName'] = authuser()->RestName;
        $data['RUserId'] = authuser()->RUserId;
        $data['Cash'] = $this->session->userdata('Cash');
        $data['EType'] = $this->session->userdata('EType');
        $Fest = $this->session->userdata('Fest');
        $data['CheckOTP'] = $this->session->userdata('DeliveryOTP');
        $data['EID'] = authuser()->EID;
        $data['DispenseAccess'] = $this->rest->getDispenseAccess();
        $data['dispenseMode'] = $this->rest->getDispenseModes();

		$this->load->view('rest/dispense_orders',$data);	
    }

    public function order_delivery(){
        $Fest = $this->session->userdata('Fest');
        $langId = $this->session->userdata('site_lang');
        if($this->input->method(true) == 'POST'){
            $AutoAllot = $this->session->userdata('AutoAllot');
            $AutoDeliver = $this->session->userdata('AutoDeliver');
            $EType = $this->session->userdata('EType');
            
            $EID = authuser()->EID;

            if (isset($_POST['getOrderDetails']) && !empty($_POST['getOrderDetails'])) {
                
                if (isset($_POST['DispCd'])) {
                    $DCd = $_POST['DispCd'];
                } else {
                    $DCd = 0;
                }
                
                $dispMode = (isset($_POST['dispMode']) && $_POST['dispMode'] > 0 )?$_POST['dispMode']:0;
                
                if($dispMode > 0){
                    $this->db2->where('km.OType', $dispMode);
                }

                $langId = $this->session->userdata('site_lang');
                $partyName = "p.Name$langId as thirdPartyName";

                $kitchenData = $this->db2->select("b.BillId, b.BillNo, sum(k.Qty) as Qty, k.OType, k.TPRefNo, k.TPId, km.CustId, k.CellNo, k.EID, k.DCd, km.CNo, $partyName, (SELECT IF(min(k1.KStat)=0,0,5) FROM Kitchen k1 where k1.CNo=km.CNo and k1.DCd=k.DCd and k1.EID=km.EID group by k1.CNo) as KStat")
                                    ->order_by('b.BillId', 'Asc')
                                    ->group_by('b.BillId, k.DCd')
                                    ->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
                                    ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                                    ->join('MenuItem i', 'i.ItemId = k.ItemId', 'inner')
                                    ->join('3POrders p', 'p.3PId = km.TPId', 'left')
                                    ->get_where('Billing b', array(
                                                'k.EID' => $EID,
                                                'b.EID' => $EID,
                                                'km.EID' => $EID,
                                                'i.EID' => $EID,
                                                'k.DCd' => $DCd,
                                                'k.DStat' => 0,
                                                'k.Stat' => 3,
                                                'k.OType >=' => 100,
                                                )
                                            )
                                    ->result_array();

                if (empty($kitchenData)) {
                    $response = [
                        "status" => 0,
                        "msg" => "No Item Pending"
                    ];
                } else {
                    $response = [
                        "status" => 1,
                        "kitchenData" => $kitchenData
                    ];
                }
                echo json_encode($response);
                die();
            }

            if (isset($_POST['getOrderList']) && !empty($_POST['getOrderList'])) {
                if (isset($_POST['DispCd'])) {
                    $DCd = $_POST['DispCd'];
                } else {
                    $DCd = 0;
                }
                $ItemNm = "i.Name$langId as ItemNm";
                $ipName = "ip.Name$langId as ipName";
                $CNo = $_POST['CNo'];
                
                $orderList = $this->db2->select("$ItemNm, k.Qty, k.CustItemDesc, k.CustRmks, k.Itm_Portion, $ipName, k.KStat")
                                    ->order_by('k.DCd', 'Asc')
                                    ->group_by('k.DCd, i.ItemId, k.CustItemDesc, k.CustRmks, k.Itm_Portion')
                                    ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                                    ->join('MenuItem i', 'i.ItemId = k.ItemId', 'inner')
                                    ->join('ItemPortions ip', 'ip.IPCd = k.Itm_Portion', 'inner')
                                    ->get_where('KitchenMain km', array(
                                                'km.EID' => $EID,
                                                'k.EID' => $EID,
                                                'i.EID' => $EID,
                                                'km.CNo' => $CNo,
                                                'k.DCd' => $DCd 
                                                )
                                            )
                                    ->result_array();

                if (count($orderList) > 0) {
                    $response = [
                        "status" => 1,
                        "orderList" => $orderList
                    ];
                } else {
                    $response = [
                        "status" => 0,
                        "msg" => "No results"
                    ];
                }

                echo json_encode($response);
                die();
            }
        }
    }

    public function change_password(){
        // $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $res = '';
            $data = $_POST;
            if($data['password'] == $data['c_password']){
                $check = $this->db2->select('Passwd')->get_where('UsersRest', array('RUserId' => authuser()->RUserId))->row_array();
                 if(!empty($check)){
                    if($check['Passwd'] == $data['old_password']){
                        $status = 'success';
                        $this->session->set_userdata('new_pwd', $data['password']);
                        $this->session->set_userdata('old_pwd', $data['old_password']);
                        $otp = generateOTP(authuser()->mobile, 'Change Password');
                        $res = "OTP has been send.";
                    }else{
                        $res = "OLD Password does not matched.";
                    }
                 }else{
                    $res = "Failed to Validate User";
                 }
            }else{
                $res = "Passwords Don't Match";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }

        $data['title'] = 'Change Password';
        $this->load->view('rest/password_change',$data);   
    }

    private function generateOTP($RUserId){
        $check = $this->db2->select('token')->get_where('UsersRest', array('RUserId' => $RUserId))->row_array();
        if(!empty($check)){
            $otp = rand(9999,1000);
            $this->session->set_userdata('new_otp', $otp);
            $msg = 'Your One Time Password is '.$otp;
            $message = array(
              'body'   => $msg,
              'title'   => 'Your OTP',
              'vibrate' => 1,
              'sound'   => 1
            );
            // firebaseNotification($check['token'], $message);
        }
    }

    public function verifyOTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $otp = $this->session->userdata('cust_otp');
            if($_POST['otp'] == $otp){
                $password = $this->session->userdata('new_pwd');
                $this->rest->passwordUpdate($password);

                $this->session->set_userdata('cur_password', $password);
                $status = 'success';
                // $res = "Password has been updated.";
                $res = base_url('dashboard');
                $mItem = $this->db2->select('ItemId')->get_where('MenuItem', array('EID' => authuser()->EID))->row_array();
                if(empty($mItem)){
                    $res = base_url('restaurant/data_upload');
                }
            }else{
                $res = "OTP Doesn't Matched";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    public function merge_table(){
        if($this->input->method(true)=='POST'){

            $res = $this->rest->mergeTables($_POST);
            echo json_encode($res);
            die;
        }

        $data['title'] = 'Table - Join/Unjoin';
        $this->load->view('rest/table_merge',$data);   
    }

    public function set_theme(){
        $this->check_access();
        $EID    = authuser()->EID;
        $data['EID'] = $EID;

        $status = 'error';
        $response = 'Something went wrong!!';
        if($this->input->method(true)=='POST'){

            $status = 'success';

            $pData = $_POST;
            $ThemeId = $pData['ThemeId'];
            if($ThemeId > 0){
                unset($pData['ThemeId']);
                if($pData['Stat'] == 1){
                    $this->db2->query("UPDATE ConfigTheme set Stat = 0 where ThemeId != $ThemeId and EID = $EID");
                }else{
                    $this->db2->query("UPDATE ConfigTheme set Stat = 0 where EID = $EID");
                    $this->db2->query("UPDATE ConfigTheme set Stat = 1 where ThemeId = 1 and EID = $EID");
                }
                updateRecord('ConfigTheme', $pData, array('ThemeId' => $ThemeId, 'EID' => $EID));
                $response = 'Theme Updated.';
            }else{
                $pData['EID'] = $data['EID'];
                insertRecord('ConfigTheme', $pData);
                $response = 'New Theme Added.';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['themeNames'] = $this->rest->getThemeListName();
        $data['themes']     = $this->rest->getThemeList();
        $data['title'] = $this->lang->line('restaurantTheme');

        $this->load->view('rest/theme_setting',$data);   
    }

    public function get_theme_data(){
        $status = 'error';
        $response = 'Something went wrong!!';
        if($this->input->method(true)=='POST'){

            $res = $this->rest->getThemeData($_POST['ThemeId']);
            if(!empty($res)){
                $status = 'success';
                $response = $res;
            }else{
                $response = 'No Theme Found!!';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }        
    }

    public function stock_list(){
        $this->check_access();
        $data['trans_id'] = 0;
        $data['trans_type_id'] = 0;
        $data['from_date'] = date('Y-m-d');
        $data['to_date'] = date('Y-m-d');
        $data['stock'] = $this->rest->getStockList();
        if($this->input->method(true)=='POST'){
            $data['stock'] = $this->rest->getStockList($_POST);
        }
        
        $data['title'] = $this->lang->line('stockList');
        $data['trans_type'] = $this->rest->getTransactionType();
        $this->load->view('rest/stocks_list',$data);     
    }

    public function add_stock(){
        $this->check_access();
        if($this->input->method(true)=='POST'){
            
            if(isset($_POST['add_stock']) && $_POST['add_stock'] == 1){
                if($_POST){
                    
                    $RMStock['TransType'] = $_POST['trans_type'];

                    $RMStock['FrmSuppCd'] = !empty($_POST['to_store']) && !empty($_POST['supplier'])?$_POST['supplier']:0;
                    $RMStock['FrmEID'] = !empty($_POST['to_store']) && !empty($_POST['eatary'])?$_POST['eatary']:0;
                    $RMStock['FrmKitCd'] = !empty($_POST['to_store']) && $_POST['kit']?$_POST['kit']:0;

                    $RMStock['ToSuppCd'] = !empty($_POST['from_store']) && !empty($_POST['supplier'])?$_POST['supplier']:0;
                    $RMStock['ToEID'] = !empty($_POST['from_store']) && !empty($_POST['eatary'])?$_POST['eatary']:0;
                    $RMStock['ToKitCd'] = !empty($_POST['from_store']) && !empty($_POST['kit'])?$_POST['kit']:0;
                    
                    $RMStock['FrmStoreId'] = !empty($_POST['from_store'])?$_POST['from_store']:0;
                    
                    $RMStock['ToStoreId'] = !empty($_POST['to_store'])?$_POST['to_store']:0;

                    if($_POST['trans_type'] == 21){
                        $RMStock['FrmKitCd'] = !empty($_POST['store_adjust'])?$_POST['store_adjust']:0;
                    }

                    $RMStock['Stat'] = 0;
                    $RMStock['LoginId'] = authuser()->RUserId;
                    $RMStock['EID'] = authuser()->EID;
                    $RMStock['TransDt'] = !empty($_POST['TransDt'])?$_POST['TransDt']:date('Y-m-d');
                    $TransId = insertRecord('RMStock', $RMStock);
                    if($TransId){
                        
                        $num = sizeof($_POST['ItemId']);
                        for($i = 0;$i<$num;$i++){
                            $RMStockDet['TransId'] = $TransId;

                            $RMStockDet['RMCd'] = !empty($_POST['ItemId'][$i])?$_POST['ItemId'][$i]:0;
                            $RMStockDet['UOMCd'] = !empty($_POST['UOM'][$i])?$_POST['UOM'][$i]:0;
                            $RMStockDet['Qty'] = !empty($_POST['Qty'][$i])?$_POST['Qty'][$i]:0;
                            $RMStockDet['Rate'] = !empty($_POST['Rate'][$i])?$_POST['Rate'][$i]:0;
                            $RMStockDet['Rmks'] = !empty($_POST['Remarks'][$i])?$_POST['Remarks'][$i]:"";
                            
                            if(!empty($RMStockDet['RMCd']) && !empty($RMStockDet['Qty']) && !empty($RMStockDet['UOMCd'])){

                                insertRecord('RMStockDet',$RMStockDet);
                            }
                        }
                    }
                    redirect(base_url('restaurant/stock_list'));
                }

            }
            
            if(isset($_POST['delete_details'])){
                updateRecord('RMStockDet', array('Stat' => 9), array('RMDetId' => $_POST['RMDetId']) );
                echo 1;
            }
            if(isset($_POST['delete_trans'])){
                updateRecord('RMStock', array('Stat' => 9), array('TransId' => $_POST['TransId']) );
                echo 1;
            }

        }
        $data['title'] = $this->lang->line('addStock');

        $data['items'] = $this->rest->getRMItemUOM();
        $data['trans_type'] = $this->rest->getTransactionType();
        $data['eatary'] = $this->rest->getRestaurantList();
        $data['kit'] = $this->rest->get_kitchen();
        $data['suppliers'] = $this->rest->getSupplierList();

        $this->load->view('rest/stock_add',$data);
    }

    public function edit_stock($TransId){
        // $this->check_access();

        if($this->input->method(true)=='POST'){

            $TransId = $_POST['trans_id'];
            $num = sizeof($_POST['ItemId']);
            for($i = 0;$i<$num;$i++){
                $RMStockDet['TransId'] = $TransId;
                $detid = !empty($_POST['RMDetId'][$i])?$_POST['RMDetId'][$i]:0;
                $RMStockDet['RMCd'] = !empty($_POST['ItemId'][$i])?$_POST['ItemId'][$i]:0;
                $RMStockDet['UOMCd'] = !empty($_POST['UOM'][$i])?$_POST['UOM'][$i]:0;
                $RMStockDet['Qty'] = !empty($_POST['Qty'][$i])?$_POST['Qty'][$i]:0;
                $RMStockDet['Rate'] = !empty($_POST['Rate'][$i])?$_POST['Rate'][$i]:0;
                $RMStockDet['Rmks'] = !empty($_POST['Rate'][$i])?$_POST['Remarks'][$i]:'';
                
                updateRecord('RMStockDet', $RMStockDet, array('TransId' =>$TransId, 'RMDetId ' => $detid ));
            }
            redirect(base_url('restaurant/stock_list'));
        }
       
        $data['TransId'] = $TransId;
        $data['stock'] = getRecords('RMStock', array('TransId' => $TransId));
        if(!empty($data['stock'])){
            $data['items'] = $this->rest->getRMItemUOM();
            $data['stock_details'] = $this->rest->getRMStockDetList($TransId);
            $data['eatary'] = $this->rest->getRestaurantList();
            $data['kit'] = $this->rest->get_kitchen();
            $data['suppliers'] = $this->rest->getSupplierList();
        }else{
            $this->session->set_flashdata('error','No records found!');
            redirect(base_url('restaurant/stock_list'));   
        }

        $data['title'] = $this->lang->line('editStock');
        $this->load->view('rest/stock_edit',$data);    
    }

    public function stock_report(){
        // $this->check_access();
        $data['title'] = $this->lang->line('stockReport');
        $data['report'] = $this->rest->getStockReport();
        $this->load->view('rest/stock_report',$data);
    }

    public function stock_consumption(){
        // $this->check_access();
        $data['title'] = $this->lang->line('stockConsumption');
        $data['report'] = $this->rest->getStockConsumption();
        $this->load->view('rest/stock_consumptions',$data);   
    }

// this is not completed
    public function itemstockreport(){
        // $this->check_access();
        $data['CheckOTP'] = $this->session->userdata('DeliveryOTP');
        $data['EID'] = authuser()->EID;
        $data['EType'] = $this->session->userdata('EType');
        $data['RestName'] = authuser()->RestName;

        $data['op_stock'] = 0;
        if($this->input->method(true)=='POST'){
            $res = $this->rest->getItemStockReportList($_POST);
            $data['report'] = $res['report'];
            $data['op_stock'] = $res['op_stock'];
        }
        $data['title'] = 'Item Stock Report';
        $this->load->view('rest/itemstockreports',$data); 
    }

    public function rm_ajax(){
       if(isset($_POST['getUOM'])){
            $item_id = $_POST['RMCd'];
            $langId = $this->session->userdata('site_lang');
            $lname = "ru.Name$langId as Name";
            $uoms = $this->db2->select("riu.*, $lname")
                              ->join('RMUOM ru','riu.UOMCd = ru.UOMCd','inner')
                              ->get_where('RMItemsUOM riu', array('riu.RMCd' => $item_id))
                              ->result_array();
            echo json_encode($uoms);
        } 
    }

    public function bill_view(){
        $this->check_access();
       
        $data['RUserId'] = authuser()->RUserId;
        $data['EID'] =  authuser()->EID;
        $data['EType'] =  $this->session->userdata('EType');
        $data['from_date'] = date('Y-m-d');
        $data['to_date'] = date('Y-m-d');
        if($this->input->method(true)=='POST'){
            $data['from_date'] = date('Y-m-d', strtotime($_POST['from_date']));
            $data['to_date'] = date('Y-m-d', strtotime($_POST['to_date']));
        }
        $data['bills'] = $this->rest->getBillingData($data['from_date'] , $data['to_date'] );

        $data['title'] = $this->lang->line('billView');'Bill View';
        $this->load->view('rest/bill_view',$data);
    }

    public function bill_rcpt(){
        $data['title'] = 'Bill Receipt';
        if(isset($_GET['restaurant'])){
            $data['restaurant'] = $_GET['restaurant'];
        }
        if(isset($_GET['billId'])){
            $data['billId'] = $_GET['billId'];
        }
        
        if(isset($_GET['CustId'])){
            $data['CustId'] = $_GET['CustId'];
        }
        
        $data['EID'] = authuser()->EID;
        if(isset($_GET['EID']) && !empty($_GET['EID'])){
            $data['EID'] = $_GET['EID'];
        }
        $data['dbname'] = isset($_GET['dbn'])?$_GET['dbn'].".":"";
        $data['ChainId'] = authuser()->ChainId;
        $data['OutletId'] = $this->session->userdata('OutletId');
        $data['EType'] = $this->session->userdata('EType');
        
        $data['Tips'] = $this->session->userdata('Tips');
        $data['PymtOpt'] = $this->session->userdata('PymtOpt');
        $data['KOTNo'] = $this->session->userdata('KOTNo');
        $data['TableNo'] = $this->session->userdata('TableNo');
        $data['COrgId'] = $this->session->userdata('COrgId');
        $data['CustNo'] = $this->session->userdata('CustNo');
        $data['CellNo'] = $this->session->userdata('CellNo');
        $data['CNo'] = $this->session->userdata('CNo');

        //menu link
        $data['cId'] = $this->session->userdata('cId');
        $data['mCatgId'] = $this->session->userdata('mCatgId');
        $data['cType'] =$this->session->userdata('cType'); 

        $data['link'] = "https://qs.vtrend.org/item_details.php?cId=" . $data['cId'] . "&mCatgId=" . $data['mCatgId'] . "&cType=" . $data['cType'];

        $billData = getBillData($data['dbname'], $data['EID'], $data['billId'], $data['CustId']); 

        $data['hotelName'] = $billData[0]['Name'];
        $data['phone'] = $billData[0]['PhoneNos'];
        $data['gstno'] = $billData[0]['GSTno'];
        $data['fssaino'] = $billData[0]['FSSAINo'];
        $data['cinno'] = $billData[0]['CINNo'];
        $data['billno'] = $billData[0]['BillNo'];
        $data['orderdate'] = $billData[0]['BillDt'];
        $data['date'] = date('Y-m-d H:i:s',strtotime($data['orderdate']));
        $data['dateOfBill'] = $data['date'];
        $data['address'] = $billData[0]['Addr'];
        $data['pincode'] = $billData[0]['Pincode'];
        $data['city'] = $billData[0]['City'];
        $data['totamt'] = $billData[0]['TotAmt'];
        $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
        $data['bservecharge'] = $billData[0]['bservecharge'];
        $data['tipamt'] = $billData[0]['Tip'];
        $data['Stat'] = $billData[0]['Stat'];
        $data['total'] = 0;
        $data['sgstamt']=0;
        $data['cgstamt']=0;
        $data['grandTotal'] = $data['sgstamt'] + $data['cgstamt'] + $data['bservecharge'] + $data['tipamt'];
        $data['thankuline'] = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

        $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'];
        $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
        $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

        $res = $this->rest->getBillBody($billData, $data['EID'], $data['billId']);
        $data['billBody'] = $res[0];
        $data['itemTotal'] = $res[1];
        
        $this->load->view('rest/receipt',$data);
    }

    public function sitting_table(){
        $this->check_access();
        $data['title'] = $this->lang->line('tableView');
        $data['EType'] = $this->session->userdata('EType');
        if($data['EType'] == 5){

            $data['TableAcceptReqd'] = $this->session->userdata('TableAcceptReqd');
            $EID = authuser()->EID;
            $data['EID'] = $EID;
            $data['Kitchen'] = $this->session->userdata('Kitchen');

            $langId = $this->session->userdata('site_lang');
            $cashName = "Name$langId as Name";

            $data['SettingTableViewAccess'] = $this->rest->getCasherList();
            
            $data['captured_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 1 and EID = ".$EID)->result_array();
            $data['available_tables'] = $this->db2->query("SELECT * from Eat_tables where Stat = 0 and EID = ".$EID)->result_array();
            $data['selectMergeTable'] = $this->db2->query("SELECT TableNo , MergeNo FROM `Eat_tables` where EID = $EID")->result_array();
            $this->load->view('rest/table_sitting',$data);   
        }else{
            $this->load->view('page403', $data);
        }
        
    }

    public function sittin_table_view_ajax(){
        
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');

        if (isset($_POST['getTableOrderDetails']) && $_POST['getTableOrderDetails']) {
            $ccd_qry = " ";
            if (isset($_POST['CCd'])) {
                $CCd = $_POST['CCd'];
                $ccd_qry = " and et.CCd = $CCd and et.MergeNo = km.MergeNo and et.EID = km.EID";
            }

            $stat = ($EType == 5)?3:2;

            $groupby = ' GROUP BY km.MCNo';    
            if($_POST['filter'] == 'tableWise'){
                $groupby = ' GROUP BY km.MergeNo';
            }
            
            $kitchenData = $this->db2->query("SELECT (SUM(k.Qty) - SUM(k.DQty)) as AllDelivered, km.CNo, km.CustId,  SUM(k.OrigRate * k.Qty) as Amt, TIME_FORMAT(km.LstModDt,'%H:%i') as StTime,   km.MergeNo, km.MCNo, km.BillStat,  km.EID, km.CNo, km.CellNo, IF(km.CellNo > 0,(select count(km2.CellNo) from KitchenMain km2 where km2.CellNo=km.CellNo and km2.EID = km.EID group by km2.CellNo),0) as visitNo, km.TableNo,km.SeatNo, km.OType, km.payRest, km.custPymt, km.CnfSettle, km.billSplit FROM Kitchen k, KitchenMain km, Eat_tables et WHERE km.payRest=0 and km.CnfSettle=0 AND (k.Stat = $stat) AND (k.OType = 7 OR k.OType = 8) and (km.CNo = k.CNo) AND k.EID = km.EID AND k.MergeNo = km.MergeNo AND km.EID = $EID $ccd_qry $groupby order by km.MergeNo ASC")->result_array();

            if (empty($kitchenData)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Tables Occupied"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "kitchenData" => $kitchenData
                ];
            }

            echo json_encode($response);
            die();
        }
        if(isset($_POST['getKitchenData']) && $_POST['getKitchenData']){
            
            $f = $_POST['FKOTNo'];
            $c = $_POST['CNo'];
            $data = $this->db2->query("SELECT k.FKOTNo, ek.KitName, k.UKOTNo FROM Eat_Kit ek, Kitchen k, KitchenMain km WHERE ( k.Stat = 3) AND k.EID=km.EID AND k.CNo = km.CNo AND km.EID = $EID and (km.CNo = $c OR km.MCNo = $c) and k.FKOTNo = $f and k.MergeNo = km.MergeNo and ek.KitCd=k.KitCd and ek.EID=km.EID GROUP BY k.FKOTNo, ek.KitName, k.UKOTNo order by k.FKOTNo, ek.KitName, k.UKOTNo ASC")->result_array();
            $response['status'] = 1;
            $response['data'] = $data;
            echo json_encode($response);
            die();
        }
        if (isset($_POST['acceptTable']) && $_POST['acceptTable']) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];
            
            $updateETO = $this->db2->query("UPDATE Eat_tables_Occ eto, KitchenMain km SET eto.Stat = 1, eto.MergeNo = '$tableNo' WHERE eto.TableNo = '$tableNo' AND km.CNo = $CNo AND km.EID = eto.EID and km.CNo = eto.CNo AND km.EID = $EID");

            $updateKitchen = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.Stat = 1 WHERE km.TableNo = '$tableNo' AND km.CNo = $CNo AND km.EID = k.EID and km.CNo = k.CNo  AND (k.Stat = 0 OR k.Stat = 10) AND km.EID = $EID");

            $response = [
                "status" => 1,
                "msg" => "Table Accepted"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['rejectTable']) && $_POST['rejectTable']) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];
            
            $updateETO = $this->db2->query("DELETE FROM Eat_tables_Occ  where EID = $EID and MergeNo = (Select km.MergeNo from  KitchenMain km WHERE km.EID = $EID and (km.CNo = $CNo OR km.MCNo = $CNo)" );
            
            $updateKitchen = $this->db2->query("UPDATE Kitchen k, KitchenMain km SET k.Stat = 4 WHERE k.EID = km.EID AND k.CNo = km.CNo AND (km.CNo = $CNo OR km.MCNo = $CNo)   AND km.EID = $EID");

            $kitchenMainUpdate = $this->db2->query("UPDATE KitchenMain SET Stat = 4 WHERE (km.CNo = $CNo OR km.MCNo = $CNo) AND EID = $EID");

            $response = [
                "status" => 1,
                "msg" => "Table Rejected"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['getKot_data']) && $_POST['getKot_data']) {
            
            $mergeNo = $_POST['mergeNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];

            $EType = $this->session->userdata('EType');
            $stat = ($EType == 5)?3:2;

            $groupby = ' ,k.CNo';
            $where = " and (k.CNo = $CNo OR k.MCNo = $CNo) ";

            if($_POST['tableFilter'] == 'tableWise'){
                $groupby = '';
                $where = '';
            }

            $langId = $this->session->userdata('site_lang');
            $lname = "i.Name$langId as ItemNm";
            $kots = $this->db2->query("SELECT k.MergeNo,k.TableNo, k.FKOTNo, k.KOTNo, k.KitCd, SUM(k.Qty) as Qty , k.KOTPrintNo, k.ItemId, $lname, SUM(k.Qty) as Qty, SUM(k.DQty) as DQty,TIME_FORMAT(k.EDT, '%H:%i') as EDT, k.CellNo, k.CNo,k.MCNo FROM Kitchen k, MenuItem i WHERE k.ItemId = i.ItemId AND ( k.Stat = $stat ) AND k.EID = $EID $where and k.MergeNo = '$mergeNo' and k.payRest = 0  GROUP BY k.KitCd, k.KOTNo, k.ItemId, k.EDT, k.MergeNo $groupby order by k.KOTNo, k.FKOTNo, i.Name1 DESC")->result_array();
            
            if (empty($kots)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Kot Founds"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "kots" => $kots
                ];
            }
            echo json_encode($response);
            die();
        }

        if (isset($_POST['getAllItems'])) {
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $CNo = $_POST['cNo'];

            $itemDetails = $this->db2->query("SELECT  k.ItemId, i.Name1, SUM(k.Qty) as Qty, SUM(k.AQty) as AQty, SUM(k.DQty) as DQty FROM Kitchen k, KitchenMain km, MenuItem i WHERE k.ItemId = i.ItemId AND k.EID = km.EID AND (k.Stat = 3)  AND km.CNo = k.CNo AND km.EID = 51 AND (km.CNo = $CNo OR km.MCNo = $CNo) AND km.MergeNo = k.MergeNo  group by i.ItemId ORDER by i.Name1")->result_array();
            
            if (empty($itemDetails)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Items Founds"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "itemDetails" => $itemDetails
                ];
            }
            echo json_encode($response);
            die();
        }

        if (isset($_POST['deliverOrder'])) {

            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $cNo = $_POST['cNo'];

            $updateKitchenDelivery = $this->db2->query("UPDATE Kitchen SET Stat = IF((Qty = (DQty+AQty)),  5, 2), DQty = (DQty + AQty), AQty = 0 WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID");

            $response = [
                "status" => 1,
                "msg" => "Updated"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['handleReassign'])) {

            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $cNo = $_POST['cNo'];

            $fromReassignOrder = $this->db2->query("SELECT OrdNo, ItemId, CustRmks, Itm_Portion from Kitchen WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID")->result_array();

            $orderId = $fromReassignOrder[0]['OrdNo'];
            $itemId = $fromReassignOrder[0]['ItemId'];
            $custRemarks = $fromReassignOrder[0]['CustRmks'];
            $itemPortion = $fromReassignOrder[0]['Itm_Portion'];

            $kitchenData = $this->db2->query("SELECT OrdNo, (Qty - DQty) as PQty, TableNo from Kitchen where ItemId = :itemId AND CustRmks = :custRemarks AND Itm_Portion = :itemPortion AND (Qty - DQty) > 0 AND (Stat = 1 OR Stat = 2) AND OrdNo != :orderId AND EID = $EID", ["itemId" => $itemId, "custRemarks" => $custRemarks, "itemPortion" => $itemPortion, "orderId" => $orderId])->result_array();

            if (!empty($kitchenData)) {
                $response = [
                    "status" => 1,
                    "kitchenData" => $kitchenData
                ];
            } else {
                $response = [
                    "status" => 0,
                    "msg" => "No tables Found"
                ];
            }

            echo json_encode($response);
            die();
        }

        if (isset($_POST['reassignOrder'])) {

            $reassign = 1;

            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $assignToOrderId = $_POST['assignToOrderId'];
            $assignQty = $_POST['assignQty'];
            $cNo = $_POST['cNo'];

            $kitchenRemoveAllocate = $this->db2->query("UPDATE Kitchen set AQty = (AQty - $assignQty) WHERE ItemId = $itemId AND TableNo = '$tableNo' AND CustId = $custId AND EID = $EID");

            if ($reassign == 1) {
                $kitchenReassignOrder = $this->db2->query("UPDATE Kitchen set AQty = $assignQty, Stat = 2 where OrdNo = $assignToOrderId AND EID = $EID", ["assignQty" => $assignQty, "assignToOrderId" => $assignToOrderId]);
            } elseif ($reassign == 2) {
                $kitchenReassignOrder = $this->db2->query("UPDATE Kitchen set Stat = IF((DQty + $assignQty + AQty) < Qty, 2, 5), DQty = (DQty + $assignQty + AQty), AQty = 0 where OrdNo = $assignToOrderId AND EID = $EID", ["assignQty" => $assignQty, "assignToOrderId" => $assignToOrderId]);
            }

            $response = [
                "status" => 1,
                "msg" => "Update Success"
            ];

            echo json_encode($response);
            die();
        }

        if (isset($_POST['checkStatForDecline'])) {
            $itemId = $_POST['itemId'];
            $tableNo = $_POST['tableNo'];
            $custId = $_POST['custId'];
            $cNo = $_POST['cNo'];

            $checkItemStat = $this->db2->query("SELECT SUM(k.Qty - (k.AQty + k.DQty)) as DeclineQty, k.ItemId, k.Stat, i.Name1 From Kitchen k, MenuItem i WHERE i.ItemId = k.ItemId AND i.EID = k.EID AND k.ItemId = $itemId AND k.TableNo = '$tableNo' AND k.CustId = $custId AND k.EID = $EID AND k.Stat = 1 GROUP BY k.ItemId, k.Stat")->result_array();

            if (empty($checkItemStat)) {
                $response = [
                    "status" => 0,
                    "msg" => "Item can not be declined"
                ];
            } else {
                $response = [
                    "status" => 1,
                    "checkItemStat" => $checkItemStat[0]
                ];
            }

            echo json_encode($response);
            die();
        }

        if(!empty($_POST['check_new_orders'])){
            $q = "SELECT * from KitchenMain where (Stat = 0 or Stat = 10) and EID = ".$EID." and ChainId = ".$ChainId." and BillStat = 0 order by CNo asc";
            $data = $this->db2->query($q)->result_array();
            // echo "<pre>";print_r($data);exit();
            $b = "";
            foreach($data as $key){
                $q1 = "SELECT sum(ItmRate*Qty) as amount from Kitchen where CNo=".$key['CNo']." and (stat = 0 or stat = 10)";
                $am = $this->db2->query($q1)->result_array();
                $am = $am[0]['amount'];
                $b.="<tr>";
                $b.="<td>".$key['MergeNo']."</td>";
                $b.="<td>".$am."</td>";
                $b.="<td><button class='btn btn-success btn-sm' onclick='accept_order(".$key['CNo'].")'>Accept</button>&nbsp;&nbsp;<button class='btn btn-danger btn-sm' onclick='reject_order(".$key['CNo'].")'>Reject</button></td>";
                $b.="</tr>";
            }
            echo $b;
        }
        
        if(isset($_POST['check_settled_table']) && !empty($_POST['check_settled_table'])){
            $q = "SELECT km.MergeNo, km.CNo, b.BillNo, b.TotAmt from KitchenMain as km, Billing as b where km.CNo = b.CNo and CnfSettle = 0 and BillStat > 0";
            $am = $this->db2->query($q)->result_array();
            // print_r($am);exit();
            if(!empty($am)){
                $b = '';
                foreach($am as $key){
                    $b.="<tr>";
                    $b.="<td>".$key['MergeNo']."</td>";
                    $b.="<td>".$key['BillNo']."</td>";
                    $b.="<td>".$key['TotAmt']."</td>";
                    $b.="</tr>";
                }
                echo $b;
            }else{
                echo 0;
            }
        }

        if(isset($_POST['move_table']) && !empty($_POST['move_table'])){
            $from = $_POST['from_table'];
            $to = $_POST['to_table'];
            $phone = implode(",", $_POST['cell_no']);
            $q1 = "UPDATE KitchenMain set TableNo = '".$to."', Mergeno ='".$to."' where EID = ".$EID." and (TableNo = '".$from."' or MergeNo = '".$from."') and CellNo in(".$phone.")";
            
            $update_kitchen_main = $this->db2->query($q1);
            $q2 = "UPDATE Kitchen set TableNo = '".$to."', Mergeno =".$to." where EID = ".$EID." and (TableNo = '".$from."' or MergeNo = '".$from."') and CellNo in(".$phone.")";
            
            $update_kitchen = $this->db2->query($q2);
            $q3 = "UPDATE Eat_tables set Stat = 0 where EID = ".$EID." and TableNo = ".$from;
            
            $update_et = $this->db2->query($q3);
            $update_et = $this->db2->query("UPDATE Eat_tables set Stat = 1 where EID = ".$EID." and TableNo = ".$to);
            
            redirect(base_url('restaurant/sitting_table'));
        }

        if(isset($_POST['get_phone_num']) && !empty($_POST['get_phone_num'])){

            $from = $_POST['from_table'];

            $nums = $this->db2->query("SELECT CellNo from KitchenMain where EID = ".$EID." and TableNo = ".$from." or MergeNo = ".$from)->result_array();
            if(!empty($nums)){
                $b = '';
                foreach($nums as $key){
                    $b = '<input type="checkbox" value="'.$key['CellNo'].'" name="cell_no[]"> '.$key['CellNo'];
                }
                echo $b;
            }else{
                echo 0;
            }
        }

    }

    public function merge_table_ajax(){

        $RUserId = authuser()->RUserId;
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;

        if (isset($_POST['getUnmergeTables']) && $_POST['getUnmergeTables']) {

            $tables = $this->db2->query("SELECT et.TableNo, et.MergeNo from Eat_tables et where et.TableNo = et.MergeNo and et.TableNo < 100 or (et.TableNo in (select km.TableNo from KitchenMain km where km.BillStat = 0 and km.TableNo = km.MergeNo and km.EID = $EID) and et.EID = $EID) order by et.TableNo ASC")->result_array();

            if (!empty($tables)) {
                $response = [
                    "status" => 1,
                    "tables" => $tables
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "All Tables are Allocated"
                ];
            }
            echo json_encode($response);
            die;
        }

        if (isset($_POST['mergeTables']) && $_POST['mergeTables']) {

            $selectedTables = json_decode($_POST['selectedTables']);
            if (count($selectedTables) > 1) {

                    $mergeNo = implode("~", $selectedTables);
                
                    $selectedTablesString = implode(',', $selectedTables); 

                    $result = $this->db2->query("UPDATE Eat_tables set MergeNo = '$mergeNo', Stat = 1 where TableNo in ($selectedTablesString)");
                    
                    $result1 = $this->db2->query("UPDATE KitchenMain km set km.MergeNo = '$mergeNo' where km.TableNo in ($selectedTablesString) and km.BillStat = 0");

                    $this->db2->query("UPDATE Kitchen k set k.MergeNo = '$mergeNo' where k.TableNo in ($selectedTablesString) and k.BillStat = 0");

                    if($result){
                        $response = [
                            "status" => 1,
                            "msg" => "ok"
                        ];
                    }else{
                        $response = [
                            "status" => 3,
                            "msg" => "Fail to update in  Eat_tables table"
                        ];
                    }

            }else {
                $response = [
                    "status" => 0,
                    "msg" => "You can select Min 2 and Max 4 Tables"
                ];
            }

            echo json_encode($response);
            die;
        }
        if (isset($_POST['getMergedTables']) && $_POST['getMergedTables']) {
            $tables = $this->db2->query("SELECT MergeNo from Eat_tables where TableNo != MergeNo and EID = $EID group by MergeNo order by MergeNo ASC ")->result_array();

            if (!empty($tables)) {
                $response = [
                    "status" => 1,
                    "tables" => $tables
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "No table is merged"
                ];
            }
            
            echo json_encode($response);
            die;
        }
        if (isset($_POST['getEachTable']) && $_POST['getEachTable']) {
            $merge_no = $_POST['MergeNo'];
            
            $q = "SELECT TableNo from Eat_tables where MergeNo = '$merge_no'";
            $tables = $this->db2->query($q)->result_array();
            
            if (!empty($tables)) {
                $response = [
                    "status" => 1,
                    "tables" => $tables
                ];
            }else {
                $response = [
                    "status" => 0,
                    "msg" => "No table is merged"
                ];
            }

            echo json_encode($response);
            die;
        }

        if(isset($_POST['unmergeTables']) && $_POST['unmergeTables']){
            
            $old_merge_no = $_POST['MergeNo'];

            $this->db2->query("UPDATE Eat_tables set MergeNo = TableNo, stat=0 where MergeNo = '$old_merge_no'");

            $this->db2->query("UPDATE KitchenMain set MergeNo = TableNo where MergeNo = '$old_merge_no'");
            $this->db2->query("UPDATE Kitchen set MergeNo = TableNo where MergeNo = '$old_merge_no'");
            $response = [
                "status" => 1,
                "msg" => "Tables are unmerged."
            ];

            echo json_encode($response);
            die;
            
        }
        if(isset($_POST['UnmergeTable']) && $_POST['UnmergeTable']){
            
            $mergeNo = $_POST['MergeNo'];
            $table = $_POST['TableNo'];
            $q1 = "UPDATE Eat_tables set MergeNo = TableNo where MergeNo = '$mergeNo'";

            $tables = $this->db2->query($q1);
        }

    }

    public function customer_landing_page_ajax(){

        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');

        if (isset($_POST['getCuisine']) && $_POST['getCuisine']) {

            if ($ChainId == 0) {
                $eatCuisineData = $this->db2->query("SELECT ec.ECID, ec.CID, c.Name, c.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt, ec.CID as imgSrc FROM `EatCuisine` ec, Cuisines c, Food f, Eatary e WHERE e.EID=ec.EID and ec.CID = c.CID AND c.CTyp = f.CTyp AND ec.EID = $EID AND e.Stat = 0 AND ec.Stat = 0 ORDER BY Rank")->result_array();
            } else {
                $eatCuisineData = $this->db2->query("SELECT ec.ECID, ec.CID, c.Name, c.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt, ec.CID as imgSrc FROM `EatCuisine` ec, Cuisines c, Food f, Eatary e WHERE e.EID=ec.EID and ec.CID = c.CID AND c.CTyp = f.CTyp AND  e.ChainId = $ChainId AND e.Stat = 0 AND ec.Stat = 0 ORDER BY Rank")->result_array();
            }

            if (empty($eatCuisineData)) {
                $response = [
                    "status" => 0,
                    "msg" => "No Cuisines Defined"
                ];
            } else {

                foreach ($eatCuisineData as $key => $data) {
                    
                    if ($ChainId > 0) {
                        $imgSrc = "uploads/c" . $ChainId . "/" . $data['imgSrc'] . ".jpg";
                    } else {
                        $imgSrc = "uploads/e" . $EID . "/" . $data['imgSrc'] . ".jpg";
                    }

                    if (!file_exists("../$imgSrc")) {
                        $imgSrc = "uploads/general/" . $data['imgSrc'] . ".jpg";
                    }

                    $eatCuisineData[$key]['imgSrc'] = ltrim($imgSrc);
                }

                $response = [
                    "status" => 1,
                    "eatCuisineData" => $eatCuisineData
                ];
            }

            echo json_encode($response);
            die();
        }

        if(isset($_POST['call_help'])){
            
            $dd['table_no'] = $_SESSION['TableNo'];
            $insert_help = insertRecord('call_bell', $dd);
            echo $insert_help;
        }
        if(isset($_POST['check_call_help'])){
            $list_id = $_POST['list_id'];
            $check = '';

            $q = "SELECT * from call_bell where response_status < 3";
            if($list_id != ''){
                $q.=" and id not in($list_id)";
            }
            $data = $this->db2->query($q)->result_array();
            
            if(!empty($data)){
                $check =  $data;
            }else{
                $check =  array();
            }
            echo json_encode($check);
        }
        if(isset($_POST['view_call_help'])){
            
            $id = $_POST['help_table_id'];
            $t = date('Y-m-d H:i:s');
            $q = "UPDATE call_bell set viewed = 1, viewed_time='$t' where id = '$id'";
            
            $this->db2->query($q);
            $check = 1;
            echo $check;
        }
        if(isset($_POST['respond_call_help'])){

            $datas['respond_time']    = date('Y-m-d H:i:s');
            $datas['response_status'] = $_POST['status'];
            $id                       = $_POST['help_table_text_id'];
            updateRecord('call_bell', $datas, array('id' => $id) );

            $check = 1;
            echo $check;
        }
        if(isset($_POST['check_help'])){
            
            $check = '';
            $q = "SELECT * from call_bell where id = '$id'";
            $data = $this->exec($q);
            if(!empty($data)){
                $check = $data[0];
            }else{
                $check = array();
            }

            echo json_encode($check);
        }
        if(isset($_POST['save_firebase_token'])){
            $this->session->set_userdata('FirebaseToken', $_POST['token']);
            $Firebase['Token'] = $_POST['token'];
            $id = insertRecord('FirebaseTokens', $Firebase);
            echo $id;
        }
        if(!empty($_POST['set_lang']) && !empty($_POST['lang'])){
            $lang = $_POST['lang'];
            setcookie('lang', $lang, time() + (3600 * 24 * 365), "/");
        }
    }

    public function order_ajax_3p(){

        $CustId = $this->session->userdata('CustId');
        $COrgId = $this->session->userdata('COrgId');
        $EID = authuser()->EID;
        $ChainId = authuser()->ChainId;
        $EType = $this->session->userdata('EType');
        
        $CellNo = $this->session->userdata('CellNo');
        $CustNo = $this->session->userdata('CustNo');
        $MultiKitchen = $this->session->userdata('MultiKitchen');
        
        $CNo = 0;
        $TableNo = 0;
        $KOTNo = 0;
        $ONo = 0;

        $langId = $this->session->userdata('site_lang');
        $itemName1 = "mi.Name$langId as LngName";
        $lname = "mi.Name$langId";
        $ipname = "ip.Name$langId as portionName";

        if (isset($_POST['searchItem']) && $_POST['searchItem']) {
            $itemName = $_POST['itemName'];
            $tableNo = $_POST['TableNo'];

            $ItmDesc = "mi.ItmDesc$langId ItmDesc";
            $ingeredients = "mi.Ingeredients$langId as Ingeredients";
            $Rmks = "mi.Rmks$langId as Rmks";

            $likeQry = " ($lname like '%$itemName%' or mi.ItemId like '$itemName%' or mi.IMcCd like '$itemName%') ";
            $order_by = " mi.Name1";

            if($this->session->userdata('IMcCdOpt') == 2){
                $likeQry = " ($lname like '%$itemName%' or mi.IMcCd like '$itemName%' or mi.ItemId like '$itemName%') ";
                $order_by = " mi.IMcCd";
            }

            $items = $this->db2->query("SELECT mc.TaxType, mc.DCd, mi.KitCd, mc.CTyp, mi.ItemId, mi.ItemTyp, mi.NV, mi.PckCharge, $itemName1, $ItmDesc , $ingeredients, $Rmks, $ipname, mir.Itm_Portion, mi.PrepTime, mi.AvgRtng, mi.FID, mi.ItemAttrib, mi.ItemSale, mi.ItemTag, mi.Name1 as imgSrc, mi.UItmCd, mi.IMcCd, mi.CID, mi.MCatgId, mi.videoLink, mir.OrigRate,  et.TblTyp FROM MenuItem mi, MenuCatg mc, ItemPortions ip, MenuItemRates mir, Eat_tables et  where  mi.MCatgId = mc.MCatgId and ip.IPCd = mir.Itm_Portion and mir.ItemId = mi.ItemId and et.SecId = mir.SecId and mir.OrigRate > 0 and et.TableNo = $tableNo AND et.EID = $EID AND mir.EID = $EID AND mir.ItemId = mi.ItemId and mi.Stat = 0 and $likeQry  and (DAYOFWEEK(CURDATE()) = mi.DayNo OR mi.DayNo = 8)  AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) AND mc.EID= $EID AND mi.EID=$EID ORDER BY $order_by")->result_array();
            
            if (!empty($items)) {
                $response = [
                    "status" => 1,
                    "items" => $items
                ];
            } else {
                $response = [
                    "status" => 0,
                    "msg" => "NO Item Found"
                ];
            }
            echo json_encode($response);
            die();
        }

        if (isset($_POST['sendToKitchen']) && $_POST['sendToKitchen']) {

            $thirdParty = 0;
            $thirdPartyRef = 0;

            $orderType = $_POST['orderType'];
            $tableNo = $_POST['tableNo'];
            if($orderType == 101){
                $thirdParty = !empty($_POST['thirdParty'])?$_POST['thirdParty']:0;
                $thirdPartyRef = !empty($_POST['thirdParty'])?$_POST['thirdParty']:0;
            }
            $itemIds = !empty($_POST['itemIds'])?$_POST['itemIds']:array();
            $itemKitCds = !empty($_POST['itemKitCds'])?$_POST['itemKitCds']:0;
            $itemQty = !empty($_POST['itemQty'])?$_POST['itemQty']:0;
            $Itm_Portion = !empty($_POST['Itm_Portion'])?$_POST['Itm_Portion']:0;
            $ItmRate = !empty($_POST['ItmRates'])?$_POST['ItmRates']:0;
            $origRates = !empty($_POST['origRates'])?$_POST['origRates']:0;
            $itemRemarks = !empty($_POST['itemRemarks'])?$_POST['itemRemarks']:0;
            $Stat = $_POST['Stat'];
            $phone = $_POST['phone'];
            $pckValue = !empty($_POST['pckValue'])?$_POST['pckValue']:0;
            $data_type = $_POST['data_type'];
            $CNo = $_POST['CNo'];
            $taxtype = !empty($_POST['taxtype'])?$_POST['taxtype']:0;
            $ItemTyp = !empty($_POST['ItemTyp'])?$_POST['ItemTyp']:0;
            $take_away = !empty($_POST['take_away'])?$_POST['take_away']:0;
            $prep_time = !empty($_POST['prep_time'])?$_POST['prep_time']:0;
            $seatNo = !empty($_POST['seatNo'])?$_POST['seatNo']:1;
            $DCd = !empty($_POST['DCd'])?$_POST['DCd']:0;
            $customerAddress = !empty($_POST['customerAddress'])?$_POST['customerAddress']:'';
            $CCd = !empty($_POST['CCd'])?$_POST['CCd']:0;

            $SchCd = !empty($_POST['SchCd'])?$_POST['SchCd']:0;
            $SDetCd = !empty($_POST['SDetCd'])?$_POST['SDetCd']:0;

            $CustItem = !empty($_POST['CustItem'])?$_POST['CustItem']:0;
            $CustItemDesc = !empty($_POST['CustItemDesc'])?$_POST['CustItemDesc']:'Std';
            
            $CountryCd = $_POST['CountryCd'];
            if(!empty($CountryCd)){
                $this->session->set_userdata('pCountryCd', $CountryCd);
            }

            $CustId = 0;
            if(!empty($phone)){
                $CustId = createCustUser($phone);
            }
            
            if ($CNo == 0) {
                if(!empty($phone)){

                    $this->db2->set('visit', 'visit+1', FALSE);
                    $this->db2->set('DelAddress', $customerAddress);
                    $this->db2->where('CustId', $CustId);
                    $this->db2->update('Users');
                }

                $CNo = $this->insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $phone, $EID, $ChainId, $ONo, $tableNo,$data_type, $orderType, $seatNo, $thirdParty, $thirdPartyRef, $CCd);
                if($orderType == 8){
                    updateRecord('Eat_tables', array('Stat' => 1), array('TableNo' => $tableNo, 'EID' => $EID));
                }
            }else{
                $oldSeatNo = getSeatNo($CNo);
                if($oldSeatNo != $seatNo){
                    $CNo = 0;
                    $CNo = $this->insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $phone, $EID, $ChainId, $ONo, $tableNo,$data_type, $orderType, $seatNo, $thirdParty, $thirdPartyRef, $CCd);
                }    
            }

            if ($KOTNo == 0) {
                // To generate new KOTNo
                $kotNoCount = $this->db2->query("SELECT Max(KOTNo + 1) as tKot from Kitchen where DATE(LstModDt) = CURDATE() AND EID = $EID")->result_array();

                if ($kotNoCount[0]['tKot'] == '') {
                    $kotNo = 1;
                } else {
                    $kotNo = $kotNoCount[0]['tKot'];
                }

                $KOTNo = $kotNo;
                $oldKitCd = 0;

                $this->session->set_userdata('KOTNo', $kotNo);
                $this->session->set_userdata('oldKitCd', 0);
            }

            $oldKitCd = $this->session->userdata('oldKitCd');
            $fKotNo = $KOTNo;

            $success = [];

            $oldKitCd = 0;
            
            $orderAmount = 0;
            $itemKitCd = 0;
            $newUKOTNO = 0;

            $stat = ($EType == 5)?3:2;

            for ($i = 0; $i < sizeof($itemIds); $i++) {
                $itemKitCd = $itemKitCds[$i];

                    if ($MultiKitchen > 1) {
                    } else {
                        $fkotArray = $KOTNo;
                    }
                    if ($oldKitCd != $itemKitCds[$i]) {
                        // $itemKitCd = $itemKitCds[$i];
                        $getFKOT = $this->db2->query("SELECT max(FKOTNO) as FKOTNO FROM Kitchen WHERE EID=$EID AND KitCd = $itemKitCd and TableNo = $tableNo and Stat = 3")->result_array();
                        $fKotNo = $getFKOT[0]['FKOTNO'];
                        $fKotNo = $fKotNo + 1;
                        $fkotArray[$i] = $fKotNo;
                        $newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
                    } 
                    $oldKitCd = $itemKitCd;

                $kitchenObj['CNo'] = $CNo;
                $kitchenObj['MCNo'] = $CNo;
                $kitchenObj['CustId'] = $CustId;
                $kitchenObj['EID'] = $EID;
                $kitchenObj['ChainId'] = $ChainId;
                $kitchenObj['OType'] = $orderType;
                if ($orderType == 101) {
                    $kitchenObj['TPRefNo'] = $thirdPartyRef;
                    $kitchenObj['TPId'] = $thirdParty;
                }
                $kitchenObj['KitCd'] = $itemKitCd;        
                $kitchenObj['FKOTNo'] = $fKotNo;          
                $kitchenObj['KOTNo'] = $kotNo;
                $kitchenObj['UKOTNo'] = $newUKOTNO;       
                $kitchenObj['TableNo'] = $tableNo;
                $kitchenObj['MergeNo'] = $tableNo;
                $kitchenObj['ItemId'] = $itemIds[$i];
                $kitchenObj['Qty'] = $itemQty[$i];
                $kitchenObj['TA'] = $take_away[$i];
                $kitchenObj['PckCharge'] = 0;
                if($kitchenObj['TA'] == 1){
                    $kitchenObj['PckCharge'] = $pckValue[$i];
                }
                $kitchenObj['CustRmks'] = $itemRemarks[$i];
                $kitchenObj['ItmRate'] = $ItmRate[$i];
                $kitchenObj['OrigRate'] = $origRates[$i];
                $kitchenObj['Stat'] = $stat;
                if(!empty($phone)){
                    $kitchenObj['CellNo'] = $CountryCd.$phone;
                }else{
                    $kitchenObj['CellNo'] = 0;
                }
                $kitchenObj['Itm_Portion'] = $Itm_Portion[$i];
                $kitchenObj['TaxType'] = $taxtype[$i];
                $kitchenObj['ItemTyp'] = $ItemTyp[$i];
                $kitchenObj['SeatNo'] = $seatNo;
                $kitchenObj['DCd'] = $DCd[$i];
                $kitchenObj['SchCd'] = $SchCd[$i];
                $kitchenObj['SDetCd'] = $SDetCd[$i];
                $kitchenObj['CustItem'] = $CustItem[$i];
                $kitchenObj['CustItemDesc'] = $CustItemDesc[$i];
                $kitchenObj['langId'] =$this->session->userdata('site_lang');
                // edt
                $date = date("Y-m-d H:i:s");
                $date = strtotime($date);
                $time = $prep_time[$i];
                $date = strtotime("+" . $time . " minute", $date);
                $edtTime = date('H:i', $date);
                // edt
                $kitchenObj['EDT'] = $edtTime;
                $kitchenObj['LoginCd'] = authuser()->RUserId;
                
                insertRecord('Kitchen', $kitchenObj);

                $orderAmount = $orderAmount + $ItmRate[$i];
            }
            // billbased offer
            $SchType = $this->session->userdata('SchType');
            if(in_array($SchType, array(1,3))){
                $billOffer = billBasedOffer();
                if(!empty($billOffer)){
                    $dis = 0;
                    if($orderAmount >= $billOffer['MinBillAmt']){
                        if($billOffer['Disc_pcent'] > 0){
                            $dis = ($orderAmount * $billOffer['Disc_pcent']) / 100;
                            $dis = round($dis);
                        }else{
                             if($billOffer['Disc_Amt'] > 0){
                                $dis = $billOffer['Disc_Amt'];
                            }   
                        }
                    }
                    updateRecord('KitchenMain', array('BillDiscAmt' => $dis), array('CNo' => $CNo, 'EID' => $EID));
                }
            }
            // end of billbased offer

            // delete from temp kitchen
            deleteRecord('tempKitchen', array('TableNo' => $tableNo, 'EID' => $EID));
            // end delete from temp kitchen

            $url = base_url('restaurant/kot_print/').$CNo.'/'.$tableNo.'/'.$fKotNo.'/'.$kotNo;
            $dArray = array('MCNo' => $CNo, 'MergeNo' => $tableNo,'FKOTNo' => $fKotNo,'KOTNo' => $kotNo,'sitinKOTPrint' => $this->session->userdata('sitinKOTPrint'), 'url' => $url);

            if ($data_type == 'bill' || $data_type == 'kot_bill') {

                $MergeNo = $tableNo;
                $lname = "m.Name$langId";
                $ipName = "ip.Name$langId as Portions";

                $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  (k.OrigRate*sum(k.Qty)) as OrdAmt, km.MCNo, km.MergeNo, k.FKOTNo, k.KOTNo, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID, k1.MCNo) as TotItemDisc,(SELECT sum(k1.PckCharge*k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID, k1.MCNo) as TotPckCharge, $ipName, km.CNo,km.MergeNo, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.CustId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.MCNo AND km.MCNo IN (Select km1.MCNo from KitchenMain km1 where km1.MergeNo=$MergeNo group by km1.MergeNo) group by km.MCNo, k.ItemId, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.Name1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by k.TaxType, m.Name1 Asc")->result_array();

                $taxDataArray = array();

                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $SubAmtTax = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    $CNo = $kitcheData[0]['CNo'];

                    $per_cent = 1;
                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);

                    $taxDataArray = $TaxRes['taxDataArray'];

                    foreach ($kitcheData as $kit ) {

                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                        
                    }

                    foreach ($taxDataArray as $tax) {
                        foreach ($tax as $key) {
                            if($key['Included'] >= 5){
                                $SubAmtTax = $SubAmtTax + round($key['SubAmtTax'], 2);
                            }
                        }
                    }

                    $orderAmt = $orderAmt + $SubAmtTax;

                    $this->session->set_userdata('TipAmount', 0);
                    $this->session->set_userdata('itemTotalGross', $orderAmt);

                    $this->session->set_userdata('ONo', 0);
                    $this->session->set_userdata('CustNo', 0);
                    $this->session->set_userdata('COrgId', 0);
                    $this->session->set_userdata('CellNo', '-');
                    
                    $charge =  $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    // grand total
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount;

                    $postData["orderAmount"] = $total;
                    $postData["paymentMode"] = 'RCash';
                    $postData["MergeNo"] = $MergeNo;
                    $postData["TableNo"] = $MergeNo;
                    $postData["cust_discount"] = 0;

                    $custId = $kitcheData[0]['CustId'];
                    $this->session->set_userdata('CustId', $custId);
                    $res = billCreate($EID, $CNo, $postData);
                    if($res['status'] > 0){  
                        $response = [
                            "status" => 1,
                            "msg" => "Bill Created.",
                            "data" => array('billId' => $res['billId'], 'MergeNo' => $kitcheData[0]['MergeNo'], 'MCNo' => $kitcheData[0]['MCNo'], 'FKOTNo' => $kitcheData[0]['FKOTNo'], 'KOTNo' => $kitcheData[0]['KOTNo'])
                        ];      
                    }else{
                        $response = [
                            "status" => 0,
                            "msg" => "Fail to insert to Billing"
                        ];
                    }
                }
                
            }else{
                $response = [
                        "status" => 1,
                        "msg" => "success",
                        "data" => $dArray 
                    ];
            }
            
            $CNo = 0;
            $KOTNo = 0;
            echo json_encode($response);
            die();
        }

        if (isset($_POST['bill_create']) && $_POST['bill_create']) {

            $data_type  =   $_POST['data_type'];
            $tableNo    =   $_POST['tableNo'];
            $orderType  =   $_POST['orderType'];
            $stat = ($EType == 5)?3:2;

            if ($data_type == 'bill' || $data_type == 'kot_bill') {

                $MergeNo = $tableNo;
                $lname = "m.Name$langId";
                $ipName = "ip.Name$langId as Portions";

                $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,sum(k.Qty) as Qty ,k.ItmRate,  (k.OrigRate*sum(k.Qty)) as OrdAmt, km.MCNo, km.MergeNo, k.FKOTNo, k.KOTNo, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID, k1.MCNo) as TotItemDisc,(SELECT sum(k1.PckCharge*k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.MCNo=km.MCNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID, k1.MCNo) as TotPckCharge, $ipName, km.CNo, km.MergeNo, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.CustId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.MCNo AND km.MCNo IN (Select km1.MCNo from KitchenMain km1 where km1.MergeNo=$MergeNo group by km1.MergeNo) group by km.MCNo, k.ItemId, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.Name1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by k.TaxType, m.Name1 Asc")->result_array();

                $taxDataArray = array();

                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $SubAmtTax = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    $CNo = $kitcheData[0]['CNo'];

                    $per_cent = 1;
                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);

                    $taxDataArray = $TaxRes['taxDataArray'];

                    foreach ($kitcheData as $kit ) {
                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                    }

                    foreach ($taxDataArray as $tax) {
                        foreach ($tax as $key) {
                            if($key['Included'] >= 5){
                                $SubAmtTax = $SubAmtTax + round($key['SubAmtTax'], 2);
                            }
                        }
                    }

                    $orderAmt = $orderAmt + $SubAmtTax;

                    $this->session->set_userdata('TipAmount', 0);
                    $this->session->set_userdata('itemTotalGross', $orderAmt);

                    $this->session->set_userdata('ONo', 0);
                    $this->session->set_userdata('CustNo', 0);
                    $this->session->set_userdata('COrgId', 0);
                    $this->session->set_userdata('CellNo', '-');
                    
                    $charge =  $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    // grand total
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount;

                    $postData["orderAmount"] = $total;
                    $postData["paymentMode"] = 'RCash';
                    $postData["MergeNo"] = $MergeNo;
                    $postData["TableNo"] = $MergeNo;
                    $postData["cust_discount"] = 0;

                    $custId = $kitcheData[0]['CustId'];
                    $this->session->set_userdata('CustId', $custId);
                    $res = billCreate($EID, $CNo, $postData);
                    if($res['status'] > 0){  
                        $response = [
                            "status" => 1,
                            "msg" => "Bill Created.",
                            "data" => array('billId' => $res['billId'], 'MergeNo' => $kitcheData[0]['MergeNo'], 'MCNo' => $kitcheData[0]['MCNo'], 'FKOTNo' => $kitcheData[0]['FKOTNo'], 'KOTNo' => $kitcheData[0]['KOTNo'])
                        ];      
                    }else{
                        $response = [
                            "status" => 0,
                            "msg" => "Fail to insert to Billing"
                        ];
                    }
                }
                
            }else{
                $response = [
                        "status" => 1,
                        "msg" => "success",
                        "data" => $dArray 
                    ];
            }
            
            $CNo = 0;
            $KOTNo = 0;
            echo json_encode($response);
            die();
        }

        if(isset($_POST['get_table_order_items'])){
            $stat = ($EType == 5)?3:2;
            $langId = $this->session->userdata('site_lang');
            $lname = "mi.Name$langId as ItemNm";

            $mergeNo = $_POST['mergeNo'];
            $seatNo = $_POST['seatNo'];
            $whr = "mir.Itm_Portion = k.Itm_Portion and mir.SecId = (SELECT et.SecId from Eat_tables et where et.EID = k.EID and et.TableNo = k.TableNo )";
            $data = $this->db2->select("k.CNo, k.TA, k.Qty, k.ItmRate, k.CustRmks,k.CellNo, km.BillStat, $lname, mir.OrigRate, k.SeatNo, k.Itm_Portion, k.ItemId")
                        ->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
                        ->join('MenuItem mi', 'mi.ItemId = k.ItemId', 'inner')
                        ->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
                        ->where($whr)
                        ->get_where('KitchenMain km', array(
                                            'km.MergeNo' => $mergeNo,
                                            'k.Stat' => $stat,
                                            'km.BillStat' => 0,
                                            'k.SeatNo' => $seatNo ,
                                            'mir.OrigRate >' => 0,
                                            'k.EID' => $EID,
                                            'km.EID' => $EID
                                        )
                                    )
                        ->result_array();
            
            echo json_encode($data);
        }
    }

    private function insertKitchenMain($CNo, $EType, $CustId, $COrgId, $CustNo, $CellNo, $EID, $ChainId, $ONo, $TableNo,$data_type, $orderType, $seatNo, $thirdParty, $thirdPartyRef, $CCd)
    {
        if ($CNo == 0) {

            $TableNo = $TableNo;

            $kitchenMainObj['CustId'] = $CustId;
            $kitchenMainObj['COrgId'] = $COrgId;
            $kitchenMainObj['CustNo'] = $CustNo;
            $kitchenMainObj['CellNo'] = $CellNo;
            if(empty($CellNo)){
                $kitchenMainObj['CellNo'] = 0;
            }
            $kitchenMainObj['EID'] = $EID;
            $kitchenMainObj['ChainId'] = $ChainId;
            $kitchenMainObj['ONo'] = $ONo;
            $kitchenMainObj['OType'] = $orderType;
            $kitchenMainObj['TPId'] = $thirdParty;
            $kitchenMainObj['TPRefNo'] = $thirdPartyRef;
            $kitchenMainObj['TableNo'] = $TableNo;
            $kitchenMainObj['MergeNo'] = $TableNo;
            $kitchenMainObj['OldTableNo'] = $TableNo;
            $kitchenMainObj['Stat'] = 2;
            $kitchenMainObj['LoginCd'] = authuser()->RUserId;
            $kitchenMainObj['TPRefNo'] = '';
            $kitchenMainObj['MngtRmks'] = '';
            $kitchenMainObj['BillStat'] = 0;
            $kitchenMainObj['payRest'] = 0;
            $kitchenMainObj['SeatNo'] = $seatNo;
            $kitchenMainObj['CCd'] = $CCd;
           
            $CNo = insertRecord('KitchenMain', $kitchenMainObj);
            if (!empty($CNo)) {
                updateRecord('KitchenMain', array('MCNo' => $CNo), array('CNo' => $CNo, 'EID' => $EID));
                $this->session->set_userdata('CNo', $CNo);
                return $CNo;
            }
        } else {
            return $CNo = $CNo;
        }
    }

    public function tokenGenerate(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $data['token'] = $_POST['token']; 
            $data['LstModDt'] = date('Y-m-d H:i:s'); 
            updateRecord('UsersRest', $data, array('RUserId' => authuser()->RUserId) );
            $status = 'success';
            $response = 'Token Generated.';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function rmcat(){
        $this->check_access();
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $RMCatgCd = 0;
            if(isset($_POST['RMCatgCd']) && !empty($_POST['RMCatgCd'])){
                $RMCatgCd = $_POST['RMCatgCd'];
            }

            if(!empty($RMCatgCd)){
                updateRecord('RMCatg', array('RMCatgName' => $_POST['RMCatgName']), array('RMCatgCd' => $RMCatgCd));
                $status = 'success';
                $response = 'Category Updated.';
            }else{
                $check = getRecords('RMCatg', array('RMCatgName' => $_POST['RMCatgName']));
                if(!empty($check)){
                    $response = 'Category Already Exists';
                }else{
                    $cat['RMCatgName'] = $_POST['RMCatgName'];
                    $cat['Stat'] = 0;
                    $cat['EID'] = $EID;
                    insertRecord('RMCatg', $cat);
                    $status = 'success';
                    $response = 'Category Inserted';
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        
        $langId = $this->session->userdata('site_lang');
        $RMCatgName = "Name$langId as RMCatgName";

        $data['catList'] = $this->db2->select("*, $RMCatgName")->get_where('RMCatg', array('EID' => $EID))->result_array();
        $data['title'] ='RMCategory';
        $this->load->view('rest/rm_category',$data);
    }

    public function rmitems_list(){
        $this->check_access();
        $langId = $this->session->userdata('site_lang');

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $RMName = "Name$langId";
            $RMCd = 0;
            if(isset($_POST['RMCd']) && !empty($_POST['RMCd'])){
                $RMCd = $_POST['RMCd'];
            }

            if(!empty($RMCd)){
                updateRecord('RMItems', array("$RMName" => $_POST['RMName']), array('RMCd' => $RMCd));
                $status = 'success';
                $response = 'RMItem Updated.';
            }else{
                $check = getRecords('RMItems', array("$RMName" => $_POST['RMName'], 'RMCatg' => $_POST['RMCatg']));

                if(!empty($check)){
                    $response = 'RMItem Already Exists';
                }else{
                    $cat["$RMName"] = $_POST['RMName'];
                    $cat['RMCatg'] = $_POST['RMCatg'];
                    $cat['ItemId'] = $_POST['ItemId'];
                    $cat['Stat'] = 0;
                    insertRecord('RMItems', $cat);
                    $status = 'success';
                    $response = 'RMItem Inserted';
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $RMCatgName = "Name$langId as RMCatgName";

        $data['catList'] = $this->db2->select("*, $RMCatgName")->get('RMCatg')->result_array();
        $data['rm_items'] = $this->rest->getItemLists();
        $data['itemList'] = $this->rest->getAllItemsList();
        $data['title'] ='RMItems List';
        $this->load->view('rest/rm_items',$data);
    }

    public function bom_dish(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $bom['ItemId'] = $_POST['ItemId'];
            for ($i=0; $i < sizeof($_POST['RMCd']) ; $i++) { 
                $BOMNo = 0;
                if(isset($_POST['BOMNo'][$i]) && !empty($_POST['BOMNo'][$i])){
                    $BOMNo = $_POST['BOMNo'][$i];
                }
                $bom['RMCd'] = $_POST['RMCd'][$i];
                $bom['RMQty'] = unicodeToEnglish($_POST['RMQty'][$i]);
                $bom['RMUOM'] = $_POST['RMUOM'][$i];
                if(!empty($BOMNo)){
                    updateRecord('BOM_Dish', $bom, array('BOMNo' => $BOMNo) );
                    $response = 'Data updated.';
                }else{
                    insertRecord('BOM_Dish', $bom);
                    $response = 'Data inserted.';
                }
            }

            $status = 'success';
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['cuisine'] = $this->rest->getCuisineList();
        $data['rm_items'] = $this->rest->getItemLists();
        $data['title'] = $this->lang->line('billOfMaterial');
        $this->load->view('rest/bom_dish',$data);
    }

    public function getMenuItemList(){
        if($_POST){
            $mcatid = $_POST['MCatgId'];

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId as ItemNm";

            $data = $this->db2->select("ItemId, $lname")
                              ->get_where('MenuItem', array('MCatgId' => $mcatid))
                              ->result_array();
            echo json_encode($data);
        }
    }

    public function getRMItemsUOMList(){
        if($_POST){
            $RMCd = $_POST['RMCd'];
            $data = $this->rest->getRmUOMlist($RMCd);
            echo json_encode($data);
        }
    }

    public function get_bom_dish(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
           
            $response = '';
            $data = $this->db2->get_where('BOM_Dish', array('ItemId' => $_POST['item']))->result_array();
            $temp = '';
            if(!empty($data)){
                $count = 0;
                foreach ($data as $key) {
                    $count++;
                    $temp .= '<tr>
                            <td>'.$this->getRMName($key['RMCd'], $count).'</td>
                            <td>
                            <input type="number" class="form-control" name="RMQty[]" placeholder="Quantity" required="" id="RMQty" value="'.$key['RMQty'].'">
                            </td>
                            <td>'.$this->getRMUOM($key['RMCd'], $key['RMUOM'], $count).'</td>
                            <td>
                            <input type="hidden" name="BOMNo[]" value="'.$key['BOMNo'].'" />
                            </td>
                        </tr>';
                }
                $response = $temp;
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    private function getRMName($RMCd, $count){
        $rm_items = $this->rest->getItemLists();
        $temp = '<select name="RMCd[]" id="RMCd_'.$count.'" class="form-control" required="" onchange="getRMItemsUOM('.$count.')">
                                <option value="">Select</option>';
        foreach ($rm_items as $row ) {
            $select = '';
            if($row['RMCd'] == $RMCd){
                $select = 'selected';
            }
            $temp .= '<option value="'.$row['RMCd'].'" '.$select.'>'.$row['RMName'].'</option>';
        }
        $temp .= '</select>';
        return $temp;
    }

    private function getRMUOM($RMCd, $UOMCd, $count){
        $data['RMUOM'] = $this->rest->getRmUOMlist($RMCd);
        $temp = '<select name="RMUOM[]" id="RMUOM_'.$count.'" class="form-control" required="">
                                <option value="">Select RMUOM</option>';
        foreach ($data['RMUOM']as $row ) {
            $select = '';
            if($row['UOMCd'] == $UOMCd){
                $select = 'selected';
            }
            $temp .= '<option value="'.$row['UOMCd'].'" '.$select.'>'.$row['Name'].'</option>';
        }
        $temp .= '</select>';
        return $temp;
    }
    
    public function get_billing_details(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            extract($_POST);
            $data = $this->rest->getBillDetailsForSettle($custId, $MCNo, $mergeNo);
            $status = 'success';
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }
    }

    function get_cash_collect(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            extract($_POST);
            $whr = '';
            if($custId > 0){
                $whr = " and b.CustId = $custId";
            }
            
            $bill = array();
            $EID = authuser()->EID;
             $bills = $this->db2->query("SELECT b.TableNo, b.MergeNo, b.BillId, b.BillNo, b.CNo, b.TotAmt, b.PaidAmt, b.CustId, b.EID, b.MergeNo, b.CellNo, b.CustId from Billing b where b.EID = $EID and b.MergeNo = '$mergeNo' and b.CNo = $MCNo $whr and b.payRest = 0")->result_array();
            $data['sts'] = 0;
            if(!empty($bills)){
                $bill = $bills;
                $data['sts'] = 1;
            }

            $data['bills'] = $bill;
            $data['payModes'] = $this->rest->getPaymentModes();
        
            $status = 'success';
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }   
    }

    public function settle_bill_without_payment(){
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $otp = $this->session->userdata('payment_otp');
            $MergeNo = $_POST['paymentMergeNo'];
            $MCNo = $_POST['paymentMCNo'];
            $billId = $_POST['billId'];

            if($_POST['otp'] == $otp){
                if(empty($_POST['paymentCustId'])){
                    $CustId = createCustUser($_POST['paymentMobile']);
                }else{
                    $CustId = createCustUser($_POST['paymentCustId']);
                }
                $ca['custId']       = $CustId;
                $ca['MobileNo']     = $_POST['paymentMobile'];
                $ca['OTP']          = $_POST['otp'];
                $ca['billId']       = $billId;
                $ca['billAmount']   = $_POST['billAmount'];
                $ca['EID']          = $EID;
                $ca['mode']         = $_POST['paymentMode'];

                $caId = insertRecord('custAccounts', $ca);
                if(!empty($caId)){

                    $pay = array('BillId' => $billId,
                                'MCNo' => $MCNo,
                                'MergeNo' => $MergeNo,
                                'TotBillAmt' => $ca['billAmount'],
                                'CellNo' => $_POST['paymentMobile'],
                                'SplitTyp' => 0 ,'SplitAmt' => 0,'PymtId' => 0,
                                'PaidAmt' => 0 ,'OrderRef' => 0,
                                'PaymtMode'=> $_POST['paymentMode'],'PymtType' => 0,
                                'PymtRef'=>  0, 'Stat'=>  1 ,'EID'=>  $EID,
                                'billRef' => 0);

                    insertRecord('BillPayments', $pay);

                    autoSettlePayment($billId, $MergeNo, $MCNo);

                    updateRecord('Billing', array('Stat' => $_POST['paymentMode']), array('BillId' => $billId, 'EID' => $EID));

                    $status = "success";
                    $response = "Bill Settled.";
                }
            }else{
                $response = "OTP Doesn't Matched!!";
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function collect_payment()
    {
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $pay = $_POST;

            $pay['PaymtMode'] = 1;
            $pay['SplitTyp'] = 0;
            $pay['SplitAmt'] = 0;
            $pay['PymtId'] = 0;
            $pay['OrderRef'] = 0;
            $pay['PymtType'] = 0;
            $pay['PymtRef'] = 0;
            $pay['Stat'] = 0;
            $TableNo = $pay['TableNo'];
            unset($pay['TableNo']);
            $EID = $pay['EID'];

            $checkBP = $this->db2->get_where('BillPayments', array('EID' => $pay['EID'],'BillId' => $pay['BillId']))->row_array();

            if(!empty($checkBP)){
                updateRecord('BillPayments', array('PymtType' => $pay['PymtType']), array('EID' => $pay['EID'],'BillId' => $pay['BillId']));
            }else{
                unset($pay['oType']);
                unset($pay['CustId']);
                $payNo = insertRecord('BillPayments', $pay);
            }

            $status = 'success';
            $response = $this->lang->line('paymentCollected');
            
            if($this->session->userdata('AutoSettle') == 1){
                autoSettlePayment($pay['BillId'], $pay['MergeNo'], $_POST['MCNo']);
            }else{
                $billId = $pay['BillId'];

                if($this->session->userdata('EType') == 1){

                    updateRecord('Billing', array('Stat' => 1,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));

                    updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));

                    $this->db2->query("UPDATE Kitchen k, KitchenMain km, Billing b SET k.payRest=1, km.payRest=1, km.CnfSettle = 1, k.Stat = 3, km.custPymt = 1 WHERE b.BillId = $billId and (k.Stat = 2) AND k.CNo=km.CNo and km.EID=k.EID and k.EID = $EID and (km.CNo = b.CNo OR km.MCNo = b.CNo)");
                }else{
                    updateRecord('Billing', array('Stat' => 1,'payRest' => 1), array('BillId' => $billId, 'EID' => $EID));

                    updateRecord('BillPayments', array('Stat' => 1), array('BillId' => $billId,'EID' => $EID));
                }
            }
            // loyality
            $loyalties = array(
                     'LId'          => 0,
                     'custId'       => 0,
                     'EID'          => $EID,
                     'billId'       => $_POST['BillId'],
                     'billDate'     => date('Y-m-d H:i:s'),
                     'billAmount'   => $_POST['TotBillAmt'],
                     'MobileNo'     => $_POST['CellNo'],
                     'OTP'          => 0,
                     'Points' => 0,
                     'earned_used'  => 0
                    );
            insertLoyalty($loyalties);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        } 
    }

    public function send_payment_otp(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $mobileNO = $_POST['mobile'];

            $otp = rand(9999,1000);
            $this->session->set_userdata('payment_otp', $otp);
            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            sendSMS($mobileNO, $msgText);
            saveOTP($mobileNO, $otp, 'payNow');

            $status = "success";
            $response = "OTP send on your mobile no.";
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function resend_payment_OTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $mobileNO = $_POST['mobile'];

            $otp = rand(9999,1000);
            $this->session->set_userdata('payment_otp', $otp);
            $msgText = "$otp is the OTP for EATOUT, valid for 45 seconds - powered by Vtrend Services";
            sendSMS($mobileNO, $msgText);
            saveOTP($mobileNO, $otp, 'table view');

            $status = "success";
            $res = 'Resend OTP Successfully.';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }   
    }

    public function file_rename(){

        $data['title'] = 'File Rename';
        $this->load->view('rest/rename_file', $data);
    }

    public function test(){

       echo "<pre>";
        print_r($_SESSION);
        die;
        $data['title'] ='dd';
        $this->load->view('rest/blank',$data);
    }

    public function billCreateRest(){
        $status = "error";
        $response = "Something went wrong! Try again later.";

        $EType = $this->session->userdata('EType');
        if($this->input->method(true)=='POST'){
            $MergeNo = $_POST['mergeNo'];
            $EID = authuser()->EID;

            $SchType = $this->session->userdata('SchType');
            if(in_array($SchType, array(1,3))){
                $this->rest->updateBillDiscountAmount($MergeNo);
            }
             // SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty),(k.ItmRate*k.Qty))) as OrdAmt,

            $langId = $this->session->userdata('site_lang');
            $lname = "m.Name$langId";
            $ipName = "ip.Name$langId  as Portions";

            $groupby = ' km.MCNo';
            if($_POST['tableFilter'] == 'tableWise'){
                $characterToFind = '~';
                $count = substr_count($MergeNo, $characterToFind);
                if ($count > 0) {
                    $groupby = ' km.MergeNo';
                }
            }

            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate,k.ItmRate,  SUM(k.OrigRate*k.Qty) as OrdAmt, (SELECT sum((k1.OrigRate-k1.ItmRate) * k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty) from Kitchen k1 where k1.MergeNo = km.MergeNo and k1.MergeNo = $MergeNo  and k1.EID=km.EID AND (k1.Stat = 3) and k1.BillStat = km.BillStat GROUP BY k1.EID) as TotPckCharge, $ipName, km.CNo,km.MergeNo, km.MCNo,sum(km.BillDiscAmt) as BillDiscAmt, sum(km.DelCharge) as DelCharge, sum(km.RtngDiscAmt) as totRtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType, k.TA, km.RtngDiscAmt,km.TableNo, km.CustId, c.ServChrg, c.Tips,e.Name, m.CTyp  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND km.MergeNo = $MergeNo group by $groupby, k.TA,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.Name1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.Name1 Asc")->result_array();

            // remove string
            $MergeNo = str_replace("'","",$MergeNo);
            
                $taxDataArray = array();
                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    $CNo = $kitcheData[0]['MCNo'];

                    $per_cent = 1;
                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);
                    $taxDataArray = $TaxRes['taxDataArray'];

                    foreach ($kitcheData as $kit ) {
                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                    }
                    //tax calculate
                    $SubAmtTax = 0;
                    foreach ($taxDataArray as $tax) {
                        foreach ($tax as $key) {
                            if($key['Included'] >= 5){
                                $SubAmtTax = $SubAmtTax + round($key['SubAmtTax'], 2);
                            }
                        }
                    }
                        
                    $orderAmt = $orderAmt + $SubAmtTax;
                    $custDiscount = ($orderAmt * $_POST['custDiscPer']) / 100;
                    // check session and remove
                    $this->session->set_userdata('TipAmount', 0);
                    $this->session->set_userdata('itemTotalGross', $orderAmt);
                    $this->session->set_userdata('ONo', 0);
                    $this->session->set_userdata('CustNo', 0);
                    $this->session->set_userdata('COrgId', 0); 

                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    $charge = $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount - $custDiscount;
                    
                    $postData["orderAmount"] = $total;
                    $postData["paymentMode"] = 'RCash';
                    $postData["MergeNo"] = $MergeNo;
                    $postData["TableNo"] = $kitcheData[0]['TableNo'];
                    $postData["cust_discount"] = $custDiscount;

                    $custId = $kitcheData[0]['CustId'];
                    
                    $res = billCreate($EID, $CNo, $postData);
                    if($res['status'] > 0){
                        updateRecord('KitchenMain', array('discount' => $_POST['custDiscPer']), array('CNo' => $CNo, 'MergeNo' => '$MergeNo','EID' => $EID));
                        $status = 'success';
                        $response = $res['billId'];         
                    }
                }else{
                    $response = 'Bill Already Generated.';
                }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function bill($billId){

        $data['title'] = $this->lang->line('billing');
        $data['billId'] = $billId;
        $this->load->view('rest/billing', $data);
    }

    public function kot_billing($billId, $MCNo, $MergeNo, $FKOTNo, $KOTNo){

        $data['title'] = 'KOT & Billking';
        $kotList = $this->rest->getKotList($MCNo, $MergeNo, $FKOTNo, $KOTNo);
        $group_arr = [];
        foreach ($kotList as $key ) {
            $kot = $key['KitCd'];
            if(!isset($group_arr[$kot])){
                $group_arr[$kot] = [];
            }
            array_push($group_arr[$kot], $key);
        }
        $data['kotList'] = $group_arr;
        $data['billId'] = $billId;
        $EID = authuser()->EID;
        $detail = $this->db2->select('CustId,CustNo,CellNo')->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))->row_array();
        $CustId = $detail['CustId'];
        $data['CustNo'] = $detail['CustNo'];
        $dbname = $this->session->userdata('my_db');

        $flag = 'rest';
        $res = getBillData($dbname, $EID, $billId, $CustId, $flag);
        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];
            
            $data['hotelName'] = $billData[0]['Name'];
            $data['TableNo'] = $billData[0]['TableNo'];
            $data['Fullname'] = getName($billData[0]['CustId']);
            $data['CellNo'] = $billData[0]['CellNo'];
            $data['phone'] = $billData[0]['PhoneNos'];
            $data['gstno'] = $billData[0]['GSTno'];
            $data['fssaino'] = $billData[0]['FSSAINo'];
            $data['cinno'] = $billData[0]['CINNo'];
            $data['billno'] = $billData[0]['BillNo'];
            $data['OType'] = $billData[0]['OType'];
            $data['dateOfBill'] = date('d-m-Y @ H:i', strtotime($billData[0]['BillDt']));
            $data['address'] = $billData[0]['Addr'];
            $data['pincode'] = $billData[0]['Pincode'];
            $data['city'] = $billData[0]['City'];
            $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
            $data['bservecharge'] = $billData[0]['bservecharge'];
            $data['SerChargeAmt'] = $billData[0]['SerChargeAmt'];

            $data['tipamt'] = $billData[0]['Tip'];
            $Stat = $billData[0]['Stat'];
            $total = 0;
            $sgstamt=0;
            $cgstamt=0;
            $grandTotal = $sgstamt + $cgstamt + $data['bservecharge'] + $data['tipamt'];
            $data['thankuline'] = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

            $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'] + $billData[0]['custDiscAmt'];
            $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
            $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

            $data['billData'] = $res['billData'];
        }
        $this->load->view('rest/kot_bill', $data);
    }

    public function payments(){
        $this->check_access();
        $data['title'] = $this->lang->line('customerPayments');
        $data['fdate'] = date('Y-m-d');
        $data['tdate'] = date('Y-m-d');
        $data['pmode'] = '';
        $pdata = array
                    (
                        'fdate' => $data['fdate'],
                        'tdate' => $data['tdate'],
                        'pmode' => $data['pmode']
                    );
        if($this->input->method(true)=='POST'){
            $pdata = $_POST;
            $data['fdate'] = date('Y-m-d', strtotime($pdata['fdate']));
            $data['tdate'] = date('Y-m-d', strtotime($pdata['tdate']));
            $data['pmode'] = $pdata['pmode'];
        }
        $data['details'] = $this->rest->getPaymentList($pdata);
        $data['modes'] = $this->rest->getPaymentModes();
        $this->load->view('rest/payments', $data);
    }

    public function feedback(){
        $this->check_access();
        $data['title'] = $this->lang->line('customerFeedback');
        $data['list'] = $this->db2->order_by('created_at','DESC')->get_where('Feedback', array('EID' => authuser()->EID))->result_array();
        
        $this->load->view('rest/feedback_list', $data);
    }

    public function email_send(){
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

    public function link_generate(){
        $this->check_access();
        $EID = authuser()->EID;

        include APPPATH.'third_party/phpqrcode/qrlib.php'; 
        $data['title'] = $this->lang->line('qrColdeLink');
        $data['eid'] = $EID;
        $data['chain'] = '';
        $data['table'] = '';
        $data['stock'] = '';
        
        $listData = array();

        if($this->input->method(true)=='POST'){
            $from = 0;
            $to = 0;
            $data['chain'] = authuser()->ChainId;

            $folderPath = "uploads/e$EID/qrcode/";
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, true);
            }
            // remove all files inside this folder uploads/qrcode/
            $filesPath = glob($folderPath.'/*'); // get all file names
            foreach($filesPath as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); 
              }
            }  
            // end remove all files inside folder
            $codesDir = $folderPath;

            if(!empty($_POST['qrcode'])){
                if($_POST['qrcode'] == 'stall'){
                    $from = $_POST['from_stall'];
                    $to = $_POST['to_stall'];

                    for ($i=$from; $i <= $to ; $i++) { 

                        $link = 'e='.$data['eid'].'&c='.$data['chain'].'&t=0&o='.$i;
                        $link64 = base64_encode($link);
                        $temp['link'] = base_url('qr?qr_data=').rtrim($link64, "=");
                        $temp['tblNo'] = $i;

                        $codeFile = $i.'_'.$EID.'_'.date('d-m-Y-h-i-s').'.png';
                        $formData = $temp['link'];
                        // generating QR code
                        QRcode::png($formData, $codesDir.$codeFile, $level = 'H', $size = 5);
                        $temp['img'] = $codeFile;

                        $listData[] = $temp;

                        $tblDt['TableNo'] = $i;
                        $tblDt['link'] = $temp['link'];
                        $tblDt['EID'] = $EID;
                        $tblDt['file'] = $codeFile;
                        insertRecord('QRCodes', $tblDt);
                    }
                }else{
                    $from = $_POST['from_table'];
                    $to = $_POST['to_table'];

                    for ($i=$from; $i <= $to ; $i++) { 

                        $link = 'e='.$data['eid'].'&c='.$data['chain'].'&t='.$i.'&o=0';
                        $link64 = base64_encode($link);
                        $temp['link'] = base_url('qr?qr_data=').rtrim($link64, "=");
                        $temp['tblNo'] = $i;

                        $codeFile = $i.'_'.$EID.'_'.date('d-m-Y-h-i-s').'.png';
                        $formData = $temp['link'];
                        // generating QR code
                        QRcode::png($formData, $codesDir.$codeFile, $level = 'H', $size = 5);
                        $temp['img'] = $codeFile;

                        $listData[] = $temp;

                        $tblDt['TableNo'] = $i;
                        $tblDt['link'] = $temp['link'];
                        $tblDt['EID'] = $EID;
                        $tblDt['file'] = $codeFile;
                        insertRecord('QRCodes', $tblDt);
                    }
                }
            redirect(base_url('restaurant/link_generate'));
            }
        }
        
        $data['lists'] = $this->rest->getQRCodesLink();
        $this->load->view('rest/link_create', $data);   
    }

    function remove_qr_code(){
        $status = 'error';
        $response = 'Something went wrong please try again!!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $EID = authuser()->EID;
            $qId = $_POST['qid'];
            $TableNo = $_POST['TableNo'];

            $response = "QR Code for $TableNo Deleted.";
            $dt = getRecords('QRCodes', array('EID' => $EID, 'qId' => $qId));

            deleteRecord('QRCodes', array('EID' => $EID, 'qId' => $qId) );

             // remove existing file
            $fileurl = 'uploads/e'.$EID.'/'.$dt['file']; 
            if (file_exists($fileurl)) {
                unlink($fileurl);
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function add_item(){
        $this->check_access();

        if($this->input->method(true)=='POST'){

            $getItem = $this->db2->select('UItmCd, Rank')->order_by('ItemId', 'DESC')->get('MenuItem')->row_array();

            $data = $_POST;

            $langId = $this->session->userdata('site_lang');

            $iname = "Name$langId";
            $descname = "ItmDesc$langId";
            $ingname = "Ingeredients$langId";

            $data[$iname] = $data['ItemNm'];
            $data[$descname] = $data['ItmDesc'];
            $data[$ingname] = $data['Ingeredients'];

            unset($data['sections']);
            unset($data['portions']);
            unset($data['price']);
            unset($data['ItemNm']);
            unset($data['ItmDesc']);
            unset($data['Ingeredients']);

            $data['UItmCd'] = $getItem['UItmCd']+1;
            $data['Rank'] = $getItem['Rank']+1;
            $data['EID'] = authuser()->EID;
            $data['ChainId'] = authuser()->ChainId;
            // $data['Stall'] = 0;
            // $data['Value'] = 0;
            // $data['Itm_Portion'] = 0;
            $data['AvgRtng'] = '3.5';
            $data['Stat'] = 0;
            $data['LoginCd'] = authuser()->RUserId;

            $flag = 0;
            if(isset($_FILES['item_file']['name']) && !empty($_FILES['item_file']['name'])){ 

                $files = $_FILES['item_file'];

                $allowed = array('jpeg', 'jpg');
                $filename_c = $_FILES['item_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only jpg,jpeg format!');
                }
                // less than 1mb size upload
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }

                $_FILES['item_file']['name']= $files['name'];
                $_FILES['item_file']['type']= $files['type'];
                $_FILES['item_file']['tmp_name']= $files['tmp_name'];
                $_FILES['item_file']['error']= $files['error'];
                $_FILES['item_file']['size']= $files['size'];
                $file = $data[$iname];

                // $folderPath = '/var/www/eo.vtrend.org/public_html/uploads/e'.authuser()->EID;
                $folderPath = FCPATH."uploads/e".authuser()->EID;
                
                if (!file_exists($folderPath)) {
                    // Create the directory
                    mkdir($folderPath, 0777, true);
                    chmod($folderPath, 0777);
                }

                if($flag == 0){
                    $res = do_upload('item_file',$file,$folderPath,'*');
                }
                
              }

            if($flag == 0){
              $ItemId = insertRecord('MenuItem', $data);

              $menuRates = [];
              $tempData = [];
              for ($i=0; $i < sizeof($_POST['sections']); $i++) { 
                  $tempData['EID'] = authuser()->EID;
                  $tempData['ChainId'] = authuser()->ChainId;
                  $tempData['ItemId'] = $ItemId;
                  $tempData['SecId'] = $_POST['sections'][$i];
                  $tempData['Itm_Portion'] = $_POST['portions'][$i];
                  $tempData['OrigRate'] = 0;
                  $tempData['ItmRate'] = $_POST['price'][$i];
                  $menuRates[] = $tempData;
              }
              $this->db2->insert_batch('MenuItemRates', $menuRates); 
              
              if(!empty($ItemId)){
                $this->session->set_flashdata('success','Record Inserted.');
              }
            }
        }
        
        $data['title'] = $this->lang->line('addItem');
        $data['CuisineList'] = $this->rest->getCuisineList();
        $data['Eat_Kit'] = $this->rest->get_kitchen_list();
        $data['ctypList'] = $this->rest->getCTypeList();
        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['menuTags'] = $this->rest->getMenuTagList();
        $data['uomList'] = $this->rest->getUOMlist();        
        $data['EatSections'] = $this->rest->get_eat_section();
        $data['ItemPortions'] = $this->rest->get_item_portion();
        
        $this->load->view('rest/add_item', $data);
    }

    public function get_item_name(){
        extract($_POST);
        $data = $this->rest->get_item_name_list($name);
        echo json_encode($data);
        die;
    }

    public function edit_item($ItemId){
        // $this->check_access();
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
 
            $updateData = $_POST;

            $langId = $this->session->userdata('site_lang');

            $iname = "Name$langId";
            $descname = "ItmDesc$langId";
            $ingname = "Ingeredients$langId";

            $updateData[$iname] = $updateData['ItemNm'];
            $updateData[$descname] = $updateData['ItmDesc'];
            $updateData[$ingname] = $updateData['Ingeredients'];

            $flag = 0;

            if(isset($_FILES['item_file']['name']) && !empty($_FILES['item_file']['name'])){ 

                $folderPath = 'uploads/e'.$EID;
                if (!file_exists($folderPath)) {
                    // Create the directory
                    mkdir($folderPath, 0777, true);
                }
                // remove existing file
                $filename = $folderPath.'/'.$updateData[$iname].'.jpg'; 
                if (file_exists($filename)) {
                    unlink($filename);
                }

                $files = $_FILES['item_file'];

                $allowed = array('jpeg', 'jpg');
                $filename_c = $_FILES['item_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only jpg,jpeg format!');
                }
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }

                $_FILES['item_file']['name']= $files['name'];
                $_FILES['item_file']['type']= $files['type'];
                $_FILES['item_file']['tmp_name']= $files['tmp_name'];
                $_FILES['item_file']['error']= $files['error'];
                $_FILES['item_file']['size']= $files['size'];
                // $file = str_replace(' ', '_', rand('10000','999').'_'.$files['name']);
                $file = $updateData[$iname];
                if($flag == 0){
                    $res = do_upload('item_file',$file,$folderPath,'*');
                }
              }

            if($flag == 0){
                unset($updateData['ItemId']);
                unset($updateData['sections']);
                unset($updateData['portions']);
                unset($updateData['price']);
                unset($updateData['ItemNm']);
                unset($updateData['ItmDesc']);
                unset($updateData['Ingeredients']);

                updateRecord('MenuItem', $updateData, array('ItemId' => $ItemId));
                $this->db2->delete('MenuItemRates', array('ItemId' => $ItemId, 'EID' => $EID));
                $menuRates = [];
                $tempData = [];
                for ($i=0; $i < sizeof($_POST['sections']); $i++) { 
                  $tempData['EID'] = $EID;
                  $tempData['ChainId'] = authuser()->ChainId;
                  $tempData['ItemId'] = $ItemId;
                  $tempData['SecId'] = $_POST['sections'][$i];
                  $tempData['Itm_Portion'] = $_POST['portions'][$i];
                  $tempData['OrigRate'] = 0;
                  $tempData['ItmRate'] = $_POST['price'][$i];
                  $menuRates[] = $tempData;
                }
                $this->db2->insert_batch('MenuItemRates', $menuRates); 
                $this->session->set_flashdata('success','Record Updated.');
            }
        }
        $data['title'] = 'Edit Item';
        $data['ItemId'] = $ItemId;
        $data['MCatgIds'] = $this->rest->get_MCatgId();
        $data['CuisineList'] = $this->rest->getCuisineList();
        $data['FoodType'] = $this->rest->get_foodType();
        $data['Eat_Kit'] = $this->rest->get_kitchen();
        $data['ctypList'] = $this->rest->getCTypeList();
        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['menuTags'] = $this->rest->getMenuTagList();
        $data['uomList'] = $this->rest->getUOMlist();
        $data['EatSections'] = $this->rest->get_eat_section();
        $data['ItemPortions'] = $this->rest->get_item_portion();
        $langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as ItemNm";
        $Descname = "ItmDesc$langId as ItmDesc";
        $Ingeredients = "Ingeredients$langId as Ingeredients";
        $data['detail'] = $this->db2->select("*, $lname, $Descname, $Ingeredients")->get_where('MenuItem', array('ItemId' => $ItemId))->row_array();
        $data['itmRates'] = $this->db2->select("*")->get_where('MenuItemRates', array('EID' => $EID,'ItemId' => $ItemId))->result_array();

        $this->load->view('rest/edit_item', $data);
    }

    public function print($billId){

        $data['billId'] = $billId;

        $EID = authuser()->EID;

        $detail = $this->db2->select('CustId,CustNo,CellNo')->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))->row_array();
        $CustId = $detail['CustId'];

        $data['CustNo'] = $detail['CustNo'];

        $dbname = $this->session->userdata('my_db');

        $flag = 'rest';
        $res = getBillData($dbname, $EID, $billId, $CustId, $flag);
        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];
            
            $data['hotelName'] = $billData[0]['Name'];
            $data['TableNo'] = $billData[0]['TableNo'];
            $data['MergeNo'] = $billData[0]['MergeNo'];
            $data['Fullname'] = getName($billData[0]['CustId']);
            $data['CellNo'] = $billData[0]['CellNo'];
            $data['phone'] = $billData[0]['PhoneNos'];
            $data['gstno'] = $billData[0]['GSTno'];
            $data['fssaino'] = $billData[0]['FSSAINo'];
            $data['cinno'] = $billData[0]['CINNo'];
            $data['billno'] = $billData[0]['BillNo'];
            $data['dateOfBill'] = date('d-M-Y @ H:i', strtotime($billData[0]['BillDt']));
            $data['address'] = $billData[0]['Addr'];
            $data['pincode'] = $billData[0]['Pincode'];
            $data['city'] = $billData[0]['City'];
            $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
            $data['bservecharge'] = $billData[0]['bservecharge'];
            $data['SerChargeAmt'] = $billData[0]['SerChargeAmt'];

            $data['tipamt'] = $billData[0]['Tip'];
            $data['splitTyp'] = $billData[0]['splitTyp'];
            $Stat = $billData[0]['Stat'];
            $total = 0;
            $sgstamt=0;
            $cgstamt=0;
            $grandTotal = $sgstamt + $cgstamt + $data['bservecharge'] + $data['tipamt'];
            $data['thankuline'] = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

            $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'] + $billData[0]['custDiscAmt'];
            $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
            $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

            $data['billData'] = $res['billData'];

        }
        $this->load->view('print', $data);
    }

    public function kot_print($MCNo, $mergeNo, $FKOTNo, $KOTNo){
        $data['kotList'] = $this->rest->getKotList($MCNo, $mergeNo, $FKOTNo, $KOTNo);

        $langId = $this->session->userdata('site_lang');

        $group_arr = [];
        foreach ($data['kotList'] as &$key ) {
            $kot = $key['KitCd'];
            if($langId != $key['langId']){
                $text = $key['CustRmks'];
                $currentLng = lngShortName($key['langId']);
                $targetLng  = lngShortName($langId);
                $key['CustRmks'] = translateText($text, $currentLng, $targetLng);
            }

            if(!isset($group_arr[$kot])){
                $group_arr[$kot] = [];
            }
            array_push($group_arr[$kot], $key);
        }

        $data['kotList'] = $group_arr;

        $data['title'] = $this->lang->line('kot');
        $this->load->view('rest/kots_print', $data);
    }

    public function print_pdf(){
        // https://stackoverflow.com/questions/37831516/dompdf-with-codeigniter
        $this->load->library('pdf');
         $billId =17;
        $data['billId'] = $billId;

        $EID = authuser()->EID;

        $detail = $this->db2->select('CustId,CustNo,CellNo')->get_where('Billing', array('BillId' => $billId, 'EID' => $EID))->row_array();
        $CustId = $detail['CustId'];

        $data['CustNo'] = $detail['CustNo'];
        $data['CellNo'] = $detail['CellNo'];

        $dbname = $this->session->userdata('my_db');

        $res = getBillData($dbname, $EID, $billId, $CustId);
        if(!empty($res['billData'])){

            $billData = $res['billData'];
            $data['ra'] = $res['ra'];
            $data['taxDataArray'] = $res['taxDataArray'];

            $data['hotelName'] = $billData[0]['Name'];
            $data['Fullname'] = $billData[0]['FName'].' '.$billData[0]['LName'];
            $data['phone'] = $billData[0]['PhoneNos'];
            $data['gstno'] = $billData[0]['GSTno'];
            $data['fssaino'] = $billData[0]['FSSAINo'];
            $data['cinno'] = $billData[0]['CINNo'];
            $data['billno'] = $billData[0]['BillNo'];
            $data['dateOfBill'] = date('d-m-Y @ H:i', strtotime($billData[0]['BillDt']));
            $data['address'] = $billData[0]['Addr'];
            $data['pincode'] = $billData[0]['Pincode'];
            $data['city'] = $billData[0]['City'];
            $data['servicecharge'] = isset($billData[0]['ServChrg'])?$billData[0]['ServChrg']:"";
            $data['bservecharge'] = $billData[0]['bservecharge'];
            $data['SerChargeAmt'] = $billData[0]['SerChargeAmt'];

            $data['tipamt'] = $billData[0]['Tip'];
            $Stat = $billData[0]['Stat'];
            $total = 0;
            $sgstamt=0;
            $cgstamt=0;
            $grandTotal = $sgstamt + $cgstamt + $data['bservecharge'] + $data['tipamt'];
            $data['thankuline'] = isset($billData[0]['Tagline'])?$billData[0]['Tagline']:"";

            $data['total_discount_amount'] = $billData[0]['TotItemDisc'] + $billData[0]['BillDiscAmt'];
            $data['total_packing_charge_amount'] = $billData[0]['TotPckCharge'];
            $data['total_delivery_charge_amount'] = $billData[0]['DelCharge'];

            $data['billData'] = $res['billData'];
        }
        $this->pdf->load_view('print', $data);
    }

    public function bill_settle(){
        $status = 'error';
        $response = $this->lang->line('notSettled');
        if($this->input->method(true)=='POST'){
            extract($_POST);
            
            autoSettlePayment($billId, $MergeNo, $CNo);

            $status = 'success';
            $response = $this->lang->line('billingSettled');
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function kds(){
        $this->check_access();
        $EID = authuser()->EID;
        $data['title'] = $this->lang->line('kitchenDisplaySystem');
        $minutes = 0;
        $kitcd = 0;
        if($this->input->method(true)=='POST'){
            $minutes = $_POST['minutes'];
            $minutes = unicodeToEnglish($minutes);
            $kitcd = $_POST['kitchen'];
        }
        $data['minutes'] = $minutes;
        $data['kitcd'] = $kitcd;
        $data['kds'] = $this->rest->getPendingKOTLIST($minutes, $kitcd);

        $data['kitchen'] = $this->rest->getKitchenList();
        $this->load->view('rest/kds', $data);
    }

    public function updateKotStat(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
           
            extract($_POST);
            $EID = authuser()->EID;
            $today = date('Y-m-d H:i:s');
            $this->db2->query("UPDATE Kitchen set KStat = 5, DelTime = '$today' where EID = $EID and OrdNo in ($ordNo) ");
            $status = 'success';
            $response = 'KOT is closed.';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function kitchen_planner(){
        $this->check_access();
        $EID = authuser()->EID;
        $data['title'] = $this->lang->line('kitchenPlanner');
        $data['kplanner'] = array();
        $kitcd = 0;
        if($this->input->method(true)=='POST'){
            $kitcd = $_POST['kitchen'];
            $data['kplanner'] = $this->rest->getPendingItemLIST($kitcd);
        }
        
        $data['kitcd'] = $kitcd;
        $data['kitchen'] = $this->rest->getKitchenList();
        
        $this->load->view('rest/kitchen_plan', $data);
    }

    public function checkCustDiscount(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $custId = $_POST['custId'];

            $data = $this->db2->select('uId, FName,CustId, Disc, visit')
                            ->get_where('Users', array('CustId' => $custId))
                            ->row_array();
            if(!empty($data)){
                $response = $data;
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function thirdParty(){
        $this->check_access();
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        $data['bills'] = $this->rest->getTAPendingBills();
        $data['cashier'] = $this->rest->getCasherList();
        $data['payModes'] = $this->rest->getPaymentModes();
        $data['country']    = $this->rest->getCountries();
        $data['CountryCd']    = $this->session->userdata('CountryCd');
        $data['title'] = $this->lang->line('thirdParty');
        $data['OType'] = 101;

        $currentTable = $this->rest->getTablesDetails($EID, $data['OType']);
        $data['currentTableOffer'] = $currentTable['offerValid'];

        $this->load->view('rest/offline_order', $data);
    }

    public function takeAway(){
        $this->check_access();
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['OType'] = 105;
        $currentTable = $this->rest->getTablesDetails($EID, $data['OType']);
        $data['currentTableOffer'] = $currentTable['offerValid'];

        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        $data['bills'] = $this->rest->getTAPendingBills();
        $data['payModes'] = $this->rest->getPaymentModes();
        $data['cashier'] = $this->rest->getCasherList();
        $data['country']    = $this->rest->getCountries();
        $data['CountryCd']    = $this->session->userdata('CountryCd');
        $data['title'] = $this->lang->line('takeAway');
        $this->load->view('rest/offline_order', $data);
    }

    public function Deliver(){
        $this->check_access();
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        $data['bills'] = $this->rest->getTAPendingBills();
        $data['cashier'] = $this->rest->getCasherList();
        $data['payModes'] = $this->rest->getPaymentModes();
        $data['country']    = $this->rest->getCountries();
        $data['CountryCd']    = $this->session->userdata('CountryCd');
        $data['title'] = $this->lang->line('deliver');
        $data['OType'] = 110;

        $currentTable = $this->rest->getTablesDetails($EID, $data['OType']);
        $data['currentTableOffer'] = $currentTable['offerValid'];
        $this->load->view('rest/offline_order', $data);
    }

    public function mcdonald(){
        $this->check_access();
        $EID = authuser()->EID;
        $data['EID'] = $EID;
        $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
        $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
        $data['bills'] = $this->rest->getTAPendingBills();
        $data['cashier'] = $this->rest->getCasherList();
        $data['payModes'] = $this->rest->getPaymentType();
        $data['country']    = $this->rest->getCountries();
        $data['CountryCd']    = $this->session->userdata('CountryCd');
        $data['title'] = 'McDonalds';
        $data['OType'] = 100;

        $currentTable = $this->rest->getTablesDetails($EID, $data['OType']);
        $data['currentTableOffer'] = $currentTable['offerValid'];

        $this->load->view('rest/offline_order', $data);
    }

    public function sitIn(){
        $this->check_access();
        $EType = $this->session->userdata('EType');
        $data['title'] = $this->lang->line('sitIn');
        if($EType == 5){
            $EID = authuser()->EID;
            $data['EID'] = $EID;
            $data['thirdOrdersData'] = $this->rest->getThirdOrderData();
            $data['tablesAlloted'] = $this->rest->getTablesAllotedData($EID);
            $data['cashier'] = $this->rest->getCasherList();
            $data['country']    = $this->rest->getCountries();
            $data['CountryCd']    = $this->session->userdata('CountryCd');
            $data['OType'] = 8;
            $data['currentTableOffer'] = 1;

            $this->load->view('rest/offline_order', $data);
        }else{
            $this->load->view('page403', $data);
        }
    }

    public function get_portions(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = "success";
            $response = $this->rest->getMenuItemRates($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_item_offer(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = "success";
            $response = $this->rest->getItemOfferList($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_customize_items(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            $response = $this->rest->get_customize_lists($_POST);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function insert_temp_kitchen(){
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $thirdParty = 0;
            $thirdPartyRef = 0;
            $orderType = $_POST['orderType'];
            if($orderType == 101){
                $thirdParty = !empty($_POST['thirdParty'])?$_POST['thirdParty']:0;
                $thirdPartyRef = !empty($_POST['thirdParty'])?$_POST['thirdParty']:0;
            }
            
            // $take_away = !empty($_POST['take_away'])?$_POST['take_away']:0;
            $prep_time = !empty($_POST['PrepTime'])?$_POST['PrepTime']:0;
                            
            $customerAddress = !empty($_POST['customerAddress'])?$_POST['customerAddress']:'';

            $CustItem = !empty($_POST['CustItem'])?$_POST['CustItem']:0;
            $CustItemDesc = !empty($_POST['CustItemDesc'])?$_POST['CustItemDesc']:'Std';
            $Qty = !empty($_POST['Qty'])?$_POST['Qty']:1;
            $SchCd = $_POST['SchCd'];
            $SDetCd = $_POST['SDetCd'];

            $TableNo = $_POST['TableNo'];

            $kitchenObj['CNo'] = 0;
            $kitchenObj['MCNo'] = 0;
            $kitchenObj['CustId'] = 0;
            $kitchenObj['EID'] = $EID;
            $kitchenObj['ChainId'] = 0;
            $kitchenObj['ItemId'] = $_POST['ItemId'];
            $kitchenObj['itemName'] = $_POST['itemName'];
            $kitchenObj['ItmRate'] = $_POST['ItmRate'];
            $kitchenObj['tmpItmRate'] = $_POST['ItmRate'];
            $kitchenObj['OrigRate'] = $_POST['OrigRate'];
            $kitchenObj['tmpOrigRate'] = $_POST['OrigRate'];
            $kitchenObj['KitCd'] = $_POST['KitCd'];
            $kitchenObj['ItemTyp'] = $_POST['ItemTyp'];
            $kitchenObj['OType'] = $orderType;
            // $kitchenObj['PckCharge'] = ($orderType == 105)?$_POST['PckCharge']:0;
            $kitchenObj['PckCharge'] = $_POST['PckCharge'];
            $kitchenObj['Itm_Portion'] = $_POST['Itm_Portion'];
            $kitchenObj['TaxType'] = $_POST['TaxType'];
            $kitchenObj['MCatgId'] = $_POST['MCatgId'];
            $kitchenObj['PrepTime'] = $prep_time;
            $kitchenObj['DCd'] = $_POST['DCd'];
            $kitchenObj['CID'] = $_POST['CID'];
            $kitchenObj['TableNo'] = $TableNo;
            $kitchenObj['MergeNo'] = $TableNo;
            if(!empty($_POST['CellNo'])){
                $kitchenObj['CellNo'] = $_POST['CellNo'];
            }else{
                $kitchenObj['CellNo'] = '';
            }
                
            $kitchenObj['Qty'] = $Qty;
            $kitchenObj['FID'] = $_POST['FID'];
            $kitchenObj['SchCd'] = $SchCd;
            $kitchenObj['SDetCd'] = $SDetCd;

            $kitchenObj['CustItem'] = $CustItem;
            $kitchenObj['CustItemDesc'] = $CustItemDesc;
            $kitchenObj['custAddr'] = $customerAddress;
            $kitchenObj['ItemSale'] = $_POST['ItemSale'];

            $kitchenObj['FKOTNo'] = 0;         
            $kitchenObj['KOTNo'] = 0;
            $kitchenObj['UKOTNo'] = 0;       
            $kitchenObj['Stat'] = 0;
            // edt
            $date = date("Y-m-d H:i:s");
            $date = strtotime($date);
            $time = $prep_time;
            $date = strtotime("+" . $time . " minute", $date);
            $edtTime = date('H:i', $date);
            // edt
            $kitchenObj['EDT'] = $edtTime;
            $kitchenObj['LoginCd'] = authuser()->RUserId;
            
            if(!empty($SchCd)){
                $OrdNo = $_POST['OrdNo'];
                $Offers = $this->getSchemeOfferList($SchCd, $SDetCd);
                $tmpKit = $this->rest->getTempKitchenByOrdno($OrdNo);
                
                $origRates = $tmpKit['OrigRate'];
                $offerOrigRate = $origRates;
                if(!empty($Offers)){
                    $upData['childOrdNo'] = $OrdNo;
                    if($OrdNo > 0){
                        if($Offers['Qty'] > 1){
                            if($tmpKit['ItemTyp'] < 1){
                                $upData['Qty'] = $Offers['Qty'];
                            }
                        }
                        if($Offers['IPCd'] >= 1){
                            $mRates = $this->rest->getItemRatesByItemIdIPCd($tmpKit['ItemId'],$Offers['IPCd']);
                            if(!empty($mRates)){
                                $origRates = $mRates['OrigRate'];
                                $offerOrigRate = $origRates;
                                $upData['Itm_Portion'] = $Offers['IPCd'];

                                if($tmpKit['CustItem'] == 0){
                                    $upData['OrigRate'] = $mRates['OrigRate'];
                                    $upData['ItmRate'] = $mRates['OrigRate'];
                                    $upData['tmpItmRate'] = $mRates['OrigRate'];
                                    $upData['tmpOrigRate'] = $mRates['OrigRate'];
                                }
                            }else{
                                $status = 'error';
                                $response = 'Offer details do not exist for this item!!';
                                header('Content-Type: application/json');
                                echo json_encode(array(
                                    'status' => $status,
                                    'response' => $response
                                  ));
                                 die;
                            }
                        }

                        $upData['SchCd'] = $SchCd;
                        $upData['SDetCd'] = $SDetCd;
                        
                        updateRecord('tempKitchen', $upData, array('OrdNo' => $OrdNo, 'EID' => $EID));
                    }
                    // for offer 
                    $Disc_ItemId = $Offers['Disc_ItemId'];
                    $Disc_IPCd = $Offers['Disc_IPCd'];
                    
                    $offerRate = (int)$origRates - ((int)$origRates * (int)$Offers['Disc_pcent'] / 100);
                    if($Disc_ItemId > 0){
                        if($Disc_ItemId != $Offers['ItemId'] || $Disc_IPCd != $Offers['IPCd']){
                            
                            $offerRates = $this->db2->query("select mir.Itm_Portion, mir.OrigRate, mc.TaxType, mi.PckCharge, mi.ItemTyp, mi.KitCd, mc.MCatgId from MenuItemRates as mir, MenuItem mi, MenuCatg mc where mi.EID = mir.EID and mc.EID=mir.EID and mi.ItemId=mir.ItemId and mc.MCatgId = mi.MCatgId and mir.EID = $EID and mir.ItemId = $Disc_ItemId and mir.Itm_Portion = $Disc_IPCd and mir.SecId = (select SecId from Eat_tables where TableNo = $TableNo and EID = $EID)")->row_array();
                            if(!empty($offerRates)){
                                $offerRate = $offerRates['OrigRate'] -  ($offerRates['OrigRate'] * $Offers['Disc_pcent'] / 100);
                                $offerOrigRate = $offerRates['OrigRate'];

                                $kitchenObj['KitCd']        = $offerRates['KitCd'];
                                $kitchenObj['ItemTyp']      = $offerRates['ItemTyp'];
                                $kitchenObj['PckCharge']    = $offerRates['PckCharge'];
                                $kitchenObj['Itm_Portion']  = $offerRates['Itm_Portion'];
                                $kitchenObj['TaxType']      = $offerRates['TaxType'];
                                $kitchenObj['MCatgId']      = $offerRates['MCatgId'];
                            }
                        }

                        if($Offers['SchTyp'] > 1) {
                            if($Offers['DiscItemPcent'] > 0){
                                $offerRate = (int)$origRates - ((int)$origRates * (int)$Offers['DiscItemPcent'] / 100);

                                if($Disc_ItemId != $Offers['ItemId'] || $Disc_IPCd != $Offers['IPCd']){
                                    
                                    $offerRate = $offerRates['OrigRate'] -  ($offerRates['OrigRate'] * $Offers['DiscItemPcent'] / 100);
                                    $offerOrigRate = $offerRates['OrigRate'];
                                }   

                                if($Offers['DiscMaxAmt'] > 0){
                                    $perc_Amt =  ((int)$origRates * (int)$Offers['DiscItemPcent'] / 100);
                                    $perc_Amt = round($perc_Amt) * $Offers['Qty'];

                                    if($perc_Amt <= $Offers['DiscMaxAmt']){
                                        $offerRate = $offerOrigRate - $perc_Amt;
                                    }else{
                                        $offerRate = $offerOrigRate - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                                    }
                                }

                            }else if($Offers['DiscMaxAmt'] > 0){
                                $offerRate = $offerOrigRate - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                            }
                        }

                        $kitchenObj['ItemId'] = $Disc_ItemId;
                        $kitchenObj['Itm_Portion'] = $Offers['Disc_IPCd'];
                        
                        $kitchenObj['Qty'] = $Offers['Disc_Qty'];
                        $kitchenObj['ItmRate'] = $offerRate;
                        $kitchenObj['tmpItmRate'] = $offerRate;
                        $kitchenObj['OrigRate'] = $offerOrigRate;
                        $kitchenObj['tmpOrigRate'] = $offerOrigRate;
                        $kitchenObj['SchCd'] = $SchCd;
                        $kitchenObj['SDetCd'] = $SDetCd;
                        $kitchenObj['childOrdNo'] = $OrdNo;
                        insertRecord('tempKitchen', $kitchenObj);
                    }else if($Offers['Disc_ItemTyp'] > 0 && $_POST['customizeItemId'] > 0 && $Offers['Disc_IPCd'] > 0){
                        $Disc_IPCd = $Offers['Disc_IPCd'];
                        $customizeItemId = $_POST['customizeItemId'];

                        $langId = $this->session->userdata('site_lang');
                        $lname = "mi.Name$langId as LngName";

                        $offerRates = $this->db2->query("select mir.Itm_Portion, mir.OrigRate, mc.TaxType, mi.PckCharge, mi.ItemTyp, mi.KitCd, $lname, mc.MCatgId from MenuItemRates as mir, MenuItem mi, MenuCatg mc where mi.EID = mir.EID and mc.EID=mir.EID and mi.ItemId=mir.ItemId and mc.MCatgId = mi.MCatgId and mir.EID = $EID and mir.ItemId = $customizeItemId and mir.Itm_Portion = $Disc_IPCd and mir.SecId = (select SecId from Eat_tables where TableNo = $TableNo and EID = $EID)")->row_array();

                        if(!empty($offerRates)){
                            $offerRate = $offerRates['OrigRate'] -  ($offerRates['OrigRate'] * $Offers['Disc_pcent'] / 100);
                            $offerOrigRate = $offerRates['OrigRate'];

                            $kitchenObj['KitCd']        = $offerRates['KitCd'];
                            $kitchenObj['ItemTyp']      = $offerRates['ItemTyp'];
                            $kitchenObj['Itm_Portion']  = $offerRates['Itm_Portion'];
                            $kitchenObj['TaxType']      = $offerRates['TaxType'];
                            $kitchenObj['itemName']     = $offerRates['LngName'];
                            $kitchenObj['ItemId']       = $customizeItemId;
                        }       

                        if($Offers['DiscItemPcent'] > 0){
                            $offerRate = (int)$offerOrigRate - ((int)$offerOrigRate * (int)$Offers['DiscItemPcent'] / 100);

                            if($Offers['DiscMaxAmt'] > 0){
                                $perc_Amt =  ((int)$offerOrigRate * (int)$Offers['DiscItemPcent'] / 100);
                                $perc_Amt = round($perc_Amt) * $Offers['Qty'];

                                if($perc_Amt <= $Offers['DiscMaxAmt']){
                                    $offerRate = $offerOrigRate - $perc_Amt;
                                }else{
                                    $offerRate = $offerOrigRate - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                                }
                            }

                        }else if($Offers['DiscMaxAmt'] > 0){
                            $offerRate = $offerOrigRate - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                        }

                        $kitchenObj['Qty'] = $Offers['Disc_Qty'];
                        $kitchenObj['ItmRate'] = $offerRate;
                        $kitchenObj['OrigRate'] = $offerOrigRate;
                        $kitchenObj['tmpItmRate'] = $kitchenObj['ItmRate'];
                        $kitchenObj['tmpOrigRate'] = $kitchenObj['OrigRate'];
                        $kitchenObj['SchCd'] = $SchCd;
                        $kitchenObj['SDetCd'] = $SDetCd;
                        $kitchenObj['childOrdNo'] = $OrdNo;
                        insertRecord('tempKitchen', $kitchenObj);
                    }else{

                        if($Offers['Disc_ItemTyp'] > 0){
                            // Disc_IPCd
                        }else if($Offers['DiscItemPcent'] > 0){
                            $perc_Amt = ($origRates * $Offers['DiscItemPcent'] / 100);
                            $perc_Amt = round($perc_Amt) * $Offers['Qty'];
                            // $itmrate = $origRates - $perc_Amt;
                            if($Offers['DiscMaxAmt'] > 0){
                                if($perc_Amt <= $Offers['DiscMaxAmt']){
                                    $itmrate = $origRates - $perc_Amt;
                                }else{
                                    $itmrate = $origRates - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                                }
                            }else{
                                $itmrate = $origRates - $perc_Amt;
                            }
                        }else if($Offers['DiscMaxAmt'] > 0){
                            if($Offers['DiscMaxAmt'] > 0){
                                $itmrate = $origRates - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                            }
                        }
                        updateRecord('tempKitchen', array('ItmRate' => $itmrate, 'tmpItmRate' => $itmrate), array('OrdNo' => $OrdNo, 'EID' => $EID));
                    }

                    // start custom item
                    if($tmpKit['ItemTyp'] > 0){
                        if($Offers['Qty'] > 1){
                            $tempKitDT = $this->rest->getTempKitchenByOrdno($OrdNo);
                            unset($tempKitDT['OrdNo']);
                            for ($i=0; $i < $Offers['Qty']-1; $i++) { 
                                $tempKitDT['childOrdNo'] = $OrdNo;
                                insertRecord('tempKitchen', $tempKitDT);
                            }
                        }
                    }
                    // end custom item
                }
            }else{
                insertRecord('tempKitchen', $kitchenObj);
            }

            $status = "success";
            $response = 'Data Inserted';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function get_temp_kitchen_data(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = "success";
            $response = $this->rest->getTempKitchenData($_POST['TableNo']);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function delete_temp_kitchen(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){

            $kd = $this->db2->select('childOrdNo')->get_where('tempKitchen', array('OrdNo' => $_POST['OrdNo'], 'EID' => $EID))->row_array();
            
            if(!empty($kd)){
                if($kd['childOrdNo']> 0){
                    deleteRecord('tempKitchen', array('EID' => $EID, 'childOrdNo' => $kd['childOrdNo']));
                }
            }

            deleteRecord('tempKitchen', array('ItemId' => $_POST['ItemId'], 'OrdNo' => $_POST['OrdNo'], 'EID' => $EID));
            
            $status = "success";
            $response = 'Item has been removed.';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    private function getSchemeOfferList($schcd, $sdetcd){

        $langId = $this->session->userdata('site_lang');
        $scName = "c.SchNm$langId as SchNm";
        $scDesc = "cod.SchDesc$langId as SchDesc";

        return $this->db2->select("$scName, c.SchCd, cod.SDetCd, $scDesc, c.PromoCode, c.SchTyp, c.SchCatg, c.Rank,cod.Disc_ItemId, cod.Qty, cod.Disc_Qty, cod.IPCd, cod.Disc_IPCd, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId, cod.DiscItemPcent, cod.DiscMaxAmt, cod.Disc_ItemTyp")
                        ->join('CustOffers c', 'c.SchCd = cod.SchCd', 'inner')
                        ->get_where('CustOffersDet cod', array('c.SchCd' => $schcd,
                         'cod.SDetCd' => $sdetcd,
                         'c.Stat' => 0,
                         'cod.Stat' => 0,
                         'c.EID' => authuser()->EID))
                        ->row_array();
    }
    
    public function get_custom_items(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $customDetails = $this->rest->getCustomItemsList($_POST);

            if(!empty($customDetails)){
                $status = "success";

                $grpType = $customDetails[0]['GrpType'];
                $itemGroupCd = $customDetails[0]['ItemGrpCd'];
                $itemGroup = $customDetails[0]['ItemGrpName'];
                $itemReq = $customDetails[0]['Reqd'];

                $returnData = [];

                $temp['GrpType'] = $grpType;
                $temp['ItemGrpCd'] = $itemGroupCd;
                $temp['ItemGrpName'] = $itemGroup;
                $temp['Reqd'] = $itemReq;
                $temp['Details'] = [];
                // unique id logic
                foreach ($customDetails as $key => $value) {
                    if ($value['ItemGrpName'] == $itemGroup) {
                        $temp['Details'][] = [
                            "Name" => $value['Name'],
                            "Rate" => $value['Rate'],
                            "ItemOptCd" => $value['ItemOptCd'],
                        ];
                    } else {
                        $returnData[] = $temp;
                        $grpType = $value['GrpType'];
                        $itemGroupCd = $value['ItemGrpCd'];
                        $itemGroup = $value['ItemGrpName'];
                        $itemReq = $value['Reqd'];
                        $temp['GrpType'] = $grpType;
                        $temp['ItemGrpCd'] = $itemGroupCd;
                        $temp['ItemGrpName'] = $itemGroup;
                        $temp['Reqd'] = $itemReq;
                        $temp['Details'] = [];
                        $temp['Details'][] = [
                            "Name" => $value['Name'],
                            "Rate" => $value['Rate'],
                            "ItemOptCd" => $value['ItemOptCd'],
                        ];
                    }
                }

                $returnData[] = $temp;
                
                $response =  $returnData;
            }else{
              $response =  'No customization available!!';  
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

     public function update_customItem_onTempKitchen(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $updateData['CustItem'] = 1;
            $updateData['CustItemDesc'] = $_POST['CustItemDesc'];
            $updateData['ItmRate'] = $_POST['ItemRates'];
            $updateData['OrigRate'] = $_POST['OrigRates'];

            updateRecord('tempKitchen', $updateData, array('EID' => authuser()->EID, 'OrdNo' => $_POST['OrdNo'], 'ItemId' => $_POST['ItemId']));
            $status = 'success';
            $response = "Item rate updated";

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }
    // csv file upload
    public function csv_file_upload(){
        $this->check_access();
        $EID = $this->session->userdata('EID');
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $folderPath = 'uploads/e'.$EID.'/csv';
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, true);
            }
            // remove all files inside this folder uploads/qrcode/
            $filesPath = glob($folderPath.'/*'); // get all file names
            foreach($filesPath as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); // delete file
              }
            }  
            // end remove all files inside folder

            $flag = 0;

            switch ($_POST['type']) {
                case 'eatary':

                    if(isset($_FILES['eatary_file']['name']) && !empty($_FILES['eatary_file']['name'])){ 
                        $files = $_FILES['eatary_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['eatary_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['eatary_file']['name']= $files['name'];
                        $_FILES['eatary_file']['type']= $files['type'];
                        $_FILES['eatary_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['eatary_file']['error']= $files['error'];
                        $_FILES['eatary_file']['size']= $files['size'];
                        $file = $files['name'];

                        $eatary_id = 0;
                        if($flag == 0){
                            $res = do_upload('eatary_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    if($csv_data[0] !='ChainId'){

                                        $eatObj['ChainId'] = $csv_data[0];
                                        $eatObj['ONo'] = $csv_data[1];
                                        $eatObj['Stall'] = $csv_data[2];
                                        $eatObj['Name'] = $csv_data[3];
                                        $eatObj['CatgID'] = $csv_data[4];
                                        $eatObj['CountryCd'] = $csv_data[5];
                                        $eatObj['CityCd'] = $csv_data[6];
                                        $eatObj['Addr'] = $csv_data[7];
                                        $eatObj['Suburb'] = $csv_data[8];
                                        $eatObj['EWNS'] = $csv_data[9];
                                        $eatObj['City'] = $csv_data[10];
                                        $eatObj['Pincode'] = $csv_data[11];
                                        $eatObj['Tagline'] = $csv_data[12];
                                        $eatObj['Remarks'] = $csv_data[13];
                                        $eatObj['GSTNo'] = $csv_data[14];
                                        $eatObj['CINNo'] = $csv_data[15];
                                        $eatObj['FSSAINo'] = $csv_data[16];
                                        $eatObj['PhoneNos'] = $csv_data[17];
                                        $eatObj['Email'] = $csv_data[18];
                                        $eatObj['Website'] = $csv_data[19];
                                        $eatObj['ContactNos'] = $csv_data[20];
                                        $eatObj['ContactAddr'] = $csv_data[21];
                                        $eatObj['BillerName'] = $csv_data[22];
                                        $eatObj['BillerGSTNo'] = $csv_data[23];
                                        $eatObj['BTyp'] = $csv_data[24];
                                        $eatObj['VFM'] = $csv_data[25];
                                        $eatObj['TaxInBill'] = $csv_data[26];
                                        $eatObj['QRLink'] = $csv_data[27];
                                        $eatObj['LstModDt'] = date('Y-m-d H:i:s');
                                        $eatObj['Stat'] = 0;
                                        $eatObj['LoginCd'] = authuser()->RUserId;
                                        $eatary_id = insertRecord('Eatary', $eatObj);
                                    }
                                }
                                fclose($open);
                            }
                        }

                        if(!empty($eatary_id)){
                            $status = 'success';
                            $response = 'Data Inserted';
                            $this->session->set_flashdata('success','Data Inserted.');
                        }
                      }

                break;

                case 'cuisine':
                    if(isset($_FILES['cuisine_file']['name']) && !empty($_FILES['cuisine_file']['name'])){ 
                        $files = $_FILES['cuisine_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['cuisine_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['cuisine_file']['name']= $files['name'];
                        $_FILES['cuisine_file']['type']= $files['type'];
                        $_FILES['cuisine_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['cuisine_file']['error']= $files['error'];
                        $_FILES['cuisine_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('cuisine_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $cuisineData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $temp['EID'] = $this->checkEatary($csv_data[0]);

                                        if($temp['EID'] < 1){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Name1'] =  $csv_data[1];

                                        if(empty($temp['Name1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['CID'] =  $this->checkCuisine($csv_data[2]);
                                        if($temp['CID'] == 0){
                                          $response = $csv_data[2]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['KitCd'] =  $this->checkKitchen($csv_data[3], $temp['EID']);
                                        if($temp['KitCd'] == 0){
                                          $response = $csv_data[3]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Rank'] = $csv_data[4];
                                        $temp['Stat'] = 0;
                                        $temp['LoginId'] = authuser()->RUserId;

                                        if($checker == 0){
                                            $cuisineData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }
                                        $cuisineData[] = $temp;
                                    }
                                }

                                if(!empty($cuisineData)){
                                    $this->db2->insert_batch('EatCuisine', $cuisineData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'menucatg':
                    if(isset($_FILES['mcatg_file']['name']) && !empty($_FILES['mcatg_file']['name'])){ 
                        $files = $_FILES['mcatg_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mcatg_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mcatg_file']['name']= $files['name'];
                        $_FILES['mcatg_file']['type']= $files['type'];
                        $_FILES['mcatg_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['mcatg_file']['error']= $files['error'];
                        $_FILES['mcatg_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mcatg_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                $mcData = [];
                                $mc = [];
                                $count = 1;
                                $checker = 0;
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){
                                            
                                            $mc['EID'] = $this->checkEatary($csv_data[0]);

                                            $mc['CID'] = $this->checkCuisine($csv_data[1]);
                                            $mc['Name1'] = $csv_data[2];
                                            $mc['CTyp'] = $this->checkCTyp($csv_data[3]);
                                            $mc['Rank'] = $csv_data[4];
                                            $mc['KitCd'] = $this->checkKitchen($csv_data[5], $mc['EID']);
                                            $checker = 1;
                                            
                                            if($mc['EID'] == 0){
                                              $response = $csv_data[0]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if($mc['CID'] == 0){
                                              $response = $csv_data[1]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if(empty($mc['Name1'])){
                                              $response = $csv_data[2]. " Field Required in row no: $count";
                                              $checker = 0;
                                            }

                                            if($mc['KitCd'] == 0){
                                              $response = $csv_data[5]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if($checker == 0){
                                                $mcData = [];
                                                header('Content-Type: application/json');
                                                echo json_encode(array(
                                                    'status' => $status,
                                                    'response' => $response
                                                  ));
                                                 die; 
                                            }

                                            $mcData[] = $mc;
                                    }
                                    $count++;
                                }

                                if(!empty($mcData)){
                                    $this->db2->insert_batch('MenuCatg', $mcData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                                
                                fclose($open);
                            }
                        }
                      }
                break;

                case 'itemType':
                    if(isset($_FILES['itemtype_file']['name']) && !empty($_FILES['itemtype_file']['name'])){ 
                        $files = $_FILES['itemtype_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['itemtype_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['itemtype_file']['name']= $files['name'];
                        $_FILES['itemtype_file']['type']= $files['type'];
                        $_FILES['itemtype_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['itemtype_file']['error']= $files['error'];
                        $_FILES['itemtype_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('itemtype_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                $typeData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    if($csv_data[0] !='RestName'){
                                            
                                            $temp['EID'] = $this->checkEatary($csv_data[0]);

                                            $temp['Name1'] = $csv_data[1];
                                            $checker = 1;
                                            
                                            if($temp['EID'] < 1){
                                              $response = $csv_data[0]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            if(empty($temp['Name1'])){
                                              $response = $csv_data[1]. " Field Required in row no: $count";
                                              $checker = 0;
                                            }

                                            $temp['Stat'] = 0;

                                            if($checker == 0){
                                                $typeData = [];
                                                header('Content-Type: application/json');
                                                echo json_encode(array(
                                                    'status' => $status,
                                                    'response' => $response
                                                  ));
                                                 die; 
                                            }

                                            $typeData[] = $temp;
                                    }
                                    $count++;
                                }

                                if(!empty($typeData)){
                                    $this->db2->insert_batch('ItemTypes', $typeData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                                
                                fclose($open);
                            }
                        }
                      }
                break;

                case 'menuitem':
                    if(isset($_FILES['mitem_file']['name']) && !empty($_FILES['mitem_file']['name'])){ 
                        $files = $_FILES['mitem_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mitem_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mitem_file']['name']= $files['name'];
                        $_FILES['mitem_file']['type']= $files['type'];
                        $_FILES['mitem_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['mitem_file']['error']= $files['error'];
                        $_FILES['mitem_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mitem_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $mItemData = [];
                                $mItem = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    if($csv_data[0] !='RestName'){
                                    
                                        $checker = 1;

                                        $mItem['EID'] = $this->checkEatary($csv_data[0]);

                                        if($mItem['EID'] < 1){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }
                                            $mItem['CID'] = $this->checkCuisine($csv_data[1]);

                                            if($mItem['CID'] == 0){
                                              $response = $csv_data[1]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['MCatgId'] = $this->checkMenuCatg($mItem['EID'], $csv_data[2]);

                                            if($mItem['MCatgId'] == 0){
                                              $response = $csv_data[2]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['CTyp'] = $this->checkCTyp($csv_data[3]);

                                            $mItem['FID'] = $this->checkFoodType($csv_data[4]);

                                            if($mItem['FID'] == 0){
                                              $response = $csv_data[4]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['Name1'] = $csv_data[5];
                                            $mItem['IMcCd'] = $csv_data[6];
                                            $mItem['PckCharge'] = $csv_data[7];
                                            $mItem['ItemTyp'] = $this->checkItemType($csv_data[8], $mItem['EID']);
                                            if($mItem['ItemTyp'] == 0){
                                              $response = $csv_data[8]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['ItemAttrib'] = $this->checkType($csv_data[9], 1);

                                            if($mItem['ItemAttrib'] == 0){
                                              $response = $csv_data[9]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['ItemSale'] = $this->checkType($csv_data[10], 3);

                                            if($mItem['ItemSale'] == 0){
                                              $response = $csv_data[10]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }

                                            $mItem['ItemTag'] = $this->checkType($csv_data[11], 2);

                                            if($mItem['ItemTag'] == 0){
                                              $response = $csv_data[11]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['NV'] = $csv_data[12];
                                            $mItem['MaxDisc'] = $csv_data[13];
                                            $mItem['StdDiscount'] = $csv_data[14];
                                            $mItem['DiscRate'] = $csv_data[15];
                                            $mItem['Rank'] = $csv_data[16];
                                            $mItem['ItmDesc1'] = $csv_data[17];
                                            $mItem['Ingeredients1'] = $csv_data[18];
                                            $mItem['MaxQty'] = $csv_data[19];
                                            $mItem['MTyp'] = $csv_data[20];
                                            $mItem['Rmks1'] = $csv_data[21];
                                            $mItem['PrepTime'] = $csv_data[22];
                                            $mItem['FrmTime'] = $csv_data[23];
                                            $mItem['ToTime'] = $csv_data[24];
                                            $mItem['AltFrmTime'] = $csv_data[25];
                                            $mItem['AltToTime'] = $csv_data[26];
                                            $mItem['DayNo'] = getDayNo($csv_data[27]);

                                            if($mItem['DayNo'] == 0){
                                              $response = $csv_data[27]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['KitCd'] = $this->checkKitchen($csv_data[28], $mItem['EID']);

                                            if($mItem['KitCd'] == 0){
                                              $response = $csv_data[28]. " Not Found in row no: $count";
                                              $checker = 0;
                                            }
                                            $mItem['videoLink'] = $csv_data[29];
                                            $mItem['Stat'] = 0;
                                            $mItem['LoginCd'] = authuser()->RUserId;
                                        

                                        if($checker == 0){
                                            $mItemData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $mItemData[] = $mItem;
                                    }
                                    $count++;    
                                }

                                if(!empty($mItemData)){

                                    $this->db2->insert_batch('MenuItem', $mItemData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                             
                                fclose($open);
                            }
                        }
                      }
                break;

                case 'itemrates':
                    if(isset($_FILES['mitem_rates']['name']) && !empty($_FILES['mitem_rates']['name'])){ 
                        $files = $_FILES['mitem_rates'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mitem_rates']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mitem_rates']['name']= $files['name'];
                        $_FILES['mitem_rates']['type']= $files['type'];
                        $_FILES['mitem_rates']['tmp_name']= $files['tmp_name'];
                        $_FILES['mitem_rates']['error']= $files['error'];
                        $_FILES['mitem_rates']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mitem_rates',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                $mRatesData = [];
                                $mc = [];
                                $count = 1;
                                $checker = 0;
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    if($csv_data[0] !='RestName'){
                                        
                                        $checker = 1;
                                        $mc['EID'] = $this->checkEatary($csv_data[0]);

                                        if($mc['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['ItemId'] = $this->getItemId($mc['EID'], $csv_data[1]);

                                        if($mc['ItemId'] == 0){
                                          $response = $csv_data[1]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['SecId'] = $this->checkSection($csv_data[2]);

                                        if($mc['SecId'] == 0){
                                          $response = $csv_data[2]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['Itm_Portion'] = $this->checkPortion($csv_data[3]);

                                        if($mc['Itm_Portion'] == 0){
                                          $response = $csv_data[3]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['ItmRate'] = $csv_data[4];
                                        $mc['ChainId'] = 0;

                                        if($checker == 0){
                                            $mRatesData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $mRatesData[] = $mc;
                                    }
                                    $count++;
                                }

                                if(!empty($mRatesData)){

                                    $this->db2->insert_batch('MenuItemRates', $mRatesData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'itemRecom':
                    if(isset($_FILES['mitem_recos']['name']) && !empty($_FILES['mitem_recos']['name'])){ 
                        $files = $_FILES['mitem_recos'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['mitem_recos']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['mitem_recos']['name']= $files['name'];
                        $_FILES['mitem_recos']['type']= $files['type'];
                        $_FILES['mitem_recos']['tmp_name']= $files['tmp_name'];
                        $_FILES['mitem_recos']['error']= $files['error'];
                        $_FILES['mitem_recos']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('mitem_recos',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $mRatesData = [];
                                $mc = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $mc['EID'] = $this->checkEatary($csv_data[0]);

                                        if($mc['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['ItemId'] = $this->getItemId($mc['EID'], $csv_data[1]);

                                        if($mc['ItemId'] == 0){
                                          $response = $csv_data[1]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['RcItemId'] = $this->getItemId($mc['EID'], $csv_data[2]);

                                        if($mc['RcItemId'] == 0){
                                          $response = $csv_data[2]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $mc['Remarks'] = '-';
                                        $mc['Stat'] = 0;
                                        $mc['LoginCd'] = authuser()->RUserId;

                                        if($checker == 0){
                                            $mRatesData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $mRatesData[] = $mc;
                                    }
                                }

                                if(!empty($mRatesData)){

                                    $this->db2->insert_batch('MenuItem_Recos', $mRatesData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'kitchen':
                    if(isset($_FILES['kitchen_file']['name']) && !empty($_FILES['kitchen_file']['name'])){ 
                        $files = $_FILES['kitchen_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['kitchen_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['kitchen_file']['name']= $files['name'];
                        $_FILES['kitchen_file']['type']= $files['type'];
                        $_FILES['kitchen_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['kitchen_file']['error']= $files['error'];
                        $_FILES['kitchen_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('kitchen_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $kitchenData = [];
                                $kd = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $kd['EID'] = $this->checkEatary($csv_data[0]);

                                        if($kd['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['KitName1'] =  $csv_data[1];

                                        if(empty($kd['KitName1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['PrinterName'] =  $csv_data[2];
                                        $kd['Stat'] = 0;

                                        if($checker == 0){
                                            $kitchenData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $kitchenData[] = $kd;
                                    }
                                }

                                if(!empty($kitchenData)){
                                    $this->db2->insert_batch('Eat_Kit', $kitchenData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'cashier':
                    if(isset($_FILES['cashier_file']['name']) && !empty($_FILES['cashier_file']['name'])){ 
                        $files = $_FILES['cashier_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['cashier_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['cashier_file']['name']= $files['name'];
                        $_FILES['cashier_file']['type']= $files['type'];
                        $_FILES['cashier_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['cashier_file']['error']= $files['error'];
                        $_FILES['cashier_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('cashier_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $cashierData = [];
                                $kd = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $kd['EID'] = $this->checkEatary($csv_data[0]);

                                        if($kd['EID'] < 1){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['Name1'] =  $csv_data[1];

                                        if(empty($kd['Name1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $kd['PrinterName'] =  $csv_data[2];
                                        $kd['Stat'] = 0;

                                        if($checker == 0){
                                            $cashierData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }

                                        $cashierData[] = $kd;
                                    }
                                }

                                if(!empty($cashierData)){
                                    $this->db2->insert_batch('Eat_Casher', $cashierData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'dispenseOutlet':
                    if(isset($_FILES['dispense_file']['name']) && !empty($_FILES['dispense_file']['name'])){ 
                        $files = $_FILES['dispense_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['dispense_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['dispense_file']['name']= $files['name'];
                        $_FILES['dispense_file']['type']= $files['type'];
                        $_FILES['dispense_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['dispense_file']['error']= $files['error'];
                        $_FILES['dispense_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('dispense_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $dispData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $temp['EID'] = $this->checkEatary($csv_data[0]);

                                        if($temp['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Name1'] =  $csv_data[1];

                                        if(empty($temp['Name1'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }
                                        $temp['Stat'] = 0;

                                        if($checker == 0){
                                            $dispData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }
                                        $dispData[] = $temp;
                                    }
                                }

                                if(!empty($dispData)){
                                    $this->db2->insert_batch('Eat_DispOutlets', $dispData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;

                case 'table':
                    if(isset($_FILES['table_file']['name']) && !empty($_FILES['table_file']['name'])){ 
                        $files = $_FILES['table_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['table_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['table_file']['name']= $files['name'];
                        $_FILES['table_file']['type']= $files['type'];
                        $_FILES['table_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['table_file']['error']= $files['error'];
                        $_FILES['table_file']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('table_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $tableData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $temp['EID'] = $this->checkEatary($csv_data[0]);

                                        if($temp['EID'] == 0){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['TableNo'] =  $csv_data[1];

                                        if(empty($temp['TableNo'])){
                                          $response = $csv_data[1]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['MergeNo'] = $temp['TableNo'];
                                        $temp['TblTyp'] =  $csv_data[2];
                                        if(empty($temp['TblTyp'])){
                                          $response = $csv_data[2]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Capacity'] =  $csv_data[3];
                                        if(empty($temp['Capacity'])){
                                          $response = $csv_data[3]. " Field Required in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['SecId'] =  $this->checkSection($csv_data[4]);
                                        if($temp['SecId'] == 0){
                                          $response = $csv_data[4]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['CCd'] =  $this->checkCashier($temp['EID'], $csv_data[5]);
                                        if($temp['CCd'] == 0){
                                          $response = $csv_data[5]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['Stat'] = 0;
                                        $temp['LoginCd'] = authuser()->RUserId;

                                        if($checker == 0){
                                            $tableData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }
                                        $tableData[] = $temp;
                                    }
                                }

                                if(!empty($tableData)){
                                    $this->db2->insert_batch('Eat_tables', $tableData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                break;
            }
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;    
        }
        $data['title'] = $this->lang->line('file').' '.$this->lang->line('upload');
        
        $this->load->view('rest/csv_file', $data);   
    }

    private function checkEatary($name){
        $EID = 0;
        $data = $this->db2->select('EID')->like('Name', $name)->get('Eatary')->row_array();
        if(!empty($data)){
            $EID = $data['EID'];
        }
        return $EID;
    }

    private function checkMenuCatg($EID, $name){
        $MCatgId = 0;
        $data = $this->db2->select('MCatgId')->like('Name1', $name)->get_where('MenuCatg', array('EID' => $EID))->row_array();
        if(!empty($data)){
            $MCatgId = $data['MCatgId'];
        }
        return $MCatgId;
    }

    private function checkEatCuisine($name){
        $ECID = 0;

        $cuisine = $this->db2->select('ECID')->like('Name1', $name)->get_where('EatCuisine', array('EID' => authuser()->EID))->row_array();
        if(!empty($cuisine)){
            $ECID = $cuisine['ECID'];
        }else{
            $eat['Name1'] = $name;
            $eat['EID'] = authuser()->EID;
            $eat['CID'] = $this->checkCuisine($name);
            $ECID = insertRecord('EatCuisine', $eat);
        }
        return $ECID;
    }

    private function getCIDFromEatCuisine($name){
        $cid = 0;
        $cuisine = $this->db2->select('CID')->like('Name1', $name)->get_where('EatCuisine', array('EID' => authuser()->EID))->row_array();
        if(!empty($cuisine)){
            $cid = $cuisine['CID'];
        }
        return $cid;
    }

    private function checkCuisine($name){
        $cid = 0;
        $cuisine = $this->db2->select('CID')->like('Name1', $name)->get('Cuisines')->row_array();
        if(!empty($cuisine)){
            $cid = $cuisine['CID'];
        }
        return $cid;
    }

    private function checkCTyp($usedFor){
        $CTyp = 0;
        $data = $this->db2->select('CTyp')->like('Usedfor1', $usedFor)->get('FoodType')->row_array();
        if(!empty($data)){
            $CTyp = $data['CTyp'];
        }
        return $CTyp;
    }

    private function checkKitchen($name, $EID){
        $KitCd = 0;
        $data = $this->db2->select('KitCd')->like('KitName1', $name)->get_where('Eat_Kit', array('EID' => $EID))->row_array();
        if(!empty($data)){
            $KitCd = $data['KitCd'];
        }
        return $KitCd;
    }

    private function checkFoodType($name){
        $FID = 0;
        $data = $this->db2->select('FID')->like('Name1', $name)
                        ->get('FoodType')
                        ->row_array();
        if(!empty($data)){
            $FID = $data['FID'];
        }
        return $FID;
    }

    private function checkItemType($name, $EID){
        $id = 0;
        $data = $this->db2->select('ItmTyp')->like('Name1', $name)->get_where('ItemTypes', array('EID' => $EID))
                    ->row_array();
        if(!empty($data)){
            $id = $data['ItmTyp'];
        }
        return $id;   
    }

    private function checkType($name, $type){
        $id = 0;
        $data = $this->db2->select('TagId')->like('TDesc1', $name)->get_where('MenuTags', array('TagTyp' => $type))
                    ->row_array();
        if(!empty($data)){
            $id = $data['TagId'];
        }
        return $id;    
    }

    private function getItemId($EID, $itemName){
        $itemId = 0;
        $data = $this->db2->select('ItemId')->like('Name1', $itemName)->get_where('MenuItem', array('EID' => $EID))
                    ->row_array();
        if(!empty($data)){
            $itemId = $data['ItemId'];
        }
        return $itemId;
    }

    private function checkSection($sectionName){
        $SecId = 0;
        $data = $this->db2->select('SecId')
                        ->like('Name1', $sectionName)
                        ->get('Eat_Sections')
                        ->row_array();
        if(!empty($data)){
            $SecId = $data['SecId'];
        }else{
            $sec['Name1'] = $sectionName;
            $sec['Stat'] = 0;
            $SecId = insertRecord('Eat_Sections', $sec);
        }
        return $SecId;
    }

    private function checkPortion($portionName){
        $IPCd = 0;
        $data = $this->db2->select('IPCd')->like('Name1', $portionName)->get('ItemPortions')
                    ->row_array();
        if(!empty($data)){
            $IPCd = $data['IPCd'];
        }
        return $IPCd;
    }

    private function checkCashier($EID, $cashierName){
        $CCd = 0;
        $data = $this->db2->select('CCd')->like('Name1', $cashierName)->get_where('Eat_Casher', array('EID' => $EID))
                    ->row_array();
        if(!empty($data)){
            $CCd = $data['CCd'];
        }
        return $CCd;
    }

    public function checkCNoForTable(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            extract($_POST);

            $whr = "k.CNo = km.CNo";
            $data = $this->db2->select("sum(k.ItmRate * k.Qty) as OrdAmt, k.CNo, km.MergeNo, ")
                            ->group_by('k.CNo')
                            ->join('Kitchen k', 'k.MergeNo = km.MergeNo', 'inner')
                            ->where($whr)
                            ->get_where('KitchenMain km', array('km.MergeNo' => $MergeNo,
                                'km.EID' => authuser()->EID,
                                'k.EID' => authuser()->EID,
                                'km.BillStat' => 0,
                                'k.Stat' => 3))
                            ->result_array();

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }
    }

    function updateMCNoForTable(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            extract($_POST);

            $EID = authuser()->EID;
            
            $this->db2->query("UPDATE Kitchen k, KitchenMain km set k.MCNo = $MCNo, km.MCNo = $MCNo where km.MergeNo = k.MergeNo and km.MergeNo = '$MergeNo' and km.EID = $EID and km.BillStat = 0 and k.BillStat = 0");
            $status = 'success';
            $response = $this->lang->line('MCNoUpdated');

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function dispense_notification(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            extract($_POST);

            $RestName = authuser()->RestName;
            $Dispense_OTP = $this->session->userdata('Dispense_OTP');

            if($oType != 101){
                $otpData['mobileNo'] = $mobile;
                $otpData['otp'] = '';
                $otpData['stat'] = 0;
                $otpData['pageRequest'] = 'Dispense';

                if($mobile){
                    $msg = "EAT-OUT: Order of Bill No: $billId from $RestName is ready. Please pick up from $dispCounter";

                    if($Dispense_OTP > 0){
                        $otp = generateOnlyOTP();
                        $msg = "EAT-OUT: Order of Bill No: $billId from $RestName is ready. Your OTP is $otp. Please pick up from $dispCounter";
                        $otpData['otp'] = $otp;
                    }

                    $smsRes = sendSMS($mobile, $msg);
                    
                    if($smsRes){
                        $otpData['stat'] = 1;
                        $status = 'success';
                        $response = $this->lang->line('messageSent');
                    }
                    insertRecord('OTP', $otpData);
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function delivery_notification(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            extract($_POST);

            $EID = authuser()->EID;
            $RestName = authuser()->RestName;
            $Dispense_OTP = $this->session->userdata('Dispense_OTP');

            if($oType != 101){

                updateRecord('Kitchen', array('DStat' => 1), array('CNo' => $CNo, 'DCd' => $DCd, 'EID' => $EID));

                $otpData['mobileNo'] = $mobile;
                $otpData['otp'] = '';
                $otpData['stat'] = 0;
                $otpData['pageRequest'] = 'Dispense';

                if($mobile){
                    $msg = "Order of Bill No : $billId, Counter : $dispCounter from $RestName has been delivered.";

                    $smsRes = sendSMS($mobile, $msg);
                    if($smsRes){
                        $otpData['stat'] = 1;
                        $status = 'success';
                        $response = $this->lang->line('deliveredSuccessfully');
                    }
                    insertRecord('OTP', $otpData);
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function verifyDelOTP(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $response = $this->lang->line('OTPNotVerified');
            
            $cust_otp = $this->session->userdata('cust_otp');
            if($cust_otp == $_POST['otp']){
                $status = "success";
                $response = $this->lang->line('OTPVerified');
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }      
    }

    public function get_cust_details(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $response = "Records Not Found!";
            $mobile = $_POST['CountryCd'].$_POST['phone'];
            $data = $this->db2->select("uId, FName, LName, DelAddress")->get_where('Users', array('MobileNo' => $mobile))->row_array();
            if(!empty($data)){
                $status = 'success';
                $response = $data;
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function updateDataItem(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
               
            $itemIdIn = implode(',', $_POST['itemId']);
            $itemIdIn = "(".$itemIdIn.")";
            
            $status = "success";

            switch ($_POST['type']) {
                case 'live':

                    $irnoIn = implode(',', $_POST['irno']);
                    $irnoIn = "(".$irnoIn.")";

                    $this->db2->query("UPDATE MenuItemRates mir, MenuItem mi set mir.OrigRate = mir.ItmRate where mi.EID = $EID and mir.ItemId = mi.ItemId and mir.IRNo IN $irnoIn ");
                $response = "Selected items are Live";
                    break;
                case 'enabled':
                    $this->db2->query("UPDATE MenuItem set Stat = 0 where EID = $EID and ItemId IN $itemIdIn ");
                    $response = "Items are Enabled";
                    break;
                case 'disabled':
                    $this->db2->query("UPDATE MenuItem set Stat = 1 where EID = $EID and ItemId IN $itemIdIn ");
                    $response = "Items are Disabled!";
                    break;
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function splitBill($MCNo, $MergeNo, $tableFilter){

        $data['MergeNo'] = $MergeNo;
        $data['payable'] = 0;
        $data['grossItemAmt'] = 0;
        $data['tip'] = 0;
        $data['MCNo'] = $MCNo;
        $data['tableFilter'] = $tableFilter;

        $EID = authuser()->EID;
        $EType = $this->session->userdata('EType');

        if($this->input->method(true)=='POST'){
                $MergeNo    = $_POST['MergeNo'];
                $strMergeNo = "'".$MergeNo."'"; 

               if($_POST['splitType'] == 1){
                    $this->food_bar_separate_bill($_POST);
                    
                    $CNo        = $_POST['MCNo'];

                    $this->db2->query("UPDATE Kitchen SET BillStat = 5  WHERE EID = $EID and (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 and Stat = 3 ");

                    $this->db2->query("UPDATE KitchenMain SET BillStat = 5 WHERE (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 AND EID = $EID ");
               }else{
                   $CNo = $_POST['MCNo'];
                   for ($i=0; $i < sizeof($_POST['mobile']) ; $i++) {
                        $pData['paymentMode'] = 'RestSplitBill';
                        $pData['CellNo'] = $_POST['mobile'][$i];
                        $pData['CNo'] = $_POST['MCNo'];
                        $pData['itemTotalGross'] = $_POST['totItemAmt'][$i];
                        $pData['orderAmount'] = $_POST['amount'][$i];
                        $pData['per_cent'] = $_POST['percent'][$i] / 100;
                        $pData['splitType'] = $_POST['splitType'];
                        $pData['MergeNo'] = $_POST['MergeNo'];
                        $pData['cust_discount'] = 0;
                        $pData['TableNo'] = 0;
                        $pData['TipAmount'] = 0;

                        $SchType = $this->session->userdata('SchType');
                        if(in_array($SchType, array(1,3))){
                            $this->rest->updateBillDiscountAmount($pData['MergeNo']);
                        }

                        $CountryCd = $_POST['CountryCd'][$i];
                        if(!empty($CountryCd)){
                            $this->session->set_userdata('pCountryCd', $CountryCd);
                        }

                        $this->session->set_userdata('CellNo', $pData['CellNo']);
                        $pData['CustId'] = createCustUser($pData['CellNo']);

                        $pData['CellNo'] = $CountryCd.$pData['CellNo'];

                        $res = billCreate($EID, $CNo, $pData);
                        if($res['status'] == 1){
                            $billId = $res['billId'];
                            $my_db = $this->session->userdata('my_db');
                            $url = $EID . "_b_" . $billId . "_" .$my_db. "_" . $CNo. "_" . $pData['CellNo']. "_" . $pData['MergeNo']. "_" . $pData['orderAmount'];

                            $url = base64_encode($url);
                            $url = rtrim($url, "=");
                            $link = base_url('users?eatout='.$url);

                            $temp['mobileNo'] = $pData['CellNo'];
                            $temp['link'] = $link;
                            $temp['billId'] = $billId;
                            $temp['EID'] = $EID;
                            $temp['created_by'] = authuser()->RUserId;
                            $temp['MCNo'] = $CNo;
                            $linkData[] = $temp;
                            $this->session->set_userdata('blink', $link);
                        // link send with bill no, sms or email => pending status
                            // for send to pay now to current customer
                        }
                   }

                   $kstat = ($EType == 5)?3:2;
                   $billingObjStat = 5;
                   $strMergeNo = "'".$MergeNo."'";
                   $this->db2->query("UPDATE Kitchen SET BillStat = $billingObjStat  WHERE EID = $EID and (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 and Stat = $kstat ");

                   $this->db2->query("UPDATE KitchenMain SET BillStat = $billingObjStat WHERE (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 AND EID = $EID ");
                        
                   redirect(base_url('restaurant/sitting_table'));
               }
        }
            
            $langId = $this->session->userdata('site_lang');
            $lname = "m.Name$langId";
            $ipName = "ip.Name$langId  as Portions";

            $groupby = ' km.MCNo';
            if($tableFilter == 'tableWise'){
                $characterToFind = '~';
                $count = substr_count($MergeNo, $characterToFind);
                if ($count > 0) {
                    $groupby = ' km.MergeNo';
                }
            }
            // add merge no to string
            $kitcheData = $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate,k.ItmRate, k.CellNo,  k.OrigRate*k.Qty as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = 3) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty) from Kitchen k1 where k1.MergeNo = km.MergeNo and k1.MergeNo = $MergeNo  and k1.EID=km.EID AND (k1.Stat = 3) and k1.BillStat = km.BillStat GROUP BY k1.EID) as TotPckCharge, $ipName, km.CNo,km.MergeNo, km.MCNo,sum(km.BillDiscAmt) as BillDiscAmt, sum(km.DelCharge) as DelCharge, sum(km.RtngDiscAmt) as totRtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType, k.TA, km.RtngDiscAmt,km.TableNo, km.CustId, c.ServChrg, c.Tips,e.Name  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = 3) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND km.MergeNo = $MergeNo group by $groupby, k.TA,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.Name1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.Name1 Asc")->result_array();

            // remove string
            $MergeNo = str_replace("'","",$MergeNo);
                $cellNo = '';
                $taxDataArray = array();
                if(!empty($kitcheData)){
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;
                    $MergeNo = $kitcheData[0]['MergeNo'];
                    $CNo = $kitcheData[0]['MCNo'];

                    $per_cent = 1;
                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);
                    $taxDataArray = $TaxRes['taxDataArray'];

                    foreach ($kitcheData as $kit ) {
                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                        if(!empty($kit['CellNo'])){
                            $cellNo = $kit['CellNo'];
                        }
                    }

                    $SubAmtTax = 0;
                    foreach ($taxDataArray as $tax) {
                        foreach ($tax as $key) {
                            if($key['Included'] >= 5){
                                $SubAmtTax = $SubAmtTax + round($key['SubAmtTax'], 2);
                            }
                        }
                    }
                        
                    $orderAmt = $orderAmt + $SubAmtTax;
                    $data['grossItemAmt'] = $orderAmt;
                    
                    $custDiscount = 0;

                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    $charge = $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];

                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount - $custDiscount;
                    
                    $data['payable'] = $total;
                }

        $data['CellNo']     = substr($cellNo, -10);
        $data['country']    = $this->rest->getCountries();
        $data['CountryCd']  = $this->session->userdata('CountryCd');

        $data['title'] = $this->lang->line('splitbill');
        $this->load->view('rest/split_bill', $data); 
    }

    public function split_bills(){
        $data['title'] = 'Split Bills';
        $data['bills'] = $this->rest->getSplitBills();
        $data['payModes'] = $this->rest->getPaymentModes();
        $this->load->view('rest/split_bill_list', $data);
    }

    public function eatary(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $res = [];
            switch ($_POST['type']) {
                case 'search':
                        $EID = $_POST['EID'];
                        $res['eatary'] = getRecords('Eatary', array('EID' => $EID));
                        $res['category'] = $this->rest->getCategory();
                        $res['country'] = $this->rest->getCountries();
                    break;

                case 'update':
                        $updateData = $_POST;
                        unset($updateData['type']);
                        updateRecord('Eatary', $updateData, array('EID' => $_POST['EID']));
                        $res = 'Updated Records';
                    break;
            }

            $response = $res;

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('restaurant');
        $data['eatary'] = $this->rest->getRestaurantList();
        $this->load->view('rest/eatary_edit', $data);    
    }

    public function cuisine(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['cuisineName'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['CID'] > 0){
                updateRecord('Cuisines', $updateData, array('CID' => $_POST['CID']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['CID']);
                insertRecord('Cuisines', $updateData);
                $response = 'Insert Records'; 
            }                    

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('cuisine');
        $data['cuisines'] = $this->rest->getCuisineList();
        $this->load->view('rest/cuisine_edit', $data);    
    }

    public function eat_cuisine(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['cuisineName'];
            $updateData['EID'] = $_POST['EID'];
            $updateData['CID'] = $_POST['CID'];
            $updateData['KitCd'] = $_POST['KitCd'];
            $updateData['Rank'] = $_POST['Rank'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['ECID'] > 0){
                updateRecord('EatCuisine', $updateData, array('ECID' => $_POST['MCatgId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ECID']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('EatCuisine', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('cuisine');
        $data['eatCuisine'] = $this->rest->getEatCuisineList();
        $data['kitchens'] = $this->rest->get_kitchen();
        // echo "<pre>";print_r($data);die;
        $this->load->view('rest/eat_cuisine', $data);    
    }

    public function menu_category(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['menuName'];
            $updateData['EID'] = $_POST['EID'];
            $updateData['CID'] = $_POST['CID'];
            $updateData['KitCd'] = $_POST['KitCd'];
            $updateData['Rank'] = $_POST['Rank'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['MCatgId'] > 0){
                updateRecord('MenuCatg', $updateData, array('MCatgId' => $_POST['MCatgId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['MCatgId']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('MenuCatg', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('menuCategory');
        $data['cuisines'] = $this->rest->getCuisineList();
        $data['kitchens'] = $this->rest->get_kitchen();
        $data['menuCatList'] = $this->rest->getMenuCatList();
        $this->load->view('rest/menu_category_edit', $data);    
    }

    public function kitchen(){
        // $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['kitchen'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['KitCd'] > 0){
                updateRecord('Eat_Kit', $updateData, array('KitCd' => $_POST['KitCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['KitCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('Eat_Kit', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('kitchen');
        $data['kitchens'] = $this->rest->get_kitchen();
        $this->load->view('rest/kitchen_edit', $data);    
    }

    public function cashier(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['cashier'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['CCd'] > 0){
                updateRecord('Eat_Casher', $updateData, array('CCd' => $_POST['CCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['CCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('Eat_Casher', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('cashier');
        $data['casherList'] = $this->rest->getCashier();

        $this->load->view('rest/cashier', $data);    
    }

    public function dispense_outlet(){
        $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['dispense'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['DCd'] > 0){
                updateRecord('Eat_DispOutlets', $updateData, array('DCd' => $_POST['DCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['DCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('Eat_DispOutlets', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('dispense').' '.$this->lang->line('outlet');
        $data['outlets'] = $this->rest->getDispenseOutletList();
        $this->load->view('rest/dispense_outlets', $data);    
    }

    public function table_list(){
        $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $updateData = $_POST;
            $updateData['MergeNo'] = $updateData['TableNo'];
            if($_POST['TId'] > 0){
                updateRecord('Eat_tables', $updateData, array('TId' => $_POST['TId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['TId']);
                $updateData['EID'] = authuser()->EID;
                $updateData['LoginCd'] = authuser()->RUserId;
                insertRecord('Eat_tables', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('table').' '.$this->lang->line('list');
        $data['tables'] = $this->rest->getAllTables();
        $data['sections'] = $this->rest->get_eat_section();
        $data['casherList'] = $this->rest->getCashier();
        
        $this->load->view('rest/table_list', $data);    
    }

    public function menu_list(){
        // $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['menu'];

            $updateData['pageUrl'] = $_POST['pageUrl'];
            $updateData['Rank'] = $_POST['Rank'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['RoleId'] > 0){
                updateRecord('UserRoles', $updateData, array('RoleId' => $_POST['RoleId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['RoleId']);
                insertRecord('UserRoles', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('menu').' '.$this->lang->line('list');
        $data['menus'] = $this->rest->getAllMenuList();

        $this->load->view('rest/menuList', $data);
    }

    public function menu_listing(){
        // $this->check_access();
        
        $data['title'] = $this->lang->line('menu').' '.$this->lang->line('list');
        $data['menus'] = $this->rest->getAllMenuListing();
        $this->load->view('rest/menuListing', $data);
    }

    public function scheme_category(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['category'];
            $updateData['SchTyp'] = 2;
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['SchCatg'] > 0){
                updateRecord('CustOfferTypes', $updateData, array('SchCatg' => $_POST['SchCatg']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['SchCatg']);
                insertRecord('CustOfferTypes', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('offers').' '. $this->lang->line('category');
        $data['sch_cat'] = $this->rest->getOffersSchemeCategory(0);

        $this->load->view('rest/schemeCategory', $data);
    }

    public function menu_item_rates(){
        // $this->check_access();
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            switch ($_POST['type']) {
                case 'rates_file':
                        $folderPath = 'uploads/e'.$EID.'/csv';
                        if (!file_exists($folderPath)) {
                            // Create the directory
                            mkdir($folderPath, 0777, true);
                        }
                        // remove all files inside this folder uploads/qrcode/
                        $filesPath = glob($folderPath.'/*'); // get all file names
                        foreach($filesPath as $file){ // iterate files
                          if(is_file($file)) {
                            unlink($file); // delete file
                          }
                        }  
                        // end remove all files inside folder
                        $flag = 0;
                        if(isset($_FILES['itmRates']['name']) && !empty($_FILES['itmRates']['name'])){ 
                        $files = $_FILES['itmRates'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['itmRates']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['itmRates']['name']= $files['name'];
                        $_FILES['itmRates']['type']= $files['type'];
                        $_FILES['itmRates']['tmp_name']= $files['tmp_name'];
                        $_FILES['itmRates']['error']= $files['error'];
                        $_FILES['itmRates']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('itmRates',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $ratesData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;

                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    if($csv_data[0] !='RestName'){
                                        $checker = 1;
                                        $temp['EID'] = $this->checkEatary($csv_data[0]);

                                        if($temp['EID'] < 1){
                                          $response = $csv_data[0]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['ItemId'] = $this->getItemId($temp['EID'], $csv_data[1]);

                                        if($temp['ItemId'] < 1){
                                          $response = $csv_data[1]. " Not Found in row no: $count";
                                          $checker = 0;
                                        }

                                        $temp['SecId'] = $this->checkSection($csv_data[2]);

                                        $temp['Itm_Portion'] =$this->checkPortion($csv_data[3]);

                                        $temp['ItmRate']    = $csv_data[4];
                                        $temp['OrigRate']   = 0;
                                        $temp['ChainId']    = 0;

                                        if($checker == 0){
                                            $ratesData = [];
                                            header('Content-Type: application/json');
                                            echo json_encode(array(
                                                'status' => $status,
                                                'response' => $response
                                              ));
                                             die; 
                                        }
                                        $ratesData[] = $temp;
                                    }
                                }

                                if(!empty($ratesData)){
                                    $this->db2->insert_batch('MenuItemRates', $ratesData);
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }

                                fclose($open);
                            }
                        }
                      }
                    break;
                
                case 'get':
                    $status = 'success';
                    $response = $this->rest->getItemRatesByItemId($_POST['ItemId']);
                    break;
                case 'update':
                    $status = 'success';
                    
                    for ($i=0; $i <sizeof($_POST['SecId']) ; $i++) { 
                        updateRecord('MenuItemRates', array('ItmRate'=> $_POST['itemRate'][$i]), array('EID' => $EID, 'ItemId' => $_POST['ItemId'], 'SecId' => $_POST['SecId'][$i], 'Itm_Portion' => $_POST['Itm_Portion'][$i]));
                    }
                    $response = 'Rate has been updated.';
                    break;
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['kitchen'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['KitCd'] > 0){
                updateRecord('Eat_Kit', $updateData, array('KitCd' => $_POST['KitCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['KitCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('Eat_Kit', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = 'Menu Item Rates';
        $data['itemList'] = $this->rest->getAllItemsList();
        $this->load->view('rest/item_rates', $data);    
    }

    public function recommendation(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($updateData['RecNo'] > 0){
                updateRecord('MenuItem_Recos', $updateData, array('RecNo' => $_POST['RecNo']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['RecNo']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('MenuItem_Recos', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('recommendation');
        $data['itemList'] = $this->rest->getAllItemsList();
        $data['recList'] = $this->rest->getRecommendationList();
        $this->load->view('rest/recommendation', $data);    
    }

    public function get_recommendation(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;

            $status = 'success';
            $itemId = $_POST['itemId'];
            $TableNo = $_POST['TableNo'];

            $langId = $this->session->userdata('site_lang');
            $lname = "mi.Name$langId as ItemNm";
            $iDesc = "mi.ItmDesc$langId as ItmDesc";
            $ingeredients = "mi.Ingeredients$langId as Ingeredients";
            $Rmks = "mi.Rmks$langId as Rmks";

            $select = "mc.TaxType, mc.KitCd, mi.ItemId, $lname, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, $iDesc, $ingeredients, $Rmks, mi.PrepTime, mi.AvgRtng, mi.FID, Name1 as imgSrc, mi.UItmCd,mi.CID ,mi.MCatgId,  (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate,(select mir.Itm_Portion FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$TableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as Itm_Portions, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$EID' and et1.TableNo = '$TableNo') as TblTyp";
            $rec = $this->db2->select($select)
                            ->join('MenuItem mi','mi.ItemId = mr.RcItemId', 'inner')
                            ->join('MenuCatg mc', 'mc.MCatgId = mi.MCatgId', 'inner')
                            ->get_where('MenuItem_Recos mr', 
                                        array('mr.ItemId' => $itemId, 
                                            'mr.EID' => $EID,
                                            'mr.Stat' => 0
                                        )
                            )->result_array();
                 
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $rec
              ));
             die;
        }
    }

    public function recomAddCart(){
        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            $EID = authuser()->EID;
            $TableNo = $this->session->userdata('TableNo');            
            $temp = array();
            $data = array();
            $flag = 0;
            if(!empty($_POST['itemArray'])){
                foreach ($_POST['itemArray'] as $itemId ) {
                    $TableNo = $_POST['TableNo'][$itemId][0];
                    $MergeNo = $TableNo;
                    $itemName = $_POST['itemName'][$itemId][0];

                    $qty = $_POST['qty'][$itemId][0];
                    $rate = $_POST['rate'][$itemId][0];
                    if($qty > 0){
                        $flag++;
                        $OType = 0;
                        $TblTyp = $_POST['TblTyp'][$itemId][0];
                        
                        if($TblTyp == 100){
                            // QSR
                            $OType = 100;
                        }else if($TblTyp == 5){
                            // Seat no basis - common table like in bars
                            $OType = 5;
                        }else if($TblTyp == 7){
                            // Sit-In customer
                            $OType = 7;
                        }else if($TblTyp == 8){
                            // Sit-In offline
                            // $OType = 8;
                        }else if($TblTyp == 105){
                            // personal TakeAway
                            $OType = 105;
                        }else if($TblTyp == 110){
                            // Rest Delivery
                            $OType = 110;
                        }else if($TblTyp == 101){
                            // 3P Delivery - swiggy/zomato
                            $OType = 101;
                        }else if($TblTyp == 115){
                            // Drive-In
                            $OType = 115;
                        }else if($TblTyp == 30){
                            // Charity
                            $OType = 30;
                        }else if($TblTyp == 35){
                            // RoomService
                            $OType = 35;
                        }else if($TblTyp == 40){
                            // Suite Service
                            $OType = 40;
                        }

                        $prepration_time = $_POST['prepration_time'][$itemId][0];

                        $temp['CNo'] = 0;
                        $temp['CustId'] = 0;
                        $temp['COrgId'] = 0;
                        $temp['CustNo'] = 0;
                        $temp['CellNo'] = 0;
                        $temp['EID'] = $EID;
                        $temp['ChainId'] = 0;
                        $temp['ONo'] = 0;
                        $temp['KitCd'] = $_POST['itemKitCd'][$itemId][0];
                        $temp['itemName'] = $itemName;
                        $temp['OType'] = $OType;
                        $temp['FKOTNo'] = 0;
                        $temp['KOTNo'] = 0;
                        $temp['UKOTNo'] = 0;
                        $temp['TableNo'] = $TableNo;
                        $temp['MergeNo'] = $MergeNo;
                        $temp['ItemId'] = $itemId;
                        $temp['TaxType'] = $_POST['tax_type'][$itemId][0];
                        $temp['Qty'] = $qty;
                        $temp['ItmRate'] = $rate;
                        $temp['OrigRate'] = $rate;  
                        $temp['Itm_Portion'] = $_POST['Itm_Portions'][$itemId][0];
                        $temp['CustRmks'] = '';
                        $temp['DelTime'] = date('Y-m-d H:i:s');
                        $temp['TA'] = 0;
                        $temp['Stat'] = 0;
                        $temp['LoginCd'] = 1;
                        $temp['SDetCd'] = 0;
                        $temp['SchCd'] = 0;

                        $data[] = $temp;
                    }
                }
            }
            
            $response = 'Please add atleast one Item';
            if($flag > 0){
                $this->db2->insert_batch('tempKitchen', $data);
                $status = 'success';
                $response = 'Item Added';
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function paymentMode(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($updateData['PMNo'] > 0){
                updateRecord('PymtModes', $updateData, array('PMNo' => $_POST['PMNo']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['PMNo']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('PymtModes', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('payment').' '.$this->lang->line('mode');
        $data['payList'] = $this->rest->getPaymentType();
        $this->load->view('rest/pay_modes', $data);    
    }

    public function third_party_list(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['PId'] > 0){
                updateRecord('3POrders', $updateData, array('3PId' => $_POST['PId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['PId']);
                insertRecord('3POrders', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('thirdParty');
        $data['lists'] = $this->rest->getThirdOrderData();
        $this->load->view('rest/third_party', $data);    
    }

    public function config_payment(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['PymtMode'] > 0){
                updateRecord('ConfigPymt', $updateData, array('PymtMode' => $_POST['PymtMode']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['PymtMode']);
                insertRecord('ConfigPymt', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('payment');
        $data['lists'] = $this->rest->getConfigPayment();
        $this->load->view('rest/config_payment', $data);    
    }

    public function sections(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['SecId'] > 0){
                updateRecord('Eat_Sections', $updateData, array('SecId' => $_POST['SecId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['SecId']);
                insertRecord('Eat_Sections', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('section');
        $data['lists'] = $this->rest->getSectionList();
        $this->load->view('rest/section', $data);    
    }

    public function cuisine_access(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
            $langId = $this->session->userdata('site_lang');
            
            $status = 'success';

            if (isset($_POST['getAvailableRoles']) && $_POST['getAvailableRoles']==1) {
                $lname = "Name$langId as Name";
                $response = $this->db2->query("SELECT CID, $lname from Cuisines where CID not in (select CID from EatCuisine where Stat = 0 and EID = $EID) ")->result_array();
            }

            if (isset($_POST['getAssignedRoles']) && $_POST['getAssignedRoles']==1) {
                $lname = "ec.Name$langId as Name";
                $response = $this->db2->select("ec.CID, $lname")
                                ->join('Cuisines c', 'c.CID = ec.CID', 'inner')
                                ->get_where('EatCuisine ec', array('ec.EID' => $EID, 'ec.Stat' => 0))->result_array();
            }

            if (isset($_POST['setRestRoles']) && $_POST['setRestRoles']==1) {

                $response = "Cuisines are assigned";

                $temp = [];
                $cui = [];
                $roles = $_POST['roles'];

                foreach ($roles as $role) {
                    $temp['EID'] = $EID;
                    $temp['CID'] = $role;
                    $cui[] = $temp;
                }

                if(!empty($cui)){
                        $this->db2->insert_batch('EatCuisine', $cui); 
                    }else{
                        $response = "Failed to insert";
                    }
                }

            if (isset($_POST['removeRestRoles']) && $_POST['removeRestRoles'] == 1) {
                $CID = $_POST['CID'];
                $CID = implode(",",$CID);

                $deleteRoles = $this->db2->query("DELETE FROM EatCuisine WHERE EID = $EID AND CID IN ($CID)");

                if ($deleteRoles) {
                    $response = "Cuisine are Removed";
                }else {
                    $response = "Failed to delete in EatCuisine table";
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('cuisine');

        $this->load->view('rest/cuisine_access', $data);    
    }

    public function entertainment(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['name'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['EntId'] > 0){
                updateRecord('Entertainment', $updateData, array('EntId' => $_POST['EntId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['EntId']);
                insertRecord('Entertainment', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('entertainment');
        $data['lists'] = $this->rest->getEntertainment();
        $this->load->view('rest/entertainment', $data);    
    }

    public function eat_ent(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $EID = authuser()->EID;
            $langId = $this->session->userdata('site_lang');

            $updateData = $_POST;
            $updateData['EID'] = $EID;
            $updateData['fromDt'] = date('Y-m-d', strtotime($updateData['fromDt']));
            $updateData['toDt'] = date('Y-m-d', strtotime($updateData['toDt']));
            $updateData['LoginId'] = authuser()->RUserId;

            $flag = 0;
            if(isset($_FILES['item_file']['name']) && !empty($_FILES['item_file']['name'])){ 

                $files = $_FILES['item_file'];

                $allowed = array('jpeg', 'jpg');
                $filename_c = $_FILES['item_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only jpg,jpeg format!');
                }
                // less than 1mb size upload
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }

                $_FILES['item_file']['name']= $files['name'];
                $_FILES['item_file']['type']= $files['type'];
                $_FILES['item_file']['tmp_name']= $files['tmp_name'];
                $_FILES['item_file']['error']= $files['error'];
                $_FILES['item_file']['size']= $files['size'];
                $file = $files['name'];
                $updateData['PerImg'] = $updateData['EntId'].'_'.$EID.'_'.$file; 

                $folderPath = FCPATH."uploads/e$EID/ent";
                
                if (!file_exists($folderPath)) {
                    // Create the directory
                    mkdir($folderPath, 0777, true);
                    chmod($folderPath, 0777);
                }

                if($flag == 0){
                    $res = do_upload('item_file',$file,$folderPath,'*');
                    insertRecord('Eat_Ent', $updateData);
                }
                
              }

            $status = 'success';
            $response = 'Record Inserted';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('entertainment');
        $data['lists'] = $this->rest->getEntertainment();
        $data['weekDay'] = $this->rest->getWeekDayList();
        $data['entertainments'] = $this->rest->getEntertainmentList();
        $this->load->view('rest/eat_ent', $data);    
    }

    public function suppliers(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname]         = $_POST['name'];
            $updateData['CreditDays']   = $_POST['CreditDays'];
            $updateData['Remarks']      = $_POST['Remarks'];
            $updateData['Stat']         = $_POST['Stat'];

            if($_POST['SuppCd'] > 0){
                updateRecord('RMSuppliers', $updateData, array('SuppCd' => $_POST['SuppCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['SuppCd']);
                insertRecord('RMSuppliers', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('supplier');
        $data['lists'] = $this->rest->getSuppliers();
        $this->load->view('rest/suppliers', $data);    
    }

    public function itemType(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData[$lname] = $_POST['item'];
            $updateData['Stat'] = $_POST['Stat'];

            if($_POST['ItmTyp'] > 0){
                updateRecord('ItemTypes', $updateData, array('ItmTyp' => $_POST['ItmTyp']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ItmTyp']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('ItemTypes', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('item').' '.$this->lang->line('type');
        $data['itemTyp'] = $this->rest->getItemTypeList();

        $this->load->view('rest/itemType', $data);    
    }

    public function item_type_group(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $langId = $this->session->userdata('site_lang');
            $lname = "Name$langId";

            $updateData = $_POST;
            $updateData[$lname]         = $updateData['name'];

            unset($updateData['name']);
            if($_POST['ItemGrpCd'] > 0){
                updateRecord('ItemTypesGroup', $updateData, array('ItemGrpCd' => $_POST['ItemGrpCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ItemGrpCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('ItemTypesGroup', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = 'Custom '.$this->lang->line('item').' '.$this->lang->line('group');
        $data['itemTyp'] = $this->rest->getMenuTagList();
        $data['itemList'] = $this->rest->getAllItemsList();
        $data['lists'] = $this->rest->getItemTypesGroupList();
        $this->load->view('rest/itemtypegroup', $data);    
    }

    public function item_type_det(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($_POST['ItemOptCd'] > 0){
                updateRecord('ItemTypesDet', $updateData, array('ItemOptCd' => $_POST['ItemOptCd']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['ItemOptCd']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('ItemTypesDet', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = 'Custom Item Details';
        $data['itemGroup'] = $this->rest->getItemTypesGroup();
        $data['lists'] = $this->rest->getItemTypesDet();
        $this->load->view('rest/itemtypedet', $data);    
    }

    public function get_menu_list(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $response = $this->rest->get_menu_list_by_stat($_POST['Stat']);
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function item_files_upload(){
        $this->check_access();
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        $data['notUpload'] = [];
        if($this->input->method(true)=='POST'){

            $temp = [];
            $notUpload = [];
            
            // If files are selected to upload 
            if(!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0){ 
                $filesCount = count($_FILES['files']['name']); 
                $flag = 0;
                // $allowed = array('jpg');
                $files = $_FILES['files'];
                for($i = 0; $i < $filesCount; $i++){ 
                    $_FILES['files']['name']     = $files['name'][$i]; 
                    $_FILES['files']['type']     = $files['type'][$i]; 
                    $_FILES['files']['tmp_name'] = $files['tmp_name'][$i]; 
                    $_FILES['files']['error']     = $files['error'][$i]; 
                    $_FILES['files']['size']     = $files['size'][$i]; 
                    $file = $files['name'][$i];
                    
                    $folderPath = 'uploads/e'.$EID.'/'; 
                    if (!file_exists($folderPath)) {
                        // Create the directory
                        mkdir($folderPath, 0777, true);
                    }

                    if($_FILES['files']['size'] < 1048576){
                         $res = do_upload('files',$file,$folderPath,'*');
                         $status = 'success';
                         $response = 'File Uploaded';
                    }else{
                        $temp = $file;
                        $notUpload[] = $temp;
                    }                     
                } 
            }

            if(!empty($notUpload)){
                $status = 'pending';
                $response = $notUpload;
                $fd['files'] = implode(",",$notUpload);
                $fd['EID'] = $EID;
                $fd['created_at'] = date('Y-m-d H:i:s'); 
                insertRecord('fileNotUploaded', $fd);
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['title'] = $this->lang->line('item');

        $this->load->view('rest/item_files', $data);    
    }

    public function abc_report(){
        // $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $reports = $this->rest->getABCRepots($_POST);
            $response = $reports;

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['cuisine'] = $this->rest->getCuisineList();
        $data['title'] = $this->lang->line('abc').' '.$this->lang->line('report');
        $this->load->view('report/abcReport', $data);    
    }

    public function get_itemList_by_mcatgId(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getAllItemsListByMenuCatgId($_POST['MCatgId']);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function tax_report(){
        $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getTaxRepots($_POST);
            
            $new_response = [];
            foreach ($response as $key ) {
                $new_response[$key['BillId']]['BillId'] = $key['BillId'];
                // $new_response[$key['BillId']]['TaxPcent'] = $key['TaxPcent'];
                $new_response[$key['BillId']]['Date'] = $key['Date'];

                if($key['TaxName'] == 'VAT'){
                    
                    $new_response[$key['BillId']]['VAT'] = $key['TaxAmt'];
                    $new_response[$key['BillId']]['VAT_Pcent'] = $key['TaxPcent'];
                }

                if($key['TaxName'] == 'SGST'){
                    $new_response[$key['BillId']]['SGST'] = $key['TaxAmt'];
                    $new_response[$key['BillId']]['SGST_Pcent'] = $key['TaxPcent'];
                }

                if($key['TaxName'] == 'CGST'){
                    $new_response[$key['BillId']]['CGST'] = $key['TaxAmt'];
                    $new_response[$key['BillId']]['CGST_Pcent'] = $key['TaxPcent'];
                }
            }

            $res = [];
            foreach ($new_response as $key) {
                $temp['BillId'] = $key['BillId'];
                // $temp['TaxPcent'] = $key['TaxPcent'];
                $temp['Date'] = $key['Date'];

                $temp['VAT'] = !empty($key['VAT'])?$key['VAT']:0;
                $temp['CGST'] = !empty($key['CGST'])?$key['CGST']:0;
                $temp['SGST'] = !empty($key['SGST'])?$key['SGST']:0;

                $temp['VAT_Pcent'] = !empty($key['VAT_Pcent'])?$key['VAT_Pcent']:0;
                $temp['CGST_Pcent'] = !empty($key['CGST_Pcent'])?$key['CGST_Pcent']:0;
                $temp['SGST_Pcent'] = !empty($key['SGST_Pcent'])?$key['SGST_Pcent']:0;

                $res[] = $temp;
            }
            $data['res'] = $res;
            $data['headers'] = $this->rest->getTaxHead();

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $data
              ));
             die;
        }

        $data['title'] = $this->lang->line('tax').' '.$this->lang->line('report');
        $this->load->view('report/taxReport', $data);    
    }

    public function income_report(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getIncomeRepots($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('income').' '.$this->lang->line('report');
        $this->load->view('report/incomeReport', $data);    
    }

    public function stock_statement(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getStockStatementRepots($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = $this->lang->line('stock').' '.$this->lang->line('statement');
        $data['stores'] = $this->rest->getKitchenList();
        $this->load->view('report/stockStatement', $data);    
    }

    public function discount_user(){
        // $this->check_access();
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";
            $country = $_POST['countryCd'];
            $this->session->set_userdata('pCountryCd', $country);
            if($_POST['uId'] > 0){
                $userId = $_POST['uId'];
            }else{
                $userId = createCustUser($_POST['MobileNo']);
            }

            $upData['CountryCd']   = $country;
            $upData['discId']      = $_POST['discId'];

            updateRecord('Users', $upData, array('EID' => $EID, 'CustId' => $userId));

            $response = 'Discount Updated';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Discount User';
        $data['discounts'] = $this->rest->getUserDiscount();
        $data['country'] = $this->rest->getCountries();
        $data['users'] = $this->rest->discountUserList();
        
        $this->load->view('rest/discount_user', $data);    
    }

    public function data_upload(){
        // $this->check_access();
        
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $EID = $_POST['EID'];
            $folderPath = 'uploads/e'.$EID.'/csv';
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, true);
            }

            $filesPath = glob($folderPath.'/*');

            $flag = 0;

            switch ($_POST['type']) {
                case 'items':

                    if(isset($_FILES['items_file']['name']) && !empty($_FILES['items_file']['name'])){ 
                        $files = $_FILES['items_file'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['items_file']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['items_file']['name']= $files['name'];
                        $_FILES['items_file']['type']= $files['type'];
                        $_FILES['items_file']['tmp_name']= $files['tmp_name'];
                        $_FILES['items_file']['error']= $files['error'];
                        $_FILES['items_file']['size']= $files['size'];
                        $file = $files['name'];

                        $itemData = [];
                        $temp = [];
                        $count = 1;
                        $checker = 0;

                        if($flag == 0){
                            $res = do_upload('items_file',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='RestName'){

                                        $temp['RestName'] = $csv_data[0];
                                        $temp['Cuisine'] = $csv_data[1];
                                        $temp['FID'] = $csv_data[2];
                                        $temp['IMcCd'] = $csv_data[3];
                                        $temp['MenuCatgNm'] = $csv_data[4];
                                        $temp['CTypUsedFor'] = $csv_data[5];
                                        $temp['ItemNm'] = $csv_data[6];
                                        $temp['ItemTyp'] = $csv_data[7];
                                        $temp['NV'] = $csv_data[8];
                                        $temp['Section'] = $csv_data[9];
                                        $temp['PckCharge'] = $csv_data[10];
                                        $temp['Rate'] = $csv_data[11];
                                        $temp['Rank'] = $csv_data[12];
                                        $temp['ItmDesc'] = $csv_data[13];
                                        $temp['Ingeredients'] = $csv_data[14];
                                        $temp['MaxQty'] = $csv_data[15];
                                        $temp['Rmks'] = $csv_data[16];
                                        $temp['PrepTime'] = $csv_data[17];
                                        $temp['DayNo'] = $csv_data[18];
                                        $temp['FrmTime'] = $csv_data[19];
                                        $temp['ToTime'] = $csv_data[20];
                                        $temp['AltFrmTime'] = $csv_data[21];
                                        $temp['AltToTime'] = $csv_data[22];
                                        $temp['videoLink'] = $csv_data[23];
                                        $temp['Itm_Portion'] = $csv_data[24];
                                        $temp['LoginCd'] = authuser()->RUserId;

                                        $itemData[] = $temp;
                                    }
                                }
                                if(!empty($itemData)){
                                    // print_r($itemData);die;
                                   $this->db2->query('TRUNCATE TempMenuItem');
                                   $this->db2->insert_batch('TempMenuItem', $itemData);
                                    
                                    $status = 'success';
                                    $response = 'Data Inserted.';
                                }
                                fclose($open);
                            }
                        }
                      }

                break;
            }
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die; 
        }
        $data['title'] = 'Items Upload';
        $data['rests'] = $this->rest->getRestaurantList();
        $this->load->view('rest/item_upload', $data);    
    }

    public function insert_temp_menu_item(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $EID = $_POST['EID'];

            $check = $this->db2->get('TempMenuItem')->row_array();
            if(!empty($check)){
                $this->db2->query("DELETE From EatCuisine where EID=$EID");
                $this->db2->query("DELETE From MenuCatg where EID=$EID");
                $this->db2->query("DELETE From MenuItem where EID=$EID");
                $this->db2->query("DELETE From MenuItemRates where EID=$EID");

                $this->db2->query("INSERT INTO EatCuisine (CID, Name1, EID) SELECT DISTINCT c.CID, c.Name1, $EID From Cuisines c, TempMenuItem t where c.Name1 = t.Cuisine");

                $this->db2->query("INSERT INTO MenuCatg (Name1, CID, CTyp,EID, TaxType)  SELECT DISTINCT t.MenuCatgNm , c.CID, f.CTyp, $EID, 0 From Cuisines c, TempMenuItem t, FoodType f where c.Name1 = t.Cuisine and f.Usedfor1 = t.CTypUsedFor");
                // t.DayNo = 
                $this->db2->query("INSERT INTO MenuItem (IMcCd, EID, MCatgId, CID, CTyp,  FID, Name1, NV, PckCharge, ItmDesc1, Ingeredients1, MaxQty, Rmks1, PrepTime, DayNo, FrmTime, ToTime, AltFrmTime, AltToTime, videoLink)  SELECT DISTINCT t.IMcCd, $EID, m.MCatgId, m.CID, m.CTyp, f.FID, t.ItemNm, t.NV, t.PckCharge,t.ItmDesc, t.Ingeredients, t.MaxQty, t.Rmks, t.PrepTime, IFNULL((SELECT DayNo FROM WeekDays wd where wd.Name1 = t.DayNo),8), t.FrmTime, t.ToTime, t.AltFrmTime, t.AltToTime, t.videoLink From TempMenuItem t, FoodType f, MenuCatg m where f.Name1=t.FID and t.MenuCatgNm=m.Name1");

                $this->db2->query("INSERT INTO MenuItemRates (EID, SecId, ItemId, Itm_Portion, ItmRate)  SELECT $EID,IFNULL((SELECT es.SecId From Eat_Sections es where es.Name1 = t.Section),1),  i.ItemId, ip.IPCd, t.Rate From TempMenuItem t, ItemPortions ip, MenuItem i where ip.Name1=t.Itm_Portion and i.Name1=t.ItemNm");
                $status = 'success';
                $response = 'Basic Menu Item has been Setup.';
            }else{
                $response = 'No Data Found!!';
            }


            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }
    // onaccount , prepaid
    public function payment_collection_settled_bill(){
        // $this->check_access();
        $data['EID'] = authuser()->EID;

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $response = $this->rest->getSettledBillNotCollectPymnt($_POST);
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Payment Collection';
        $data['pymtModes'] = $this->rest->getPaymentModes();
        $this->load->view('rest/payment_collection', $data);
    }
    
    public function getBillByCustId(){

        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = 'success';
            $dt['details'] = $this->rest->getBillingByCustId($_POST['CustId']);
            $custList = checkOnAccountCust($_POST['CustId']);
            $dt['prePaid'] = 0;
            if(!empty($custList)){
                $dt['prePaid'] = $custList['prePaidAmt'];
            }
            $response = $dt;
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function save_group_payment(){
        $EID = authuser()->EID;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $totalAmount = $_POST['amount'];
            $bData = $this->rest->getBillingByCustId($_POST['CustId']);

            if(!empty($bData)){
                $extraAmount = 0;
                $counter = 1;
                foreach ($bData as $bill) {
                    $onAccDetail = checkOnAccountCust($_POST['CustId']);
                    if(!empty($onAccDetail)){
                        if($onAccDetail['prePaidAmt'] > 0){
                            $totalAmount = $totalAmount + $onAccDetail['prePaidAmt'];
                            $RC['CustId'] = $_POST['CustId'];
                            $RC['Amount'] = $onAccDetail['prePaidAmt'];
                            $RC['Remarks'] = 'Adjust';
                            rechargeHistory($RC);
                            $cusList['prePaidAmt'] = 0;
                            updateRecord('CustList', $cusList, array('CustId' => $_POST['CustId']));
                        }

                        if($totalAmount >= $bill['totalBillPaidAmt']){
                            $extraAmount = ($totalAmount - $bill['totalBillPaidAmt']);
                            if($counter == 1){
                                if($extraAmount > 0){
                                    $cusList['prePaidAmt']  = $extraAmount;

                                    $RC['CustId'] = $_POST['CustId'];
                                    $RC['Amount'] = $extraAmount;
                                    $RC['Remarks'] = 'Added';
                                    rechargeHistory($RC);
                                    updateRecord('CustList', $cusList, array('CustId' => $_POST['CustId']));
                                }
                            }
                            $counter++;
                        }
                    }

                    if($totalAmount > 0){
                        if($totalAmount >= $bill['totalBillPaidAmt']){

                            $bpAmount = $bill['totalBillPaidAmt'];
                            $totalAmount = $totalAmount - $bill['totalBillPaidAmt'];

                            updateRecord('Billing', array('Stat' => 1), array('EID' => $EID, 'BillId' => $bill['BillId']));
                            $pymt_rcpt = 1;

                            // loyalty
                            $loyalties = array(
                             'LId'          => 0,
                             'custId'       => $_POST['CustId'],
                             'EID'          => $EID,
                             'billId'       => $bill['BillId'],
                             'billDate'     => date('Y-m-d H:i:s'),
                             'billAmount'   => $bill['totalBillPaidAmt'],
                             'MobileNo'     => $bill['CellNo'],
                             'OTP'          => 0,
                             'Points' => 0,
                             'earned_used'  => 0
                            );
                            insertLoyalty($loyalties);
                        }else{
                            $bpAmount = $totalAmount;
                            $totalAmount = $totalAmount - $totalAmount;
                            $pymt_rcpt = 0;
                        }
                        // custAccounts
                        $ca['receivedAmount'] = $bpAmount;
                        $ca['receivedDate']   = date('Y-m-d H:i:s');
                        $ca['pymt_rcpt']      = $pymt_rcpt;
                        updateRecord('custAccounts', $ca, array('EID' => $EID, 'billId' => $bill['BillId']));
                        $caData = $this->rest->getCustAccounts($bill['BillId']);
                        // billpayments
                        $pay['BillId'] = $bill['BillId'];
                        $pay['MCNo'] = $bill['CNo'];
                        $pay['MergeNo'] = $bill['MergeNo'];
                        $pay['TotBillAmt'] = $bill['PaidAmt'];
                        $pay['CellNo'] = $bill['CellNo'];
                        $pay['SplitTyp'] = 0;
                        $pay['SplitAmt'] = 0;
                        $pay['PymtId'] = 0;
                        $pay['PaidAmt'] = $bpAmount;
                        $pay['OrderRef'] = 0;
                        $pay['PaymtMode'] = $_POST['PaymtMode'];
                        $pay['PymtType'] = 0;
                        $pay['PymtRef'] = 0;
                        $pay['Stat'] = $pymt_rcpt;
                        $pay['EID'] = $EID;
                        $pay['caId'] = $caData['caId'];
                        insertRecord('BillPayments', $pay);
                    }
                }
            }
            $status = 'success';
            $response = $this->lang->line('paymentCollected');
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function sales_report(){
        $this->check_access();

        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->rest->getSalesRepots($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Date Wise Detailed Sales '.$this->lang->line('report');
        $this->load->view('report/salesReport', $data);    
    }

    public function item_sales_report(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->rest->getItemSalesRepots($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Item Sales '.$this->lang->line('report');
        $this->load->view('report/itemSalesReport', $data);    
    }

    public function contribution_report(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";

            $response = $this->rest->getContributionRepots($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['cuisine'] = $this->rest->getCuisineList();
        $data['otypes'] = $this->rest->getOTypeList();
        $data['title'] = 'Contribution '.$this->lang->line('report');
        $this->load->view('report/contributionReport', $data);    
    }

    public function sale_summary(){
        $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->rest->getSalesSummary($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['cuisine'] = $this->rest->getCuisineList();
        $data['title'] = 'Sale Summary';
        $this->load->view('report/saleSummary', $data);    
    }

    public function onaccount_sale_summary(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->rest->getOnaccountsData();

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Pending Collection Reports';
        $this->load->view('report/onAccountSaleSummary', $data);    
    }

    private function check_access(){
        $pgUrl = $this->uri->segment(2);
        $RUserId = authuser()->RUserId;
        $EID = authuser()->EID;
        $data =  $this->db2->select('ur.RoleId')
                        ->order_by('ur.Rank', 'ASC')
                        ->join('UserRolesAccess ura', 'ura.RoleId = ur.RoleId','inner')
                        ->get_where('UserRoles ur', 
                            array('ura.RUserId' => $RUserId,
                                'ura.EID' => $EID,
                                'ur.Stat' => 0,
                                'ur.pageUrl' => $pgUrl)
                                )
                        ->row_array();

        if(empty($data)){
            redirect(base_url('restaurant/access_denied'));
        }
    }

    public function get_schemes(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->rest->get_scheme_lists($_POST['SchTyp']);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function access_denied(){
        $data['title'] = 'Access';
        $this->load->view('page403', $data);
    }

    public function config(){
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
            $status = "success";
            
            $configDt['MultiKitchen'] = $_POST['MultiKitchen'];
            $configDt['SchType'] = $_POST['SchType'];
            $configDt['pymtENV'] = $_POST['pymtENV'];
            $configDt['ServChrg'] = $_POST['ServChrg'];
            $configDt['DelCharge'] = $_POST['DelCharge'];
            $configDt['restBilling'] = $_POST['restBilling'];

            $configDt['MultiLingual'] = !isset($_POST['MultiLingual'])?0:1;
            $configDt['AutoDeliver'] = !isset($_POST['AutoDeliver'])?0:1;
            $configDt['EDT'] = !isset($_POST['EDT'])?0:1;
            $configDt['Move'] = !isset($_POST['Move'])?0:1;
            $configDt['JoinTable'] = !isset($_POST['JoinTable'])?0:1;
            $configDt['SchPop'] = !isset($_POST['SchPop'])?0:1;
            $configDt['TableReservation'] = !isset($_POST['TableReservation'])?0:1;
            $configDt['Discount'] = !isset($_POST['Discount'])?0:1;
            $configDt['MultiPayment'] = !isset($_POST['MultiPayment'])?0:1;
            $configDt['Tips'] = !isset($_POST['Tips'])?0:1;
            $configDt['CustLoyalty'] = !isset($_POST['CustLoyalty'])?0:1;
            $configDt['RtngDisc'] = !isset($_POST['RtngDisc'])?0:1;
            $configDt['TableAcceptReqd'] = !isset($_POST['TableAcceptReqd'])?0:1;
            $configDt['AutoSettle'] = !isset($_POST['AutoSettle'])?0:1;
            $configDt['AutoPrintKOT'] = !isset($_POST['AutoPrintKOT'])?0:1;
            $configDt['CustAssist'] = !isset($_POST['CustAssist'])?0:1;
            $configDt['Dispense_OTP'] = !isset($_POST['Dispense_OTP'])?0:1;
            $configDt['Ingredients'] = !isset($_POST['Ingredients'])?0:1;
            $configDt['NV'] = !isset($_POST['NV'])?0:1;
            $configDt['WelcomeMsg'] = !isset($_POST['WelcomeMsg'])?0:1;
            $configDt['billPrintTableNo'] = !isset($_POST['billPrintTableNo'])?0:1;
            $configDt['Bill_KOT_Print'] = !isset($_POST['Bill_KOT_Print'])?0:1;
            $configDt['sitinKOTPrint'] = !isset($_POST['sitinKOTPrint'])?0:1;
            $configDt['Ing_Cals'] = !isset($_POST['Ing_Cals'])?0:1;
            $configDt['Ent'] = !isset($_POST['Ent'])?0:1;

            $configDt['GSTInclusiveRates'] = !isset($_POST['GSTInclusiveRates'])?0:1;
            $configDt['Seatwise'] = !isset($_POST['Seatwise'])?0:1;
            $configDt['BillMergeOpt'] = !isset($_POST['BillMergeOpt'])?0:1;
            $configDt['billSplitOpt'] = !isset($_POST['billSplitOpt'])?0:1;
            $configDt['DeliveryOTP'] = !isset($_POST['DeliveryOTP'])?0:1;
            $configDt['Charity'] = !isset($_POST['Charity'])?0:1;
            $configDt['IMcCdOpt'] = !isset($_POST['IMcCdOpt'])?0:1;
            $configDt['tableSharing'] = !isset($_POST['tableSharing'])?0:1;
            $configDt['recommend'] = !isset($_POST['recommend'])?0:1;
            $configDt['addItemLock'] = !isset($_POST['addItemLock'])?0:1;

            updateRecord('Config', $configDt, array('EID' => $EID) );
            $response = 'Config Upadated';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        $data['title'] = 'Config';
        $data['detail'] = getRecords('Config',array('EID' => $EID));
        $this->load->view('rest/config', $data);
    }

    public function prepaid_account(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $EID = authuser()->EID;
            $CountryCd = $this->session->userdata('CountryCd');
            $this->session->set_userdata('pCountryCd', $CountryCd);
            $status = "success";

            switch ($_POST['type']) {
                case 'pdata':

                    $cusList['EID']         = $EID;
                    $cusList['MaxLimit']    = $_POST['MaxLimit'];
                    $cusList['prePaidAmt']  = $_POST['prePaidAmt'];
                    $cusList['custType']    = $_POST['custType'];
                    if($_POST['acNo'] > 0){
                        $response = 'Prepaid Account Updated.';
                        updateRecord('CustList', $cusList, array('acNo' => $_POST['acNo']));
                    }else{

                        $CountryCd              = $_POST['countryCd'];
                        $this->session->set_userdata('pCountryCd', $CountryCd);

                        $cusList['MobileNo']    = $CountryCd.$_POST['MobileNo'];
                        $cusList['CustId']      = createCustUser($_POST['MobileNo']);
                        $chk = getRecords('CustList', array('EID' => $EID, 'CustId' => $cusList['CustId']));
                        if(empty($chk)){
                            insertRecord('CustList', $cusList);
                            $response = 'Prepaid Account Inserted.';
                            $RC['CustId'] = $cusList['CustId'];
                            $RC['Amount'] = $cusList['prePaidAmt'];
                            $RC['Remarks'] = 'Added';
                            rechargeHistory($RC);
                        }else{
                            $status = 'error';
                            $response = 'User already exists!!';
                        }
                    }

                break;

                case 'file_data':
                    
                    $folderPath = 'uploads/e'.$EID.'/csv';
                    if (!file_exists($folderPath)) {
                        // Create the directory
                        mkdir($folderPath, 0777, true);
                        chmod($folderPath, 0777);
                    }
                    // remove all files inside this folder uploads/qrcode/
                    $filesPath = glob($folderPath.'/*'); // get all file names
                    foreach($filesPath as $file){ // iterate files
                      if(is_file($file)) {
                        unlink($file); // delete file
                      }
                    }  
                    // end remove all files inside folder
                    $flag = 0;
                    if(isset($_FILES['prepaid_files']['name']) && !empty($_FILES['prepaid_files']['name'])){ 
                        $files = $_FILES['prepaid_files'];
                        $allowed = array('csv');
                        $filename_c = $_FILES['prepaid_files']['name'];
                        $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) {
                            $flag = 1;
                            $this->session->set_flashdata('error','Support only CSV format!');
                        }
                        // less than 1mb size upload
                        if($files['size'] > 1048576){
                            $flag = 1;
                            $this->session->set_flashdata('error','File upload less than 1MB!');   
                        }
                        $_FILES['prepaid_files']['name']= $files['name'];
                        $_FILES['prepaid_files']['type']= $files['type'];
                        $_FILES['prepaid_files']['tmp_name']= $files['tmp_name'];
                        $_FILES['prepaid_files']['error']= $files['error'];
                        $_FILES['prepaid_files']['size']= $files['size'];
                        $file = $files['name'];

                        if($flag == 0){
                            $res = do_upload('prepaid_files',$file,$folderPath,'*');
                            if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {

                                $itemData = [];
                                $temp = [];
                                $count = 1;
                                $checker = 0;
                                $not10digit = '';
                                while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                                    
                                    if($csv_data[0] !='MobileNo'){
                                     
                                        $checker = 1;
                                        $MobileNo = substr($csv_data[0], -10);

                                        if(strlen($MobileNo) == 10 ){
                                            $c_len = strlen($csv_data[0]) - strlen($MobileNo);

                                            $CountryCd = substr($csv_data[0], 0, $c_len);
                                            $this->session->set_userdata('CountryCd', $CountryCd);

                                            $temp['CustId'] = createCustUser($MobileNo);

                                            $temp['MobileNo'] = $CountryCd.$MobileNo;
                                            $temp['MaxLimit'] = $csv_data[1];
                                            $temp['prePaidAmt'] = $csv_data[2];
                                            $custType = '';

                                            if($csv_data[3] == 'OnAccount' || $csv_data[3] == 'onaccount'){
                                                $custType = 1;
                                            }

                                            if($csv_data[3] == 'Prepaid' || $csv_data[3] == 'prepaid'){
                                                $custType = 2;
                                            }

                                            if($csv_data[3] == 'Corporate' || $csv_data[3] == 'corporate'){
                                                $custType = 3;
                                            }

                                            $temp['custType'] = $custType;
                                            $temp['EID'] = $EID;
                                            $itemData[] = $temp;
                                        }else{
                                            $not10digit .=  $MobileNo.',';
                                        }
                                    }
                                    $count++;    
                                }

                                if(!empty($itemData)){
                                    $this->db2->insert_batch('tempCustList', $itemData);
                                    $cList = $this->db2->query('SELECT * , COUNT(MobileNo) as total_count FROM tempCustList GROUP BY MobileNo HAVING total_count = 1')->result_array();

                                    $eList = $this->db2->query('SELECT MobileNo, COUNT(MobileNo) as total_count FROM tempCustList GROUP BY MobileNo HAVING total_count > 1')->result_array();

                                    if(!empty($cList)){
                                        foreach ($cList as $key) {
                                            
                                            $chk = getRecords('CustList', array('EID' => $key['EID'], 'CustId' => $key['CustId']));
                                            if(empty($chk)){
                                                $dt['CustId'] = $key['CustId'];
                                                $dt['EID'] = $key['EID'];
                                                $dt['MobileNo'] = $key['MobileNo'];
                                                $dt['MaxLimit'] = $key['MaxLimit'];
                                                $dt['prePaidAmt'] = $key['prePaidAmt'];
                                                $dt['custType'] = $key['custType'];
                                                $dt['Stat'] = $key['Stat'];
                                                insertRecord('CustList', $dt);
                                            }else{
                                                
                                                $CustId = $key['CustId'];
                                                $EID = $key['EID'];
                                                $prePaidAmt = $key['prePaidAmt'];
                                                $this->db2->query("UPDATE CustList SET prePaidAmt= prePaidAmt + $prePaidAmt WHERE EID = $EID and CustId = $CustId");
                                            }
                                            deleteRecord('tempCustList', array('EID' => $key['EID'], 'CustId' => $key['CustId']));
                                        }
                                    }

                                    $response = 'Data inserted successfully.';
                                    if(!empty($eList)){
                                        $d = '';
                                        foreach ($eList as $key) {
                                            $d .= "\n ".$key['MobileNo'];
                                        }
                                        $response = "\n Data inserted successfully \n Except for following numbers $d \n Please insert the repeated above numbers manually!!";
                                    }

                                }

                                if(!empty($not10digit)){
                                    $response .= "\n ".$not10digit.' Mobile No must be more than 10 digits with country code';
                                }
                             
                                fclose($open);
                            }
                        }
                      }

                break;
                
            }
                
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Prepaid Amount';
        $data['prepaids'] = $this->db2->get('CustList')->result_array();
        $data['country'] = $this->rest->getCountries();
        $this->load->view('rest/prepaid_account', $data);
    }

    public function prepaid_recharge(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){
            $EID = authuser()->EID;
            $CountryCd = $this->session->userdata('CountryCd');
            $this->session->set_userdata('pCountryCd', $CountryCd);
            $status = "success";        

            $RC['CustId']   = $_POST['CustId'];
            $RC['Amount']   = $_POST['prePaidAmt'];
            $RC['Remarks']  = 'Added';
            rechargeHistory($RC);
            $response = 'Prepaid Recharge Inserted.';

            $acc = $this->db2->get_where('CustList', array('CustId' => $_POST['CustId']))->row_array();
            
            $dt['prePaidAmt'] = $acc['prePaidAmt'] + $_POST['prePaidAmt'];
            updateRecord('CustList', $dt, array('CustId' => $_POST['CustId']));
            
                
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Prepaid Recharge';
        $data['users'] = $this->db2->get('CustList')->result_array();
        $this->load->view('rest/prepaid_recharge', $data);
    }

    public function mobile_update_by_billid(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        
        if($this->input->method(true)=='POST'){

            $CountryCd = $_POST['CountryCd'];
            $this->session->set_userdata('pCountryCd', $_POST['CountryCd']);
            
            $CustId = createCustUser($_POST['mobile']);

            $mobile = $CountryCd.$_POST['mobile'];
            $billId  = $_POST['mobileBillId'];

            updateRecord('Billing', array('CustId' => $CustId, 'CellNo' => $mobile ), array('EID' => $_POST['mobileEID'], 'BillId' => $billId, 'CNo' => $_POST['mobileCNo']));

            $logs = array('custId' => $CustId, 'loginId' => authuser()->RUserId, 'remarks' => "MobileNo : $mobile updated in billId : $billId");
            logHistory($logs);

            $status = 'success';
            $response = 'Mobile No Updated.';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }        
    }

    public function discount_category(){
        // $this->check_access();
        $data['languages'] = langMenuList();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $updateData = $_POST;

            if($_POST['discId'] > 0){
                updateRecord('discounts', $updateData, array('discId' => $_POST['discId']));
                $response = 'Updated Records'; 
            }else{
                unset($updateData['discId']);
                $updateData['EID'] = authuser()->EID;
                insertRecord('discounts', $updateData);
                $response = 'Insert Records'; 
            }

            $status = 'success';
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('discount').' '.$this->lang->line('category');
        $data['discounts'] = $this->rest->getDiscountCategory();

        $this->load->view('rest/discountCategory', $data);    
    }

    public function loyalty(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $loyal['Name'] = $_POST['Name'];
            $loyal['MinPaidValue'] = $_POST['MinPaidValue'];
            $loyal['MaxPointsUsage'] = $_POST['MaxPointsUsage'];
            $loyal['billUsagePerc'] = $_POST['billUsagePerc'];
            $loyal['AcrossOutlets'] = $_POST['AcrossOutlets'];
            $loyal['EatOutLoyalty'] = $_POST['EatOutLoyalty'];
            $loyal['Validity'] = $_POST['Validity'];
            $loyal['Currency'] = $_POST['Currency'];
            $loyal['Stat'] = 0;
            $LNo = insertRecord('LoyaltyConfig', $loyal);
            if(!empty($LNo)){
                for ($i=0; $i < sizeof($_POST['PointsValue']); $i++) { 
                    $loyalDet['PointsValue'] = $_POST['PointsValue'][$i];
                    $loyalDet['PymtMode'] = $_POST['PymtMode'][$i];
                    $loyalDet['Stat'] = 0;
                    $loyalDet['LNo'] = $LNo;
                     insertRecord('LoyaltyConfigDet', $loyalDet);
                }
                $status = 'success';
                $response = 'Loyality Added.';
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die; 
        }
        $data['title'] = 'Loyality';
        $data['modes'] = $this->rest->getPaymentModes();
        $data['lists'] = $this->rest->getLoyalities();
        $data['currency'] = $this->rest->getCurrency();

        $this->load->view('rest/loyality', $data); 
    }

    public function get_rest_list(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->supp->getRestList();

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }
    }

    public function updateLoyalityStats(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $stat = ($_POST['Stat'] == 1)?0:1;
            updateRecord('LoyaltyConfig', array('Stat' => $stat), array('LNo' => $_POST['LNo']));
            
            $status = "success";
            $response = 'Status Updated.';

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }
    }

    public function getfood_by_ctype(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            $status = "success";
            $response = $this->rest->getFoodTypeByCTyp($_POST['CTyp']);

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }
    }

    public function toppings(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $mi['Name1'] = $_POST['itemName'];
            $mi['Name2'] = $_POST['itemName'];
            $mi['FID'] = $_POST['FID'];
            $mi['Stat']    = 5;
            $mi['EID']     = authuser()->EID;
            $mi['ChainId'] = authuser()->ChainId;
            $mi['LoginCd'] = authuser()->RUserId;
            $ItemId = insertRecord('MenuItem', $mi);

            if(!empty($ItemId)){
                $menuRates = [];
                $tempData = [];
                for ($i=0; $i < sizeof($_POST['sections']); $i++) { 
                    $tempData['EID'] = authuser()->EID;
                    $tempData['ChainId'] = authuser()->ChainId;
                    $tempData['ItemId'] = $ItemId;
                    $tempData['SecId'] = $_POST['sections'][$i];
                    $tempData['Itm_Portion'] = $_POST['portions'][$i];
                    $tempData['OrigRate'] = 0;
                    $tempData['ItmRate'] = $_POST['price'][$i];
                    $menuRates[] = $tempData;
                }
                  $this->db2->insert_batch('MenuItemRates', $menuRates);
            }

            $status = "success";
            $response = 'Topping added.';

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }

        $data['title'] = 'Toppings';
        $data['EatSections'] = $this->rest->get_eat_section();
        $data['ItemPortions'] = $this->rest->get_item_portion();
        $data['toppings'] = $this->rest->getToppings();
        $data['foodType'] = $this->rest->get_foodType();
        $this->load->view('rest/topping', $data);
    }

    public function edit_toppings($ItemId){
        $data['ItemId'] = $ItemId;
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            
            if(!empty($ItemId)){
                updateRecord('MenuItem', array('FID' => $_POST['FID']), array('ItemId' => $ItemId, 'EID' => authuser()->EID));
                $tempData = [];
                for ($i=0; $i < sizeof($_POST['sections']); $i++) { 

                    $irno = $_POST['irno'][$i];
                    $tempData['EID'] = authuser()->EID;
                    $tempData['ChainId'] = authuser()->ChainId;
                    $tempData['ItemId'] = $ItemId;
                    $tempData['SecId'] = $_POST['sections'][$i];
                    $tempData['Itm_Portion'] = $_POST['portions'][$i];
                    $tempData['OrigRate'] = 0;
                    $tempData['ItmRate'] = $_POST['price'][$i];
                    
                    if($irno > 0){
                        updateRecord('MenuItemRates', $tempData, array('ItemId' => $ItemId, 'IRNo' => $irno));
                    }else{
                        insertRecord('MenuItemRates', $tempData);
                    }
                }
            }

            $status = "success";
            $response = 'Topping updated.';

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }

        $data['title'] = 'Edit Toppings';
        $data['EatSections'] = $this->rest->get_eat_section();
        $data['ItemPortions'] = $this->rest->get_item_portion();
        $data['toppings'] = $this->rest->getToppingDetail($ItemId);
        $data['itmRates'] = $this->rest->getItemRatesByItemId($ItemId);
        $data['foodType'] = $this->rest->get_foodType();
        $this->load->view('rest/edit_topping', $data);
    }

    public function get_item_portion_by_itemId(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = "success";
            $response = $this->rest->getItemRatesByItemId($_POST['ItemId']);

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }        
    }


    private function food_bar_separate_bill($postData){
        
        $MergeNo    = $postData['MergeNo'];
        $CNo        = $postData['MCNo'];
        $per_cent   = 1;
        $EID        = authuser()->EID;

        $paymentMode = 'RestSplitBill';
        $postData['TipAmount'] =0;
        $cust_discount = 0;
        $billingObjStat = 5;
        $CellNo = $postData['mobile'][0];
        $CountryCd = $postData['CountryCd'][0];
        $this->session->set_userdata('pCountryCd', $CountryCd);
        $postData['CellNo'] = $CountryCd.$CellNo;
        $CustId = createCustUser($CellNo);
        $postData['CustId'] = $CustId;
        $EType = $this->session->userdata('EType');
        $ChainId = authuser()->ChainId;
        // $ONo = $this->session->userdata('ONo');
        $CustNo = $this->session->userdata('CustNo');
        $COrgId = $this->session->userdata('COrgId');
        $strMergeNo = "'".$MergeNo."'";        

        $SchType = $this->session->userdata('SchType');
        for ($CTyp=0; $CTyp <=1 ; $CTyp++) { 
            if(in_array($SchType, array(1,3))){
                $this->rest->updateBillDiscountAmount($MergeNo);
            }

        $kitcheData = $this->rest->fetchBiliingData_CTyp($EID, $CNo, $MergeNo, $per_cent, $CTyp);
        
        if (empty($kitcheData)) {
                $response = [
                    "status" => 0,
                    "msg" => "NO BILL CREATION REQUIRED "
                ];
            } else {
                // order amt
                    $initil_value = $kitcheData[0]['TaxType'];
                    $orderAmt = 0;
                    $discount = 0;
                    $charge = 0;
                    $total = 0;

                    $TaxRes = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);
                    $taxDataArray = $TaxRes['taxDataArray'];

                    foreach ($kitcheData as $kit ) {
                        $orderAmt = $orderAmt + $kit['OrdAmt'];
                    }

                    //tax calculate
                    $SubAmtTax = 0;
                    foreach ($taxDataArray as $tax) {
                        foreach ($tax as $key) {
                            if($key['Included'] >= 5){
                                $SubAmtTax = $SubAmtTax + round($key['SubAmtTax'], 2);
                            }
                        }
                    }
                        
                    $orderAmt = $orderAmt + $SubAmtTax;
                    $postData["itemTotalGross"] = $orderAmt;
                    
                    $discount = $kitcheData[0]['TotItemDisc'] + $kitcheData[0]['RtngDiscAmt'] + $kitcheData[0]['BillDiscAmt']; 
                    $charge = $kitcheData[0]['TotPckCharge'] + $kitcheData[0]['DelCharge'];
                    
                    $srvCharg = ($orderAmt * $kitcheData[0]['ServChrg']) / 100;
                    $total = $orderAmt + $srvCharg + $charge - $discount;
                    
                    $postData["orderAmount"] = $total;
                    $postData["TableNo"] = $kitcheData[0]['TableNo'];
                    $postData["cust_discount"] = 0;
                    $totalAmount = $postData["orderAmount"];
                // end of order amt

                $res = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo, $per_cent);

                $lastBillNo = $this->db2->query("SELECT max(BillNo) as BillNo from Billing where EID = $EID")->row_array();

                if ($lastBillNo['BillNo'] == '') {
                    $newBillNo = 1;
                } else {
                    $newBillNo = $lastBillNo['BillNo'] + 1;
                }

                $TotItemDisc    = $kitcheData[0]['TotItemDisc'];
                $TotPckCharge   = $kitcheData[0]['TotPckCharge'];
                $DelCharge      = $kitcheData[0]['DelCharge'];
                $BillDiscAmt    = $kitcheData[0]['BillDiscAmt'];
                
                $splitTyp = 0; 
                $splitPercent = 1;
                if($paymentMode == 'Due' || $paymentMode == 'RestSplitBill'){
                    $TipAmount = $postData['TipAmount'];
                    $itemTotalGross = $postData['itemTotalGross'];
                    $splitTyp = $postData['splitType']; 
                    $splitPercent = $per_cent;
                }else{
                    $TipAmount = $this->session->userdata('TipAmount');
                    $itemTotalGross = $this->session->userdata('itemTotalGross');
                }
                
                // FOR ONLINE PAYMENTS
                $billingObj['CTyp'] = $CTyp;
                $billingObj['EID'] = $EID;
                $billingObj['TableNo'] = $kitcheData[0]['TableNo'];
                $billingObj['MergeNo'] = $kitcheData[0]['MergeNo'];
                $billingObj['ChainId'] = $ChainId;
                // $billingObj['ONo'] = $ONo;
                $billingObj['ONo'] = 0;
                $billingObj['CNo'] = $CNo;
                $billingObj['BillNo'] = $newBillNo;
                $billingObj['COrgId'] = $COrgId;
                $billingObj['CustNo'] = $CustNo;
                $billingObj['TotAmt'] = $itemTotalGross;
                $billingObj['PaidAmt'] = $totalAmount;
                $billingObj['SerCharge'] = $kitcheData[0]['ServChrg'];
                $billingObj['SerChargeAmt'] = round(($itemTotalGross * $kitcheData[0]['ServChrg']) /100 ,2);
                $billingObj['Tip'] = $TipAmount;
                $billingObj['TotItemDisc'] = $TotItemDisc;
                $billingObj['BillDiscAmt'] = $BillDiscAmt;
                $billingObj['custDiscAmt'] = $cust_discount;
                $billingObj['TotPckCharge'] = $TotPckCharge;
                $billingObj['DelCharge'] = $DelCharge;
                $billingObj['Stat'] = $billingObjStat;
                if($paymentMode == 'Due' || $paymentMode == 'RestSplitBill'){
                    $billingObj['CellNo'] = $postData['CellNo'];
                    $billingObj['CustId'] = $postData['CustId'];
                }else{
                    $billingObj['CellNo'] = $kitcheData[0]['CellNo'];
                    $billingObj['CustId'] = $kitcheData[0]['CustId'];
                }
                $billingObj['splitTyp'] = $splitTyp;
                $billingObj['splitPercent'] = $splitPercent;
                $billingObj['OType'] = $kitcheData[0]['OType'];
                $billingObj['LoginCd'] = $kitcheData[0]['LoginCd'];

                $discountDT = array();
                if($this->session->userdata('Discount') > 0){
                    $discountDT = getDiscount($billingObj['CustId']);
                    if(!empty($discountDT)){
                        $billingObj['discPcent'] = $discountDT['pcent'];
                        $billingObj['discId'] = $discountDT['discId'];
                        $gt = $totalAmount / (100 - $discountDT['pcent']) * 100;
                        // $billingObj['autoDiscAmt'] = ($gt * $discountDT['pcent'])/100;
                        $billingObj['autoDiscAmt'] = ($billingObj['PaidAmt'] * $discountDT['pcent'])/100;
                        $billingObj['PaidAmt'] = $billingObj['PaidAmt'] - $billingObj['autoDiscAmt'];
                    }
                }

                $billingObj['PaidAmt'] = round($billingObj['PaidAmt']);
                
                $this->db2->trans_start();

                    if(($EType == 1) && ($EDTs > 0)){
                        $edtMax = $this->db2->select('MCNo, ItemId, EDT, max(EDT) as EDT')
                                        ->get_where('Kitchen',array('MCNo' => $CNo, 'EID' => $EID,'Stat' => 2))->row_array();
                        if(!empty($edtMax)){
                            updateRecord('Kitchen', array('EDT' => $edtMax['EDT']), array('MCNo' => $CNo, 'EID' => $EID) );
                        }
                    }
            
                    $lastInsertBillId = insertRecord('Billing', $billingObj);

                    $genTblDb = $this->load->database('GenTableData', TRUE);
                    
                    if(!empty($lastInsertBillId)){
                        // gen db
                        $kitchenSale = $this->db2->select("b.BillId, k.ItemId, k.Qty, k.Itm_Portion, k.OType, k.TA, k.EID, m.UItmCd")
                                    ->join('KitchenMain km', '(km.CNo = b.CNo or km.MCNo = b.CNo)', 'inner')
                                    ->join('Kitchen k', 'k.MCNo = km.MCNo', 'inner')
                                    ->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
                                    ->where_in('k.Stat', array(2,3))
                                    ->get_where('Billing b', array(
                                                'b.EID' => $EID,
                                                'km.EID' => $EID,
                                                'k.EID' => $EID,
                                                'm.EID' => $EID,
                                                'b.BillId' => $lastInsertBillId)
                                                )
                                    ->result_array();
                        if(!empty($kitchenSale)){
                            $kitchenSaleObj = [];
                            $temp = [];
                            foreach ($kitchenSale as $key) {
                                $temp['ItemId'] = $key['ItemId'];
                                $temp['BillId'] = $key['BillId'];
                                $temp['IPCd'] = $key['Itm_Portion'];
                                $temp['Quantity'] = $key['Qty'];
                                $temp['EID'] = $key['EID'];
                                $temp['OType'] = $key['OType'];
                                $temp['TakeAway'] = $key['TA'];
                                $temp['UItmCd'] = $key['UItmCd'];
                                $temp['Created_at'] = date('Y-m-d H:i:s');

                                $kitchenSaleObj[] = $temp;
                            }

                            if(!empty($kitchenSaleObj)){
                                $genTblDb->insert_batch('KitchenSale', $kitchenSaleObj); 
                            }
                        }
                        // end of gen db

                        if($EType == 5){
                            $this->db2->where_in('Stat', array(1,2));
                            $this->db2->update('Kitchen',array('Stat' => 7),array('EID' => $EID, 'MCNo' => $CNo));
                        }
                        // for etype=1 entire cart goes for checkout
                    }

                    foreach ($res['taxDataArray'] as $key => $value1) {
                        foreach ($value1 as $key => $value) {
                            $BillingTax['BillId'] = $lastInsertBillId;
                            $BillingTax['MCNo'] = $CNo;
                            $BillingTax['TNo'] = $value['TNo'];
                            $BillingTax['TaxPcent'] = $value['TaxPcent'];
                            $BillingTax['TaxAmt'] = $value['SubAmtTax'];
                            $BillingTax['EID'] = $EID;
                            $BillingTax['TaxIncluded'] = $value['Included'];
                            $BillingTax['TaxType'] = $value['TaxType'];
                            insertRecord('BillingTax', $BillingTax);
                        }
                    }

                    // store to gen db whenever bill generated

                    $custPymtObj['BillId']      = $lastInsertBillId;
                    $custPymtObj['CustId']      = $CustId;
                    $custPymtObj['BillNo']      = $lastInsertBillId;
                    $custPymtObj['EID']         = $EID;
                    $custPymtObj['aggEID']      = $EID;
                    $custPymtObj['PaidAmt']     = $totalAmount;
                    $custPymtObj['PaymtMode']   = $paymentMode;
                    $genTblDb->insert('CustPymts', $custPymtObj);

                $this->db2->trans_complete();

                $this->session->set_userdata('KOTNo', 0);
                $this->session->set_userdata('CNo', 0);
                $this->session->set_userdata('itemTotalGross', 0);

                if(!empty($lastInsertBillId)){
                    $this->session->set_userdata('billFlag',1);
                }
                $response = [
                    "status" => 1,
                    "msg" => "Bill Generated",
                    "billId" => $lastInsertBillId
                ];

            }
        } // end for loop
        // redirect(base_url('restaurant/split_bills'));

    }

    public function get_portion_itemtype(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $status = "success";
            $response = $this->rest->get_portion_item_type($_POST);

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }        
    }

    public function check_bill_based_offer(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){
            $MergeNo = $_POST['MergeNo'];
            $EID = authuser()->EID;
            $EType = $this->session->userdata('EType');
            $stat = ($EType == 5)?3:2;

            $status = "success";
            $response = [];
            $SchType = $this->session->userdata('SchType');

            if(in_array($SchType, array(1,3))){

                $chkOfferKitMain = $this->db2->query("select SchCd from KitchenMain where SchCd = 0 and MergeNo = $MergeNo and EID = $EID and BillStat = 0")->row_array();
                                    
                if(!empty($chkOfferKitMain)){

                    $rates = ($SchType == 3)?'ItmRate':'OrigRate';

                    $orderValue = $this->db2->query("select sum(Qty *  $rates) as itmValue from Kitchen where MergeNo = $MergeNo and Stat = $stat and BillStat =0 and EID =$EID")->row_array();

                    $langId = $this->session->userdata('site_lang');
                    $scName = "c.SchNm$langId as SchNm";

                    $response = $this->db2->select("$scName, c.offerType, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.DiscItemPcent, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId, cod.IPCd, cod.Disc_CID, cod.Disc_MCatgId, cod.Disc_ItemId, cod.Disc_ItemTyp, cod.Disc_IPCd, if(cod.Disc_ItemId > 0,(select Name1 from MenuItem where ItemId = cod.Disc_ItemId),'-') as itemName, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent ")
                                ->order_by('cod.MinBillAmt', 'DESC')
                                ->join('CustOffers c', 'c.SchCd = cod.SchCd', 'inner')
                                ->get_where('CustOffersDet cod', 
                                 array('cod.MinBillAmt < ' => $orderValue['itmValue'],
                                    'c.SchTyp' => 1,
                                    'c.EID' => $EID,
                                    'c.Stat' => 0))
                                ->result_array();

                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }
    }

    public function billBasedOfferUpdate(){
        $res = '';
        $status = 'error';
        $EID = authuser()->EID;
        
        if($this->input->method(true)=='POST'){
            
            $status = 'success';
            $MergeNo = $_POST['MergeNo'];

            $SchCd  = $_POST['SchCd'];
            $SDetCd = $_POST['sdetcd'];

            $langId = $this->session->userdata('site_lang');
            $scName = "c.SchNm$langId as SchNm";
            $billOfferAmt = $this->db2->select("$scName, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.Disc_ItemId, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent, cod.DiscItemPcent")
                        ->order_by('cod.MinBillAmt', 'DESC')
                        ->join('CustOffers c', 'c.SchCd = cod.SchCd')
                        ->get_where('CustOffersDet cod', 
                         array('cod.SchCd' => $SchCd,
                            'cod.SDetCd' => $SDetCd,
                            'c.SchTyp' => 1,
                            'c.EID' => $EID, 
                            'c.Stat' => 0))
                        ->row_array();

                if(!empty($billOfferAmt)){

                    $kitdata = array(
                                 'SchCd' => $billOfferAmt['SchCd'],
                                 'SDetCd' => $billOfferAmt['SDetCd'],
                                 'BillDiscAmt' => $billOfferAmt['Disc_Amt'],
                                 'BillDiscPcent' => $billOfferAmt['Disc_pcent']
                                 );

                    $whr = "MergeNo = $MergeNo and EID = $EID and BillStat = 0";
                    $this->db2->where($whr);
                    $this->db2->update('KitchenMain', $kitdata);   
                                    
                     if($billOfferAmt['Disc_ItemId'] > 0 || ($_POST['ItemId'] > 0 ) ){
                        $whre = "MergeNo = $MergeNo";
                        $kitcheData = $this->db2->order_by('OrdNo', 'ASC')
                                                ->where($whre)
                                                ->get_where('Kitchen', array('EID' => $EID, 'BillStat' => 0))
                                                ->row_array();
                        $Disc_ItemId = $billOfferAmt['Disc_ItemId'];
                        $Disc_IPCd   = $billOfferAmt['Disc_IPCd'];
                        $Disc_Qty    = $billOfferAmt['Disc_Qty'];
                        if($_POST['ItemId'] > 0){
                            $Disc_ItemId = $_POST['ItemId'];
                            $Disc_IPCd   = $_POST['ipcd'];
                            $Disc_Qty    = ($Disc_Qty > 1)?$Disc_Qty:1;
                        }
                        $ChainId     = $kitcheData['ChainId'];
                        $TableNo     = $kitcheData['TableNo'];

                        $offerRates = $this->db2->query("select mir.Itm_Portion, mir.OrigRate, mc.TaxType, mi.PckCharge, mi.ItemTyp, mi.KitCd, mc.MCatgId from MenuItemRates as mir, MenuItem mi, MenuCatg mc where mi.EID = mir.EID and mc.EID=mir.EID and mi.ItemId = mir.ItemId and mc.MCatgId = mi.MCatgId and mir.EID = $EID and mir.ItemId = $Disc_ItemId and mir.Itm_Portion = $Disc_IPCd and mir.SecId = (select SecId from Eat_tables where TableNo = $TableNo and EID = $EID)")->row_array();
                        if(!empty($offerRates)){
                            $offerRate = $offerRates['OrigRate'] -  ($offerRates['OrigRate'] * $billOfferAmt['DiscItemPcent'] / 100);
                            $offerOrigRate = $offerRates['OrigRate'];

                            $kitchenObj['KitCd']        = $offerRates['KitCd'];
                            $kitchenObj['ItemTyp']      = $offerRates['ItemTyp'];
                            $kitchenObj['Itm_Portion']  = $offerRates['Itm_Portion'];
                            $kitchenObj['TaxType']      = $offerRates['TaxType'];

                            $kitchenObj['TA'] = 0;
                            $kitchenObj['PckCharge']    = 0;
                            $kitchenObj['OType'] = $kitcheData['OType'];
                            if($kitchenObj['OType'] == 105){
                                $kitchenObj['TA'] = 1;
                                $kitchenObj['PckCharge']    = $offerRates['PckCharge'];
                            }
   
                            $newUKOTNO = date('dmy_') . $kitchenObj['KitCd'] . "_" . $kitcheData['KOTNo'] . "_" . $kitcheData['FKOTNo'];

                            $kitchenObj['CNo']    = $kitcheData['CNo'];
                            $kitchenObj['MCNo']   = $kitcheData['MCNo'];
                            $kitchenObj['CustId'] = $kitcheData['CustId'];
                            $kitchenObj['COrgId'] = $kitcheData['COrgId'];
                            $kitchenObj['CustNo'] = $kitcheData['CustNo'];
                            $kitchenObj['CellNo'] = $kitcheData['CellNo'];
                            $kitchenObj['EID']    = $kitcheData['EID'];
                            $kitchenObj['ChainId']= $kitcheData['ChainId'];
                            $kitchenObj['ONo']    = $kitcheData['ONo'];
                            $kitchenObj['FKOTNo'] = $kitcheData['FKOTNo'];
                            $kitchenObj['KOTNo']  = $kitcheData['KOTNo'];
                            $kitchenObj['UKOTNo'] = $newUKOTNO;         //date('dmy_').$KOTNo;
                            $kitchenObj['TableNo']= $kitcheData['TableNo'];
                            $kitchenObj['MergeNo']= $kitcheData['MergeNo'];
                            $kitchenObj['CustRmks']= 'bill based offer';
                            $kitchenObj['DelTime']= date('Y-m-d H:i:s');
                            $kitchenObj['Stat']   = $kitcheData['Stat'];
                            $kitchenObj['LoginCd']= $kitcheData['CustId'];

                            $kitchenObj['ItemId']       = $Disc_ItemId;
                            $kitchenObj['Itm_Portion']  = $Disc_IPCd;
                            $kitchenObj['Qty']          = $Disc_Qty;
                            $kitchenObj['ItmRate']      = $offerRate;
                            $kitchenObj['OrigRate']     = $offerOrigRate;
                                
                            $kitchenObj['SchCd']  = $billOfferAmt['SchCd'];
                            $kitchenObj['SDetCd'] = $billOfferAmt['SDetCd'];
                            
                            insertRecord('Kitchen', $kitchenObj);   
                        }

                     }
                }
           
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $res
              ));
             die;
        }
    }

    public function get_selection_offer(){
        $status = 'error';
        $response = "Something went wrong please try agian!";
        if($this->input->method(true)=='POST'){
            $response = "Selected Offer Does Not Satisfy Criteria!!";
            $langId = $this->session->userdata('site_lang');
            $scName = "c.SchNm$langId as SchNm";
            $scDesc = "cod.SchDesc$langId as SchDesc";
            
            $EType = $this->session->userdata('EType');
            $stat = ($EType == 5)?3:2;

            $MergeNo = $_POST['MergeNo'];
            $MinBillAmt = $_POST['minbillamt'];
            $EID = authuser()->EID;

            $whr = "";
            if($_POST['offerType'] > 0){
                switch ($_POST['offerType']) {
                    // food
                    case '1':
                        $whr = " and mi.CID NOT IN (10, 0)";
                        break;
                        // food & bar
                    case '2':
                        $whr = "";
                        break;
                        // bar
                    case '3':
                        $whr = " and mi.CID = 10";
                        break;
                }
            }

            if($_POST['itemtyp'] == 4){
                $ItemTyp = $_POST['itemtyp'];
                $whr .= " and k.ItemTyp = $ItemTyp";
                if($_POST['ipcd'] > 0){
                    $Itm_Portion = $_POST['ipcd'];
                    $whr .= " and  k.Itm_Portion = $Itm_Portion";
                }
            }

            if($_POST['cid'] > 0){
                $CID = $_POST['cid'];
                $whr .= " and mi.CID = $CID";
            }

            if($_POST['mcatgid'] > 0){
                $MCatgId = $_POST['mcatgid'];
                $whr .= " and mi.MCatgId = $MCatgId";
            }

            $price = $this->db2->query("SELECT sum(k.OrigRate * k.Qty) as total_amount from Kitchen k, MenuItem mi where k.MergeNo = $MergeNo and k.EID = $EID and k.BillStat = 0 and k.Stat= $stat and k.ItemId = mi.ItemId AND mi.EID = $EID $whr group by k.CNo")->row_array();
            
            $origRates = $price['total_amount'];
            if($origRates >= $MinBillAmt){
                $status = 'success';
                // dropdown list for item
                $flag = 0;
                if($_POST['disccid'] > 0){
                    $this->db2->where('mi.CID', $_POST['disccid']);
                    $flag = 1;
                }else if($_POST['discmcatgid'] > 0){
                    $flag = 1;
                    $this->db2->where('mi.MCatgId', $_POST['discmcatgid']);
                }else if($_POST['discitemid'] > 0){
                    $flag = 1;
                    $this->db2->where('mi.ItemId', $_POST['discitemid']);
                }else if($_POST['discitemtyp'] > 0){
                    $flag = 1;
                    $this->db2->where('mi.ItemTyp', $_POST['discitemtyp']);
                } 

                if($_POST['discipcd'] > 0){
                    $flag = 1;
                    $this->db2->where('mir.Itm_Portion', $_POST['discipcd']);
                }
                
                $response = [];
                if($flag > 0){
                    $langId = $this->session->userdata('site_lang');
                    $itemName = "mi.Name$langId as itemName";
                    $ipName = "ip.Name$langId as portionName";
                    $response = $this->db2->select("mi.ItemId, $itemName, ip.IPCd, $ipName")
                                    ->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
                                    ->join('ItemPortions ip', 'ip.IPCd = mir.Itm_Portion')
                                    ->get_where('MenuItem mi', array('mi.EID' => $EID, 'mir.EID' => $EID, 'mi.Stat' => 0, 'mir.OrigRate >' => 0))->result_array();
                                    
                }
            }
                            
          header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }   
    }

    public function update_status_of_offer(){
        $status = "error";
        $response = "Something went wrong! Try again later.";
        if($this->input->method(true)=='POST'){

            $status = "success";
            $Stat = ($_POST['Stat'] ==1)?0:1;
            $EID = authuser()->EID;
            $SchCd = $_POST['SchCd'];
            
            updateRecord('CustOffers', array('Stat' => $Stat), array('EID' => $EID, 'SchCd' => $SchCd));

            header('Content-Type: application/json');
            echo json_encode(array(
                    'status' => $status,
                    'response' => $response
                  ));
            die; 
        }
    }

    public function get_city_by_country(){

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            
            $response = $this->db2->get_where('city', array('phone_code' => $_POST['phone_code']) )->result_array();
            $status = 'success';

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
    }

    public function multi_lang_upload(){

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            
            $EID = authuser()->EID;
            $folderPath = 'uploads/e'.$EID.'/csv';
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, true);
            }
            // remove all files inside this folder uploads/qrcode/
            $filesPath = glob($folderPath.'/*'); // get all file names
            foreach($filesPath as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); // delete file
              }
            }

            $flag = 0;

            if(isset($_FILES['common_file']['name']) && !empty($_FILES['common_file']['name'])){ 

                $files = $_FILES['common_file'];
                $allowed = array('csv');
                $filename_c = $_FILES['common_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only CSV format!');
                }
                // less than 1mb size upload
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }
                $_FILES['common_file']['name']= $files['name'];
                $_FILES['common_file']['type']= $files['type'];
                $_FILES['common_file']['tmp_name']= $files['tmp_name'];
                $_FILES['common_file']['error']= $files['error'];
                $_FILES['common_file']['size']= $files['size'];
                $file = $files['name'];

                if($flag == 0){

                    $res = do_upload('common_file',$file,$folderPath,'*');
                    if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                        while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                            
                            if($csv_data[0] !='English'){
                                $langObj['Name2'] = $csv_data[1];
                                $langObj['Name3'] = $csv_data[2];
                                $langObj['Name4'] = $csv_data[3];

                                updateRecord($_POST['type'], $langObj, array('Name1' => $csv_data[0]));
                            }
                        }
                        fclose($open);
                        $status = 'success';
                        $response = 'Data Updated.';
                        $this->session->set_flashdata('success','Data Updated.');
                    }
                }
                
              }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Multi Language Name';
        $this->load->view('rest/multi_lang_name', $data);
    }

    public function menu_lang_upload(){

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){
            $status = 'success';
            
            $EID = authuser()->EID;
            $folderPath = 'uploads/e'.$EID.'/csv';
            if (!file_exists($folderPath)) {
                // Create the directory
                mkdir($folderPath, 0777, true);
            }
            // remove all files inside this folder uploads/qrcode/
            $filesPath = glob($folderPath.'/*'); // get all file names
            foreach($filesPath as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); // delete file
              }
            }

            $flag = 0;

            if(isset($_FILES['common_file']['name']) && !empty($_FILES['common_file']['name'])){ 

                $files = $_FILES['common_file'];
                $allowed = array('csv');
                $filename_c = $_FILES['common_file']['name'];
                $ext = pathinfo($filename_c, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $flag = 1;
                    $this->session->set_flashdata('error','Support only CSV format!');
                }
                // less than 1mb size upload
                if($files['size'] > 1048576){
                    $flag = 1;
                    $this->session->set_flashdata('error','File upload less than 1MB!');   
                }
                $_FILES['common_file']['name']= $files['name'];
                $_FILES['common_file']['type']= $files['type'];
                $_FILES['common_file']['tmp_name']= $files['tmp_name'];
                $_FILES['common_file']['error']= $files['error'];
                $_FILES['common_file']['size']= $files['size'];
                $file = $files['name'];

                if($flag == 0){

                    $res = do_upload('common_file',$file,$folderPath,'*');
                    if (($open = fopen($folderPath.'/'.$file, "r")) !== false) {
                        while (($csv_data = fgetcsv($open, 1000, ",")) !== false) {
                            
                            if($csv_data[0] !='ItemId'){
                                $langObj['Name1'] = $csv_data[1];
                                $langObj['Name2'] = $csv_data[2];
                                $langObj['Name3'] = $csv_data[3];
                                $langObj['Name4'] = $csv_data[4];

                                $langObj['ItmDesc1'] = $csv_data[5];
                                $langObj['ItmDesc2'] = $csv_data[6];
                                $langObj['ItmDesc3'] = $csv_data[7];
                                $langObj['ItmDesc4'] = $csv_data[8];

                                $langObj['Ingeredients1'] = $csv_data[9];
                                $langObj['Ingeredients2'] = $csv_data[10];
                                $langObj['Ingeredients3'] = $csv_data[11];
                                $langObj['Ingeredients4'] = $csv_data[12];

                                $langObj['Rmks1'] = $csv_data[13];
                                $langObj['Rmks2'] = $csv_data[14];
                                $langObj['Rmks3'] = $csv_data[15];
                                $langObj['Rmks4'] = $csv_data[16];

                                updateRecord('MenuItem', $langObj, array('ItemId' => $csv_data[0], 'EID' => $EID));
                            }
                        }
                        fclose($open);
                        $status = 'success';
                        $response = 'Data Updated.';
                        $this->session->set_flashdata('success','Data Updated.');
                    }
                }
                
              }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }

        $data['title'] = 'Multi Language Name';
        $this->load->view('rest/multi_lang_name', $data);
    }

    public function download_menu_item(){

        $EID = authuser()->EID;
        $data = $this->db2->select('ItemId, Name1, Name2, Name3, Name4, ItmDesc1, ItmDesc2, ItmDesc3, ItmDesc4, Ingeredients1, Ingeredients2, Ingeredients3, Ingeredients4, Rmks1, Rmks2, Rmks3, Rmks4')->get_where('MenuItem', array('EID' => $EID))->result_array();
        $this->export_csv($data);   
        redirect(base_url('restaurant/multi_lang_upload'));
    }
    // export csv
    private function export_csv($values)
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

        $filename = "export_".date("Y-m-d").".csv";

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

    public function language_access(){
        // $this->check_access();
        $status = "error";
        $response = "Something went wrong! Try again later.";
        $EID = authuser()->EID;
        if($this->input->method(true)=='POST'){
            $langId = $this->session->userdata('site_lang');
            
            $status = 'success';

            if (isset($_POST['getAvailableRoles']) && $_POST['getAvailableRoles']==1) {
                $response = $this->db2->query("SELECT id, LangName from Languages where id not in (select LCd from Eat_Lang where Stat = 0 and EID = $EID) ")->result_array();
            }

            if (isset($_POST['getAssignedRoles']) && $_POST['getAssignedRoles']==1) {
                $lname = "el.Name$langId as Name";
                $response = $this->db2->select("el.LCd, el.LangId, $lname")
                                ->join('Languages l', 'l.id = el.LangId', 'inner')
                                ->get_where('Eat_Lang el', array('el.EID' => $EID, 'el.Stat' => 0))->result_array();
            }

            if (isset($_POST['setRestRoles']) && $_POST['setRestRoles']==1) {

                $response = "Language are assigned";

                $temp = [];
                $cui = [];
                $roles = $_POST['roles'];

                foreach ($roles as $role) {
                    $temp['EID'] = $EID;
                    $temp['CID'] = $role;
                    $cui[] = $temp;
                }

                if(!empty($cui)){
                        $this->db2->insert_batch('EatCuisine', $cui); 
                    }else{
                        $response = "Failed to insert";
                    }
                }

            if (isset($_POST['removeRestRoles']) && $_POST['removeRestRoles'] == 1) {
                $CID = $_POST['CID'];
                $CID = implode(",",$CID);

                $deleteRoles = $this->db2->query("DELETE FROM EatCuisine WHERE EID = $EID AND CID IN ($CID)");

                if ($deleteRoles) {
                    $response = "Cuisine are Removed";
                }else {
                    $response = "Failed to delete in EatCuisine table";
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => $status,
                'response' => $response
              ));
             die;
        }
        
        $data['title'] = $this->lang->line('language');

        $this->load->view('rest/languages', $data);    
    }



}
