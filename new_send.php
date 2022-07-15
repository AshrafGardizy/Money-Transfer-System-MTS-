<?php
require_once("db.php");

if (!isset($_SESSION["exchanger_id"])) {
    header("location: index.php?pleaseLogin");
}
$emp_id = $_SESSION["exchanger_id"];

$logger = $con->query("SELECT * FROM employee WHERE emp_id='$emp_id'");
$logger_row = $logger->fetch_object();

// Select All Branches Except Current
$query_sel_braches = "SELECT * FROM branch WHERE b_id NOT IN($logger_row->b_id)";
$sel_braches = $con->query($query_sel_braches);

// update_my_profile
$updated = false;
if (isset($_POST["update_my_profile"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phone_no = $_POST["phone_no"];
    $email = $_POST["email"];

    $query = "UPDATE employee SET firstname='$firstname', lastname='$lastname', phone='$phone_no', email='$email' WHERE emp_id='$emp_id'";
    if ($con->query($query)) {
        $updated = true;
    } else echo $con->error;

}

// changing_password
$password_update = false;
if (isset($_POST["changing_password"])) {
    $cur_password = $_POST["cur_password"];
    $new_password = $_POST["new_password"];
    $conf_password = $_POST["conf_password"];

    if (($new_password == $conf_password) && (md5($cur_password) == $logger_row->password)) {
        $query = "UPDATE employee SET password=md5('$new_password') WHERE emp_id='$emp_id'";
        $con->query($query);
        $password_update = "success";
    } else {
        $password_update = "error";
    }
}

// Current Information
$cur_branch = $con->query("SELECT * FROM branch WHERE b_id=".$logger_row->b_id);
$cur_branch_row = $cur_branch->fetch_object();

// Count Received Money
$query_r_money = "SELECT * FROM exchange WHERE r_b_id='$cur_branch_row->b_id' AND achived='no'";
$r_money = $con->query($query_r_money);
$r_money_row = $r_money->fetch_object();

// send_money
$sent = false;
if (isset($_POST["send_money"])) {
    $ex_code = $_POST["ex_code"];
    $s_full_name = $_POST["s_full_name"];
    $r_full_name = $_POST["r_full_name"];
    $r_b_id = $_POST["r_b_id"];
    $amount = (int) $_POST["amount"];
    $tax = $_POST["tax"];

    $s_b_id = $logger_row->b_id;

    // calc r_amount & profit
    $r_amount = ($amount * (100 - $tax)) / 100;
    $profit = ($amount * $tax) / 100;

    $query_send = "INSERT INTO exchange VALUES(NULL, '$ex_code', '$s_full_name', '$s_b_id', '$r_full_name', '$r_b_id', '$amount', '$tax', '$r_amount', '$profit', 'no', current_timestamp(), null)";
    if ($con->query($query_send)) {
        $sent = true;
    } else echo $con->error;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome <?= $logger_row->firstname . " " . $logger_row->lastname; ?></title>
    <?php require_once("relatives.php"); ?>
</head>
<body>

<!-- Menu Horizontal -->
<ul class="menu">
    <li><a href="exchanger.php"><i class="fa fa-desktop"></i> Dashboard</a></li>
    <li><a href=""><i class="fa fa-inbox"></i> Recieved Money 
        <?php if ($r_money->num_rows != 0) { ?>
            <span class="badge bg-blue"><?= $r_money->num_rows; ?></span>
        <?php } ?>
    </a>
        <ul>
            <li><a href="received_list.php"><i class="fa fa-list-alt"></i> Received List</a></li>
            <li class="divider"><a href="new_receive.php"><i class="fa fa-paste"></i> New Received 
                <?php if ($r_money->num_rows != 0) { ?>
                    <span class="badge bg-blue"><?= $r_money->num_rows; ?></span>
                <?php } ?>
            </a></li>
        </ul>
    </li>
    <li class="current"><a href=""><i class="fa fa-send"></i> Send Money</a>
        <ul>
            <li><a href="send_list.php"><i class="fa fa-list-alt"></i> Send List</a></li>
            <li class="divider current"><a href="new_send.php"><i class="fa fa-paste"></i> New Send</a></li>
        </ul>
    </li>
    <li><a href="exchanger_profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
<!-- //Menu Horizontal -->

<div class="grid">
    <?php if ($sent === true) { ?>
        <div class="notice success"><i class="icon-ok icon-large"></i> Money Send Successfully! 
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <!-- Send Money -->
    <fieldset>
    <legend>Send Money From Branch <b><?= $cur_branch_row->name; ?></b></legend>
        <form method="post">
            <div class="col_12">
                <label for="ex_code" class="col_1">Exchange Code:</label>
                <input id="ex_code" type="text" placeholder="Enter the Exchange Code" name="ex_code" required="" class="input-lg col_10" />
            </div>
<!--             <div class="col_12">
                <label for="s_taz_no" class="col_1">Sender Tazkera No.:</label>
                <input id="s_taz_no" type="text" placeholder="Enter the Sender Tazkera No." name="s_taz_no" required="" class="input-lg col_10" />
            </div> -->
            <div class="col_12">
                <label for="s_full_name" class="col_1">Sender Full Name:</label>
                <input id="s_full_name" type="text" placeholder="Enter the Sender Full Name." name="s_full_name" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="r_full_name" class="col_1">Receiver Full Name:</label>
                <input id="r_full_name" type="text" placeholder="Enter the Receiver Full Name" name="r_full_name" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="r_b_id" class="col_1">Receiver Branch:</label>
                <select id="r_b_id" class="col_10" name="r_b_id">
                    <?php while ($row = $sel_braches->fetch_object()) { ?>
                        <option value="<?= $row->b_id; ?>"><?= $row->name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col_12">
                <label for="amount" class="col_1">Amount:</label>
                <input id="amount" type="number" placeholder="Enter the Amount" name="amount" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="tax" class="col_1">Tax:</label>
                <input id="tax" type="number" placeholder="Enter the Tax Percentage" name="tax" required="" class="input-lg col_10" />
            </div>

            <div class="col_12">
                <button class="medium green col_3" type="submit" name="send_money"><i class="fa fa-check"></i> Send</button>
            </div>
        </form>
    </fieldset>
</div>


<div style="padding:40px;">
    <footer class="center">
        &copy; All Rights Reserved 
        <br>
        This System Developed By: <strong>Ashraf Gardizy <i class="fa fa-heart"></i></strong>
    </footer>
</div>



</body>
</html>