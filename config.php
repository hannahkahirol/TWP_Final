<?php
$host = "localhost";
$username = "root";
$password = ""; // XAMPP default
$database = "tfh";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
