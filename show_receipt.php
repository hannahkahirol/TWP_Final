<?php
include 'config.php';

if (!isset($_GET['order_id'])) {
    die("No order specified.");
}

$order_id = intval($_GET['order_id']);

$order_query = $conn->query("SELECT * FROM orders WHERE id = $order_id");
if ($order_query->num_rows == 0) {
    die("Order not found.");
}
$order = $order_query->fetch_assoc();

$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TFH Order Receipt</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 30px;
    }
    .receipt-container {
      max-width: 700px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    h1 {
      font-size: 28px;
      color: #222;
      margin-bottom: 20px;
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
    }
    .section {
      margin-bottom: 25px;
    }
    .section h2 {
      font-size: 20px;
      margin-bottom: 10px;
      color: #444;
    }
    .section p {
      margin: 6px 0;
      color: #555;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      text-align: left;
      padding: 12px;
      border-bottom: 1px solid #eee;
    }
    th {
      background-color: #f5f5f5;
    }
    .total-row td {
      font-weight: bold;
      border-top: 2px solid #ddd;
    }
    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #fff;
      background: #222;
      padding: 10px 20px;
      border-radius: 6px;
      transition: 0.3s ease;
    }
    .back-link:hover {
      background: #FF577F;
    }
  </style>
</head>
<body>
  <div class="receipt-container">
    <h1>Order Receipt</h1>

    <div class="section">
      <h2>Order Information</h2>
      <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
      <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
      <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
    </div>

    <div class="section">
      <h2>Customer Information</h2>
      <p><strong>Name:</strong> <?= $order['first_name'] . " " . $order['last_name'] ?></p>
      <p><strong>Email:</strong> <?= $order['email'] ?></p>
      <p><strong>Phone:</strong> <?= $order['phone'] ?></p>
    </div>

    <div class="section">
      <h2>Shipping Address</h2>
      <p><?= $order['address'] ?></p>
      <p><?= $order['postcode'] ?> <?= $order['city'] ?>, <?= $order['state'] ?></p>
      <p><?= $order['country'] ?></p>
    </div>

    <div class="section">
      <h2>Order Items</h2>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Price (RM)</th>
            <th>Qty</th>
            <th>Subtotal (RM)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          while ($item = $items->fetch_assoc()):
            $total += $item['subtotal'];
          ?>
          <tr>
            <td><?= $item['product_name'] ?></td>
            <td><?= number_format($item['product_price'], 2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['subtotal'], 2) ?></td>
          </tr>
          <?php endwhile; ?>
          <tr class="total-row">
            <td colspan="3" style="text-align:right;">Grand Total:</td>
            <td>RM <?= number_format($total, 2) ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <a class="back-link" href="Checkout.html">← Return to Shop</a>
  </div>
</body>
</html>
