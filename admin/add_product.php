<?php
session_start();
include("../config/db.php");

// Authentication: admin only
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product | ElectroHub Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
form{
    max-width:500px;
    margin:50px auto;
    background:#fff;
    padding:30px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,.1);
}
form label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}
form input, form select{
    width:100%;
    padding:10px;
    margin-bottom:20px;
    border-radius:8px;
    border:1px solid #ccc;
}
form button{
    background:#0a1a33;
    color:#fff;
    border:none;
    padding:12px 25px;
    border-radius:25px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}
form button:hover{
    background:#124a9f;
}
</style>
</head>
<body>

<h2 style="text-align:center;margin-top:30px;">Add New Product</h2>

<form action="save_product.php" method="POST" enctype="multipart/form-data">
    <label>Product Name</label>
    <input type="text" name="product_name" required>

    <label>Price</label>
    <input type="number" name="price" required>

    <label>Category</label>
    <select name="category_id" required>
        <?php
        $cats = mysqli_query($conn, "SELECT * FROM categories");
        while($cat = mysqli_fetch_assoc($cats)){
            echo '<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
        }
        ?>
    </select>

    <label>Product Image</label>
    <input type="file" name="image" accept="image/png, image/jpeg" required>

    <button type="submit">Save Product</button>
</form>

</body>
</html>