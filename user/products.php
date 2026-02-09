<?php
session_start();
require "../api/db.php";

// Fetch categories for filter dropdown
$categories = [];
$catResult = $conn->query("SELECT category_id, category_name FROM categories");
while($cat = $catResult->fetch_assoc()){
    $categories[$cat['category_id']] = $cat['category_name'];
}

// Handle search and category filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;

$query = "SELECT product_id, product_name, price, image, category_id, stock FROM products WHERE 1";

if(!empty($search)){
    $query .= " AND product_name LIKE '%".$conn->real_escape_string($search)."%'";
}
if($category > 0){
    $query .= " AND category_id = ".$category;
}

$result = $conn->query($query);

// Search & Filter Form
echo "<div style='text-align:center; margin:20px;'>
        <form method='GET' style='display:inline-block;'>
            <input type='text' name='search' placeholder='Search products...' value='".htmlspecialchars($search)."' style='padding:5px; width:200px;'>
            <select name='category' style='padding:5px;'>
                <option value='0'>All Categories</option>";
foreach($categories as $id => $name){
    $selected = ($category == $id) ? 'selected' : '';
    echo "<option value='{$id}' {$selected}>{$name}</option>";
}
echo "  </select>
        <button type='submit' style='padding:5px 10px;'>Filter</button>
        </form>
      </div>";

// Products Grid
echo "<div style='display:flex; flex-wrap:wrap; gap:20px; justify-content:center;'>";

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $product_id = $row['product_id'];
        $product_name = htmlspecialchars($row['product_name']);
        $price = number_format($row['price'], 2);
        $image = !empty($row['image']) ? "../assets/images/".$row['image'] : "../assets/images/no_image.png";

        // Badge
        $badge = '';
        if($row['stock'] <= 0){
            $badge = "<span style='background:red; color:white; padding:2px 5px; font-size:12px; position:absolute; top:10px; left:10px;'>Out of Stock</span>";
        } elseif(stripos($product_name, 'new') !== false){
            $badge = "<span style='background:green; color:white; padding:2px 5px; font-size:12px; position:absolute; top:10px; left:10px;'>New</span>";
        } else {
            $badge = "<span style='background:orange; color:white; padding:2px 5px; font-size:12px; position:absolute; top:10px; left:10px;'>Popular</span>";
        }

        echo "<div style='position:relative; border:1px solid #ddd; border-radius:10px; overflow:hidden; width:220px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center; transition: transform 0.2s;'>
                {$badge}
                <img src='{$image}' alt='{$product_name}' style='width:100%; height:200px; object-fit:cover;'>
                <div style='padding:10px;'>
                    <h3 style='font-size:18px; color:#333;'>{$product_name}</h3>
                    <p style='font-weight:bold; color:#27ae60;'>\${$price}</p>";
        if($row['stock'] > 0){
            echo "<form method='POST' action='add_to_cart.php'>
                    <input type='hidden' name='product_id' value='{$product_id}'>
                    <input type='hidden' name='product_name' value='{$product_name}'>
                    <input type='hidden' name='price' value='{$row['price']}'>
                    <input type='number' name='qty' value='1' min='1' style='width:50px; margin-bottom:5px;'>
                    <br>
                    <button type='submit' style='padding:8px 15px; background-color:#2980b9; color:white; border:none; border-radius:5px; cursor:pointer;'>Add to Cart</button>
                  </form>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>Unavailable</p>";
        }
        echo "</div>
              </div>";
    }
} else {
    echo "<p>No products found.</p>";
}

echo "</div>";
?>

<style>
div:hover {
    transform: scale(1.03);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
button:hover {
    background-color: #3498db;
}
</style>