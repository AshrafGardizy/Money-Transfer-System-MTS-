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

// Select all sent money from this branch
$query_sel_ex = "SELECT * FROM exchange WHERE s_b_id='$logger_row->b_id'";
$sel_ex = $con->query($query_sel_ex);

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
            <li class="current"><a href="send_list.php"><i class="fa fa-list-alt"></i> Send List</a></li>
            <li class="divider"><a href="new_send.php"><i class="fa fa-paste"></i> New Send</a></li>
        </ul>
    </li>
    <li><a href="exchanger_profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
<!-- //Menu Horizontal -->

<div class="grid">
    <!-- Logs -->
</div>
    <!-- My Profile -->

<div class="grid">
    <fieldset>
    <legend>Money Sent Lists</legend>
        <table cellspacing="0" cellpadding="0" class="striped">
            <tr>
                <th>ExchangeCode</th>
                <th>Sender FullName</th>
                <th>ToBranch</th>
                <th>Amount</th>
                <th>Tax</th>
                <th>ReceivableAmount</th>
                <th>Profit</th>
                <th>Achieved</th>
                <th>SenderDate</th>
                <th>ReceiverDate</th>
                <!-- <th>Actions</th> -->
            </tr>
            <?php while ($row = $sel_ex->fetch_object()) { ?>
            <?php
                ;
                // Select Branch Name
                $query_sel_branch = "SELECT * FROM branch WHERE b_id='$row->r_b_id'";
                $sel_branch = $con->query($query_sel_branch);
                $b_row = $sel_branch->fetch_object();
            ?>
                <tr>
                    <td><?= $row->ex_code; ?></td>
                    <td><?= $row->s_full_name; ?></td>
                    <td><?= $b_row->name; ?></td>
                    <td><?= $row->amount; ?></td>
                    <td><?= $row->tax; ?></td>
                    <td><?= $row->r_amount; ?></td>
                    <td><?= $row->profit; ?></td>
                    <td><?= $row->achived; ?></td>
                    <td><?= $row->s_date; ?></td>
                    <td><?php if ($row->r_date == NULL) { echo "Not Received Yet!"; } else { echo $row->r_date; } ?></td>
                    <!-- <td>
                        <a title="Delete" href="delete_send.php?id=<?= $row->ex_id; ?>"><i class="fa fa-minus-square"></i></a>
                        |
                        <a title="Update" href="update_send.php?id=<?= $row->ex_id; ?>"><i class="fa fa-pencil"></i></a>
                    </td> -->
                </tr>
            <?php } ?>
        </table>
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