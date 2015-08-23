<?php

include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$oldName = $_GET['oldName'];
$newName = $_GET['newName'];

if ($db->setQuery("call RenameArtefact('$oldName','$newName')")) {
    echo "success";
} else {
    echo "fail";
}

?>