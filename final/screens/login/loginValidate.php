<?php

session_start();     // Starting Session

include "../common/DatabaseConnection.php";

// Define $username and $password
$username = $_POST['username'];
$password = $_POST['password'];

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$obj = new DatabaseConnection();
$conn = $obj->createConnection();


// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
//$username = mysql_real_escape_string($username);
//$password = mysql_real_escape_string($password);


// SQL query to fetch information of registerd users and finds user match.
$query = $obj->setQuery("select * from user where abhyasiid='$username' AND password='$password' and ActiveStatus='on'");

$rows = $query->num_rows;

if ($rows == 1) {
    $_SESSION['artefactUser'] = $username;
    header("location: ../dashboard/dashboard.php"); // Redirecting To Other Page
} else {
    header("location: ../../index.php?error=invalid");
}


?>

