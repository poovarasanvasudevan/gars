<?php
session_start();
include '../common/DatabaseConnection.php';
if (!isset ($_SESSION ['artefactUser'])) {
    header("Location: index.php");
}

$artefactCode = $_GET ['artefactCode'];
$scheduledMaintainanceKey = $_GET ['key'];
$user = $_SESSION ['artefactUser'];

$currentDate = date("d/m/Y");
$scheduledMaintainancedate;
$overrideMaintainancedate;

$db = new DatabaseConnection ();
$db->createConnection();

$pages = $db->getPages($_SESSION ['userPK']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="images/logo.png"/>

    <LINK REL="SHORTCUT ICON" HREF="images/logo.png">

    <title>Global Archive</title>
    <!-- Bootstrap -->

    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../css/custom.css" rel="stylesheet"/>
    <link href="../../css/jquery.dataTables.min.css"/>
    <script src="../../js/layout.js"></script>
    <script src="../../js/jquery.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.dataTables.min.js">
    </script>
    <link rel = "stylesheet" href= "../../css/font-awesome.min.css" />
        <!-- Switches -->
    <script type = "text/javascript" src = "../../js/bootstrap-switch.js" ></script>
    <link rel="stylesheet" href="../../css/bootstrap-switch.css">


    <script type="text/javascript">
        $(function () {
            //$('#nextMaintainanceDate').datepicker();
            $('[data-toggle="tooltip"]').tooltip();


            $('input[type="checkbox"]').bootstrapSwitch({
                onText: 'Yes',
                offText: 'No',
                size: 'small'
            });
            $('#conditionalForm').submit(function (ev)
            {
                // Get all the forms elements and their values in one step
                var values = $(this).serialize();
                var scheduledKey = getUrlParameter('key');
                var artefactCode = getUrlParameter('artefactCode');
                var urlProcess = values + '&artefactCode=' + artefactCode + '&scheduledKey=' + scheduledKey;

                if (values == "") {
                    return false;
                }

                $.ajax({
                    url: "saveReport.php",
                    data: urlProcess,
                    success: function (data) {
                        if (data == 'success')
                        {
                            $.growl.notice({message: "Report saved succesfully..!", size: 'large'});
                            window.location="../dashboard/dashboard.php";
                        } else
                        {
                            $.growl.error({message: "failed to save report..!", size: 'large'});
                            //$('#status').html("<div class='alert alert-danger' role='alert'>" + data + "</div>");
                        }
                    }
                });
                //alert(urlProcess);
                $("#conditionalForm").trigger('reset');
                ev.preventDefault();
            });
        });


        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++) {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam) {
                    return sParameterName[1];
                }
            }
        }

        var overrideStatus = false;
        function disablefield() {
            if (document.getElementById('scheduled').checked == 1) {
                if (document.getElementById('scheduled').value == 'no') {
                    overrideStatus = false;
                    document.getElementById('nextMaintainanceDate').disabled = 'disabled';
                    document.getElementById('nextMaintainanceDate').value = '';
                }
            }
            else {
                overrideStatus = true;
                document.getElementById('nextMaintainanceDate').disabled = '';
            }
        }
    </script>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <!--<div class="col-sm-1 col-xs-2" style="padding-right: 0px;">
                        <img width="89" height="89" src="images/logo-ga.png" alt="Global Arichve">
                    </div>-->
                <div class="col-md-12">
                    <div class="logintxt">Global Archives</div>
                </div>
            </div>
            <div class="col-md-5 padding0">
                <div class="col-md-12 text-right logout ">
                    <a href="logout.php">Logout</a>
                </div>
                <div class="col-md-12 tp-login-info">
                    <div class="col-md-7 padding0">
                        <span>Welcome </span><span class="tp-username"><?php echo $_SESSION['fullName']; ?></span>
                    </div>
                    <div class="col-md-5 text-right padding0">
                        <span>Location : </span><span><?php echo $_SESSION['userLocationDesc'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <?php

                    for ($i = 0; $i < sizeof($pages); $i++) {

                        if ($pages[$i]['menutitle'] != '') {

                                echo "<li class=''><a href='" . PAGE_DIR . "" . $pages[$i]['dir'] . "/" . $pages[$i]['url'] . "' title='" . $pages[$i]['menutitle'] . "'>" . $pages[$i]['menutitle'] . "</a></li>";


                        }
                    }
                    ?>
                </ul>
                <div class="tab-content">


                    <div class='tile col-md-12'>

                        <div class='col-md-12 border-low'>


                            <div class="col-md-3 text-left heading-Bg">
                                <i class="fa fa-pencil-square-o fa-2x heading-Bg"></i> &nbsp; <span
                                    style="font-size: 22px;">Conditional report</span><br>
                            </div>


                            <div class="col-md-9 form-group" style="">

                                <?php
                                $query = "SELECT * FROM maintenancecycle where ArtefactCode ='$artefactCode'";
                                $result1 = $db->setQuery($query);

                                if ($result1->num_rows > 0) {
                                    while ($rows = $result1->fetch_assoc()) {
                                        $scheduledMaintainancedate = $rows ['NextServiceDate'];
                                    }
                                } else {

                                    $scheduledMaintainancedate = "";
                                }

                                echo "
														<div class='col-md-4'>
															<div class='col-md-5 text-right marginT10'>	
																<label for='LocationDropdown'>
																	Location
																</label>
															</div>
															<div class='col-md-7'>	
													    <select id='LocationList' class='selectDropdown form-control'>
												";

                                $query = "select LocationPk,Code,Description from ArchiveLocation;";
                                if ($result = $db->setQuery($query)) {
                                    echo "<option value=''> Select Location </option>";
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <option value='<?php echo $row['Code'] ?>'
                                                <?php if ($row['LocationPk'] == $_SESSION['userLoc']) {
                                                    echo ' selected="selected"';
                                                } ?>><?php echo $row['Description'] ?></option>
                                        <?php
                                        }
                                    }
                                }
                                echo "
												</select></div></div>
													<div class='col-md-3'>	
														<div class='col-md-4 text-right marginT10'>	<label> User </label></div>  
														<div class='col-md-8'>	<input type='text' name='username' id='username' value='$user' class='form-control' readonly/></div>
													</div>
													<div class='col-md-5'>
														<div class='col-md-5 text-right marginT10'><label>Current Date </label></div>  
														<div class='col-md-7'><input type='text' name='currentdate' class='form-control' id='currentDate' value='$currentDate' readonly /></div>
													</div>
												<br/>
												</div>";

                                ?>

                            </div>

                            <div class="col-md-12 datatables">
                                <form id='conditionalForm'>
                                    <div>
                                        <header>
                                            <h4>
                                                <label>Artefact Name
                                                    : <?php echo $db->getArtefactNamePK($artefactCode); ?></label>
                                            </h4>
                                        </header>
                                        <div class="inner-spacer">
                                            <div id='status'></div>
                                            <table class="table table-hover" id="datatable1">
                                                <thead>
                                                <tr>
                                                    <th class='col-md-6'>Check List</th>
                                                    <th class='col-md-6'>Reports</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <?php
                                                    $artefactTypeCode;
                                                    $res = $db->setQuery("select ArtefactTypeCode from artefact where ArtefactCode='$artefactCode'");
                                                    if ($res->num_rows > 0) {
                                                        while ($row1 = $res->fetch_assoc()) {
                                                            $artefactTypeCode = $row1 ['ArtefactTypeCode'];
                                                        }
                                                    }
                                                    if ($artefactTypeCode == 'VTrack')
                                                        $artefactTypeCode = 'Video';

                                                    $res1 = $db->setQuery("SELECT * FROM checklist where ArtefactTypeCode='$artefactTypeCode'");
                                                    if ($res1->num_rows > 0) {

                                                        while ($row2 = $res1->fetch_assoc()) {

                                                            if ($row2 ['CheckListItem'] != 'Remarks') {
                                                                echo "<tr>
																				<td class='col-md-6'>" . $row2 ['CheckListItem'] . "</td>
																				<td class='col-md-3'><select name=" . $row2 ['SequenceNo'] . " class='form-control' required title='please select " . $row2 ['CheckListItem'] . "'>
																						<option value=''>Select option..</option>
																						<option value='yes'>Yes</option>
																						<option value='no'>No</option>
																				</select></td></tr>";
                                                            } else {
                                                                echo "<tr>
																				<td class='col-md-6'>" . $row2 ['CheckListItem'] . "</td>
																				<td class='col-md-6'><textarea rows='3' cols='40' class='form-control' name=" . $row2 ['SequenceNo'] . " id='remarks' placeholder='please enter remarks..' required></textarea></td></tr>";
                                                            }
                                                        }

                                                        echo "</table>";
                                                    }

                                                    ?>
                                                </tr>
                                                </tbody>
                                            </table>


                                            <p class="text-center clearfix">
                                                <input type="submit" id='saveButton' name='saveButton'
                                                       class="btn btn-olive margin5" onclick='' value='Save'></input>
                                                <input type="reset" id='resetButton' name='resetButton'
                                                       class="btn btn-orange margin5" onClick='' value='Reset'></input>
                                            </p>

                                        </div>

                                </form>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="col-sm-12" id="pagefooter">
                    <p>&copy; 2015 SRCM. All Rights Reserved.</p>
                </div>
            </div>


        </div>

</body>
</html>