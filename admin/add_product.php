<?php
session_start();
include("../config/db.php");

/* =========================
   AUTH CHECK (ADMIN)
========================= */
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}

/* =========================
   FETCH CATEGORIES
========================= */
$categories = mysqli_query($conn, "SELECT * FROM categories WHERE 1");

/* =========================
   HANDLE FORM SUBMISSION
========================= */
$message = '';
if(isset($_POST['add_product'])){
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int) $_POST['category_id'];
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
        // Insert into database
        $sql = "INSERT INTO products (product_name, description, category_id, price, stock, image, is_deleted)
                VALUES ('$name', '$description', $category_id, $price, $stock, '$image', 0)";
        if(mysqli_query($conn, $sql)){
            $message = "Product added successfully!";
        } else {
            $message = "Database error: " . mysqli_error($conn);
        }
    } else {
        $message = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product | Admin</title>
<style>
body{font-family:Segoe UI,sans-serif;background:#f4f6fb;margin:0;padding:0;}
.container{width:90%;margin:30px auto;background:#fff;padding:25px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,.1);}
h2{text-align:center;color:#2c3e50;margin-bottom:25px;}
form{max-width:600px;margin:auto;}
label{display:block;margin:10px 0 5px;font-weight:bold;}
input[type=text], input[type=number], textarea, select{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
    margin-bottom:10px;
    box-sizing:border-box;
}
textarea{resize:vertical;}
button{
    padding:12px 20px;
    background:#0a1a33;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
button:hover{background:#124a9f;}
.message{text-align:center;margin-bottom:15px;color:green;}
</style>
</head>
<body>

<div class="container">
    <h2>Add New Product</h2>

    <?php if($message != ''){ echo "<p class='message'>$message</p>"; } ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>Product Name</label>
        <input type="text" name="product_name" required>

        <label>Description</label>
        <textarea name="description" rows="5" required></textarea>

        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while($cat = mysqli_fetch_assoc($categories)){ ?>
                <option value="<?= $cat['category_id']; ?>"><?= htmlspecialchars($cat['category_name']); ?></option>
            <?php } ?>
        </select>

        <label>Price ($)</label>
        <input type="number" name="price" step="0.01" required>

        <label>Stock</label>
        <input type="number" name="stock" required>

        <label>Product Image</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>
</div>

</body>
</html>