
<?php

require_once './session.php';
require "./dbconnector.php";

db_disconnect();

destroy_session();

$msg_str="Logged out successfully.";
header("Location: index.php?msg=$msg_str&color=00ff00");
die();

?>