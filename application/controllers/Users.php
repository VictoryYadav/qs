<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'user');

        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

    public function index(){

        $dd = $this->db2->select("c.SchNm, cod.SchCd, cod.SDetCd , cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent, cod.Disc_ItemId,if(cod.Disc_ItemId > 0,(select ItemNm from MenuItem where ItemId = cod.Disc_ItemId),'-') as itemName, cod.Disc_IPCd, cod.Disc_Qty, cod.Bill_Disc_pcent ")
                                    ->order_by('cod.MinBillAmt', 'DESC')
                                    ->join('CustOffers c', 'c.SchCd = cod.SchCd')
                                    ->get_where('CustOffersDet cod', 
                                     array('cod.MinBillAmt > ' => 1000,
                                        'c.SchCatg <' => 20))
                                    ->result_array();

                            echo "<pre>";
                            print_r($dd);
                            print_r($this->db2->last_query());
                            die;

        $e = $this->db2->get_where('Eatary', array('EID' => 51))->row_array();
        echo "vijay";
        echo "<pre>";
        print_r($e);die;
    }

    public function print(){
$printer_name = "Microsoft Print to PDF"; // Replace with the name of your thermal printer

$printer = printer_open($printer_name); // Open the specified printer

if ($printer) {
    $text = "Hello, Thermal Printer!\r\n"; // Text to print
    $font = printer_create_font("Arial", 12, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
    
    printer_start_doc($printer, "My Document");
    printer_start_page($printer);
    
    printer_set_option($printer, PRINTER_MODE, "RAW");
    printer_set_option($printer, PRINTER_TEXT_COLOR, "000000");
    printer_select_font($printer, $font);
    
    printer_draw_text($printer, $text);
    
    printer_end_page($printer);
    printer_end_doc($printer);
    
    printer_close($printer);
} else {
    echo "Failed to open the printer.";
}

    }

}
