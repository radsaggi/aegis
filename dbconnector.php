<?php

if (!function_exists("db_disconnect")) {

    function db_disconnect() {
        if (isset($databaseMain)) {
            mysqli_close($databaseMain);
            unset($databaseMain);
        }
    }

}

global $databaseMain;
$databaseMain = mysqli_connect("localhost", "tasker", "BidEd61oWl", "sponsorship");
// Check connection
if (mysqli_connect_errno()) {
    throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
    unset($databaseMain);
}
?>
