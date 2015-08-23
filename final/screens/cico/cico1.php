<?php
define("PAGENAME", "Check In/Out");
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
          HREF="images/logo.png">

    <title>Global Archive</title>
    <!-- Bootstrap -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">


    <link href="../../css/custom.css" rel="stylesheet"/>
    <link href="../../css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="../../js/jquery-ui.css">
    <link rel="stylesheet" href="../../css/pace.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <script data-pace-options='{ "ajax": true }' src="../../js/pace.js"></script>
    <script src="../../js/layout.js"></script>
    <script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.slimscroll.js"></script>

    <script type="text/javascript" src="../../js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../js/dataTables.bootstrap.js"></script>

    <!-- Switches -->
    <script type="text/javascript" src="../../js/bootstrap-switch.js"></script>
    <link rel="stylesheet" href="../../css/bootstrap-switch.css">

    <link rel="stylesheet" href="../../css/jquery.growl.css">
    <script src="../../js/jquery.growl.js"></script>

    <style>
        .searchbg {
            background: #8bc2cb;
        }

        .searchIcon {
            color: #fff;
        }

        #datatable2_filter > label > div > input {
            width: 150px;
        }
    </style>
    <script src="../../js/artefact/cico.js">


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
                            <div class='col-md-12 border-low'>
                                <div class="col-md-3 text-left  heading-Bg">
                                    <i class="fa fa-check-square-o fa-2x heading-Bg"></i> &nbsp;<span
                                        style="font-size: 22px;">Check In / Check Out</span><br>
                                </div>

                                <div class="col-md-9">
                                    <div class="col-md-8">
                                        <div id='ArtefactTypeinAdd'></div>
                                        <br/>
                                    </div>
                                    <div class="col-md-4 maringT10">
                                        <input type="checkbox" name="checkInOut" id='checkInOut' checked>
                                    </div>
                                </div>
                            </div>


                            <div class='col-md-12 marginT10'>
                                <div id='checkoutinlist' class='checkoutinlist'></div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class='checkFooter'>

                </div>

                <div class="dataTables_info" id="datatable1_info" role="status" aria-live="polite"></div>
                <div class="col-sm-12" id="pagefooter">
                    <p>&copy; 2015 SRCM. All Rights Reserved.</p>
                </div>
            </div>
        </div>

</body>

</html>