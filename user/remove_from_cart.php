<?php
session_start();

/* =========================
   CART CHECK
========================= */
if (!isset($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

/* =========================
   VALIDATE REQUEST
========================= */
if (!isset($_GET['product_id'])) {
    header("Location: cart.php");
    exit;
}

$product_id = (int) $_GET['product_id'];

/* =========================
   REMOVE ITEM
========================= */
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

/* =========================
   REDIRECT BACK
========================= */
header("Location: cart.php");
exit;