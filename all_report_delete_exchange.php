<?php
require_once("db.php");

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $query = "DELETE FROM exchange WHERE ex_id=".$_GET["id"];
    if ($con->query($query)) {
        header("location: all_exchange_report.php?deleted");
    } else echo $con->error;

}

?>