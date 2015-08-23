<?php

session_start();

include '../common/DatabaseConnection.php';

$params = array();
foreach ($_GET as $key => $value) {
    $params[$key] = $value;
}

$artefactCode = $params['artefactCode'];
$scheduledKey = $params['scheduledKey'];
$location = $_SESSION['userLoc'];
$user = $_SESSION['userPK'];
$currentDate = date('Y-m-d');

$db = new DatabaseConnection();
$conn = $db->createConnection();

$taskListQuery = "
				INSERT INTO tasklist
						VALUES
						(NULL,
						'$artefactCode',
						'$scheduledKey',
						'$location',
						'$user',
						'$currentDate',
						'$user',
						'NULL',
						'$user',
						'NULL')";

$taskResult = $db->setQuery($taskListQuery);
$latRecordId = $conn->insert_id;

$reportQuery;
foreach ($params as $paramkey => $paramvalue) {

    if ($paramkey != 'artefactCode' || $paramkey != 'scheduledKey') {


        $reportQuery = "
							INSERT INTO conditionalreport
							VALUES
							(
								'NULL',
								'$paramkey',
								'$latRecordId',
								'$paramvalue',
								'$user',
								'$currentDate',
								'$user',
								'$currentDate'
								
							);
				
				";

        $db->setQuery($reportQuery);

    }

}

$updateSMsql = "UPDATE scheduledmaintenance
				SET CurrentStatus = 'Completed'
				WHERE ScheduleMaintenancePK = $scheduledKey";

$db->setQuery($updateSMsql);
if ($taskResult) {
    echo 'success';
} else {
    echo $reportQuery;
}


//print_r($params);