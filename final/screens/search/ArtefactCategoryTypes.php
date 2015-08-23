<?php

include_once '../common/DatabaseConnection.php';
$db=new DatabaseConnection();
$conn=$db->createConnection();

echo <<<endl
				
					   <div class="col-md-4">
						<div class='col-md-4'>	
						<label style='margin-top: 10px;'>Location &nbsp;&nbsp;&nbsp;&nbsp;</label></div>
					<span>
				<div class='col-md-8'>
				 <select id='LocationList' class='selectDropdown form-control'>
endl;

//Query to Display the Locations
$query="select Description from archivelocation;";
if($result=mysqli_query($conn, $query)) {
	echo "<option value=''> Select Location </option>";
	if($result->num_rows >0){
		while($row = $result->fetch_assoc()){
			echo "<option value=".$row['Description'].">".$row['Description']."</option>";

		}
	}

}
echo <<<endl

			</select>
		<span></span>
		</span>
		</div>
	</div>
		<div class="col-md-4">
			<div class='col-md-5'>	
			 <label style='margin-top: 10px;'>Artefact-Type &nbsp;&nbsp;&nbsp;&nbsp;</label></div>
endl;
						

	//Query to get ArtefactTypeCode and Description of Parent
	$query="Select ArtefactTypeCode, ArtefactTypeDescription
		from artefacttype
		Where ArtefactTypePID is NULL;";
	
	$result=$db->setQuery($query);
	echo "<div class='col-md-7'>	<select class='selectDropdown form-control' id='categoryList' onchange='getAttributes(this.value)'>";
	echo "<option value=''> Select Artefact Type </option>";
	if($result->num_rows >0){
		while($row = $result->fetch_assoc()){
			echo "<option value=".$row['ArtefactTypeCode'].">".$row['ArtefactTypeDescription']."</option>";
				
		}
	}
	else
		echo "Sorry No Categories";
	
	echo <<<endl
	</div>
			</select>
endl;
	
$db->closeConnection();
?>