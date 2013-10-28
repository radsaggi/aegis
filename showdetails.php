<!DOCTYPE html>

 <HTML>
   <HEAD>
     <TITLE> The display page </TITLE>
     
     <link rel="stylesheet" type="text/css" href="codetails.css">
   </HEAD>
   
   <BODY>
     <?php 
     include_once './dbconnector.php';
      if (array_key_exists("type", $_GET)) {
	$type=$_GET["type"];
      } else {
	$type="NONE";
      }
      
      switch ($type) {
	case "company"  : 
	  $_GET["coid"]=$_GET["id"]; 		
	  $require="codetails.php"; 	  	
	  $width=1300;
	  break;
	case "volunteer": 
	  $_GET["volunteerid"]=$_GET["id"];  	
	  $require="volunteerdetails.php"; 	
	  $width=600;
	  break;
	case "category" : 
	  $_GET["sponcategid"]=$_GET["id"];	
	  $require="sponcategdetails.php";	
	  $width=500;
	  break;
	default : echo "ERROR No display type specified"; die();
      }
      
      
     ?>
     
     <table style="width:<?php echo $width; ?>px;margin-right:auto;margin-left:auto;">
       <tr>
	 <td> <?php require $require; ?> </td>
       </tr>
     </table>
     
   </BODY>
 </HTML>
      
      