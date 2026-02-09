<?php
session_start();
require "../api/db.php";

$total = 0;

echo "<h2>Your Cart</h2>";
echo "<table border='1' cellpadding='10' cellspacing='0' style='width:90%; margin:auto; border-collapse:collapse; text-align:center;'>";
echo "<tr style='background-color:#f2f2f2;'>
        <th>Product</th>
        <th>Price ($)</th>
        <th>Qty</th>
        <th>Subtotal ($)</th>
        <th>Action</th>
      </tr>";

if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
    foreach($_SESSION['cart'] as $index => $item){
        $product_name = isset($item['product_name']) ? $item['product_name'] : "Unknown Product";
        $price = isset($item['price']) ? floatval($item['price']) : 0;
        $qty = isset($item['qty']) ? intval($item['qty']) : 0;
        $subtotal = $price * $qty;
        $total += $subtotal;

        echo "<tr>
                <td>{$product_name}</td>
                <td>{$price}</td>
                <td>
                    <form method='POST' action='update_cart.php'>
                        <input type='hidden' name='index' value='{$index}'>
                        <input type='number' name='qty' value='{$qty}' min='1' style='width:50px;'>
                        <button type='submit'>Update</button>
                    </form>
                </td>
                <td>{$subtotal}</td>
                <td>
                    <form method='POST' action='remove_from_cart.php'>
                        <input type='hidden' name='index' value='{$index}'>
                        <button type='submit'>Remove</button>
                    </form>
                </td>
              </tr>";
    }

    echo "<tr style='font-weight:bold; background-color:#e6ffe6;'>
            <td colspan='3'>Total</td>
            <td colspan='2'>{$total}</td>
          </tr>";

    echo "<tr>
            <td colspan='5'>
                <form method='POST' action='confirm_order.php'>
                    <button type='submit' style='padding:10px 20px; font-size:16px;'>Confirm Order</button>
                </form>
            </td>
          </tr>";

} else {
    echo "<tr><td colspan='5'>Your cart is empty</td></tr>";
}

echo "</table>";
?>