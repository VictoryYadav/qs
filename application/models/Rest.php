<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Model{

	private $db2;
	public function __construct()
	{
		parent::__construct();
		
        $my_db = $this->session->userdata('my_db');
        $this->db2 = $this->load->database($my_db, TRUE);
	}

	public function getUserRestRole(){
		return $this->db2->get_where('UserType', array('UTyp >' => 20))->result_array();
	}

	public function getUserTypeList(){
		return $this->db2->get_where('UserType', array('UTyp <' => 10))->result_array();
	}

	public function getOffersList(){
		$langId = $this->session->userdata('site_lang');
        $scName = "SchNm$langId as SchNm";

		return $this->db2->select("$scName, SchCd, SchTyp ,SchCatg,FrmDt, ToDt, FrmDayNo, ToDayNo, ")
						->order_by('SchCd', 'desc')
						->get_where('CustOffers', array('Stat' => 0))
						->result_array();
	}

	public function passwordUpdate($password){
 		$this->db2->update('UsersRest', array('Passwd' => $password), array('RUserId' => authuser()->RUserId));
			 	
	}

	public function getRestaurantList(){
		return $this->db2->select('EID, Name')
						->order_by('EID DESC')
						->get_where('Eatary', array('Stat' => 0))
						->result_array();
	}

	public function addUser($data){

		unset($data['RUserId']);

		$check = $this->db2->get_where('UsersRest', array('MobileNo' => $data['MobileNo'], 'Stat' => 0))->row_array();
		if(empty($check)){
			$createrData = $this->db2->get_where('UsersRest', array('RUserId' => authuser()->RUserId))->row_array();
			$data['ChainId'] = $createrData['ChainId'];
			$data['Stat'] = $createrData['Stat'];
			$data['LoginCd'] = authuser()->RUserId;
			$data['PWDHash'] = md5('QS1234');
			$newRUserId = insertRecord('UsersRest', $data);	

			$GUsersRest['FName'] = $data['FName'];
			$GUsersRest['LName'] = $data['LName'];
			$GUsersRest['MobileNo'] = $data['MobileNo'];
			$GUsersRest['PEmail'] = $data['PEmail'];
			$GUsersRest['DOB'] = $data['DOB'];
			$GUsersRest['Gender'] = $data['Gender'];
			$GUsersRest['UTyp'] = $data['UTyp'];
			$GUsersRest['RestRole'] = $data['RestRole'];
			$GUsersRest['RUserId'] = $newRUserId;

			$genDB = $this->load->database('GenTableData', TRUE);
			$genDB->insert('UsersRest', $GUsersRest);		

			if(!empty($newRUserId)){
				$this->sendUserLoginMsg();
				$res = $data['FName'].' '.$data['LName']." Created Successfully";
				if($data['UTyp'] == 9){

					$userRolesAccessObj['EID'] = $createrData['EID'];
					$userRolesAccessObj['RUserId'] = $newRUserId;
					$userRolesAccessObj['RoleId']= 21;
					insertRecord('UserRolesAccess', $userRolesAccessObj);

					$userRolesAccessObj['EID'] = $createrData['EID'];
					$userRolesAccessObj['RUserId'] = $newRUserId;
					$userRolesAccessObj['RoleId']= 26;
					insertRecord('UserRolesAccess', $userRolesAccessObj);
				}
			}else{
				$res = "Failed to Create ".$data['FName'].' '.$data['LName'];
			}

		}else{
			$res = "Mobile No Already Exists";
		}
		return $res;
	}

	// not completed generateToken
	private function generateToken(){
		return 'hjklkjhjkjhjkjhh';
	}

	private function sendUserLoginMsg(){
		// this function is not completed
		// msg= login has been created
		// RuserId / token from restusers
	}

	public function mergeTables($postdata){

		if (isset($postdata['getUnmergeTables']) && $postdata['getUnmergeTables']) {
			// $tables = $eatTableObj->exec("SELECT TableNo, MergeNo from Eat_tables where TableNo = MergeNo and Stat=0 order by TableNo ASC");
			$tables = $this->db2->query("SELECT TableNo, MergeNo from Eat_tables where TableNo = MergeNo and Stat=0 order by TableNo ASC")
								->result_array();
			if (!empty($tables)) {
				$response = [
					"status" => 1,
					"tables" => $tables
				];
			}else {
				$response = [
					"status" => 0,
					"msg" => "All Tables are Allocated"
				];
			}

			// echo json_encode($response);
			return $response;
		}

		if (isset($postdata['mergeTables']) && $postdata['mergeTables']) {
			$selectedTables = json_decode($postdata['selectedTables']);

			if (count($selectedTables) > 1) {
			
					$mergeNo = implode("~", $selectedTables);

					$selectedTablesString = implode(',', $selectedTables); 
					$q = "UPDATE Eat_tables set MergeNo = '$mergeNo', Stat = 1 where TableNo in ($selectedTablesString)";
					// print_r($q);exit();
					$result = $this->db2->query($q);
					$result1 = $this->db2->query("UPDATE KitchenMain set MergeNo = '$mergeNo' where TableNo in ($selectedTablesString) and BillStat = 0");
					// Update KitchenMain set MergeNo = $MergeNo where (TableNo="22" OR TableNo="23" or TableNo="24") and BillStat=0 and date(LstModDt) = date(curdate())

					if($result){
						$response = [
							"status" => 1,
							"msg" => "ok"
						];
					}else{
						$response = [
							"status" => 3,
							"msg" => "Fail to update in  Eat_tables table"
						];
					}


			}else {
				$response = [
					"status" => 0,
					"msg" => "You can select Min 2 and Max 4 Tables"
				];
			}

			// echo json_encode($response);
			return $response;
		}
		if (isset($postdata['getMergedTables']) && $postdata['getMergedTables']) {

			$tables = $this->db2->query("SELECT distinct MergeNo from Eat_tables where TableNo != MergeNo order by MergeNo ASC")->result_array();
			if (!empty($tables)) {
				$response = [
					"status" => 1,
					"tables" => $tables
				];
			}else {
				$response = [
					"status" => 0,
					"msg" => "No table is merged"
				];
			}
			
			return $response;
		}

		if (isset($postdata['getEachTable']) && $postdata['getEachTable']) {
			$merge_no = $postdata['MergeNo'];
			// $q = "SELECT CNo, TableNo FROM `KitchenMain` where BillStat=0 ORDER BY TableNo ASC";
			$q = "SELECT TableNo from Eat_tables where MergeNo = '$merge_no'";
			$tables = $this->db2->select('TableNo')->get_where('Eat_tables', array('MergeNo' => $merge_no))->result_array();
			// print_r($tables);exit();
			if (!empty($tables)) {
				$response = [
					"status" => 1,
					"tables" => $tables
				];
			}else {
				$response = [
					"status" => 0,
					"msg" => "No table is merged"
				];
			}

			// echo json_encode($response);
			return $response;
		}

		if(isset($postdata['unmergeTables']) && $postdata['unmergeTables']){
			$selectedTables = json_decode($postdata['selectedTables']);
			$deselectedTables = json_decode($postdata['deselectedTables']);
			$old_merge_no = $postdata['MergeNo'];
			$q1 = "UPDATE Eat_tables set MergeNo = TableNo, stat=0 where MergeNo = '$old_merge_no'";
			$tables = $this->db2->query($q1);



			$selectedTables = json_decode($postdata['selectedTables']);
			$response = [
				"status" => 4,
				"msg" => "Success"
			];
			if (count($selectedTables) > 1) {
				$mergeNo = implode("~", $selectedTables);

				$update_merge = "UPDATE KitchenMain set MergeNo = '$mergeNo'where MergeNo = '$old_merge_no'";
				$tables = $this->db2->query($update_merge);

				$deselectedTablesString = implode(',', $deselectedTables);
				$update_table = "UPDATE KitchenMain set MergeNo = TableNo where TableNo in ($deselectedTablesString)";
				$result = $this->db2->query($update_table);


				$selectedTablesString = implode(',', $selectedTables); 
				$q = "UPDATE Eat_tables set MergeNo = '$mergeNo', Stat = 1 where TableNo in ($selectedTablesString)";
				$result = $this->db2->query($q);
				$result1 = $this->db2->query("UPDATE KitchenMain set MergeNo = '$mergeNo' where TableNo in ($selectedTablesString) and BillStat = 0");
				// $eatTableObj->executeTransaction();
				if($result){
					$response = [
						"status" => 1,
						"msg" => "ok"
					];
				}else{
					$response = [
						"status" => 3,
						"msg" => "Fail to update in  Eat_tables table"
					];
				}

			}
			// echo json_encode($response);
			return $response;
		}

		if(isset($postdata['UnmergeTable']) && $postdata['UnmergeTable']){
			// $selectedTables = json_decode($postdata['selectedTables']);
			// print_r($postdata);exit();
			$mergeNo = $postdata['MergeNo'];
			$table = $postdata['TableNo'];
			$this->db2->query("UPDATE Eat_tables set MergeNo = TableNo where MergeNo = '$mergeNo'");
		}

	}


	public function getUserAccessRole($postdata){
		$EID = authuser()->EID;
		
        $langId = $this->session->userdata('site_lang');
        $lname = "ur.Name$langId as Name";
	        
			if (isset($postdata['getUser']) && $postdata['getUser']==1) {
				$mobileNumber =  $postdata['mobileNumber'];
				// $user = $userRestObj->search(["MobileNo" => $mobileNumber, "EID" => $EID]);
				$user = $this->db2->get_where('UsersRest', array("MobileNo" => $mobileNumber, "EID" => $EID, 'Stat' => 0))->row_array();
				if (!empty($user)) {
					$response = [
						"status" => 1,
						"userId" => $user['RUserId'],
						"userName" => $user['FName']." ".$user['LName']
					];
				}else {
					$response = [
						"status" => 0,
						"msg" => "User Not Found"
					];
				}
				// echo json_encode($response);
				return $response;
			}

			if (isset($postdata['getAvailableRoles']) && $postdata['getAvailableRoles']==1) {
			$userId =  $postdata['userId'];

			$availableRoles = $this->db2->query("SELECT ur.RoleId, $lname FROM UserRoles ur WHERE  ur.Stat = 0 AND ur.RoleId NOT IN (SELECT RoleId FROM UserRolesAccess WHERE RUserId = $userId AND EID = $EID) Order by ur.Name1")->result_array();
			return $availableRoles;
			if (!empty($availableRoles)) {
				$response = [
					"status" => 1,
					"availableRoles" => $availableRoles
				];
			}else {
				$response = [
					"status" => 0,
					"msg" => "Fail to Find roles"
				];
			}
		// echo json_encode($response);
		// die();
			return $response;
		}

		if (isset($postdata['setRoles']) && $postdata['setRoles']==1) {
			$roles = explode(",", $postdata['roles']);
			$userId = $postdata['userId'];
			$response = [
				"status" => 1,
				"msg" => "Roles are assigned"
			];

			foreach ($roles as $role) {
				$userRolesAccessObj['EID'] = $EID;
				$userRolesAccessObj['RUserId'] = $userId;
				$userRolesAccessObj['RoleId'] = $role;
				$this->db2->insert('UserRolesAccess',$userRolesAccessObj);
				$idd = $this->db2->insert_id();
				if(empty($idd)){
					$response = [
						"status" => 0,
						"msg" => "Failed to insert in UserRolesAccess table"
					];
				}
			}
			// echo json_encode($response);
			// die();
			return $response;

		}

		if (isset($postdata['setRestRoles']) && $postdata['setRestRoles']==1) {
			$userId = $postdata['userId'];
			$roles = $postdata['roles'];

			$response = "Roles are assigned";

			$userRolesAccessObj = [];
			$temp = [];
			foreach ($roles as $role) {
				$temp['EID'] = $EID;
				$temp['RUserId'] = $userId;
				$temp['RoleId'] = $role;
				$userRolesAccessObj[] = $temp;
			}
			if(!empty($userRolesAccessObj)){
				$this->db2->insert_batch('UserRolesAccess', $userRolesAccessObj); 
			}else{
				$response = "Failed to insert in UserRolesAccess table";
			}
			return $response;
		}

		if (isset($postdata['getAssignedRoles']) && $postdata['getAssignedRoles']==1) {
			$userId = $postdata['userId'];

			$getAssignedRoles = $this->db2->query("SELECT ura.URNo, $lname FROM `UserRolesAccess` ura, UserRoles ur WHERE ura.RoleId = ur.RoleId AND ur.Stat = 0 AND ura.EID = $EID AND ura.RUserId = $userId Order by ur.Name1")->result_array();
			return $getAssignedRoles;

			if (!empty($getAssignedRoles)) {
				$response = [
					"status" => 1,
					"getAssignedRoles" => $getAssignedRoles
				];
			}else {
				$response = [
					"status" => 0,
					"msg" => "No Roles are assigned"
				];
			}
			// echo json_encode($response);
			// die();
			return $response;
		}

		if (isset($postdata['removeRoles']) && $postdata['removeRoles']==1) {
			$roles = $postdata['roles'];
			$userId = $postdata['userId'];

			// $deleteRoles = $userRolesAccessObj->exec("DELETE FROM UserRolesAccess WHERE EID = $EID AND URNo IN ($roles)");

			$deleteRoles = $this->db2->query("DELETE FROM UserRolesAccess WHERE EID = $EID AND URNo IN ($roles)");

			if ($deleteRoles) {
				$response = [
					"status" => 1,
					"msg" => "Roles are Removed"
				];
			}else {
				$response = [
					"status" => 0,
					"msg" => "Failed to delete in UserRolesAccess table"
				];

			}
			// echo json_encode($response);
			// die();
			return $response;
		}

		if (isset($postdata['removeRestRoles']) && $postdata['removeRestRoles']==1) {
			
			$URNo = implode(",",$postdata['URNo']);
			$userId = $postdata['userId'];

			$deleteRoles = $this->db2->query("DELETE FROM UserRolesAccess WHERE EID = $EID AND RUserId = $userId and URNo IN ($URNo)");

			if ($deleteRoles) {
				$response = "Roles are Removed";
			}else {
				$response = "Failed to delete in UserRolesAccess table";
			}
			return $response;
		}

	}

	public function getDispenseAccess(){
		$RUserId = authuser()->RUserId;
		$EType = $this->session->userdata('EType');
		$EID = authuser()->EID;

        $data = array();

		$GetDCD = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();
		if(!empty($GetDCD)){
			$langId = $this->session->userdata('site_lang');
	        $dname = "Name$langId as Name";
	        if(!empty($GetDCD['DCd'])){
	        	$dcd = "(".$GetDCD['DCd'].")";
				$data = $this->db2->query("SELECT DCd, $dname, DCdType FROM Eat_DispOutlets Where EID = $EID AND Stat = 0 and DCd in $dcd")->result_array();
	        }
		}

        return $data;
	}

	public function getStockList($postdata=null){
		$EID = authuser()->EID;
		$trans_id = 0;
		$trans_type_id = 0;
		$from_date = 0;
		$to_date = 0;

		$langId = $this->session->userdata('site_lang');
        $KitName = "ek.KitName$langId as KitName";
        $suppName = "rs.SuppName$langId as SuppName";

		$stock = $this->db2->query("SELECT r.TransId, r.TransType,TransDt,  if(r.FrmEID>0, (Select e.Name from Eatary e where e.EID=r.FrmEID),NULL) as FromEID, if(r.FrmStoreId=1, 'MainStore',NULL) as FromMain, if(r.FrmSuppCd>0, (Select $suppName from RMSuppliers rs where rs.SuppCd=r.FrmSuppCd),NULL) as FromSupp, if(r.FrmKitCd>0, (Select $KitName from Eat_Kit ek where ek.KitCd=r.FrmKitCd),NULL) as FromKit, if(r.ToEID>0, (Select e.Name from Eatary e where e.EID=r.ToEID),NULL) as ToEID, if(r.ToSuppCd>0, (Select $suppName from RMSuppliers rs where rs.SuppCd=r.ToSuppCd),NULL) as ToSupp, if(r.ToKitCd>0, (Select $KitName from Eat_Kit ek where ek.KitCd=r.ToKitCd),NULL) as ToKit, if(r.ToStoreId=1, 'MainStore',NULL) as ToMain  FROM RMStock r where r.Stat=0 and r.EID = $EID order by TransId desc limit 10")->result_array();
		// echo "<pre>";
		// print_r($stock);
		// die;
		if($postdata){
			$trans_id = $postdata['trans_id'];
			$trans_type_id = $postdata['trans_type'];
			$from_date = $postdata['from_date'];
			$to_date = $postdata['to_date'];

			$langId = $this->session->userdata('site_lang');
        	$KitName = "ek.KitName$langId as KitName";

			$q = "SELECT r.TransId, r.TransType,  if(r.FrmEID>0, (Select e.Name from Eatary e where e.EID=r.FrmEID),NULL) as FromEID, if(r.FrmStoreId=1, 'MainStore',NULL) as FromMain, if(r.FrmSuppCd>0, (Select $suppName from RMSuppliers rs where rs.SuppCd=r.FrmSuppCd),NULL) as FromSupp, if(r.FrmKitCd>0, (Select $KitName from Eat_Kit ek where ek.KitCd=r.FrmKitCd),NULL) as FromKit, if(r.ToEID>0, (Select e.Name from Eatary e where e.EID=r.ToEID),NULL) as ToEID, if(r.ToSuppCd>0, (Select $suppName from RMSuppliers rs where rs.SuppCd=r.ToSuppCd),NULL) as ToSupp, if(r.ToKitCd>0, (Select $KitName from Eat_Kit ek where ek.KitCd=r.ToKitCd),NULL) as ToKit, if(r.ToStoreId=1, 'MainStore',NULL) as ToMain  FROM RMStock r where r.Stat=0";

			if(!empty($trans_id)){
				$q.=" and r.TransId = ".$trans_id;
			}
			if(!empty($trans_type_id)){
				$q.=" and r.TransType = ".$trans_type_id;
			}
			if(!empty($from_date)){
				$q.=" and r.TransDt >= '".$from_date."'";
			}
			if(!empty($to_date)){
				$q.=" and r.TransDt <= '".$to_date."'";
			}
			// print_r($q);exit();
			$stock = $this->db2->query($q)->result_array();
			// print_r($stock);exit();
		}
		return $stock;
	}

	public function getStockReport(){
		$langId = $this->session->userdata('site_lang');
        $lname = "rmi.RMName$langId as RMName";
        $rmname = "rmc.RMCatgName$langId as RMCatgName";

		return $this->db2->query("SELECT rsd.RMCd, $lname, rmi.ItemId, $rmname, sum(case when rs.TransType < 10 Then rsd.Qty ELSE 0 end) as sell, sum(case when rs.TransType >= 10 Then rsd.Qty else 0 end) as rcvd FROM `RMStockDet` as rsd, RMStock as rs, RMItems as rmi, RMCatg as rmc where rsd.TransId = rs.TransId and rmi.RMCd = rsd.RMCd and rmi.RMCatg = rmc.RMCatgCd and rsd.Stat = 0 and rs.Stat = 0 and rmi.ItemId = 0 group by rsd.RMCd order by rmc.RMCatgName1, rmi.RMName1")->result_array();
	}

	public function getStockConsumption(){
		$langId = $this->session->userdata('site_lang');
        $lname = "rmi.RMName$langId as RMName";
        $rmname = "rmc.RMCatgName$langId as RMCatgName";

		return $report = $this->db2->query("SELECT rsd.RMCd, $lname, rmi.ItemId, rmi.IPCd, $rmname, ip.AQty, rs.TransDt, k.LstModDt, (select sum(rsd1.Qty * ip.AQty)  from RMStockDet rsd1 where rsd1.TransId=rs.TransId and rs.TransType >= 10 and rsd1.Stat = 0 group by  rsd1.RMCd) as rcvd,(select sum(rsd1.Qty * ip.AQty)  from RMStockDet rsd1 where rsd1.TransId=rs.TransId and rs.TransType<10 and rsd1.Stat = 0 group by  rsd1.RMCd) as issued,  sum(k.Qty) as Qty FROM RMStockDet as rsd, RMStock as rs, RMItems as rmi, RMCatg as rmc, Kitchen as k, ItemPortions as ip where rsd.TransId = rs.TransId and rmi.RMCd = rsd.RMCd and rmi.RMCatg = rmc.RMCatgCd and rmi.ItemId = k.ItemId and ip.IPCd = k.Itm_Portion and rmi.ItemId > 0 and rsd.Stat = 0 and rs.Stat = 0 and k.Stat=3 group by rs.TransId, rsd.RMCd, rs.TransDt, k.LstModDt, rmi.IPCd order by rmc.RMCatgName1, rmi.RMName1")->result_array();
	}

	public function getItemStockReportList($postdata){
		$RMCd = $postdata['RMCd'];
		$q = "SELECT rs.TransType, rs.TransDt, rmi.RMName, ru.Name as UOM, rsd.* from RMStock as rs, RMStockDet as rsd, RMItems as rmi, RMUOM as ru where rs.TransId = rsd.TransId and rsd.RMCd = rmi.RMCd and ru.UOMCd = rsd.UOMCd and rsd.RMCd = ".$RMCd." and rs.TransDt >='".$postdata['from_date']."' and rs.TransDt <='".$postdata['to_date']."'";
		
		$data['report'] = $this->db2->query($q)->result_array();
		$q1 = "SELECT sum(case when rs.TransType > 10 then rsd.Qty else -rsd.Qty end) as stock from RMStock as rs, RMStockDet as rsd where rs.TransId = rsd.TransId and rs.TransDt < '".$postdata['from_date']."' and rsd.RMCd = ".$RMCd." group by rsd.RMCd";
		$s = $this->db2->query($q1)->row_array();
		$data['op_stock'] = 0;
		if(!empty($s) && !empty($s['stock'])){
			$data['op_stock'] = $s['stock'];
		}

		return $data;
	}

	private function getTaxarrayData($billData, $EID, $billId){
		$intial_value = $billData[0]['TaxType'];
		$tax_type_array = array();
		$tax_type_array[$intial_value] = $intial_value;
		
		foreach ($billData as $key) {

		    if($key['TaxType'] != $intial_value){

		        $intial_value = $key['TaxType'];

		        $tax_type_array[$intial_value] = $key['TaxType'];
		    }
		}

		$taxDataArray = array();
		foreach ($tax_type_array as $key => $value) {
			$q = "SELECT t.ShortName, t.TaxPcent, t.TaxType, t.Included, Sum(bt.TaxAmt) as SubAmtTax, t.rank from Tax t, BillingTax bt where bt.TNo=t.TNo and bt.EID=$EID and bt.BillId = $billId and bt.TNo=t.TNo and t.TaxType = $value group by t.ShortName,t.TaxPcent, t.TaxType, t.Included ,t.rank order by t.rank";
			// print_r($q);exit();
		    $TaxData = $this->db2->query($q)->result_array();   
		    $taxDataArray[$value] = $TaxData;

		}
		return $taxDataArray;
	}
	public function getBillBody($billData, $EID, $billId){
		// Repository : billing/bill_print_body.repo.php
		foreach ( $billData as $key => $value ) {
		    $TaxType = $value['TaxType'];
		    if( $key != 0 ){
		        $TaxType = $billData[$key-1]['TaxType'];
		    }

		    if( $value['TaxType'] != $TaxType || $key == 0){
		        // build table with title
		        $sameTaxType  = '';
		        $itemTotal = 0;
		        foreach ($billData as $keyData => $data) {
		            if($data['TaxType'] == $value['TaxType']){
		                    $sameTaxType .= ' <tr> ';
		                    if($data['Itm_Portion'] > 4 ){
		                        
		                        $sameTaxType .= ' <td style="float: left;">'.$data['ItemNm'].' ( '.$data['Portion'].' ) </td> ';

		                    }else{

		                        $sameTaxType .= ' <td style="float: left;">'.$data['ItemNm'].'</td> ';

		                    }
		                    
		                    $sameTaxType .= ' <td style="text-align: right;"> '.$data['Qty'].' </td>';
		                    $sameTaxType .= ' <td style="text-align: right;">'.$data['ItmRate'].'</td> ';
		                    $sameTaxType .= ' <td style="text-align: right;">'.$data['ItemAmt'].'</td> ';
		                    $sameTaxType .= ' </tr> ';
		                    $itemTotal +=$data['ItemAmt'];
		            }
		        }
		        $taxDataArray = $this->getTaxarrayData($billData, $EID, $billId);
		        $res = $this->newTaxType( $value ,$sameTaxType,$value['TaxType'],$taxDataArray,$itemTotal);
		        $result[0] = $res;
		        $result[1] = $itemTotal;
		        return $result;
		    }
		    
		}
	}

	private function newTaxType($data,$sameTaxType,$TaxType,$taxDataArray,$itemTotal){
	    $newTaxType  = ' <div style="margin-bottom: 15px;"> ';
	    $newTaxType .= ' <table style="width:100%;"> ';
	    $newTaxType .= ' <tbody> ';
	    $newTaxType .= ' <tr style="text-align: right;"> ';
	    $newTaxType .= ' <th style="float: left;">Menu Item </th> ';
	    $newTaxType .= ' <th>Qty</th> ';
	    $newTaxType .= ' <th>Rate</th> ';
	    $newTaxType .= ' <th>Amt</th> ';
	    $newTaxType .= ' </tr> ';

	    $newTaxType .=  $sameTaxType;

	    $newTaxType .= ' <tr style="border-top: 1px solid;"> ';
	    $newTaxType .= ' <td></td> <td></td> <td></td> <td></td>';
	    $newTaxType .= ' </tr> ';
	    $newTaxType .= ' <tr> ';
	    $newTaxType .= ' <td style="text-align: left;"><i>Item Total</i></td> ';
	    $newTaxType .= ' <td></td> <td></td>';
	    $newTaxType .= ' <td style="float: right;">'.$itemTotal.'</td> ';
	    $newTaxType .= ' </tr> ';
	    $sub_total = 0;
	    foreach ($taxDataArray as $key => $value) {
	        $total_tax = 0;
	        foreach ($value as $key1=> $dataTax) {

	            if($dataTax['TaxType'] == $TaxType && $dataTax['Included'] > 0){

	                // $total_tax = $this->calculatTotalTax($total_tax,number_format($dataTax['SubAmtTax'],2));

	                    $newTaxType .= ' <tr> ';
	                    $newTaxType .= ' <td style="text-align: left;"> <i> '.$dataTax['ShortName'].''.$dataTax['TaxPcent'].'% </i></td> ';
	                    $newTaxType .= ' <td></td> ';
	                    $newTaxType .= ' <td></td> ';
	                    $newTaxType .= ' <td style="text-align: right;">'.$dataTax['SubAmtTax'].'</td> ';
	                    $newTaxType .= ' </tr> ';
	                
	            }

	            if( $dataTax['TaxType'] == $TaxType && $dataTax['Included'] >= 5 ){

	                $sub_total = $sub_total + $dataTax['SubAmtTax'];

	            }
	        }

	    }
	    $sub_total = $sub_total  + $itemTotal;

	    $newTaxType .= ' <tr style="background: #80808052;"> ';
	    $newTaxType .= ' <td style="text-align: left; font-weight: bold;">Sub Total</td> ';
	    $newTaxType .= ' <td></td> <td></td>';
	    $newTaxType .= ' <td style="float: right;">'.$sub_total.'</td> ';
	    $newTaxType .= ' </tr> ';
	    $newTaxType .= ' </tbody> ';
	    $newTaxType .= ' </table> ';
	    $newTaxType .= ' </div> ';

	    // echo $newTaxType;
	    // echo "<pre>";
	    // print_r($newTaxType);
	    // die;
	    return $newTaxType;
	}

	private function calculatTotalTax($total_tax, $new_tax){
	    return $total_tax + $new_tax;
	}

	public function getThirdOrderData(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as LngName";
		return $this->db2->select("3PId, $lname, Stat")
						->get('3POrders')
						->result_array();
	}

	public function getTablesAllotedData($EID){
		return $this->db2->get_where('Eat_tables', array("EID" => $EID))->result_array();
	}

	public function getItemLists(){
		$langId = $this->session->userdata('site_lang');
        $rname = "i.RMName$langId as RMName";
        $cname = "c.RMCatgName$langId as RMCatgName";

		return $this->db2->select("i.*, $rname, $cname")
						->join('RMCatg c','c.RMCatgCd = i.RMCatg', 'inner')
						->get_where('RMItems i', array('i.Stat' => 0))
						->result_array();
	}

	public function getBomDishLists(){
		$langId = $this->session->userdata('site_lang');
        $rname = "r.RMName$langId as RMName";
        $mname = "m.ItemNm$langId as ItemNm";
        $rmname = "rm.Name$langId as Name";

		return 	$this->db2->select("b.*, $rmname, $rname ,$mname")
						->join('MenuItem m','m.MCatgId = b.ItemId', 'inner')
						->join('RMItems r','r.RMCd = b.RMCd', 'inner')
						->join('RMUOM rm', 'rm.UOMCd= b.RMUOM', 'inner')
						->get('BOM_Dish b')
						->result_array();
	}

	public function getRmUOMlist($RMCd){
		$langId = $this->session->userdata('site_lang');
        $lname = "r.Name$langId as Name";

		return $this->db2->select("r.*, $lname, rm.RMCd")
                            ->join('RMUOM r','r.UOMCd = rm.UOMCd', 'inner')
                              ->get_where('RMItemsUOM rm', array('rm.RMCd' => $RMCd))
                              ->result_array();
	}

	public function getDispenseModes(){
		return $this->db2->get_where('3POrders', array('Stat' => 0))->result_array();
	}

	public function getPaymentList($data){
		// echo "<pre>";
		// print_r($data);
		// die;
		if(!empty($data['fdate'])){
			$this->db2->where('PymtDate >= ', date('Y-m-d', strtotime($data['fdate'])));
		}
		if(!empty($data['tdate'])){
			$tdate = date('Y-m-d', strtotime("+1 day", strtotime($data['tdate'])));
			$this->db2->where('PymtDate <= ', $tdate);
		}
		if(!empty($data['pmode'])){
			$this->db2->where('bp.PaymtMode',$data['pmode']);
		}	
		return $this->db2->select('bp.*, b.BillNo, b.BillPrefix, b.BillSuffix')->order_by('bp.PymtNo', 'DESC')
						->join('Billing b', 'b.BillId = bp.BillId', 'inner')
						->get_where('BillPayments bp', array('bp.Stat' => 1))
						->result_array();
						// print_r($this->db2->last_query());
						// die;
	}

	public function getPaymentModes(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		return $this->db2->select("PymtMode, $lname,Company, CodePage1")->get_where('ConfigPymt', array('Stat' => 0, 'EID' => authuser()->EID))->result_array();
	}

	public function getConfigPayment(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		return $this->db2->select("*, $lname")
						->get_where('ConfigPymt', array('EID' => authuser()->EID))
						->result_array();
	}

	public function get_MCatgId(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as MCatgNm";
		return $this->db2->select("MCatgId, $lname")->get_where('MenuCatg', array('EID' => authuser()->EID, 'Stat' => 0 ))->result_array();
	}

	public function getMenuCatListByCID($EID, $CID){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as MCatgNm";
		return $this->db2->select("MCatgId, $lname")->get_where('MenuCatg', array('EID' => $EID, 'Stat' => 0, 'CID' => $CID))->result_array();
	}

	public function getMenuCatList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "mc.Name$langId as MCatgNm";
        $cuisine = "c.Name$langId as cuisine";
        $kitchen = "et.KitName$langId as kitchen";
        $whr = "et.EID = mc.EID";
		return $this->db2->select("mc.MCatgId, $lname, $cuisine, $kitchen, mc.CID, mc.EID, mc.Rank, mc.KitCd, mc.Stat")
						->join('Cuisines c', 'c.CID = mc.CID', 'inner')
						->join('Eat_Kit et', 'et.KitCd = mc.KitCd', 'inner')
						->where($whr)
						->get_where('MenuCatg mc', array('mc.EID' => authuser()->EID, 'mc.Stat' => 0 ))->result_array();
	}
	
	public function getCuisineList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "c.Name$langId as Name";

		return $this->db2->select("c.CID, $lname, c.Stat")
						->order_by('ec.Rank', 'ASC')
						->join('Cuisines c', 'c.CID = ec.CID', 'inner')
						->get_where('EatCuisine ec', array('ec.EID' => authuser()->EID,'ec.Stat' => 0))
						->result_array();
	}

	public function getEatCuisineList(){
		$langId = $this->session->userdata('site_lang');
        $cuisineName = "c.Name$langId as cuisineName";
        $ecuisineName = "ec.Name$langId as ecuisineName";
        $kitchenName = "ek.KitName$langId as kitchenName";

		return $this->db2->select("ec.ECID, $ecuisineName, ec.EID, e.Name as restName, $cuisineName, c.CID, $kitchenName, ec.KitCd, ec.Rank , ec.Stat")
						->order_by('ec.Rank', 'ASC')
						->join('Eatary e', 'e.EID = ec.EID', 'inner')
						->join('Cuisines c', 'c.CID = ec.CID', 'inner')
						->join('Eat_Kit ek', 'ek.KitCd = ec.KitCd', 'inner')
						->get_where('EatCuisine ec', array('ec.EID' => authuser()->EID,'ec.Stat' => 0))
						->result_array();
	}

	public function getItemTypeList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";

		return $this->db2->select("ItmTyp, $lname, Stat")
						->get_where('ItemTypes', array('Stat' => 0,'EID' => authuser()->EID))
						->result_array();
	}

	public function getOffersSchemeType(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";

		return $this->db2->select("SchCatg, $lname")
						->get_where('CustOfferTypes', array('Stat' => 0, 'SchTyp' => 1))
						->result_array();
	}

	public function getOffersSchemeCategory($stat = null){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";

        if(!empty($stat)){
        	$this->db2->where('Stat', 0);
        }

		return $this->db2->select("SchCatg, $lname, Stat")
						->get_where('CustOfferTypes', array('SchTyp' => 2))
						->result_array();
	}

	public function get_foodType(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Opt";
        $Usedfor = "Usedfor$langId as Usedfor";

		return $this->db2->select("FID, CTyp ,$lname, $Usedfor")->order_by('CTyp, Rank','ASC')
						->group_by('CTyp')
						->get_where('FoodType', array('Stat' => 0))->result_array();	
	}

	public function getAllItemsList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "m.ItemNm$langId as Name";

		return $this->db2->select("m.ItemId, $lname")
						->order_by('m.ItemNm1','ASC')
						->group_by('m.ItemId')
						->join('MenuItemRates mir', 'mir.ItemId = m.ItemId', 'inner')
						->get_where('MenuItem m', array('m.Stat' => 0, 'm.EID' => authuser()->EID, 'mir.OrigRate >' => 0))->result_array();		
	}

	public function get_kitchen(){
		$langId = $this->session->userdata('site_lang');
        $KitName = "KitName$langId as KitName";

		return $this->db2->select("KitCd ,$KitName, Stat")
						->get_where('Eat_Kit', array(
												'Stat' => 0, 
												'EID' => authuser()->EID
												)
									)
						->result_array();	
	}

	public function get_eat_section(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		return $this->db2->select("SecId, $lname, Stat")->get_where('Eat_Sections', array('Stat' => 0))->result_array();	
	}

	public function getSectionList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		return $this->db2->select("SecId, $lname, Stat")
						->get_where('Eat_Sections')
						->result_array();	
	}

	public function get_item_portion(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		return $this->db2->select("IPCd, $lname")->get('ItemPortions')->result_array();	
	}

	public function get_item_name_list($name){
		$EID = authuser()->EID;
		$langId = $this->session->userdata('site_lang');
        $lname = "ItemNm$langId as ItemNm";
        $cname = "ItemNm$langId";

		$item_name = $name.'%';

		return $this->db2->query("SELECT ItemId, $lname FROM `MenuItem` WHERE EID = $EID and  LOWER($cname) LIKE '$item_name'")->result_array();
	}

	public function getKotList($MCNo, $mergeNo, $FKOTNo){
		$EID = authuser()->EID;
		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2;

		$langId = $this->session->userdata('site_lang');
        $lname = "m.ItemNm$langId as ItemNm";
        $ipName = "ip.Name$langId as Portions";
        $KitName = "ek.KitName$langId as KitName";

         return $this->db2->select("k.ItemId, k.MCNo, $lname,k.CustItemDesc,k.CustRmks, $ipName, sum(k.Qty) Qty, k.TableNo,k.MergeNo, k.KOTNo, k.FKOTNo,k.KitCd, $KitName, k.UKOTNo,k.LstModDt,k.TA,k.EDT, k.OType")
        					->order_by('k.FKOTNo, m.ItemNm1, ek.KitName1, k.UKOTNo', 'ASC')
        					->group_by('k.ItemId, ek.KitName1,k.Itm_Portion')
         					->join('MenuItem m','m.ItemId = k.ItemId','inner')
         					->join('ItemPortions ip','ip.IPCd = k.Itm_Portion','inner')
         					->join('Eat_Kit ek', 'ek.KitCd=k.KitCd', 'inner')
        					// ->where($or_where)
        					// ->where_not_in('k.Stat', array(4,6,7,99))
        					->get_where('Kitchen k', array(
        											'k.EID' => $EID,
        											// 'k.MCNo' => $MCNo,
        											'k.MergeNo' => $mergeNo,
        											'k.FKOTNo' => $FKOTNo,
        											'k.Stat' => $stat)
        								)
        					->result_array();
        					print_r($this->db2->last_query());die;
	}

	public function getBillDetailsForSettle($custId, $MCNo, $mergeNo){
			$EID = authuser()->EID;

			$langId = $this->session->userdata('site_lang');
            $cpname = "cp.Name$langId";

			return $this->db2->select("b.TableNo,b.MergeNo, b.BillId, b.BillNo, DATE_FORMAT(DATE(billTime),'%d/%m/%Y') as BillDate, b.TotAmt as BillValue, b.PaidAmt, bp.PaymtMode, bp.TotBillAmt, bp.PymtType, bp.PaidAmt as bpPaidAmt, b.CNo, u.CustId, (case when $cpname != '' Then $cpname ELSE 'Unpaid' end) as pymtName,b.payRest")
						->order_by('BillId', 'ASC')
						->group_by('BillId')
						->join('Eat_tables et','b.EID = et.EID','inner')
						->join('BillPayments bp','bp.BillId = b.BillId','left')
						->join('Users u','b.CustId=u.CustId','inner')
						->join('ConfigPymt cp','cp.PymtMode=bp.PaymtMode','left')
						->get_where('Billing b', array('b.EID' => $EID,
														'b.MergeNo' => $mergeNo,
														'b.CNo' => $MCNo,
														'b.CustId' => $custId
												)
								   )
						->result_array();
            
	}

	public function getBillingData($from, $to){
			$from  = date('Y-m-d', strtotime($from));
			$to = date('Y-m-d', strtotime("+1 day", strtotime($to)));
			$EID = authuser()->EID;

			$langId = $this->session->userdata('site_lang');
            $cpname = "cp.Name$langId as Name";

			$billData = $this->db2->select("b.TableNo, b.BillId, b.BillNo, b.billTime as BillDate, b.CellNo,b.CustId,b.TotAmt,b.PaidAmt bPaidAmt, bp.MergeNo, bp.PaidAmt, bp.OrderRef, bp.PymtRef, bp.PaymtMode, bp.PymtType, bp.Stat, bp.PymtDate, $cpname, cp.Company")
					->order_by('b.BillId', 'DESC')
					->join('BillPayments bp','bp.BillId = b.BillId','left')
					->join('ConfigPymt cp','cp.PymtMode = bp.PaymtMode','left')
					->get_where('Billing b', array('b.EID' => $EID,
						'b.billTime >=' => $from,
						'b.billTime <=' => $to
								))
					->result_array();
            // print_r($this->db2->last_query());die;
 
            return $billData;
	}

	public function getPendingKOTLIST($minutes, $kitcd){
		$EID = authuser()->EID;

		if(!empty($minutes)){
			$whr = ' TIMEDIFF(CURRENT_TIME(), k.EDT) < '.$minutes;
			$this->db2->where($whr);
		}

		// if(!empty($kitcd)){
		// 	$this->db2->where('k.KitCd', $kitcd);
		// }

		$langId = $this->session->userdata('site_lang');
        $iname = "m.ItemNm$langId as ItemNm";
        $ipname = "ip.Name$langId as Portions";

		$data = $this->db2->select("k.OrdNo,k.EDT,k.KitCd,k.KStat,k.KOTNo, k.FKOTNo, k.ItemId, k.Qty, k.CustItemDesc, k.Itm_Portion, k.TableNo,k.MergeNo,k.CustRmks,k.TA,k.LstModDt,k.OType, $iname, $ipname")
						  ->order_by('k.FKOTNo', 'ASC')
						  ->group_by('k.KitCd, k.FKOTNo, k.ItemId,k.CustItemDesc, k.Itm_Portion,k.CustRmks,k.TA')
						  ->join('MenuItem m','m.ItemId = k.ItemId','inner')
         				  ->join('ItemPortions ip','ip.IPCd = k.Itm_Portion','inner')
         				  ->get_where('Kitchen k', array(
						  						'k.EID' => $EID,
						  						'k.KStat' => 0,
						  						'k.Stat' => 3,
						  						'k.KitCd' => $kitcd
						  							)
									)
						  ->result_array();
// print_r($this->db2->last_query());die;
						  return $data;

		$group_arr = [];
		foreach ($data as $key ) {
			$kot = $key['FKOTNo'];
			if(!isset($group_arr[$kot])){
				$group_arr[$kot] = [];
			}
			array_push($group_arr[$kot], $key);
		}
		echo "<pre>";
		print_r($group_arr);
		die;
		return $data;
	}

	public function getPendingItemLIST($kitCd){
		$EID = authuser()->EID;

		if(!empty($kitCd)){
			$this->db2->where('k.KitCd', $kitCd);
		}

		$langId = $this->session->userdata('site_lang');
        $iname = "m.ItemNm$langId as ItemNm";
        $ipname = "ip.Name$langId as Portions";

		$data = $this->db2->select("k.ItemId, sum(k.Qty) as Qty, k.CustItemDesc, k.Itm_Portion, k.TableNo,k.MergeNo,k.CustRmks,k.TA,k.LstModDt,k.OType, $iname, $ipname")
						  ->order_by('Qty, m.ItemNm1', 'DESC')
						  ->group_by('k.KitCd,k.ItemId')
						  ->join('MenuItem m','m.ItemId = k.ItemId','inner')
         				  ->join('ItemPortions ip','ip.IPCd = k.Itm_Portion','inner')
         				  ->get_where('Kitchen k', array(
						  						'k.EID' => $EID,
						  						'k.KStat' => 0,
						  						'k.Stat' => 3
						  							)
									)
						  ->result_array();
// print_r($this->db2->last_query());die;
						  return $data;

	}

	public  function getTAPendingBills()
	{
		return $this->db2->select('b.BillId,b.BillNo, b.billTime,b.PaidAmt, b.CellNo,b.OType,b.TableNo,b.CNo,b.EID, b.MergeNo,b.CustId, k.FKOTNo')
						->order_by('b.BillId', 'ASC')
						->group_by('b.BillId')
						->join('Kitchen k','k.MCNo = b.CNo', 'inner')
						->get_where('Billing b', array(
											'b.payRest' => 0,
											'b.LoginCd' => authuser()->RUserId,
											'b.EID' => authuser()->EID
											))
						->result_array();
	}

	public function getUserList(){
		return $this->db2->select("ur.*, ut.UTypName, rt.UTypName as designation")
						->join('UserType ut', 'ut.UTyp = ur.UTyp', 'inner')
						->join('UserType rt', 'rt.UTyp = ur.RestRole', 'inner')
						->get_where('UsersRest ur', 
							array('ur.EID' => authuser()->EID))
						->result_array();
	}

	public function getusersRestData(){
		$EID = authuser()->EID;
		return $this->db2->select('ur.RUserId, ur.FName, ur.LName, ur.MobileNo, ut.UTypName')
						->join('UserType ut', 'ut.UTyp = ur.RestRole', 'inner')
						->get_where('UsersRest ur', 
							array('ur.EID' => $EID, 'ur.Stat' => 0 ))
						->result_array();  	
	}

	public function getCasherList(){
		
		$RUserId = authuser()->RUserId;
		$EID = authuser()->EID;

        $data = array();

		$GetCCD = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();
		if(!empty($GetCCD)){
			$langId = $this->session->userdata('site_lang');
			$cashName = "Name$langId as Name";
	        if(!empty($GetCCD['CCd'])){
	        	$ccd = "(".$GetCCD['CCd'].")";
				$data = $this->db2->query("SELECT CCd, $cashName, PrinterName FROM Eat_Casher Where EID = $EID AND Stat = 0 and CCd in $ccd")->result_array();
	        }
		}

        return $data;
	}

	public function getCashierList(){
		$EID = authuser()->EID;
		$langId = $this->session->userdata('site_lang');
		$cashName = "Name$langId as Name";

        return $this->db2->select("CCd, $cashName, PrinterName")->get_where('Eat_Casher', array('EID' => $EID, 'Stat' => 0))->result_array();
	}

	public function getKitchenList(){
		
		$RUserId = authuser()->RUserId;
		$EID = authuser()->EID;

        $data = array();

		$GetKitCd = $this->db2->get_where('UsersRoleDaily', array('RUserId' => $RUserId))->row_array();
		if(!empty($GetKitCd)){
			$langId = $this->session->userdata('site_lang');
        	$kname = "KitName$langId as KitName";
	        if(!empty($GetKitCd['KitCd'])){
	        	$KitCd = "(".$GetKitCd['KitCd'].")";
				$data = $this->db2->query("SELECT KitCd, $kname FROM Eat_Kit Where EID = $EID AND Stat = 0 and KitCd in $KitCd")->result_array();
	        }
		}

        return $data;
	}


	public function getCTypeList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Usedfor$langId as Usedfor";
		return $this->db2->select("CTyp, $lname")
					->order_by('Rank', 'ASC')
					->group_by('Usedfor1')
					->get_where('FoodType', array('Stat' => 0))
					->result_array();
	}
	

	public function getWeekDayList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";
		return $this->db2->select("DayNo, $lname")
					->get('WeekDays')
					->result_array();	
	}

	public function getMenuTagList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "TDesc$langId as TDesc";
		return $this->db2->select("TagId, $lname, TagTyp")
					->get('MenuTags')
					->result_array();	
	}

	public function getTransactionType(){
		$langId = $this->session->userdata('site_lang');
        $lname = "TDesc$langId as TDesc";
		return $this->db2->select("TagId, $lname, TagTyp")
					->get_where('stockTrans', array('TagTyp' => 1))
					->result_array();	
	}

	public function getUOMlist(){
		$langId = $this->session->userdata('site_lang');
        $lname = "Name$langId as Name";

		return $this->db2->select("UOMCd, $lname")
                              ->get_where('RMUOM', array('Stat' => 0))
                              ->result_array();
	}

	public function getSupplierList(){
		$langId = $this->session->userdata('site_lang');
        $lname = "SuppName$langId as SuppName";

		return $this->db2->select("SuppCd, CreditDays, $lname, Remarks")
                              ->get_where('RMSuppliers', array('Stat' => 0, 'EID' => authuser()->EID))
                              ->result_array();	
	}

	public function getSuppliers(){
		$langId = $this->session->userdata('site_lang');
        $lname = "SuppName$langId as Name";

		return $this->db2->select("SuppCd, CreditDays, $lname, Remarks, Stat")
                              ->get('RMSuppliers')
                              ->result_array();	
	}

	public function getRMItemUOM(){
		$langId = $this->session->userdata('site_lang');
        $rmname = "rm.RMName$langId as RMName";

		return $this->db2->select("rm.*, $rmname")
					->group_by('RMCd')
					->join('RMCatg rc','rm.RMCatg = rc.RMCatgCd','inner')
					->join('RMItemsUOM riu', 'rm.RMCd = riu.RMCd', 'inner')
					->join('RMUOM ru', 'ru.UOMCd = riu.UOMCd', 'inner')
					->get_where('RMItems rm', array(
												'rm.Stat' => 0,
												'rm.EID' => authuser()->EID
												)
								)
					->result_array();
	}

	public function getRMStockDetList($TransId){
		return $this->db2->get_where('RMStockDet', array('TransId' => $TransId, 'Stat' => 0))->result_array();
	}

	public function getDispenseOutletList(){
		$EID = authuser()->EID;
		$langId = $this->session->userdata('site_lang');
        $dispName = "Name$langId as Name";

        return $this->db2->select("DCd, $dispName, Stat")->get_where('Eat_DispOutlets', array('EID' => $EID))->result_array();
	}

	public function getCashier(){
		$langId = $this->session->userdata('site_lang');
        $name = "Name$langId as Name";

        return $this->db2->select("CCd, $name, Stat")->get_where('Eat_Casher', array('EID' => authuser()->EID))
                    ->result_array();
    }

	public function getAllTables(){
		$EID = authuser()->EID;
		$langId = $this->session->userdata('site_lang');
		$section = "es.Name$langId as sectionName";
		$cashier = "ec.Name$langId as cashierName";

		$whr = "et.EID = es.EID and et.EID = ec.EID";
		return $this->db2->select("et.*, $section, $cashier")
					// ->order_by()
					->join('Eat_Sections es', 'es.SecId = et.SecId', 'inner')
					->join('Eat_Casher ec', 'ec.CCd = et.CCd', 'inner')
					->where($whr)
					->get_where('Eat_tables et', array('et.EID' => $EID))
					->result_array();
	}

	public function getAllMenuList(){

        $langId = $this->session->userdata('site_lang');
        $lname = "ur.Name$langId as LngName";

		return $this->db2->select("ur.RoleId, $lname, ur.RoleTyp, ur.pageUrl, ur.Rank, ur.PhpPage, ur.Stat, ur.Title")
                        ->order_by('ur.Rank', 'ASC')
                        ->get('UserRoles ur')
                        ->result_array();
	}

	public function getRecommendationList(){

        $langId = $this->session->userdata('site_lang');
        $item = "mi.ItemNm$langId as ItemNm";
        $recName = "mii.ItemNm$langId as recName";

		return $this->db2->select("mr.RecNo, mr.ItemId, $item, mr.RcItemId, $recName, mr.Stat")
                        ->join('MenuItem mi', 'mi.ItemId = mr.ItemId','inner')
                        ->join('MenuItem mii', 'mii.ItemId = mr.RcItemId','inner')
                        ->get_where('MenuItem_Recos mr', array('mr.EID' => authuser()->EID))
                        ->result_array();
	}

	public function getPaymentType(){

		$langId = $this->session->userdata('site_lang');
        $pname = "Name$langId as Name";
        return $this->db2->select("PMNo, $pname, Rank, Stat")
        				->order_by('Rank', 'ASC')
        				->get('PymtModes')
        				->result_array();

	}

	public function getEntertainment(){

		$langId = $this->session->userdata('site_lang');
        $pname = "Name$langId as Name";
        return $this->db2->select("EntId, $pname, Stat")
        				->get('Entertainment')
        				->result_array();

	}

	public function getItemTypesGroupList(){

		$langId = $this->session->userdata('site_lang');
        $iname = "itg.Name$langId as Name";
        $itname = "it.Name$langId as itemTypeName";
        $miname = "mi.ItemNm$langId as ItemNm";
        return $this->db2->select("itg.*, $iname, $itname, $miname")
        				->join('ItemTypes it', 'it.ItmTyp = itg.ItemTyp', 'inner')
        				->join('MenuItem mi', 'mi.ItemId = itg.ItemId', 'left')
        				->get_where('ItemTypesGroup itg' , array('itg.EID' => authuser()->EID))
        				->result_array();

	}

	public function getItemTypesGroup(){

		$langId = $this->session->userdata('site_lang');
        $iname = "itp.Name$langId as Name";
        return $this->db2->select("itp.*, $iname")
        				->get_where('ItemTypesGroup itp', array('itp.Stat' => 0, 'itp.EID' => authuser()->EID))
        				->result_array();

	}

	public function getItemTypesDet(){
		
		$langId = $this->session->userdata('site_lang');
        $groupName = "itp.Name$langId as groupName";
        $menuName = "mi.ItemNm$langId as menuName";
        return $this->db2->select("itd.*, $groupName, $menuName")
        			->join('ItemTypesGroup itp', 'itp.ItemGrpCd = itd.ItemGrpCd', 'inner')
        			->join('MenuItem mi', 'mi.ItemId = itd.ItemId', 'inner')
        			->get_where('ItemTypesDet itd' , array('itd.EID' => authuser()->EID))
        			->result_array();		
	}

	public function getMenuItemRates($data){
		$EID = authuser()->EID;
		$langId = $this->session->userdata('site_lang');
        $ipName = "ip.Name$langId as Name";

		return $this->db2->select("ip.IPCd, mir.OrigRate, $ipName")
						->order_by('ItmRate', 'ASC')
						->join('MenuItemRates mir', 'mir.ItemId = mi.ItemId', 'inner')
						->join('ItemPortions ip', 'ip.IPCd = mir.Itm_Portion', 'inner')
						->join('Eat_tables et', 'et.SecId = mir.SecId', 'inner')
						->get_where('MenuItem mi', array(
							'mi.ItemId' => $data['itemId'],
							'mir.EID' => $EID,
							'et.TableNo' => $data['TableNo'])
								)
						->result_array();
	}

	public function getItemOfferList($postData){
		$EID = authuser()->EID;
	    $itemId = $postData['ItemId'];
	    $cid = $postData['CID'];
	    $itemTyp = $postData['ItemTyp'];
	    $MCatgId = $postData['MCatgId'];
	    
	    $langId = $this->session->userdata('site_lang');
    	$scName = "c.SchNm$langId as SchNm";
    	$scDesc = "cod.SchDesc$langId as SchDesc";
    	$mname = "mi.ItemNm$langId as menuName";
    	$dis_name = "mii.ItemNm$langId as discName";

		// return $this->db2->query("SELECT $scName, c.SchCd, cod.SDetCd, $scDesc, c.PromoCode, c.SchTyp, c.Rank,cod.Disc_ItemId, cod.Qty,cod.Disc_Qty, cod.IPCd, cod.Disc_IPCd, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId from CustOffersDet as cod join CustOffers as c on c.SchCd=cod.SchCd and  (cod.CID = $cid or cod.MCatgId = $MCatgId or cod.ItemTyp = $itemTyp or cod.ItemId = $itemId) left outer join Cuisines as c1 on cod.CID=c1.CID   left outer join MenuCatg as m on cod.MCatgId = m.MCatgId  left outer join ItemTypes as i on cod.ItemTyp = i.ItmTyp  left outer join MenuItem as mi on mi.ItemId = cod.ItemId where c.SchCd=cod.SchCd  and c.EID=$EID   and c.Stat=0 and (time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)  group by c.schcd, cod.sDetCd order by c.Rank, cod.Rank")->result_array();

		$whr = "(cod.CID = $cid or cod.MCatgId = $MCatgId or cod.ItemTyp = $itemTyp or cod.ItemId = $itemId) and (time(Now()) BETWEEN c.FrmTime and c.ToTime OR time(Now()) BETWEEN c.AltFrmTime AND c.AltToTime) and (date(Now()) BETWEEN c.FrmDt and c.ToDt)";

		return $this->db2->select("$scName, c.SchCd, cod.SDetCd, $scDesc, c.PromoCode, c.SchTyp, c.Rank,cod.Disc_ItemId, $dis_name, cod.Qty,cod.Disc_Qty, cod.IPCd, cod.Disc_IPCd, cod.Rank, cod.Disc_pcent, cod.Disc_Amt, cod.CID, cod.MCatgId, cod.ItemTyp, cod.ItemId, $mname, mii.KitCd, mii.PckCharge, m.TaxType, mii.PrepTime, m.DCd, mii.FID ")
				->order_by('c.Rank, cod.Rank')
				->group_by('c.schcd, cod.sDetCd')
				->join('CustOffersDet cod','c.SchCd= cod.SchCd', 'inner')
				->join('Cuisines c1','c1.CID = cod.CID', 'left')
				->join('MenuCatg m','m.MCatgId = cod.MCatgId', 'left')
				->join('ItemTypes i','i.ItmTyp = cod.ItemTyp', 'left')
				->join('MenuItem mi','mi.ItemId = cod.ItemId', 'inner')
				->join('MenuItem mii','mii.ItemId = cod.Disc_ItemId', 'left')
				->where($whr)
				->get_where('CustOffers c', array(
										'c.EID' => $EID,
										'c.Stat' => 0
										)
							)
				->result_array();
	}

	public function getCustomItemsList($postData){
		$EID = authuser()->EID;
		$FID = $postData['FID'];
		$ItemId = $postData['ItemId'];
		$ItemTyp = $postData['ItemTyp'];
		$Itm_Portion =$postData['Itm_Portion'];

	    $whr = '';
    	if($FID == 1){
    		$whr = "mii.FID = $FID";
    	}else if($FID == 2){
    		$whr = "(mii.FID = 1 or mii.FID = 2)";
    	}

    	$langId = $this->session->userdata('site_lang');
    	$ItemGrpName = "mi.ItemNm$langId as ItemGrpName";
        $ItemNm = "mii.ItemNm$langId as Name";

        if($ItemTyp == 1){

        	$ItemGrpName = "mi.ItemNm$langId as ItemGrpName";
        	$ItemNm = "mii.ItemNm$langId as Name";

        // $ItemGrpName = "itg.Name$langId as ItemGrpName";
        	// itd.Name , $ItemGrpName
            return $this->db2->select("itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, $ItemGrpName, itd.ItemId, $ItemNm, mir.OrigRate as Rate, itg.Reqd")
            		->order_by('itg.Rank, itd.Rank', 'ASC')
            		->join('ItemTypesDet itd', 'itg.ItemGrpCd = itd.ItemGrpCd', 'inner')
            		->join('MenuItem mi', 'mi.ItemId = itg.ItemId', 'inner')
            		->join('MenuItem mii', 'mii.ItemId = itd.ItemId', 'inner')
            		->join('MenuItemRates mir', 'mir.ItemId = mii.ItemId', 'inner')
            		->where($whr)
            		->get_where('ItemTypesGroup itg', array(
            							'itg.EID' => $EID,
            							'itg.Stat' => 0,
            							'itg.ItemId' => $ItemId,
            							'mir.Itm_Portion' => $Itm_Portion,
            							'itg.ItemTyp' => 1
            									)
            					)
            		->result_array();
            
        }else{
        	$ItemGrpName = "itg.Name$langId as ItemGrpName";
            return $this->db2->select("itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, $ItemGrpName, itd.ItemId, $ItemNm, mir.OrigRate as Rate, itg.Reqd")
            		->order_by('itg.Rank, itd.Rank', 'ASC')
            		->join('ItemTypesDet itd', 'itg.ItemGrpCd = itd.ItemGrpCd', 'inner')
            		->join('MenuItem mi', 'mi.ItemId = itg.ItemId', 'left')
            		->join('MenuItem mii', 'mii.ItemId = itd.ItemId', 'inner')
            		->join('MenuItemRates mir', 'mir.ItemId = mii.ItemId', 'inner')
            		->where($whr)
            		->get_where('ItemTypesGroup itg', array(
            							'itg.EID' => $EID,
            							'itg.Stat' => 0,
            							'mir.Itm_Portion' => $Itm_Portion,
            							'itg.ItemTyp' => $ItemTyp
            									)
            					)
            		->result_array();
					// echo "string";print_r($dd);
     //        		print_r($this->db2->last_query());die;

            // $this->db2->query("SELECT itg.GrpType, itd.ItemGrpCd, itd.ItemOptCd, $ItemGrpName, itd.Name, itd.Rate, itg.Reqd From ItemTypesGroup itg join ItemTypesDet itd on itg.ItemGrpCd = itd.ItemGrpCd AND itg.EID = $EID and itg.ItemTyp = $ItemTyp and itd.Itm_Portion= $Itm_Portion $sql order by itg.Rank, itd.Rank")->result_array();
        }
}

	
}
