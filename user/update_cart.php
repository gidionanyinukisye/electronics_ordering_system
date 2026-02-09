<?php
session_start();
if(isset($_POST['index'], $_POST['qty'])){
    $index = intval($_POST['index']);
    $qty = intval($_POST['qty']);

    if(isset($_SESSION['cart'][$index])){
        $_SESSION['cart'][$index]['qty'] = $qty;
    }
}
header("Location: cart.php");
exit;
?>