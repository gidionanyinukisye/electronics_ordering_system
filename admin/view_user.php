<?php
include 'auth_check.php';
include '../config/db.php';

$user_id = $_GET['id'];

// Pata user info
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Pata order history ya user
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$user_id ORDER BY order_date DESC");

include 'header.php';
?>

<div class="container">
<h2>Customer: <?php echo $user['name']; ?></h2>
<p><strong>Email:</strong> <?php echo $user['email']; ?></p>
<p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
<p><strong>Status:</strong> <span class="badge <?php echo strtolower($user['status']); ?>"><?php echo $user['status']; ?></span></p>

<hr>

<h3>Order History</h3>

<?php if($orders->num_rows>0): ?>
  <?php while($order=$orders->fetch_assoc()): ?>
    <h4>Order #<?php echo $order['id']; ?> | Date: <?php echo $order['order_date']; ?> | Status: <?php echo $order['status']; ?></h4>

    <table border="1" cellpadding="10">
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
      </tr>

      <?php
      $items = $conn->query("
        SELECT oi.*, p.name
        FROM order_items oi
        JOIN products p ON oi.product_id=p.id
        WHERE oi.order_id={$order['id']}
      ");
      $orderTotal=0;
      while($item=$items->fetch_assoc()){
        $total=$item['price']*$item['quantity'];
        $orderTotal+=$total;
        echo "<tr>
               <td>{$item['name']}</td>
               <td>{$item['quantity']}</td>
               <td>{$item['price']}</td>
               <td>{$total}</td>
              </tr>";
      }
      ?>
      <tr>
        <th colspan="3">Order Total</th>
        <th><?php echo number_format($orderTotal,2); ?></th>
      </tr>
    </table>
  <?php endwhile; ?>
<?php else: ?>
  <p>No orders found for this customer.</p>
<?php endif; ?>
</div>

<?php include 'footer.php'; ?>