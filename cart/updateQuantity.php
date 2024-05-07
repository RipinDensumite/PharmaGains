<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user_id"];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmagains";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['action'])) {
    $cartItemId = $_POST['cart_item_id'];
    $action = $_POST['action'];

    // Get the current quantity from the database
    $sql = "SELECT quantity FROM CartItem WHERE cart_item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartItemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $currentQuantity = $row['quantity'];

    if ($action === 'increment') {
        $newQuantity = min($currentQuantity + 1, 10); // Limit the maximum quantity to 10
    } else {
        $newQuantity = max($currentQuantity - 1, 0); // Ensure the quantity doesn't go below 0
    }

    // Update the quantity in the CartItem table
    $sql = "UPDATE CartItem SET quantity = ? WHERE cart_item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newQuantity, $cartItemId);
    $stmt->execute();

    $stmt->close();
}

$conn->close();

// Redirect back to the cart page
header("Location: index.php");
exit;