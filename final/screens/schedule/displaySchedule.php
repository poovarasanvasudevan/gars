<?php
session_start();

$user = $_SESSION['userPK'];
$locationFK = $_SESSION['userLoc'];
include '../common/DatabaseConnection.php';

$db = new DatabaseConnection();
$db->createConnection();
$freq = $_GET['freq'];
$sql;
if ($freq == 'today') {
    $sql = "select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
																		inner join artefact a
																		on s.ArtefactCode = a.ArtefactCode
																		where  s.ScheduleMaintenancePK not in  (select ScheduleMaintenanceFK from tasklist)
																		and s.ScheduledServiceDate <= current_date() and s.LocationFK='$locationFK' and a.visiblestatus='on'";
} else if ($freq == 'week') {
    $sql = "select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
																		inner join artefact a
																		on s.ArtefactCode = a.ArtefactCode
																		where  s.ScheduleMaintenancePK not in (select ScheduleMaintenanceFK from tasklist)
																		and s.ScheduledServiceDate <= DATE_ADD(current_date(),INTERVAL 7 DAY) and s.LocationFK='$locationFK' and a.visiblestatus='on'";
} else if ($freq == 'month') {
    $sql = "select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
																		inner join artefact a
																		on s.ArtefactCode = a.ArtefactCode
																		where  s.ScheduleMaintenancePK not in  (select ScheduleMaintenanceFK from tasklist)
																		and s.ScheduledServiceDate <= DATE_ADD(current_date(),INTERVAL 30 DAY) and s.LocationFK='$locationFK' and a.visiblestatus='on'";
} else {

    $sql = "select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
																		inner join artefact a
																		on s.ArtefactCode = a.ArtefactCode
																		where  s.ScheduleMaintenancePK not in  (select ScheduleMaintenanceFK from tasklist)
																		and s.ScheduledServiceDate <= DATE_ADD(current_date(),INTERVAL 365 DAY) and s.LocationFK='$locationFK' and a.visiblestatus='on'";
}


if ($result = $db->setQuery($sql)) {

    if ($result->num_rows > 0) {

        echo "<table class='table table-hover dataTable no-footer' id='STable'>
								
									<thead>
										<tr>
											<th>Artefact Name</th>
											<th>Schedule Date</th>
											<th>Action</th>
										</tr>
									</thead>
									
									<tbody>";
        while ($row = $result->fetch_assoc()) {

            $url = "../conditionalReport/conditionalReport.php?artefactCode=" . $row['ArtefactCode'] . "&key=" . $row['ScheduleMaintenancePK'];

            echo "<tr>";
            echo "<td>" . $row['ArtefactName'] . "</td>";
            echo "<td>" . date('d-M-Y', strtotime($row['ScheduledServiceDate'])) . "</td>";
            if (date('d-M-Y', strtotime($row['ScheduledServiceDate'])) == date('d-M-Y')) {
                echo "<td>";
                echo "<a href='" . $url . "' class='btn btn-success' title='Make a conditional Report'>Perform Task</a>";
                echo "</td>";
            } else {
                echo "<td>";
                echo "<button class='btn btn-success' title='Make a conditional Report' disabled>Perform Task</a>";
                echo "</td>";
            }
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    }

}
?>