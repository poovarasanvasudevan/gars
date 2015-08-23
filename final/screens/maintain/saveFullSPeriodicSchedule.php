<?php
session_start();
include '../common/DatabaseConnection.php';

//conversion dd-mm-yyy to yyyy-mm-dd 
$currentdate = date('Y-m-d', strtotime($_GET['currentDate']));
$endDate = date('Y-m-d', strtotime($_GET['endDate']));
$artefactType = $_GET['type'];


$location = $_SESSION['userLoc'];
$userPK = $_SESSION['userPK'];
$db = new DatabaseConnection();
$conn = $db->createConnection();


$lastDateModified = date('Y-m-d', strtotime($_GET['endDate']));
/*
 * Query if data already exista in maintainance cycle
 * if exists update
 * else
 * insert
 * */

$sql = "select artefactCode from " . $artefactType . "Attributes WHERE VisibleStatus='on'";
$result1 = $db->setQuery($sql);

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {

        $artefactCode = $row['artefactCode'];


        $q;
        $message = '';

        $res1;
        $q = "INSERT INTO maintenancecycle
			 		VALUES
			 		(	NULL,
				 		'$location',
				 		'$artefactType',
				 		'$artefactCode',
				 		'$currentdate',
				 		'0',
				 		'NULL',
				 		'$endDate',
				 		'$lastDateModified',
				 		'$userPK',
				 		'$currentdate',
				 		'$userPK',
				 		'$currentdate'
			 		)";

        $maintainResult = $db->setQuery($q);
        $latRecordId = $conn->insert_id;
        $sql = "INSERT INTO scheduledmaintenance
			 			(	ScheduleMaintenancePK,
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
				 			'$artefactType',
				 			'$artefactCode',
				 			'$location',
				 			'$endDate',
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


    }
    echo $message;
} else {
    echo "NoArtefactHere";
}
 
