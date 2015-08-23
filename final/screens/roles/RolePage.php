<?php
define("PAGENAME", "Role");
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
    <link rel="stylesheet" href="../../css/font-awesome.min.css">

    <script src="../../js/layout.js"></script>
    <script src="../../js/jquery-1.10.2.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.bpopup.min.js"></script>
    <script src="../../js/jquery.dataTables.min.js"></script>
    <script src="../../js/bootbox.min.js"></script>
    <script type="text/javascript" src="../../js/dataTables.bootstrap.js"></script>
    <style type="text/css">
        .searchbg {
            background: #8bc2cb;
        }

        .searchIcon {
            color: #fff;
        }

        #RoleTable_filter > label > div > input {
            width: 150px;
        }
    </style>
    <script src="../../js/artefact/roles.js"></script>

    <style>

        #tab, th, td {
            padding: 5px;
        }

        #tab {
            border-spacing: 15px;
        }

        form.required label:after {
            content: "*";
            color: red;
        }

    </style>
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
                            <i class="fa fa-users fa-2x heading-Bg"></i> <span style="font-size: 22px;">Roles</span><br>
                        </div>
                        <div class='col-md-12 marginT20'>

                            <table class='table table-hover dataTable no-footer clearfix' id='RoleTable'>
                                <thead>
                                <tr>
                                    <th class='col-md-6'>Role</th>
                                    <th class='col-md-3'>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $result = $db->setQuery("select * from role");
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td class='col-md-6'><?php echo $row['Description'] ?></td>

                                            <td class='col-md-3'><input type='button' value='Edit'
                                                                        class='btn btn-primary editRole'
                                                                        name="<?php echo $row['RolePk'] ?>"
                                                                        id="<?php echo $row['Description'] ?>"/>
                                                <input type='button' value='Delete' class='btn btn-danger deleteRole'
                                                       name="<?php echo $row['RolePk'] ?>"
                                                       id="<?php echo $row['Description'] ?>"/></td>
                                        </tr>
                                    <?php
                                    }
                                }
                                ?>

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

                <!-- Modal -->
                <div id="myModal" style="display: none;">
                    <form name='newRoleForm' id='newRoleForm'>
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header header-color">
                                    <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-asterisk'
                                                                                    style="color: white;"></span>&nbsp;&nbsp;Add
                                        New Role</h4>
                                </div>
                                <div class="modal-body">
                                    <span id='status'></span>
                                    <table style="width: 80%;" id='tab'>
                                        <tr>
                                            <td class="padding">Role Code</td>
                                            <td class="padding"><input type="text" id='rolecode' name='abyasiId'
                                                                       class='form-control' required></td>
                                        </tr>

                                        <tr>
                                            <td class="padding">Role Name</td>
                                            <td class="padding"><input type="text" id='roleName' name='Password'
                                                                       class='form-control' required></td>
                                        </tr>


                                    </table>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger closep">Cancel</button>
                                    <input type="reset" class="btn btn-warning" value="Reset"/>
                                    <input type="submit" id='saveUser' class="btn btn-success" value='Add New'/>
                                </div>
                            </div>

                        </div>


                    </form>
                </div>
                <!-- Edit Role  -->

                <div id="editModel" style="display: none;">
                    <form name='editModelForm' id='editModelForm'>
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header header-color">
                                    <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                                    style="color: white;"></span>&nbsp;&nbsp;Update
                                        Role</h4>
                                </div>
                                <div class="modal-body">
                                    <span id='estatus'></span>
                                    <table style="width: 80%;" id='tab'>
                                        <tr>
                                            <td class="padding">Role Code</td>
                                            <td class="padding"><input type="text" id='erolecode' name='rolecode'
                                                                       class='form-control' required></td>
                                        </tr>

                                        <tr>
                                            <td class="padding">Role Name</td>
                                            <td class="padding"><input type="text" id='eroleName' name='rolename'
                                                                       class='form-control' required></td>
                                        </tr>


                                    </table>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger closep">Cancel</button>
                                    <input type="reset" class="btn btn-warning" value="Reset"/>
                                    <input type="submit" id='saveUser' class="btn btn-success" value='Save'/>
                                </div>
                            </div>

                        </div>
                    </form>

                    <!-- /tabs -->
                </div>

                <div class='statusDialog' tyle="display: none;"></div>
            </div>
            <div class="col-sm-12" id="pagefooter">
                <p>
                    &copy; 2015 SRCM. All Rights Reserved.</p>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->


</body>
</html>
