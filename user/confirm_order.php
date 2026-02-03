<?php
session_start();
include("../config/db.php");

// 1️⃣ Check login
if(!isset($_SESSION['user_id'])){
    header("Location: ../public/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    echo "<script>alert('Your cart is empty!'); window.location='products.php';</script>";
    exit();
}

// 2️⃣ Insert into orders table
$status = 'Pending';
$order_sql = "INSERT INTO orders (user_id, order_date, status) VALUES (?, NOW(), ?)";
$stmt = mysqli_prepare($conn, $order_sql);
mysqli_stmt_bind_param($stmt, "is", $user_id, $status);
mysqli_stmt_execute($stmt);

// 3️⃣ Get order_id
$order_id = mysqli_insert_id($conn);

// 4️⃣ Insert products into order_items
foreach($cart as $product_id => $quantity){
    $p_sql = "SELECT price FROM products WHERE product_id=?";
    $p_stmt = mysqli_prepare($conn, $p_sql);
    mysqli_stmt_bind_param($p_stmt, "i", $product_id);
    mysqli_stmt_execute($p_stmt);
    $res = mysqli_stmt_get_result($p_stmt);
    $product = mysqli_fetch_assoc($res);

    $price = $product['price'];

    $oi_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $oi_stmt = mysqli_prepare($conn, $oi_sql);
    mysqli_stmt_bind_param($oi_stmt, "iiid", $order_id, $product_id, $quantity, $price);
    mysqli_stmt_execute($oi_stmt);
}

// 5️⃣ Clear cart
unset($_SESSION['cart']);

// 6️⃣ Redirect to My Orders page
header("Location: my_orders.php");
exit();
?>