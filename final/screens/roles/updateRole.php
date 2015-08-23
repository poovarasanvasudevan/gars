<?php
session_start();
include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$id = $_GET['id'];
$rolecode = $_GET['rolecode'];
$roleName = $_GET['roledesc'];
$user = $_SESSION['userPK'];
$sql = "UPDATE role
			SET
			Code = '$rolecode',
			Description = '$roleName',
			ModifiedBy = '$user',
			ModifiedDate = CURRENT_TIMESTAMP
			WHERE RolePk ='$id'";
$result = $db->setQuery($sql);

if ($result) {
    echo 'success';
} else {
    echo $sql;
}