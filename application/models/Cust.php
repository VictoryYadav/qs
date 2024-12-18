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
        $lname = "ec.Name$langId";
        $select_sql = "c.CID, (case when $lname != '-' Then $lname ELSE ec.Name1 end) as Name";

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
        $lname = "Name$langId";

        $select_sql = "MCatgId, CTyp, CID, (case when $lname != '-' Then $lname ELSE Name1 end) as LngName";

		$data['mcat'] = $this->db2->select($select_sql)
								->order_by('Rank', 'ASC')
								->get_where('MenuCatg', 
									array('CID' => $cid, 
										'EID' => $this->EID)
								    )
								->result_array();
		
		$this->session->set_userdata('f_fid', 0);
		$this->session->set_userdata('f_cid', $cid);
		$this->session->set_userdata('f_mcat', $data['mcat'][0]['MCatgId']);

		return $data;
	}

	function getItemDetailLists($CID, $mcat, $fl){

		$langId = $this->session->userdata('site_lang');
        $lname = "mi.Name$langId";
        $iDesc = "mi.ItmDesc$langId";
        $ingeredients = "mi.Ingeredients$langId";
        $Rmks = "mi.Rmks$langId";

		$this->session->set_userdata('f_cid', $CID);
        $tableNo = authuser()->TableNo;

		$where = "mi.Stat = 0 and (DAYOFWEEK(CURDATE()) = mi.DayNo OR mi.DayNo = 8)  AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime)))";
// et.TblTyp
        $select_sql = "mc.TaxType, mc.DCd, mi.KitCd,mc.CTyp, mi.ItemId, mi.ItemTyp, mi.NV, mi.PckCharge, (case when $lname != '-' Then $lname ELSE mi.Name1 end) as itemName, (case when $iDesc != '-' Then $iDesc ELSE mi.ItmDesc1 end) as itemDescr, (case when $ingeredients != '-' Then $ingeredients ELSE mi.Ingeredients1 end) as Ingeredients, (case when $Rmks != '-' Then $Rmks ELSE mi.Rmks1 end) as Rmks, mi.PrepTime, mi.AvgRtng, mi.FID,mi.ItemAttrib, mi.ItemSale, mi.ItemTag, mi.Name1 as imgSrc, mi.UItmCd,mi.CID ,mi.MCatgId,mi.videoLink,  (select mir.OrigRate FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and mir.OrigRate > 0 and et.TableNo = $tableNo AND et.EID = '$this->EID' AND mir.EID = '$this->EID' AND mir.ItemId = mi.ItemId and mi.Stat = 0 ORDER BY mir.OrigRate ASC LIMIT 1) as ItmRate, (select mir.Itm_Portion FROM MenuItemRates mir, Eat_tables et where et.SecId = mir.SecId and mir.OrigRate > 0 and et.TableNo = $tableNo AND et.EID = '$this->EID' AND mir.EID = '$this->EID' AND mir.ItemId = mi.ItemId and mi.Stat = 0 ORDER BY mir.OrigRate ASC LIMIT 1) as Itm_Portion, (select et1.TblTyp from Eat_tables et1 where et1.EID = '$this->EID' and et1.TableNo = $tableNo) as TblTyp";
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
                        ->where($where)
                        ->get_where('MenuCatg mc', array(
                            'mc.CID' => $CID,
                            'mc.EID' => $this->EID,
                            'mi.EID' => $this->EID
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

	public function getMenuItemRates($EID, $itemId, $cid,$MCatgId,$ItemTyp){
		$langId = $this->session->userdata('site_lang');
        $ipName = "ip.Name$langId";

        $TableNo = authuser()->TableNo;
        $whr = "et.TableNo = $TableNo";
		return $this->db2->select("ip.IPCd as IPCode, mir.OrigRate as ItmRate, (case when $ipName != '-' Then $ipName ELSE ip.Name1 end) as Name")
						->order_by('ItmRate', 'ASC')
						->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
						->join('ItemPortions ip', 'ip.IPCd = mir.Itm_Portion', 'inner')
						->join('Eat_tables et', 'et.SecId = mir.SecId', 'inner')
						->where($whr)
						->get_where('MenuItem mi', array(
							'mi.ItemId' => $itemId,
							'mir.EID' => $EID
							))
						->result_array();
						
	}

	public function getItemOfferAjax($postData){
		if (isset($postData['getOrderData']) && $postData['getOrderData'] == 1) {

		    $itemId = $postData['itemId'];
		    $cid = $postData['cid'];
		    $itemTyp = $postData['itemTyp'];
		    $MCatgId = $postData['MCatgId'];
		    $itemPortion = $postData['itemPortion'];
		    $FID = $postData['FID'];
		    $itemsale = $postData['itemsale'];

		    if(!empty($cid)){
		    	$this->db2->or_where('cod.CID', $cid);
		    }
		    if(!empty($itemId)){
		    	$this->db2->or_where('cod.ItemId', $itemId);
		    }
		    if(!empty($MCatgId)){
		    	$this->db2->or_where('cod.MCatgId', $MCatgId);
		    }
		    if(!empty($itemsale)){
		    	$this->db2->or_where('cod.ItemSale', $itemsale);
		    }
		    if(!empty($itemPortion)){
		    	$this->db2->or_where('cod.IPCd', $itemPortion);
		    }
		    if(!empty($itemTyp)){
		    	$this->db2->or_where('cod.ItemTyp', $itemTyp);
		    }
		    
		    $langId = $this->session->userdata('site_lang');
        	$scName = "c.SchNm$langId";
        	$scDesc = "cod.SchDesc$langId";

        	$whr = "(time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)";
        	return $this->db2->select("(case when $scName != '-' Then $scName ELSE c.SchNm1 end) as SchNm, c.SchCd, cod.SDetCd, (case when $scDesc != '-' Then $scDesc ELSE cod.SchDesc1 end) as SchDesc, c.PromoCode, c.SchTyp, c.Rank,cod.Disc_ItemId, cod.Qty,cod.Disc_Qty, cod.IPCd, cod.Disc_IPCd, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId")
        					->order_by('c.Rank, cod.Rank', 'ASC')
        					->group_by('c.schcd, cod.sDetCd')
        					->join('CustOffers c', 'c.SchCd=cod.SchCd', 'inner')
        					->where($whr)
        					->get_where('CustOffersDet cod', array(
        										'cod.Stat' => 0,
        										'c.Stat' => 0,
        										'c.EID' => $this->EID,
        										'c.SchTyp' => 2
        											)
        								)
        						->result_array();
		}
	}

	public function getItem_details_ajax($postData){

		$COrgId = $this->session->userdata('COrgId');
		$CustNo = $this->session->userdata('CustNo');
		$EID = $this->EID;
		$ChainId = $this->ChainId;
		$ONo = $this->session->userdata('ONo');
		$EType = $this->session->userdata('EType');

		$TableNo = $this->session->userdata('TableNo');
		$TableNoStr = authuser()->TableNo;

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
				$tmpOrigRate = str_replace(" ", "", $postData['tmpOrigRate']);
				$serveTime = 0;
				$newServeTime = 0;
				$postData['prepration_time'] = (!empty($postData['prepration_time'])?$postData['prepration_time']:'00');
				$prepration_time = "00:" . $postData['prepration_time'] . ":00";

				$OType = 0;
				$TblTyp = $postData['TblTyp'];

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
						$kitchenMainObj['EID'] 	  = $EID;
						$kitchenMainObj['ChainId']= $ChainId;
						$kitchenMainObj['ONo'] 	  = $ONo;
						$kitchenMainObj['OType']  = $OType;
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
						
						$kitchenMainObj['LoginCd'] = 0;
						$kitchenMainObj['payRest'] = 0;
						// echo "<pre>";
						// print_r($kitchenMainObj);die;
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
				$kitchenObj['DCd'] = $postData['DCd'];
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
				$kitchenObj['langId'] =$this->session->userdata('site_lang');
				$kitchenObj['tmpOrigRate'] = $tmpOrigRate;
				$kitchenObj['tmpItmRate'] = $tmpOrigRate;
				if($kitchenObj['TA'] == 1){
					$kitchenObj['PckCharge'] = $postData['PckCharge'];
				}

				if($kitchenObj['TA'] == 2){
					if($this->session->userdata('CharityCharge') == 1){
						$kitchenObj['PckCharge'] = $postData['PckCharge'];
					}
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
							// $custItemDesc = implode(',', $custItemDescArray);
							$kitchenObj['CustItemDesc'] = implode(',', $_POST['CustItemDesc']);
							$kitchenObj['CustItemId'] 	= implode(',', $_POST['custItemIds']);
						}
					}
				}
				//end custom offers
				
				$schcd = !empty($postData['schcd'])?$postData['schcd']:0;
				$sdetcd = 0;
				$childOrdNo = 0;
				if(!empty($schcd)){
					
					$sdetcd = !empty($postData['sdetcd'])?$postData['sdetcd']:0;
					$Offers = $this->getSchemeOfferList($schcd, $sdetcd);
					
					$offerOrigRate = $itmrate;
					if(!empty($Offers)){
						
						$kitchenObj['ItemId'] = $postData['itemId'];
						$kitchenObj['Itm_Portion'] = $postData['itemPortionText'];
						if($Offers['ItemId'] > 0){
							$kitchenObj['ItemId'] = $Offers['ItemId'];
							if($Offers['IPCd'] > 0){
								$kitchenObj['Itm_Portion'] = $Offers['IPCd'];
							}
						}
						$kitchenObj['Qty'] = $Offers['Qty'];
						$kitchenObj['ItmRate'] = $itmrate;
						$kitchenObj['OrigRate'] = $itmrate; 	
						$kitchenObj['tmpOrigRate'] = $itmrate;
						$kitchenObj['tmpItmRate'] = $itmrate;
						// $kitchenObj['Itm_Portion'] = $postData['itemPortionText'];
						
						$kitchenObj['SchCd'] = $schcd;
						$kitchenObj['SDetCd'] = $sdetcd;

						$childOrdNo = insertRecord('Kitchen', $kitchenObj);
						updateRecord('Kitchen', array('childOrdNo' => $childOrdNo), array('OrdNo' => $childOrdNo, 'EID' => $EID));

						// for offer 
						$Disc_ItemId = $Offers['Disc_ItemId'];
						$Disc_IPCd = $Offers['Disc_IPCd'];
						$offerRate = $itmrate - ($itmrate * $Offers['Disc_pcent'] / 100);

						if($Disc_ItemId > 0){
							if($Disc_ItemId != $postData['itemId'] || $Disc_IPCd != $Offers['IPCd']){
								$offerRates = $this->db2->query("select mi.OrigRate, mi.ItmRate, mc.TaxType, mc.DCd, m.ItemTyp, m.PckCharge, m.KitCd from MenuItemRates as mi, MenuCatg as mc, MenuItem m where m.ItemId = mi.ItemId and mc.MCatgId = m.MCatgId  and m.EID = mc.EID and mi.EID=m.EID and  mi.EID = $this->EID and mi.ItemId = $Disc_ItemId and mi.Itm_Portion = $Disc_IPCd and mi.SecId = (select SecId from Eat_tables where TableNo = $TableNo and EID = $this->EID)")->row_array();

								$offerRate = $offerRates['OrigRate'] -  ($offerRates['OrigRate'] * $Offers['Disc_pcent'] / 100);
								$offerOrigRate = $offerRates['OrigRate'];
								$kitchenObj['TaxType'] = $offerRates['TaxType'];
								$kitchenObj['KitCd'] =	$offerRates['KitCd'];
								$kitchenObj['DCd'] = $offerRates['DCd'];
								$kitchenObj['ItemTyp'] = $offerRates['ItemTyp'];
								if($kitchenObj['TA'] == 1){
									$kitchenObj['PckCharge'] = $offerRates['PckCharge'];
								}
								$kitchenObj['OrigRate'] = $offerRates['OrigRate'];
								$kitchenObj['ItmRate'] = $offerRates['ItmRate'];

								$kitchenObj['tmpOrigRate'] = $offerRates['OrigRate'];
								$kitchenObj['tmpItmRate'] = $offerRates['ItmRate'];
							}

							$kitchenObj['ItemId'] = $Disc_ItemId;
							$kitchenObj['Itm_Portion'] = $Offers['Disc_IPCd'];
							
							if($Offers['SchTyp'] > 1) {
								$offerRate = $itmrate - ($itmrate * $Offers['DiscItemPcent'] / 100);
								if($Disc_ItemId > 0){
									if($Disc_ItemId != $postData['itemId'] || $Disc_IPCd != $Offers['IPCd']){
										
										$offerRate = $offerRates['OrigRate'] -  ($offerRates['OrigRate'] * $Offers['DiscItemPcent'] / 100);
										$offerOrigRate = $offerRates['OrigRate'];
									}	
								}						
							}
							
							$kitchenObj['Qty'] = $Offers['Disc_Qty'];
							$kitchenObj['OrigRate'] = $offerOrigRate;
							$kitchenObj['ItmRate'] = $offerRate;

							$kitchenObj['tmpOrigRate'] = $offerOrigRate;
							$kitchenObj['tmpItmRate'] = $offerRate;

							$kitchenObj['SchCd'] = $schcd;
							$kitchenObj['SDetCd'] = $sdetcd;
							$kitchenObj['childOrdNo'] = $childOrdNo;
						
							insertRecord('Kitchen', $kitchenObj);

						}else if($Offers['DiscItemPcent'] > 0){
                            $perc_Amt = ($offerOrigRate * $Offers['DiscItemPcent'] / 100);
                            $perc_Amt = round($perc_Amt) * $Offers['Qty'];
                            if($Offers['DiscMaxAmt'] > 0){
                                if($perc_Amt <= $Offers['DiscMaxAmt']){
                                    $itmrate = $offerOrigRate - $perc_Amt;
                                }else{
                                    $itmrate = $offerOrigRate - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                                }
                            }else{
                                $itmrate = $offerOrigRate - $perc_Amt;
                            }
                        }else if($Offers['DiscMaxAmt'] > 0){
                            if($Offers['DiscMaxAmt'] > 0){
                                $itmrate = $offerOrigRate - round($Offers['DiscMaxAmt']/$Offers['Qty']);
                            }
                        }
                        updateRecord('Kitchen', array('ItmRate' => $itmrate, 'tmpItmRate' => $itmrate), array('OrdNo' => $childOrdNo, 'EID' => $EID));

                        if($Offers['ItemTyp'] == 4){
                        	
                        	$kt = getRecords('Kitchen', array('OrdNo' => $childOrdNo,  'EID' => $EID));
                        	
	                        if($Offers['Qty'] > 1){
	                        	unset($kt['OrdNo']);
	                            for ($i=0; $i < $Offers['Qty']-1; $i++) { 
	                                $kt['childOrdNo'] = $childOrdNo;
	                                $kt['Qty'] = 1;
	                                insertRecord('Kitchen', $kt);
	                            }
	                            updateRecord('Kitchen', array('Qty' => 1), array('OrdNo' => $childOrdNo, 'EID' => $EID));
	                        }
						}
					}
				}else{
					
					$kitchenObj['ItemId'] = $postData['itemId'];
					$kitchenObj['TaxType'] =$postData['tax_type'];
					$kitchenObj['Qty'] = $postData['qty'];
					$kitchenObj['ItmRate'] = $itmrate;
					$kitchenObj['OrigRate'] = $itmrate; 

					// $kitchenObj['tmpOrigRate'] = $itmrate;
					// $kitchenObj['tmpItmRate'] = $itmrate;

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

			echo json_encode($response);
			die();
		}

		if (isset($postData['checkMaxQty']) && $postData['checkMaxQty'] == 1) {
			$itemId = $postData['itemId'];
			$getData = $this->db2->query("SELECT Sum(Qty) as qty FROM `Kitchen` WHERE EID = $EID and ChainId = $ChainId and ItemId = $itemId  and date(LstModDt)=date(now()) and Stat != 4 and Stat != 6 and Stat != 7 and Stat != 99")
			->result_array();
			echo $postData['maxQty'] - ($getData[0]['qty'] + $postData['enterQty']);
		}

	}

	private function getSchemeOfferList($schcd, $sdetcd){

		$langId = $this->session->userdata('site_lang');
    	$scName = "c.SchNm$langId";
    	$scDesc = "cod.SchDesc$langId";

		return $this->db2->select("(case when $scName != '-' Then $scName ELSE c.SchNm1 end) as SchNm, c.SchCd, cod.SDetCd, (case when $scDesc != '-' Then $scDesc ELSE cod.SchDesc1 end) as SchDesc, c.PromoCode, c.SchTyp, c.SchCatg, c.Rank,cod.Disc_ItemId, cod.Qty, cod.Disc_Qty, cod.IPCd, cod.Disc_IPCd, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId, cod.DiscItemPcent, cod.DiscMaxAmt")
						->join('CustOffers c', 'c.SchCd = cod.SchCd', 'inner')
						->get_where('CustOffersDet cod', array('c.SchCd' => $schcd,
						 'cod.SDetCd' => $sdetcd,
						 'c.Stat' => 0,
						 'cod.Stat' => 0,
						 'c.EID' => $this->EID,
						 'c.ChainId' => $this->ChainId))
						->row_array();
	}

    private function getMenuCatgData($EID, $cId)
    {
        return $this->db2->query("SELECT mc.MCatgId, mc.Name1, mc.CTyp, mc.TaxType, f.FID, f.fIdA, f.Opt, f.AltOpt from MenuCatg mc, MenuItem i, Food f where  i.MCatgId=mc.MCatgId AND i.Stat = 0 and (DAYOFWEEK(CURDATE()) = i.DayNo OR i.DayNo = 0) AND (IF(ToTime < FrmTime, (CURRENT_TIME() >= FrmTime OR CURRENT_TIME() <= ToTime) ,(CURRENT_TIME() >= FrmTime AND CURRENT_TIME() <= ToTime)) OR IF(AltToTime < AltFrmTime, (CURRENT_TIME() >= AltFrmTime OR CURRENT_TIME() <= AltToTime) ,(CURRENT_TIME() >= AltFrmTime AND CURRENT_TIME() <= AltToTime))) AND mc.CID = :cId AND mc.EID=i.EID AND mc.Stat = 0 AND mc.CTyp = f.CTyp and f.LId = 1 and i.ItemId Not in (Select md.ItemId from MenuItem_Disabled md where md.ItemId=i.ItemId and md.EID=$EID and md.Chainid=i.ChainId) group by mc.MCatgId, mc.Name1, mc.CTyp, f.FID, f.fIdA, f.Opt, f.AltOpt order by mc.Rank " , ["cId" => $cId])->result_array();
    }

    public function getCustomDetails($itemTyp, $ItemId, $itemPortionCode, $FID)
    {
    	// $whr = '';
    	if($FID == 1){
    		$whr = "mii.FID = $FID";
    		$this->db2->where($whr);
    	}else if($FID == 2){
    		$whr = "(mii.FID = 1 or mii.FID = 2)";
    		$this->db2->where($whr);
    	}

    	$langId = $this->session->userdata('site_lang');
        $ItemGrpName = "mi.Name$langId";
        $ItemNm = "mii.Name$langId as Name";

        if($itemTyp == 1){

        	$ItemGrpName = "mi.Name$langId as ItemGrpName";
        	$ItemNm = "mii.Name$langId as Name";

            return $this->db2->select("itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, (case when $ItemGrpName != '-' Then $ItemGrpName ELSE mi.Name1 end) as ItemGrpName, itd.ItemId, (case when $ItemNm != '-' Then $ItemNm ELSE mii.Name1 end) as Name, mir.OrigRate as Rate, itg.Reqd, itg.CalcType")
            		->order_by('itg.Rank, itd.Rank', 'ASC')
            		->join('ItemTypesDet itd', 'itg.ItemGrpCd = itd.ItemGrpCd', 'inner')
            		->join('MenuItem mi', 'mi.ItemId = itg.ItemId', 'inner')
            		->join('MenuItem mii', 'mii.ItemId = itd.ItemId', 'inner')
            		->join('MenuItemRates mir', 'mir.ItemId = mii.ItemId', 'inner')
            		->get_where('ItemTypesGroup itg', array(
            							'itg.EID' => $this->EID,
            							'itg.Stat' => 0,
            							'itg.ItemId' => $ItemId,
            							'mir.Itm_Portion' => $itemPortionCode,
            							'itg.ItemTyp' => 1
            									)
            					)
            		->result_array();
        }else{
            $ItemGrpName = "itg.Name$langId";
            return $this->db2->select("itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, (case when $ItemGrpName != '-' Then $ItemGrpName ELSE itg.Name1 end) as ItemGrpName, itd.ItemId, $ItemNm, mir.OrigRate as Rate, itg.Reqd, itg.CalcType")
            		->order_by('itg.Rank, itd.Rank', 'ASC')
            		->join('ItemTypesDet itd', 'itg.ItemGrpCd = itd.ItemGrpCd', 'inner')
            		->join('MenuItem mi', 'mi.ItemId = itg.ItemId', 'left')
            		->join('MenuItem mii', 'mii.ItemId = itd.ItemId', 'inner')
            		->join('MenuItemRates mir', 'mir.ItemId = mii.ItemId', 'inner')
            		->get_where('ItemTypesGroup itg', array(
            							'itg.EID' => $this->EID,
            							'itg.Stat' => 0,
            							'mir.Itm_Portion' => $itemPortionCode,
            							'itg.ItemTyp' => $itemTyp
            									)
            					)
            		->result_array();
        }
            
    }

	public function insertKitchenMain()
	{
		global $CNo, $EType, $CustId, $COrgId, $CustNo, $CellNo, $EID, $ChainId, $ONo, $TableNo, $kitchenMainObj;

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
        
        $lname = "mi.Name$langId";
        $cuiname = "c1.Name";
        $mname = "m.Name$langId";
        $ipname = "ip.Name$langId";
        $scName = "c.SchNm$langId";
        $scDesc = "cod.SchDesc$langId";

		return $this->db2->query("SELECT (case when $scName != '-' Then $scName ELSE c.SchNm1 end) as SchNm, c.SchCd, cod.SDetCd, (case when $scDesc != '-' Then $scDesc ELSE cod.SchDesc1 end) as SchDesc, c.PromoCode, c.SchTyp, c.Rank, cod.Qty as FreeQty, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.SchImg, (case when $lname != '-' Then $lname ELSE mi.Name1 end) as LngName ,mi.ItemId, (case when $mname != '-' Then $mname ELSE m.Name1 end) as mcName, (case when $cuiname != '-' Then $cuiname ELSE c1.Name end) as cuiName, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as portionName from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd left outer join Cuisines as c1 on cod.CID=c1.CID left outer join MenuCatg as m on cod.MCatgId = m.MCatgId left outer join ItemPortions as ip on cod.IPCd = ip.IPCd left outer join MenuTags as i on cod.ItemTyp = i.TagId left outer join MenuItem as mi on mi.ItemId = cod.ItemId where (IF(c.ToTime < c.FrmTime, (CURRENT_TIME() >= c.FrmTime OR CURRENT_TIME() <= c.ToTime) ,(CURRENT_TIME() >= c.FrmTime AND CURRENT_TIME() <= c.ToTime)) OR IF(c.AltToTime < c.AltFrmTime, (CURRENT_TIME() >= c.AltFrmTime OR CURRENT_TIME() <= c.AltToTime) ,(CURRENT_TIME() >=c.AltFrmTime AND CURRENT_TIME() <= c.AltToTime))) and ((DAYOFWEEK(CURDATE()) >= c.FrmDayNo and DAYOFWEEK(CURDATE()) <= c.ToDayNo)  or DayNo = 0) and (DATE(CURDATE()) >= c.FrmDt and DATE(CURDATE()) <= c.ToDt) group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank")->result_array();
	}

	public function getEntertainmentList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "e.Name$langId";

		return $this->db2->select("ee.Dayno, DAYNAME(ee.Dayno) as DayName, (case when $lname != '-' Then $lname ELSE e.Name1 end) as Name, ee.performBy,ee.PerImg")
						->order_by('ee.Dayno', 'ASC')
                        ->join('Entertainment e', 'e.EntId = ee.EntId', 'inner')
                        ->get_where('Eat_Ent ee', array('ee.Stat' => 0,
                        				 'ee.EID' => $this->EID,
                        				 'e.EID' => $this->EID,
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

	public function getBillAmount($EID, $CNo){
		if($CNo > 0){
			$EType = $this->session->userdata('EType');
			$stat = ($EType == 5)?3:2;

            $langId = $this->session->userdata('site_lang');
            $lname = "m.Name$langId";
            $ipname = "ip.Name$langId";

		return $this->db2->query("SELECT (case when $lname != '-' Then $lname ELSE m.Name1 end) as ItemNm, sum(k.Qty) as Qty ,k.OrigRate, k.ItmRate,sum(k.OrigRate*k.Qty) as OrdAmt, k.CustItemDesc, IFNULL((SELECT sum((k1.OrigRate-k1.ItmRate)*k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.CNo=km.CNo and k1.EID=km.EID and k1.CNo =$CNo AND (k1.Stat = $stat) and ((k1.OrigRate-k1.ItmRate) * k1.Qty) > 0),0) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.MCNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotPckCharge, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as Portions, km.BillDiscAmt, km.DelCharge, km.RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name, k.TA  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.BillStat = 0 and km.BillStat = 0 and (k.CNo = km.CNo or k.MCNo = km.MCNo) AND (km.CNo = $CNo OR km.MCNo = $CNo) group by km.MCNo, k.ItemId, k.Itm_Portion, k.TA, k.CustItemDesc order by TaxType, m.Name1 Asc")->result_array();
		}
	}

	public function fetchBiliingData($EID, $CNo, $MergeNo, $per_cent){
		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2; 

		$langId = $this->session->userdata('site_lang');
        $lname = "m.Name$langId";
        $ipname = "ip.Name$langId";

		return $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,sum(k.Qty * $per_cent) as Qty ,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty * $per_cent),(k.ItmRate*k.Qty * $per_cent))) as OrdAmt, km.OType, km.LoginCd, (SELECT sum((k1.OrigRate-k1.ItmRate)* k1.Qty * $per_cent) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty * $per_cent) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotPckCharge, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as Portions, km.BillDiscAmt * $per_cent as BillDiscAmt, km.DelCharge * $per_cent as DelCharge, km.RtngDiscAmt * $per_cent as RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.MergeNo,km.TableNo, km.MCNo, km.CustId, km.CellNo, k.ItemId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.payRest = 0 and km.payRest = 0 and (k.CNo = km.CNo OR km.MCNo = k.MCNo) and (km.MCNo = $CNo and km.CNo = $CNo) and (km.MergeNo = k.MergeNo and km.MergeNo = '$MergeNo') group by km.MCNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.Name1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.Name1 Asc")->result_array();
	}

	public function fetchBiliingData_CTyp($EID, $CNo, $MergeNo, $per_cent, $CTyp){
		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2; 

		$langId = $this->session->userdata('site_lang');
        $lname = "m.Name$langId";
        $ipname = "ip.Name$langId";

        $wh_ctyp = " and m.CTyp != 1";
        if($CTyp == 1){
        	$wh_ctyp = " and m.CTyp = $CTyp";
        }

		return $this->db2->query("SELECT (if (k.ItemTyp > 0,(CONCAT($lname, ' - ' , k.CustItemDesc)),($lname ))) as ItemNm,sum(k.Qty * $per_cent) as Qty ,k.ItmRate,  SUM(if (k.TA=1,((k.ItmRate+m.PckCharge)*k.Qty * $per_cent),(k.ItmRate*k.Qty * $per_cent))) as OrdAmt, km.OType, km.LoginCd, (SELECT sum((k1.OrigRate-k1.ItmRate)* k1.Qty * $per_cent) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotItemDisc,(SELECT sum(k1.PckCharge * k1.Qty * $per_cent) from Kitchen k1 where (k1.CNo=km.CNo or k1.CNo=km.CNo) and k1.CNo=km.CNo and k1.EID=km.EID AND (k1.Stat = $stat) GROUP BY k1.EID) as TotPckCharge, (case when $ipname != '-' Then $ipname ELSE ip.Name1 end) as Portions, km.BillDiscAmt * $per_cent as BillDiscAmt, km.DelCharge * $per_cent as DelCharge, km.RtngDiscAmt * $per_cent as RtngDiscAmt, date(km.LstModDt) as OrdDt, k.Itm_Portion, k.TaxType,  c.ServChrg, c.Tips,e.Name,km.MergeNo,km.TableNo, km.MCNo, km.CustId, km.CellNo, k.ItemId  from Kitchen k, KitchenMain km, MenuItem m, Config c, Eatary e, ItemPortions ip where k.Itm_Portion = ip.IPCd and e.EID = c.EID AND c.EID = km.EID AND k.ItemId=m.ItemId and ( k.Stat = $stat) and km.EID = k.EID and km.EID = $EID And k.payRest = 0 and km.payRest = 0 and (k.CNo = km.CNo OR km.MCNo = k.MCNo) and (km.MCNo = $CNo and km.CNo = $CNo) and (km.MergeNo = k.MergeNo and km.MergeNo = '$MergeNo') $wh_ctyp group by km.MCNo, k.ItmRate,k.ItemTyp,k.CustItemDesc, k.Itm_Portion, m.Name1, date(km.LstModDt), k.TaxType, ip.Name1, c.ServChrg, c.Tips  order by TaxType, m.Name1 Asc")
		->result_array();
		
	}

	private function calculatTotalTax($total_tax, $new_tax){
		return $total_tax + $new_tax;
	}

	public function billGenerated($EID, $CNo, $postData){

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

            if (empty($kitcheData)) {
                $response = [
                    "status" => 0,
                    "msg" => $this->lang->line('noBillCreation')
                ];
                
            } else {

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
                $billingObj['PaidAmt'] = round($totalAmount);
                $billingObj['SerCharge'] = $kitcheData[0]['ServChrg'];
                $billingObj['SerChargeAmt'] = round(($itemTotalGross * $kitcheData[0]['ServChrg']) /100 ,2);
                $billingObj['Tip'] = $TipAmount;
                // $billingObj['PaymtMode'] = $paymentMode;
                // $billingObj['PymtRef'] = $orderId;
                // $billingObj['PymtType'] = 0;
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
	                	// $gt = $totalAmount / (100 - $discountDT['pcent']) * 100;
	                	// $billingObj['autoDiscAmt'] = ($gt * $discountDT['pcent'])/100;
	                	$billingObj['autoDiscAmt'] = ($totalAmount * $discountDT['pcent'])/100;
	                	$billingObj['PaidAmt'] = round($totalAmount - $billingObj['autoDiscAmt']);
	                }
                }
                
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

                    $custPymtObj['BillId'] 		= $lastInsertBillId;
                    $custPymtObj['CustId'] 		= $CustId;
                    $custPymtObj['BillNo'] 		= $lastInsertBillId;
                    $custPymtObj['EID'] 		= $EID;
                    $custPymtObj['aggEID'] 		= $this->session->userdata('aggEID');
                    $custPymtObj['PaidAmt'] 	= $totalAmount;
                    $custPymtObj['PaymtMode'] 	= $paymentMode;
                    $genTblDb->insert('CustPymts', $custPymtObj);
                    
                    $kstat = ($EType == 5)?3:2;
                    // check for customer split bill 9-jan-24
                    if($splitTyp == 0){

                    	$this->db2->query("UPDATE Kitchen SET BillStat = $billingObjStat  WHERE EID = $EID and (MCNo = $CNo and MergeNo = $strMergeNo) AND BillStat = 0 and Stat = $kstat ");

                    	$this->db2->query("UPDATE KitchenMain SET BillStat = $billingObjStat WHERE (MCNo = $CNo and MergeNo = $strMergeNo) AND BillStat = 0 AND EID = $EID ");
                    }

                $this->db2->trans_complete();

                $this->session->set_userdata('KOTNo', 0);
                $this->session->set_userdata('CNo', 0);
                $this->session->set_userdata('itemTotalGross', 0);

                if(!empty($lastInsertBillId)){
                	$this->session->set_userdata('billFlag',1);
                }
                $response = [
                    "status" 	=> 1,
                    "msg" 		=> "Bill Generated",
                    "billId" 	=> $lastInsertBillId,
                    "paidAmt" 	=> $billingObj['PaidAmt']
                ];

            }
            
            return $response;
	}

	public function getOrderDetailsByTableNo($MergeNo){	
		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2;

		$whr = " k.CNo = km.CNo ";
		return $this->db2->select("km.CustId, m.ItemId,m.Name1,k.Qty ,k.ItmRate,  sum(k.OrigRate*k.Qty) as OrdAmt,km.CNo,km.CellNo, km.BillStat, k.Stat, km.MergeNo, CONCAT_WS(' ', u.FName, u.LName) as Fullname, dis.pcent")
						->order_by('km.CNo', 'asc')
						->group_by('km.CNo, km.CellNo')
						->join('Kitchen k', 'k.MergeNo = km.MergeNo', 'inner')
						->join('MenuItem m', 'm.ItemId = k.ItemId', 'inner')
						->join('Users u', 'u.CustId = km.CustId', 'inner')
						->join('discounts dis', 'dis.discId = u.discId', 'left')
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
        $lname = "cp.Name$langId";
		return $this->db2->select("cp.PymtMode, (case when $lname != '-' Then $lname ELSE cp.Name1 end) as Name, cp.Company, cp.CodePage1, cp.repeatable")
						->order_by('cp.Rank', 'ASC')
						->join('PymtModes pm', 'pm.PymtMode = cp.PymtMode', 'inner')
						->get_where('ConfigPymt cp', array('cp.Stat' => 1))->result_array();
	}

	public function getSplitPayments($billId){
		return $this->db2->get_where('BillPayments', array('BillId' => $billId,'Stat' => 1,'EID' => $this->EID))->result_array();
	}

	public function getModesFromBillPayment($billId){
		return $this->db2->select("PaymtMode")->get_where('BillPayments', array('BillId' => $billId,'Stat' => 1,'EID' => $this->EID))->result_array();	
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

	public function getCountries(){
		return $this->db2->select('*')
					->order_by('country_name', 'ASC')
					->get_where('countries', array('Stat' => 0))->result_array();
	}

	public function getCityListByCountry($phone_code){
		return $this->db2->get_where('city', array('status' => 0, 'phone_code' => $phone_code))->result_array();
	}

	public function getUserDetails($custId){
		return $this->db2->select('*')
                                    ->get_where('Users', array('CustId' => $custId))
                                    ->row_array();
	}

	public function getCurrenOrderBill($custId){
		return $this->db2->select('BillStat, CNo')
						->order_by('CNo', 'DESC')
						->get_where('KitchenMain', array('CustId' => $custId,
														 'EID' => $this->EID
														)
									)->row_array();
	}

	public function checkBillCreation($MCNo){
		return $this->db2->select('BillId, CNo')
						->where_in('Stat', array(1,5))
						->get_where('Billing', array('CNo' => $MCNo, 'EID' => $this->EID))
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
									'EID' => $this->EID
									))
				->result_array();
	}

	public function getTableDetails($table){
		return $this->db2->select('TblTyp, Capacity, SecId, CCd')->get_where('Eat_tables', array('EID' => $this->EID, 'TableNo' => $table))->row_array();
	}

	public function checkUserFromGenDb($mobile){
		$genTblDb = $this->load->database('GenTableData', TRUE);

		return $genTblDb->select('*')
		                ->get_where('AllUsers', array('MobileNo' => $mobile))
		                ->row_array();
	}

	public function getLoyalityList($BillId){
		$EID = $this->EID;
		$CustId = $this->session->userdata('CustId');

		$data = array();
		$billDT = $this->db2->query("SELECT (bp.PaidAmt) as rcvdamt,bp.PaymtMode,   (SELECT sum(b1.PaidAmt) from Billing b1 where b1.BillId=bp.BillId and b1.EID= $EID and b1.CustId = $CustId) as totpayable from BillPayments bp  where  bp.BillId= $BillId and bp.EID = $EID")->result_array();

		if(!empty($billDT)){

				$loyaltyDT = $this->db2->get_where('LoyaltyConfig lc', 
										 array('lc.MinPaidValue <=' => $billDT[0]['totpayable'], 
										 	'lc.Stat' => 0)
										 )
				    				->result_array();
				if(!empty($loyaltyDT)){
					foreach ($loyaltyDT as &$row) {
						$langId = $this->session->userdata('site_lang');
        				$lname = "c.Name$langId";
						$configDet = $this->db2->select("PointsValue, (case when $lname != '-' Then $lname ELSE c.Name1 end) as Name, c.PymtMode")
												->join('ConfigPymt c', 'c.PymtMode = l.PymtMode', 'left')
												->get_where('LoyaltyConfigDet l', array(
													'l.LNo' => $row['LNo'],
													'l.Stat' => 0
													)
												)
												->result_array();
						$points = [];
						$tmpPoint = [];
						$totalPoints = 0;
						foreach ($configDet as $con) {
							if($con['PymtMode'] == 0){
								$tmpPoint['Name'] = 'All Payment';
								$tmpPoint['PymtMode'] = $con['PymtMode'];
								$tmpPoint['PointsValue'] = round($billDT[0]['totpayable'] / $con['PointsValue'], 2); 
								$points[] = $tmpPoint;
								$totalPoints = $tmpPoint['PointsValue'];
								break;
							}else{
								foreach ($billDT as $bill) {
									if($bill['PaymtMode'] == $con['PymtMode']){
										$tmpPoint['Name'] = $con['Name'];
										$tmpPoint['PymtMode'] = $con['PymtMode'];
										$tmpPoint['PointsValue'] = round($bill['rcvdamt'] / $con['PointsValue'], 2); 
										$totalPoints = $totalPoints + $tmpPoint['PointsValue'];
										$points[] = $tmpPoint;
									}
								}
							} //else block
						} // configDet block
						$row['totalPoints'] = $totalPoints;
						$row['points'] = $points;
					}
					$data = $loyaltyDT;
				}
		}
		return $data;
	}

	public function checkLoyaltyPoints($billId){
		$CustId = $this->session->userdata('CustId');
		return $this->db2->get_where('Loyalty', array('EID' => $this->EID, 'custId' => $CustId, 'billId' => $billId))
					->row_array();
	}
	
	public function getLoyaltiPoints($CustId, $RestEID){
		$lno = '';
		if($RestEID == 29){
			// rest
			$lno = '2';
			$this->db2->where('lc.EatOutLoyalty', $this->EID);
		}else{
			$lno = '1';
			// eatout
			$this->db2->where('lc.EatOutLoyalty', 0);
		}
		return $this->db2->select("lc.Name, Sum(Case When l.earned_used = 0 
         Then l.Points Else 0 End) EarnedPoints, Sum(Case When l.earned_used = 1 
         Then l.Points Else 0 End) UsedPoints, lc.MaxPointsUsage, lc.billUsagePerc, lc.PointsValue")
					->group_by('lc.LNo')
					->join('LoyaltyConfig lc', 'lc.LNo = l.LNo', 'inner')
					->get_where('Loyalty l', 
										array('l.EID' => $this->EID, 
												'l.custId' => $CustId,
												'l.LNo' => $lno)
								)
					->result_array();
	}

	public function getCustAccount($custId)
	{
		return $this->db2->select("sum(billAmount) as billAmount")
					->get_where('custAccounts', array('custId' => $custId, 'EID' => authuser()->EID, 'pymt_rcpt' => 0))
					->row_array();
	}

	public function getTablesDetails(){
		$EID = authuser()->EID;
		$OType = $this->session->userdata('TableNo');
		return $this->db2->select('offerValid')->get_where('Eat_tables', array('EID' => $EID, 'TableNo' => $OType))->row_array();
	}

	public function updateBillDiscountAmount($CNo){
        $SchType = $this->session->userdata('SchType');
        $EID = authuser()->EID;
        if(in_array($SchType, array(1,3))){
        	$EType = $this->session->userdata('EType');
			$stat = ($EType == 5)?2:1;

            $Offer = $this->db2->select("km.SchCd, km.SDetCd, sum(k.Qty * k.OrigRate) as orderAmount")
            				->join('Kitchen k', 'k.MCNo = km.MCNo', 'inner')
            				->get_where('KitchenMain km', array('km.EID' =>$EID, 'km.BillStat' => 0, 'k.Stat' => $stat, 'km.CNo' => $CNo))
            				->row_array();
			if(!empty($Offer)){
				
	            $billOffer = $this->db2->select('cod.DiscMaxAmt, cod.MinBillAmt, cod.Disc_Amt, cod.Disc_pcent')
	            					->join('CustOffersDet cod', 'cod.SchCd = c.SchCd', 'inner')
	            					->get_where('CustOffers c', array('c.EID' => $EID, 'cod.SchCd' => $Offer['SchCd'], 'cod.SDetCd' => $Offer['SDetCd']))
	            
	            					->row_array();

	            if(!empty($billOffer)){
	                $dis = 0;
	                if($Offer['orderAmount'] >= $billOffer['MinBillAmt']){
	                	if($billOffer['DiscMaxAmt'] > 0){
	                		$dis = $billOffer['DiscMaxAmt'];
	                	}else if($billOffer['Disc_pcent'] > 0){
	                        $amt = ($Offer['orderAmount'] * $billOffer['Disc_pcent']) / 100;
	                        $amt  = round($amt);
	                        if($amt > $billOffer['Disc_Amt']){
	                        	$dis = $billOffer['Disc_Amt'];
	                        }else{
	                        	$dis = $amt;
	                        }
	                    }else{
	                         if($billOffer['Disc_Amt'] > 0){
	                            $dis = $billOffer['Disc_Amt'];
	                        }   
	                    }
	                }

	                updateRecord('KitchenMain', array('BillDiscAmt' => $dis), array('EID' => $EID, 'CNo' => $CNo));
	            }
			}            				
        }
            
	}

	public function get_customize_lists($dt){
		$EID = authuser()->EID;
		$langId = $this->session->userdata('site_lang');
        $lname = "mi.Name$langId";
        $pname = "p.Name$langId";

        if(!empty($dt['IPCd'])){
        	$this->db2->where('cod.IPCd', $dt['IPCd']);
        	$this->db2->where('mir.Itm_Portion', $dt['IPCd']);
        }

        if(!empty($dt['ItemTyp'])){
        	$this->db2->where('cod.ItemTyp', $dt['ItemTyp']);
        }

		return $this->db2->select("mi.ItemId, (case when $lname != '-' Then $lname ELSE mi.Name1 end) as itemName, (case when $pname != '-' Then $pname ELSE p.Name1 end) as PortionName")
					->join('CustOffersDet cod', 'cod.SchCd = c.SchCd', 'inner')
					->join('MenuItem mi', 'mi.ItemId = cod.ItemId', 'inner')
					->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
					->join('ItemPortions p', 'p.IPCd = mir.Itm_Portion', 'inner')
					->get_where('CustOffers c', array('c.EID' => $EID, 'cod.SchCd' => $dt['SchCd'], 'cod.SDetCd' => $dt['SDetCd']))
					->result_array();
	}

	public function getOnAccountDetails($CustId, $CustType){
		$EID = authuser()->EID;
		$data['MaxLimit'] = 0;
		$data['balance'] = 0;
		$data['BillAmt'] = 0;
		$data['PaidAmt'] = 0;

		$billDt = $this->db2->select("IFNULL(c.MaxLimit, 0) as MaxLimit, sum(b.PaidAmt) as BillAmt")
								->join('CustList c', 'c.EID = b.EID', 'left')
								->get_where('Billing b', array('b.EID' => $EID, 'b.CustId' => $CustId, 'c.CustId' => $CustId, 'b.Stat' => 25))
								->row_array();
		$paymentDt = $this->db2->select("IFNULL(sum(bp.PaidAmt),0) as PaidAmt")
								->join('BillPayments bp', 'bp.BillId = b.BillId', 'inner')
								->get_where('Billing b', array('b.EID' => $EID, 'b.CustId' => $CustId, 'bp.EID' => $EID, 'b.Stat' => 25))
								->row_array();
		if(!empty($billDt)){
			$data['MaxLimit'] 	= $billDt['MaxLimit'];
			$data['balance'] 	= $billDt['BillAmt'] - $paymentDt['PaidAmt'];
			$data['BillAmt'] 	= $billDt['BillAmt'];
			$data['PaidAmt'] 	= $paymentDt['PaidAmt'];
		}
		return $data;
	}

	public function getPrepaidDetails($CustId, $CustType){
		$EID = authuser()->EID;
		$data['MaxLimit'] = 0;
		$data['balance'] = 0;
		$data['BillAmt'] = 0;
		$data['PaidAmt'] = 0;
		$data['prePaidAmt'] = 0;

		$custDT = $this->db2->select("IFNULL(MaxLimit, 0) as MaxLimit, IFNULL(prePaidAmt, 0) as prePaidAmt")
			->get_where('CustList', array('CustId' => $CustId, 'custType' => 2))
			->row_array();
		if(!empty($custDT)){
			$data['prePaidAmt'] = $custDT['prePaidAmt'];
			$data['MaxLimit'] 	= $custDT['MaxLimit'];
		}

		$billDt = $this->db2->select("IFNULL(sum(b.PaidAmt),0) as BillAmt")
								->get_where('Billing b', array('b.EID' => $EID, 'b.CustId' => $CustId, 'b.Stat' => 26))
								->row_array();

		$paymentDt = $this->db2->select("IFNULL(sum(bp.PaidAmt),0) as PaidAmt")
								->join('BillPayments bp', 'bp.BillId = b.BillId', 'inner')
								->get_where('Billing b', array('b.EID' => $EID, 'b.CustId' => $CustId, 'bp.EID' => $EID, 'b.Stat' => 26))
								->row_array();
		if(!empty($billDt)){
			$data['balance'] 	= $billDt['BillAmt'] - $paymentDt['PaidAmt'];
			$data['BillAmt'] 	= $billDt['BillAmt'];
			$data['PaidAmt'] 	= $paymentDt['PaidAmt'];
		}
		return $data;
	}

	public function getItemListForReorder(){
		// 'mi.CID' => 10 = Bar Cuisine
		$EID  = authuser()->EID;
        $CNo = $this->session->userdata('CNo');
        $CustId = $this->session->userdata('CustId');

		$stat = ($this->session->userdata('EType') == 5)?3:2;
		return $this->db2->select("k.*, (select max(k1.reOrder) from Kitchen k1 where k1.EID=k.EID and k1.CNo = k.CNo) as LastReOrders")
					->order_by('k.OrdNo', 'DESC')
					->group_by('k.EID, k.ItemId, k.Itm_Portion')
					->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
					->join('MenuItem mi', 'mi.ItemId = k.ItemId', 'inner')
					->where_in('mi.CTyp', array(1, 3))
					->get_where('KitchenMain km', array('k.Stat' => $stat, 'k.BillStat' => 0, 'km.BillStat' => 0, 'k.EID' => $EID, 'km.EID' => $EID, 'km.CustId' => $CustId, 'km.CNo' => $CNo))
					->result_array();
	}

	public function getOfferItemsForReorder($OrdNo, $SchCd, $SDetCd){
		$EID  = authuser()->EID;
        $CNo = $this->session->userdata('CNo');
        $CustId = $this->session->userdata('CustId');

		$stat = ($this->session->userdata('EType') == 5)?3:2;

        $whr = "(time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)";
		return $this->db2->select("k.*, (select max(k1.reOrder) from Kitchen k1 where k1.EID=k.EID and k1.CNo = k.CNo) as LastReOrders")
					->join('Kitchen k', 'k.CNo = km.CNo', 'inner')
					->join('MenuItem mi', 'mi.ItemId = k.ItemId', 'inner')
					->join('CustOffers c', 'c.SchCd = k.SchCd', 'inner')
					->join('CustOffersDet cod', 'cod.SchCd = c.SchCd', 'inner')
					->where($whr)
					->get_where('KitchenMain km', array('k.Stat' => $stat, 'k.BillStat' => 0, 'km.BillStat' => 0, 'k.EID' => $EID, 'km.EID' => $EID, 'km.CustId' => $CustId, 'km.CNo' => $CNo, 'k.SchCd' => $SchCd, 'k.SDetCd' => $SDetCd, 'k.OrdNo !=' => $OrdNo))
					->result_array();	
	}
	

	
}