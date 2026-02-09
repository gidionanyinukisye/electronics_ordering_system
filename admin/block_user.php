<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    mysqli_query($conn, "UPDATE users SET status='blocked' WHERE user_id=$id");
}

header("Location: users.php");
exit;