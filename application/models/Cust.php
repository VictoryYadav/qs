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
// et.TblTyp
        $sql = "mc.TaxType, mc.KitCd, mi.ItemId, mi.ItemNm, mi.ItemNm2, mi.ItemNm3, mi.ItemNm4, mi.ItemTag, mi.ItemTyp, mi.NV, mi.PckCharge, mi.ItmDesc, mi.ItmDesc2, mi.ItmDesc3, mi.ItmDesc4, mi.Ingeredients, mi.Ingeredients2, mi.Ingeredients3, mi.Ingeredients4, mi.Rmks, mi.Rmks2, mi.Rmks3, mi.Rmks4, mi.PrepTime, mi.AvgRtng, mi.FID,ItemNm as imgSrc, mi.UItmCd,mi.CID,mi.Itm_Portion,mi.Value,mi.MCatgId,  (select mir.ItmRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and et.TableNo = '$tableNo' AND et.EID = '$this->EID' AND mir.EID = '$this->EID' AND mir.ItemId = mi.ItemId ORDER BY mir.ItmRate ASC LIMIT 1) as ItmRate, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$this->EID' and et1.TableNo = '$tableNo') as TblTyp";
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
	      if ($len > 15) {
	          $str = substr($str, 0, 15) . "...";
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
	public function getItemOfferAjax($postData){
		if (isset($postData['getOrderData']) && $postData['getOrderData'] == 1) {
		    $itemId = $postData['itemId'];
		    $cid = $postData['cid'];
		    $itemTyp = $postData['itemTyp'];
		    $MCatgId = $postData['MCatgId'];
		    // print_r($itemId);exit();

			$GetOffer = $this->db2->query("SELECT c.SchNm, c.SchCd, cod.SDetCd, cod.SchDesc, c.PromoCode, c.SchTyp, c.Rank, cod.Qty as FreeQty, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd and  (cod.CID = 10 or cod.MCatgId = 1 or cod.ItemTyp =0 or cod.ItemId = 460) left outer join Cuisines as c1 on cod.CID=c1.CID   left outer join MenuCatg as m on cod.MCatgId = m.MCatgId  left outer join ItemTypes as i on cod.ItemTyp = i.ItmTyp  left outer join MenuItem as mi on mi.ItemId = cod.ItemId where c.EID=".$this->EID." and c.ChainId =".$this->ChainId."  and c.Stat=0 and (time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)  group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank")->result_array();
			return $GetOffer;
			echo "<pre>";
			print_r($GetOffer);
			die;
		}
	}

	public function getItem_details_ajax($postData){
		
		$COrgId = $this->session->userdata('COrgId');
		$CustNo = $this->session->userdata('CustNo');
		$EID = $this->EID;
		$ChainId = $this->ChainId;
		$ONo = $this->session->userdata('ONo');
		$EType = $this->session->userdata('EType');
		$TableNo = authuser()->TableNo;
		$KOTNo = $this->session->userdata('KOTNo');
		$CellNo = $this->session->userdata('CellNo');
		$MultiKitchen = $this->session->userdata('MultiKitchen');
		$Kitchen = $this->session->userdata('Kitchen');
		$TableAcceptReqd = $this->session->userdata('TableAcceptReqd');
		$AutoSettle = $this->session->userdata('AutoSettle');
		$CustId = $this->session->userdata('CustId');
		$CNo = $this->session->userdata('CNo');

		if(!empty($CustId) && $CustId > 0){
			$res = $this->db2->query("SELECT * from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND timediff(time(Now()),time(LstModDt))  < time('03:00:00') order by CNo desc limit 1")->result_array();
			if(!empty($res)){
				$_SESSION['CNo'] = $res[0]['CNo'];
				$CNo = $res[0]['CNo'];
			}
		}

		if (isset($postData['getFilterItems']) && $postData['getFilterItems']) {

			$eCID = $postData['eCID'];
			$mCatgId = $postData['mCatgId'];
			$FType = $postData['FType'];

			$varCID = '';

			if ($FType == 0) {
				$varCID = " AND i.CID = $eCID";
				if($mCatgId > 0){
					$varCID .= " AND i.MCatgId = $mCatgId";
				}
			}else{
				$varCID = " AND  FID = $FType AND i.CID = $eCID";
				if($mCatgId > 0){
					$varCID .= " AND i.MCatgId = $mCatgId";
				}
			}

			$menuItemData = $this->getItemQuery($TableNo,$EID,$varCID);

			if (empty($menuItemData)) {
				$response = [
					"status" => 0,
					"msg" => "No Items Available"
				];
			} else {
				foreach ($menuItemData as $key => $data) {
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
					
					$menuItemData[$key]['imgSrc'] = ltrim($imgSrc);
				}

				$response = [
					"status" => 1,
					"menuItemData" => $menuItemData
				];

				// set filter to session
				$this->session->set_userdata('cId', $eCID);
				$this->session->set_userdata('mCatgId', $mCatgId);
			}

			echo json_encode($response);
			die();
		}

		if (isset($postData['getMenuCat']) && $postData['getMenuCat']) {
			$cId = $postData['cId'];
			$menuCatgData = $menuCatgData = $this->getMenuCatgData($EID, $cId);
			if (empty($menuCatgData)) {
				$response = [
					"status" => 0,
					"msg" => "No Menu Category Available At This Time"
				];
			} else {
				$response = [
					"status" => 1,
					"menuCatgData" => $menuCatgData
				];
			}

			echo json_encode($response);
			die();
		}

		if (isset($postData['getUnFilterItems']) && $postData['getUnFilterItems']) {

			$eCID = $postData['eCID'];
			$FType = $postData['FType'];
			$mCatgId = $postData['mCatgId'];

			$varCID = '';

			if ($FType == 0) {
				$varCID = " AND i.CID = $eCID";
				if($mCatgId > 0){
					$varCID .= " AND i.MCatgId = $mCatgId";
				}
			}else{
				$varCID = " AND  FID = $FType AND i.CID = $eCID";
				if($mCatgId > 0){
					$varCID .= " AND i.MCatgId = $mCatgId";
				}
			}

			$menuItemData = $this->getItemQuery($TableNo,$EID,$varCID);

			if (empty($menuItemData)) {
				$response = [
					"status" => 0,
					"msg" => "No Items Available At This Time"
				];
			} else {
				foreach ($menuItemData as $key => $data) {

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

					$menuItemData[$key]['imgSrc'] = ltrim($imgSrc);
				}

				$response = [
					"status" => 1,
					"menuItemData" => $menuItemData
				];

				$this->session->set_userdata('cId', $eCID);
				$this->session->set_userdata('mCatgId', $menuItemData[0]['MCatgId']);
			}

			echo json_encode($response);
			die();
		}

		//Adding an Item to ORder
		if (isset($postData['confirmOrder']) && !empty($postData['confirmOrder'])) {
			// echo "<pre>";print_r($postData);exit();
			if (isset($_SESSION['CustId'])) {
				$CellNo = $this->session->userdata('CellNo');
				$CustNo = $this->session->userdata('CustNo');
				$CustId = $this->session->userdata('CustId');
				$itmrate = str_replace(" ", "", $postData['itmrate']);
				$serveTime = 0;
				$newServeTime = 0;
				$prepration_time = "00:" . $postData['prepration_time'] . ":00";

				$OType = 0;
				$TblTyp = $postData['TblTyp'];
				if($TblTyp > 50){
					if($TblTyp == 51){
						$OType = 1;
					}elseif($TblTyp == 55){
						$OType = 2;
					}elseif($TblTyp == 60){
						$OType=3;
					}elseif($TblTyp == 65){
						$OType=30;
					}elseif($TblTyp == 70){
						$OType=35;
					}
				}else{
					if ($EType == 5) {
						$OType = 7;
					} else {
						$OType = 0;
					}
				}

				$this->db2->trans_start();

				// $DB->beginTransaction();
				try {
					if ($KOTNo == 0) {
						//Deleting older orders
						if ($EType == 5) {
			
							$updateOldUnpaidOrders = $this->db2->query("UPDATE Kitchen set Stat = 99, DelTime = ADDTIME(now(), '$prepration_time') WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 10 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");
			
							//Also update kitchenMain
							$updatekitchenMain = $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 0 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");
			
						} else {
			
							$updateOldUnpaidOrders = $this->db2->query("UPDATE Kitchen set Stat = 99, DelTime = ADDTIME(now(), '$prepration_time') WHERE EID = $EID AND CustId = $CustId AND BillStat = 0 AND KOTNo <> $KOTNo AND (Stat = 0)");
			
							//Also update kitchenMain
							$updatekitchenMain = $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE CustId = $CustId AND EID = $EID AND BillStat = 0 AND timediff(time(Now()),time(LstModDt)) > time('00:30:00')");
			
						}
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
						//USed for MultiKitchen
						$this->session->set_userdata('oldKitCd', 0);
					}

					$oldKitCd = $this->session->userdata('oldKitCd');
					$fKotNo = $KOTNo;

					if ($CNo == 0) {
						
						// insert kitchen main
						if ($CNo == 0) {
							if ($EType == 5) {
								$orderType = 7;
							} else {
								$orderType = 0;
							}
							// $kitchenMainObj
							$kitchenMainObj['CustId'] = $CustId;
							$kitchenMainObj['COrgId'] = $COrgId;
							$kitchenMainObj['CustNo'] = $CustNo;
							$kitchenMainObj['CellNo'] = $CellNo;
							$kitchenMainObj['EID'] = $EID;
							$kitchenMainObj['ChainId'] = $ChainId;
							$kitchenMainObj['ONo'] = $ONo;
							$kitchenMainObj['OType'] = $orderType;
							$kitchenMainObj['TableNo'] = $TableNo;
							$kitchenMainObj['OldTableNo'] = $TableNo;
							$kitchenMainObj['MergeNo'] = $TableNo;
							$kitchenMainObj['Stat'] = $this->session->userdata('TableAcceptReqd');
							$kitchenMainObj['CnfSettle'] = ($this->session->userdata('AutoSettle') == 1)?0:1;
							$kitchenMainObj['LoginCd'] = 1;
							$kitchenMainObj['payRest'] = 0;

							$kichnid = insertRecord('KitchenMain', $kitchenMainObj);
							if ($kichnid) {
								$CNo = $kichnid;
								$this->session->set_userdata('CNo', $CNo);
							}
						} else {
							$CNo = $CNo;
						}
						// end of kitchen main
						$MergeNo = $TableNo;
					} else {
						$MergeNoGet = $this->db2->query("SELECT MergeNo FROM KitchenMain WHERE EID = $EID AND CNo = $CNo and BillStat = 0")->result_array();
						$MergeNo = $MergeNoGet[0]['MergeNo'];
					}

					// For EType = 5
					if ($EType == 5) {
						// $orderType = 7;
						$stat = 10;
						//$newUKOTNO = date('dmy_') . $KOTNo;

						// Check entry is already inserted in ETO
						$checkTableEntry = $this->db2->query("SELECT TNo FROM Eat_tables_Occ WHERE EID = $EID AND CNo = $CNo")->row_array();
				
						//If Empty insert new record
						if (empty($checkTableEntry)) {
							$eatTablesOccObj['EID'] = $EID;
							$eatTablesOccObj['TableNo'] = $TableNo;
							$eatTablesOccObj['MergeNo'] = $MergeNo;
							$eatTablesOccObj['CustId'] = $CustId;
							$eatTablesOccObj['CNo'] = $CNo;
							//$eatTablesOccObj->Stat = 0;
							$eatobj = insertRecord('Eat_tables_Occ', $eatTablesOccObj);
							if ($eatobj) {
								// update Eat_tables for table Allocate
								$eatTablesUpdate = $this->db2->query("UPDATE Eat_tables set Stat = 1, MergeNo = $MergeNo where EID = $EID AND TableNo = '$TableNo' AND  Stat = 0");
							} else {
								//alert "Add another customer to occupied table"
							}
						}

					}else{
						//For ETpye 1 Order Type Will Be 0 and Stat = 1
						$OType = 0;
						$stat = 0;
					}

					$newUKOTNO = date('dmy_') . $KOTNo;
					$prepration_time = $postData['prepration_time'];

					$date = date("Y-m-d H:i:s");
					$date = strtotime($date);
					$time = $prepration_time;
					$date = strtotime("+" . $time . " minute", $date);

					if ($MultiKitchen > 1) {
						$itemKitCd = $postData['itemKitCd'];
						if ($oldKitCd != $postData['itemKitCd']) {
							$getFKOT = $this->db2->query("SELECT max(FKOTNO) as FKOTNO FROM Kitchen WHERE EID = $EID AND KitCd = $itemKitCd")
							->result_array();
							$fKotNo = $getFKOT[0]['FKOTNO'];
							$fKotNo += 1;
							// new ukot
							$newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;

							$this->session->set_userdata('oldKitCd', $itemKitCd);
						} else {
							// next ukot					
							$newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
						}
					}
					// Insert Record to kitchen with Stat 10 For Tem
					$kitchenObj['CNo'] = $CNo;
					$kitchenObj['CustId'] = $CustId;
					$kitchenObj['COrgId'] = $COrgId;
					$kitchenObj['CustNo'] = $CustNo;
					$kitchenObj['CellNo'] = $CellNo;
					$kitchenObj['EID'] = $EID;
					$kitchenObj['ChainId'] = $ChainId;
					$kitchenObj['ONo'] = $ONo;
					$kitchenObj['KitCd'] = $postData['itemKitCd'];
					$kitchenObj['OType'] = $OType;
					$kitchenObj['FKOTNo'] = $fKotNo;
					$kitchenObj['KOTNo'] = $KOTNo;
					$kitchenObj['UKOTNo'] = $newUKOTNO; 		//date('dmy_').$KOTNo;
					$kitchenObj['TableNo'] = $TableNo;
					$kitchenObj['MergeNo'] = $MergeNo;
					$kitchenObj['ItemId'] = $postData['itemId'];
					$kitchenObj['TaxType'] =$postData['tax_type'];
					$kitchenObj['Qty'] = $postData['qty'];
					$kitchenObj['ItmRate'] = $itmrate;
					$kitchenObj['OrigRate'] = $itmrate; 	//(m.Value)
					$kitchenObj['Itm_Portion'] = $postData['itemPortionText'];
					$kitchenObj['CustRmks'] = $postData['custRemarks'];
					$kitchenObj['DelTime'] = date('Y-m-d H:i:s', $date);
					$kitchenObj['TA'] = $postData['takeAway'];
					$kitchenObj['Stat'] = $stat;
					$kitchenObj['LoginCd'] = 1;
					$kitchenObj['SDetCd'] = !empty($postData['sdetcd'])?$postData['sdetcd']:0;
					$kitchenObj['SchCd'] = !empty($postData['schcd'])?$postData['schcd']:0;
					insertRecord('Kitchen', $kitchenObj);
					
					$response = [
						"status" => 1,
						"msg" => "success",
					];
					
					if ($EType == 5) {
						// $response["redirectTo"] = "send_to_kitchen.php";
						$response["redirectTo"]  = base_url('customer/cart');
					} else {
						// $response["redirectTo"] = "order_details.php";
						$response["redirectTo"]  = base_url('customer/order_details');
					}

					// $DB->executeTransaction();

				} catch (Exception $e) {
					$response = [
						"status" => 0,
						"msg" => $e->getMessage(),
					];
					// $DB->rollBack();
				}
				$this->db2->trans_complete();
				
				echo json_encode($response);
				
			} else {
				echo '1';
			}
		}
		// order add to cart
		if (isset($postData['orderToCart']) && !empty($postData['orderToCart'])) {
			// echo "<pre>";print_r($postData);exit();
			if (isset($_SESSION['CustId'])) {
				$CellNo = $this->session->userdata('CellNo');
				$CustNo = $this->session->userdata('CustNo');
				$CustId = $this->session->userdata('CustId');
				$itmrate = str_replace(" ", "", $postData['itmrate']);
				$serveTime = 0;
				$newServeTime = 0;
				$prepration_time = "00:" . $postData['prepration_time'] . ":00";

				$OType = 0;
				$TblTyp = $postData['TblTyp'];
				if($TblTyp > 50){
					if($TblTyp == 51){
						$OType = 1;
					}elseif($TblTyp == 55){
						$OType = 2;
					}elseif($TblTyp == 60){
						$OType=3;
					}elseif($TblTyp == 65){
						$OType=30;
					}elseif($TblTyp == 70){
						$OType=35;
					}
				}else{
					if ($EType == 5) {
						$OType = 7;
					} else {
						$OType = 0;
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

				$this->db2->trans_start();

				if ($CNo == 0) {
					// insert kitchen main
					if ($CNo == 0) {
						if ($EType == 5) {
							$orderType = 7;
						} else {
							$orderType = 0;
						}
						// $kitchenMainObj
						$kitchenMainObj['CustId'] = $CustId;
						$kitchenMainObj['COrgId'] = $COrgId;
						$kitchenMainObj['CustNo'] = $CustNo;
						$kitchenMainObj['CellNo'] = $CellNo;
						$kitchenMainObj['EID'] = $EID;
						$kitchenMainObj['ChainId'] = $ChainId;
						$kitchenMainObj['ONo'] = $ONo;
						$kitchenMainObj['OType'] = $orderType;
						$kitchenMainObj['TableNo'] = $TableNo;
						$kitchenMainObj['OldTableNo'] = $TableNo;
						$kitchenMainObj['MergeNo'] = $TableNo;
						$kitchenMainObj['Stat'] = $this->session->userdata('TableAcceptReqd');
						$kitchenMainObj['CnfSettle'] = ($this->session->userdata('AutoSettle') == 1)?0:1;
						$kitchenMainObj['LoginCd'] = 1;
						$kitchenMainObj['payRest'] = 0;

						$kichnid = insertRecord('KitchenMain', $kitchenMainObj);
						if ($kichnid) {
							$CNo = $kichnid;
							$this->session->set_userdata('CNo', $CNo);
						}
					} else {
						$CNo = $CNo;
					}
					// end of kitchen main
					$MergeNo = $TableNo;
				} else {
					$MergeNoGet = $this->db2->query("SELECT MergeNo FROM KitchenMain WHERE EID = $EID AND CNo = $CNo and BillStat = 0")->result_array();
					$MergeNo = $MergeNoGet[0]['MergeNo'];
				}
				// For EType = 5
				if ($EType == 5) {
					// $orderType = 7;
					$stat = 10;
					//$newUKOTNO = date('dmy_') . $KOTNo;

					// Check entry is already inserted in ETO
					$checkTableEntry = $this->db2->query("SELECT TNo FROM Eat_tables_Occ WHERE EID = $EID AND CNo = $CNo")->row_array();
			
					//If Empty insert new record
					if (empty($checkTableEntry)) {
						$eatTablesOccObj['EID'] = $EID;
						$eatTablesOccObj['TableNo'] = $TableNo;
						$eatTablesOccObj['MergeNo'] = $MergeNo;
						$eatTablesOccObj['CustId'] = $CustId;
						$eatTablesOccObj['CNo'] = $CNo;
						//$eatTablesOccObj->Stat = 0;
						$eatobj = insertRecord('Eat_tables_Occ', $eatTablesOccObj);
						if ($eatobj) {
							// update Eat_tables for table Allocate
							$eatTablesUpdate = $this->db2->query("UPDATE Eat_tables set Stat = 1, MergeNo = $MergeNo where EID = $EID AND TableNo = '$TableNo' AND  Stat = 0");
						} else {
							//alert "Add another customer to occupied table"
						}
					}

				}else{
					//For ETpye 1 Order Type Will Be 0 and Stat = 1
					$OType = 0;
					$stat = 0;
				}

				$newUKOTNO = date('dmy_') . $KOTNo;
				$prepration_time = $postData['prepration_time'];

				$date = date("Y-m-d H:i:s");
				$date = strtotime($date);
				$time = $prepration_time;
				$date = strtotime("+" . $time . " minute", $date);

				if ($MultiKitchen > 1) {
					$itemKitCd = $postData['itemKitCd'];
					if ($oldKitCd != $postData['itemKitCd']) {
						$getFKOT = $this->db2->query("SELECT max(FKOTNO) as FKOTNO FROM Kitchen WHERE EID = $EID AND KitCd = $itemKitCd")
						->result_array();
						$fKotNo = $getFKOT[0]['FKOTNO'];
						$fKotNo += 1;
						// new ukot
						$newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
						$this->session->set_userdata('oldKitCd', $itemKitCd);
					} else {
						// next ukot					
						$newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
					}
				}
				// Insert Record to kitchen with Stat 10 For Tem
				$kitchenObj['CNo'] = $CNo;
				$kitchenObj['CustId'] = $CustId;
				$kitchenObj['COrgId'] = $COrgId;
				$kitchenObj['CustNo'] = $CustNo;
				$kitchenObj['CellNo'] = $CellNo;
				$kitchenObj['EID'] = $EID;
				$kitchenObj['ChainId'] = $ChainId;
				$kitchenObj['ONo'] = $ONo;
				$kitchenObj['KitCd'] = $postData['itemKitCd'];
				$kitchenObj['OType'] = $OType;
				$kitchenObj['FKOTNo'] = $fKotNo;
				$kitchenObj['KOTNo'] = $KOTNo;
				$kitchenObj['UKOTNo'] = $newUKOTNO; 		//date('dmy_').$KOTNo;
				$kitchenObj['TableNo'] = $TableNo;
				$kitchenObj['MergeNo'] = $MergeNo;
				$kitchenObj['ItemId'] = $postData['itemId'];
				$kitchenObj['TaxType'] =$postData['tax_type'];
				$kitchenObj['Qty'] = $postData['qty'];
				$kitchenObj['ItmRate'] = $itmrate;
				$kitchenObj['OrigRate'] = $itmrate; 	//(m.Value)
				$kitchenObj['Itm_Portion'] = $postData['itemPortionText'];
				$kitchenObj['CustRmks'] = $postData['custRemarks'];
				$kitchenObj['DelTime'] = date('Y-m-d H:i:s', $date);
				$kitchenObj['TA'] = $postData['takeAway'];
				$kitchenObj['Stat'] = $stat;
				$kitchenObj['LoginCd'] = 1;
				$kitchenObj['SDetCd'] = !empty($postData['sdetcd'])?$postData['sdetcd']:0;
				$kitchenObj['SchCd'] = !empty($postData['schcd'])?$postData['schcd']:0;
				insertRecord('Kitchen', $kitchenObj);
				
				$response = [
					"status" => 1,
					"msg" => "success",
				];
				
				if ($EType == 5) {
					$response["redirectTo"]  = base_url('customer/cart');
				} else {
					// $response["redirectTo"] = "order_details.php";
					$response["redirectTo"]  = base_url('customer/order_details');
				}

				$this->db2->trans_complete();
				
				echo json_encode($response);
				
			} else {
				echo '1';
			}
		}

		//------------ ItemTyp details -----------------
		if (isset($postData['getCustomItem']) && $postData['getCustomItem']) {
			$itemId = $postData['itemId'];
			$itemTyp = $postData['itemTyp'];
			$itemPortionCode = $postData['itemPortionCode'];
			$FID = $postData['FID'];
			
			$customDetails = $this->getCustomDetails($itemTyp, $itemId, $itemPortionCode, $FID);
			
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

			$response = [
				"status" => 1,
				"customDetails" => $returnData
			];

			echo json_encode($response);

			die();
		}

		if (isset($postData['setCustomItem']) && !empty($postData['setCustomItem'])) {

			$this->db->trans_start();
			// $DB->beginTransaction();
			try {
				if ($CNo == 0) {
					// insertKitchenMain();
					// Check CNo is 0 or not
					if ($CNo == 0) {
						if ($EType == 5) {
							$orderType = 7;
						} else {
							$orderType = 0;
						}
						// $kitchenMainObj
						$kitchenMainObj['CustId'] = $CustId;
						$kitchenMainObj['COrgId'] = $COrgId;
						$kitchenMainObj['CustNo'] = $CustNo;
						$kitchenMainObj['CellNo'] = $CellNo;
						$kitchenMainObj['EID'] = $EID;
						$kitchenMainObj['ChainId'] = $ChainId;
						$kitchenMainObj['ONo'] = $ONo;
						$kitchenMainObj['OType'] = $orderType;
						$kitchenMainObj['TableNo'] = $TableNo;
						$kitchenMainObj['OldTableNo'] = $TableNo;
						$kitchenMainObj['MergeNo'] = $TableNo;
						$kitchenMainObj['Stat'] = $this->session->userdata('TableAcceptReqd');
						$kitchenMainObj['CnfSettle'] = ($this->session->userdata('AutoSettle') == 1)?0:1;
						$kitchenMainObj['LoginCd'] = 1;
						$kitchenMainObj['payRest'] = 0;
						$kichnid = insertRecord('KitchenMain', $kitchenMainObj);
						if ($kichnid) {
							$CNo = $kichnid;
							$this->session->set_userdata('CNo', $CNo);
						}
					} else {
						$CNo = $CNo;
					}
					
					$MergeNo = $TableNo;
				} else {
					$MergeNoGet = $this->db2->query("SELECT MergeNo FROM KitchenMain WHERE EID = $EID AND CNo = $CNo and BillStat = 0")->result_array();
					$MergeNo = $MergeNoGet[0]['MergeNo'];
				}
				// For KOTNo == 0 Generate New KOT
				if ($KOTNo == 0) {

					//Deleting older orders
					if ($EType == 5) {

						$updateOldUnpaidOrders = $this->db2->query("UPDATE Kitchen set Stat = 99, DelTime = ADDTIME(now(), '$prepration_time') WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 10 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");

						//Also update kitchenMain
						$updatekitchenMain = $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE EID = $EID AND CustId = $CustId AND TableNo = '$TableNo' AND Stat = 0 AND BillStat = 0 AND timediff(time(Now()),time(LstModDt))  > time('03:00:00')");

					} else {

						$updateOldUnpaidOrders = $this->db2->query("UPDATE Kitchen set Stat = 99, DelTime = ADDTIME(now(), '$prepration_time') WHERE EID = $EID AND CustId = $CustId AND BillStat = 0 AND KOTNo <> $KOTNo AND (Stat = 0)");

						//Also update kitchenMain
						$updatekitchenMain = $this->db2->query("UPDATE KitchenMain set Stat = 99 WHERE CustId = $CustId AND EID = $EID AND BillStat = 0 AND timediff(time(Now()),time(LstModDt)) > time('00:30:00')");

					}
			
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

				$newUKOTNO = date('dmy_') . $KOTNo;
				$prepration_time = $postData['item_prepTime'];
				$date = date("Y-m-d H:i:s");
				$date = strtotime($date);
				$time = $prepration_time;
				$date = strtotime("+" . $time . " minute", $date);
				$prepration_time = "00:" . $postData['item_prepTime'] . ":00";
			
			
				if ($MultiKitchen > 1) {
					// $KitCd = $postData['itemKitCd'];
					// To set FKOTNO
					$itemKitCd = $postData['itemKitCd'];
					if ($oldKitCd != $postData['itemKitCd']) {
						$getFKOT = $this->db2->query("SELECT max(FKOTNO) as FKOTNO FROM Kitchen WHERE EID=$EID AND KitCd = $itemKitCd AND date(LstModDt) = Date(now())")->result_array();
						
						$fKotNo = $getFKOT[0]['FKOTNO'];
						//$oldFKOTNO = $getFKOT[0]['FKOTNO'];
						$fKotNo += 1;
						// new ukot
						$newUKOTNO = date('dmy_') . $itemKitCd . "_" . $KOTNo . "_" . $fKotNo;
						$this->session->set_userdata('oldKitCd', $itemKitCd);
					}
				}
				
				// For EType = 5
				if ($EType == 5) {
			
					//For ETpye 5 Order Type Will Be 7 and Stat = 10 
					$orderType = 7;
					$stat = 10;
			
					// For Entry table in ETO
					// Check entry is already inserted in ETO
					$checkTableEntry = $this->db2->query("SELECT TNo FROM Eat_tables_Occ WHERE EID = $EID AND CNo = $CNo")->result_array();
			
					//If Empty insert new record
					if (empty($checkTableEntry)) {
						$eatTablesOccObj['EID'] = $EID;
						$eatTablesOccObj['TableNo'] = $TableNo;
						$eatTablesOccObj['MergeNo'] = $MergeNo;
						$eatTablesOccObj['CustId'] = $CustId;
						$eatTablesOccObj['CNo'] = $CNo;
						//$eatTablesOccObj->Stat = 0;
						$eatobj = insertRecord('Eat_tables_Occ', $eatTablesOccObj);
						if ($eatobj) {
							// update Eat_tables for table Allocate
							$eatTablesUpdate = $this->db2->query("UPDATE Eat_tables set Stat = 1, MergeNo = $MergeNo where EID = $EID AND TableNo = '$TableNo' AND  Stat = 0");
						} else {
							//alert "Add another customer to occupied table"
						}
					}
				} else {
					//For ETpye 1 Order Type Will Be 0 and Stat = 1
					$orderType = 0;
					$stat = 0;

				}

				// Insert Record to kitchen with Stat 10 For Tem
				$kitchenObj['CNo'] = $CNo;
				$kitchenObj['CustId'] = $CustId;
				$kitchenObj['COrgId'] = $COrgId;
				$kitchenObj['CustNo'] = $CustNo;
				$kitchenObj['CellNo'] = $CellNo;
				$kitchenObj['EID'] = $EID;
				$kitchenObj['ChainId'] = $ChainId;
				$kitchenObj['ONo'] = $ONo;
				$kitchenObj['KitCd'] = $postData['itemKitCd'];
				$kitchenObj['OType'] = $orderType;
				$kitchenObj['FKOTNo'] = $fKotNo;
				$kitchenObj['KOTNo'] = $KOTNo;
				$kitchenObj['UKOTNo'] = date('dmy_') . $KOTNo;
				$kitchenObj['TableNo'] = $TableNo;
				$kitchenObj['MergeNo'] = $MergeNo;
				$kitchenObj['ItemId'] = $postData['itemId'];
				$kitchenObj['TaxType'] = $postData['tax_type'];

				// Check order is customized
				if ($postData['total'] != 0) {
					$kitchenObj['CustItem'] = 1;

					$custItemDescArray = [];

					$radioNameArray = explode(",", $postData['radioName']);
					foreach ($radioNameArray as $key => $value) {
						if ($value != "0") {
							$custItemDescArray[] = $value;
						}
					}

					$checkboxNameArray = explode(",", $postData['checkboxName']);
					foreach ($checkboxNameArray as $key => $value) {
						if ($value != "0") {
							$custItemDescArray[] = $value;
						}
					}

					$custItemDesc = implode(',', $custItemDescArray);

					$kitchenObj->CustItemDesc = $custItemDesc;
				}

				$kitchenObj['ItemTyp'] = $postData['itemTyp'];
				$kitchenObj['Qty'] = $postData['qty'];
				$kitchenObj['OrigRate'] = $postData['rate'];			// should calculate with original rates... This is temporary 
				$kitchenObj['ItmRate'] = $postData['rate'];
				//$kitchenObj->ItmRate = $ItmRate;
				$kitchenObj['Itm_Portion'] = $postData['itemPortion'];
				$kitchenObj['CustRmks'] = $postData['custRemarks'];
				$kitchenObj['DelTime'] =  date('Y-m-d H:i:s', $date);
				$kitchenObj['TA'] = $postData['takeAway'];
				$kitchenObj['Stat'] = $stat;
				$kitchenObj['LoginCd'] = 1;

				$OrdNo = insertRecord('Kitchen', $kitchenObj);

				$radioValArray = explode(",", $postData['radioVal']);
				$radioRateArray = explode(",", $postData['radioRate']);
				$raidoGrpCdArray = explode(",", $postData['raidoGrpCd']);

				// insert radio items

				foreach ($radioValArray as $key => $value) {
					if ($value == 0) {
						continue;
					}
					$kitchenDetObj['OrdNo'] = $OrdNo;
					$kitchenDetObj['ICd'] = $value;
					$kitchenDetObj['size'] = $postData['itemPortion'];
					$kitchenDetObj['value'] = $radioRateArray[$key];
					$kitchenDetObj['ItemGrpCd'] = $raidoGrpCdArray[$key];
					// $kitchenDetObj->create();
					insertRecord('KitchenDet', $kitchenDetObj);
				}

				// insert Checkbox items
				$checkboxValArray = explode(",", $postData['checkboxVal']);
				$checkboxItemCdArray = explode(",", $postData['checkboxItemCd']);
				$checkboxRateArray = explode(",", $postData['checkboxRate']);

				foreach ($checkboxValArray as $key => $value) {
					if ($value) {
						$kitchenDetObj['OrdNo'] = $OrdNo;
						$kitchenDetObj['ICd'] = $checkboxItemCdArray[$key];
						$kitchenDetObj['size'] = $postData['itemPortion'];
						$kitchenDetObj['value'] = $checkboxRateArray[$key];
						$kitchenDetObj['ItemGrpCd'] = $postData['checkboxGrpCd'];
						// $kitchenDetObj->create();
						insertRecord('KitchenDet', $kitchenDetObj);
					}
				}

				$response = [
					"status" => 1,
					"msg" => "success",
				];

				if ($EType == 5) {
					// $response["redirectTo"] = "send_to_kitchen.php";
					redirect(base_url('customer/send_to_kitchen'));
				} else {
					// $response["redirectTo"] = "order_details.php";
					redirect(base_url('customer/order_details'));
				}

				// $DB->executeTransaction();
				
			} catch (Exception $e) {
				$response = [
					"status" => 0,
					"msg" => $e->getMessage(),
				];
				// $DB->rollBack();
			}
			$this->db->trans_complete();

			echo json_encode($response);
			die();
		}

		if (isset($postData['checkMaxQty']) && $postData['checkMaxQty'] == 1) {
			$itemId = $postData['itemId'];
			$getData = $this->db2->query("SELECT Sum(Qty) as qty FROM `Kitchen` WHERE EID = $EID and ChainId = $ChainId and ItemId = $itemId  and date(LstModDt)=date(now()) and Stat != 4 and Stat != 6 and Stat != 7 and Stat != 99")
			->result_array();
			echo $postData['maxQty'] - ($getData[0]['qty'] + $postData['enterQty']);

			// new logic query
			// $getData = $kitchenObj->exec("SELECT Sum(Qty) as qty FROM `Kitchen` WHERE Eid=1 and ChainId=1 and ItemId=$itemId  and date(LstModDt)=date(now()) and (Stat == 1 or Stat == 2 or Stat == 3 or Stat == 5) ");
		}

	}

	private function getItemQuery($TableNo,$EID,$varCID)
    {
        return $this->db2->query("SELECT i.ItemId, i.ItemNm".$_SESSION['item_name']." as ItemNm,i.NV, mc.TaxType, i.MCatgId, (Select ip.Name from MenuItemRates m , ItemPortions ip, Eat_tables et  where m.ItemId = i.ItemId and m.SecId = et.SecId and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID and ip.IPCd = m.Itm_Portion order by m.ItmRate ASC LIMIT 1 ) as Itm_Portion, (Select ip.IpCd from MenuItemRates m , ItemPortions ip, Eat_tables et  where m.ItemId = i.ItemId and m.SecId = et.SecId and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID and ip.IPCd = m.Itm_Portion order by m.ItmRate ASC LIMIT 1 ) as Itm_Portion_Code, (Select m.ItmRate from MenuItemRates m , ItemPortions ip, Eat_tables et where m.ItemId = i.ItemId AND  m.SecId = et.SecId  and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID order by m.ItmRate ASC LIMIT 1) as Value, AvgRtng, ItmDesc".$_SESSION['item_desc']." as ItmDesc, ItemNm as imgSrc, ItemTyp, i.KitCd, UItmCd, FID, ItemAttrib, PckCharge, MTyp, Ingeredients, MaxQty, PrepTime, ItemSale,ItemTag from MenuItem i, MenuCatg mc where mc.MCatgId = i.MCatgId   AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) $varCID AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID = $EID and md.Chainid=i.ChainId) order by i.Rank")->result_array();
    }

    private function getMenuCatgData($EID, $cId)
    {
        return $this->db2->query("SELECT mc.MCatgId, mc.MCatgNm, mc.CTyp, mc.TaxType, f.FID, f.fIdA, f.Opt, f.AltOpt from MenuCatg mc, MenuItem i, Food f where  i.MCatgId=mc.MCatgId AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) AND mc.CID = :cId AND mc.EID=i.EID AND mc.Stat = 0 AND mc.CTyp = f.CTyp and f.LId = 1 and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID=$EID and md.Chainid=i.ChainId) group by mc.MCatgId, mc.MCatgNm, mc.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt order by mc.Rank " , ["cId" => $cId])->result_array();
    }

    private function getCustomDetails($itemTyp, $ItemId, $itemPortionCode, $FID)
    {
    	$sql = '';
    	if($FID == 1){
    		$sql = " and itd.FID = $FID";
    	}

        if($itemTyp == 125){
            return $this->db2->query("SELECT itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, itg.ItemGrpName, itd.Name, itd.Rate, itg.Reqd From ItemTypesGroup itg join ItemTypesDet itd on itg.ItemGrpCd = itd.ItemGrpCd AND itg.ItemTyp = $itemTyp and itg.ItemId = $itemId and itd.Itm_Portion= $itemPortionCode $sql order by itg.Rank, itd.Rank")->result_array();
        }else{
            return $this->db2->query("SELECT itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, itg.ItemGrpName, itd.Name, itd.Rate, itg.Reqd From ItemTypesGroup itg join ItemTypesDet itd on itg.ItemGrpCd = itd.ItemGrpCd AND itg.ItemTyp = $itemTyp and itd.Itm_Portion= $itemPortionCode $sql order by itg.Rank, itd.Rank")->result_array();
            // print_r($this->db2->last_query());
            // die;
        }
    }

	public function ItemQuery($TableNo,$EID,$varCID)
	{
		return $this->db2->query("SELECT i.ItemId, i.ItemNm".$_SESSION['item_name'].", mc.TaxType, i.MCatgId, et.TblTyp, (Select ip.Name from MenuItemRates m , ItemPortions ip, Eat_tables et  where m.ItemId = i.ItemId and m.SecId = et.SecId and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID and ip.IPCd = m.Itm_Portion order by m.ItmRate ASC LIMIT 1 ) as Itm_Portion, (Select m.ItmRate from MenuItemRates m , ItemPortions ip, Eat_tables et where m.ItemId = i.ItemId AND  m.SecId = et.SecId  and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID order by m.ItmRate ASC LIMIT 1) as Value, AvgRtng, ItmDesc".$_SESSION['item_desc'].", ItemNm as imgSrc, ItemTyp, i.KitCd, UItmCd, FID, ItemAttrib, PckCharge, MTyp, Ingeredients,  PrepTime, ItemSale,ItemTag from MenuItem i, MenuCatg mc where mc.MCatgId = i.MCatgId   AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) $varCID AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID = $EID and md.Chainid=i.ChainId) order by i.Rank")->result_array();
	}

	public function insertKitchenMain()
	{
		global $CNo, $EType, $CustId, $COrgId, $CustNo, $CellNo, $EID, $ChainId, $ONo, $TableNo, $kitchenMainObj;

		// Check CNo is 0 or not
		if ($CNo == 0) {
			if ($EType == 5) {
				$orderType = 7;
			} else {
				$orderType = 0;
			}
			// $kitchenMainObj
			$kitchenMainObj['CustId'] = $CustId;
			$kitchenMainObj['COrgId'] = $COrgId;
			$kitchenMainObj['CustNo'] = $CustNo;
			$kitchenMainObj['CellNo'] = $CellNo;
			$kitchenMainObj['EID'] = $EID;
			$kitchenMainObj['ChainId'] = $ChainId;
			$kitchenMainObj['ONo'] = $ONo;
			$kitchenMainObj['OType'] = $orderType;
			$kitchenMainObj['TableNo'] = $TableNo;
			$kitchenMainObj['OldTableNo'] = $TableNo;
			$kitchenMainObj['MergeNo'] = $TableNo;
			$kitchenMainObj['Stat'] = $this->session->userdata('TableAcceptReqd');
			$kitchenMainObj['CnfSettle'] = ($this->session->userdata('AutoSettle') == 1)?0:1;
			$kitchenMainObj['LoginCd'] = 1;
			$kitchenMainObj['payRest'] = 0;
			$kichnid = insertRecord('KitchenMain', $kitchenMainObj);
			if ($kichnid) {
				$CNo = $kichnid;
				$this->session->set_userdata('CNo', $CNo);
			}
		} else {
			$CNo = $CNo;
		}
	}

	public function getOffers(){
		return $this->db2->query('SELECT c.SchNm, c.SchCd, cod.SDetCd, cod.SchDesc, c.PromoCode, c.SchTyp, c.Rank, cod.Qty as FreeQty, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.SchImg, c1.Name, mi.ItemNm, m.MCatgNm, ip.Name as portionName from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd left outer join Cuisines as c1 on cod.CID=c1.CID left outer join MenuCatg as m on cod.MCatgId = m.MCatgId left outer join ItemPortions as ip on cod.IPCd = ip.IPCd left outer join ItemTypes as i on cod.ItemTyp = i.ItmTyp left outer join MenuItem as mi on mi.ItemId = cod.ItemId where (IF(c.ToTime < c.FrmTime, (CURRENT_TIME() >= c.FrmTime OR CURRENT_TIME() <= c.ToTime) ,(CURRENT_TIME() >= c.FrmTime AND CURRENT_TIME() <= c.ToTime)) OR IF(c.AltToTime < c.AltFrmTime, (CURRENT_TIME() >= c.AltFrmTime OR CURRENT_TIME() <= c.AltToTime) ,(CURRENT_TIME() >=c.AltFrmTime AND CURRENT_TIME() <= c.AltToTime))) and ((DAYOFWEEK(CURDATE()) >= c.FrmDayNo and DAYOFWEEK(CURDATE()) <= c.ToDayNo)  or DayNo = 0) and (DATE(CURDATE()) >= c.FrmDt and DATE(CURDATE()) <= c.ToDt) group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank')->result_array();
	}
	
}
