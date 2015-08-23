<?php
include_once '../common/DatabaseConnection.php';
session_start();

$_SESSION['type'] = $_GET['type'];

if (isset($_GET['artefactCode'])) {
    $artefactCode = $_GET['artefactCode'];
    $_SESSION['seesionSelectedCode'] = $artefactCode;

}
if (isset($_GET['artefactTitle']))
    $artefactTitle = $_GET['artefactTitle'];


$_SESSION['seesionSelectedtype'] = $_GET['type'];
$maintainFull = $_GET['full'];

unset($_SESSION['maintainStatus']);

$db = new DatabaseConnection();
$db->createConnection();

if ($maintainFull == 'NO') {
    $sql = "select * from maintenancecycle where ArtefactTypeCode='" . $_GET['type'] . "' and ArtefactCode='$artefactCode'";
    $result = $db->setQuery($sql);

    ?>

    <div class='col-md-12 '>
        <span id='saveStatus' style='height: 200px; width: 100%; margin-top: 10%;'></span>
    </div>


    <div id='duration' class='duration col-md-12 marginT10  border-top'>

    <?php
    if (isset($result)) {
        if ($result->num_rows > 0) {
            //update

            $_SESSION['maintainStatus'] = 'update';
            while ($row = $result->fetch_assoc()) {
                ?>

                <!--
                    Display the grid here
                    each grid represents each maintenance


                -->


                <div class="item col-md-4 boxesFlat" style="margin-top: 10px;">
                    <div class="top-row">
                        <div class="row">
                            <div class="col-md-6"><b>StartDate</b></div>
                            <div class="col-md-6">
                                <label><?php echo date('d-m-Y', strtotime($row['CurrentDate'])) ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><b>End Date</b></div>
                            <div class="col-md-6"><label><?php echo date('d-m-Y', strtotime($row['EndDate'])) ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><b>Type</b></div>
                            <div class="col-md-6"><label>
                                    <?php
                                    if ($row['Unit'] == 'NULL') echo "Sperodic";
                                    else echo "Periodic";
                                    ?>
                                </label>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <button class="removeSchedule btnFlat btn-danger removeButton" id="<?php echo $row['MaintenanceCyclePK']; ?>" onclick="deleteSchedule(this.id)">Remove</button>
                    </div>


                </div>


                <!--

    <div class='col-md-4 padding0 marginT10'>
        <div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label></div>
        <div class='col-md-7 padding0'><input type='text' name='currentDate' class='form-control' id='currentDate'
                                              value='<?php echo date('d-m-Y') ?>' readonly/></div>
    </div>

    <div class='col-md-4 marginT10 duration'>

        <div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
        <div class='col-md-7 padding0 input-append date' id='dd1'><input type='text' name='endDate' id='endDate'
                                                                         class='form-control endDate' readonly
                                                                         value="<?php echo date('d-m-Y', strtotime($row['EndDate'])) ?>"/>
            <span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span></div>

    </div>

    <div class='col-md-12 marginT10 duration'>
        <div class='col-md-4 padding0 marginT10'>
            <div class='col-md-4 padding5 paddingT5 text-right'><label>Frequency</label></div>
            <div class='col-md-7 padding0'><input type='number' min="1" name='nextFreqencyValue'
                                                  value="<?php echo $row['Frequency'] ?>" id='nextFrequency'
                                                  class='form-control'/></div>
        </div>
        <div class='col-md-4 padding0 marginT10'>
            <div class='col-md-4 padding5 paddingT5 text-right'><label>Select</label></div>
            <div class='col-md-7 padding0'>
                <select name='units' onchange='calculateValue(this)' id='units' style=' ' class='form-control' required>
                    <option value=''>Select Option</option>
                    <option value='Week' <?php if ($row['Unit'] == 'Week') {
                    echo ' selected="selected"';
                } ?>>Week
                    </option>
                    <option value='Month' <?php if ($row['Unit'] == 'Month') {
                    echo ' selected="selected"';
                } ?>>Month
                    </option>
                    <option value='Year' <?php if ($row['Unit'] == 'Year') {
                    echo ' selected="selected"';
                } ?>>Year
                    </option>
                </select>
            </div>
        </div>

        <div class='col-md-4 padding0 marginT10'>
            <div class='col-md-4 padding5 paddingT5 text-right'><label>Next Schedule Date</label></div>
            <div class='col-md-7 padding0'><input type='text' name='nextDate' id='nextDate' readonly
                                                  class='form-control'
                                                  value="<?php echo date('d-m-Y', strtotime($row['NextServiceDate'])) ?>"/></td>
            </div>

        </div>

        <div class='col-md-12'>
            <div id='WeekMonth'></div>
        </div>

        <div class="col-md-12 border-top maintenance-btm-ctl marginT10 text-center">
            <input type="button" value="View Scheduled Maintenance" class="btn btn-default btnviewschedule  "
                   onclick="viewMaintainence()"/>
            <input type="button" value="Perform Tasks" class="btn btn-default btnperform"/>
            <input type="button" value="Save" id="<?php echo $row['MaintenanceCyclePK']; ?>"
                   class="btn btn-default btnsave" onclick="saveData(this.id)"/>
            <input type="button" value="Clear" class="btn btn-default btnclr" onclick="clearValue()"/>
        </div>

-->
            <?php
            }
        } else {
            $_SESSION['maintainStatus'] = 'new';
            ?>

           <label>Sorry No Schedule for this artefact...</label>
        <?php }
    }
}
?>