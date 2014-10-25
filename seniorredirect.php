<?php

require_once './session.php';
require './dbconnector.php';

function create_notifications_seniors($code, $data, $id) {
    global $databaseMain;
    $student_query = "SELECT `Student ID` FROM `StudentSenior` WHERE `Student ID` != '%s'";
    $student_query = sprintf($student_query, $_SESSION["id"]);
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
    $query = "UPDATE `StudentSenior` SET `Student Name`=\"{$_POST["FirstName"]} {$_POST["LastName"]}\", " .
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
    $query = "SELECT * FROM `StudentSenior` WHERE `Student ID` = '{$_SESSION["id"]}'";
    $curr_response_stud = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    global $NOTIFICATION_CODES;
    $code = $NOTIFICATION_CODES["New Response Received"];
    $notif_data = create_notification_data($code, $curr_response, $curr_response_comp, $curr_response_stud);
    create_notifications_seniors($code, $notif_data, $curr_response["Company ID"]);
    create_notifications_juniors($curr_response_comp["Company ID"], $code, $notif_data, $curr_response["Company ID"]);
}

function validate_new_company_entries() {
    if (!filter_var($_POST["CompanyName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z, ]+$/')))) {
        return "Incorrect Company Name.";
    }
    if (!filter_var($_POST["Address"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[0-9,\.\-:!\(\)\/_a-zA-Z;\s+]+$/')))) {
        return "Incorrect Address";
    }
    if (!filter_var($_POST["ContactFirstName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
        return "Incorrect Contact First Name";
    }
    if (!filter_var($_POST["ContactLastName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
        return "Incorrect Contact Last Name";
    }
    if (!filter_var($_POST["ContactNumber"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^\d{10}$/')))) {
        return "Incorrect Contact Number";
    }
    if (!filter_var($_POST["ContactDesignation"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z ]+$/')))) {
        return "Incorrect Contact Designation";
    }
    if (!filter_var($_POST["SponsorshipFor"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[0-9,\.-:!\(\)a-zA-Z ]+$/')))) {
        return "Incorrect Sponsorship For";
    }
    global $databaseMain;
    $query = "SELECT COUNT(*) FROM `SponsorshipCategories` WHERE `Category ID` = '%s'";
    $query = sprintf($query, $_POST["SCategory"]);
    $query = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    if ($query["COUNT(*)"] != 1) {
        return "Incorrect Sponsorship Category";
    }
    $query = "SELECT COUNT(*) FROM `Probability Index` WHERE `Probability Index ID` = '%s'";
    $query = sprintf($query, $_POST["ProbabilityIndex"]);
    $query = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    if ($query["COUNT(*)"] != 1) {
        return "Incorrect Probability Index";
    }

    return TRUE;
}

function update_new_company_entries() {
    global $databaseMain;
    $query = "INSERT INTO `Companies` (`Company Name` ,`Address` ,`Contact Name` ,`Contact Number` ,`Contact Designation` ,`Sponsorship Category` ,`Last Response` ,`Sponsorship For` ,`Probability Index ID`) " .
            "VALUES ('%s',           '%s',      '%s %s',            '%s',           '%s',                '%s',                   NULL,           '%s',               '%s')";
    $query = sprintf($query, $_POST["CompanyName"], $_POST["Address"], $_POST["ContactFirstName"], $_POST["ContactLastName"], $_POST["ContactNumber"], $_POST["ContactDesignation"], $_POST["SCategory"], $_POST["SponsorshipFor"], $_POST["ProbabilityIndex"]);
    mysqli_query($databaseMain, $query);

    $query = "SELECT * FROM `Companies` WHERE `Company Name` = '%s'";
    $query = sprintf($query, $_POST["CompanyName"]);
    $curr_co = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    $query = "SELECT * FROM `StudentSenior` WHERE `Student ID` = '%s'";
    $query = sprintf($query, $_SESSION["id"]);
    $curr_stud = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    global $NOTIFICATION_CODES;
    $code = $NOTIFICATION_CODES["New Company Created"];
    $notif_data = create_notification_data($code, $curr_co, $curr_stud);
    create_notifications_seniors($code, $notif_data, $curr_co["Company ID"]);
}

function validate_ch_company_entries() {
    if (!filter_var($_POST["CompanyName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z, ]+$/')))) {
        return "Incorrect Company Name.";
    }
    if (!filter_var($_POST["Address"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[0-9,\.\-:!\(\)\/_a-zA-Z;\s+]+$/')))) {
        return "Incorrect Address";
    }
    if (!filter_var($_POST["ContactFirstName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
        return "Incorrect Contact First Name";
    }
    if (!filter_var($_POST["ContactLastName"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')))) {
        return "Incorrect Contact Last Name";
    }
    if (!filter_var($_POST["ContactNumber"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^\d{10}$/')))) {
        return "Incorrect Contact Number";
    }
    if (!filter_var($_POST["ContactDesignation"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-z A-Z]+$/')))) {
        return "Incorrect Contact Designation";
    }
    if (!filter_var($_POST["SponsorshipFor"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[0-9,\.-:!\(\)a-zA-Z ]+$/')))) {
        return "Incorrect Sponsorship For";
    }
    global $databaseMain;
    $query = "SELECT COUNT(*) FROM `SponsorshipCategories` WHERE `Category ID` = '%s'";
    $query = sprintf($query, $_POST["SCategory"]);
    $query = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    if ($query["COUNT(*)"] != 1) {
        return "Incorrect Sponsorship Category";
    }
    $query = "SELECT COUNT(*) FROM `Probability Index` WHERE `Probability Index ID` = '%s'";
    $query = sprintf($query, $_POST["ProbabilityIndex"]);
    $query = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    if ($query["COUNT(*)"] != 1) {
        return "Incorrect Probability Index";
    }

    return TRUE;
}

function update_ch_company_entries() {
    global $databaseMain;
    $query = "UPDATE `Companies` SET `Company Name` = '%s',
                `Address` = '%s',
                `Contact Name` = '%s %s',
                `Contact Number` = '%s',
                `Contact Designation` = '%s',
                `Sponsorship Category` = '%s',
                `Sponsorship For` = '%s',
                `Probability Index ID` = '%s' WHERE `Companies`.`Company ID` = %s";
    $query = sprintf($query, $_POST["CompanyName"], $_POST["Address"], $_POST["ContactFirstName"], $_POST["ContactLastName"], $_POST["ContactNumber"], $_POST["ContactDesignation"], $_POST["SCategory"], $_POST["SponsorshipFor"], $_POST["ProbabilityIndex"], $_POST["coid"]);
    mysqli_query($databaseMain, $query);

    $query = "SELECT * FROM `Companies` WHERE `Company Name` = '%s'";
    $query = sprintf($query, $_POST["CompanyName"]);
    $curr_co = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    $query = "SELECT * FROM `StudentSenior` WHERE `Student ID` = '%s'";
    $query = sprintf($query, $_SESSION["id"]);
    $curr_stud = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    global $NOTIFICATION_CODES;
    $code = $NOTIFICATION_CODES["Company Details Changed"];
    $notif_data = create_notification_data($code, $curr_co, $curr_stud);
    create_notifications_seniors($code, $notif_data, $curr_co["Company ID"]);
    create_notifications_juniors($curr_co["Company ID"], $code, $notif_data, $curr_co["Company ID"]);
}

function validate_stud_alloc_entries() {
    global $databaseMain;
    $query = "SELECT COUNT(*) FROM `StudentVolunteer` WHERE FALSE";

    foreach ($_POST["volunteers"] as $volun) {
        if (!filter_var($volun, FILTER_VALIDATE_INT)) {
            return "Unknown Student ID " . $volun;
        }
        $query = $query . " OR `Student ID`='$volun'";
    }
    unset($volun);

    $query = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    if ($query["COUNT(*)"] == array_count_values($input)) {
        return "Unknown Student ID(s) used";
    }

    return TRUE;
}

function update_stud_alloc_entries() {
    global $databaseMain;
    $query = "DELETE FROM `CompanyStudentAllocations` WHERE `Company ID` = '{$_SESSION["coid"]}'";
    mysqli_query($databaseMain, $query);

    $query = "INSERT INTO `CompanyStudentAllocations` (`Student ID`, `Company ID`) VALUES ('%s', '{$_SESSION["coid"]}')";
    foreach ($_POST["volunteers"] as $value) {
        $query_run = sprintf($query, $value);
        mysqli_query($databaseMain, $query_run);
    }

    $query = "SELECT * FROM `Companies` WHERE `Company ID` = '%s'";
    $query = sprintf($query, $_SESSION["coid"]);
    $curr_co = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    $query = "SELECT * FROM `StudentSenior` WHERE `Student ID` = '%s'";
    $query = sprintf($query, $_SESSION["id"]);
    $curr_stud1 = mysqli_fetch_array(mysqli_query($databaseMain, $query));
    global $NOTIFICATION_CODES;
    $code = $NOTIFICATION_CODES["New Student Company Allocation Made"];
    $notif_data = create_notification_data($code, $curr_stud1, $curr_co);
    create_notifications_seniors($code, $notif_data, $curr_co["Company ID"]);
    create_notifications_juniors($curr_co["Company ID"], $code, $notif_data, $curr_co["Company ID"]);
}

if ($_SESSION["student"] != $SENIOR_LOGIN) {
    header("Location: volunredirect.php");
    die();
}

if (isset($_POST["clear-all-notif"]) && $_POST["clear-all-notif"] == $_SESSION["oldsalt"]) {
    $clear_notif_query = "DELETE FROM `Notifications` WHERE `Student ID` = '{$_SESSION["id"]}'";
    mysqli_query($databaseMain, $clear_notif_query);
} else if (isset($_POST["notif-id"]) && filter_var($_POST["notif-id"], FILTER_VALIDATE_INT) &&
        isset($_POST["coid"]) && filter_var($_POST["coid"], FILTER_VALIDATE_INT) &&
        isset($_POST["show"]) && filter_var($_POST["show"], FILTER_VALIDATE_REGEXP, 
                array("options" => array('regexp' => '/[\d]+\.[\d]+/')))) {
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
                    $_SESSION["page"] = $PAGES["Senior"];
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
                    $_SESSION["page"] = $PAGES["Senior"];
                    $_SESSION["msg"] = "The response has been recorded.";
                } else {
                    $_SESSION["page"] = $_POST["set"];
                    $_SESSION["msg"] = $msg;
                }
                break;
            } case $PAGES["New Company"] : {
                $msg = validate_new_company_entries();
                if ($msg === TRUE) {
                    update_new_company_entries();
                    $_SESSION["page"] = $PAGES["Senior"];
                    $_SESSION["msg"] = "The new company has been recorded.";
                } else {
                    $_SESSION["page"] = $_POST["set"];
                    $_SESSION["msg"] = $msg;
                }
                break;
            } case $PAGES["Change Company"] : {
                $msg = validate_ch_company_entries();
                if ($msg === TRUE) {
                    update_ch_company_entries();
                    $_SESSION["page"] = $PAGES["Senior"];
                    $_SESSION["msg"] = "The company changes have been recorded.";
                } else {
                    $_SESSION["page"] = $_POST["set"];
                    $_SESSION["msg"] = $msg;
                }
                break;
            } case $PAGES["Student Allocation"] : {
                $msg = validate_stud_alloc_entries();
                if ($msg === TRUE) {
                    update_stud_alloc_entries();
                    $_SESSION["page"] = $PAGES["Senior"];
                    $_SESSION["msg"] = "The student company allocations have been recorded.";
                } else {
                    $_SESSION["page"] = $_POST["set"];
                    $_SESSION["msg"] = $msg;
                }
                break;
            }
    }
} else {
    $_SESSION["page"] = $PAGES["Senior"];
}
unset($_POST);
unset($_GET);
header("Location: senior.php");
die();
?>
