<?php


$currentdate = $_GET['currentDate'];
$endDate = $_GET['endDate'];
$type = $_GET['type'];
$code = $_GET['code'];


session_start();
include '../common/DatabaseConnection.php';


$currentdateq = date('Y-m-d', strtotime($currentdate));
$endDateq = date('Y-m-d', strtotime($endDate));


$location = $_SESSION['userLoc'];
$userPK = $_SESSION['userPK'];


$db = new DatabaseConnection();
$conn = $db->createConnection();


/*
 * Query if data already exista in maintainance cycle
 * if exists update
 * else
 	* insert
 	* */

$q;
$message = '';

$res1;
$q = "INSERT INTO maintenancecycle
		 			VALUES
		 			(NULL,
			 			'$location',
			 			'$type',
			 			'$code',
			 			'$currentdateq',
			 			'0',
			 			'NULL',
			 			'$endDateq',
			 			'$endDateq',
			 			'$userPK',
			 			'$currentdateq',
			 			'$userPK',
			 			'$currentdateq'
		 			)";

$maintainResult = $db->setQuery($q);
$latRecordId = $conn->insert_id;


$sql = "INSERT INTO scheduledmaintenance
		 				(ScheduleMaintenancePK,
			 				MaintenanceCycleFK,
			 				ArtefactTypeCode,
			 				ArtefactCode,
			 				LocationFK,
			 				ScheduledServiceDate,
			 				CurrentStatus,
			 				CreatedDate,
			 				CreatedBy,
			 				ModifiedDate,
			 				ModifiedBy
		 				) VALUES
		 				(	NULL,
			 				'$latRecordId',
			 				'$type',
			 				'$code',
			 				'$location',
			 				'$endDateq',
			 				'Pending',
			 				CURRENT_TIMESTAMP,
			 				'$userPK',
			 				'$currentdate',
			 				'$userPK'
		 				)";

$res1 = $db->setQuery($sql);


if ($maintainResult) {
    $message .= "Succesfully Updated";
} else {
    $message .= $q;
}

echo $message;

