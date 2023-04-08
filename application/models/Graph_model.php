<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Graph_model extends CI_Model{

	private $db2;
	public function __construct()
	{
		parent::__construct();
		
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

	
}
