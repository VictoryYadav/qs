<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cust extends CI_Model{

	private $db2;
	private $EID;
	private $ChainId;
	public function __construct()
	{
		parent::__construct();
		
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
        $this->EID = authuser()->EID;
        $this->ChainId = authuser()->ChainId;
	}

	public function getCuisineList(){
		return $this->db2->select('c.CID, c.Name, c.Name2, c.Name3, c.Name4, c.CTyp')
						->order_by('ec.Rank', 'ASC')
						->join('Cuisines c', 'c.CID = ec.CID', 'inner')
						->get_where('EatCuisine ec', array('ec.EID' => $this->EID))
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
							->result_array();
		}
		return $data;
	}

	function getItemDetailLists($CID, $mcat, $fl){
        $tableNo = authuser()->TableNo;

		$where = "mi.Stat = 0 and (DAYOFWEEK(CURDATE()) = mi.DayNo OR mi.DayNo = 0)  AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and mi.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=mi.ItemId and md.EID = $this->EID and md.Chainid=mi.ChainId)";

        $sql = "mc.TaxType, mc.KitCd, mi.ItemId, mi.ItemNm, mi.ItemNm2, mi.ItemNm3, mi.ItemNm4, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, mi.ItmDesc, mi.ItmDesc2, mi.ItmDesc3, mi.ItmDesc4, mi.Ingeredients, mi.Ingeredients2, mi.Ingeredients3, mi.Ingeredients4, mi.Rmks, mi.Rmks2, mi.Rmks3, mi.Rmks4, mi.PrepTime, mi.AvgRtng, mi.FID,ItemNm as imgSrc, mi.UItmCd,  (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$tableNo' AND et.EID = '$this->EID' AND mir.EID = '$this->EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate";
        if(!empty($mcat)){
            $this->db2->where('mc.MCatgId', $mcat);
        }
        if(!empty($fl)){
        	$this->db2->where('mi.FID', $fl);
        }
        $data =  $this->db2->select($sql)
        				->order_by('mi.Rank', 'ASC')
                        ->join('MenuItem mi', 'mi.MCatgId = mc.MCatgId')
                        // ->join('MenuItem_Disabled mid', 'mid.ItemId = mi.ItemId', 'inner')
                        ->where($where)
                        ->get_where('MenuCatg mc', array(
                            'mc.CID' => $CID,
                            'mc.EID' => $this->EID
                        ))
                        ->result_array();
         
         if(!empty($data)){
	        foreach ($data as &$key) {
				if ($this->ChainId > 0) {
					$imgSrc = "uploads/c$this->ChainId/" . trim($key['imgSrc']) . ".jpg";
				} else {
					$imgSrc = "uploads/e$this->EID/" . trim($key['imgSrc']) . ".jpg";
				}

				if (!file_exists($imgSrc)) {
					$imgSrc = "uploads/general/" . trim($key['imgSrc']) . ".jpg";
					if (!file_exists("../$imgSrc")) {
						$imgSrc = "uploads/uItem/" . $key['UItmCd'] . ".jpg";
					}
				}

				$key['imgSrc'] = ltrim($imgSrc);
				$key['short_ItemNm'] = $this->strTruncate($key['ItemNm']);
			}
         }

         return $data;

        echo "<pre>";
        print_r($data);
        die;
	}

	function strTruncate($str){
	    $len = strlen($str);
	      if ($len > 20) {
	          $str = substr($str, 0, 20) . "...";
	      }
	      return $str;
	  }

	public function getMenuItemRates($EID, $itemId, $TableNo,$cid,$MCatgId,$ItemTyp){
		return $this->db2->select('ip.IPCd as IPCode, mir.ItmRate, ip.Name')
						->order_by('ItmRate', 'ASC')
						->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
						->join('ItemPortions ip', 'ip.IPCd = mir.Itm_Portion', 'inner')
						->join('Eat_tables et', 'et.SecId = mir.SecId', 'inner')
						->get_where('MenuItem mi', array(
							'mi.ItemId' => $itemId,
							'mir.EID' => $EID,
							'et.TableNo' => $TableNo))
						->result_array();
		// return $this->db2->query("SELECT ip.Name, mi.ItemId as mtemid, mi.ItemTyp as mitype, mi.MCatgId as micat, mi.CID as micid, mi.ItemId, m.ItmRate,ip.IPCd as IPCode, cod.* FROM MenuItem as mi join MenuItemRates m on mi.ItemId = m.ItemId join ItemPortions ip on ip.IPCd = m.Itm_Portion join Eat_tables et on m.SecId=et.SecId left outer join CustOffersDet as cod on ((mi.ItemId = cod.ItemId and cod.ItemId>0) or (mi.MCatgId = cod.MCatgId and cod.MCatgId>0) or (mi.ItemTyp = cod.ItemTyp and cod.ItemTyp>0) or (mi.CID = cod.CID and cod.CID>0) or (ip.IPCd = cod.IPCd and cod.IPCd > 0)) where m.EID=".$EID." and mi.ItemId=$itemId and et.TableNo='".$TableNo."' and m.EID=et.EID Order by ItmRate Asc")->result_array();

		// SELECT ip.Name, mi.ItemId as mtemid, mi.ItemTyp as mitype, mi.MCatgId as micat, mi.CID as micid, mi.ItemId, m.ItmRate,ip.IPCd as IPCode FROM MenuItem as mi join MenuItemRates m on mi.ItemId = m.ItemId join ItemPortions ip on ip.IPCd = m.Itm_Portion join Eat_tables et on m.SecId=et.SecId  where m.EID=51 and mi.ItemId=167 and et.TableNo='22' and m.EID=et.EID Order by ItmRate Asc
	}

	public function getOfferCustAjax($postData){
		if (isset($postData['getOrderData']) && $postData['getOrderData'] == 1) {

		    $itemId = $postData['itemId'];
		    $cid = $postData['cid'];
		    $itemTyp = $postData['itemTyp'];
		    $MCatgId = $postData['MCatgId'];
		    // print_r($itemId);exit();

			$GetOffer = $this->db2->query("SELECT c.SchNm, c.SchCd, cod.SDetCd, cod.SchDesc, c.PromoCode, c.SchTyp, c.Rank, cod.Qty as FreeQty, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd and  (cod.CID = 10 or cod.MCatgId = 1 or cod.ItemTyp =0 or cod.ItemId = 460) left outer join Cuisines as c1 on cod.CID=c1.CID   left outer join MenuCatg as m on cod.MCatgId = m.MCatgId  left outer join ItemTypes as i on cod.ItemTyp = i.ItmTyp  left outer join MenuItem as mi on mi.ItemId = cod.ItemId where c.EID=".$this->EID." and c.ChainId =".$this->ChainId."  and c.Stat=0 and c.Stat=0 and c.Stat=0 and (time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)  group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank")->result_array();

		    if (count($GetOffer) == 0) {
		        echo 0;
		    }
		    $html = '';
		    $tempArray = array();
		    for ($i = 0; $i < count($GetOffer); $i++) {
		        $currentValue = $GetOffer[$i]['SchCd'];
		        $tempArray[$i] = $currentValue;
		    }
		    $temp = 0;
		    $j = 0;
		    $tempArray =  array_count_values($tempArray);
		    $data = array();
		    $b = false;
		    $html.="<ul style='list-style-type:none;padding:0;'>";
		    // echo "<pre>";print_r($tempArray);exit();
		    foreach ($GetOffer as $key => $value) {
		        $b = true;
		        $GetOffer[$j]['ItemRate'] = 0;
		        // if ($GetOffer[$j]['SchCd'] == $key) {
		            $html.='<li><div class="row p-1"><div class="col-sm-1" style="padding-top: 10px;"><input type="radio" name="offer" schcd='.$GetOffer[$j]['SchCd'].' sdetcd='.$GetOffer[$j]['SDetCd'].' onchange="select_offer(this)"></div>';
		            $html .= '<div class="col-sm-11"><span class="offer_name"><b>' . $GetOffer[$j]['SchNm'].'</b>'.'<br><p>' . $GetOffer[$j]['SchDesc'] . '</p>'.'</span></div>';
		            // $html .= ;
		            $html .= '</div></li>';
		            $temp++;
		        // }
		        $j++;
		    }
		    $html.='</ul>';
		    
		    if(!$b) {
		        $html = 0;
		    }

		    return $html;
		}
	}
	
	
}
