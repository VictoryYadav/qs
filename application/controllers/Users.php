<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function index()
	{
        // $db2 = $this->load->database('51e', TRUE);

        // $e = $db2->get_where('Eatary', array('EID' => 51))->row_array();

        // $d = $this->db->get_where('EIDDet', array('EID' => 51))->row_array();
		// echo "<pre>";
		// print_r($d);
        // print_r($e);
        // die;
        if(isset($_GET['qr_data']) && !empty($_GET['qr_data'])){
            $qr_data = rtrim(base64_decode($_REQUEST['qr_data']), "=");
            // echo "<pre>";print_r($qr_data);exit();
            $req = explode("&", $qr_data);
            
            if(!empty($req)){
                $e_data = explode("=", $req[0]);
                if($e_data[0] == 'e'){
                    $_REQUEST['e'] = $e_data[1];
                    $my_db = $e_data[1].'e';
                    $this->session->set_userdata('my_db', $my_db);
                }
                if(isset($req[1])){
                    $c_data = explode("=", $req[1]);
                    if($c_data[0] == 'c'){
                        $_REQUEST['c'] = $c_data[1];
                    }
                }
                if(isset($req[2])){
                    $t_data = explode("=", $req[2]);
                    if($t_data[0] == 't'){
                        $_REQUEST['t'] = $t_data[1];
                    }
                }
                if(isset($req[3])){
                    $o_data = explode("=", $req[3]);
                    if($o_data[0] == 'o'){
                        $_REQUEST['o'] = $o_data[1];
                    }
                }
            }

            if (!isset($_REQUEST['e']) && !isset($_REQUEST['c']) && !isset($_REQUEST['t']) && !isset($_REQUEST['o'])) {
                echo "<h1>Incorrect Details !</br></br> Please Scan QR Code again.</h1>";
                exit;
            }

        }else{
            echo "<h1>Incorrect Details !</br></br> Please Scan QR Code again.</h1>";
            die;
        }
		
        // set db in sesion and use constroctor to load db in all controller

		// Session::set('EID', $_REQUEST['e']);
		// Session::set('ChainId', $_REQUEST['c']);
		// Session::set('TableNo', $_REQUEST['t']);
		// Session::set('Stall', $_REQUEST['o']);

        $data['db_name'] = '51e';

        $db2 = $this->load->database($data['db_name'], TRUE);

        $e = $db2->get_where('Eatary', array('EID' => 51))->row_array();

        $tt = $this->config->item('foo');
		echo "<pre>";
        print_r($_SESSION);
        print_r($e);
        print_r($tt);
		print_r($_REQUEST);
		die;
	}

}
