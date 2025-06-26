<?php
include 'config.php';

if (!isset($_GET['order_id'])) {
    die("No order specified.");
}

$order_id = intval($_GET['order_id']);

// Get order info
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<h1>Order Receipt</h1>
<p><strong>Order ID:</strong> <?= $order['id'] ?></p>
<p><strong>Customer:</strong> <?= $order['first_name'] . " " . $order['last_name'] ?></p>
<p><strong>Total:</strong> RM <?= number_format($order['total'], 2) ?></p>
<p><strong>Date:</strong> <?= $order['order_date'] ?></p>

<h2>Items:</h2>
<ul>
<?php while ($item = $items->fetch_assoc()): ?>
  <li><?= $item['product_name'] ?> - RM <?= number_format($item['product_price'], 2) ?> x <?= $item['quantity'] ?> = <strong>RM <?= number_format($item['subtotal'], 2) ?></strong></li>
<?php endwhile; ?>
</ul>

<a href="Checkout.html">Return to Shop</a>
