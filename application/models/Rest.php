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
		return $this->db2->order_by('SchCd', 'desc')->get('CustOffers')->result_array();
	}

	public function passwordUpdate($data){
		$res = '';

		if($data['password'] == $data['c_password']){
			$check = $this->db2->select('Passwd')->get_where('UsersRest', array('RUserId' => authuser()->RUserId))->row_array();
			 if(!empty($check)){
			 	if($check['Passwd'] == $data['old_password']){
			 		$this->db2->update('UsersRest', array('Passwd' => $data['password']), array('RUserId' => authuser()->RUserId));
			 		$res = "Password has been Changed";
			 	}
			 }else{
			 	$res = "Failed to Validate User";
			 }
		}else{
			$res = "Passwords Don't Match";
		}
		return $res;
	}

	public function getRestorentList($ChainId){
		return $this->db2->select('EID, Name')
						->order_by('EID DESC')
						->get_where('Eatary', array('ChainId' => $ChainId))->result_array();
		// SELECT EID, Name FROM `Eatary` WHERE ChainId = $ChainId ORDER BY EID = 2 DESC, EID ASC
	}

	public function addUser($data){
		// UsersRest
		// UserRolesAccess
		$check = $this->db2->get_where('UsersRest', array('MobileNo' => $data['MobileNo'], 'Stat' => 0))->row_array();
		if(empty($check)){
			$createrData = $this->db2->get_where('UsersRest', array('RUserId' => authuser()->RUserId))->row_array();
			$data['CatgID'] = $createrData['CatgID'];
			$data['DeputedEID'] = $data['EID'];
			$data['ChainId'] = $createrData['ChainId'];
			$data['ONo'] = $createrData['ONo'];
			$data['Stat'] = $createrData['Stat'];
			$data['LoginCd'] = $createrData['LoginCd'];

			 $this->db2->insert('UsersRest', $data);
			 $newRUserId = $this->db2->insert_id();
			if(!empty($newRUserId)){
				$res = "user created successfully";
				if($data['UTyp'] == 9){

					$userRolesAccessObj['EID'] = $createrData['EID'];
					$userRolesAccessObj['RUserId'] = $newRUserId;
					$userRolesAccessObj['RoleId']= 21;
					$this->db2->insert('UserRolesAccess', $userRolesAccessObj);

					$userRolesAccessObj['EID'] = $createrData['EID'];
					$userRolesAccessObj['RUserId'] = $newRUserId;
					$userRolesAccessObj['RoleId']= 22;
					$this->db2->insert('UserRolesAccess', $userRolesAccessObj);

					$userRolesAccessObj['EID'] = $createrData['EID'];
					$userRolesAccessObj['RUserId'] = $newRUserId;
					$userRolesAccessObj['RoleId']= 26;
					$this->db2->insert('UserRolesAccess', $userRolesAccessObj);
				}
			}else{
				$res = "failed to create user";
			}
			// echo "<pre>";
			// print_r($data);
			// die;

		}else{
			$res = "Mobile No is Allready Exists";
		}
		return $res;
	}

	public function mergeTables($postdata){

		if (isset($postdata['getUnmergeTables']) && $postdata['getUnmergeTables']) {
			// $tables = $eatTableObj->exec("SELECT TableNo, MergeNo from Eat_tables where TableNo = MergeNo and Stat=0 order by TableNo ASC");
			$tables = $this->db2->select('TableNo, MergeNo')
								->order_by('TableNo', 'ASC')
								->get_where('Eat_tables', array('TableNo' => 'MergeNo','Stat' => 0))
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

// test

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
	// $q = "SELECT distinct MergeNo from Eat_tables where TableNo != MergeNo order by MergeNo ASC";

	$tables = $this->db2->select('MergeNo')->distinct('MergeNo')->order_by('MergeNo','asc')->get_where('Eat_tables', array('TableNo !=' => 'MergeNo'))->result_array();
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
	// print_r($response);exit();
	// echo json_encode($response);
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
	$q1 = "UPDATE Eat_tables set MergeNo = TableNo where MergeNo = '$mergeNo'";



	$tables = $this->db2->query($q1);
	// $q2 = 
}

	}


	public function getUserAccessRole($postdata){
		$EID = authuser()->EID;
			if ($postdata['getUser']) {
				$mobileNumber =  $postdata['mobileNumber'];
				// $user = $userRestObj->search(["MobileNo" => $mobileNumber, "EID" => $EID]);
				$user = $this->db2->get_where('UsersRest', array("MobileNo" => $mobileNumber, "EID" => $EID))->row_array();
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

			if ($postdata['getAvailableRoles']) {
			$userId =  $postdata['userId'];

			// $availableRoles = $userRolesObj->exec("SELECT ur.RoleId, ur.Name FROM UserRoles ur WHERE  ur.Stat = 0 AND ur.RoleId NOT IN (SELECT RoleId FROM UserRolesAccess WHERE RUserId = $userId AND EID = $EID) Order by ur.Name");
			$availableRoles = $this->db2->query("SELECT ur.RoleId, ur.Name FROM UserRoles ur WHERE  ur.Stat = 0 AND ur.RoleId NOT IN (SELECT RoleId FROM UserRolesAccess WHERE RUserId = $userId AND EID = $EID) Order by ur.Name")->row_array();
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

		if ($postdata['setRoles']) {
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
				if(!empty($idd)){
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

		if ($postdata['getAssignedRoles']) {
			$userId = $postdata['userId'];
			// $getAssignedRoles = $userRolesAccessObj->exec("SELECT ura.URNo, ur.Name FROM `UserRolesAccess` ura, UserRoles ur WHERE ura.RoleId = ur.RoleId AND ur.Stat = 0 AND ura.EID = $EID AND ura.RUserId = $userId Order by ur.Name");

			$getAssignedRoles = $this->db2->query("SELECT ura.URNo, ur.Name FROM `UserRolesAccess` ura, UserRoles ur WHERE ura.RoleId = ur.RoleId AND ur.Stat = 0 AND ura.EID = $EID AND ura.RUserId = $userId Order by ur.Name")->row_array();

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

		if ($postdata['removeRoles']) {
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
		$stock = $this->db2->query("SELECT r.TransId, r.TransType,  if(r.FrmEID>0, (Select e.Name from Eatary e where e.EID=r.FrmEID),NULL) as FromEID, if(r.FrmStoreId=1, 'MainStore',NULL) as FromMain, if(r.FrmSuppCd>0, (Select rs.SuppName from RMSuppliers rs where rs.SuppCd=r.FrmSuppCd),NULL) as FromSupp, if(r.FrmKitCd>0, (Select ek.KitName from Eat_Kit ek where ek.KitCd=r.FrmKitCd),NULL) as FromKit, if(r.ToEID>0, (Select e.Name from Eatary e where e.EID=r.ToEID),NULL) as ToEID, if(r.ToSuppCd>0, (Select rs.SuppName from RMSuppliers rs where rs.SuppCd=r.ToSuppCd),NULL) as ToSupp, if(r.ToKitCd>0, (Select ek.KitName from Eat_Kit ek where ek.KitCd=r.ToKitCd),NULL) as ToKit, if(r.ToStoreId=1, 'MainStore',NULL) as ToMain  FROM RMStock r where r.Stat=0 order by TransId desc limit 10")->result_array();
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
		return $this->db2->query("SELECT rsd.RMCd, rmi.RMName, rmi.ItemId, rmc.RMCatgName, sum(case when rs.TransType < 10 Then rsd.Qty ELSE 0 end) as sell, sum(case when rs.TransType > 10 Then rsd.Qty else 0 end) as rcvd FROM `RMStockDet` as rsd, RMStock as rs, RMItems as rmi, RMCatg as rmc where rsd.TransId = rs.TransId and rmi.RMCd = rsd.RMCd and rmi.RMCatg = rmc.RMCatgCd group by rsd.RMCd order by rmc.RMCatgName, rmi.RMName")->result_array();
	}

	public function getStockConsumption(){
		return $report = $this->db2->query("SELECT rsd.RMCd, rmi.RMName, rmi.ItemId, rmi.IPCd, rmc.RMCatgName,(select sum(rsd1.Qty)  from RMStockDet rsd1 where rsd1.TransId=rs.TransId and rs.TransType>10 group by  rsd1.RMCd) as rcvd,(select sum(rsd1.Qty)  from RMStockDet rsd1 where rsd1.TransId=rs.TransId and rs.TransType<10 group by  rsd1.RMCd) as issued,  sum(k.Qty) as Qty FROM RMStockDet as rsd, RMStock as rs, RMItems as rmi, RMCatg as rmc, Kitchen as k where rsd.TransId = rs.TransId and rmi.RMCd = rsd.RMCd and rmi.RMCatg = rmc.RMCatgCd and rmi.ItemId = k.ItemId and rmi.ItemId > 0 group by rs.TransId, rsd.RMCd, rmi.IPCd order by rmc.RMCatgName, rmi.RMName")->result_array();
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

	

	
}
