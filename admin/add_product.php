<?php
session_start();
include("../config/db.php");

// Session check – only admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../public/login.html");
    exit();
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $filename = time().'_'.basename($_FILES['image']['name']);
        $target = "../assets/images/".$filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $filename = null; // no image uploaded
    }

    // Insert into database
    $sql = "INSERT INTO products (product_name, description, price, stock, category_id, image, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssdiis", $name, $description, $price, $stock, $category_id, $filename);
    mysqli_stmt_execute($stmt);

    header("Location: products.php");
    exit();
}

// Fetch categories for dropdown
$cat_sql = "SELECT * FROM categories ORDER BY category_name ASC";
$cat_res = mysqli_query($conn, $cat_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product</title>
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
<h3 class="mb-4 text-center">Add New Product</h3>
<form method="post" enctype="multipart/form-data">
<div class="mb-3">
<label>Product Name</label>
<input type="text" name="product_name" class="form-control" required>
</div>
<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control" rows="3" required></textarea>
</div>
<div class="mb-3">
<label>Price ($)</label>
<input type="number" step="0.01" name="price" class="form-control" required>
</div>
<div class="mb-3">
<label>Stock</label>
<input type="number" name="stock" class="form-control" required>
</div>
<div class="mb-3">
<label>Category</label>
<select name="category_id" class="form-control" required>
<option value="">--Select Category--</option>
<?php while($cat = mysqli_fetch_assoc($cat_res)) { ?>
<option value="<?= $cat['category_id']; ?>"><?= $cat['category_name']; ?></option>
<?php } ?>
</select>
</div>
<div class="mb-3">
<label>Product Image</label>
<input type="file" name="image" class="form-control" accept="image/*">
</div>
<button type="submit" class="btn btn-primary w-100">Add Product</button>
</form>
</div>
</div>
</body>
</html>