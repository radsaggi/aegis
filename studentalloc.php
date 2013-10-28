
<?php
require_once './session.php';
require_once './dbconnector.php';
?>



<!-- form
        StudentID
        CompanyID
-->
<div id="form_container">

    <style type="text/css" scoped>
        body {
            background:#f60;
            margin:8px 0 16px;
            text-align:center;
            font:small "Lucida Grande",Tahoma,Arial,Verdana,sans-serif
        }

        #form_container {
            background:#fff;
            margin:0 auto;
            text-align:left;

        }

        form.appnitro {
            margin:20px 20px 0;
            padding:0 0 20px
        }

        h1 {
            margin:0;
            min-height:0;
            padding:0;
            text-decoration:none;
            text-indent:-8000px;
            background:#f90
        }

        h1 a {
            display:block;
            height:100%;
            min-height:40px;
            overflow:hidden
        }

        .appnitro {
            font:small Lucida Grande,Tahoma,Arial,Verdana,sans-serif
        }

        .appnitro li {
            width:61%
        }

        form ul {
            font-size:100%;
            list-style-type:none;
            margin:0;
            padding:0;
            width:100%
        }

        form li {
            display:block;
            margin:0;
            padding:4px 5px 2px 9px;
            position:relative
        }

        form li:after {
            clear:both;
            content:".";
            display:block;
            height:0;
            visibility:hidden
        }

        .buttons:after {
            clear:both;
            content:".";
            display:block;
            height:0;
            visibility:hidden
        }

        .buttons {
            clear:both;
            display:block;
            margin-top:10px
        }

        .form_description {
            border-bottom:1px dotted #ccc;
            clear:both;
            display:inline-block;
            margin:0 0 1em
        }

        .form_description[class] {
            display:block
        }

        .form_description h2 {
            clear:left;
            font-size:160%;
            font-weight:400;
            margin:0 0 3px
        }

        .form_description p {
            font-size:95%;
            line-height:130%;
            margin:0 0 12px
        }

        input.button_text {
            overflow:visible;
            padding:0 7px;
            width:auto
        }

        .buttons input {
            font-size:120%;
            margin-right:5px
        }

        label.description {
            border:none;
            color:#222;
            display:block;
            font-size:95%;
            font-weight:700;
            line-height:150%;
            padding:0 0 1px
        }

        form li:hover .guidelines {
            visibility:visible
        }

        ul.checkbox-grid li{
            display: block;
            float: left;
            width: 30%;
        }
    </style>


    <?php
    if ($_SESSION["student"] != $SENIOR_LOGIN) {
        return;
    }

    $query = "SELECT `Company Name` FROM `Companies` WHERE `Company ID` ='{$_SESSION["coid"]}'";
    $comp_data = mysqli_query($databaseMain, $query);
    if (mysqli_num_rows($comp_data) == 0) {
        ?>
        <center>Select a company first</center>
        <form method="post" action="<?php echo $_SESSION["redirect page"]; ?>">
            <input name="page" type="hidden" value="<?php echo $_SESSION["default page"]; ?>" />
            <input name="back" value="< Back" type="submit" />
        </form>
        <?php
        return;
    }
    $comp_data = mysqli_fetch_array($comp_data);
    $query = "SELECT S.`Student ID` , S.`Student Name` , A.`Company ID`
            FROM (`StudentVolunteer` AS S)
            LEFT JOIN (`CompanyStudentAllocations` AS A) ON (S.`Student ID` = A.`Student ID` AND A.`Company ID` = '{$_SESSION["coid"]}')";
    $stud_alloc_data = mysqli_query($databaseMain, $query);
    ?>



    <h1><a>Company Allocation</a></h1>
    <form id="form_696599" class="appnitro"  method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <div class="form_description">
            <h2>Company Allocation</h2>
            <p>Enter the allocation details for <?php echo $comp_data["Company Name"]; ?>.... <br/>
                <?php
                if (isset($_SESSION["msg"])) {
                    echo "<font color=\"#ff0000\">{$_SESSION["msg"]}</font>";
                    unset($_SESSION["msg"]);
                }
                ?>
            </p>
        </div>						
        <ul >

            <li>
                <label class="description" for="element_1">Select Volunteers </label>
                <ul class="checkbox-grid">
                    <?php
                    $stud_data = mysqli_fetch_array($stud_alloc_data);
                    while ($stud_data) {
                        ?>
                        <li><label><input type="checkbox" name="volunteers[]" value="<?php echo $stud_data["Student ID"]; ?>" <?php echo $stud_data["Company ID"] == $_SESSION["coid"] ? "checked" : ""; ?>><?php echo $stud_data["Student Name"]; ?></label></li>
                        <?php
                        $stud_data = mysqli_fetch_array($stud_alloc_data);
                    }
                    ?>
                </ul>
            </li>

            <li class="buttons">
                <input name="set" type="hidden" value="<?php echo "{$PAGES["Student Allocation"]}" ?>"/>
                <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
            </li>
        </ul>
    </form>	

    <form method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <input name="page" type="hidden" value="<?php echo "{$_SESSION["default page"]}" ?>"/>
        <input id="back-button" name="back" value="< Back" type="submit" />
    </form>
</div>