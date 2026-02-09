<?php
session_start();
require "../api/db.php";

// Initialize cart if it doesn't exist
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Check required POST data
if(isset($_POST['product_id'], $_POST['product_name'], $_POST['price'], $_POST['qty'])){
    $product_id = intval($_POST['product_id']);
    $product_name = $_POST['product_name'];
    $price = floatval($_POST['price']);
    $qty = intval($_POST['qty']);

    // Check if product already exists in cart
    $found = false;
    foreach($_SESSION['cart'] as &$item){
        if($item['product_id'] == $product_id){
            $item['qty'] += $qty;
            $found = true;
            break;
        }
    }
    if(!$found){
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'qty' => $qty
        ];
    }

    header("Location: cart.php");
    exit;

} else {
    die("Error: Missing product info. Make sure all fields are filled.");
}
?>