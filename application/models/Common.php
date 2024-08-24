<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}

	public function getKotList($MCNo, $mergeNo, $FKOTNo, $KOTNo, $EID, $stat){

		$this->db2 = $this->load->database($EID.'e', TRUE);

		// $langId = $this->session->userdata('site_lang');
		$langId = 1;
        $lname = "m.Name$langId as ItemNm";
        $ipName = "ip.Name$langId as Portions";
        $KitName = "ek.Name$langId as KitName";

         return $this->db2->select("k.ItemId, k.MCNo, $lname,k.CustItemDesc,k.CustRmks, $ipName, sum(k.Qty) Qty, k.TableNo,k.MergeNo, k.KOTNo, k.FKOTNo,k.KitCd, $KitName, k.UKOTNo,k.LstModDt,k.TA,k.EDT, k.OType, k.langId")
        					->order_by('k.FKOTNo, m.Name1, ek.Name1, k.UKOTNo, k.FKOTNo', 'ASC')
        					->group_by('ek.Name1, k.ItemId, k.KOTNo, k.FKOTNo, k.Itm_Portion, k.CustItemDesc, k.TA, k.CustRmks')
         					->join('MenuItem m','m.ItemId = k.ItemId','inner')
         					->join('ItemPortions ip','ip.IPCd = k.Itm_Portion','inner')
         					->join('Eat_Kit ek', 'ek.KitCd=k.KitCd', 'inner')
        					// ->where($or_where)
        					// ->where_not_in('k.Stat', array(4,6,7,99))
        					->get_where('Kitchen k', array(
        											'k.EID' => $EID,
        											'k.MCNo' => $MCNo,
        											'k.MergeNo' => $mergeNo,
        											'k.KOTNo' => $KOTNo,
        											'k.Stat' => $stat)
        								)
        					->result_array();
        					
	}

	

}