<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit;
}

if (isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status   = $_POST['status'];

    mysqli_query($conn, "
        UPDATE orders 
        SET status='$status' 
        WHERE order_id=$order_id
    ");
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;