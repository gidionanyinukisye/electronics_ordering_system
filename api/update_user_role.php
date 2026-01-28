<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit();
}

$user_id = $_POST['user_id'];
$role_id = $_POST['role_id'];

$sql = "UPDATE users SET role_id='$role_id' WHERE user_id='$user_id'";

if(mysqli_query($conn, $sql)){
    header("Location: ../admin/users.php");
}else{
    die("Update failed: " . mysqli_error($conn));
}