<?php
session_start();
include("../config/db.php");

// Authentication
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2){
    header("Location: ../public/login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

// Overview
$total_orders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total_orders FROM orders WHERE user_id='$user_id'"))['total_orders'];
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as pending_orders FROM orders WHERE user_id='$user_id' AND status='Pending'"))['pending_orders'];
$delivered_orders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as delivered_orders FROM orders WHERE user_id='$user_id' AND status='Delivered'"))['delivered_orders'];
$total_spent = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_price) as total_spent FROM orders WHERE user_id='$user_id' AND status='Delivered'"))['total_spent'];

// Recent Orders Summary
$orders_query = mysqli_query($conn,"SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_date DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:#f0f2f5;}
.wrapper{display:flex;min-height:100vh;}
.sidebar{
    width:250px;background:#0d6efd;color:#fff;display:flex;flex-direction:column;padding-top:20px;
}
.sidebar h2{text-align:center;margin-bottom:30px;}
.sidebar a{padding:15px 25px;text-decoration:none;color:#fff;font-weight:600;display:block;}
.sidebar a:hover{background:#0b5ed7;border-radius:8px;}
.main{flex:1;padding:30px;}
.topnav{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;}
.topnav h1{font-size:22px;color:#333;}
.topnav a{text-decoration:none;background:#dc3545;color:#fff;padding:8px 15px;border-radius:6px;}
.cards{display:flex;gap:20px;flex-wrap:wrap;margin-bottom:30px;}
.card{flex:1;min-width:220px;background:#fff;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,0.1);text-align:center;}
.card h3{color:#555;font-size:16px;margin-bottom:10px;}
.card p{font-size:22px;color:#0d6efd;font-weight:bold;}
.table-container{background:#fff;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,0.1);}
.table-container h2{color:#0d6efd;margin-bottom:15px;}
table{width:100%;border-collapse:collapse;}
table th, table td{padding:12px;border-bottom:1px solid #ccc;text-align:left;}
table th{background:#e9ecef;color:#333;}
.status{padding:4px 10px;border-radius:6px;color:#fff;font-weight:bold;font-size:13px;}
.Pending{background:#ffc107;}
.Approved{background:#0d6efd;}
.Delivered{background:#198754;}
.Cancelled{background:#dc3545;}
.btn-view{background:#0d6efd;color:#fff;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:13px;}
.btn-view:hover{background:#0b5ed7;}
.profile{background:#fff;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,0.1);margin-bottom:30px;}
.profile h2{color:#0d6efd;margin-bottom:15px;}
.profile p{margin-bottom:8px;color:#555;font-size:14px;}
</style>
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2><?= htmlspecialchars($full_name) ?></h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">View Products</a>
        <a href="dashboard.php#orders">My Orders</a>
        <a href="../api/logout.php">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">

        <!-- TOP NAV -->
        <div class="topnav">
            <h1>Welcome, <?= htmlspecialchars($full_name) ?></h1>
            <a href="../api/logout.php">Logout</a>
        </div>

        <!-- PROFILE -->
        <div class="profile">
            <h2>My Profile</h2>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($full_name) ?></p>
            <?php
            $user_data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT email FROM users WHERE user_id='$user_id'"));
            ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email']) ?></p>
        </div>

        <!-- OVERVIEW CARDS -->
        <div class="cards">
            <div class="card"><h3>Total Orders</h3><p><?= $total_orders ?></p></div>
            <div class="card"><h3>Pending Orders</h3><p><?= $pending_orders ?></p></div>
            <div class="card"><h3>Delivered Orders</h3><p><?= $delivered_orders ?></p></div>
            <div class="card"><h3>Total Spent</h3><p>$<?= $total_spent ? number_format($total_spent,2) : '0.00' ?></p></div>
        </div>

        <!-- ORDERS TABLE -->
        <div class="table-container" id="orders">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($order = mysqli_fetch_assoc($orders_query)){
                        ?>
                    <tr>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= date('d M, Y', strtotime($order['order_date'])) ?></td>
                        <td><span class="status <?= $order['status'] ?>"><?= $order['status'] ?></span></td>
                        <td>$<?= number_format($order['total_price'],2) ?></td>
                        <td><a class="btn-view" href="view_order.php?order_id=<?= $order['order_id'] ?>">View Details</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

</body>
</html>