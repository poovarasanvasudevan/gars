<?php


include "../common/DatabaseConnection.php";

$db = new DatabaseConnection();
$db->createConnection();

$type=$_GET['type'];
$fromDate = date("Y-m-d", strtotime($_GET['fromdate']));
$toDate = date("Y-m-d", strtotime($_GET['todate']));

//echo $fromDate;

$fromDate = date("Y-m-d", strtotime($_GET['fromdate']));
$toDate = date("Y-m-d", strtotime($_GET['todate']));
	
	//Valid from and end Date
$sql = "select
			c.ArtefactTypeCode,
			c.ArtefactCode,
			l.Description,
			u.FirstName,
			c.Purpose,
			c.Remarks,
			c.CheckInDate
			from artefactcico c
			inner join
			archivelocation l
			on c.LocationSK = l.LocationPk
			inner join user u
			on c.userfk = u.UserPk
			where c.CICOTYPE = '$type'
			and c.CheckInDate BETWEEN '$fromDate' AND '$toDate'
		";


$result = $db->setQuery($sql);
if($result->num_rows > 0) {
	
	$resultArray = array();
	while($row = $result->fetch_assoc()) {
		$temp = array(
				'artefactType' => $row['ArtefactTypeCode'],
				'artefactCode' => $row['ArtefactCode'],
				'Description'  => $row['Description'],
				'FirstName'    => $row['FirstName'],
				'Purpose'      => $row['Purpose'],
				'Remarks'      => $row['Remarks'],
				'CheckInDate'  => $row['CheckInDate']
		);
		
		array_push($resultArray, $temp);
	}
	
	echo json_encode($resultArray);
}else {
	echo "No";	
}
