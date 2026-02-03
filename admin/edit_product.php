<?php
session_start();
include("../config/db.php");

// Session check – only admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../public/login.html");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];

// Fetch product
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// Fetch categories
$cat_sql = "SELECT * FROM categories ORDER BY category_name ASC";
$cat_res = mysqli_query($conn, $cat_sql);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    // Image upload handling
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $filename = time().'_'.basename($_FILES['image']['name']);
        $target = "../assets/images/".$filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $filename = $product['image']; // keep old image
    }

    $update_sql = "UPDATE products SET product_name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE product_id=?";
    $stmt2 = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt2, "ssdiisi", $name, $description, $price, $stock, $category_id, $filename, $id);
    mysqli_stmt_execute($stmt2);

    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f6f8;font-family:Arial,sans-serif;}
.container{margin-top:50px;width:600px;}
.card{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
</style>
</head>
<body>
<div class="container">
<div class="card p-4">
<h3 class="mb-4 text-center">Edit Product</h3>
<form method="post" enctype="multipart/form-data">
<div class="mb-3">
<label>Product Name</label>
<input type="text" name="product_name" class="form-control" value="<?= $product['product_name']; ?>" required>
</div>
<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control" rows="3" required><?= $product['description']; ?></textarea>
</div>
<div class="mb-3">
<label>Price ($)</label>
<input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price']; ?>" required>
</div>
<div class="mb-3">
<label>Stock</label>
<input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
</div>
<div class="mb-3">
<label>Category</label>
<select name="category_id" class="form-control" required>
<option value="">--Select Category--</option>
<?php while($cat = mysqli_fetch_assoc($cat_res)) { ?>
<option value="<?= $cat['category_id']; ?>" <?= ($cat['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
<?= $cat['category_name']; ?>
</option>
<?php } ?>
</select>
</div>
<div class="mb-3">
<label>Product Image</label><br>
<?php if($product['image']){ ?>
<img src="../assets/images/<?= $product['image']; ?>" width="100" style="margin-bottom:10px;"><br>
<?php } ?>
<input type="file" name="image" class="form-control" accept="image/*">
</div>
<button type="submit" class="btn btn-primary w-100">Update Product</button>
</form>
</div>
</div>
</body>
</html>