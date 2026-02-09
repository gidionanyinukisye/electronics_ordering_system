<?php
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html"); exit;
}
include("../config/db.php");

$categories = mysqli_query($conn,"SELECT * FROM categories");

if(isset($_POST['save'])){
    $name  = trim($_POST['name'] ?? '');
    $cat   = intval($_POST['category'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $desc  = trim($_POST['description'] ?? '');

    // Validate required fields
    $errors = [];
    if ($name === '') $errors[] = 'Product name is required.';
    if ($cat <= 0) $errors[] = 'Please select a category.';
    if ($price <= 0) $errors[] = 'Price must be greater than zero.';
    if ($stock < 0) $errors[] = 'Stock must be zero or greater.';

    // Image upload handling
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0777, true);
    }

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['image']['tmp_name'];
        $origName = $_FILES['image']['name'];
        $imgSize = $_FILES['image']['size'];

        $imgInfo = @getimagesize($tmp);
        if ($imgInfo === false) {
            $errors[] = 'Uploaded file is not a valid image.';
        } else {
            $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];
            if (!in_array($imgInfo[2], $allowedTypes, true)) {
                $errors[] = 'Only JPG, PNG and GIF images are allowed.';
            }
            // Limit to 5MB
            if ($imgSize > 5 * 1024 * 1024) {
                $errors[] = 'Image must be 5MB or smaller.';
            }
        }

        if (empty($errors)) {
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $imageName = time() . '_' . bin2hex(random_bytes(6)) . '.' . strtolower($ext);
            if (!move_uploaded_file($tmp, $uploadDir . $imageName)) {
                $errors[] = 'Failed to move uploaded file.';
            }
        }
    } else {
        $errors[] = 'Product image is required.';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO products (category_id, product_name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'issdis', $cat, $name, $desc, $price, $stock, $imageName);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("Location: products.php");
            exit;
        } else {
            $errors[] = 'Database error: could not prepare statement.';
        }
    }
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
<?php if (!empty($errors)) : ?>
    <div style="background:#ffe6e6;border:1px solid #ffb3b3;padding:10px;border-radius:6px;margin-bottom:12px;color:#900;">
        <strong>The following errors occurred:</strong>
        <ul>
        <?php foreach ($errors as $err) : ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<input name="name" placeholder="Product Name" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">

<select name="category" required>
<option value="">Select Category</option>
<?php
    // Rewind categories result pointer if needed
    if ($categories && mysqli_num_rows($categories) > 0) {
        mysqli_data_seek($categories, 0);
        while($c = mysqli_fetch_assoc($categories)){
            $sel = (isset($cat) && $cat == $c['category_id']) ? 'selected' : '';
            echo '<option value="' . $c['category_id'] . '" ' . $sel . '>' . htmlspecialchars($c['category_name']) . '</option>';
        }
    }
?>
</select>

<textarea name="description" placeholder="Description"><?= isset($desc) ? htmlspecialchars($desc) : '' ?></textarea>
<input type="number" step="0.01" name="price" placeholder="Price" required value="<?= isset($price) ? htmlspecialchars($price) : '' ?>">
<input type="number" name="stock" placeholder="Stock" required value="<?= isset($stock) ? htmlspecialchars($stock) : '' ?>">
<input type="file" name="image" <?= empty($imageName) ? 'required' : '' ?>>
<button name="save">Save Product</button>
</form>
</div>

</body>
</html>