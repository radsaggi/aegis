<?php

// $ID
// id = Student ID
// HTTP_USER_AGENT = md5(HTTP_USER_AGENT)
// views -> add 1 each time
// salt -> regenerate each time
// oldsalt -> the previous salt value
// page -> the current page to be displayed
// default page -> volunteer.php or senior.php
// redirect page -> volunredirect.php or seniorredirect.php
// student -> the type of student who has logged in
// coid -> the company that is displayed
// coshow -> the eact details of the company that is displayed
// SESSION_ID = sha1(%id%HTTP_USER_AGENT%views%salt)

ini_set("mysqli.reconnect", "1");

if (!isset($PAGES)) {
    global $PAGES;
    $PAGES = array(
        "Volunteer" => "volunteer.php",
        "Volunteer Redirect" => "volunredirect.php",
        "Senior" => "senior.php",
        "Senior Redirect" => "seniorredirect.php",
        "Account Settings" => "accsettings.php",
        "Response" => "response.php",
        "New Company" => "newcompany.php",
        "Change Company" => "chcompany.php",
        "Student Allocation" => "studentalloc.php",
        "Student Deallocation" => "studdealloc.php",
        "Logout" => "logout.php",
        "Register" => "register.php"
    );
}
if (!isset($NOTIFICATION_CODES)) {
    global $NOTIFICATION_CODES;
    $NOTIFICATION_CODES = array(
        //Remember to reflect changes in the function below and in notification list
        "New Company Created" => "NewCompany",
        "Company Details Changed" => "ChCompany",
        "New Student Company Allocation Made" => "NewAlloctn",
        "Student Allocation Removed" => "DeAlloctn",
        "New Response Received" => "NewRespons"
    );
}
if (!isset($SENIOR_LOGIN) || !isset($VOLUNTEER_LOGIN)) {
    global $SENIOR_LOGIN;
    $SENIOR_LOGIN = "Senior";
    global $VOLUNTEER_LOGIN;
    $VOLUNTEER_LOGIN = "Volunteer";
}
if (!function_exists("create_notification_data")) {
    /* Arguments change with the notification code
     * New Response Received        1) Response
     *                              2) Company
     *                              3) Student
     * 
     * New Company Created          1) Company
     *                              2) StudentSenior
     * 
     * Company Details Changed      1) Company
     *                              2) StudentSenior
     * 
     * New Student Company Allocation Made
     *                              1) StudentSenior
     *                              2) Company
     */

    function create_notification_data($code) {
        global $NOTIFICATION_CODES;
        $arg = func_get_args();

        switch ($code) {
            case $NOTIFICATION_CODES["New Response Received"] :
                $ret["title"] = $arg[2]["Company Name"];
                $ret["message"] = "{$arg[3]["Student Name"]} submitted a new response for {$arg[2]["Company Name"]}";
                $ret["show"] = "3.{$arg[1]["Response ID"]}";
                break;
            case $NOTIFICATION_CODES["New Company Created"] :
                $ret["title"] = $arg[1]["Company Name"];
                $ret["message"] = "{$arg[2]["Student Name"]} created a new company - {$arg[1]["Company Name"]}";
                $ret["show"] = "1.0";
                break;
            case $NOTIFICATION_CODES["Company Details Changed"] :
                $ret["title"] = $arg[1]["Company Name"];
                $ret["message"] = "{$arg[2]["Student Name"]} changed details of {$arg[1]["Company Name"]}";
                $ret["show"] = "1.0";
                break;
            case $NOTIFICATION_CODES["New Student Company Allocation Made"]:
                $ret["title"] = $arg[2]["Company Name"];
                $ret["message"] = "New allocations were made to {$arg[2]["Company Name"]} by {$arg[1]["Student Name"]}";
                $ret["show"] = "2.0";
                break;
            default:
                $ret["title"] = "Error, bug found";
                $ret["message"] = "Wrong code submitted in redirect file.... Please fix me!!!!";
                $ret["show"] = "";
                break;
        }

        return $ret;
    }

}
if (!function_exists("destroy_session")) {

    function destroy_session() {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
        session_destroy();
        session_write_close();
    }

}

/*
  ini_set("session.use_cookies", "on");
  ini_set("session.use_trans_sid", "on");
  ini_set("session.use_onlycookies", "on");
  ini_set("session.use_trans_sid", "on");

  session_cache_limiter("nocache");
 */

session_start();


if (!isset($_SESSION["id"]) || !isset($_SESSION["HTTP_USER_AGENT"]) ||
        !isset($_SESSION["views"]) || !isset($_SESSION["salt"]) || !isset($_SESSION["page"])) {
    //Variables are not found, Maybe first access to website 

    if (!isset($ID)) {
        //attempt to hack?
        //or attempt to go back after logging out
        destroy_session();
        if (!isset($login)) {
            header("Location: index.php?msg=Please%20log%20in...&color=000000");
            die();
        } else {
            $login = TRUE;
        }
        return;
    }

    //First access to the website  
    $_SESSION["views"] = 0;
    $_SESSION["id"] = $ID;
    $_SESSION["coid"] = 0;
    $_SESSION["coshow"] = "";
    $_SESSION["oldsalt"] = "";
    if ($ID < 100) {
        $_SESSION["student"] = $SENIOR_LOGIN;
        $_SESSION["database table"] = "StudentSenior";
        $_SESSION["page"] = $PAGES["Senior"];
        $_SESSION["default page"] = $PAGES["Senior"];
        $_SESSION["redirect page"] = $PAGES["Senior Redirect"];
    } else {
        $_SESSION["student"] = $VOLUNTEER_LOGIN;
        $_SESSION["database table"] = "StudentVolunteer";
        $_SESSION["page"] = $PAGES["Volunteer"];
        $_SESSION["default page"] = $PAGES["Volunteer"];
        $_SESSION["redirect page"] = $PAGES["Volunteer Redirect"];
    }
    unset($ID);
    //Rest variales will be set below
} else if (session_id() != sha1("%{$_SESSION["id"]}%{$_SESSION["HTTP_USER_AGENT"]}%{$_SESSION["views"]}%{$_SESSION["salt"]}")) {
    //Variables are intact yet the hash does not match! Attempt to hack for sure!
    destroy_session();
    header("Location: index.php?msg=Error%20logging%20in.%20Try%20clearing%20cookies...&color=ff0000");
    exit;
}

$_SESSION["HTTP_USER_AGENT"] = md5($_SERVER["HTTP_USER_AGENT"]);
$_SESSION["views"]++;
$_SESSION["oldsalt"] = $_SESSION["salt"];
$_SESSION["salt"] = strtr(base64_encode(mcrypt_create_iv(20, MCRYPT_DEV_URANDOM)), "+", ".");
$session_id = sha1("%{$_SESSION["id"]}%{$_SESSION["HTTP_USER_AGENT"]}%{$_SESSION["views"]}%{$_SESSION["salt"]}");
session_id($session_id);
setcookie(session_name(), $session_id, time() + 1440);
?>