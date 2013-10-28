
<!DOCTYPE html>

<HTML>

    <BODY>

        <FORM action="register.php" method="post">


            Student ID : <INPUT type="text" name="id" /> <br />
            Student Username : <INPUT type="text" name="user" /> <br />
            Password :  <INPUT type="password" name="pass" /> <br />
            <INPUT type="submit" name="Send" />

        </FORM>





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
            //header("Location: index.php?msg=$msg_str&color=ff0000");
            die();
        }
        if (!filter_var($user, FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
            $msg_str = "Enter your proper login name. Login name has to be alphabetic only. No numbers or special charcters.";
            echo $msg_str;
            //header("Location: index.php?msg=$msg_str&color=ff0000");
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

        $count = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `users` WHERE `Student ID` = " . $id));
        if ($count["COUNT(*)"] != 1) {
            $msg_str = "Enter your proper Student ID. Check database. Add dummy entry if needed.";
            echo $msg_str;
            //header("Location: index.php?msg=$msg_str&color=ff0000");
            die();
        }

        $count = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `users` WHERE `Student Username` = \"" . $user . "\""));
        $value = mysqli_fetch_array(mysqli_query($con, "SELECT `Student ID` FROM `users` WHERE `Student Username` = \"" . $user . "\""));
        if (($count["COUNT(*)"] > 1) || (isset($value["Student ID"]) && $value["Student ID"] != $id)) {
            $msg_str = "Choose a unique username. " . $user . " is already taken.";
            echo $msg_str;
            //header("Location: index.php?msg=$msg_str&color=ff0000");
            die();
        }

        echo "Setting password.... <br />";
        mysqli_query($con, "UPDATE `users` SET `Student Username`=\"" . $user . "\",`Salt`=\" \",`Hash`=\"" . $hash . "\" WHERE `Student ID`=" . $id);
        echo "Password set! <br />";

        mysqli_close($con);

        unset($_POST["id"]);
        unset($_POST["user"]);
        unset($_POST["pass"]);
        header("Refresh: 2; Location: register.php");
        echo "Done";
        ?>


    </BODY>

</HTML>