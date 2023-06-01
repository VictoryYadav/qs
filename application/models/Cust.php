<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cust extends CI_Model{

	private $db2;
	public function __construct()
	{
		parent::__construct();
		
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

	public function getCuisineList(){
		return $this->db2->select('c.CID, c.Name, c.Name2, c.Name3, c.Name4, c.CTyp')
						->order_by('ec.Rank', 'ASC')
						->join('Cuisines c', 'c.CID = ec.CID', 'inner')
						->get_where('EatCuisine ec', array('ec.EID' => authuser()->EID))
						->result_array();

		
	}

	public function getMcatandCtypList($cid){
		$data['mcat'] = array();
		$data['filter'] = array();

		$menuCatg = $this->session->userdata('menuCatg');
		$foodTyp = $this->session->userdata('foodTyp');

		if ($menuCatg == 1) {
			$data['mcat'] = $this->db2->select('MCatgId, MCatgNm, L1MCatgNm, L2MCatgNm, L3MCatgNm, CTyp, CID')
								->get_where('MenuCatg', array('CID' => $cid))
								->result_array();
		}
		
		if ($foodTyp == 1) {				
			$data['filter'] = $this->db2->select('FID, Opt, FIdA, AltOpt')
							->get_where('Food', array('CTyp' => $data['mcat'][0]['CTyp']))
							->row_array();
		}
		return $data;
	}

	function getItemDetailLists($CID, $mcat, $fl){
// AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0)  AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID = $EID and md.Chainid=i.ChainId) order by i.Rank


		$EID = authuser()->EID;
        $tableNo = authuser()->TableNo;

		$where = "mi.Stat = 0 and (DAYOFWEEK(CURDATE()) = mi.DayNo OR mi.DayNo = 0)  AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and mi.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=mi.ItemId and md.EID = $EID and md.Chainid=mi.ChainId)";
// ask for ctype filter
        $sql = "mc.TaxType, mc.KitCd, mi.ItemId, mi.ItemNm, mi.ItemNm2, mi.ItemNm3, mi.ItemNm4, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, mi.ItmDesc, mi.ItmDesc2, mi.ItmDesc3, mi.ItmDesc4, mi.Ingeredients, mi.Ingeredients2, mi.Ingeredients3, mi.Ingeredients4, mi.Rmks, mi.Rmks2, mi.Rmks3, mi.Rmks4, mi.PrepTime, mi.AvgRtng, mi.FID, (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$tableNo' AND et.EID = '$EID' AND mir.EID = '$EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate";
        if(!empty($mcat)){
            $this->db2->where('mc.MCatgId', $mcat);
        }
        if(!empty($fl)){
        	$this->db2->where('mi.FID', $fl);
        }
        return $this->db2->select($sql)
        				->order_by('mi.Rank', 'ASC')
                        ->join('MenuItem mi', 'mi.MCatgId = mc.MCatgId')
                        // ->join('MenuItem_Disabled mid', 'mid.ItemId = mi.ItemId', 'inner')
                        ->where($where)
                        ->get_where('MenuCatg mc', array(
                            'mc.CID' => $CID,
                            'mc.EID' => $EID
                        ))
                        ->result_array();


        // echo "<pre>";
        // print_r($data);
        // die;
	}
	
}
