<?php

require_once './session.php';
require './dbconnector.php';

function create_notifications_seniors($code, $data, $id) {
    global $databaseMain;
    $student_query = "SELECT `Student ID` FROM `StudentSenior`";
    $student_query = mysqli_query($databaseMain, $student_query);
    $student_data = mysqli_fetch_array($student_query);
    while ($student_data) {
        $query = "INSERT INTO `Notifications` (`Student ID`, `Type`, `Title`, `Message`, `ID`, `Show`) " .
                "VALUES ( '%s', '%s' , '%s', '%s', '%s', '%s')";
        $query = sprintf($query, $student_data["Student ID"], $code, $data["title"], $data["message"], $id, $data["show"]);
        mysqli_query($databaseMain, $query);
        $student_data = mysqli_fetch_array($student_query);
    }
    unset($student_data);
    unset($student_query);
}

function create_notifications_juniors($coid, $code, $data, $id) {
    global $databaseMain;
    $student_query = "SELECT `Student ID` FROM `CompanyStudentAllocations`" .
            " WHERE `Company ID` = '%s' AND `Student ID` != '%s'";
    $student_query = sprintf($student_query, $coid, $_SESSION["id"]);
    $student_query = mysqli_query($databaseMain, $student_query);
    $student_data = mysqli_fetch_array($student_query);
    while ($student_data) {
        $query = "INSERT INTO `Notifications` (`Student ID`, `Type`, `Title`, `Message`, `ID`, `Show`) " .
                "VALUES ( '%s', '%s', '%s', '%s', '%s')";
        $query = sprintf($query, $student_data["Student ID"], $code, $data["title"], $data["message"], $id, $data["show"]);
        mysqli_query($databaseMain, $query);
        $student_data = mysqli_fetch_array($student_query);
    }
    unset($student_data);
    unset($student_query);
}

function validate_account_settings_entries() {
    if (!filter_var($_POST["FirstName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
        return "Incorrect First Name.";
    }
    if (!filter_var($_POST["LastName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
        return "Incorrect Last Name";
    }
    if (!filter_var($_POST["RollNumber"], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}(cs|ee|me|ch|ce)\d{2}$/i")))) {
        return "Enter correct College Roll Number";
    }
    if (!filter_var($_POST["CollegeEmail"], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-z]+[\.](cs|ee|me|ch|ce)[1][0-9](@iitp.ac.in)?$/i")))) {
        return "Incorrect College Email Address";
    }
    if (!filter_var($_POST["AltEmail"], FILTER_VALIDATE_EMAIL)) {
        return "Incorrect College Email Address";
    }
    if (!filter_var($_POST["Phone"], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{10}$/")))) {
        return "Incorrect Phone number";
    }
    return TRUE;
}

function update_account_settings_entries() {
    global $databaseMain;
    $query = "UPDATE `StudentVolunteer` SET `Student Name`=\"{$_POST["FirstName"]} {$_POST["LastName"]}\", " .
            "`Contact Number`=\"{$_POST["Phone"]}\", `College Roll Number`=\"{$_POST["RollNumber"]}\", " .
            "`College Email`=\"{$_POST["CollegeEmail"]}\", `Alternate Email`=\"{$_POST["AltEmail"]}\" " .
            "WHERE `Student ID`=\"{$_SESSION["id"]}\"";
    mysqli_query($databaseMain, $query);
    return $query;
}

function validate_response_entries() {
    global $databaseMain;
    $query = "SELECT COUNT(*) FROM `Companies` WHERE `Company ID`=\"%s\"";
    $query = sprintf($query, $_POST["CompanyID"]);
    $arr = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    if ($arr["COUNT(*)"] != 1) {
        return "Incorrect Company Selected.";
    }
    if (!filter_var($_POST["DateMonth"] + 0, FILTER_VALIDATE_INT, array("options" => array('min_range' => '1', "max_range" => "12")))) {
        return "Incorrect Month Selected";
    }
    if (!filter_var($_POST["DateDay"] + 0, FILTER_VALIDATE_INT, array("options" => array('min_range' => '1', "max_range" => "31")))) {
        return "Incorrect Date Selected";
    }
    if (!filter_var($_POST["DateYear"], FILTER_VALIDATE_INT)) {
        return "Incorrect Year Selected";
    }
    if (!filter_var($_POST["NextDateMonth"] + 0, FILTER_VALIDATE_INT, array("options" => array('min_range' => '1', "max_range" => "12")))) {
        return "Incorrect Month Selected";
    }
    if (!filter_var($_POST["NextDateDay"] + 0, FILTER_VALIDATE_INT, array("options" => array('min_range' => '1', "max_range" => "31")))) {
        return "Incorrect Date Selected";
    }
    if (!filter_var($_POST["NextDateYear"], FILTER_VALIDATE_INT)) {
        return "Incorrect Year Selected";
    }
    if (!filter_var($_POST["Response"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[0-9,\.-:!\(\)a-zA-Z ]+$/')))) {
        return "Unacceptable response";
    }
    return TRUE;
}

function update_response_entries() {
    global $databaseMain;
    $query = "SELECT * FROM `Responses` WHERE `Next Response` IS NULL AND `Company ID`=\"%s\"";
    $query = sprintf($query, $_POST["CompanyID"]);
    $prev_response = mysqli_query($databaseMain, $query);
    $prev_response = mysqli_fetch_array($prev_response);
    if (!isset($prev_response)) {
        $query = "INSERT INTO `Responses` " .
                "(`Company ID`, `Student ID`, `Date`, `Meeting Number`, `Previous Response`, `Next Response`, `Response`) " .
                "VALUES ('%s',         '%s',         '%s-%s-%s',   '%s',               NULL,                NULL,            '%s'); ";
        $query = sprintf($query, $_POST["CompanyID"], $_SESSION["id"], $_POST["DateYear"], $_POST["DateMonth"], $_POST["DateDay"], 1, $_POST["Response"]);
        mysqli_query($databaseMain, $query);

        $query = "SELECT * FROM `Responses` WHERE `Company ID`='%s' AND `Meeting Number`='%d'";
        $query = sprintf($query, $_POST["CompanyID"], $prev_response["Meeting Number"] + 1);
        $curr_response = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    } else {
        $query = "INSERT INTO `Responses` " .
                "(`Company ID`, `Student ID`, `Date`, `Meeting Number`, `Previous Response`, `Next Response`, `Response`) " .
                "VALUES ('%s',         '%s',         '%s%s%s',   '%d',              '%s',                NULL,            '%s'); ";
        $query = sprintf($query, $_POST["CompanyID"], $_SESSION["id"], $_POST["DateYear"], $_POST["DateMonth"], $_POST["DateDay"], $prev_response["Meeting Number"] + 1, $prev_response["Response ID"], $_POST["Response"]);
        mysqli_query($databaseMain, $query);

        $query = "SELECT * FROM `Responses` WHERE `Company ID`='%s' AND `Meeting Number`='%d'";
        $query = sprintf($query, $_POST["CompanyID"], $prev_response["Meeting Number"] + 1);
        $curr_response = mysqli_fetch_array(mysqli_query($databaseMain, $query));

        $query = "UPDATE `Responses` SET `Next Response`='%s' WHERE `Response ID`='%s'";
        $query = sprintf($query, $curr_response["Response ID"], $prev_response["Response ID"]);
        mysqli_query($databaseMain, $query);
    }

    $query = "UPDATE `Companies` SET `Last Meeting` = '%s', `Next Meeting` = '%s-%s-%s' WHERE `Company ID` = '%s'";
    $query = sprintf($query, $curr_response["Date"], $_POST["NextDateYear"], $_POST["NextDateMonth"], $_POST["NextDateDay"], $curr_response["Company ID"]);
    mysqli_query($databaseMain, $query);

    $query = "SELECT * FROM `Companies` WHERE `Company ID` = '{$curr_response["Company ID"]}'";
    $curr_response_comp = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    $query = "SELECT * FROM `StudentVolunteer` WHERE `Student ID` = '{$_SESSION["id"]}'";
    $curr_response_stud = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    global $NOTIFICATION_CODES;
    $code = $NOTIFICATION_CODES["New Response Received"];
    $notif_data = create_notification_data($code, $curr_response, $curr_response_comp, $curr_response_stud);
    create_notifications_seniors($code, $notif_data, $curr_response["Company ID"]);
    create_notifications_juniors($curr_response_comp["Company ID"], $code, $notif_data, $curr_response["Company ID"]);
}

if ($_SESSION["student"] != $VOLUNTEER_LOGIN) {
    header("Location: seniorredirect.php");
    die();
}

if (isset($_POST["clear-all-notif"]) && $_POST["clear-all-notif"] == $_SESSION["oldsalt"]) {
    $clear_notif_query = "DELETE FROM `Notifications` WHERE `Student ID` = '{$_SESSION["id"]}'";
    mysqli_query($databaseMain, $clear_notif_query);
} else if (isset($_POST["notif-id"]) && filter_var($_POST["notif-id"], FILTER_VALIDATE_INT) &&
        isset($_POST["coid"]) && filter_var($_POST["coid"], FILTER_VALIDATE_INT) &&
        isset($_POST["show"]) && filter_var($_POST["show"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/[\d]+\.[\d]+/')))) {
    $notif_query = "SELECT COUNT(*) FROM `Notifications` WHERE `Notification ID`='%s' AND `ID`='%s' AND `Show`='%s'";
    $notif_query = sprintf($notif_query, $_POST["notif-id"], $_POST["coid"], $_POST["show"]);
    $notif_query = mysqli_fetch_array(mysqli_query($databaseMain, $notif_query));
    if ($notif_query["COUNT(*)"] == 1) {
        $company_id_query = "SELECT COUNT(*) FROM `Companies` WHERE `Company ID` = '%d'";
        $company_id_query = sprintf($company_id_query, $_POST["coid"]);
        $company_id_query = mysqli_fetch_array(mysqli_query($databaseMain, $company_id_query));
        if ($company_id_query["COUNT(*)"] == 1) {
            $_SESSION["coid"] = $_POST["coid"];
            $_SESSION["coshow"] = $_POST["show"];
            $notif_del_query = "DELETE FROM `Notifications` WHERE `Notification ID` = '%s'";
            $notif_del_query = sprintf($notif_del_query, $_POST["notif-id"]);
            mysqli_query($databaseMain, $notif_del_query);
        }
    }
} else if (isset($_GET["coid"]) && filter_var($_GET["coid"], FILTER_VALIDATE_INT)) {
    $company_id_query = "SELECT COUNT(*) FROM `Companies` WHERE `Company ID` = '%d'";
    $company_id_query = sprintf($company_id_query, $_GET["coid"]);
    $company_id_query = mysqli_fetch_array(mysqli_query($databaseMain, $company_id_query));
    if ($company_id_query["COUNT(*)"] == 1) {
        $_SESSION["coid"] = $_GET["coid"];
        $_SESSION["coshow"] = "1.0";
    }
} else if (isset($_POST["page"])) {
    if ($_POST["page"] == $PAGES["Logout"]) {
        header("Location: logout.php");
        die();
    }
    $_SESSION["page"] = $_POST["page"];
} else if (isset($_POST["set"])) {
    switch ($_POST["set"]) {
        case $PAGES["Account Settings"] : {
                $msg = validate_account_settings_entries();
                if ($msg === TRUE) {
                    update_account_settings_entries();
                    $_SESSION["page"] = $PAGES["Volunteer"];
                    $_SESSION["msg"] = "The account details have been updated successfully.";
                } else {
                    $_SESSION["page"] = $_POST["set"];
                    $_SESSION["msg"] = $msg;
                }
                break;
            } case $PAGES["Response"] : {
                $msg = validate_response_entries();
                if ($msg === TRUE) {
                    update_response_entries();
                    $_SESSION["page"] = $PAGES["Volunteer"];
                    $_SESSION["msg"] = "The response has been recorded.";
                } else {
                    $_SESSION["page"] = $_POST["set"];
                    $_SESSION["msg"] = $msg;
                }
                break;
            }
    }
} else {
    $_SESSION["page"] = $PAGES["Volunteer"];
}
unset($_POST);
unset($_GET);
header("Location: volunteer.php");
die();
?>
