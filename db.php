<?php
// Start Session
if (!session_start()) {
    session_start();
}

// require_once("library/tcpdf.php");

// Set timezone
date_default_timezone_set("Asia/Kabul");


$host = "localhost";
$username = "root";
$password = "";
$database = "money_exchange";

// Create connection
$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

?>