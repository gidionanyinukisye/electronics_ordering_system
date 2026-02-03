<?php
session_start();
include("../config/db.php");

// Session check – only admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../public/login.html");
    exit();
}

// Fetch products with category
$sql = "SELECT p.*, c.category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        ORDER BY p.product_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f6f8;font-family:Arial,sans-serif;}
.container{margin-top:30px;}
.card{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
.table th{background:#0d6efd;color:#fff;}
.table td img{width:80px;height:60px;object-fit:cover;border-radius:6px;}
.btn-edit{background:#198754;color:#fff;}
.btn-edit:hover{background:#157347;}
.btn-delete{background:#dc3545;color:#fff;}
.btn-delete:hover{background:#b02a37;}
.btn-add{background:#0d6efd;color:#fff;border-radius:12px;padding:8px 16px;margin-bottom:15px;}
.btn-add:hover{background:#0b5ed7;}
</style>
</head>
<body>
<div class="container">
<h1 class="text-center mb-4">Manage Products</h1>

<a href="add_product.php" class="btn btn-add">+ Add New Product</a>

<div class="card p-3">
<table class="table table-hover align-middle">
<thead>
<tr>
<th>Image</th>
<th>Product Name</th>
<th>Category</th>
<th>Description</th>
<th>Price ($)</th>
<th>Stock</th>
<th>Created At</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php while($product = mysqli_fetch_assoc($result)) { ?>
<tr>
<td>
<?php if($product['image']){ ?>
<img src="../assets/images/<?= $product['image']; ?>" alt="<?= $product['product_name']; ?>">
<?php } else { echo "No Image"; } ?>
</td>
<td><?= $product['product_name']; ?></td>
<td><?= $product['category_name'] ?? 'N/A'; ?></td>
<td><?= $product['description']; ?></td>
<td><?= number_format($product['price'],2); ?></td>
<td><?= $product['stock']; ?></td>
<td><?= date("d M Y", strtotime($product['created_at'])); ?></td>
<td>
<a href="edit_product.php?id=<?= $product['product_id']; ?>" class="btn btn-edit btn-sm">Edit</a>
<a href="delete_product.php?id=<?= $product['product_id']; ?>" class="btn btn-delete btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</body>
</html>