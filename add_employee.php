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

// update_my_profile
$added = false;
if (isset($_POST["add_employee"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phone_no = $_POST["phone_no"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $b_id = $_POST["b_id"];
    // $branch_id = ($b_id == "") ? (NULL) : ($b_id);
    $position = $_POST["position"];

    if ($b_id != "") {
        $query = "INSERT INTO employee VALUES(NULL, '$firstname', '$lastname', '$phone_no', '$email', md5('$password'), '$b_id', '$position')";
        if ($con->query($query)) {
            $added = true;
        } else echo $con->error;
    } else {
        $query = "INSERT INTO employee VALUES(NULL, '$firstname', '$lastname', '$phone_no', '$email', md5('$password'), NULL, '$position')";
        if ($con->query($query)) {

        // $conn = mysqli_connect($host, $username, $password, $database);
        // $query_sel_emp = mysqli_query($conn, "SELECT * FROM employee ORDER BY emp_id DESC LIMIT 1");
        // $rows = mysqli_fetch_assoc($query_sel_emp);
        // $emp_id = $rows["emp_id"];

        // update branch
        // if (mysqli_query($con, "UPDATE branch SET manager_id=$emp WHERE b_id=".$_POST["b_id"])) {
        // get to insert
        // $query_sel_emp = "SELECT * FROM employee ORDER BY emp_id DESC LIMIT 1";
        // $seled = $con->query($query_sel_emp);
        // $row = $seled->fetch_object();
        // $emp = $row->emp_id;
        // echo $emp;
        // $update_branch_mgr = "UPDATE branch SET manager_id=$emp WHERE b_id=".$_POST["b_id"];
        // if ($con->query($update_branch_mgr)) {
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
    <li class="current"><a href=""><i class="fa fa-inbox"></i> Add Employee </a>
        <ul>
            <li class="current"><a href="add_employee.php"><i class="fa fa-plus"></i> Add Employee </a></li>
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

<div class="grid">
    <?php if ($added == true) { ?>
        <div class="notice success"><i class="icon-ok icon-large"></i> Employee Added Successfully! 
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <!-- My Profile -->
    <fieldset>
    <legend>Add Employee</legend>
        <form method="post">
            <div class="col_12">
                <label for="firstname" class="col_1">Firstname:</label>
                <input id="firstname" type="text" placeholder="Enter the Firstname" name="firstname" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="lastname" class="col_1">Lastname:</label>
                <input id="lastname" type="text" placeholder="Enter the Lastname" name="lastname" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="phone_no" class="col_1">Phone No:</label>
                <input id="phone_no" type="text" placeholder="Enter the Phone No" name="phone_no" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="email" class="col_1">Email:</label>
                <input id="email" type="text" placeholder="Enter the Email" name="email" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="password" class="col_1">Password:</label>
                <input id="password" type="password" placeholder="Enter the Password" name="password" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="b_id" class="col_1">Branch:</label>
                <select class="input-lg col_10" name="b_id" id="b_id">
                    <option value=""></option>
                    <?php while ($row = $sel_braches->fetch_object()) { ?>
                        <option value="<?= $row->b_id; ?>"><?= $row->name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col_12">
                <label for="position" class="col_1">Position:</label>
                <select class="input-lg col_10" name="position" id="position">
                        <option>exchanger</option>
                        <option>admin</option>
                </select>
            </div>

            <div class="col_12">
                <button class="medium green col_3" type="submit" name="add_employee"><i class="fa fa-check"></i> Add</button>
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