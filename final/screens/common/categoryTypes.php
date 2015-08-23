<?php

include_once 'DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->createConnection();
echo " <div class='col-md-12'>
						<div class='col-md-5'>	<label id='typeLabel' for='categoryListinAdd' style='margin-top: 10px;'> Artefact Type  &nbsp;&nbsp;</label></div>";
//Query to get ArtefactTypeCode and Description of Parent
$query = "Select ArtefactTypeCode, ArtefactTypeDescription
		from ArtefactType
		Where ArtefactTypePID is NULL;";

$result = $db->setQuery($query);

if ($result->num_rows > 0) {
    echo "<div class='col-md-7'><select id='typeSelect' class='selectDropdown form-control' id='categoryListinAdd' onchange='getTree(this.value)'>";
    echo "<option value=''> Select Artefact Type </option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value=" . $row['ArtefactTypeCode'] . ">" . $row['ArtefactTypeDescription'] . "</option>";
    }
    echo "</select></div>";
} else
    echo "Sorry No Categories";
?>