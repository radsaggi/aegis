

<?php

if (!array_key_exists("volunteerid", $_GET)) {
    echo "No Student specified";
    die();
}


$id = $_GET["volunteerid"];
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    echo "No volunteer specified";
    die();
}
$count = mysqli_fetch_array(mysqli_query($databaseMain, "SELECT COUNT(*) FROM `StudentVolunteer` WHERE `Student ID` = " . $id));

if ($count["COUNT(*)"] < 1) {
    echo "No data exists for the Student ID : " . $id;
    die();
} else if ($count["COUNT(*)"] > 1) {
    echo "Database corrupt, multiple entries for Student ID : " . $id;
    die();
}

$db_data = mysqli_query($databaseMain, "SELECT * FROM `StudentVolunteer` WHERE `Student ID` = " . $id);
$volunteerdata = mysqli_fetch_array($db_data);
?>



<div>

    <table class="data-table">

        <thead>
            <tr>
                <th colspan="2"><?php echo $volunteerdata["Student Name"] . "(" . $volunteerdata["Student ID"] . ") Details" ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="rowlabel">Student ID</td>
                <td class="rowdata"><?php echo $volunteerdata["Student ID"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Student Name</td>
                <td class="rowdata"><?php echo $volunteerdata["Student Name"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">College Roll Number</td>
                <td class="rowdata"><?php echo $volunteerdata["College Roll Number"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">College Email</td>
                <td class="rowdata"><?php echo $volunteerdata["College Email"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Alternate Email</td>
                <td class="rowdata"><?php echo $volunteerdata["Alternate Email"] ?></td>
            </tr>
            <tr>
                <td class="rowlabel">Contact Number</td>
                <td class="rowdata"><?php echo $volunteerdata["Contact Number"] ?></td>
            </tr>
        </tbody>
    </table>

</div>