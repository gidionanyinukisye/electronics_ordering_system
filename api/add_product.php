<?php
session_start();
require_once "../config/db.php";

/* Admin only */
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit();
}

$product_name = trim($_POST['product_name']);
$description  = trim($_POST['description']);
$price        = $_POST['price'];
$stock        = $_POST['stock'];
$category_id  = $_POST['category_id'];

/* Image handling */
$image_name = $_FILES['image']['name'];
$tmp_name   = $_FILES['image']['tmp_name'];

$folder = "../uploads/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$new_image = time() . "_" . basename($image_name);
move_uploaded_file($tmp_name, $folder . $new_image);

/* Insert product */
$sql = "INSERT INTO products
(product_name, description, price, category_id, stock, image, created_at)
VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "ssdiis",
    $product_name,
    $description,
    $price,
    $category_id,
    $stock,
    $new_image
);

mysqli_stmt_execute($stmt);

header("Location: ../admin/products.php");
exit();