<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit();
}

$order_id = $_POST['order_id'];
$status   = $_POST['status'];

$sql = "UPDATE orders SET status='$status' WHERE order_id='$order_id'";

if(mysqli_query($conn, $sql)){
    header("Location: ../admin/orders.php");
}else{
    die("Update failed: " . mysqli_error($conn));
}