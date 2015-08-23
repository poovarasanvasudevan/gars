<?php
session_start();
include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$id = $_GET['id'];

$sql = "select * from user where rolefk = '$id'";
$result = $db->setQuery($sql);
if ($result->num_rows > 0) {

    echo "Failed to Delete";
} else {
    $sql2 = "delete from role_page_mapping where RoleFk='$id'";
    $sql1 = "DELETE FROM role WHERE RolePk='$id'";
    if ($db->setQuery($sql2)) {
        if ($db->setQuery($sql1)) {
            echo 'success';
        }
    }
}
