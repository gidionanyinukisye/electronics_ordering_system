<?php
session_start();
include("../config/db.php");

// Admin Authentication
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}

if(isset($_POST['order_id']) && isset($_POST['status'])){
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Update order status
    $update = mysqli_query($conn, "UPDATE orders SET status='$status' WHERE order_id=$order_id");

    if($update){
        header("Location: orders.php?success=Status updated");
        exit;
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
} else {
    header("Location: orders.php");
    exit;
}
?>