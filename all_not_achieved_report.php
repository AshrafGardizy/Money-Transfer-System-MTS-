<?php
require_once("db.php");

if (!isset($_SESSION["admin_id"])) {
    header("location: index.php?pleaseLogin");
}
$emp_id = $_SESSION["admin_id"];

$logger = $con->query("SELECT * FROM employee WHERE emp_id='$emp_id'");
$logger_row = $logger->fetch_object();

// Give the Report
$fetched = false;
if (isset($_POST["report"])) {
    $year = $_POST["report_year"];
    $month = $_POST["report_month"];
    $query_sel_ex = "SELECT ex_code, s_full_name, name as b_name, amount, tax, r_amount, profit, achived, s_date, r_date FROM exchange INNER JOIN branch ON exchange.r_b_id=branch.b_id WHERE s_date LIKE '$year-$month%' AND achived='no'";
    $sel_ex = $con->query($query_sel_ex);
    if ($sel_ex) {
        $fetched = true;
    } else echo $con->error;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome <?= $logger_row->firstname . " " . $logger_row->lastname; ?></title>
    <?php require_once("relatives.php"); ?>
    <style type="text/css">
        td {
            text-align: center;
        }
        th {
            text-align: left;
        }
    </style>
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
    <li class="current"><a href=""><i class="fa fa-inbox"></i> Monthly Reports</a>
        <ul>
            <li class="current"><a href=""><i class="fa fa-paste"></i> All Branches</a>
                <ul>
                    <li><a href="all_exchange_report.php"><i class="fa fa-refresh"></i> Transfers</a></li>
                    <li><a href="all_profit_report.php"><i class="fa fa-camera-retro"></i> Profit</a></li>
                    <li class="current"><a href=""><i class="fa fa-coffee"></i> Achived</a>
                        <ul>
                            <li><a href="all_achieved_report.php"><i class="fa fa-check"></i> Yes</a></li>
                            <li class="current"><a href="all_not_achieved_report.php"><i class="fa fa-minus-square"></i> No</a></li>
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

<!-- If fetched hide this area -->
<?php if ($fetched !== true) { ?>
<div class="grid">
    <?php if (isset($_GET["deleted"])) { ?>
    <div class="notice success"><i class="icon-ok icon-large"></i> Report Deleted Successfully!
    <a href="#close" class="icon-remove"></a></div>
    <?php } ?>
    <fieldset>
        <legend>Monthly Not Achieved Report Form</legend>
        <form method="post">
            <div class="col_12">
                <label for="report_year" class="col_1">Year of Report:</label>
                <select class="input-lg col_10" id="report_year" name="report_year">
                    <?php $year = 2015; while ($year <= 2025) { ?>
                        <option><?= $year; ?></option>
                    <?php $year++; } ?>
                </select>
            </div>
            <div class="col_12">
                <label for="report_month" class="col_1">Month of Report:</label>
                <select class="input-lg col_10" id="report_month" name="report_month">
                    <option>01</option>
                    <option>02</option>
                    <option>03</option>
                    <option>04</option>
                    <option>05</option>
                    <option>06</option>
                    <option>07</option>
                    <option>08</option>
                    <option>09</option>
                    <option>10</option>
                    <option>11</option>
                    <option>12</option>
                </select>
            </div>

            <div class="col_12">
                <button class="medium green col_3" type="submit" name="report"><i class="fa fa-check"></i> Give Me Report</button>
                <!-- <button class="medium green col_3" type="submit" name="pdf"><i class="fa fa-download"></i> Download PDF Report</button> -->
            </div>
        </form>
    </fieldset>
</div>
<?php } else { ?>
<div class="grid">
    <fieldset>
        <legend>Monthly Not Achieved Report of Year: <b><?= $_POST["report_year"] ?></b> and Month: <b><?= $_POST["report_month"]; ?></b></legend>
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
                <th>Delete</th>
            </tr>
            <?php while ($row = $sel_ex->fetch_object()) { ?>
                <tr>
                    <td><?= $row->ex_code; ?></td>
                    <td><?= $row->s_full_name; ?></td>
                    <td><?= $row->b_name; ?></td>
                    <td><?= $row->amount; ?></td>
                    <td><?= $row->tax; ?></td>
                    <td><?= $row->r_amount; ?></td>
                    <td><?= $row->profit; ?></td>
                    <td><?= $row->achived; ?></td>
                    <td><?= $row->s_date; ?></td>
                    <td><?php if ($row->r_date == NULL) { echo "Not Received Yet!"; } else { echo $row->r_date; } ?></td>
                    <td>
                        <a title="Delete" href="all_report_delete_exchange.php?id=<?= $row->ex_id; ?>"><i class="fa fa-minus-square"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </fieldset>
</div>
<?php } ?>

<div style="padding:40px;">
    <footer class="center">
        &copy; All Rights Reserved 
        <br>
        This System Developed By: <strong>Ashraf Gardizy <i class="fa fa-heart"></i></strong>
    </footer>
</div>



</body>
</html>