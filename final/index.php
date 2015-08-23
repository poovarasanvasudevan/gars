<?php
session_start();
if (isset($_SESSION['artefactUser'])) {
    header("Location : ./screens/dashboard/dashboard.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet"/>
    <script src="js/layout.js"></script>
    <link rel="icon"
          type="image/png"
          href="images/logo.png"/>

    <LINK REL="SHORTCUT ICON"
          HREF="images/logo.png">

    <title>Global Archive</title>
</head>
<body class="loginBody">
<div class="wrapper">
    <div class="container">

        <div class="row">

            <div class="col-sm-10" style="padding-left: 0px;">
                <div class="logintxt">Global Archives</div>
            </div>
        </div>


        <div class="login_bg row">

            <div class="sticky-logo"><img alt="" width="85" height="100" src="images/loginimage.png"/ ></div>


            <div class="loginForm">
                <div class="col-lg-6 col-sm-6" id="watermark">
                    <img alt="Watermark" width="300" height="300" src="images/login-watermark.png"/ >
                </div>
                <div class="col-lg-6 col-sm-6 form-content">
                    <form class="form-signin" method="post" action="./screens/login/loginValidate.php">
                        <h2 class="form-signin-heading">User Login</h2>
                        <label for="inputEmail" class="col-lg-3 col-sm-3 uname">Abhyasi Id:</label>

                        <div class="col-md-9 col-sm-9">
                            <input type="text" autofocus="" placeholder="Enter Abhyasi Id" name="username"
                                   title="Please enter a valid Abhyasi Id" class="form-control" id="inputEmail"
                                   required="required" maxlength="10">
							<span id="error">
								<?php
                                if (isset($_GET['error'])) {
                                    echo '<h5 class="text-danger">Invalid Abhyasi Id</h5>';
                                }
                                ?>
							</span>
                        </div>

                        <label for="inputPassword" class="col-lg-3 col-sm-3 upass">Password:</label>

                        <div class="col-md-9 col-sm-9">
                            <input type="password" name="password" title="Please enter a valid password"
                                   class="form-control" id="inputPassword" required="required" maxlength="10">
							<span id="error">
								<?php
                                if (isset($_GET['error'])) {
                                    echo '<h5 class="text-danger">Invalid Password</h5>';
                                }
                                ?>
							</span>
                        </div>

                        <div class="col-md-12" style="text-align:left;padding-left: 30px;">
                            <button type="submit" class="btn btn-lg btn-primary col-md-offset-3 col-md-9 col-sm-9"
                                    title='Login to Global Archives'>LOGIN
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>

        <div class="row row col-sm-12" id="pagefooter">
            <p>&copy; 2015 SRCM. All Rights Reserved.</p>
        </div>

    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</body>
</html>