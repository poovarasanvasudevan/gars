<?php
/**
 * Created by PhpStorm.
 * User: poovarasan
 * Date: 22/8/15
 * Time: 7:44 PM
 */

include "../common/DatabaseConnection.php";
$db = new DatabaseConnection();
$db->createConnection();

$id=$_GET['id'];

$deleteSMT = "delete from scheduledmaintenance where MaintenanceCycleFK='$id'";
$deleteMT = "delete from maintenancecycle where MaintenanceCyclePK='$id'";

if($db->setQuery($deleteSMT)) {

    if($db->setQuery($deleteMT)) {

        echo "success";
    } else {
        echo "fail";
    }
}else {
    echo "fail";
}