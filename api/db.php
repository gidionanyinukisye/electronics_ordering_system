<?php
$conn = new mysqli("localhost","root","","electronics_ordering_system");
if($conn->connect_error){
    die("Database connection failed: " . $conn->connect_error);
}
?>