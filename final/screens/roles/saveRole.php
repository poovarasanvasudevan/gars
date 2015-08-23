<?php

session_start();
include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$rolecode = $_GET['rolecode'];
$roleName = $_GET['roledesc'];

$user = $_SESSION['userPK'];

$sql = "INSERT INTO role VALUES
					(NULL,
					 NULL,
					'$rolecode',
					'$roleName',
					'$user',
					CURRENT_TIMESTAMP,
					'$user',
					CURRENT_TIMESTAMP)";
$result = $db->setQuery($sql);

if ($result) {
    echo 'success';
} else {
    echo $sql;
}


?>