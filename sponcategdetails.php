
 
 <?php 
 
 if (!array_key_exists("sponcategid", $_GET)) {
  echo "No Category specified";
  die();
 }
 
 $id=$_GET["sponcategid"];
 if (!filter_var($id, FILTER_VALIDATE_INT)) {
  echo "No sponsorship category specified";
  die();
 }
 $count=mysqli_fetch_array( mysqli_query($databaseMain, "SELECT COUNT(*) FROM `SponsorshipCategories` WHERE `Category ID` = ".$id) );
 
 if ($count["COUNT(*)"] < 1) {
  echo "No data exists for the Category ID : ".$id;
  die();
 } else if ($count["COUNT(*)"] > 1) {
  echo "Database corrupt, multiple entries for Category ID : ".$id;
  die();
 }
 
 $db_data=mysqli_query($databaseMain, "SELECT * FROM `SponsorshipCategories` WHERE `Category ID` = ".$id);
 $codata=mysqli_fetch_array($db_data);
   ?>
   
     
	 <style>
	   #gradient-style{font-family:"Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size:12px;width:70%;text-align:left;border-collapse:collapse;margin-left:auto;margin-right:auto;margin-top:20px;}
	   #gradient-style th{font-size:16px;font-weight:bold;background:#b9c9fe url("/images/codetails/gradhead.png") repeat-x;border-top:2px solid #d3ddff;border-bottom:1px solid #fff;color:#039;padding:8px;text-align:center;}
	   #gradient-style td{border-bottom:1px solid #fff;color:#669;border-top:1px solid #fff;background:#e8edff url("/images/codetails/gradback.png") repeat-x;padding:8px;}
	   #gradient-style td.rowlabel{width:30%;}
	   #gradient-style td.rowdata{width:70%;padding:10px;}
	   #gradient-style tfoot tr td{background:#e8edff;font-size:12px;color:#99c;}
	   #gradient-style tbody tr:hover td{background:#d0dafd url("/images/codetails/gradhover.png") repeat-x;color:#339;}
	 </style>
	 
	 <table id="gradient-style">
	   <thead>
	     <tr>
	       <th colspan="2"><?php echo $codata["Category Name"]."(".$codata["Category ID"].") Details" ?></th>
	     </tr>
	   </thead>
	   <tbody>
	     <tr>
	       <td class="rowlabel">Category ID</td>
	       <td class="rowdata"><?php echo $codata["Category ID"] ?></td>
	     </tr>
	     <tr>
	       <td class="rowlabel">Category Name</td>
	       <td class="rowdata"><?php echo $codata["Category Name"] ?></td>
	     </tr>
	     <tr>
	       <td class="rowlabel">Minimum Amount</td>
	       <td class="rowdata"><?php echo $codata["Minimum Amount"] ?></td>
	     </tr>
	     <tr>
	       <td class="rowlabel">Maximum Amount</td>
	       <td class="rowdata"><?php echo $codata["Maximum Amount"] ?></td>
	     </tr>
	     <tr>
	       <td class="rowlabel">Incentives</td>
	       <td class="rowdata"><?php echo $codata["Incentives"] ?></td>
	     </tr>
	   </tbody>
	 </table>
	 
