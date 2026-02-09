<?php
include("../config/db.php");

if(!isset($_GET['user_id'])) exit;

$user_id = intval($_GET['user_id']);

$sql = "SELECT o.order_id, o.order_date, o.status AS order_status, 
        p.product_name, oi.quantity, oi.price, (oi.quantity*oi.price) AS subtotal
        FROM orders o
        JOIN order_items oi ON o.order_id=oi.order_id
        JOIN products p ON oi.product_id=p.product_id
        WHERE o.user_id='$user_id'
        ORDER BY o.order_date DESC";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) > 0){
    $currentOrder = 0;
    echo '<div>';
    while($row = mysqli_fetch_assoc($result)){
        if($currentOrder != $row['order_id']){
            if($currentOrder!=0) echo '</table><hr>';
            $currentOrder = $row['order_id'];
            echo '<h3>Order ID: '.$row['order_id'].' | Status: '.$row['order_status'].' | Date: '.date("d M Y", strtotime($row['order_date'])).'</h3>';
            echo '<table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse;margin-bottom:10px;">';
            echo '<tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>';
        }
        echo '<tr>';
        echo '<td>'.htmlspecialchars($row['product_name']).'</td>';
        echo '<td>'.$row['quantity'].'</td>';
        echo '<td>'.$row['price'].'</td>';
        echo '<td>'.$row['subtotal'].'</td>';
        echo '</tr>';
    }
    echo '</table></div>';
}else{
    echo '<p>No orders found for this user.</p>';
}
?>