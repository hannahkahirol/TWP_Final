<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $email = $_POST['email'] ?? '';
    $first = $_POST['first_name'] ?? '';
    $last = $_POST['last_name'] ?? '';
    $country = $_POST['country'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $payment = $_POST['payment_method'] ?? '';
    $total = $_POST['total'] ?? 0;
    $cartJson = $_POST['order_items'] ?? '[]';

    // Insert into orders table
    $sql = "INSERT INTO orders (email, first_name, last_name, country, address, city, state, postcode, phone, payment_method, total)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssd", $email, $first, $last, $country, $address, $city, $state, $postcode, $phone, $payment, $total);

    if ($stmt->execute()) {
        // Get the inserted order ID
        $order_id = $stmt->insert_id;

        // Decode JSON cart
        $cart_items = json_decode($cartJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Invalid cart data.");
        }

        // Insert into order_items
        $item_sql = "INSERT INTO order_items (order_id, product_name, product_price, quantity, subtotal)
                     VALUES (?, ?, ?, ?, ?)";
        $item_stmt = $conn->prepare($item_sql);
        if (!$item_stmt) {
            die("Item prepare failed: " . $conn->error);
        }

        foreach ($cart_items as $item) {
            $name = $item['name'];
            $price = $item['price'];
            $qty = $item['quantity'];
            $subtotal = $price * $qty;

            $item_stmt->bind_param("isddi", $order_id, $name, $price, $qty, $subtotal);
            if (!$item_stmt->execute()) {
                die("Item insert failed: " . $item_stmt->error);
            }
        }

        // Redirect to receipt
        header("Location: show_receipt.php?order_id=$order_id");
        exit();

    } else {
        die("Order insert failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request.");
}
?>
