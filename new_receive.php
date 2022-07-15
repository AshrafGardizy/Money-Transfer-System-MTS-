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

// Current Information
$cur_branch = $con->query("SELECT * FROM branch WHERE b_id=".$logger_row->b_id);
$cur_branch_row = $cur_branch->fetch_object();

// Count Received Money
$query_r_money = "SELECT * FROM exchange WHERE r_b_id='$cur_branch_row->b_id' AND achived='no'";
$r_money = $con->query($query_r_money);
$r_money_row = $r_money->fetch_object();

// received
$achived = "false";
if (isset($_POST["received_money"])) {
    $ex_code = $_POST["ex_code"];
    $amount = (int) $_POST["amount"];

    $r_b_id = $logger_row->b_id;

    $query_chk = "SELECT * FROM exchange WHERE ex_code='$ex_code' AND amount='$amount' AND achived='no' AND r_b_id='$r_b_id'";
    $chked = $con->query($query_chk);
    if ($chked->num_rows == 1) {
        $query_achived = "UPDATE exchange SET achived='yes', r_date=current_timestamp() WHERE ex_code='$ex_code'";
        if ($con->query($query_achived)) {
            $achived = true;
        } else echo $con->error;
    } else {
        $achived = "not exists";
    }



    // if ($con->query($query_send)) {
    //     $sent = true;
    // } else echo $con->error;
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
    <li class="current"><a href=""><i class="fa fa-inbox"></i> Recieved Money 
        <?php if ($r_money->num_rows != 0) { ?>
            <span class="badge bg-blue"><?= $r_money->num_rows; ?></span>
        <?php } ?>
    </a>
        <ul>
            <li><a href="received_list.php"><i class="fa fa-list-alt"></i> Received List</a></li>
            <li class="divider current"><a href="new_receive.php"><i class="fa fa-paste"></i> New Received 
                <?php if ($r_money->num_rows != 0) { ?>
                    <span class="badge bg-blue"><?= $r_money->num_rows; ?></span>
                <?php } ?>
            </a></li>
        </ul>
    </li>
    <li><a href=""><i class="fa fa-send"></i> Send Money</a>
        <ul>
            <li><a href="send_list.php"><i class="fa fa-list-alt"></i> Send List</a></li>
            <li class="divider"><a href="new_send.php"><i class="fa fa-paste"></i> New Send</a></li>
        </ul>
    </li>
    <li><a href="exchanger_profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
<!-- //Menu Horizontal -->

<div class="grid">
    <?php if ($achived == "not exists" && $achived !== true) { ?>
        <div class="notice error"><i class="icon-remove-sign icon-large"></i> Not Exists in the System! 
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <?php if ($achived === true) { ?>
        <div class="notice success"><i class="icon-ok icon-large"></i> Money Achieved Successfully! 
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <!-- Send Money -->
    <fieldset>
    <legend>Received Money to: <b><?= $cur_branch_row->name; ?></b></legend>
        <form method="post">
            <div class="col_12">
                <label for="ex_code" class="col_1">Exchange Code:</label>
                <input id="ex_code" type="text" placeholder="Enter the Exchange Code" name="ex_code" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="amount" class="col_1">Amount:</label>
                <input id="amount" type="number" placeholder="Enter the Amount" name="amount" required="" class="input-lg col_10" />
            </div>

            <div class="col_12">
                <button class="medium green col_3" type="submit" name="received_money"><i class="fa fa-check"></i> Receive</button>
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