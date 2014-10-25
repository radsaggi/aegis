<?php


function validate_all($vars) {
    $show = false;
    $proceed = true;
    $check = function ($var) use ($vars, &$show, &$proceed){
        if (isset($vars[$var]) && $vars[$var]) {
            $show = true;
            return filter_var($vars[$var], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-zA-Z]+$/')));
        } else {
            $proceed = false;
            return false;
        }
    };
    $optional = function($var) use ($vars, &$proceed) {
        if (isset($vars[$var])) {
            return $vars[$var];
        } else {
            $proceed = false;
            return '';
        }
    };
    return array (
        "db-name" => $check("db-name"),
        "db-host" => $check("db-host"),
        "db-user" => $check("db-user"),
        //no need to check this
        "db-pass" => $optional('db-pass'),
        "admin-user" => $check("admin-user"),
        //no need to check this
        "admin-pass" => $optional('admin-pass'),
        "proceed" => $proceed,
        "show" => $show,
    );
}

$args = validate_all($_POST);

//Check a variable for highlighting field
function ch_high($var) {
    global $args;
    return $args['show'] && !$args[$var];
}

//Check a variable for display
function ch_disp($var) {
    global $args;
    return $args['show'] && $args[$var];
}

if ($args["proceed"]) {
    $schema_script = "./schema.sql";
    $users_script = "./users.sql";
    $encrypt_cost = 10;

    //Create the database
    $db = new mysqli($args['db-host'], $args['db-user'], '', '');
    if ($db->connect_error) {
        $args['error'] = 'Connect Error (' . $db->connect_errno . ') ' . $db->connect_error;
        goto end;
    }

    $query = "CREATE DATABASE " . $args['db-name'];
    $db->query($query);

    if ($db->errno) {
        $args['error'] = 'Error creating database (' . $db->errno . ') ' . $db->error;
        goto end;
    }
    $db->close();
    unset($db);

    //Create the databse schema
    $db = new mysqli($args['db-host'], $args['db-user'], '', $args['db-name']);
    if ($db->connect_error) {
        $args['error'] = 'Connect Error (' . $db->connect_errno . ') ' . $db->connect_error;
        goto end;
    }

    $sql = file_get_contents($schema_script);
    $db->multi_query($sql);

    while ($db->next_result()) if ($db->errno) {
        $args['error'] = 'Error creating tables (' . $db->errno . ') ' . $db->error;
        goto end;
    }
    $db->close();
    unset($db);

    //Create the users
    $db = new mysqli($args['db-host'], $args['db-user'], '', $args['db-name']);
    if ($db->connect_error) {
        $args['error'] = 'Connect Error (' . $db->connect_errno . ') ' . $db->connect_error;
        goto end;
    }

    $sql = file_get_contents($users_script);
    $patterns = array (
        0 => '/%db-name%/',
        1 => '/%db-host%/',
    );
    $substuts = array (
        0 => $args['db-name'],
        1 => $args['db-host'],
    );
    $sql = (preg_replace($patterns, $substuts, $sql));
    $db->multi_query($sql);

    while ($db->next_result()) if ($db->errno) {
        $args['error'] = 'Error creating mysql users (' . $db->errno . ') ' . $db->error;
        goto end;
    }
    $db->close();
    unset($db);

    //Create the default admin user
    $db = new mysqli($args['db-host'], $args['db-user'], '', $args['db-name']);
    if ($db->connect_error) {
        $args['error'] = 'Connect Error (' . $db->connect_errno . ') ' . $db->connect_error;
        goto end;
    }

    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    $salt = sprintf("$2y$%02d$", $encrypt_cost) . $salt;
    $hash = crypt($args['admin-pass'], $salt);

    $query = "INSERT INTO `StudentSenior` (`Student ID`,`Student Name`) VALUES ('1','{$args['admin-user']}'); ".
             "INSERT INTO `users` (`Student ID`,`Student Username`,`Salt`,`Hash`) VALUES ('1','{$args['admin-user']}','','{$hash}')";
    $db->multi_query($query);

    while ($db->next_result()) if ($db->errno) {
        $args['error'] = 'Error creating default admin (' . $db->errno . ') ' . $db->error;
        goto end;
    }
    $db->close();

    $args['compl'] = true;
}

end:
?>

<!DOCTYPE html>

<html>

    <head>

    <link rel = "stylesheet" type="text/css" href="assets/css/bootstrap.css"/>
        <title>Sponsorship Portal Setup</title>
    </head>

    <body>

        <h1 style="text-align:center">Sponsorship Portal Setup</h1>
        <?php if (isset($args['error'])) { ?>
        <div class="alert alert-danger col-sm-6 col-sm-offset-3" role="alert">
          <strong>Alert!</strong> <?php echo $args['error']; ?>
        </div>
        <?php } ?>
        <?php if (isset($args['compl'])) { ?>
        <div class="alert alert-success col-sm-6 col-sm-offset-3" role="alert">
          <strong>Success!</strong> Setup completed successfully.
        </div>
        <?php } ?>
        <div class = "container">
            <form action = "setup.php" method="post" class="well form-horizontal col-sm-6 col-sm-offset-3" role="form" name="login_form">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Database configuration </h3>
                    </div>
                    <div class="panel-body">
                        <div class = "form-group <?php if (ch_high('db-host')) { echo 'has-error has-feedback'; }?>">
                            <label for="db-host" class="col-sm-3 control-label">Hostname</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="db-host" placeholder="Hostname" name="db-host" autofocus="autofocus" value="<?php if (ch_disp('db-host')) echo $args['db-host'] ; ?>">
                            </div>
                        </div>
                        <div class = "form-group <?php if (ch_high('db-name')) { echo 'has-error has-feedback'; }?>">
                            <label for="db-name" class="col-sm-3 control-label">DB Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="db-name" placeholder="Database Name" name="db-name" value="<?php if (ch_disp('db-name')) echo $args['db-name'] ; ?>">
                            </div>
                        </div>
                        <div class = "form-group <?php if (ch_high('db-user')) { echo 'has-error has-feedback'; }?>">
                            <label for="db-user" class="col-sm-3 control-label">DB Username</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="db-user" placeholder="Username" name="db-user" value="<?php if (ch_disp('db-user')) echo $args['db-user'] ; ?>">
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for="db-pass" class="col-sm-3 control-label">DB Password</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="db-pass" placeholder="Password" name="db-pass">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Default Admin </h3>
                    </div>
                    <div class="panel-body">
                        <div class = "form-group <?php if (ch_high('admin-user')) { echo 'has-error has-feedback'; }?>">
                            <label for="admin-user" class="col-sm-3 control-label">Username</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="admin-user" placeholder="Username" name="admin-user" value="<?php if (ch_disp('admin-user')) echo $args['admin-user'] ; ?>">
                            </div>
                        </div>
                        <div class = "form-group <?php if (ch_high('admin-pass')) { echo 'has-error has-feedback'; }?>">
                            <label for="admin-pass" class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="admin-pass" placeholder="Password" name="admin-pass" value="<?php if (ch_disp('admin-pass')) echo $args['admin-pass'] ; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group -sol-sm-6">
                    <div class="col-sm-offset-8 col-sm-4">
                        <button type="submit" class="btn btn-primary">Start Setup</button>
                    </div>
                </div>
            </form>
        </div>


    </body>
</html>