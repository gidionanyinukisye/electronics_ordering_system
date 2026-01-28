<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit();
}

$id = $_GET['id'];

// Prevent admin deleting himself
if($id == $_SESSION['user_id']){
    header("Location: ../admin/users.php");
    exit();
}

$sql = "DELETE FROM users WHERE user_id='$id'";

if(mysqli_query($conn, $sql)){
    header("Location: ../admin/users.php");
}else{
    die("Delete failed: " . mysqli_error($conn));
}