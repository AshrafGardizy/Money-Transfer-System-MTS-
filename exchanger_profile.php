<?php
require_once("db.php");

if (!isset($_SESSION["exchanger_id"])) {
    header("location: index.php?pleaseLogin");
}
$emp_id = $_SESSION["exchanger_id"];

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
    <li><a href=""><i class="fa fa-send"></i> Send Money</a>
        <ul>
            <li><a href="send_list.php"><i class="fa fa-list-alt"></i> Send List</a></li>
            <li class="divider"><a href="new_send.php"><i class="fa fa-paste"></i> New Send</a></li>
        </ul>
    </li>
    <li class="current"><a href="exchanger_profile.php"><i class="fa fa-user"></i> My Profile</a></li>
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