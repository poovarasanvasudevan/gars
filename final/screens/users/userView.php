<?php
define("PAGENAME", "User");
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
    <script src="../../js/jquery.bpopup.min.js"></script>
    <script src="../../js/jquery.dataTables.min.js"></script>
    <script src="../../js/bootbox.min.js"></script>
    <link rel="stylesheet" href="../../css/font-awesome.min.css">

    <script type="text/javascript" src="../../js/dataTables.bootstrap.js"></script>
    <style type="text/css">
        .searchbg {
            background: #8bc2cb;
        }

        .searchIcon {
            color: #fff;
        }

        #userTable_filter > label > div > input {
            width: 150px;
        }
    </style>

    <script src="../../js/artefact/users.js"></script>


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
                            <i class="fa fa-user fa-2x heading-Bg"></i> <span style="font-size: 22px;">Users</span><br>
                        </div>


                        <div class="col-md-12 dashboard-tile padding10">
                            <div class="loadingUser marginT20">
                                <table class="table table-striped table-hover" style="width: 100%;" align='center'
                                       id='userTable'>
                                    <thead>
                                    <tr class='' style="background-color: #8bc2cb;">
                                        <th>AbhyasiID</th>
                                        <th>Location</th>
                                        <th>FirstName</th>
                                        <th>Lastname</th>
                                        <th>Email Id</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php



                                    $result = $db->setQuery("select l.description,
																			r.Description,
																			u.firstname,
																			u.lastname,
																			u.userpk,
																			u.AbhyasiID,
																			u.EmailId ,
																			u.ActiveStatus
																			from user u inner join archivelocation l 
																			on u.locationfk = l.locationpk
																			inner join role r
																			on u.rolefk = r.rolepk;"
                                    );


                                    if ($result->num_rows > 0) {

                                        while ($row = $result->fetch_assoc()) {
                                            $btntext = "";
                                            if ($row['ActiveStatus'] == 'on')
                                                $btntext = "De-active";
                                            else
                                                $btntext = "Active";


                                            echo "<tr>
															<td>" . $row['AbhyasiID'] . "</td>
															<td>" . $row['description'] . "</td>
															<td>" . $row['firstname'] . "</td>
															<td>" . $row['lastname'] . "</td>
															<td>" . $row['EmailId'] . "</td>
															<td>" . $row['Description'] . "</td>
															<td><input type='button' id='edit' class='btn btn-success editButton' name='" . $row['userpk'] . "' value='Edit' data-toggle='modal' data-target='#editModel' title='Click to Edit User Information'/>&nbsp;&nbsp;&nbsp;
																<input type='button' class='btn btn-danger deleteButton' id='delete' name='" . $row['userpk'] . "' value='" . $btntext . "' title='" . $btntext . " this user'/></td>
														  </tr>";
                                        }
                                    }


                                    ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class='loadem' id='loadem' width='400px' height='300px' style='display: none;'>
                                <form name='enewUserForm'>
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header btn-primary">
                                                <h4 class="modal-title"><span class='glyphicon glyphicon-user'
                                                                              style="color: white;"></span>&nbsp;&nbsp;Update
                                                    User</h4>
                                            </div>
                                            <div class="modal-body">
                                                <span id='estatus'></span>
                                                <table style="width: 80%;" id='tab'>
                                                    <tr>
                                                        <td class="padding">Abyasi Id</td>
                                                        <td class="padding"><input type="text" id='eabyasiId'
                                                                                   name='abyasiId' class='form-control'>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="padding">Password</td>
                                                        <td class="padding"><input type="password" id='epassword'
                                                                                   name='Password' class='form-control'>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="padding">FirstName</td>
                                                        <td class="padding"><input type="text" id='efirstName'
                                                                                   name='firstName'
                                                                                   class='form-control'></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="padding">Middle Name</td>
                                                        <td class="padding"><input type="text" id='emiddleName'
                                                                                   name='middleName'
                                                                                   class='form-control'></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="padding">LastName</td>
                                                        <td class="padding"><input type="text" id='elastName'
                                                                                   name='lastName' class='form-control'>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="padding">E-mail</td>
                                                        <td class="padding"><input type="email" id='eemail' name='email'
                                                                                   class='form-control'></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="padding">Location</td>
                                                        <td class="padding">
                                                            <select name="location" class="form-control" id='elocation'>
                                                                <option value="">Select Location</option>
                                                                <?php
                                                                $locationResult = $db->setQuery("select LocationPk,Description from archivelocation");
                                                                if ($locationResult->num_rows > 0) {

                                                                    while ($locationRows = $locationResult->fetch_assoc()) {
                                                                        echo "<option value=" . $locationRows['LocationPk'] . ">" . $locationRows['Description'] . "</option>";
                                                                    }

                                                                }

                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="padding">Role</td>
                                                        <td class="padding">
                                                            <select name="roles" class="form-control" id='eroles'>
                                                                <option value="">Select Role</option>
                                                                <?php
                                                                $rolesResult = $db->setQuery("select RolePk,Description from role");
                                                                if ($rolesResult->num_rows > 0) {

                                                                    while ($rolesRows = $rolesResult->fetch_assoc()) {
                                                                        echo "<option value=" . $rolesRows['RolePk'] . ">" . $rolesRows['Description'] . "</option>";
                                                                    }

                                                                }

                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                </table>


                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger closep">Close</button>
                                                <input type="reset" class="btn btn-warning" value="Reset"/>
                                                <input type="button" id='updateUser' class="btn btn-success"
                                                       value='Update User'/>
                                            </div>
                                        </div>

                                    </div>
                            </div>


                            </form>

                        </div>


                        <!-- Modal -->
                        <div id="myModal" style="display: none;">
                            <form name='newUserForm' id='newUserForm'>
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header btn-primary">
                                            <h4 class="modal-title"><span class='glyphicon glyphicon-user'
                                                                          style="color: white;"></span>&nbsp;&nbsp;Add
                                                New User</h4>
                                        </div>
                                        <div class="modal-body">
                                            <span id='status'></span>
                                            <table style="width: 80%;" id='tab'>
                                                <tr>
                                                    <td class="padding">Abyasi Id</td>
                                                    <td class="padding"><input type="text" id='abyasiId' name='abyasiId'
                                                                               class='form-control' required></td>
                                                </tr>

                                                <tr>
                                                    <td class="padding">Password</td>
                                                    <td class="padding"><input type="password" id='password'
                                                                               name='Password' class='form-control'
                                                                               required></td>
                                                </tr>

                                                <tr>
                                                    <td class="padding">FirstName</td>
                                                    <td class="padding"><input type="text" id='firstName'
                                                                               name='firstName' class='form-control'
                                                                               required></td>
                                                </tr>

                                                <tr>
                                                    <td class="padding">Middle Name</td>
                                                    <td class="padding"><input type="text" id='middleName'
                                                                               name='middleName' class='form-control'>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="padding">LastName</td>
                                                    <td class="padding"><input type="text" id='lastName' name='lastName'
                                                                               class='form-control' required></td>
                                                </tr>

                                                <tr>
                                                    <td class="padding">E-mail</td>
                                                    <td class="padding"><input type="email" id='email' name='email'
                                                                               class='form-control' required></td>
                                                </tr>

                                                <tr>
                                                    <td class="padding">Location</td>
                                                    <td class="padding">
                                                        <select name="location" class="form-control" id='location'
                                                                required>
                                                            <option value="">Select Location</option>
                                                            <?php
                                                            $locationResult = $db->setQuery("select LocationPk,Description from archivelocation");
                                                            if ($locationResult->num_rows > 0) {

                                                                while ($locationRows = $locationResult->fetch_assoc()) {
                                                                    echo "<option value=" . $locationRows['LocationPk'] . ">" . $locationRows['Description'] . "</option>";
                                                                }

                                                            }

                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="padding">Role</td>
                                                    <td class="padding">
                                                        <select name="roles" class="form-control" id='roles' required>
                                                            <option value="">Select Role</option>
                                                            <?php
                                                            $rolesResult = $db->setQuery("select RolePk,Description from role");
                                                            if ($rolesResult->num_rows > 0) {

                                                                while ($rolesRows = $rolesResult->fetch_assoc()) {
                                                                    echo "<option value=" . $rolesRows['RolePk'] . ">" . $rolesRows['Description'] . "</option>";
                                                                }

                                                            }

                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                            </table>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger closep">Close</button>
                                            <input type="reset" class="btn btn-warning" value="Reset"/>
                                            <input type="submit" id='saveUser' class="btn btn-success"
                                                   value='Add User'/>
                                        </div>
                                    </div>

                                </div>
                            </form>
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
