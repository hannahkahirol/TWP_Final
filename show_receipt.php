<?php
include 'config.php';

if (!isset($_GET['order_id'])) {
    die("No order specified.");
}

$order_id = intval($_GET['order_id']);

// Get order info
$order_query = $conn->query("SELECT * FROM orders WHERE id = $order_id");
if ($order_query->num_rows == 0) {
    die("Order not found.");
}
$order = $order_query->fetch_assoc();

// Get order items
$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Receipt</title>
  <style>
    body { font-family: Arial; padding: 40px; max-width: 700px; margin: auto; background: #f9f9f9; }
    h1 { color: #333; }
    .order-details, .order-items { margin-top: 30px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background: #eee; }
    .total { font-weight: bold; }
  </style>
</head>
<body>
  <h1>Order Receipt</h1>

  <div class="order-details">
    <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
    <p><strong>Name:</strong> <?= $order['first_name'] . " " . $order['last_name'] ?></p>
    <p><strong>Email:</strong> <?= $order['email'] ?></p>
    <p><strong>Phone:</strong> <?= $order['phone'] ?></p>
    <p><strong>Address:</strong> <?= $order['address'] ?>, <?= $order['postcode'] ?> <?= $order['city'] ?>, <?= $order['state'] ?>, <?= $order['country'] ?></p>
    <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
    <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
  </div>

  <div class="order-items">
    <h2>Items Ordered</h2>
    <table>
      <tr>
        <th>Product</th>
        <th>Price (RM)</th>
        <th>Quantity</th>
        <th>Subtotal (R
