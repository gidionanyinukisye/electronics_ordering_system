<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH ORDERS
========================= */
$orders = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE user_id = '$user_id' 
    ORDER BY order_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f6f8;padding:20px;}
        table{width:100%;border-collapse:collapse;background:white;border-radius:10px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,.1);}
        th,td{padding:12px;text-align:center;border-bottom:1px solid #ddd;}
        th{background:#2c3e50;color:white;}
        a.details{color:#3498db;text-decoration:none;}
    </style>
</head>
<body>

<h1>My Orders</h1>

<table>
    <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Status</th>
        <th>Total</th>
        <th>Action</th>
    </tr>

    <?php while($order = mysqli_fetch_assoc($orders)): ?>
        <tr>
            <td><?= $order['order_id']; ?></td>
            <td><?= $order['order_date']; ?></td>
            <td><?= $order['status']; ?></td>
            <td>$<?= number_format($order['total_amount'],2); ?></td>
            <td>
                <a class="details" href="order_details.php?order_id=<?= $order['order_id']; ?>">View Details</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>