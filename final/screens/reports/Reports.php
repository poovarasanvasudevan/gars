<?php

define("PAGENAME", "Report");

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
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/dashboard.css" rel="stylesheet"/>
    <link href="../../css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="../../css/font-awesome.min.css">

    <script src="../../js/layout.js"></script>
    <script src="../../js/jquery-1.10.2.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.bpopup.min.js"></script>
    <script src="../../js/jquery.dataTables.min.js"></script>
    <script src="../../jS/bootbox.min.js"></script>
    <script type="text/javascript" src="../../js/dataTables.bootstrap.js"></script>

    <!-- Pdf Scripts -->
    <script type="text/javascript" src="../../js/jspdf.js"></script>
    <script type="text/javascript" src="../../js/libs/FileSaver.js"></script>
    <script type="text/javascript" src="../../js/libs/standard_fonts_metrics.js"></script>
    <script type="text/javascript" src="../../js/libs/split_text_to_size.js"></script>
    <script type="text/javascript" src="../../js/libs/from_html.js"></script>
    <script type="text/javascript" src="../../js/libs/jspdf.debug.js"></script>

    <script type="text/javascript" src="../../js/libs/dep/canvas.js"></script>
    <script type="text/javascript" src="../../js/libs/dep/context2d.js"></script>
    <script type="text/javascript" src="../../js/libs/dep/png_support.js"></script>
    <script type="text/javascript" src="../../js/libs/dep/html2canvas.js"></script>

    <script type="text/javascript" src="../../js/libs/png_support/png.js"></script>
    <script type="text/javascript" src="../../js/libs/png_support/zlib.js"></script>

    <script type="text/javascript" src="../../js/bootstrap-datepicker.js"></script>

    <!-- Table to json -->
    <script type="text/javascript" src="../../js/jspdf.plugin.autotable.js"></script>

    <link rel="stylesheet" href="../../css/jquery.growl.css">
    <script src="../../js/jquery.growl.js"></script>
    <style>
        #tree {
            height: 600px;
            overflow: auto;
        }

        #AttributeListDiv {
            height: 650px;
        }

        .searchbg {
            background: #8bc2cb;
        }

        .searchIcon {
            color: #fff;
        }

        .dataTables_filter {
            width: 100%;
            display: inline;
        }

        #fromDate, #toDate {
            cursor: pointer;
        }

        .maintenance1 {

            height: 526px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .active {
            background-color: #8bc2cb !important;
        }
    </style>


    <link rel="stylesheet" href="css/jquery.growl.css">
    <script src="js/jquery.growl.js"></script>

    <script src="../../js/artefact/reports.js">

    </script>
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
                            <div class="col-md-4 text-left heading-Bg">
                                <i class="fa fa-pencil fa-2x heading-Bg"></i> <span style="font-size: 22px;">&nbsp;Reports</span><br>
                            </div>

                        </div>


                        <div class="col-md-3  marginT10" style="height : 70%;">

                            <div>
                                <div class="list-group border-full">
                                    <a class="list-group-item" href="#" id='cico'><i class="fa fa-home fa-fw"></i>&nbsp;
                                        Check In /Check Out Report</a>
                                    <a class="list-group-item" href="#" id='creport'><i class="fa fa-book fa-fw"></i>&nbsp;
                                        Conditional Report</a>
                                    <a class="list-group-item" href="#" id='dreport'><i class="fa fa-pencil fa-fw"></i>&nbsp;
                                        Artefact delta report</a>
                                    <a class="list-group-item" href="#" id='ctreport'><i class="fa fa-cog fa-fw"></i>&nbsp;
                                        Catalog reports</a>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-9 maintenance1 marginT10" id='AttributeListDiv'>
                            <div class='col-md-12' id='cicoReport'>

                                <div class='col-md-12' style="border-bottom: 1px solid #ccc;text-align: center;">
                                    <p><label><h4>Check In /out Report</h4></label></p>
                                </div>
                                <div class='col-md-4 marginT10'>
                                    <div class='col-md-2'><label class='marginT10'>From &nbsp</label></div>
                                    <div class='col-md-10'><input type='text' name='fromDate' id='fromDate' required
                                                                  class='form-control'/> <span class="add-on"><i
                                                class="glyphicon glyphicon-calendar"></i></span></div>
                                </div>
                                <div class='col-md-4 marginT10'>
                                    <div class='col-md-2'><label class='marginT10'>To &nbsp </label></div>
                                    <div class='col-md-10'><input type='text' name='toDate' id='toDate' required
                                                                  class='form-control'/> <span class="add-on"><i
                                                class="glyphicon glyphicon-calendar"></i></span></div>
                                </div>
                                <div class='col-md-4 marginT10'>
                                    <div class='col-md-2'><label class='marginT10'>Type </label></div>
                                    <div class='col-md-10'>
                                        <select name='cicotype' id='cicotype' class='form-control'>
                                            <option value="">Select type</option>
                                            <option value="checkin">Check In</option>
                                            <option value="checkout">Check Out</option>
                                        </select></div>
                                </div>


                            </div>
                            <div class='marginT10' id='result'>

                            </div>

                        </div>

                    </div>


                </div>

                <div class="col-sm-12" id="pagefooter">
                    <p>
                        &copy; 2015 SRCM. All Rights Reserved.</p>
                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/bootstrap.min.js"></script>


</body>
</html>
