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
$servername = "167.71.207.105";
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
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        
        /* Header */
        header {
            background-color: #2980b9;
            color: white;
            text-align: center;
            padding: 2rem 0;
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        /* Main content */
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            text-align: center;
        }
        
        .order-details {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .order-details > * + * {
            margin-top: 0.5rem;
        }

        .back-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            .nav-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
   
    <header>
        <h1>Order Completed</h1>
    </header>

    <main>
        <div class="order-details">
            <p>Thank you for your order!</p>
            <p>Order ID: <strong><?php echo $order['order_id']; ?></strong></p>
            <p class="total-price">Total Price: $<?php echo $order['total_price']; ?></p>
            <p>Order Date: <?php echo $order['created_at']; ?></p>
            <a class="back-button" href="/">Back to homepage</a>
        </div>
    </main>
</body>
</html>
