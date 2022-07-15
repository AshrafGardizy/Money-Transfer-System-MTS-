<?php
require_once("db.php");

if (!isset($_SESSION["admin_id"])) {
    header("location: index.php?pleaseLogin");
}
$emp_id = $_SESSION["admin_id"];

$logger = $con->query("SELECT * FROM employee WHERE emp_id='$emp_id'");
$logger_row = $logger->fetch_object();

$query_sel_braches = "SELECT * FROM branch";
$sel_braches = $con->query($query_sel_braches);

// Select All Emp for Being Manager
$query_sel_emps = "SELECT * FROM employee";
$sel_emps = $con->query($query_sel_emps);

// update_my_profile
$added = false;
if (isset($_POST["add_branch"])) {
    $name = $_POST["b_name"];
    $loc = $_POST["location"];
    $m_id = $_POST["manager_id"];

    if ($m_id != "") {
        $query = "INSERT INTO branch VALUES(NULL, '$name', '$loc', '$m_id', current_timestamp())";
        if ($con->query($query)) {
            $added = true;
        } else echo $con->error;
    } else {
        $query = "INSERT INTO branch VALUES(NULL, '$name', '$loc', NULL, current_timestamp())";
        if ($con->query($query)) {
            $added = true;
        } else echo $con->error;
    }
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
    <li><a href="admin.php"><i class="fa fa-desktop"></i> Dashboard</a></li>
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
                    <li><a href="all_exchange_report.php"><i class="fa fa-refresh"></i> Transfers</a></li>
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
                    <li><a href="one_exchange_report.php"><i class="fa fa-refresh"></i>Transfers</a></li>
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
    <li class="current"><a href=""><i class="fa fa-bitcoin"></i> Branch Management</a>
        <ul>
            <li><a href="branch_lists.php"><i class="fa fa-list-alt"></i> Branch Lists</a></li>
            <li class="current"><a href="add_branch.php"><i class="fa fa-plus"></i> Add Branch</a></li>
        </ul>
    </li>
    <li><a href="admin_profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
<!-- //Menu Horizontal -->

<div class="grid">
    <?php if ($added == true) { ?>
        <div class="notice success"><i class="icon-ok icon-large"></i> Branch Added Successfully! 
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <!-- My Profile -->
    <fieldset>
    <legend>Add New Branch</legend>
        <form method="post">
            <div class="col_12">
                <label for="b_name" class="col_1">Branch Name:</label>
                <input id="b_name" type="text" placeholder="Enter the Branch Name" name="b_name" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="location" class="col_1">Location:</label>
                <input id="location" type="text" placeholder="Enter the Location" name="location" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="manager_id" class="col_1">Manager:</label>
                <select class="input-lg col_10" name="manager_id" id="manager_id">
                    <option value=""></option>
                    <?php while ($row = $sel_emps->fetch_object()) { ?>
                        <option value="<?= $row->emp_id; ?>"><?= $row->firstname . " " . $row->lastname; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col_12">
                <button class="medium green col_3" type="submit" name="add_branch"><i class="fa fa-check"></i> Add</button>
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