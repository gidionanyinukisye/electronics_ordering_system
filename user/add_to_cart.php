<?php
session_start();

// Check if POST data exists
if(!isset($_POST['product_id'], $_POST['quantity'])){
    header("Location: products.php");
    exit();
}

$product_id = $_POST['product_id'];
$quantity   = $_POST['quantity'];

// Initialize cart if empty
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Add or update product in cart
if(isset($_SESSION['cart'][$product_id])){
    $_SESSION['cart'][$product_id] += $quantity; // update quantity
} else {
    $_SESSION['cart'][$product_id] = $quantity; // new item
}

// Redirect back to products page
header("Location: products.php");
exit();
?>