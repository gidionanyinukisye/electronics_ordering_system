<?php
session_start();
if (
    !isset($_SESSION['auth']) ||
    $_SESSION['auth'] !== true ||
    $_SESSION['role_id'] != 1
) {
    header("Location: ../public/login.html");
    exit;
}

include("../config/db.php");

/* ===== DASHBOARD STATISTICS ===== */

// Total Orders
$totalOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders")
)['total'];

// Pending Orders
$pendingOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='Pending'")
)['total'];

// Delivered Orders
$deliveredOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='Delivered'")
)['total'];

// Total Customers
$totalCustomers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role_id=2")
)['total'];

// Total Products
$totalProducts = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM products")
)['total'];

// Total Revenue (Estimate – COD)
$totalRevenue = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT SUM(oi.price * oi.quantity) AS total
        FROM order_items oi
        JOIN orders o ON oi.order_id=o.order_id
        WHERE o.status='Delivered'
    ")
)['total'] ?? 0;

// Latest Orders
$latestOrders = mysqli_query($conn, "
    SELECT o.order_id, u.full_name, o.status, o.order_date
    FROM orders o
    JOIN users u ON o.user_id=u.user_id
    ORDER BY o.order_date DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background:#f4f7f9;
}
.header{
    background:#0d6efd;
    color:#fff;
    padding:20px;
}
.header h1{margin:0;}
.container{
    padding:20px;
}
.stats{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}
.card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.card h2{
    margin:0;
    font-size:28px;
    color:#0d6efd;
}
.card p{
    margin:5px 0 0;
    color:#555;
}
.actions{
    display:flex;
    gap:15px;
    margin-bottom:30px;
}
.actions a{
    padding:12px 20px;
    background:#0d6efd;
    color:#fff;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
}
.actions a:hover{
    background:#084298;
}
.table-box{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{
    background:#0d6efd;
    color:#fff;
}
.status{
    padding:5px 10px;
    border-radius:6px;
    color:#fff;
    font-size:13px;
}
.Pending{background:#ffc107;color:#000;}
.Delivered{background:#198754;}
.Cancelled{background:#dc3545;}
</style>
</head>

<body>

<div class="header">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= $_SESSION['full_name']; ?></p>
</div>

<div class="container">

    <!-- STATISTICS -->
    <div class="stats">
        <div class="card">
            <h2><?= $totalOrders; ?></h2>
            <p>Total Orders</p>
        </div>
        <div class="card">
            <h2><?= $pendingOrders; ?></h2>
            <p>Pending Orders</p>
        </div>
        <div class="card">
            <h2><?= $deliveredOrders; ?></h2>
            <p>Delivered Orders</p>
        </div>
        <div class="card">
            <h2><?= $totalCustomers; ?></h2>
            <p>Total Customers</p>
        </div>
        <div class="card">
            <h2><?= $totalProducts; ?></h2>
            <p>Total Products</p>
        </div>
        <div class="card">
            <h2>TZS <?= number_format($totalRevenue); ?></h2>
            <p>Total Revenue (COD)</p>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="actions">
        <a href="add_product.php">➕ Add Product</a>
        <a href="products.php">🛒 Manage Products</a>
        <a href="orders.php">📦 View Orders</a>
        <a href="users.php">manage users</a>
        <a href="../public/logout.php">🚪 Logout</a>
    </div>

    <!-- LATEST ORDERS -->
    <div class="table-box">
        <h3>Latest Orders</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($latestOrders)){ ?>
            <tr>
                <td>#<?= $row['order_id']; ?></td>
                <td><?= $row['full_name']; ?></td>
                <td><span class="status <?= $row['status']; ?>"><?= $row['status']; ?></span></td>
                <td><?= $row['order_date']; ?></td>
                <td>
                    <a href="order_details.php?id=<?= $row['order_id']; ?>">View</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>