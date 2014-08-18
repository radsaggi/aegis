<?php
require './session.php';
require './dbconnector.php';
if($_SESSION["student"] != $SENIOR_LOGIN) {
    if($_SESSION["student"] != $VOLUNTEER_LOGIN) {
        header('location:index.php');
        die();
    } else {
        header('location:volunredirect.php');
    }
}
$ID=$_SESSION["id"];
?>

<!DOCTYPE html>
<HTML>
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/register.css">
</head>
    <BODY>
    <div class="profile-link"><a href="senior.php">Back To Profile</a></div>
        <div class="container">
            <h1>Sponsorship Registration Portal</h1>
            
            
        <FORM action="register.php" method="post" class="well form-horizontal col-sm-8 col-sm-offset-2" role="form">

            <div class="form-group">
                <label for="studentid" class="col-sm-3 control-label">Student ID</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="studentid" placeholder="Student ID" name="id">
                    </div>
            </div>
            <div class="form-group">
                <label for="username" class="col-sm-3 control-label">Student Username</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="username" placeholder="Username" name="user">
                    </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="pass">
                    </div>
            </div>

            <div class="form-group -sol-sm-6">
                <div class="col-sm-offset-3 col-sm-4">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>

        </FORM>
    </div>
        <?php
        if (!isset($_POST["user"]) || !isset($_POST["id"]) || !isset($_POST["pass"])) {
            die();
        }

        $cost = 10;
        $user = $_POST["user"];
        $id = $_POST["id"];
        $pass = $_POST["pass"];

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            $msg_str = "Enter your proper Student ID. [".$id."]";
            echo $msg_str;
            die();
        }
        if (!filter_var($user, FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
            $msg_str = "Enter your proper login name. Login name has to be alphabetic only. No numbers or special charcters.";
            echo $msg_str;
            die();
        }

        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        $salt = sprintf("$2y$%02d$", $cost) . $salt;
        $hash = crypt($pass, $salt);

        $con = mysqli_connect("localhost", "login_user", "hasD78PwD9login", "sponsorship");
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br /> CONTACT ADMIN.";
            die();
        }
        //ID check
        $value = mysqli_fetch_array(mysqli_query($con, "SELECT `Student ID` FROM `users` WHERE `Student ID` = \"" . $id . "\""));
        if(isset($value["Student ID"])){
            $msg_str = "ID alreay exists";
            echo $msg_str;
            die();
        }
        //Username check
        $value = mysqli_fetch_array(mysqli_query($con, "SELECT `Student Username` FROM `users` WHERE `Student Username` = \"" . $user . "\""));
        if(isset($value["Student Username"])){
            $msg_str = "Username alreay exists,choose another one";
            echo $msg_str;
            die();
        }
        $insert_query = "INSERT INTO `users` (`Student ID`,`Student Username`,`Salt`,`Hash`) VALUES ($id,'{$user}','{$salt}','{$hash}')";
        $success = mysqli_query($con,$insert_query);
        if(!$success)
        {
            echo "Unable to insert query ".mysqli_error($con);
            die();
        }
        else{
            if($id<100){
                $insert_query = "INSERT INTO `StudentSenior` (`Student ID`,`Student Name`) VALUES ($id,'{$user}')";
            }
            else
            {
                $insert_query = "INSERT INTO `StudentVolunteer` (`Student ID`,`Student Name`) VALUES ($id,'{$user}')";
            }
            if(!mysqli_query($con,$insert_query)){
                echo "Unable to add to Student Database".mysqli_error($con);
                die();
            }
        }
        echo "Setting password.... <br />";
        echo "Password set! <br />";

        mysqli_close($con);

        unset($_POST["id"]);
        unset($_POST["user"]);
        unset($_POST["pass"]);
        echo "Done";
        ?>


    </BODY>

</HTML>