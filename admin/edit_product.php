<?php
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html"); exit;
}
include("../config/db.php");

$id = $_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM products WHERE product_id=$id"));
$categories = mysqli_query($conn,"SELECT * FROM categories");

if(isset($_POST['update'])){
    $name=$_POST['name']; $cat=$_POST['category'];
    $price=$_POST['price']; $stock=$_POST['stock'];
    $desc=$_POST['description'];

    if($_FILES['image']['name']!=""){
        $img=time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],"../uploads/".$img);
        mysqli_query($conn,"UPDATE products SET image='$img' WHERE product_id=$id");
    }

    mysqli_query($conn,"UPDATE products SET
    product_name='$name', category_id='$cat',
    price='$price', stock='$stock', description='$desc'
    WHERE product_id=$id");

    header("Location: products.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>
<style>
body{font-family:Arial;background:#f4f6f9}
.box{width:520px;margin:40px auto;background:#fff;padding:25px;border-radius:12px}
input,select,textarea,button{width:100%;padding:12px;margin-top:12px}
button{background:#198754;color:#fff;border:none}
</style>
</head>
<body>

<div class="box">
<h2>Edit Product</h2>
<form method="POST" enctype="multipart/form-data">
<input name="name" value="<?= $product['product_name'] ?>">
<select name="category">
<?php while($c=mysqli_fetch_assoc($categories)){ ?>
<option value="<?= $c['category_id'] ?>"
<?= $c['category_id']==$product['category_id']?'selected':'' ?>>
<?= $c['category_name'] ?>
</option>
<?php } ?>
</select>
<textarea name="description"><?= $product['description'] ?></textarea>
<input type="number" name="price" value="<?= $product['price'] ?>">
<input type="number" name="stock" value="<?= $product['stock'] ?>">
<input type="file" name="image">
<button name="update">Update Product</button>
</form>
</div>

</body>
</html>