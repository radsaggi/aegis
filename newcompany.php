<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->

<!-- form
        CompanyName
        Address
        ContactFirstName
        ContactLastName
        ContactNumber
        ContactDesignation
        SCategory
        SponsorshipFor
        ProbabilityIndex
-->

<?php
require_once './session.php';
require_once './dbconnector.php';
?>

<div id="form_container">

    <style type="text/css" scoped>
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

        form li div {
            color:#444;
            margin:0 4px 0 0;
            padding:0 0 8px
        }

        form li span {
            color:#444;
            float:left;
            margin:0 4px 0 0;
            padding:0 0 8px
        }

        form li span label {
            clear:both;
            color:#444;
            display:block;
            font-size:9px;
            line-height:9px;
            margin:0;
            padding-top:3px
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

        input.text {
            background:url(../../../images/shadow.gif) repeat-x top #fff;
            border-bottom:1px solid #ddd;
            border-left:1px solid #c3c3c3;
            border-right:1px solid #c3c3c3;
            border-top:1px solid #7c7c7c;
            color:#333;
            font-size:100%;
            margin:0;
            padding:2px 0
        }

        textarea.textarea {
            background:url(../../../images/shadow.gif) repeat-x top #fff;
            border-bottom:1px solid #ddd;
            border-left:1px solid #c3c3c3;
            border-right:1px solid #c3c3c3;
            border-top:1px solid #7c7c7c;
            color:#333;
            margin:0;
            width:99%;
            font:100% "Lucida Grande",Tahoma,Arial,Verdana,sans-serif
        }

        input.medium {
            width:50%
        }

        textarea.medium {
            height:10em
        }

        form li:hover .guidelines {
            visibility:visible
        }
    </style>

    <?php
    if ($_SESSION["student"] != $SENIOR_LOGIN) {
        return;
    }
    ?>

    <h1><a>New Company</a></h1>
    <form id="form_696599" class="appnitro" method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <div class="form_description">
            <h2>New Company</h2>
            <p>Enter the new company details..... <br />
                <?php
                if (isset($_SESSION["msg"])) {
                    echo "<font color=\"#ff0000\">{$_SESSION["msg"]}</font>";
                    unset($_SESSION["msg"]);
                }
                ?>
            </p>
        </div>						
        <ul >

            <li id="li_1" >
                <label class="description" for="element_1">Company Name </label>
                <div>
                    <input id="element_1" name="CompanyName" class="element text medium" type="text" maxlength="255" value=""/> 
                </div> 
            </li>		
            <li id="li_3" >
                <label class="description" for="element_3">Address </label>
                <div>
                    <textarea id="element_3" name="Address" class="element textarea medium"></textarea> 
                </div> 
            </li>		
            <li id="li_4" >
                <label class="description" for="element_4">Contact Name </label>
                <span>
                    <input id="element_4_1" name= "ContactFirstName" class="element text" maxlength="255" size="8" value=""/>
                    <label>First</label>
                </span>
                <span>
                    <input id="element_4_2" name= "ContactLastName" class="element text" maxlength="255" size="14" value=""/>
                    <label>Last</label>
                </span> 
            </li>		
            <li id="li_5" >
                <label class="description" for="element_5">Contact Number </label>
                <div>
                    <input id="element_5" name="ContactNumber" class="element text medium" type="text" maxlength="255" value=""/> 
                </div> 
            </li>		
            <li id="li_7" >
                <label class="description" for="element_7">Contact Designation </label>
                <div>
                    <input id="element_7" name="ContactDesignation" class="element text medium" type="text" maxlength="255" value=""/> 
                </div> 
            </li>		
            <li id="li_10" >
                <label class="description" for="element_10">Potential Sponsorship Category </label>
                <div>
                    <select id="element_10" name="SCategory">
                        <?php
                        $sponsorship_category_query = "SELECT * FROM `SponsorshipCategories`";
                        $sponsorship_category_query = mysqli_query($databaseMain, $sponsorship_category_query);
                        $sponsorship_category_data = mysqli_fetch_array($sponsorship_category_query);
                        while ($sponsorship_category_data) {
                            $option_cmd = "<option value=\"%s\"> %s </option>";
                            $option_cmd = sprintf($option_cmd, $sponsorship_category_data["Category ID"], $sponsorship_category_data["Category Name"]);
                            echo $option_cmd;
                            $sponsorship_category_data = mysqli_fetch_array($sponsorship_category_query);
                        }
                        ?>
                    </select>
                </div> 
            </li>		
            <li id="li_13" >
                <label class="description" for="element_13">Sponsorship For:</label>
                <div>
                    <input id="element_13" name="SponsorshipFor" class="element textarea medium"></textarea> 
                </div> 
            </li>
            <li id="li_14" >
                <label class="description" for="element_14">Probability Index</label>
                <div>
                    <select id="element_14" name="ProbabilityIndex">
                        <?php
                        $sponsorship_category_query = "SELECT * FROM `Probability Index`";
                        $sponsorship_category_query = mysqli_query($databaseMain, $sponsorship_category_query);
                        $sponsorship_category_data = mysqli_fetch_array($sponsorship_category_query);
                        while ($sponsorship_category_data) {
                            $option_cmd = "<option value=\"%s\"> %s </option>";
                            $option_cmd = sprintf($option_cmd, $sponsorship_category_data["Probability Index ID"], $sponsorship_category_data["Description"]);
                            echo $option_cmd;
                            $sponsorship_category_data = mysqli_fetch_array($sponsorship_category_query);
                        }
                        ?>
                    </select>
                </div> 
            </li>
            <li class="buttons">
                <input type="hidden" name="set" value="<?php echo $PAGES["New Company"] ?>" />
                <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
            </li>
        </ul>
    </form>	
    <form method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <input name="page" type="hidden" value="<?php echo "{$_SESSION["default page"]}" ?>"/>
        <input id="back-button" name="back" value="< Back" type="submit" />
    </form>
</div>

