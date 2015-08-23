<?php

define("PAGENAME", "Maintenance");

session_start();
if (!isset($_SESSION['artefactUser'])) {
    header("Location: index.php");
}

include '../common/DatabaseConnection.php';
$db = new DatabaseConnection();
$db->createConnection();

$pages = $db->getPages($_SESSION['userPK']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="icon"
          type="image/png"
          href="images/logo.png"/>

    <LINK REL="SHORTCUT ICON"
          HREF="favicon.ico">

    <title>Global Archive</title>


    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/jquery-migrate-1.2.1.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui.min.js"></script>


    <!-- Bootstrap -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/bootstrap.min.js"></script>


    <link href="../../css/dashboard.css" rel="stylesheet"/>
    <link href="../../css/jquery.dataTables.min.css"/>
    <script src="../../js/layout.js"></script>


    <link rel="stylesheet" href="../../css/pace.css">
    <script data-pace-options='{ "ajax": true }' src="../../js/pace.js"></script>


    <link href="../../tree/skin-lion/ui.fancytree.css" rel="stylesheet" type="text/css">
    <script src="../../tree/src/jquery.fancytree.js" type="text/javascript"></script>
    <script src="../../tree/src/jquery.fancytree.edit.js" type="text/javascript"></script>
    <script src="../../tree/src/jquery.fancytree.filter.js" type="text/javascript"></script>

    <!-- Switches -->

    <link rel="stylesheet" href="../../css/jquery.datetimepicker.css">
    <script src="../../js/datepicker/jquery.datetimepicker.js"></script>

    <script src="../../js/bootbox.min.js"></script>

    <link rel="stylesheet" href="../../css/bootstrap-switch.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <script src="../../js/jquery.bpopup.min.js"></script>

    <style>
        #tree {
            height: 600px;
            overflow: auto;
        }

        #AttributeListDiv {
            height: 650px;
        }

        #endDate {
            cursor: pointer !important;
        }

    </style>


    <link rel="stylesheet" href="../../css/jquery.growl.css">
    <script src="../../js/jquery.growl.js"></script>


    <!-- Custom java Scripts -->
    <script type="text/javascript" src="../../js/artefact/maintain.js"></script>


    <!-- End of Custom Java Scripts -->
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
                    <div class="logintxt">
                        Global Archives
                    </div>
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
                    <?php for ($i = 0; $i < sizeof($pages); $i++) {

                        if ($pages[$i]['menutitle'] != '') {
                            if ($pages[$i]['menutitle'] == PAGENAME) {
                                echo "<li class='active'><a href='" . PAGE_DIR . "" . $pages[$i]['dir'] . "/" . $pages[$i]['url'] . "' title='" . $pages[$i]['menutitle'] . "'>" . $pages[$i]['menutitle'] . "</a></li>";
                            } else {

                                echo "<li><a href='" . PAGE_DIR . "" . $pages[$i]['dir'] . "/" . $pages[$i]['url'] . "' title='" . $pages[$i]['menutitle'] . "'>" . $pages[$i]['menutitle'] . "</a></li>";
                            }
                        }

                    } ?>
                </ul>
                <div class="tab-content">

                    <div class="col-md-12 tile" id="tile">

                        <div class='col-md-12 border-low '>
                            <div class="col-md-3 text-left heading-Bg">
                                <i class="fa fa-pencil fa-2x heading-Bg"></i> <span style="font-size: 22px;">&nbsp;Maintenance</span><br>
                            </div>

                            <div class="col-md-7">
                                <?php include 'locationCategory.php'; ?>
                            </div>

                            <div class='col-md-2'>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Full Schedule</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="addScheduleSelectMenu">
                                        <li><a href="#" id="fperiodic">Peridoic</a></li>
                                        <li><a href="#" id="fsperiodic">Sperodic</a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-3 container1 marginT10" style="height : 70%;">

                            <input type="text" name='search' id='ser' placeholder="search for artefact.."
                                   class='form-control'>
                            <!-- Add Tree Structure Here -->
                            <div id='tree' class='scrollbar style-1 container2'>


                            </div>
                        </div>
                        <div class="col-md-9 maintenance scrollbar style-1" id='AttributeListDiv'>
                            <div class='col-md-12' id='headerMaintain'>
                                <div class='col-md-6'><h4 id='aCode'></h4></div>
                                </hr>
                                <div class="col-md-6 marginT10">
                                    <div class="col-md-6 pull-right">
                                        <div class="btn-group">

                                            <button type="button" class="btn btn-primary">Add Schedule</button>
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="addScheduleSelectMenu">
                                                <li><a href="#" id="periodic">Peridoic</a></li>
                                                <li><a href="#" id="speriodic">Sperodic</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div id='duration'></div>


                            </div>
                            <div class='col-md-12'>
                                <span id='saveStatus' style='height: 200px; width: 100%; margin-top: 10%;'></span>
                            </div>


                        </div>


                    </div>

                </div>
                <!-- /tabs -->
            </div>


            <div class="col-sm-12" id="pagefooter">
                <p>
                    &copy; 2015 SRCM. All Rights Reserved.</p>
            </div>
        </div>
    </div>

</div>


<div id="singleModel" style="display: none;">

    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header header-color">
                <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                style="color: white;"></span>&nbsp;&nbsp;
                    Schedule</h4>
            </div>
            <div class="">

                <div id='singleBody' class='col-md-12'>
                    <div class='col-md-6 padding0 marginT10'>
                        <div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label>
                        </div>
                        <div class='col-md-7 padding0 input-append date' id='dd1'><input type='text'
                                                                                         name='newSingleCurrentDate'
                                                                                         class='form-control'
                                                                                         id='newSingleCurrentDate'
                                                                                         value='<?php echo date('d-m-Y') ?>'
                                                                                         readonly/>
                        </div>
                    </div>

                    <div class='col-md-6 marginT10 duration'>

                        <div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
                        <div class='col-md-7 padding0'><input type='text' name='newSingleEndDate'
                                                              id='newSingleEndDate'
                                                              class='form-control newSingleEndDate'/><span
                                class="add-on"><i
                                    class="glyphicon glyphicon-calendar"></i></span></div>

                    </div>

                    <div class='col-md-12 marginT10 duration'>
                        <div class='col-md-6 padding0 marginT10'>
                            <div class='col-md-4 padding5 paddingT5 text-right'><label>Frequency</label>
                            </div>
                            <div class='col-md-7 padding0'><input type='number'
                                                                  name='newSingleNextFreqencyValue'
                                                                  value='0'
                                                                  id='newSingleNextFrequency'
                                                                  class='form-control'/></div>
                        </div>
                        <div class='col-md-6 padding0 marginT10'>
                            <div class='col-md-4 padding5 paddingT5 text-right'><label>Select</label>
                            </div>
                            <div class='col-md-7 padding0'>
                                <select name='newSingleUnits' onchange='calculateValue(this)'
                                        id='newSingleUnits' style=' ' class='form-control'
                                        required>
                                    <option value=''>Select Option</option>
                                    <option value='Week'>Week</option>
                                    <option value='Month'>Month</option>
                                    <option value='Year'>Year</option>
                                </select>
                            </div>
                        </div>


                        <div class='col-md-12 marginT10'>
                            <div id='WeekMonth' class="marginT10"></div>
                        </div>

                    </div>

                </div>

            </div>
            <div class="modal-footer marginT10">

                <input type="button" value="Save" id="" class="btn btn-default btnsave"
                       id="saveSinglePeriodic" onclick="saveSingleArtefactPeriodicSchedule()"/>
                <input type="button" value="Close" class="btn btn-default btnclr closep"/>

            </div>
        </div>

    </div>

</div>


<!-- Single sperodic -->
<div id="singleSperodicModel" style="display: none;">

    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header header-color">
                <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                style="color: white;"></span>&nbsp;&nbsp;
                    Schedule</h4>
            </div>
            <div class="">

                <div id='singleSperodicBody' class='col-md-12'>

                    <div class='col-md-6 padding0 marginT10'>
                        <div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label>
                        </div>
                        <div class='col-md-7 padding0 input-append date' id='dd1'><input type='text'
                                                                                         name='newSingleCurrentDate1'
                                                                                         class='form-control'
                                                                                         id='newSingleCurrentDate1'
                                                                                         value='<?php echo date('d-m-Y') ?>'
                                                                                         readonly/>
                        </div>
                    </div>

                    <div class='col-md-6 marginT10 duration'>

                        <div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
                        <div class='col-md-7 padding0'><input type='text' name='newSingleEndDate1'
                                                              id='newSingleEndDate1'
                                                              class='form-control newSingleEndDate'/><span
                                class="add-on"><i
                                    class="glyphicon glyphicon-calendar"></i></span></div>

                    </div>


                </div>

            </div>
            <div class="modal-footer marginT10">

                <input type="button" value="Save" id="" class="btn btn-default btnsave"
                       id="saveSingleSperiodic" onclick="saveSingleArtefactSperiodicSchedule()"/>
                <input type="button" value="Close" class="btn btn-default btnclr closep"/>

            </div>
        </div>

    </div>

</div>



<!--Full schedule periodic-->

<div id="fullModel" style="display: none;">

    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header header-color">
                <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                style="color: white;"></span>&nbsp;&nbsp;
                    Schedule</h4>
            </div>
            <div class="">

                <div id='fullBody' class='col-md-12'>
                    <div class='col-md-6 padding0 marginT10'>
                        <div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label>
                        </div>
                        <div class='col-md-7 padding0 input-append date' id='dd1'><input type='text'
                                                                                         name='newfullCurrentDate'
                                                                                         class='form-control'
                                                                                         id='newfullCurrentDate'
                                                                                         value='<?php echo date('d-m-Y') ?>'
                                                                                         readonly/>
                        </div>
                    </div>

                    <div class='col-md-6 marginT10 duration'>

                        <div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
                        <div class='col-md-7 padding0'><input type='text' name='newfullEndDate'
                                                              id='newfullEndDate'
                                                              class='form-control newSingleEndDate'/><span
                                class="add-on"><i
                                    class="glyphicon glyphicon-calendar"></i></span></div>

                    </div>

                    <div class='col-md-12 marginT10 duration'>
                        <div class='col-md-6 padding0 marginT10'>
                            <div class='col-md-4 padding5 paddingT5 text-right'><label>Frequency</label>
                            </div>
                            <div class='col-md-7 padding0'><input type='number'
                                                                  name='newfullNextFreqencyValue'
                                                                  value='0'
                                                                  id='newfullNextFrequency'
                                                                  class='form-control'/></div>
                        </div>
                        <div class='col-md-6 padding0 marginT10'>
                            <div class='col-md-4 padding5 paddingT5 text-right'><label>Select</label>
                            </div>
                            <div class='col-md-7 padding0'>
                                <select name='newfullUnits' onchange='calculateValueFull(this)'
                                        id='newfullUnits' style=' ' class='form-control'
                                        required>
                                    <option value=''>Select Option</option>
                                    <option value='Week'>Week</option>
                                    <option value='Month'>Month</option>
                                    <option value='Year'>Year</option>
                                </select>
                            </div>
                        </div>


                        <div class='col-md-12 marginT10'>
                            <div id='FWeekMonth' class="marginT10"></div>
                        </div>

                    </div>

                </div>

            </div>
            <div class="modal-footer marginT10">

                <input type="button" value="Save" id="" class="btn btn-default btnsave"
                       id="saveFullPeriodic" onclick="saveFullData() "/>
                <input type="button" value="Close" class="btn btn-default btnclr closep"/>

            </div>
        </div>

    </div>

</div>


<!-- Sperodic -->
<!-- Single sperodic -->
<div id="fullSperodicModel" style="display: none;">

    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header header-color">
                <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                style="color: white;"></span>&nbsp;&nbsp;
                    Schedule</h4>
            </div>
            <div class="">

                <div id='singleSperodicBody' class='col-md-12'>

                    <div class='col-md-6 padding0 marginT10'>
                        <div class='col-md-4 padding5 paddingT5 text-right'><label>Start Date</label>
                        </div>
                        <div class='col-md-7 padding0 input-append date' id='dd1'><input type='text'
                                                                                         name='newSingleCurrentDate1'
                                                                                         class='form-control'
                                                                                         id='newfullCurrentDate1'
                                                                                         value='<?php echo date('d-m-Y') ?>'
                                                                                         readonly/>
                        </div>
                    </div>

                    <div class='col-md-6 marginT10 duration'>

                        <div class='col-md-4 padding5 paddingT5 text-right'><label>End Date<label></div>
                        <div class='col-md-7 padding0'><input type='text' name='newSingleEndDate1'
                                                              id='newfullEndDate1'
                                                              class='form-control newSingleEndDate'/><span
                                class="add-on"><i
                                    class="glyphicon glyphicon-calendar"></i></span></div>

                    </div>


                </div>

            </div>
            <div class="modal-footer marginT10">

                <input type="button" value="Save" id="" class="btn btn-default btnsave"
                       id="saveSingleSperiodic" onclick="saveSingleData()"/>
                <input type="button" value="Close" class="btn btn-default btnclr closep"/>

            </div>
        </div>

    </div>

</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

</body>
</html>
