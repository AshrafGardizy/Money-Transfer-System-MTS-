<?php
require_once("db.php");

if (!isset($_SESSION["exchanger_id"])) {
    header("location: index.php?pleaseLogin");
}
$emp_id = $_SESSION["exchanger_id"];

$logger = $con->query("SELECT * FROM employee WHERE emp_id='$emp_id'");
$logger_row = $logger->fetch_object();

// Current Information
$cur_branch = $con->query("SELECT * FROM branch WHERE b_id=".$logger_row->b_id);
$cur_branch_row = $cur_branch->fetch_object();

// Count Received Money
$query_r_money = "SELECT * FROM exchange WHERE r_b_id='$cur_branch_row->b_id' AND achived='no'";
$r_money = $con->query($query_r_money);
$r_money_row = $r_money->fetch_object();

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
    <li class="current"><a href="exchanger.php"><i class="fa fa-desktop"></i> Dashboard</a></li>
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

<div class="callout clearfix" style="height:600px; background:url('img/gplaypattern.png') #4598ef;">
    <div class="grid">
        <h1>Hi: <?= $logger_row->firstname . " " . $logger_row->lastname; ?></h1>
        <h3>You are working in Branch: <b><?= $cur_branch_row->name; ?></b> <br>Which is Located in: <b><?= $cur_branch_row->location; ?></b></h3>
        <h4>Here you are an Exchanger which receive and send money in this branch!</h4>
        
        <div class="col_3 column">
            <i class="fa fa-mobile fa-4x"></i><br>
            Received &amp; Send List Money
        </div>
        
        <div class="col_3 column">
            <i class="fa fa-paste fa-4x"></i><br>
            New Received &amp; Send Money
        </div>
        
        <div class="col_3 omega column">
            <i class="fa fa-tint fa-4x"></i><br>
            My Profile
        </div>

        <div class="col_3 column">
            <i class="fa fa-flag fa-4x"></i><br>
            Logout<br>
        </div>
        
    </div>
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