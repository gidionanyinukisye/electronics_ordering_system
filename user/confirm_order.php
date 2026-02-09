<?php
session_start();
require "../api/db.php";

if(!isset($_SESSION['user_id'])){
    die("Please login first to confirm order");
}

if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0){
    die("Cart is empty");
}

$user_id = $_SESSION['user_id'];
$total = 0;

// calculate total
foreach($_SESSION['cart'] as $item){
    $total += floatval($item['price']) * intval($item['qty']);
}

// insert into orders
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, order_date) VALUES (?, ?, 'Pending', NOW())");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// insert into order_items
foreach($_SESSION['cart'] as $item){
    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $order_id, $item['product_id'], $item['qty'], $item['price']);
    $stmt2->execute();
}

// clear cart
unset($_SESSION['cart']);

echo "Order confirmed! Your order ID is: $order_id";
?>