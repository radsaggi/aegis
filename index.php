<?php
$login = false;
unset($ID);
require './session.php';


if (!$login) {
    if ($_SESSION["student"] == $VOLUNTEER_LOGIN) {
        require './volunredirect.php';
    } else if ($_SESSION["student"] == $SENIOR_LOGIN) {
        require './seniorredirect.php';
    }
}


?>
<!DOCTYPE html> 

<html>

    <head>

    <link rel = "stylesheet" type="text/css" href="assets/css/bootstrap.css"/>
        <style>
            input {
                border:0px solid #ffcfa4;
                border-radius:10px;
                padding:3px 5px;
            }

            input:hover, input:focus {
                box-shadow: 0px 0px 5px 5px #ffa452;
            }
        </style>
        <title>Sponsorship Login Portal</title> 
    </head>

    <body>


        <table width="100%">
            <tr>
                <td style="text-align:center;<?php
                                if (isset($_GET["color"])) {
                                    echo "color:#{$_GET["color"]};";
                                }
                           ?>font-weight:bolder;">
                <?php
                if (isset($_GET["msg"])) {
                    echo $_GET["msg"];
                }
                ?>
                </td>
            </tr>
        </table>



        <!--<form action="login.php" method="post" name="login_form">

            <table bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td align="center">
                <center>
                    <br />
                    <div style="font-family:verdana;background-color:#ffcfa4;padding:20px;border-radius:10px;width:350px;border:10px solid #EE872A;">
                        <table style="border:0px;width:350">
                            <tr>
                                <td style="background-color:#5dc1df;text-align:center;">
                                    <b>IIT Patna Sponsorship Portal Login</b>
                                </td>
                            </tr>

                            <tr>
                                <td align="left">
                                    <table align="center" border="0" width="100%">
                                        <tr>
                                            <td align="right" width="30%">StudentID:</td> 
                                            <td align="left" width="70%">
                                                <input type="text" name="login_id" value="" autofocus="autofocus"/>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td align="right" width="30%">Password:</td>
                                            <td align="left" width="70%">
                                                <input type="password" name="login_pwd" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr><td align="left"><center><input type="submit" value="Login" /></center></td>
                            </tr>
                        </table>
                    </div>
                </center></td>
                </tr>
            </table>

        </form>-->
        <h1 style="text-align:center">Sponsorship Login Portal</h1>
        <div class = "container" style="margin-top:50px">
            <form action = "login.php" method="post" class="well form-horizontal col-sm-6 col-sm-offset-3" role="form" name = "login_form">
                <div class = "form-group">
                    <label for="studentid" class="col-sm-3 control-label">Student ID</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="studentid" placeholder="Student ID" name="login_id" autofocus="autofocus">
                    </div>
                </div>
                <div class = "form-group">
                    <label for="password" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="login_pwd">
                    </div>
                </div>
                <div class="form-group -sol-sm-6">
                <div class="col-sm-offset-3 col-sm-4">
                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>
            </div>
            </form>
        </div>


    </body>
</html> 
