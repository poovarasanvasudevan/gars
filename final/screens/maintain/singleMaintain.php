<?php
include_once 'DatabaseConnection.php';
session_start();

$_SESSION['type']=$_GET['type'];
$artefactCode = $_GET['artefactCode'];
$artefactTitle = $_GET['artefactTitle'];


$_SESSION['seesionSelectedCode'] = $artefactCode;
$_SESSION['seesionSelectedtype'] = $_GET['type'];


unset($_SESSION['maintainStatus']);

$db=new DatabaseConnection();
$db->createConnection();


$sql="select * from maintenancecycle where ArtefactTypeCode='".$_GET['type']."' and ArtefactCode='$artefactCode'";
$result=$db->setQuery($sql);

// /echo $sql;

?>

		<div class='col-md-12 '>
			<span id='saveStatus' style='height: 200px; width: 100%; margin-top: 10%;'></span>
		</div>
		
		
		<div id='duration' class='duration col-md-12 marginT10  border-top'>
			
<?php 		
if(isset($result)) {
if($result->num_rows>0) {
	//update
	
	$_SESSION['maintainStatus'] = 'update';
	while ($row = $result->fetch_assoc()) {
?>


					<div class='col-md-6 padding0 marginT10'>	
							<div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label></div>
						    <div class='col-md-7 padding0'><input type='text' name='currentDate' class='form-control' id='currentDate' value='<?php echo date('d-m-Y') ?>' readonly/></div>
						</div>
					
					<div class='col-md-6 marginT10 duration'> 

							<div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
							<div class='col-md-7 padding0 input-append date' id='dd1'><input type='text' name='endDate' id='sendDate' class='form-control sendDate' readonly value="<?php echo date('d-m-Y',strtotime($row['EndDate'])) ?>"/>  <span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span></div>			

					</div>
			
					
					 
					 <div class='col-md-12'>					 
					 	 <div id='WeekMonth'></div>
					 </div>
				
					 <div class="col-md-12 border-top maintenance-btm-ctl marginT10 text-center" >
                            <input type="button" value="View Scheduled Maintenance" class="btn btn-default btnviewschedule  " onclick="viewMaintainence()"/>
                            <input type="button" value="Perform Tasks" class="btn btn-default btnperform" />
                            <input type="button" value="Save" class="btn btn-default btnsave" id="<?php echo $row['MaintenanceCyclePK'];?>" onclick="saveSingleData(this.id)"/>
                            <input type="button" value="Clear" class="btn btn-default btnclr" onclick="clearValue()" />
                     </div>
				
				    
		<?php
	}
} else {
	$_SESSION['maintainStatus'] = 'new';
	?>

						<div class='col-md-6 padding0 marginT10'>	
							<div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label></div>
						    <div class='col-md-7 padding0 input-append date' id='dd1'><input type='text' name='currentDate' class='form-control' id='currentDate' value='<?php echo date('d-m-Y') ?>' readonly/></div>
						</div>
					
					<div class='col-md-6 marginT10 duration'> 

							<div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
							<div class='col-md-7 padding0'><input type='text' name='endDate' id='sendDate' class='form-control sendDate' readonly/><span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span></div>			

					</div>
			
					
					
					 <div class='col-md-12 marginT10'>					 
					 	 <div id='WeekMonth' class="marginT10"></div>
					 </div>
					 <div class="col-md-12 border-top maintenance-btm-ctl marginT10 text-center" >
                            <input type="button" value="View Scheduled Maintenance" class="btn btn-default btnviewschedule  " onclick="viewMaintainence()"/>
                            <input type="button" value="Perform Tasks" class="btn btn-default btnperform" />
                            <input type="button" value="Save" class="btn btn-default btnsave" id="" onclick="saveSingleData(this.id)"/>
                            <input type="button" value="Clear" class="btn btn-default btnclr" onclick="clearValue()" />
                     </div>
				   						
	<?php } }?>	
	
