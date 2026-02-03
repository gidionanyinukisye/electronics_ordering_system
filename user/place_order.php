<?php
session_start();
include("../config/db.php");

// 1️⃣ Check login
if(!isset($_SESSION['user_id'])){
    header("Location: ../public/login.html");
    exit();
}

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    echo "<script>alert('Your cart is empty!'); window.location='products.php';</script>";
    exit();
}

// 2️⃣ Get products details
$cart_products = [];
$total = 0;

foreach($cart as $product_id => $quantity){
    $sql = "SELECT product_name, price FROM products WHERE product_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($res);

    $product['quantity'] = $quantity;
    $product['subtotal'] = $quantity * $product['price'];
    $total += $product['subtotal'];

    $cart_products[] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;}
        .container{width:90%;margin:auto;padding:20px;}
        h1{text-align:center;margin-bottom:20px;}
        table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.2);}
        th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
        th{background:#28a745;color:#fff;}
        tfoot td{font-weight:bold;}
        .btn{padding:8px 15px;background:#28a745;color:#fff;border:none;border-radius:5px;cursor:pointer;}
        .btn:hover{background:#218838;}
    </style>
</head>
<body>
<div class="container">
    <h1>Review Your Order</h1>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price ($)</th>
                <th>Quantity</th>
                <th>Subtotal ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cart_products as $item){ ?>
            <tr>
                <td><?= $item['product_name']; ?></td>
                <td><?= number_format($item['price'],2); ?></td>
                <td><?= $item['quantity']; ?></td>
                <td><?= number_format($item['subtotal'],2); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td><?= number_format($total,2); ?></td>
            </tr>
        </tfoot>
    </table>

    <form action="confirm_order.php" method="post" style="margin-top:20px;">
        <button type="submit" class="btn">Confirm Order</button>
    </form>
</div>
</body>
</html>