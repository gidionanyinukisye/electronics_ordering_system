<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "electronics_ordering_system";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>