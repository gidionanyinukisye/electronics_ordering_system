<?php
require "db.php";

$sql = "SELECT p.*, c.category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.category_id
        WHERE p.status='active'
        ORDER BY p.created_at DESC
        LIMIT 8";

$result = $conn->query($sql);

$products = [];
while($row = $result->fetch_assoc()){
    $products[] = $row;
}

echo json_encode($products);