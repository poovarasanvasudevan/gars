<?php
//It delete the artefact in the table
//it need to delete data from following table
//Atribute value
//Artefact
//VHS->BOK->

//http://localhost:81/final/deleteArtefact.php?artefactType=BOK&artefactCode=109788

include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->createConnection();

$type = $_GET['artefactType'];
$code = $_GET['artefactCode'];


//check for maintenance
$sqlMQ = "SELECT count(MaintenanceCyclePK) as result FROM maintenancecycle where ArtefactCode='$code'";

$resMQ = $db->setQuery($sqlMQ);
$c;
if ($row = $resMQ->fetch_assoc()) {
    $c = $row['result'];
}

if ($c > 0) {
    echo "failed to delete parent";
} else {
    //delete from temp table
    $sql = "update " . $type . "attributes set visiblestatus='off' WHERE  artefactCode = '$code'";

    //delete from attribute value table
    //$sql1 = "update attributevalue WHERE  artefactCode = '$code'";

    //delete from artefact Table

    $sql2 = "update artefact set visiblestatus='off' WHERE artefactCode = '$code'";

    $db->setQuery("SET SQL_SAFE_UPDATES=0");

    if ($res = $db->setQuery($sql2)) {
        //$arr = array($res,$conn);
        if ($db->setQuery($sql)) {

            echo "success";
        } else {
            echo "failed to delete parent";

        }
    } else {
        echo "failed to delete parent";
    }
}

//echo $sql."->".$sql1."->".$sql2;

