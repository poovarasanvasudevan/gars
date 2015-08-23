<?php

//abyasiId=ghj&firstName=ghj&middleName=j&lastName=jgh&email=fghf%40emnail.com&location=421&roles=2

include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$abyasiId = $_GET['abyasiId'];
$fname = $_GET['firstName'];
$nname = $_GET['middleName'];
$lname = $_GET['lastName'];
$password = $_GET['password'];
$email = $_GET['email'];
$location = $_GET['location'];
$role = $_GET['roles'];


$sql = "INSERT INTO user VALUES
						(NULL,
						'$location',
						'$role',
						'$password',
						NULL,
						'$abyasiId',
						'$fname',
						'$nname',
						'$lname',
						'$email',
						'1',
						CURRENT_TIMESTAMP,
						'1',
						CURRENT_TIMESTAMP,
						'on')";
$result = $db->setQuery($sql);

if ($result) {
    echo 'success';
} else {
    echo $sql;
}


?>