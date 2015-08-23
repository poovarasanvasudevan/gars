<?php

session_start();
include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$type = $_GET['type'];
$sql = "select c.artefactcode,a.ArtefactName,c.CheckoutDate,c.purpose,c.CICOStatus,c.remarks from artefactcico c
											inner join artefact a
											on c.artefactcode = a.ArtefactCode
											where c.ArtefactTypeCode ='$type'
											and c.CICOStatus='open' and
											c.userfk = '$_SESSION[userPK]'";


//echo $sql;
$result = $db->setQuery($sql);
$resultArray = array();
if (isset($result)) {
    if ($result->num_rows > 0) {
        echo "<form id='checkInForm'>";
        echo "<table id='datatable2' class='table table-striped table-hover dataTable no-footer clearfix'>";
        echo "<thead><tr  role='row' style='background-color:#8bc2cb;'><th>#</th><th>Artefact Name</th><th>Checkout Date</th><th>Purpose</th><th>Remarks</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            ?>

            <tr role='row'>
                <td><input type='checkbox' name='artefactCode' value='<?php echo $row['artefactcode'] ?>' class='cbox'
                           onchange='CBChange(this)'/></td>
                <td><?php echo $row['ArtefactName'] ?></td>
                <td><?php echo date('d-M-Y', strtotime($row['CheckoutDate'])) ?></td>
                <td><textarea rows='2' cols='20' name="<?php echo $row['artefactcode'] ?>_purpose" class='form-control'
                              id='purpose'><?php echo $row['purpose'] ?></textarea></td>

                <td><textarea rows='2' cols='20' name="<?php echo $row['artefactcode'] ?>_remarks" class='form-control'
                              id='remarks'><?php echo $row['remarks'] ?></textarea></td>
            </tr>
        <?php
        }
        echo "</tbody></table>";
        echo " <div class='modal-footer'>
							          <a href='cico1.php' class='btn btn-danger closep'>Cancel</a>
							          <input type='reset' class='btn btn-warning' value='Reset' />
							          <input type='submit' id='checkin' class='btn btn-success' value='Check In' />
							 </div>";
        echo "</form>";
    } else {

        echo "<label>Sorry No Artefact in this Artefact Type</label>";
    }
}

?>
