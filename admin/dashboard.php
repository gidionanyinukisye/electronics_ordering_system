<?php
session_start();
include("../config/db.php");

// Admin Authentication
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}

// Fetch stats
$products_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE is_deleted=0"))['total'];
$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$orders_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$pending_orders_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='Pending'"))['total'];
$completed_orders_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='Completed'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Electronics Ordering System</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
body{
    margin:0;
    font-family: 'Roboto', sans-serif;
    background:#f4f6fb;
}
header{
    background:#0a1a33;
    color:#fff;
    padding:20px 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
header h1{font-size:28px;}
header nav a{
    color:#fff;
    margin-left:20px;
    text-decoration:none;
    font-weight:500;
}
.container{width:95%;margin:auto;padding:30px 0;}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:25px;margin-bottom:40px;}
.card{
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
    transition:.3s;
    text-align:center;
}
.card:hover{transform:translateY(-6px);box-shadow:0 15px 40px rgba(0,0,0,.2);}
.card h2{font-size:32px;margin:10px 0;color:#0a1a33;}
.card p{color:#777;font-size:16px;}
.card span{display:block;margin-top:5px;color:#124a9f;font-weight:bold;font-size:18px;}
.overview{background:#fff;padding:20px;border-radius:20px;box-shadow:0 10px 25px rgba(0,0,0,.1);}
.overview h3{margin-bottom:15px;color:#0a1a33;}
.overview table{width:100%;border-collapse:collapse;}
.overview th, .overview td{padding:12px 15px;text-align:left;border-bottom:1px solid #ddd;}
.overview th{background:#0a1a33;color:#fff;}
.overview tr:hover{background:#f1f1f1;}
</style>
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="orders.php">Orders</a>
        <a href="users.php">Users</a>
        <a href="../api/logout.php">Logout</a>
    </nav>
</header>

<div class="container">

    <div class="cards">
        <div class="card">
            <h2><?php echo $products_count; ?></h2>
            <p>Products</p>
        </div>
        <div class="card">
            <h2><?php echo $users_count; ?></h2>
            <p>Users</p>
        </div>
        <div class="card">
            <h2><?php echo $orders_count; ?></h2>
            <p>Total Orders</p>
        </div>
        <div class="card">
            <h2><?php echo $pending_orders_count; ?></h2>
            <p>Pending Orders</p>
        </div>
        <div class="card">
            <h2><?php echo $completed_orders_count; ?></h2>
            <p>Completed Orders</p>
        </div>
    </div>

    <div class="overview">
        <h3>Recent Orders</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php
            $recent_orders = mysqli_query($conn, "
                SELECT o.*, u.full_name, SUM(oi.quantity*oi.price) AS total_amount
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.user_id
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                GROUP BY o.order_id
                ORDER BY o.created_at DESC
                LIMIT 5
            ");
            while($order = mysqli_fetch_assoc($recent_orders)){
                echo "<tr>
                        <td>#".$order['order_id']."</td>
                        <td>".$order['full_name']."</td>
                        <td>$".number_format($order['total_amount'],2)."</td>
                        <td>".$order['status']."</td>
                        <td>".date("d M Y H:i", strtotime($order['created_at']))."</td>
                    </tr>";
            }
            ?>
        </table>
    </div>

</div>

</body>
</html>