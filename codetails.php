

<?php
if (!array_key_exists("coid", $_GET)) {
    echo "No company specified";
    return;
}

if (!array_key_exists("show", $_GET)) {
    $_GET["show"]="1.0";
}
$dot_pos = strpos($_GET["show"], ".");
$show1 = substr($_GET["show"], 0, 1);
$show2 = substr($_GET["show"], $dot_pos + 1, 1);

include_once './dbconnector.php';

$id = $_GET["coid"];
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    echo "No company specified";
    return;
}
$count = mysqli_fetch_array(mysqli_query($databaseMain, "SELECT COUNT(*) FROM `Companies` WHERE `Company ID` = " . $id));

if ($count["COUNT(*)"] < 1) {
    echo "No data exists for the Company ID : " . $id;
    die();
} else if ($count["COUNT(*)"] > 1) {
    echo "Database corrupt, multiple entries for Company ID : " . $id;
    die();
}
unset($count);

$db_data = mysqli_query($databaseMain, "SELECT * FROM `Companies`, `Probability Index`, `SponsorshipCategories` 
    WHERE `Company ID` = " . $id . " AND `Probability Index`.`Probability Index ID` = `Companies`.`Probability Index ID` AND `Sponsorship Category` = `Category ID`");
$codata = mysqli_fetch_array($db_data);
?>

<div id="codetails">

    <div class="tabGroup">

        <div id="tab3" class="control">
            <input type="radio" name="tabGroup1" id="rad3" class="tab" <?php if ($show1 == "3") echo "checked"; ?>/>
            <?php
            $query = "SELECT COUNT(*) FROM `Responses` WHERE `Company ID` = '$id'";
            $query = mysqli_query($databaseMain, $query);
            $query = mysqli_fetch_array($query);
            ?>
            <label for="rad3">Responses - <?php echo $query["COUNT(*)"]; ?></label>
            <div class="tab-content">
                <div id="responses-div" class="inside-tabGroup">
                    <?php
                    $c = 1;

                    $query = "SELECT * FROM `Responses`
                                JOIN (SELECT * FROM `StudentVolunteer`
                                    UNION ALL
                                    SELECT * FROM `StudentSenior`) AS S 
                                ON (`Responses`.`Student ID` = S.`Student ID` )
                                WHERE `Company ID` = '$id'";
                    $query = mysqli_query($databaseMain, $query);
                    $response_data = mysqli_fetch_array($query);
                    while ($response_data) {
                        ?>
                        <div id="response-tab<?php echo $c; ?>" class="inside-control">
                            <input type="radio" name="response-tabGroup" id="response-rad<?php echo $c; ?>" class="inside-tab" <?php if ($show2 == $response_data["Response ID"]) echo "checked";?>/>
                            <label for="response-rad<?php echo $c; ?>">
                                <span class="inside-dot"><?php echo $c > 9 ? $c : "0" . $c; ?></span>
                                <span class="inside-dot-text"><?php echo $response_data["Date"]; ?></span>
                            </label>
                            <div class="inside-tab-content">
                                <?php
                                $_GET["responseid"] = $response_data["Response ID"];
                                require './responsedetails.php';
                                ?>
                            </div>
                        </div>
                        <?php
                        $c++;
                        $response_data = mysqli_fetch_array($query);
                    }
                    ?>
                </div> 
            </div>

        </div>

        <div id="tab2" class="control">
            <input type="radio" name="tabGroup1" id="rad2" class="tab" <?php if ($show1 == "2") echo "checked"; ?>/>
            <?php
            $query = "SELECT COUNT(*) FROM `CompanyStudentAllocations` WHERE `Company ID` = '$id'";
            $query = mysqli_query($databaseMain, $query);
            $query = mysqli_fetch_array($query);
            ?>
            <label for="rad2">Volunteers - <?php echo $query["COUNT(*)"]; ?></label>
            <div id="volunteer-data" class="tab-content">

                <div id="volunteers-div" class="inside-tabGroup">

                    <?php
                    $c = 1;

                    $query = "SELECT `StudentVolunteer`.* FROM `StudentVolunteer`, `CompanyStudentAllocations` 
                WHERE `Company ID` = '$id' AND `CompanyStudentAllocations`.`Student ID` = `StudentVolunteer`.`Student ID`";
                    $query = mysqli_query($databaseMain, $query);
                    $volunteer_data = mysqli_fetch_array($query);
                    while ($volunteer_data) {
                        ?>
                        <div id="volun-tab<?php echo $c; ?>" class="inside-control">
                            <input type="radio" name="volun-tabGroup" id="volun-rad<?php echo $c; ?>" class="inside-tab" <?php if ($show2 == $volunteer_data["Student ID"]) echo "checked";?>/>
                            <label for="volun-rad<?php echo $c; ?>">
                                <span class="inside-dot"><?php echo $c; ?></span>
                                <span class="inside-dot-text"><?php echo $volunteer_data["Student Name"]; ?></span>
                            </label>
                            <div class="inside-tab-content">
                                <?php
                                $_GET["volunteerid"] = $volunteer_data["Student ID"];
                                require './volunteerdetails.php';
                                ?>
                            </div>
                        </div>
                        <?php
                        $c++;
                        $volunteer_data = mysqli_fetch_array($query);
                    }
                    ?>


                </div>

            </div>
        </div>

        <div id="tab1" class="control">
            <input type="radio" name="tabGroup1" id="rad1" class="tab" <?php if ($show1 == "1") echo "checked"; ?>/>
            <label for="rad1">Company Details</label>
            <div id="company-data" class="tab-content data-table">

                <table id="company-table">
                    <thead>
                        <tr>
                            <th colspan="2"><?php echo $codata["Company Name"] . " (" . $codata["Company ID"] . ") Details" ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="rowlabel">Company ID</td>
                            <td class="rowdata"><?php echo $codata["Company ID"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Company Name</td>
                            <td class="rowdata"><?php echo $codata["Company Name"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Address</td>
                            <td class="rowdata"><?php echo $codata["Address"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Contact Name</td>
                            <td class="rowdata"><?php echo $codata["Contact Name"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Contact Designation</td>
                            <td class="rowdata"><?php echo $codata["Contact Designation"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Contact Number</td>
                            <td class="rowdata"><?php echo $codata["Contact Number"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Last Meeting Date</td>
                            <td class="rowdata"><?php echo $codata["Last Meeting"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Next Meeting Date</td>
                            <td class="rowdata"><?php echo $codata["Next Meeting"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Sponsorship Category</td>
                            <td class="rowdata"><?php echo $codata["Category Name"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Sponsorship For</td>
                            <td class="rowdata"><?php echo $codata["Sponsorship For"] ?></td>
                        </tr>
                        <tr>
                            <td class="rowlabel">Probability Index</td>
                            <td class="rowdata"><?php echo $codata["Description"] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>





</div>
