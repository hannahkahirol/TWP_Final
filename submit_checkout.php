<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    $sql = "INSERT INTO orders (email, first_name, last_name, country, address, city, state, postcode, phone, payment_method, total)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssd", $email, $first, $last, $country, $address, $city, $state, $postcode, $phone, $payment, $total);

    if ($stmt->execute()) {
        echo "Order placed successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
