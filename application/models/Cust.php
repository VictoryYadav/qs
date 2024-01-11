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

		$langId = $this->session->userdata('site_lang');
        $lname = "c.Name$langId as Name";
        $select_sql = "c.CID, $lname";

		return $this->db2->select($select_sql)
						->order_by('ec.Rank', 'ASC')
						->join('Cuisines c', 'c.CID = ec.CID', 'inner')
						->get_where('EatCuisine ec', array('ec.EID' => $this->EID,'ec.Stat' => 0))
						->result_array();
	}

	public function getMcatandCtypList($cid){
		$data['mcat'] = array();
		$data['filter'] = array();

		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as LngName";

        $select_sql = "MCatgId, CTyp, CID, $lname";

		$data['mcat'] = $this->db2->select($select_sql)
								->order_by('Rank', 'ASC')
								->get_where('MenuCatg', array('CID' => $cid))
								->result_array();
		
		// $data['filter'] = $this->db2->select('FID, Opt, Rank')
		// 					->order_by('Rank', 'ASC')
		// 					->get_where('FoodType', array('CTyp' => $data['mcat'][0]['CTyp'], 'Stat' => 0))
		// 					->result_array();
		// echo "<pre>";
		// print_r($data);
		// die;
		$this->session->set_userdata('f_fid', 0);
		$this->session->set_userdata('f_cid', $cid);
		$this->session->set_userdata('f_mcat', $data['mcat'][0]['MCatgId']);

		return $data;
	}

	function getItemDetailLists($CID, $mcat, $fl){

		$langId = $this->session->userdata('site_lang');
        $lname = "mi.ItemNm$langId as itemName";
        $iDesc = "mi.ItmDesc$langId as itemDescr";
        $ingeredients = "mi.Ingeredients$langId as Ingeredients";
        $Rmks = "mi.Rmks$langId as Rmks";

		$this->session->set_userdata('f_cid', $CID);
        $tableNo = authuser()->TableNo;

		$where = "mi.Stat = 0 and (DAYOFWEEK(CURDATE()) = mi.DayNo OR mi.DayNo = 0)  AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime)))";
// et.TblTyp
        $select_sql = "mc.TaxType, mc.KitCd,mc.CTyp, mi.ItemId, mi.ItemTyp, mi.NV, mi.PckCharge, $lname, $iDesc, $ingeredients, $Rmks, mi.PrepTime, mi.AvgRtng, mi.FID,mi.ItemAttrib, mi.ItemSale, mi.ItemTag, mi.ItemNm1 as imgSrc, mi.UItmCd,mi.CID,mi.Itm_Portion ,mi.MCatgId,mi.videoLink,  (select mir.OrigRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and mir.OrigRate > 0 and et.TableNo = '$tableNo' AND et.EID = '$this->EID' AND mir.EID = '$this->EID' AND mir.ItemId = mi.ItemId and mi.Stat = 0 ORDER BY mir.OrigRate ASC LIMIT 1) as ItmRate, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$this->EID' and et1.TableNo = '$tableNo') as TblTyp";
        if(!empty($mcat)){
        	$this->session->set_userdata('f_mcat', $mcat);
            $this->db2->where('mc.MCatgId', $mcat);
        }
        if(!empty($fl) && ($fl > 0) ){
        	$this->db2->where('mi.FID', $fl);
        	$this->session->set_userdata('f_fid', $fl);
        }
        $data =  $this->db2->select($select_sql)
        				->order_by('mi.Rank', 'ASC')
                        ->join('MenuItem mi', 'mi.MCatgId = mc.MCatgId')
                        // ->join('MenuItem_Disabled mid', 'mid.ItemId = mi.ItemId', 'inner')
                        ->where($where)
                        ->get_where('MenuCatg mc', array(
                            'mc.CID' => $CID,
                            'mc.EID' => $this->EID
                        ))
                        ->result_array();
         // echo "<pre>";
         // print_r($data);
         // die;
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
						$imgSrc = "uploads/uItem/Eat-Out-Icon.png";
						// $imgSrc = "uploads/uItem/" . $key['UItmCd'] . ".jpg";
					}
				}

				$key['imgSrc'] = ltrim($imgSrc);
				if($langId == 1){
					$key['short_ItemNm'] = $this->strTruncate($key['itemName']);
					$key['short_Desc'] = $this->descTruncate($key['itemDescr']);
				}
			}
         }

         return $data;

        // echo "<pre>";
        // print_r($data);
        // die;
	}

	function strTruncate($str){
	    $len = strlen($str);
	      if ($len > 15) {
	          $str = substr($str, 0, 15) . "...";
	      }
	      return $str;
	  }

	function descTruncate($str){
	    $len = strlen($str);
	      if ($len > 55) {
	          $str = substr($str, 0, 55) . "...";
	      }
	      return $str;
	}

	public function getMenuItemRates($EID, $itemId, $TableNo,$cid,$MCatgId,$ItemTyp){
		$langId = $this->session->userdata('site_lang');
        $ipName = "ip.Name$langId as Name";

		return $this->db2->select("ip.IPCd as IPCode, mir.ItmRate, $ipName")
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

			$GetOffer = $this->db2->query("SELECT c.SchNm, c.SchCd, cod.SDetCd, cod.SchDesc, c.PromoCode, c.SchTyp, c.Rank, cod.Qty as FreeQty, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd and  (cod.CID = $cid or cod.MCatgId = $MCatgId or cod.ItemTyp = $itemTyp or cod.ItemId = $itemId) left outer join Cuisines as c1 on cod.CID=c1.CID   left outer join MenuCatg as m on cod.MCatgId = m.MCatgId  left outer join ItemTypes as i on cod.ItemTyp = i.ItmTyp  left outer join MenuItem as mi on mi.ItemId = cod.ItemId where c.EID=".$this->EID." and c.ChainId =".$this->ChainId."  and c.Stat=0 and c.Stat=0 and c.Stat=0 and (time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)  group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank")->result_array();

			// echo "<pre>";
			// print_r($this->db2->last_query());
			// print_r($GetOffer);
			// die;

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
		    
		    $langId = $this->session->userdata('site_lang');
        	$scName = "c.SchNm$langId as SchNm";
        	$scDesc = "cod.SchDesc$langId as SchDesc";

			return $this->db2->query("SELECT $scName, c.SchCd, cod.SDetCd, $scDesc, c.PromoCode, c.SchTyp, c.Rank,cod.Disc_ItemId, cod.Qty,cod.Disc_Qty, cod.IPCd, cod.Disc_IPCd, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd and  (cod.CID = $cid or cod.MCatgId = $MCatgId or cod.ItemTyp = $itemTyp or cod.ItemId = $itemId) left outer join Cuisines as c1 on cod.CID=c1.CID   left outer join MenuCatg as m on cod.MCatgId = m.MCatgId  left outer join ItemTypes as i on cod.ItemTyp = i.ItmTyp  left outer join MenuItem as mi on mi.ItemId = cod.ItemId where c.EID=".$this->EID." and c.ChainId =".$this->ChainId."  and c.Stat=0 and (time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)  group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank")->result_array();
		}
	}

	public function getItem_details_ajax($postData){
		// echo "<pre>";
		// print_r($postData);
		// die;
		$COrgId = $this->session->userdata('COrgId');
		$CustNo = $this->session->userdata('CustNo');
		$EID = $this->EID;
		$ChainId = $this->ChainId;
		$ONo = $this->session->userdata('ONo');
		$EType = $this->session->userdata('EType');
		$TableNo = authuser()->TableNo;
		$MergeNo = $this->session->userdata('MergeNo');
		$KOTNo = $this->session->userdata('KOTNo');
		$CellNo = $_SESSION['signup']['MobileNo'];
		$MultiKitchen = $this->session->userdata('MultiKitchen');
		$Kitchen = $this->session->userdata('Kitchen');
		$TableAcceptReqd = $this->session->userdata('TableAcceptReqd');
		$AutoSettle = $this->session->userdata('AutoSettle');
		$CustId = $this->session->userdata('CustId');
		$CNo = $this->session->userdata('CNo');

		// ask for bcoz $cno assign at login time
		if(!empty($CustId) && $CustId > 0){
			$res = $this->db2->query("SELECT * from KitchenMain where CustId = ".$CustId." and BillStat = 0 AND timediff(Now(),LstModDt) < ('03:00:00') order by CNo desc limit 1")->result_array();
			if(!empty($res)){
				$this->session->set_userdata('CNo', $res[0]['CNo']);
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

		// order add to cart
		if (isset($postData['orderToCart']) && !empty($postData['orderToCart'])) {
			// echo "<pre>";print_r($postData);exit();
			if (isset($_SESSION['CustId'])) {

				if($CNo == 0){
					$checkKM = $this->db2->select('custPymt, BillStat, payRest')
										->get_where('KitchenMain', array('BillStat >' => 0, 'EID' => $EID, 'CustId' => $CustId,'payRest' => 0))->row_array();
					if(!empty($checkKM)){
							$response = [
								"status" => 2,
								"data" => $checkKM
							];
							$response["redirectTo"]  = base_url('customer/current_order');
							echo json_encode($response);
							die;
					}
					$this->session->set_userdata('CNo' , $CNo);
							$this->session->set_userdata('MergeNo' , $TableNo);
				}
				else{
					$billData = $this->db2->select('b.BillId, b.Stat, b.CNo, b.PaidAmt, b.payRest')
                                        ->order_by('b.Billid','DESC')
                                        ->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
                                        ->get_where('Billing b', array('b.EID' => $EID,
                                            'km.CNo' => $CNo,
                                            'km.CustId' => $CustId
                                        )
                                                    )->row_array();
                    if(!empty($billData)){
                    	$CNo = 0;
                    	$this->session->set_userdata('KOTNo', 0);
                		$this->session->set_userdata('CNo', $CNo);
                		$this->session->set_userdata('itemTotalGross', 0);
                		if($billData['payRest'] == 0){
			                $response = [
									"status" => 2,
									"data" => $billData
								];
							$response["redirectTo"]  = base_url('customer/current_order');
							echo json_encode($response);
							die;
                		}
		            }
				}

				$CellNo = $_SESSION['signup']['MobileNo'];
				$this->session->set_userdata('CellNo', $CellNo);
				$CustNo = $this->session->userdata('CustNo');
				$CustId = $this->session->userdata('CustId');
				$itmrate = str_replace(" ", "", $postData['itmrate']);
				$serveTime = 0;
				$newServeTime = 0;
				$postData['prepration_time'] = (!empty($postData['prepration_time'])?$postData['prepration_time']:'00');
				$prepration_time = "00:" . $postData['prepration_time'] . ":00";

				$OType = 0;
				$TblTyp = $postData['TblTyp'];

				if($TblTyp == 0){
                    // QSR
                    $OType = 1;
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
						// if ($EType == 5) {
						// 	$orderType = 7;
						// } else {
						// 	$orderType = 0;
						// }

						
						if ($EType == 5) {
							$tblData = getTableDetail($TableNo);
							$kitchenMainObj['CCd'] = $tblData['CCd'];
						}

						if($TableNo == $MergeNo){
							$mergeTable = getRecords('Eat_tables', array('EID' => $EID , 'TableNo' => $TableNo));
							if(!empty($mergeTable)){
								$MergeNo = $mergeTable['MergeNo'];
								$this->session->set_userdata('MergeNo', $MergeNo);
							}
						}
						// $kitchenMainObj
						$kitchenMainObj['CustId'] = $CustId;
						$kitchenMainObj['COrgId'] = $COrgId;
						$kitchenMainObj['CustNo'] = $CustNo;
						$kitchenMainObj['CellNo'] = $CellNo;
						$kitchenMainObj['EID'] = $EID;
						$kitchenMainObj['ChainId'] = $ChainId;
						$kitchenMainObj['ONo'] = $ONo;
						$kitchenMainObj['OType'] = $OType;
						// delivery charge for deliver = 110
						if($OType == 110){
							$kitchenMainObj['DelCharge'] = $this->session->userdata('DelCharge');
						}

						$kitchenMainObj['TableNo'] = $TableNo;
						$kitchenMainObj['OldTableNo'] = $TableNo;
						$kitchenMainObj['MergeNo'] = $MergeNo;
						$kitchenMainObj['Stat'] = 1;
						if($TableAcceptReqd == 0){
							$kitchenMainObj['Stat'] = 2;
						}
						
						$kitchenMainObj['LoginCd'] = $CustId;
						$kitchenMainObj['payRest'] = 0;

						$CNo = insertRecord('KitchenMain', $kitchenMainObj);
						if ($CNo) {
							
							updateRecord('KitchenMain',array('MCNo' => $CNo), array('CNo' => $CNo));
							$this->session->set_userdata('CNo', $CNo);
						}
					} else {
						$CNo = $CNo;
					}
					$this->session->set_userdata('CNo', $CNo);
					// end of kitchen main
					$MergeNo = $TableNo;
				} else {
					$MergeNoGet = $this->db2->query("SELECT MergeNo FROM KitchenMain WHERE EID = $EID AND CNo = $CNo and BillStat = 0")->row_array();
					$MergeNo = $MergeNoGet['MergeNo'];
				}

				// For EType = 5
				if ($EType == 5) {
					// $orderType = 7;
					if($TableAcceptReqd > 0){					
						$checkStat = $this->db2->query("SELECT Stat FROM KitchenMain WHERE CNo = $CNo AND EID = $EID AND BillStat = 0")->row_array();
						if(!empty($checkStat)){
							$stat = $checkStat['Stat'];
						}else{
							$stat=2;
						}
					}else{
						$stat = 2;
					}
					//$newUKOTNO = date('dmy_') . $KOTNo;
					updateRecord('Eat_tables', array('Stat' => 1), array('EID' => $EID ,'TableNo' => $TableNo, 'Stat' => 0));						
				}else{
					//For ETpye 1 Order Type Will Be 0 and Stat = 1
					// $OType = 0;
					$stat = 1;
				}

				$newUKOTNO = date('dmy_') . $KOTNo;
				$prepration_time = $postData['prepration_time'];

				$date = date("Y-m-d H:i:s");
				$date = strtotime($date);
				$time = $prepration_time;
				$date = strtotime("+" . $time . " minute", $date);

				$edtTime = '00:00';
				if($this->session->userdata('EDT') > 0){
					$edtTime = date('H:i', $date);
				}

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

				// check for asking
				$MergeNoGet = $this->db2->query("SELECT MergeNo FROM KitchenMain WHERE EID = $EID AND CNo = $CNo and BillStat = 0")->row_array();
				$MergeNo = $MergeNoGet['MergeNo'];
				// end check for

				// offer
				$kitchenObj['CNo'] = $CNo;
				$kitchenObj['MCNo'] = $CNo;
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
				$kitchenObj['TaxType'] =$postData['tax_type'];
				$kitchenObj['CustRmks'] = $postData['custRemarks'];
				$kitchenObj['DelTime'] = date('Y-m-d H:i:s', $date);
				$kitchenObj['EDT'] = $edtTime;
				$kitchenObj['TA'] = $postData['takeAway'];
				$kitchenObj['PckCharge'] = 0;
				if($kitchenObj['TA'] == 1){
					$kitchenObj['PckCharge'] = $postData['PckCharge'];
				}
				$kitchenObj['Stat'] = $stat;
				$kitchenObj['LoginCd'] = $CustId;
				$kitchenObj['ItemTyp'] = $postData['itemTyp'];

				//custom offers
				if($kitchenObj['ItemTyp'] != 0){
					if ($postData['total'] != 0) {
						// doubt ask for custom 
						$itmrate = $postData['total'];
						$kitchenObj['CustItem'] = 1;

						$custItemDescArray = [];

						$radioNameArray = isset($postData['radioName'])?$postData['radioName']:[];
						// $radioNameArray = explode(",", $postData['radioName']);
						if(!empty($radioNameArray)){
							foreach ($radioNameArray as $key => $value) {
								if ($value != "0") {
									$custItemDescArray[] = $value;
								}
							}
						}

						$checkboxNameArray = isset($postData['checkboxName'])?$postData['checkboxName']:[];
						// $checkboxNameArray = explode(",", $postData['checkboxName']);
						if(!empty($checkboxNameArray)){
							foreach ($checkboxNameArray as $key => $value) {
								if ($value != "0") {
									$custItemDescArray[] = $value;
								}
							}
						}

						if(!empty($custItemDescArray)){
							$custItemDesc = implode(',', $custItemDescArray);
							$kitchenObj['CustItemDesc'] = $custItemDesc;
						}
					}
				}
				//end custom offers

				$schcd = !empty($postData['schcd'])?$postData['schcd']:0;
				$sdetcd = 0;
				if(!empty($schcd)){
					
					$sdetcd = !empty($postData['sdetcd'])?$postData['sdetcd']:0;
					$Offers = $this->getSchemeOfferList($schcd, $sdetcd);

					if(!empty($Offers)){
						
						$kitchenObj['ItemId'] = $Offers['ItemId'];
						$kitchenObj['Qty'] = $Offers['Qty'];
						$kitchenObj['ItmRate'] = $itmrate;
						$kitchenObj['OrigRate'] = $itmrate; 	//(m.Value)
						$kitchenObj['Itm_Portion'] = $Offers['IPCd'];
						// $kitchenObj['Itm_Portion'] = $postData['itemPortionText'];
						
						$kitchenObj['SchCd'] = $schcd;
						$kitchenObj['SDetCd'] = $sdetcd;
						insertRecord('Kitchen', $kitchenObj);
						// for offer 
						$Disc_ItemId = $Offers['Disc_ItemId'];
						$Disc_IPCd = $Offers['Disc_IPCd'];
						$offerRate = $itmrate - ($itmrate * $Offers['Disc_pcent'] / 100);
						if($Disc_ItemId != $postData['itemId']){
							$offerRates = $this->db2->query("select mi.ItmRate from MenuItemRates as mi where mi.EID = $this->EID, mi.ChainId = $this->ChainId and mi.ItemId = $Disc_ItemId and mi.Itm_Portion = $Disc_IPCd and mi.SecId = (select SecId from Eat_tables where TableNo = $TableNo and EID = $this->EID")->row_array();
							$offerRate = $offerRates['ItmRate'] -  ($offerRates['ItmRate'] * $Offers['Disc_pcent'] / 100);
						}

						$kitchenObj['ItemId'] = $Disc_ItemId;
						$kitchenObj['TaxType'] =$postData['tax_type'];
						$kitchenObj['Qty'] = $Offers['Disc_Qty'];
						$kitchenObj['ItmRate'] = $offerRate;
						$kitchenObj['OrigRate'] = $itmrate; 	//(m.Value)
						$kitchenObj['Itm_Portion'] = $Offers['Disc_IPCd'];
						$kitchenObj['SchCd'] = $schcd;
						$kitchenObj['SDetCd'] = $sdetcd;
						insertRecord('Kitchen', $kitchenObj);
					}
				}else{
					
					$kitchenObj['ItemId'] = $postData['itemId'];
					$kitchenObj['TaxType'] =$postData['tax_type'];
					$kitchenObj['Qty'] = $postData['qty'];
					$kitchenObj['ItmRate'] = $itmrate;
					$kitchenObj['OrigRate'] = $itmrate; 	//(m.Value)
					$kitchenObj['Itm_Portion'] = $postData['itemPortionText'];
					
					$kitchenObj['SchCd'] = $schcd;
					$kitchenObj['SDetCd'] = $sdetcd;

					// echo "<pre>";
					// print_r($kitchenObj);
					// die;
					insertRecord('Kitchen', $kitchenObj);
				}

				// end offer				
				
				$response = [
					"status" => 1,
					"msg" => "success",
				];
				
				$response["redirectTo"]  = base_url('customer/cart');

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

			// echo "<pre>";
			// print_r($response);
			// die;

			echo json_encode($response);
			die();
		}

		if (isset($postData['setCustomItem']) && !empty($postData['setCustomItem'])) {

			$this->db2->trans_start();
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
						$kitchenMainObj['Stat'] = 0;
						if($TableAcceptReqd > 0){
							$kitchenMainObj['Stat'] = 10;
						}
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
						$getFKOT = $this->db2->query("SELECT max(FKOTNO) as FKOTNO FROM Kitchen WHERE EID=$EID AND KitCd = $itemKitCd")->result_array();
						
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

					if($TableAcceptReqd > 0){
						$checkStat = $this->db2->select('Stat')->get_where('KitchenMain', array('CNo' => $CNo, 'EID' => $EID, 'BillStat' => 0))->row_array();
						$stat = $checkStat['Stat'];
					}else{
						$stat = 0;
					}
			
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

					$kitchenObj['CustItemDesc'] = $custItemDesc;
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

	private function getSchemeOfferList($schcd, $sdetcd){
		return $this->db2->select('c.SchNm, c.SchCd, cod.SDetCd, cod.SchDesc, c.PromoCode, c.SchTyp, c.Rank,cod.Disc_ItemId, cod.Qty,cod.Disc_Qty, cod.IPCd, cod.Disc_IPCd, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId')
						->join('CustOffers c', 'c.SchCd = cod.SchCd', 'inner')
						->get_where('CustOffersDet cod', array('c.SchCd' => $schcd,
						 'cod.SDetCd' => $sdetcd,
						 'c.Stat' => 0,
						 'cod.Stat' => 0,
						 'c.EID' => $this->EID,
						 'c.ChainId' => $this->ChainId))
						->row_array();
	}

	// private function getItemQuery($TableNo,$EID,$varCID)
 //    {
 //        return $this->db2->query("SELECT i.ItemId, i.ItemNm1".$_SESSION['item_name']." as ItemNm,i.NV, mc.TaxType, i.MCatgId, (Select ip.Name1 from MenuItemRates m , ItemPortions ip, Eat_tables et  where m.ItemId = i.ItemId and m.SecId = et.SecId and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID and ip.IPCd = m.Itm_Portion order by m.ItmRate ASC LIMIT 1 ) as Itm_Portion, (Select ip.IpCd from MenuItemRates m , ItemPortions ip, Eat_tables et  where m.ItemId = i.ItemId and m.SecId = et.SecId and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID and ip.IPCd = m.Itm_Portion order by m.ItmRate ASC LIMIT 1 ) as Itm_Portion_Code, (Select m.ItmRate from MenuItemRates m , ItemPortions ip, Eat_tables et where m.ItemId = i.ItemId AND  m.SecId = et.SecId  and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID order by m.ItmRate ASC LIMIT 1) as Value, AvgRtng, ItmDesc".$_SESSION['item_desc']." as ItmDesc, ItemNm as imgSrc, ItemTyp, i.KitCd, UItmCd, FID, ItemAttrib, PckCharge, MTyp, Ingeredients, MaxQty, PrepTime, ItemSale,ItemTag from MenuItem i, MenuCatg mc where mc.MCatgId = i.MCatgId   AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) $varCID AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID = $EID and md.Chainid=i.ChainId) order by i.Rank")->result_array();
 //    }

    private function getMenuCatgData($EID, $cId)
    {
        return $this->db2->query("SELECT mc.MCatgId, mc.Name1, mc.CTyp, mc.TaxType, f.FID, f.fIdA, f.Opt, f.AltOpt from MenuCatg mc, MenuItem i, Food f where  i.MCatgId=mc.MCatgId AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) AND mc.CID = :cId AND mc.EID=i.EID AND mc.Stat = 0 AND mc.CTyp = f.CTyp and f.LId = 1 and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID=$EID and md.Chainid=i.ChainId) group by mc.MCatgId, mc.Name1, mc.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt order by mc.Rank " , ["cId" => $cId])->result_array();
    }

    public function getCustomDetails($itemTyp, $ItemId, $itemPortionCode, $FID)
    {
    	$sql = '';
    	if($FID == 1){
    		$sql = " and itd.FID = $FID";
    	}else if($FID == 2){
    		$sql = " and (itd.FID = 1 or itd.FID = 2)";
    	}

        if($itemTyp == 125){
            return $this->db2->query("SELECT itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, itg.ItemGrpName, itd.Name, itd.Rate, itg.Reqd From ItemTypesGroup itg join ItemTypesDet itd on itg.ItemGrpCd = itd.ItemGrpCd AND itg.ItemTyp = $itemTyp and itg.ItemId = $itemId and itd.Itm_Portion= $itemPortionCode $sql order by itg.Rank, itd.Rank")->result_array();
        }else{
            return $this->db2->query("SELECT itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, itg.ItemGrpName, itd.Name, itd.Rate, itg.Reqd From ItemTypesGroup itg join ItemTypesDet itd on itg.ItemGrpCd = itd.ItemGrpCd AND itg.ItemTyp = $itemTyp and itd.Itm_Portion= $itemPortionCode $sql order by itg.Rank, itd.Rank")->result_array();

            // $this->db2->query("SELECT itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, itg.ItemGrpName, itd.Name, itd.Rate, itg.Reqd From ItemTypesGroup itg join ItemTypesDet itd on itg.ItemGrpCd = itd.ItemGrpCd AND itg.ItemTyp = $itemTyp and itd.Itm_Portion= $itemPortionCode $sql order by itg.Rank, itd.Rank")->result_array();
        }
            // print_r($this->db2->last_query());
            // die;
    }

	// public function ItemQuery($TableNo,$EID,$varCID)
	// {
	// 	return $this->db2->query("SELECT i.ItemId, i.ItemNm1".$_SESSION['item_name'].", mc.TaxType, i.MCatgId, et.TblTyp, (Select ip.Name1 from MenuItemRates m , ItemPortions ip, Eat_tables et  where m.ItemId = i.ItemId and m.SecId = et.SecId and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID and ip.IPCd = m.Itm_Portion order by m.ItmRate ASC LIMIT 1 ) as Itm_Portion, (Select m.ItmRate from MenuItemRates m , ItemPortions ip, Eat_tables et where m.ItemId = i.ItemId AND  m.SecId = et.SecId  and et.TableNo = '$TableNo' and m.EID = et.EID and et.EID = $EID order by m.ItmRate ASC LIMIT 1) as Value, AvgRtng, ItmDesc".$_SESSION['item_desc'].", ItemNm as imgSrc, ItemTyp, i.KitCd, UItmCd, FID, ItemAttrib, PckCharge, MTyp, Ingeredients,  PrepTime, ItemSale,ItemTag from MenuItem i, MenuCatg mc where mc.MCatgId = i.MCatgId   AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) $varCID AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID = $EID and md.Chainid=i.ChainId) order by i.Rank")->result_array();
	// }

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
			$kitchenMainObj['Stat'] = 0;
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
		$langId = $this->session->userdata('site_lang');
        
        $lname = "mi.ItemNm$langId as LngName";
        $cuiname = "c1.Name$langId as cuiName";
        $mname = "m.Name$langId as mcName";
        $ipname = "ip.Name$langId as portionName";
        $scName = "c.SchNm$langId as SchNm";
        $scDesc = "cod.SchDesc$langId as SchDesc";
		return $this->db2->query("SELECT $scName, c.SchCd, cod.SDetCd, $scDesc, c.PromoCode, c.SchTyp, c.Rank, cod.Qty as FreeQty, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.SchImg, $lname ,mi.ItemId, $mname, $cuiname, $ipname from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd left outer join Cuisines as c1 on cod.CID=c1.CID left outer join MenuCatg as m on cod.MCatgId = m.MCatgId left outer join ItemPortions as ip on cod.IPCd = ip.IPCd left outer join ItemTypes as i on cod.ItemTyp = i.ItmTyp left outer join MenuItem as mi on mi.ItemId = cod.ItemId where (IF(c.ToTime < c.FrmTime, (CURRENT_TIME() >= c.FrmTime OR CURRENT_TIME() <= c.ToTime) ,(CURRENT_TIME() >= c.FrmTime AND CURRENT_TIME() <= c.ToTime)) OR IF(c.AltToTime < c.AltFrmTime, (CURRENT_TIME() >= c.AltFrmTime OR CURRENT_TIME() <= c.AltToTime) ,(CURRENT_TIME() >=c.AltFrmTime AND CURRENT_TIME() <= c.AltToTime))) and ((DAYOFWEEK(CURDATE()) >= c.FrmDayNo and DAYOFWEEK(CURDATE()) <= c.ToDayNo)  or DayNo = 0) and (DATE(CURDATE()) >= c.FrmDt and DATE(CURDATE()) <= c.ToDt) group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank")->result_array();
	}

	public function getEntertainmentList(){
		return $this->db2->select('ee.Dayno, DAYNAME(ee.Dayno) as DayName, e.Name, ee.performBy,ee.PerImg')
						->order_by('ee.Dayno', 'ASC')
                        ->join('Entertainment e', 'e.EntId = ee.EntId', 'inner')
                        ->get_where('Eat_Ent ee', array('ee.Stat' => 0,
                        				 'ee.EID' => authuser()->EID,
                        				 'ee.Dayno >=' => date('Y-m-d')
                        				))
                        ->result_array();
	}

	public function checkRecommendation($itemId){
		$rec = $this->db2->select_max('RecNo')
						->get_where('MenuItem_Recos', 
								array('EID' => $this->EID, 'ItemId' => $itemId ))
						->row_array();
		$val = 0;
		if(!empty($rec['RecNo'])){
			$val = $rec['RecNo'];
		}
		return $val;
	}

	// bill.repo.php

	public function getBillAmount($EID, $CNo){
		if($CNo > 0){
			$EType = $this->session->userdata('EType');
			$stat = ($EType == 5)?3:2;

            $langId = $this->session->userdata('site_lang');
            $lname = "m.ItemNm$langId";
            $ipName = "ip.Name$langId as Portions";

		return $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,sum(k.Qty) as Qty ,k.OrigRate, k.ItmRate,sum(k.OrigRate*k.Qty) as OrdAmt, (SELECT sum(k1.OrigRate-k1.ItmRate)*k1.Qty from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID and k1.CNo =$CNo AND (k1.Stat = $stat) and (k1.OrigRate-k1.ItmRate) > 0) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotPckCharge, $ipName, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name, k.TA  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and k.CNo = km.CNo AND (km.CNo = $CNo OR km.MCNo = $CNo) group by km.CNo, k.ItemId, k.Itm_Portion ,k.CustItemDesc order by TaxType, m.ItemNm1 Asc")->result_array();
		}
	}

// create common for customer and rest side showing and insert to billing and billing tax table
	public function fetchBiliingData($EID, $CNo, $MergeNo, $per_cent){
		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2; 

		$langId = $this->session->userdata('site_lang');
        $lname = "m.ItemNm$langId";
        $ipName = "ip.Name$langId as Portions";

		return $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,sum(k.Qty * $per_cent) as Qty ,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty * $per_cent),(k.ItmRate*k.Qty * $per_cent))) as OrdAmt, km.OType, km.LoginCd, (SELECT sum((k1.OrigRate-k1.ItmRate) * $per_cent) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty * $per_cent) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotPckCharge, $ipName, km.BillDiscAmt * $per_cent as BillDiscAmt, km.DelCharge * $per_cent as DelCharge, km.RtngDiscAmt * $per_cent as RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.MergeNo,km.TableNo, km.MCNo, km.CustId, km.CellNo, k.ItemId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.payRest = 0 and km.payRest = 0 and (k.CNo = km.CNo OR km.MCNo = k.MCNo) and (km.MCNo = $CNo and km.CNo = $CNo) and (km.MergeNo = k.MergeNo and km.MergeNo = '$MergeNo') group by km.MCNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.ItemNm1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.ItemNm1 Asc")->result_array();
	}

	private function calculatTotalTax($total_tax, $new_tax){
		return $total_tax + $new_tax;
	}

	public function billGenerated($EID, $CNo, $postData){
// echo "<pre>";
// print_r($postData);
// die;
        $totalAmount = $postData["orderAmount"];
        $paymentMode = $postData["paymentMode"];

		$CustId = $this->session->userdata('CustId');
        $ChainId = authuser()->ChainId;
        $ONo = $this->session->userdata('ONo');
        $CustNo = $this->session->userdata('CustNo');
        $COrgId = $this->session->userdata('COrgId');

        $EType = $this->session->userdata('EType');
        $EDTs = $this->session->userdata('EDT');

        $per_cent = 1;
        if($paymentMode == 'Due' || $paymentMode == 'RestSplitBill'){
        	$CellNo = $postData['CellNo'];
        	$per_cent = $postData['per_cent'];
        }else{
        	$CellNo = $this->session->userdata('CellNo');
        }

        $cust_discount = 0;
        // by customer
        $billingObjStat = 1;
        if($paymentMode == 'RCash' || $paymentMode == 'RestSplitBill'){
        	$MergeNo = $postData['MergeNo'];
        	$TableNo = $postData['TableNo'];
        	// by rest
        	$billingObjStat = 5;
        	$cust_discount = $postData['cust_discount'];
        }else{
        	$MergeNo = $this->session->userdata('MergeNo');
        	$TableNo = authuser()->TableNo;
        }

        $strMergeNo = "'".$MergeNo."'";
        // Due => split type, RCash => Restorent side(offline), multiPayment
        if($paymentMode == 'cash' || $paymentMode == 'RCash' || $paymentMode == 'Due' || $paymentMode == 'multiPayment' || $paymentMode == 'RestSplitBill'){
        	$orderId = 'NA';
        }else{
        	$orderId = $postData["orderId"];
        }        

        if($this->session->userdata('billFlag') > 0){
	        // delete billing, billingtax with existing cno
	        updateRecord('Billing', array('Stat' => 25), array('CNo' => $CNo,'EID' => $EID,'MergeNo' => $strMergeNo));

	        updateRecord('Kitchen', array('BillStat' => 0), array('EID' => $EID,
	        				'MCNo' => $CNo,'MergeNo' => $strMergeNo));
	        updateRecord('KitchenMain', array('BillStat' => 0), array('EID' => $EID,
	        				'MCNo' => $CNo,'MergeNo' => $strMergeNo));
	        $this->session->set_userdata('billFlag',0);
	        // end of code 
        }

		$kitcheData = getBillingDataByEID_CNo($EID, $CNo, $MergeNo, $per_cent);

		// echo "<pre>";
		// print_r($kitcheData);
		// die;
      
            if (empty($kitcheData)) {
                $response = [
                    "status" => 0,
                    "msg" => "NO BILL CREATION REQUIRED "
                ];
                
            } else {

            	$res = taxCalculateData($kitcheData, $EID, $CNo, $MergeNo);

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

                // if($splitTyp == 1){
                // 	//food and bar
                // 	$billingObjStat = 4;
                // }else if($splitTyp > 1){
                // 	//split bill
                // 	$billingObjStat = 5;
                // }
                
                // FOR ONLINE PAYMENTS
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
                $billingObj['PaymtMode'] = $paymentMode;
                $billingObj['PymtRef'] = $orderId;
                $billingObj['TotItemDisc'] = $TotItemDisc;
                $billingObj['BillDiscAmt'] = $BillDiscAmt;
                $billingObj['custDiscAmt'] = $cust_discount;
                $billingObj['TotPckCharge'] = $TotPckCharge;
                $billingObj['DelCharge'] = $DelCharge;
                $billingObj['PymtType'] = 0;
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
                
                // echo "<pre>";
                // print_r($billingObj);
                // die;
                $this->db2->trans_start();

                	if(($EType == 1) && ($EDTs > 0)){
                		$edtMax = $this->db2->select('MCNo, ItemId, EDT, max(EDT) as EDT')
                						->get_where('Kitchen',array('MCNo' => $CNo, 'EID' => $EID,'Stat' => 2))->row_array();
                		if(!empty($edtMax)){
                			updateRecord('Kitchen', array('EDT' => $edtMax['EDT']), array('MCNo' => $CNo, 'EID' => $EID) );
                		}
                	}
            
                    $lastInsertBillId = insertRecord('Billing', $billingObj);

                    if(!empty($lastInsertBillId)){
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

                    $genTblDb = $this->load->database('GenTableData', TRUE);
                    // store to gen db
                    $custPymtObj['BillId'] = $lastInsertBillId;
                    $custPymtObj['CustId'] = $CustId;
                    $custPymtObj['BillNo'] = $newBillNo;
                    $custPymtObj['EID'] = $EID;
                    $custPymtObj['PaidAmt'] = $totalAmount;
                    $custPymtObj['PaymtMode'] = $paymentMode;
                    $genTblDb->insert('CustPymts', $custPymtObj);
                    
                    $kstat = ($EType == 5)?3:2;
                    // check for customer split bill 9-jan-24
                    if($splitTyp == 0){

                    	$this->db2->query("UPDATE Kitchen SET BillStat = $billingObjStat  WHERE EID = $EID and (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 and Stat = $kstat ");

                    	$this->db2->query("UPDATE KitchenMain SET BillStat = $billingObjStat WHERE (MCNo = $CNo or MergeNo = $strMergeNo) AND BillStat = 0 AND EID = $EID ");
                    }

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
            
            return $response;
	}

	public function getOrderDetailsByTableNo($MergeNo){	
		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2;

		$whr = " k.CNo = km.CNo ";
		return $this->db2->select('km.CustId, m.ItemId,m.ItemNm1,k.Qty ,k.ItmRate,  sum(k.OrigRate*k.Qty) as OrdAmt,km.CNo,km.CellNo, km.BillStat, k.Stat, km.MergeNo')
						->order_by('km.CNo', 'asc')
						->group_by('km.CNo, km.CellNo')
						->join('Kitchen k', 'k.MergeNo = km.MergeNo', 'inner')
						->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
						->where($whr)
						->get_where('KitchenMain km', array('km.MergeNo' => $MergeNo, 
							'km.EID' => $this->EID,
							'k.BillStat' => 0,
							'km.BillStat' => 0,
							'k.EID' => $this->EID,
							'k.Stat' => $stat
							 )
							)
						->result_array();
	}

	public function getPaymentModes(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		return $this->db2->select("PymtMode, $lname ,Company, CodePage1")->get_where('ConfigPymt', array('Stat' => 1))->result_array();
	}

	public function getSplitPayments($billId){
		return $this->db2->get_where('BillPayments', array('BillId' => $billId,'Stat' => 1,'EID' => $this->EID))->result_array();
	}

	public function getShareDetails($billId, $MCNo){
		return $this->db2->select('km.CellNo, km.CustId, b.BillId, km.MCNo, km.CNo')
						->join('KitchenMain km', 'km.MCNo = b.CNo', 'inner')
						->get_where('Billing b', array('b.BillId' => $billId, 'b.CNo' => $MCNo, 'EID' => $this->EID))
						->result_array();
	}

	public function checkCheckoutItem($custId, $CNo, $stat){
		return $this->db2->select('OrdNo')->get_where('Kitchen', array('CustId' => $custId, 'CNo' => $CNo, 'Stat' => $stat, 'BillStat' => 0, 'EID' => $this->EID))->row_array();
	}

	public function getCountryList(){
		return $this->db2->select('*')
					->order_by('country_name', 'ASC')
					->get('countries')->result_array();
	}

	public function getCityListByCountry($phone_code){
		return $this->db2->get_where('city', array('status' => 0))->result_array();
	}

	public function getUserDetails($custId){
		return $this->db2->select('*')
                                    ->get_where('Users', array('CustId' => $custId))
                                    ->row_array();
	}

	public function getCurrenOrderBill($custId){
		$EID = authuser()->EID;
		return $this->db2->select('BillStat, CNo')
						->order_by('CNo', 'DESC')
						->get_where('KitchenMain', array('CustId' => $custId,
														 'EID' => $EID
														)
									)->row_array();
	}

	public function checkBillCreation($MCNo){
		return $this->db2->select('BillId, CNo')
						->where_in('Stat', array(1,5))
						->get_where('Billing', array('CNo' => $MCNo, 'EID' => authuser()->EID))
						->row_array();
	}

	public function getBillLinks($billId, $MCNo){
		
		if($this->session->userdata('billSplit') > 1){
			$CellNo = $this->session->userdata('CellNo');
			$hours_2 = date('Y-m-d H:i:s', strtotime("-2 hours"));

			$this->db2->where('billDate >', $hours_2);
		}else{
			$this->db2->where('billId', $billId);
		}
		$this->db2->where('MCNo', $MCNo);

		return $this->db2->get_where('BillingLinks', array(
									'EID' => authuser()->EID
									))
				->result_array();
	}

	public function getTableDetails($table){
		return $this->db2->select('TblTyp, Capacity, SecId, CCd')->get_where('Eat_tables', array('EID' => authuser()->EID, 'TableNo' => $table))->row_array();
	}


	
}
