<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit();
}

$order_id = $_GET['id'] ?? 0;
if($order_id == 0){
    echo "Invalid Order ID!";
    exit();
}

$sql_order = "SELECT o.*, u.full_name, u.email 
              FROM orders o
              JOIN users u ON o.user_id = u.user_id
              WHERE o.order_id=?";
$stmt_order = mysqli_prepare($conn, $sql_order);
mysqli_stmt_bind_param($stmt_order, "i", $order_id);
mysqli_stmt_execute($stmt_order);
$result_order = mysqli_stmt_get_result($stmt_order);
$order = mysqli_fetch_assoc($result_order);

if(!$order){
    echo "Order not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #2563eb;
            color: #fff;
        }
        .btn-back {
            background-color: #2563eb;
            color: #fff;
            border: none;
        }
        .btn-back:hover {
            background-color: #1e4db7;
        }
    </style>
</head>
<body>
<div class="container mt-5">

    <div class="card p-4">
        <h3 class="mb-4 text-primary">Order Details - Order #<?= $order['order_id'] ?></h3>

        <div class="row mb-3">
            <div class="col-md-4">
                <h6 class="text-secondary">Customer Name:</h6>
                <p><?= htmlspecialchars($order['full_name']) ?></p>
            </div>
            <div class="col-md-4">
                <h6 class="text-secondary">Email:</h6>
                <p><?= htmlspecialchars($order['email']) ?></p>
            </div>
            <div class="col-md-4">
                <h6 class="text-secondary">Status:</h6>
                <p><?= htmlspecialchars($order['status']) ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <h6 class="text-secondary">Order Date:</h6>
                <p><?= $order['order_date'] ?></p>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_items = "SELECT p.product_name, oi.price, oi.quantity, (oi.price * oi.quantity) AS subtotal
                                  FROM order_items oi
                                  JOIN products p ON oi.product_id = p.product_id
                                  WHERE oi.order_id=?";
                    $stmt_items = mysqli_prepare($conn, $sql_items);
                    mysqli_stmt_bind_param($stmt_items, "i", $order_id);
                    mysqli_stmt_execute($stmt_items);
                    $result_items = mysqli_stmt_get_result($stmt_items);

                    $total = 0;
                    while($row = mysqli_fetch_assoc($result_items)){
                        echo "<tr>";
                        echo "<td>".htmlspecialchars($row['product_name'])."</td>";
                        echo "<td>$".$row['price']."</td>";
                        echo "<td>".$row['quantity']."</td>";
                        echo "<td>$".$row['subtotal']."</td>";
                        echo "</tr>";
                        $total += $row['subtotal'];
                    }
                    ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td><strong>$<?= $total ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <a href="orders.php" class="btn btn-back mt-3">← Back to Orders</a>
    </div>
</div>
</body>
</html>