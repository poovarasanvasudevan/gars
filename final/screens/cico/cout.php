<?php

include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$type = $_GET['type'];
$sql = "select
		b.artefactcode,
	    a.artefactname from 
	    " . $type . "Attributes b
	    inner join artefact a
	    on a.ArtefactCode=b.ArtefactCode
	    and b.artefactCode not in (select c.artefactcode from artefactcico c) and  b.visiblestatus='on' ";

//echo $sql;
$result = $db->setQuery($sql);
$resultArray = array();

if (isset($result)) {
    if ($result->num_rows > 0) {
        echo "<form id='checkForm'>";
        echo "<table id='datatable2' class='table table-striped table-hover dataTable no-footer clearfix'>";
        echo "<thead><tr  role='row' style='background-color:#8bc2cb;'><th>#</th><th>Artefact Name</th><th>Purpose</th><th>Remarks</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "";
            echo "<tr  role='row'>
						<td><input type='checkbox' name='artefactCode' value='" . $row['artefactcode'] . "' class='cbox' onchange='CBChange(this)'/></td>
						<td>" . $row['artefactname'] . "</td>
						<td><textarea rows='2' cols='20' name='" . $row['artefactcode'] . "_purpose' class='form-control' id='purpose'></textarea></td>
						<td><textarea rows='2' cols='20' name='" . $row['artefactcode'] . "_remarks' class='form-control' id='remarks'></textarea></td>
					</tr>";
        }
        echo "</tbody></table>";
        echo " <div class='modal-footer'>
							          <a href='cico1.php' class='btn btn-danger closep'>Cancel</a>
							          <input type='reset' class='btn btn-warning' value='Reset' />
							          <input type='submit' id='checkin' class='btn btn-success' value='Check out' />
							 </div>";
        echo "</form>";
    } else {

        echo "<label>Sorry No Artefact in this Artefact Type</label>";
    }

}


?>
