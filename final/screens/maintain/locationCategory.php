<?php

include_once '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->createConnection();

echo <<<endl
				<div class='col-md-6'>
					<div class='col-md-4'><label for='LocationDropdown text-right' style='margin-top: 10px;'>
						Location
					</label></div><div class='col-md-8'>
				   <select id='LocationList' class='selectDropdown form-control'>
endl;

//Query to Display the Locations
$query = "select Description from ArchiveLocation;";
if ($result = mysqli_query($conn, $query)) {
    echo "<option value=''> Select Location </option>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['Description'] . ">" . $row['Description'] . "</option>";

        }
    }

}
echo <<<endl
			</select></div>
			</div>
			<div class='col-md-6'><div class='col-md-5'>
			<label for='CategoryTypeDropdown' style='margin-top: 10px;'>
				Artefact Type
			</label></div>
		
endl;


//Query to get ArtefactTypeCode and Description of Parent
$query = "Select ArtefactTypeCode, ArtefactTypeDescription
		from ArtefactType
		Where ArtefactTypePID is NULL;";

$result = $db->setQuery($query);
echo "<div class='col-md-7'><select class='selectDropdown form-control' id='categoryList' onchange='getTree(this.value)'>";
echo "<option value=''> Artefact Type </option>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value=" . $row['ArtefactTypeCode'] . ">" . $row['ArtefactTypeDescription'] . "</option>";

    }
} else
    echo "Sorry No Categories";

echo <<<endl
			
			</select></div>
		</div>
endl;

$db->closeConnection();
?>