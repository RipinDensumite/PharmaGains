<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

$cartItemId = $_GET["cart_item_id"];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmagains";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Remove the item from the CartItem table
$sql = "DELETE FROM CartItem WHERE cart_item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cartItemId);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect back to the cart page
header("Location: index.php");
exit;