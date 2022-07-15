<?php
require_once("db.php");

if (!isset($_SESSION["admin_id"])) {
    header("location: index.php?pleaseLogin");
}
$emp_id = $_SESSION["admin_id"];

$logger = $con->query("SELECT * FROM employee WHERE emp_id='$emp_id'");
$logger_row = $logger->fetch_object();

// Current Information
$cur_branch = $con->query("SELECT * FROM branch WHERE b_id='$logger_row->b_id'");
if ($cur_branch->num_rows != 0) {
    $cur_branch_row = $cur_branch->fetch_object();
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
    <li class="current"><a href="admin.php"><i class="fa fa-desktop"></i> Dashboard</a></li>
    <li><a href=""><i class="fa fa-inbox"></i> Add Employee </a>
        <ul>
            <li><a href="add_employee.php"><i class="fa fa-plus"></i> Add Employee </a></li>
        </ul>
    </li>
    <li><a href=""><i class="fa fa-desktop"></i> Employee Reports</a>
        <ul>
            <li><a href="admin_list.php"><i class="fa fa-list-alt"></i> Admin Lists</a></li>
            <li class="divider"><a href="exchanger_list.php"><i class="fa fa-list-alt"></i> Exchanger Lists</a></li>
        </ul>
    </li>
    <li><a href=""><i class="fa fa-inbox"></i> Monthly Reports</a>
        <ul>
            <li><a href=""><i class="fa fa-paste"></i> All Branches</a>
                <ul>
                    <li><a href="all_exchange_report.php"><i class="fa fa-refresh"></i>Transfers</a></li>
                    <li><a href="all_profit_report.php"><i class="fa fa-camera-retro"></i> Profit</a></li>
                    <li><a href=""><i class="fa fa-coffee"></i> Achived</a>
                        <ul>
                            <li><a href="all_achieved_report.php"><i class="fa fa-check"></i> Yes</a></li>
                            <li><a href="all_not_achieved_report.php"><i class="fa fa-minus-square"></i> No</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="divider"><a href=""><i class="fa fa-paste"></i> One Branche</a>
                <ul>
                    <li><a href="one_exchange_report.php"><i class="fa fa-refresh"></i> Transfers</a></li>
                    <li><a href="one_profit_report.php"><i class="fa fa-camera-retro"></i> Profit</a></li>
                    <li><a href="one_sent_money_report.php"><i class="fa fa-check"></i> Sent Money</a></li>
                    <li><a href="one_received_money_report.php"><i class="fa fa-sign-in"></i> Received Money</a></li>
                    <li><a href=""><i class="fa fa-coffee"></i> Achived</a>
                        <ul>
                            <li><a href="one_achieved_report.php"><i class="fa fa-check"></i> Yes</a></li>
                            <li><a href="one_not_achieved_report.php"><i class="fa fa-minus-square"></i> No</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href=""><i class="fa fa-bitcoin"></i> Branch Management</a>
        <ul>
            <li><a href="branch_lists.php"><i class="fa fa-list-alt"></i> Branch Lists</a></li>
            <li><a href="add_branch.php"><i class="fa fa-plus"></i> Add Branch</a></li>
        </ul>
    </li>
    <li><a href="admin_profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
<!-- //Menu Horizontal -->

<div class="callout clearfix" style="height:600px; background:url('img/gplaypattern.png') #4598ef;">
    <div class="grid">
        <h1>Hi: <?= $logger_row->firstname . " " . $logger_row->lastname; ?></h1>
        <h3></h3>
        <h4>Here you are an Admin which Controls the Whole Things of this Company!</h4>
        
        <div class="col_3 column">
            <i class="fa fa-mobile fa-4x"></i><br>
            Make Sure before Doing Something
        </div>
        
        <div class="col_3 column">
            <i class="fa fa-paste fa-4x"></i><br>
            Get the Received &amp; Send Money Reports
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