<?php
include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

header('Content-Type: application/json');
$userid = $_GET['id'];
$sql = "SELECT * FROM user WHERE UserPk = '$userid'";

$userarray = array();

if ($result = $db->setQuery($sql)) {
    while ($row = $result->fetch_assoc()) {
        $userarray[] = $row;
    }


}
echo json_encode($userarray);

		
