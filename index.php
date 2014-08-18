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
