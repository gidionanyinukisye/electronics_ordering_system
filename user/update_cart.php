<?php
session_start();

/* =========================
   CHECK CART
========================= */
if (!isset($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

/* =========================
   VALIDATE INPUT
========================= */
if (!isset($_POST['product_id'], $_POST['quantity'])) {
    header("Location: cart.php");
    exit;
}

$product_id = (int) $_POST['product_id'];
$quantity = (int) $_POST['quantity'];

/* =========================
   UPDATE CART
========================= */
if ($quantity <= 0) {
    unset($_SESSION['cart'][$product_id]);
} else {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
}

/* =========================
   REDIRECT BACK
========================= */
header("Location: cart.php");
exit;