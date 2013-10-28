

<?php

if (!array_key_exists("responseid", $_GET)) {
    echo "No Response specified";
    die();
}


$respid = $_GET["responseid"];
if (!filter_var($respid, FILTER_VALIDATE_INT)) {
    echo "No response specified";
    die();
}
$count = mysqli_fetch_array(mysqli_query($databaseMain, "SELECT COUNT(*) FROM `Responses` WHERE `Response ID` = " . $respid));

if ($count["COUNT(*)"] < 1) {
    echo "No data exists for the Response ID : " . $respid;
    die();
} else if ($count["COUNT(*)"] > 1) {
    echo "Database corrupt, multiple entries for Response ID : " . $respid;
    die();
}

$db_data = mysqli_query($databaseMain, "SELECT * FROM `Responses`,`StudentVolunteer`,`StudentSenior` WHERE `Response ID` = '$respid' 
    AND (`Responses`.`Student ID` = `StudentVolunteer`.`Student ID` 
    OR `Responses`.`Student ID` = `StudentSenior`.`Student ID`)");
$responsedata = mysqli_fetch_array($db_data);
?>



<div>

    <table class="data-table">

        <thead>
            <tr>
                <th colspan="2"><?php echo $responsedata["Date"] . "(" . $responsedata["Student Name"] . ") Details" ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="rowlabel">Response ID</td>
                <td class="rowdata"><?php echo $responsedata["Response ID"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Student ID</td>
                <td class="rowdata"><?php echo $responsedata["Student ID"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Student Name</td>
                <td class="rowdata"><?php echo $responsedata["Student Name"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Date</td>
                <td class="rowdata"><?php echo $responsedata["Date"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Meeting Number</td>
                <td class="rowdata"><?php echo $responsedata["Meeting Number"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Response</td>
                <td class="rowdata"><?php echo $responsedata["Response"] ?></td>
            </tr>
        </tbody>
    </table>

</div>