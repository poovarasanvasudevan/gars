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
$user = $_GET['user'];

$sql = "UPDATE user
				SET
				LocationFk = '$location',
				RoleFk = '$role',
				Password = '$password',
				AbhyasiID = '$abyasiId',
				FirstName = '$fname',
				MiddleName = '$nname',
				LastName = '$lname',
				EmailId = '$email',
				ModifiedBy = '1',
				ModifiedDate =  CURRENT_TIMESTAMP
				WHERE UserPK = '$user'";
$result = $db->setQuery($sql);

if ($result) {
    echo 'success';
} else {
    echo $sql;
}


?>