<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $email = $_POST['email'];
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postcode = $_POST['postcode'];
    $phone = $_POST['phone'];
    $payment = $_POST['payment_method'];
    $total = $_POST['total'];
    $cartJson = $_POST['order_items'];

    // Insert into orders table
    $sql = "INSERT INTO orders (email, first_name, last_name, country, address, city, state, postcode, phone, payment_method, total)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssd", $email, $first, $last, $country, $address, $city, $state, $postcode, $phone, $payment, $total);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Decode cart JSON
        $cart_items = json_decode($cartJson, true);

        // Insert each item into order_items
        $item_sql = "INSERT INTO order_items (order_id, product_name, product_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)";
        $item_stmt = $conn->prepare($item_sql);

        foreach ($cart_items as $item) {
            $name = $item['name'];
            $price = $item['price'];
            $qty = $item['quantity'];
            $subtotal = $price * $qty;

            $item_stmt->bind_param("isddi", $order_id, $name, $price, $qty, $subtotal);
            $item_stmt->execute();
        }

        // Optional: clear cart
        echo "Order placed successfully. <a href='receipt.php?order_id=$order_id'>View Receipt</a>";

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
