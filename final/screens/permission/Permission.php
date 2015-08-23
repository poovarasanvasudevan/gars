<?php
define("PAGENAME", "Permissions");
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


    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/dashboard.css" rel="stylesheet"/>
    <link href="../../css/jquery.dataTables.min.css"/>
    <script src="../../js/layout.js"></script>
    <script src="../../js/jquery-1.10.2.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.dataTables.min.js"></script>
    <link rel='shortcut  icon' href='../../images/logo-ga.ico'/>
    <link rel="stylesheet" href="../../css/font-awesome.min.css">


    <link rel="stylesheet" href="../../css/jquery.growl.css">
    <script src="../../js/jquery.growl.js"></script>


    <script>
        function getRolemap() {
            var selected = $('#roleSelect').val();
            if (selected != '') {
                $.ajax({
                    url: "permissionMapping.php",
                    data: "role=" + selected,
                    success: function (data) {
                        $("#roleData").html(data);

                        $("#rolemapForm").submit(function () {
                            savePermission();
                            return false;
                        });
                    }
                });

            } else {
            }
        }


        function savePermission() {
            var checked = '';
            $('.pagePermission:checked').each(function () {
                checked = checked + ',' + $(this).val();
            });

            var selected = $('#roleSelect').val();
            $.ajax({
                url: "saveAccessPermission.php",
                data: "role=" + selected + "&permission=" + checked,
                success: function (data) {
                    if (data == "success") {

                        $.growl.notice({message: "Updated Succesfully...!", size: 'large'});
                    } else {

                        $.growl.error({message: "Updation Failed..!", size: 'large'});
                    }
                }
            });
            return false;
        }

        function selectAll(obj) {
            if (obj.checked) {
                $('.pagePermission').each(function () {
                    this.checked = true;
                });
            } else {
                $('.pagePermission').each(function () {
                    this.checked = false;
                });
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
                        <div class="col-md-12 text-left border-low heading-Bg">
                            <div class='col-md-4'>
                                <i class="fa fa-file-text-o fa-2x heading-Bg"></i> <span style="font-size: 22px;">Permission</span>
                            </div>

                            <div class='col-md-8'>

                                <div class='col-md-12'>
                                    <div class='col-md-2'>
                                        <label style='margin-top: 10px;'>Select Role</label>
                                    </div>
                                    <div class='col-md-7'>
                                        <?php
                                        $result = $db->setQuery("select * from role");
                                        if ($result->num_rows > 0) {
                                            echo "<select name='role' class='form-control' id='roleSelect' onclick='getRolemap()'>";
                                            echo "<option value=''>Select a Role.</option>";
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='$row[RolePk]'>$row[Description]</option>";

                                            }
                                            echo "</select>";

                                        } else {

                                            echo "<label>Sorry No Roles Defined.</label>";
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class='col-md-12 marginT20'>
                            <div class='row'>
                                <span id='status'></span>
                            </div>
                            <div class='col-md-12' id='roleData'>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- /tabs -->
            </div>

        </div>

        <div class="col-sm-12" id="pagefooter">
            <p>
                &copy; 2015 SRCM. All Rights Reserved.</p>
        </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->


</body>
</html>
