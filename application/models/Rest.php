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

	public function getDisableUserList($ChainId, $EID){
		return $this->db2->select('RUserId, FName, LName, MobileNo, Stat')
					->order_by('FName','LName')
					->get_where('UsersRest', array('ChainId' => $ChainId,'EID' => $EID))
					->result_array();
	}

	public function userDisableEnable($data){
		$res = '';
		if($data['type'] == 'disable'){
			$this->db2->update('UsersRest', array('Stat' => 3 ), array('RUserId' => $data['id']));
			$res = "User Disabled";
		}else{
			$this->db2->update('UsersRest', array('Stat' => 0 ), array('RUserId' => $data['id']));
			$res = "User Enabled";
		}
		return $res;
	}

	public function getOffersList(){
		return $this->db2->order_by('SchCd', 'desc')->get_where('CustOffers', array('Stat' => 0))->result_array();
	}

	public function passwordUpdate($password){
 		$this->db2->update('UsersRest', array('Passwd' => $password), array('RUserId' => authuser()->RUserId));
			 	
	}

	public function getRestaurantList($ChainId){
		return $this->db2->select('EID, Name')
						->order_by('EID DESC')
						->get_where('Eatary', array('ChainId' => $ChainId))->result_array();
	}

	public function addUser($data){

		$check = $this->db2->get_where('UsersRest', array('MobileNo' => $data['MobileNo'], 'Stat' => 0))->row_array();
		if(empty($check)){
			$createrData = $this->db2->get_where('UsersRest', array('RUserId' => authuser()->RUserId))->row_array();
			$data['CatgID'] = $createrData['CatgID'];
			$data['DeputedEID'] = $data['EID'];
			$data['ChainId'] = $createrData['ChainId'];
			$data['ONo'] = $createrData['ONo'];
			$data['Stat'] = $createrData['Stat'];
			$data['LoginCd'] = authuser()->RUserId;
			// $data['token'] = $this->generateToken();
			 $newRUserId = insertRecord('UsersRest', $data);

			 // GenTableData loaded
			$genDB = $this->load->database('GenTableData', TRUE);
			$checkGen = $genDB->where('MobileNo' , $data['MobileNo'])
							  ->or_where('PEmail' , $data['PEmail'])
							  ->get('UsersRest')
							  ->row_array();
			
			$GenUsersRestDet['RUserId'] = $newRUserId;
			$GenUsersRestDet['EID'] = $data['EID'];
			$GenUsersRestDet['UTyp'] = $data['UTyp'];
			$GenUsersRestDet['ChainId'] = authuser()->ChainId;
			// $GenUsersRestDet['OffEmail'] = $data['PEmail'];
			if(!empty($checkGen)){
				$genDB->insert('UsersRestDet', $GenUsersRestDet);
			}else{
				$GUsersRest['FName'] = $data['FName'];
				$GUsersRest['LName'] = $data['LName'];
				$GUsersRest['MobileNo'] = $data['MobileNo'];
				$GUsersRest['PEmail'] = $data['PEmail'];
				$GUsersRest['DOB'] = $data['DOB'];
				$GUsersRest['Gender'] = $data['Gender'];
				$genDB->insert('UsersRest', $GUsersRest);
				$genDB->insert('UsersRestDet', $GenUsersRestDet);
			}

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
					$userRolesAccessObj['RoleId']= 22;
					insertRecord('UserRolesAccess', $userRolesAccessObj);

					$userRolesAccessObj['EID'] = $createrData['EID'];
					$userRolesAccessObj['RUserId'] = $newRUserId;
					$userRolesAccessObj['RoleId']= 26;
					insertRecord('UserRolesAccess', $userRolesAccessObj);
				}
			}else{
				$res = "Failed to Create ".$data['FName'].' '.$data['LName'];
			}
			// echo "<pre>";
			// print_r($data);
			// die;

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

			// $availableRoles = $userRolesObj->exec("SELECT ur.RoleId, ur.Name FROM UserRoles ur WHERE  ur.Stat = 0 AND ur.RoleId NOT IN (SELECT RoleId FROM UserRolesAccess WHERE RUserId = $userId AND EID = $EID) Order by ur.Name");
			$availableRoles = $this->db2->query("SELECT ur.RoleId, ur.Name FROM UserRoles ur WHERE  ur.Stat = 0 AND ur.RoleId NOT IN (SELECT RoleId FROM UserRolesAccess WHERE RUserId = $userId AND EID = $EID) Order by ur.Name")->result_array();
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

		if (isset($postdata['getAssignedRoles']) && $postdata['getAssignedRoles']==1) {
			$userId = $postdata['userId'];
			// $getAssignedRoles = $userRolesAccessObj->exec("SELECT ura.URNo, ur.Name FROM `UserRolesAccess` ura, UserRoles ur WHERE ura.RoleId = ur.RoleId AND ur.Stat = 0 AND ura.EID = $EID AND ura.RUserId = $userId Order by ur.Name");

			$getAssignedRoles = $this->db2->query("SELECT ura.URNo, ur.Name FROM `UserRolesAccess` ura, UserRoles ur WHERE ura.RoleId = ur.RoleId AND ur.Stat = 0 AND ura.EID = $EID AND ura.RUserId = $userId Order by ur.Name")->result_array();

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

	}

	public function getDispenseAccess(){
		$RUserId = authuser()->RUserId;

		$userRoleDailyDispence = $this->db2->query("SELECT DCd from UsersRoleDaily where RUserId = $RUserId ")->result_array();

		$userRoleDailyKitchen = $this->db2->query("SELECT KitCd from UsersRoleDaily where RUserId = $RUserId ");

		// $dispenseData = [];
		// $kitchenData = [];

		// if ($userRoleDailyDispence[0]['DCd'] != '') {
		// 	$DCd = $userRoleDailyDispence[0]['DCd'];

		// 	$dispenseData = $eatDispOutletsObj->exec("SELECT DCd, Name FROM Eat_DispOutlets where DCd in ($DCd)");
		// }

		// if ($userRoleDailyKitchen[0]['KitCd'] != '') {
		// 	$KitCd = $userRoleDailyKitchen[0]['KitCd'];

		// 	$kitchenData = $eatKitObj->exec("SELECT KitCd, KitName FROM Eat_Kit where KitCd in ($KitCd)");
		// }



		$EType = $this->session->userdata('EType');
		$EID = authuser()->EID;
		$GetDCD = $this->db2->query("SELECT DCd FROM `UsersRoleDaily` WHERE RUserId = $RUserId")->result_array();
		$tempArray =explode(",",$GetDCD[0]['DCd']);

		$SqlQueryVar = "SELECT DCd, Name, DCdType FROM Eat_DispOutlets Where EID = $EID AND Stat = 0";
		if(count($tempArray) >=1 && $tempArray[0] != ''){
		    $SqlQueryVar .=" AND (";
		for ($i=0; $i <count($tempArray) ; $i++) { 
		    if($i>=1){
		        $SqlQueryVar .=" OR ";
		    }
		    $SqlQueryVar .= "DCd =".$tempArray[$i];
		    
		}
		$SqlQueryVar .= ")";
		}else{

		}

		return $this->db2->query($SqlQueryVar)->result_array();

	}

	public function getStockList($postdata=null){
		$trans_id = NULL;
		$trans_type_id = NULL;
		$from_date = NULL;
		$to_date =NULL;
		$stock = $this->db2->query("SELECT r.TransId, r.TransType,TransDt,  if(r.FrmEID>0, (Select e.Name from Eatary e where e.EID=r.FrmEID),NULL) as FromEID, if(r.FrmStoreId=1, 'MainStore',NULL) as FromMain, if(r.FrmSuppCd>0, (Select rs.SuppName from RMSuppliers rs where rs.SuppCd=r.FrmSuppCd),NULL) as FromSupp, if(r.FrmKitCd>0, (Select ek.KitName from Eat_Kit ek where ek.KitCd=r.FrmKitCd),NULL) as FromKit, if(r.ToEID>0, (Select e.Name from Eatary e where e.EID=r.ToEID),NULL) as ToEID, if(r.ToSuppCd>0, (Select rs.SuppName from RMSuppliers rs where rs.SuppCd=r.ToSuppCd),NULL) as ToSupp, if(r.ToKitCd>0, (Select ek.KitName from Eat_Kit ek where ek.KitCd=r.ToKitCd),NULL) as ToKit, if(r.ToStoreId=1, 'MainStore',NULL) as ToMain  FROM RMStock r where r.Stat=0 order by TransId desc limit 10")->result_array();
		// echo "<pre>";
		// print_r($stock);
		// die;
		if($postdata){
			$trans_id = $postdata['trans_id'];
			$trans_type_id = $postdata['trans_type'];
			$from_date = $postdata['from_date'];
			$to_date = $postdata['to_date'];
			$q = "SELECT r.TransId, r.TransType,  if(r.FrmEID>0, (Select e.Name from Eatary e where e.EID=r.FrmEID),NULL) as FromEID, if(r.FrmStoreId=1, 'MainStore',NULL) as FromMain, if(r.FrmSuppCd>0, (Select rs.SuppName from RMSuppliers rs where rs.SuppCd=r.FrmSuppCd),NULL) as FromSupp, if(r.FrmKitCd>0, (Select ek.KitName from Eat_Kit ek where ek.KitCd=r.FrmKitCd),NULL) as FromKit, if(r.ToEID>0, (Select e.Name from Eatary e where e.EID=r.ToEID),NULL) as ToEID, if(r.ToSuppCd>0, (Select rs.SuppName from RMSuppliers rs where rs.SuppCd=r.ToSuppCd),NULL) as ToSupp, if(r.ToKitCd>0, (Select ek.KitName from Eat_Kit ek where ek.KitCd=r.ToKitCd),NULL) as ToKit, if(r.ToStoreId=1, 'MainStore',NULL) as ToMain  FROM RMStock r where r.Stat=0";

			// $q = "SELECT * from RMStock as rs join RMStockDet as rmd on rs.TransId = rmd.TransId join RMItems as rm on rmd.RMCd = rm.RMCd join RMCatg as rc on rm.RMCatg = rc.RMCatgCd join RMItemsUOM as riu on rm.RMCd = riu.RMCd join RMUOM as ru on ru.UOMCd = riu.UOMCd where Stat = 0";
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
		return $this->db2->query("SELECT rsd.RMCd, rmi.RMName, rmi.ItemId, rmc.RMCatgName, sum(case when rs.TransType < 10 Then rsd.Qty ELSE 0 end) as sell, sum(case when rs.TransType > 10 Then rsd.Qty else 0 end) as rcvd FROM `RMStockDet` as rsd, RMStock as rs, RMItems as rmi, RMCatg as rmc where rsd.TransId = rs.TransId and rmi.RMCd = rsd.RMCd and rmi.RMCatg = rmc.RMCatgCd and rsd.Stat = 0 and rs.Stat = 0 group by rsd.RMCd order by rmc.RMCatgName, rmi.RMName")->result_array();
	}

	public function getStockConsumption(){
		return $report = $this->db2->query("SELECT rsd.RMCd, rmi.RMName, rmi.ItemId, rmi.IPCd, rmc.RMCatgName,(select sum(rsd1.Qty)  from RMStockDet rsd1 where rsd1.TransId=rs.TransId and rs.TransType>10 and rsd1.Stat = 0 group by  rsd1.RMCd) as rcvd,(select sum(rsd1.Qty)  from RMStockDet rsd1 where rsd1.TransId=rs.TransId and rs.TransType<10 and rsd1.Stat = 0 group by  rsd1.RMCd) as issued,  sum(k.Qty) as Qty FROM RMStockDet as rsd, RMStock as rs, RMItems as rmi, RMCatg as rmc, Kitchen as k where rsd.TransId = rs.TransId and rmi.RMCd = rsd.RMCd and rmi.RMCatg = rmc.RMCatgCd and rmi.ItemId = k.ItemId and rmi.ItemId > 0 and rsd.Stat = 0 and rs.Stat = 0 group by rs.TransId, rsd.RMCd, rmi.IPCd order by rmc.RMCatgName, rmi.RMName")->result_array();
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
			$q = "SELECT t.ShortName, t.TaxPcent, t.TaxType, t.Included, Sum(bt.TaxAmt) as SubAmtTax, t.rank from Tax t, BillingTax bt where bt.EID=t.EID and bt.TNo=t.TNo and bt.EID=$EID and bt.BillId = $billId and bt.TNo=t.TNo and t.TaxType = $value group by t.ShortName,t.TaxPcent, t.TaxType, t.Included ,t.rank order by t.rank";
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
		return $this->db2->get_where('3POrders', array("Stat" => 0))->result_array();
	}

	public function getTablesAllotedData($EID){
		return $this->db2->get_where('Eat_tables', array("EID" => $EID))->result_array();
	}

	public function getItemLists(){
		return $this->db2->select('i.*, c.RMCatgName')
						->join('RMCatg c','c.RMCatgCd = i.RMCatg', 'inner')
						->get_where('RMItems i', array('i.Stat' => 0))
						->result_array();
	}

	public function getBomDishLists(){
		return 	$this->db2->select('b.*, rm.Name,r.RMName,m.ItemNm')
						->join('MenuItem m','m.MCatgId = b.ItemId', 'inner')
						->join('RMItems r','r.RMCd = b.RMCd', 'inner')
						->join('RMUOM rm', 'rm.UOMCd= b.RMUOM', 'inner')
						->get('BOM_Dish b')
						->result_array();
	}

	// public function getRMUOMList(){
	// 	return $this->db2->get_where('RMUOM', array('Stat' => 0))->result_array();
	// }

	public function getRmUOMlist($RMCd){
		return $this->db2->select('r.*, rm.RMCd')
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
			$this->db2->where('PaymtMode',$data['pmode']);
		}	
		return $this->db2->select('bp.*, b.BillNo, b.BillPrefix, b.BillSuffix')->order_by('bp.PymtNo', 'DESC')
						->join('Billing b', 'b.BillId = bp.BillId', 'inner')
						->get_where('BillPayments bp', array('bp.Stat' => 1))
						->result_array();
						// print_r($this->db2->last_query());
						// die;
	}

	public function getPaymentModes(){
		return $this->db2->select('PymtMode,Name,Company, CodePage1')->get_where('ConfigPymt', array('Stat' => 1))->result_array();
	}

	public function get_MCatgId(){
		return $this->db2->select('MCatgId, MCatgNm')->get_where('MenuCatg', array('EID' => authuser()->EID, 'Stat' => 0 ))->result_array();
	}
	
	public function getCuisineList(){
		return $this->db2->select('c.CID, c.Name, c.Name2, c.Name3, c.Name4')
						->order_by('ec.Rank', 'ASC')
						->join('Cuisines c', 'c.CID = ec.CID', 'inner')
						->get_where('EatCuisine ec', array('ec.EID' => authuser()->EID,'ec.Stat' => 0))
						->result_array();
	}

	public function get_foodType(){
		return $this->db2->select('FID, Opt')->order_by('CTyp, Rank','ASC')->get_where('FoodType', array('Stat' => 0, 'EID' => authuser()->EID))->result_array();	
	}

	public function get_kitchen(){
		return $this->db2->select('KitCd ,KitName')->get_where('Eat_Kit', array('Stat' => 0))->result_array();	
	}

	public function get_eat_section(){
		return $this->db2->select('SecId,Name')->get('Eat_Sections')->result_array();	
	}

	public function get_item_portion(){
		return $this->db2->select('IPCd,Name')->get('ItemPortions')->result_array();	
	}

	public function get_item_name_list($name){
		$item_name = $name.'%';
		return $this->db2->query("SELECT ItemId,ItemNm FROM `MenuItem` WHERE LOWER(ItemNm) LIKE '$item_name'")->result_array();
	}

	public function getKotList($MCNo, $mergeNo, $FKOTNo){
		$EID = authuser()->EID;
		$EType = $this->session->userdata('EType');
		$stat = ($EType == 5)?3:2;
         return $this->db2->select("k.ItemId, k.MCNo, m.ItemNm,k.CustItemDesc,k.CustRmks, ip.Name as Portions, sum(k.Qty) Qty,k.TableNo,k.KOTNo, k.FKOTNo,k.KitCd, ek.KitName, k.UKOTNo,k.LstModDt,k.TA,k.EDT, k.OType")
        					->order_by('k.FKOTNo, m.ItemNm, ek.KitName, k.UKOTNo', 'ASC')
        					->group_by('k.ItemId, ek.KitName,k.Itm_Portion')
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
			return $this->db2->select("b.TableNo,b.MergeNo, b.BillId, b.BillNo, DATE_FORMAT(DATE(billTime),'%d/%m/%Y') as BillDate, b.TotAmt as BillValue, b.PaidAmt, bp.PaymtMode, bp.TotBillAmt, bp.PymtType,bp.PaidAmt as bpPaidAmt, b.CNo, u.CustId, (case when cp.Name != '' Then cp.Name ELSE 'Unpaid' end) as pymtName,b.payRest")
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

			$billData = $this->db2->select('b.TableNo, b.BillId, b.BillNo, b.billTime as BillDate, b.CellNo,b.CustId,b.TotAmt,b.PaidAmt bPaidAmt, bp.MergeNo, bp.PaidAmt, bp.OrderRef, bp.PymtRef, bp.PaymtMode, bp.PymtType, bp.Stat, bp.PymtDate,cp.Name, cp.Company')
					->order_by('b.BillId', 'ASC')
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

		if(!empty($kitcd)){
			$this->db2->where('k.KitCd', $kitcd);
		}

		$data = $this->db2->select('k.OrdNo,k.EDT,k.KitCd,k.KStat,k.KOTNo, k.FKOTNo, k.ItemId, k.Qty, k.CustItemDesc, k.Itm_Portion, k.TableNo,k.MergeNo,k.CustRmks,k.TA,k.LstModDt,k.OType,m.ItemNm,ip.Name as Portions')
						  ->order_by('k.FKOTNo', 'ASC')
						  ->group_by('k.KitCd, k.FKOTNo, k.ItemId,k.CustItemDesc, k.Itm_Portion,k.CustRmks,k.TA')
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
		// echo "<pre>";
		// // print_r($data);
		// // die;


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

		$data = $this->db2->select('k.ItemId, sum(k.Qty) as Qty, k.CustItemDesc, k.Itm_Portion, k.TableNo,k.MergeNo,k.CustRmks,k.TA,k.LstModDt,k.OType,m.ItemNm,ip.Name as Portions')
						  ->order_by('m.ItemNm', 'ASC')
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
		return $this->db2->get_where('UsersRest', array('EID' => authuser()->EID,'Stat' => 0))
		->result_array();
	}

	

	
}
