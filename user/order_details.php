<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "Order not found!";
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id  = $_SESSION['user_id'];

/* Fetch order */
$order = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE order_id = '$order_id' 
    AND user_id = '$user_id'
");

if (mysqli_num_rows($order) == 0) {
    echo "Order not found!";
    exit();
}

$orderData = mysqli_fetch_assoc($order);

/* Fetch order items */
$items = mysqli_query($conn, "
    SELECT oi.*, p.product_name, p.price, p.image 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = '$order_id'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f6f8;
        }
        .container{
            width:90%;
            margin:30px auto;
        }
        .card{
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }
        h2{
            color:#2c3e50;
        }
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }
        th, td{
            padding:12px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }
        th{
            background:#2c3e50;
            color:white;
        }
        img{
            width:70px;
            border-radius:8px;
        }
        .status{
            padding:6px 12px;
            border-radius:20px;
            color:white;
            font-size:14px;
        }
        .Pending{ background:orange; }
        .Confirmed{ background:green; }
        .Cancelled{ background:red; }
        .total{
            font-size:18px;
            font-weight:bold;
            color:#27ae60;
        }
        a.back{
            display:inline-block;
            margin-top:20px;
            text-decoration:none;
            background:#3498db;
            color:white;
            padding:10px 20px;
            border-radius:8px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Order #<?= $orderData['order_id']; ?></h2>
        <p><strong>Date:</strong> <?= $orderData['order_date']; ?></p>
        <p>
            <strong>Status:</strong>
            <span class="status <?= $orderData['status']; ?>">
                <?= $orderData['status']; ?>
            </span>
        </p>

        <table>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>

            <?php 
            $grandTotal = 0;
            while($row = mysqli_fetch_assoc($items)):
                $sub = $row['price'] * $row['quantity'];
                $grandTotal += $sub;
            ?>
            <tr>
                <td>
                    <img src="../uploads/<?= $row['image']; ?>">
                </td>
                <td><?= $row['product_name']; ?></td>
                <td>$<?= number_format($row['price'],2); ?></td>
                <td><?= $row['quantity']; ?></td>
                <td>$<?= number_format($sub,2); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <p class="total">Total Amount: $<?= number_format($grandTotal,2); ?></p>

        <a href="my_orders.php" class="back">⬅ Back to My Orders</a>
    </div>
</div>

</body>
</html>