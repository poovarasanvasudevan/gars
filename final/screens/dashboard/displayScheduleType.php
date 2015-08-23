<?php
session_start();
include "../common/DatabaseConnection.php";

$db = new DatabaseConnection();
$db->createConnection();

$locationFK = $_SESSION['userLoc'];
$type = $_GET['artefactType'];
$dateType = $_GET['dtype'];


if ($dateType == 'c') {
    getCurrentDate($locationFK, $type, $db);
} else {
    getPending($locationFK, $type, $db);
}


function getCurrentDate($locationFK, $type, $db)
{
    $sql = "select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
		inner join artefact a
		on s.ArtefactCode = a.ArtefactCode
		where  s.ScheduleMaintenancePK not in  (select ScheduleMaintenanceFK from tasklist)
		and s.ScheduledServiceDate = current_date() and s.LocationFK='$locationFK' and s.ArtefactTypeCode = '$type' and a.visiblestatus='on'";


    if ($result = $db->setQuery($sql)) {
        if ($result->num_rows > 0) {
            $resultArray = array();
            while ($row = $result->fetch_assoc()) {
                $url = "conditionalReport.php?artefactCode=" . $row['ArtefactCode'] . "&key=" . $row['ScheduleMaintenancePK'];
                $name = $row['ArtefactName'];


                echo "<a href=" . $url . " class='list-group-item'>" .
                    "<span class='glyphicon glyphicon-file'></span>" . $name . "<span class='badge'>" . date('d/m/Y', strtotime($row['ScheduledServiceDate'])) . "</span></a>";
                //echo " <a href=".$url." class='list-group-item' ><span class='glyphicon glyphicon-file'></span>".$name."<span class='badge'>".date('d/m/Y', strtotime($row['ScheduledServiceDate']))."</span></a>";
            }
            //echo json_encode($resultArray);
        } else {
            echo "NULL";
        }
    }

}

function getPending($locationFK, $type, $db)
{
    $sql = "select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
					inner join artefact a
					on s.ArtefactCode = a.ArtefactCode
					where  s.ScheduleMaintenancePK not in  (select ScheduleMaintenanceFK from tasklist)
					and s.ScheduledServiceDate < current_date() and s.LocationFK='$locationFK' and s.ArtefactTypeCode = '$type' and a.visiblestatus='on'";


    if ($result = $db->setQuery($sql)) {
        if ($result->num_rows > 0) {
            $resultArray = array();
            while ($row = $result->fetch_assoc()) {
                $url = "conditionalReport.php?artefactCode=" . $row['ArtefactCode'] . "&key=" . $row['ScheduleMaintenancePK'];
                $name = $row['ArtefactName'];


                echo "<a href=" . $url . " class='list-group-item'>" .
                    "<span class='glyphicon glyphicon-file'></span>" . $name . "<span class='badge'>" . date('d/m/Y', strtotime($row['ScheduledServiceDate'])) . "</span></a>";

                //echo " <a href=".$url." class='list-group-item' ><span class='glyphicon glyphicon-file'></span>".$name."<span class='badge'>".date('d/m/Y', strtotime($row['ScheduledServiceDate']))."</span></a>";
            }
            //echo json_encode($resultArray);
        } else {
            echo "NULL";
        }
    }
}

?>