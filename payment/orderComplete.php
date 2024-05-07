<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

$orderId = $_GET["order_id"];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmagains";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve order details
$sql = "SELECT Orders.order_id, Orders.total_price, Orders.created_at
        FROM Orders
        WHERE Orders.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Complete</title>
</head>
<body style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
    <h1>Order Completed</h1>
    <p>Thank you for your order!</p>
    <p>Order ID: <?php echo $order['order_id']; ?></p>
    <p>Total Price: $<?php echo $order['total_price']; ?></p>
    <p>Order Date: <?php echo $order['created_at']; ?></p>
    <!-- Add any additional information or actions here -->
    <a href="/pharmagains">Back to homepage</a>
</body>
</html>