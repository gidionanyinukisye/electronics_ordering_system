<?php
session_start();
include("../config/db.php");

/* =========================
   AUTH CHECK
========================= */
if (!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2) {
    header("Location: ../public/login.html");
    exit;
}

/* =========================
   CART CHECK
========================= */
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$payment_method = "Pay on Delivery";
$status = "Pending";

/* =========================
   CALCULATE TOTAL
========================= */
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

/* =========================
   START TRANSACTION
========================= */
mysqli_begin_transaction($conn);

try {

    /* =========================
       INSERT ORDER
    ========================= */
    $order_sql = "INSERT INTO orders (user_id, total_amount, payment_method, status)
                  VALUES ($user_id, $total_amount, '$payment_method', '$status')";
    mysqli_query($conn, $order_sql);

    $order_id = mysqli_insert_id($conn);

    /* =========================
       INSERT ORDER ITEMS
    ========================= */
    foreach ($_SESSION['cart'] as $item) {

        $product_id = $item['product_id'];
        $qty = $item['quantity'];
        $price = $item['price'];

        // Save order item
        mysqli_query($conn, "
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES ($order_id, $product_id, $qty, $price)
        ");

        // Reduce stock
        mysqli_query($conn, "
            UPDATE products 
            SET stock = stock - $qty 
            WHERE product_id = $product_id
        ");
    }

    /* =========================
       COMMIT
    ========================= */
    mysqli_commit($conn);

    // Clear cart
    unset($_SESSION['cart']);

    $_SESSION['success'] = "Order placed successfully!";
    header("Location: order_success.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);
    die("Order failed: " . $e->getMessage());
}