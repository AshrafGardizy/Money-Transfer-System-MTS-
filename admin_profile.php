<?php

require_once("db.php");

if (!isset($_SESSION["admin_id"])) {
    header("location: index.php?pleaseLogin");
}
$emp_id = $_SESSION["admin_id"];

$logger = $con->query("SELECT * FROM employee WHERE emp_id='$emp_id'");
$logger_row = $logger->fetch_object();

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
    <li><a href=""><i class="fa fa-bitcoin"></i> Branch Management</a>
        <ul>
            <li><a href="branch_lists.php"><i class="fa fa-list-alt"></i> Branch Lists</a></li>
            <li><a href="add_branch.php"><i class="fa fa-plus"></i> Add Branch</a></li>
        </ul>
    </li>
    <li class="current"><a href="admin_profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
<!-- //Menu Horizontal -->

<div class="grid">
    <?php if ($updated === true) { ?>
        <div class="notice success"><i class="icon-ok icon-large"></i> Profile Updated Successfully! 
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <?php if ($password_update == "success") { ?>
        <div class="notice success"><i class="icon-ok icon-large"></i> Password Updated Successfully! 
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <?php if ($password_update == "error") { ?>
        <div class="notice error"><i class="icon-remove-sign icon-large"></i> Error Password Updation!
        <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <!-- My Profile -->
    <fieldset>
    <legend>My Profile</legend>
        <form method="post">
            <div class="col_12">
                <label for="firstname" class="col_1">Firstname:</label>
                <input id="firstname" type="text" placeholder="Enter Your Firstname" value="<?= $logger_row->firstname; ?>" name="firstname" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="lastname" class="col_1">Lastname:</label>
                <input id="lastname" type="text" placeholder="Enter Your Lastname" value="<?= $logger_row->lastname; ?>" name="lastname" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="phone_no" class="col_1">Phone No.:</label>
                <input id="phone_no" type="text" placeholder="Enter Your Phone No." value="<?= $logger_row->phone; ?>" name="phone_no" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="email" class="col_1">Email:</label>
                <input id="email" type="text" placeholder="Enter Your Email" value="<?= $logger_row->email; ?>" name="email" required="" class="input-lg col_10" />
            </div>

            <div class="col_12">
                <button class="medium green col_3" type="submit" name="update_my_profile"><i class="fa fa-check"></i> Save</button>
            </div>
        </form>
    </fieldset>

    <!-- Changing Password -->
    <fieldset>
    <legend>Changing Password</legend>
        <form method="post">
            <div class="col_12">
                <label for="cur_password" class="col_1">Current Password:</label>
                <input id="cur_password" type="password" placeholder="Enter Your Current Password"  name="cur_password" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="new_password" class="col_1">New Password:</label>
                <input id="new_password" type="password" placeholder="Enter Your New Password"  name="new_password" required="" class="input-lg col_10" />
            </div>
            <div class="col_12">
                <label for="conf_password" class="col_1">Confirm Password:</label>
                <input id="conf_password" type="password" placeholder="Enter Your Confirm Password"  name="conf_password" required="" class="input-lg col_10" />
            </div>

            <div class="col_12">
                <button class="medium green col_3" type="submit" name="changing_password"><i class="fa fa-check"></i> Change Password</button>
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