
<!DOCTYPE html>

<?php
require "./session.php";
require './dbconnector.php';
$id = $_SESSION["id"];

if ($_SESSION["student"] != $SENIOR_LOGIN) {
    header("Location: volunredirect.php");
    die();
}

$user_data = mysqli_query($databaseMain, "SELECT * FROM `StudentSenior` WHERE `Student ID`=$id;");
if (!$user_data) {
    $user_data = NULL;
} else {
    $user_data = mysqli_fetch_array($user_data);
}
?>


<html>
    <head>
        <title> <?php
            if (isset($user_data)) {
                echo "Student Senior : {$user_data["Student Name"]}";
            }
            ?> 
        </title>
        <link rel="stylesheet" type="text/css" href="./style.css">
        <link rel="stylesheet" type="text/css" href="./codetails.css">
    </head>

    <body id="global-body">
        <div id="header-div" class="div">
            <div id="login-name">
                <h1>
                    <?php echo $user_data["Student Name"] . "({$_SESSION["id"]})"; ?>
                </h1>
            </div>
            <div id="login-name-arrow"></div>
            <div id="buttons-menu">
                <ul class="ul-1">
                    <li class="li-1"><a href='javascript:;' onclick="document.getElementById('form-logout').submit();"><span>Logout</span></a></li>
                    <li class="li-1"><a href='javascript:;' onclick="document.getElementById('form-acc').submit();"><span>Account</span></a></li>
                    <li class="li-1"><a href='javascript:;' onclick="document.getElementById('form-response').submit();"><span>Give Response</span></a></li>
                    <li class="li-1"><a href='#'><span>Companies</span></a>
                        <ul class="ul-2">
                            <li class="li-2"><a href="javascript:;" onclick="document.getElementById('form-new-company').submit();"><span>Create New</span></a></li>
                            <li class="li-2"><a href="javascript:;" onclick="document.getElementById('form-change-company').submit();"><span>Change details</span></a></li>
                            <li class="li-2"><a href="javascript:;" onclick="document.getElementById('form-student-alloc').submit();"><span>Student Allocation</span></a></li>
                        </ul>
                    </li>


                </ul>
            </div>

        </div>



        <form id="form-acc" method="post" action="seniorredirect.php">
            <input type="hidden" name="page" value="<?php echo "{$PAGES["Account Settings"]}"; ?>" />
        </form>
        <form id="form-logout" method="post" action="seniorredirect.php">
            <input type="hidden" name="page" value="<?php echo "{$PAGES["Logout"]}"; ?>" />
        </form>
        <form id="form-response" method="post" action="seniorredirect.php">
            <input type="hidden" name="page" value="<?php echo "{$PAGES["Response"]}"; ?>" />            
        </form>
        <form id="form-new-company" method="post" action="seniorredirect.php">
            <input type="hidden" name="page" value="<?php echo "{$PAGES["New Company"]}"; ?>" />
        </form>
        <form id="form-change-company" method="post" action="seniorredirect.php">
            <input type="hidden" name="page" value="<?php echo "{$PAGES["Change Company"]}"; ?>" />
        </form>
        <form id="form-student-alloc" method="post" action="seniorredirect.php">
            <input type="hidden" name="page" value="<?php echo "{$PAGES["Student Allocation"]}"; ?>" />
        </form>


        <div id="list-companies-div">

            <h2> COMPANY LIST </h2>
            <div id="list-companies-data"> <ul>
                    <?php
                    $co_query = mysqli_query($databaseMain, "SELECT `Company Name`, `Company ID` from `Companies`");
                    $co_data = mysqli_fetch_array($co_query);
                    while ($co_data) {
                        ?>
                        <li class="<?php echo $co_data["Company ID"] == $_SESSION["coid"] ? "selected" : "regular"; ?>">
                            <a href="./seniorredirect.php?coid=<?php echo $co_data["Company ID"]; ?>"><?php echo $co_data["Company Name"]; ?></a>
                        </li>
                        <?php
                        $co_data = mysqli_fetch_array($co_query);
                    }
                    ?>
                </ul> </div>

        </div>


        <div id="notifications">
            <h2 id="title"> NOTIFICATIONS : </h2>

            <ol id="notifications-list">
                <?php
                $notif_query = "SELECT * FROM `Notifications` WHERE `Student ID` = '%s'";
                $notif_query = sprintf($notif_query, $_SESSION["id"]);
                $notif_query = mysqli_query($databaseMain, $notif_query);
                $notif_data = mysqli_fetch_array($notif_query);
                $c = 0;
                while ($notif_data) {
                    $notif_data["Form ID"] = "notification-view-{$notif_data["Notification ID"]}";
                    $c++;
                    ?>
                    <li>
                        <h2><a href="javascript:;" onclick="document.getElementById('<?php echo $notif_data["Form ID"]; ?>').submit();"><?php echo $notif_data["Title"]; ?></a></h2>
                        <p><?php echo $notif_data["Message"]; ?></p>
                        <form id="<?php echo $notif_data["Form ID"]; ?>" method="post" action="seniorredirect.php">
                            <input type="hidden" name="coid" value="<?php echo $notif_data["ID"]; ?>"/>
                            <input type="hidden" name="show" value="<?php echo $notif_data["Show"]; ?>"/>
                            <input type="hidden" name="notif-id" value="<?php echo $notif_data["Notification ID"]; ?>"/>
                        </form>
                    </li>

                    <?php
                    $notif_data = mysqli_fetch_array($notif_query);
                }
                ?>
            </ol>
            <?php
            if ($c > 0) {
                ?>
                <form id="clear-all-notif-form" method="post" action="seniorredirect.php">
                    <input type="hidden" name="clear-all-notif" value="<?php echo $_SESSION["salt"]; ?>"/>
                    <input type="submit" value="Clear All"/>
                </form>
                <?php
            }
            ?>
        </div>



        <div id="main-scrollable">
            <div id="company-details">
                <?php
                $_GET["coid"] = $_SESSION["coid"];
                $_GET["show"] = $_SESSION["coshow"];
                require "./codetails.php";
                ?>
            </div>
        </div>

        <?php
        if ($_SESSION["page"] != $PAGES["Senior"]) {
            ?>
            <div id="pagedisplay">
                <?php require "{$_SESSION["page"]}"; ?>
            </div>
            <?php
        }

        if (isset($_SESSION["msg"])) {
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
        ?>

        <?php
        if ($_SESSION["views"] === 2) {

            echo "<footer> 
               For best experience use latest version of Firefox. Chrome, Opera and Safari are also supported.
               Wondering where's IE?
               </footer>";
        }
        ?>

    </body>
</html>
