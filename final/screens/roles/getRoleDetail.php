<?php

include '../common/DatabaseConnection.php';

$db = new DatabaseConnection();
$db->createConnection();

$id = $_GET['id'];
$rolesDetail = array();

$sql = "SELECT code,description FROM role WHERE  RolePk = '$id'";
$res = $db->setQuery($sql);

while ($row = $res->fetch_assoc()) {

    $rolesDetail[] = $row;
}

echo json_encode($rolesDetail);


?>