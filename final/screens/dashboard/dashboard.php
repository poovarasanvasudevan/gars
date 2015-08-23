<?php
define("PAGENAME", "Home");
session_start();
include '../common/DatabaseConnection.php';


if (!isset($_SESSION['artefactUser'])) {
    header("Location: index.php");
}

$user = $_SESSION['artefactUser'];

$obj = new DatabaseConnection();
$conn = $obj->createConnection();

$fullName = "";
$location = "";
$locationFK = "";
$userPK = '';
$userRole = '';

$user1 = $obj->setQuery("select u.FirstName,u.LastName,u.UserPk,u.RoleFk,u.LocationFK,l.Description from user u inner join archivelocation l on u.LocationFk = l.LocationPk where u.AbhyasiID = '$user'");

if ($user1->num_rows == 1) {
    while ($row = $user1->fetch_assoc()) {
        $fullName = $row['FirstName'] . " " . $row['LastName'];
        $location = $row['Description'];
        $locationFK = $row['LocationFK'];
        $userPK = $row['UserPk'];
        $userRole = $row['RoleFk'];
    }

    $_SESSION['userLoc'] = $locationFK;
    $_SESSION['userPK'] = $userPK;
    $_SESSION['userRole'] = $userRole;
    $_SESSION['userLocationDesc'] = $location;
    $_SESSION['fullName'] = $fullName;
}

//echo $locationFK;
$bookCount = $obj->getArtefactCount($locationFK, 'Book');
$videoCount = $obj->getArtefactCount($locationFK, 'Video');
$audioCount = $obj->getArtefactCount($locationFK, 'Audio');
$magazineCount = $obj->getArtefactCount($locationFK, 'Magazine');

$pages = $obj->getPages($_SESSION['userPK']);
// 	/print_r($pages);
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
    <link href="../../css/dashboard.css" rel="stylesheet"/>
    <link href="../../css/jquery.dataTables.min.css"/>
    <script src="../../js/layout.js"></script>
    <script src="../../js/jquery-1.10.2.js"></script>
    <script src="../../js/jssor.js"></script>
    <script src="../../js/jssor.slider.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.dataTables.min.js"/>
    <script src="../../js/jquery.slimscroll.js"></script>

    <style>
        #pendingtaskitem {
            overflow: scroll;
            overflow-x: hidden;
        }

    </style>
    <script src="../../js/artefact/dashboard.js"></script>
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
                    <a href='logout.php'>Logout</a>
                </div>
                <div class="col-md-12 tp-login-info">

                    <div class="col-md-7 padding0">
                        <span>Welcome </span><span class="tp-username"><?php echo $fullName; ?></span>
                    </div>
                    <div class="col-md-5 text-right padding0">
                        <span>Location : </span><span><?php echo $location; ?></span>
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
                                echo "<li class='active'><a href='" . PAGE_DIR."".$pages[$i]['dir']."/".$pages[$i]['url'] . "' title='" . $pages[$i]['menutitle'] . "'>" . $pages[$i]['menutitle'] . "</a></li>";
                            } else {

                                echo "<li><a href='" . PAGE_DIR."".$pages[$i]['dir']."/".$pages[$i]['url']. "' title='" . $pages[$i]['menutitle'] . "'>" . $pages[$i]['menutitle'] . "</a></li>";
                            }
                        }
                    } ?>

                </ul>
                <div class="tab-content">
                    <div class="col-md-12 padding0" id="tile">
                        <div class="col-md-6 text-left dashboard">
                            <h2> Dashboard</h2>
                        </div>
                        <div class="col-md-12 dashboard-tile padding0">
                            <div id="slider1_container" class='col-md-12'
                                 style="position: relative; overflow: hidden; height: 150px;">

                                <div u="slides" class='col-md-12 slid'
                                     style="position: relative; overflow: hidden; height: 150px;">
                                    <?php
                                    $sqlq = "
												SELECT
												a.ArtefactTypeCode,
												a.ArtefactTypeDescription,
												a.ArtefactColor
												from artefacttype a
												where ArtefactTypePID is null order by SequenceNumber";

                                    $result = $obj->setQuery($sqlq);
                                    while ($rowDash = $result->fetch_assoc()) {
                                        $dis = "";
                                        if ($obj->getArtefactCount($locationFK, $rowDash['ArtefactTypeCode']) > 0 && $rowDash['ArtefactTypeCode'] != 'Photos') {
                                            $dis = $rowDash['ArtefactTypeDescription'] . "s";
                                        } else {

                                            $dis = $rowDash['ArtefactTypeDescription'];
                                        }

                                        ?>
                                        <div class="col-md-3 <?php echo $rowDash['ArtefactColor'] ?> paddingL0">
                                            <div class="col-md-12 padding0">
                                                <div class="col-md-8 ">
                                                    <span
                                                        class="tile-lblcount"><?php echo $obj->getArtefactCount($locationFK, $rowDash['ArtefactTypeCode']) ?></span>
                                                    <span class="tile-lbldesc">New <?php echo $dis; ?></span>
                                                </div>
                                                <div class="col-md-4 paddingR0 text-right">
                                                    <img src="../../images/booksimg.png" alt="" class="tile-icon"/>
                                                </div>
                                                <div class="col-md-12 text-right tile-total">
                                                </div>
                                                <div class="col-md-12 text-center moreinfo" title="Search for books"
                                                     id='<?php echo $rowDash['ArtefactTypeCode']; ?>'>
                                                    <span><a href='#' class=''>Click to See your tasks</a> </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>


                                <style>

                                    .jssora03l, .jssora03r {
                                        display: block;
                                        position: absolute;
                                        /* size of arrow element */
                                        width: 55px;
                                        height: 55px;
                                        cursor: pointer;
                                        background: url(../../images/a03.png) no-repeat;
                                        overflow: hidden;
                                    }

                                    .jssora03l {
                                        background-position: -243px -33px;
                                    }

                                    .jssora03r {
                                        background-position: -303px -33px;
                                    }

                                    .jssora03l:hover {
                                        background-position: -123px -33px;
                                    }

                                    .jssora03r:hover {
                                        background-position: -183px -33px;
                                    }

                                    .jssora03l.jssora03ldn {
                                        background-position: -243px -33px;
                                    }

                                    .jssora03r.jssora03rdn {
                                        background-position: -303px -33px;
                                    }
                                </style>
                                <!-- Arrow Left -->
        <span u="arrowleft" class="jssora03l" style="top: 123px; left: 8px;">
        </span>
                                <!-- Arrow Right -->
        <span u="arrowright" class="jssora03r" style="top: 123px; right: 8px;">
        </span>
                            </div>

                        </div>
                    </div>
                    <!--Pending task and recent entries -->
                    <div class="col-md-12 padding0">
                        <div class="col-md-6 paddingL0">
                            <div class="col-md-12 topborder padding0">
                            </div>
                            <div class="col-md-12 pending-task-header">
                                <span>Task for <?php echo date('d-m-Y'); ?></span>
                            </div>
                            <div id='pendingtaskitem' class="col-md-12 pending-task-item padding0">
                                <div class="padding0" id='cItem' style="background: none;">

                                    <?php
                                    if ($res = $obj->setQuery("select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
																		inner join artefact a
																		on s.ArtefactCode = a.ArtefactCode
																		where  s.ScheduleMaintenancePK not in (select ScheduleMaintenanceFK from tasklist)
																		and s.ScheduledServiceDate = current_date()  and s.LocationFK='$locationFK' and a.visiblestatus='on'")
                                    ) {

                                        if ($res->num_rows > 0) {
                                            while ($row1 = $res->fetch_assoc()) {
                                                $url = "../conditionalReport/conditionalReport.php?artefactCode=" . $row1['ArtefactCode'] . "&key=" . $row1['ScheduleMaintenancePK'];
                                                $name = $row1['ArtefactName'];
                                                ?>
                                                <a href="<?php echo $url ?>" class="list-group-item">
                                                    <span class="glyphicon glyphicon-file"></span> <?php echo $name ?>
                                                    <span
                                                        class='badge'><?php echo date('d/m/Y', strtotime($row1['ScheduledServiceDate'])); ?></span>
                                                </a>

                                            <?php
                                            }
                                        } else {

                                            echo "No Task today";
                                        }
                                    } else {
                                        echo "Problem in Query";
                                    }

                                    ?>
                                </div>
                            </div>
                            <a class="col-md-12 text-center pending-task-footer" href="scheduledMaintainence.php"
                               title='Click to look Schedule'>
                                <span> View All Upcomming task</span>
                            </a>
                        </div>
                        <div class="col-md-6 paddingR0">

                            <div class="col-md-12 topborder padding0">
                            </div>
                            <div class="col-md-12 recent-entries-header">
                                <span>Pending Task</span>
                            </div>
                            <div class="col-md-12 recent-entries-item padding0">
                                <div class="padding0" id='pItem' style="background: none;">

                                    <?php
                                    if ($res = $obj->setQuery("select s.ScheduleMaintenancePK,s.ArtefactCode,a.ArtefactName,s.ScheduledServiceDate from scheduledmaintenance s
																		inner join artefact a
																		on s.ArtefactCode = a.ArtefactCode
																		where  s.ScheduleMaintenancePK not in (select ScheduleMaintenanceFK from tasklist)
																		and s.ScheduledServiceDate < current_date()  and s.LocationFK='$locationFK' and a.visiblestatus='on'")
                                    ) {

                                        if ($res->num_rows > 0) {
                                            while ($row1 = $res->fetch_assoc()) {
                                                $url = "conditionalReport.php?artefactCode=" . $row1['ArtefactCode'] . "&key=" . $row1['ScheduleMaintenancePK'];
                                                $name = $row1['ArtefactName'];
                                                ?>
                                                <a href="<?php echo $url ?>" class="list-group-item">
                                                    <span class="glyphicon glyphicon-file"></span> <?php echo $name ?>
                                                    <span
                                                        class='badge'><?php echo date('d/m/Y', strtotime($row1['ScheduledServiceDate'])); ?></span>
                                                </a>

                                            <?php
                                            }
                                        } else {

                                            echo "No Pending Task";
                                        }
                                    } else {
                                        echo "Problem in Query";
                                    }

                                    ?>
                                </div>
                            </div>
                            <div class="col-md-12 text-center recent-entries-footer">
                                <span> more info</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /tabs -->
            </div>
        </div>
        <div class="col-sm-12" id="pagefooter">
            <p> &copy; 2015 SRCM. All Rights Reserved.</p>
        </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->


</body>
</html>
