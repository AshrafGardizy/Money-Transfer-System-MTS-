<?php
require("db.php");

// Login
$login_try = "not_tried";
if (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);

    $query = "SELECT * FROM employee WHERE email='$email' AND password=md5('$password')";
    $login_result = $con->query($query);
    
    if ($login_result->num_rows == 1) {
        $found = $login_result->fetch_object();
        if ($found->position == "exchanger") {
            $_SESSION["exchanger_id"] = $found->emp_id;
            header("location: exchanger.php");
        } elseif ($found->position == "admin") {
            $_SESSION["admin_id"] = $found->emp_id;
            header("location: admin.php");
        } else {
            echo "Position is Not Recognized!";
        }
    } else $login_try = "failed";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Money Exchange</title>
    <?php require_once("relatives.php"); ?>
</head>
<body>
<div class="grid">
    <div class="col_12" style="margin-top:60px;">
        <h1 class="center" style="color:#999;">
            <p class="animated zoomInDown"><i class="fa fa-money"></i></p>
            <span class="animated zoomInRight">Welcome to Money Transfer System (MTS)</span>
        </h1>
    </div>

    <div class="col_2"></div>

    <div class="col_8 panel">
        <div class="panel-header">
                <i class="fa fa-sign-in"></i> Please Fill the Form to Login
                <?php
                if ($login_try == "failed") {
                    echo "<span class='red-text animated hinge'>Incorrect username or password!</span>";
                }
                ?>
        </div>
        <div class="panel-body">
            <form class="vertical" method="post" autocomplete="off">
                <label for="email">Email: <span class="right">A-Z, 0-9</span></label>
                <input id="email" type="text" placeholder="Enter the Email" name="email" class="input-lg" />

                <label for="password">Password: <span class="right">A-Z, 0-9</span></label>
                <input id="password" type="password" placeholder="Enter the Password" name="password" class="input-lg" />
                
                <button class="medium green inset" type="submit" name="login"><i class="fa fa-lock"></i> Login</button>
            </form>
        </div>
        <div class="panel-footer">
            Developed By: <strong>Ashraf Gardizy</strong>
        </div>
    </div>

</div>
<!-- End Grid -->

</body>
</html>
