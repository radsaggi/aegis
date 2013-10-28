<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<?php
require_once './session.php';
require './dbconnector.php';

$data = mysqli_query($databaseMain, "SELECT * FROM `{$_SESSION["database table"]}` WHERE `Student ID` = '{$_SESSION["id"]}'");
$data = mysqli_fetch_array($data);

if (str_word_count($data["Student Name"]) > 1) {
    $user_name = explode(" ", $data["Student Name"]);
    $data["First Name"] = $user_name[0];
    $data["Last Name"] = $user_name[1];
} else {
    $data["First Name"] = $data["Student Name"];
}
?>


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

        form li div {
            color:#444;
            margin:0 4px 0 0;
            padding:0 0 8px
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
            background:url(./images/accsettings/shadow.gif) repeat-x top #fff;
            border-bottom:1px solid #ddd;
            border-left:1px solid #c3c3c3;
            border-right:1px solid #c3c3c3;
            border-top:1px solid #7c7c7c;
            color:#333;
            font-size:100%;
            margin:0;
            padding:2px 0
        }

        input.medium {
            width:50%
        }

        form li:hover .guidelines {
            visibility:visible
        }
    </style>
    <h1><a>Account Details</a></h1>

    <form id="form_696599" class="appnitro"  method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <div class="form_description">
            <h2>Account Details</h2>
            <p>Alter the account details here..... <br />
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
                <label class="description" for="FirstName">First Name </label>
                <div>
                    <input id="element_1" name="FirstName" class="element text medium" type="text" maxlength="255" value="<?php if (isset($data["First Name"])) echo $data["First Name"]; ?>"/> 
                </div> 
            </li>		<li id="li_2" >
                <label class="description" for="LastName">Last Name </label>
                <div>
                    <input id="element_2" name="LastName" class="element text medium" type="text" maxlength="255" value="<?php if (isset($data["Last Name"])) echo $data["First Name"]; ?>"/> 
                </div> 
            </li>		<li id="li_3" >
                <label class="description" for="RollNumber">Roll Number </label>
                <div>
                    <input id="element_3" name="RollNumber" class="element text medium" type="text" maxlength="255" value="<?php if (isset($data["College Roll Number"])) echo $data["College Roll Number"]; ?>"/> 
                </div> 
            </li>		<li id="li_4" >
                <label class="description" for="Phone">Contact Number </label>
                <div>
                    <input id="element_4" name="Phone" class="element text medium" type="text" maxlength="255" value="<?php if (isset($data["Contact Number"])) echo $data["Contact Number"]; ?>"/> 
                </div> 
            </li>		<li id="li_5" >
                <label class="description" for="CollegeEmail">IITP email ID </label>
                <div>
                    <input id="element_5" name="CollegeEmail" class="element text medium" type="text" maxlength="255" value="<?php if (isset($data["College Email"])) echo $data["College Email"]; ?>"/> 
                </div> 
            </li>		<li id="li_6" >
                <label class="description" for="AltEmail">Alternate email ID </label>
                <div>
                    <input id="element_6" name="AltEmail" class="element text medium" type="email" maxlength="255" value="<?php if (isset($data["Alternate Email"])) echo $data["Alternate Email"]; ?>"/> 
                </div> 
            </li>

            <li class="buttons">
                <input type="hidden" name="form_id" value="696599" />
                <input name="set" type="hidden" value="<?php echo "{$PAGES["Account Settings"]}" ?>"/>
                <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
            </li>
        </ul>
    </form>	


    <form method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <input name="page" type="hidden" value="<?php echo "{$_SESSION["default page"]}" ?>"/>
        <input id="back-button" name="back" value="< Back" type="submit" />
    </form>
</div>


