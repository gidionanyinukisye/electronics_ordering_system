<?php
session_start();

// Check if admin is logged in
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}
include("../config/db.php"); // connect to database
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>
<style>
body{margin:0;font-family:Arial,sans-serif;background:#f4f6f9;}
header{background:#0d6efd;color:#fff;padding:10px;display:flex;justify-content:space-between;align-items:center;}
header a{color:#fff;text-decoration:none;margin-right:15px;font-weight:bold;}
header a:hover{text-decoration:underline;}
.container{width:95%;margin:20px auto;}
.breadcrumb{margin-bottom:15px;color:#333;}
.breadcrumb a{color:#0d6efd;text-decoration:none;margin-right:5px;}
.breadcrumb a:hover{text-decoration:underline;}
button{padding:8px 12px;border:none;border-radius:5px;background:#0d6efd;color:#fff;cursor:pointer;margin-top:5px;}
table{width:100%;border-collapse:collapse;background:#fff;margin-bottom:20px;}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:center;}
th{background:#0d6efd;color:#fff;}
img{width:60px;border-radius:6px;}
.edit{background:#198754;}
.delete{background:#dc3545;}
.no-results{color:red;text-align:center;font-weight:bold;padding:10px;}
</style>
</head>
<body>

<!-- Top Navigation -->
<header>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Manage Products</a>
        <a href="view_orders.php">View Orders</a>
    </div>
    <div>
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="container">