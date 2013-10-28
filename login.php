<?php 

//login_id
//login_pwd



if (!array_key_exists("login_id",$_POST) || !array_key_exists("login_pwd",$_POST)) {
  $msg_str="Please log in first.";
  header("Location: index.php?msg=$msg_str&color=ff0000");
  die();
} 

$username=trim($_POST["login_id"]);
$password=$_POST["login_pwd"];

$db=mysqli_connect("localhost","login_user","hasD78PwD9login","sponsorship");
if (mysqli_connect_errno($db)) {
  $msg_str="Unable to connect to users database. Contact admin. ".mysqli_connect_error();
  header("Location: index.php?msg=$msg_str&color=ff0000");
  die();
}

if (filter_var($username, FILTER_VALIDATE_INT)) {
  $query="SELECT * FROM `users` WHERE `Student ID`=".$username;
} else if (filter_var($username, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/[a-zA-Z]+$/')))) {
  $query="SELECT * FROM `users` WHERE `Student Username`=\"".$username."\"";
} else {
  $_GET["msg"]="Enter your proper Student ID or login name.";
  require "error.php";
  die();
}
$query=mysqli_query($db, $query);
if (!$query) {
  $msg_str="Malformed Query. Contact admin. ";
  header("Location: index.php?msg=$msg_str&color=ff0000");
  die();
}

$query=mysqli_fetch_array($query);
mysqli_close($db);
$hash=crypt($password, $query["Hash"]);

if($hash != $query["Hash"]) {
  header("Location: index.php?msg=Wrong%20Password&color=ff0000");
  die();
}
  
//echo "Password Correct";
unset($_POST);

$ID=$query["Student ID"];
require "session.php";
$ID=$query["Student ID"];
if ($ID < 100) {
  header("Location: senior.php");
} else {
  header("Location: volunteer.php");
}
exit();
?>