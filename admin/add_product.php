<?php
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html"); exit;
}
include("../config/db.php");

$categories = mysqli_query($conn,"SELECT * FROM categories");

if(isset($_POST['save'])){
    $name  = $_POST['name'];
    $cat   = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $desc  = $_POST['description'];

    $img = time().'_'.$_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$img);

    mysqli_query($conn,"INSERT INTO products
    (category_id, product_name, description, price, stock, image)
    VALUES ('$cat','$name','$desc','$price','$stock','$img')");

    header("Location: products.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<style>
body{font-family:Arial;background:#f4f6f9}
.box{width:520px;margin:40px auto;background:#fff;padding:25px;border-radius:12px}
input,select,textarea,button{width:100%;padding:12px;margin-top:12px}
button{background:#0d6efd;color:#fff;border:none;font-size:16px}
</style>
</head>
<body>

<div class="box">
<h2>Add Product</h2>
<form method="POST" enctype="multipart/form-data">
<input name="name" placeholder="Product Name" required>

<select name="category" required>
<option value="">Select Category</option>
<?php while($c=mysqli_fetch_assoc($categories)){ ?>
<option value="<?= $c['category_id'] ?>"><?= $c['category_name'] ?></option>
<?php } ?>
</select>

<textarea name="description" placeholder="Description"></textarea>
<input type="number" name="price" placeholder="Price" required>
<input type="number" name="stock" placeholder="Stock" required>
<input type="file" name="image" required>
<button name="save">Save Product</button>
</form>
</div>

</body>
</html>