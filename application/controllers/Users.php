<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    private $db2;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User', 'user');
	}

    public function index(){

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
