<?php
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html"); exit;
}
include("../config/db.php");

$id = $_GET['id'];
mysqli_query($conn,"DELETE FROM products WHERE product_id=$id");
header("Location: products.php");