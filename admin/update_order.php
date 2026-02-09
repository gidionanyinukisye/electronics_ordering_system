<?php
include 'auth_check.php';
include '../config/db.php';

$id = $_POST['order_id'];
$status = $_POST['status'];

$conn->query("UPDATE orders SET status='$status' WHERE id=$id");

header("Location: view_order.php?id=$id");