<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->

<?php
require_once './session.php';
require_once './dbconnector.php';
?>


<!-- form
        Company ID
        DateMonth
        DateDay
        DateYear
        Response
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
            position:relative;
            height:1%
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
            margin-top:10px;
            height:1%
        }

        form li div {
            display:inline-block;
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

        select.select {
            color:#333;
            font-size:100%;
            margin:1px 0;
            padding:1px 0 0;
            background:url(../../../images/shadow.gif) repeat-x top #fff;
            border-bottom:1px solid #ddd;
            border-left:1px solid #c3c3c3;
            border-right:1px solid #c3c3c3;
            border-top:1px solid #7c7c7c
        }

        select.select[class] {
            margin:0;
            padding:1px 0
        }

        select.medium {
            width:50%
        }

        textarea.medium {
            height:10em
        }

        form .guidelines {
            background:#f5f5f5;
            border:1px solid #e6e6e6;
            color:#444;
            font-size:80%;
            left:100%;
            line-height:130%;
            margin:0 0 0 8px;
            padding:8px 10px 9px;
            position:absolute;
            top:0;
            visibility:hidden;
            width:42%;
            z-index:1000
        }

        form .guidelines small {
            font-size:105%
        }

        form li:hover .guidelines {
            visibility:visible
        }
    </style>

    <h1><a>Company Response</a></h1>
    <form id="form_696577" class="appnitro"  method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <div class="form_description">
            <h2>Company Response</h2>
            <p>Enter the company response here..... <br />
                <?php
                if (isset($_SESSION["msg"])) {
                    echo "<font color=\"#ff0000\">{$_SESSION["msg"]}</font>";
                    unset($_SESSION["msg"]);
                }
                ?>
            </p>
        </div>						
        <ul >

            <li id="li_3" >
                <label class="description" for="element_3">Company ID </label>
                <div>
                    <select class="element select medium" id="element_3" name="CompanyID"> 
                        <?php
                        if ($_SESSION["student"] == $SENIOR_LOGIN) {
                            $query = "SELECT `Company Name`, `Company ID` from `Companies`";
                        } else {
                            $query = "SELECT `Company Name`, `Companies`.`Company ID` from `Companies`, `CompanyStudentAllocations`" .
                                    " WHERE `Companies`.`Company ID` = `CompanyStudentAllocations`.`Company ID` " .
                                    " AND `CompanyStudentAllocations`.`Student ID` = {$_SESSION["id"]}";
                        }
                        $query = mysqli_query($databaseMain, $query);

                        $data = mysqli_fetch_array($query);
                        while ($data) {
                            $html_comm = "<option value=\"%s\">%s</option>";
                            $html_comm = sprintf($html_comm, $data["Company ID"], $data["Company Name"]);
                            echo $html_comm;
                            $data = mysqli_fetch_array($query);
                        }
                        ?>  
                    </select>
                </div><p class="guidelines" id="guide_3"><small>Choose the Company ID</small></p> 
            </li>		
            <li id="li_1" >
                <label class="description" for="element_1">Date </label>
                <span>
                    <input id="element_1_2" name="DateDay" class="element text" size="2" maxlength="2" value="" type="text"> /
                    <label for="element_1_2">DD</label>
                </span>
                <span>
                    <input id="element_1_1" name="DateMonth" class="element text" size="2" maxlength="2" value="" type="text"> /
                    <label for="element_1_1">MM</label>
                </span>
                <span>
                    <input id="element_1_3" name="DateYear" class="element text" size="4" maxlength="4" value="" type="text">
                    <label for="element_1_3">YYYY</label>
                </span>

            </li>		
            <li id="li_2" >
                <label class="description" for="element_2">Response </label>
                <div>
                    <textarea id="element_2" name="Response" class="element textarea medium"></textarea> 
                </div><p class="guidelines" id="guide_2">
                    <small>Do not use special Characters other than , . ( ) ! -</small></p> 
            </li>               
            <li id="li_3" >
                <label class="description" for="element_1">Next Appointment Date </label>
                <span>
                    <input id="element_3_2" name="NextDateDay" class="element text" size="2" maxlength="2" value="" type="text"> /
                    <label for="element_3_2">DD</label>
                </span>
                <span>
                    <input id="element_3_1" name="NextDateMonth" class="element text" size="2" maxlength="2" value="" type="text"> /
                    <label for="element_3_1">MM</label>
                </span>
                <span>
                    <input id="element_3_3" name="NextDateYear" class="element text" size="4" maxlength="4" value="" type="text">
                    <label for="element_3_3">YYYY</label>
                </span>

            </li>		

            <li class="buttons">
                <input name="set" type="hidden" value="<?php echo "{$PAGES["Response"]}" ?>"/>
                <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
            </li>
        </ul>
    </form>	
    <form method="post" action="<?php echo "{$_SESSION["redirect page"]}" ?>">
        <input name="page" type="hidden" value="<?php echo "{$_SESSION["default page"]}" ?>"/>
        <input id="back-button" name="back" value="< Back" type="submit" />
    </form>


</div>
