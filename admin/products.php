<?php

session_start();
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}
include("../config/db.php");

// Handle search/filter with sanitization
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn,$_GET['search']) : '';
$filter_cat = isset($_GET['category']) ? mysqli_real_escape_string($conn,$_GET['category']) : '';

// Delete product
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $img = mysqli_fetch_assoc(mysqli_query($conn,"SELECT image FROM products WHERE product_id='$id'"));
    if(file_exists("../uploads/".$img['image'])){
        unlink("../uploads/".$img['image']);
    }
    mysqli_query($conn,"UPDATE products SET is_deleted = 1 WHERE product_id = '$id'");
    header("Location: products.php");
}

// Fetch products with search/filter
$sql = "SELECT p.*, c.category_name 
        FROM products p 
        JOIN categories c ON p.category_id=c.category_id 
        WHERE 1";

if($search != ''){
    $sql .= " AND p.product_name LIKE '%$search%'";
}

if($filter_cat != ''){
    $sql .= " AND p.category_id='$filter_cat'";
}

$sql .= " ORDER BY p.product_id DESC";

$products = mysqli_query($conn, $sql);

// Fetch categories for dropdown
$categories = mysqli_query($conn,"SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin - Manage Products</title>
<style>
body{font-family:Arial;background:#f4f6f9;margin:0;padding:0}
.container{width:95%;margin:20px auto}
h2{text-align:center;margin-bottom:20px}
form{margin-bottom:15px;text-align:center}
input,select{padding:8px;margin-right:5px}
button{padding:8px 12px;background:#0d6efd;color:#fff;border:none;border-radius:5px;cursor:pointer;margin-top:5px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:center}
th{background:#0d6efd;color:#fff}
img{width:60px;border-radius:6px}
a{padding:5px 10px;border-radius:5px;color:#fff;text-decoration:none;margin:2px}
.edit{background:#198754}
.delete{background:#dc3545}
.no-results{color:red;text-align:center;font-weight:bold;padding:10px;}
</style>
</head>
<body>

<div class="container">
<h2>Admin - Product Management</h2>

<!-- Search & Filter -->
<form method="GET">
<input type="text" name="search" placeholder="Search Product..." value="<?= htmlspecialchars($search) ?>">
<select name="category">
<option value="">All Categories</option>
<?php mysqli_data_seek($categories,0); while($c=mysqli_fetch_assoc($categories)){ ?>
<option value="<?= $c['category_id'] ?>" <?= ($filter_cat==$c['category_id'])?'selected':'' ?>><?= $c['category_name'] ?></option>
<?php } ?>
</select>
<button type="submit">Search / Filter</button>
</form>

<!-- Add Product Button -->
<button onclick="openModal()">Add New Product</button>

<!-- Product Table -->
<table>
<tr>
<th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Action</th>
</tr>

<?php if(mysqli_num_rows($products) > 0){ ?>
<?php while($p=mysqli_fetch_assoc($products)){ ?>
<tr>
<td><img src="../uploads/<?= $p['image'] ?>"></td>
<td><?= $p['product_name'] ?></td>
<td><?= $p['category_name'] ?></td>
<td><?= $p['price'] ?></td>
<td><?= $p['stock'] ?></td>
<td>
<button class="edit" onclick="openModal('<?= $p['product_id'] ?>','<?= htmlspecialchars($p['product_name']) ?>','<?= htmlspecialchars($p['description']) ?>','<?= $p['price'] ?>','<?= $p['stock'] ?>','<?= $p['category_id'] ?>','<?= $p['image'] ?>')">Edit</button>
<a class="delete" href="?delete=<?= $p['product_id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
</td>
</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="6" class="no-results">No products found for your search/filter.</td></tr>
<?php } ?>
</table>
</div>

<!-- Add/Edit Modal -->
<div id="modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5)">
<div style="background:#fff;width:400px;margin:50px auto;padding:20px;border-radius:8px;position:relative">
<h3 id="modalTitle">Add Product</h3>
<form method="POST" action="save_product.php" enctype="multipart/form-data">
<input type="hidden" name="product_id" id="product_id">
<label>Name:</label><br>
<input type="text" name="product_name" id="product_name" required><br>
<label>Description:</label><br>
<textarea name="description" id="description" required></textarea><br>
<label>Price:</label><br>
<input type="number" name="price" id="price" required><br>
<label>Stock:</label><br>
<input type="number" name="stock" id="stock" required><br>
<label>Category:</label><br>
<select name="category_id" id="category_id" required>
<?php mysqli_data_seek($categories,0); while($c=mysqli_fetch_assoc($categories)){ ?>
<option value="<?= $c['category_id'] ?>"><?= $c['category_name'] ?></option>
<?php } ?>
</select><br>
<label>Image:</label><br>
<input type="file" name="image" id="image"><br><br>
<button type="submit">Save</button>
<button type="button" onclick="closeModal()">Cancel</button>
</form>
</div>
</div>

<script>
function openModal(id='',name='',desc='',price='',stock='',cat='',img=''){
    document.getElementById('modal').style.display='block';
    document.getElementById('modalTitle').innerText = id ? 'Edit Product' : 'Add Product';
    document.getElementById('product_id').value = id;
    document.getElementById('product_name').value = name;
    document.getElementById('description').value = desc;
    document.getElementById('price').value = price;
    document.getElementById('stock').value = stock;
    document.getElementById('category_id').value = cat;
}
function closeModal(){
    document.getElementById('modal').style.display='none';
}
</script>

</body>
</html>