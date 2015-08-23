<?php
session_start();

$user = $_SESSION['userPK'];
include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();
$role = $_GET['role'];
$ids = explode(',', substr($_GET['permission'], 1));

if ($db->setQuery("delete  from role_page_mapping where rolefk='$role'")) {
    $result;
    for ($i = 0; $i < sizeof($ids); $i++) {
        $sql = " INSERT INTO role_page_mapping
							VALUES
							(NULL,
							'$role',
							'$ids[$i]',
							'$user',
							CURRENT_TIMESTAMP,
							'$user',
							CURRENT_TIMESTAMP)";

        $result = $db->setQuery($sql);
    }

    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
} else {
    echo "fail";
}


?>