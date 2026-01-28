<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "electronics_ordering_system";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die(" Database connection failed: " . mysqli_connect_error());
} else {
    echo " Database connected successfully";
}