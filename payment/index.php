<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user_id"];

// Connect to the database
$servername = "167.71.207.105";
$username = "root";
$password = "";
$dbname = "pharmagains";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $phoneNumber = $_POST["phonenumber"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $state = $_POST["state"];
    $postalCode = $_POST["poscode"];
    $paymentMethod = $_POST["paymentmethod"];
    $nameOnCard = $_POST["nameoncard"];
    $cardNumber = $_POST["cardnumber"];
    $expiryDate = $_POST["expirydate"];
    $cvc = $_POST["cvc"];

    // Calculate the total price from the cart
    $sql = "SELECT SUM(Products.price * CartItem.quantity) AS total_price
            FROM CartItem
            JOIN Cart ON CartItem.cart_id = Cart.cart_id
            JOIN Products ON CartItem.product_id = Products.product_id
            WHERE Cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalPrice = $row['total_price'];

    // Insert the order into the Orders table
    $sql = "INSERT INTO Orders (user_id, total_price) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $userId, $totalPrice);
    $stmt->execute();

    // Get the last inserted order ID
    $orderId = $conn->insert_id;

    // Clear the cart after placing the order
    $sql = "DELETE FROM CartItem WHERE cart_id = (SELECT cart_id FROM Cart WHERE user_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    // Redirect to a success page or display a success message
    header("Location: orderComplete.php?order_id=" . $orderId);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment - PharmaGains</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .payment-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
        }
        .form-image {
            flex: 1;
            background-image: url('../public/image/Logo/LOGO.jpg');
            background-size: cover;
            background-position: center;
        }
        .form-fields {
            flex: 2;
            padding: 30px;
        }
        .form-row {
            display: flex;
            margin-bottom: 20px;
        }
        .form-group {
            flex: 1;
            margin-right: 20px;
        }
        .form-group:last-child {
            margin-right: 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }
        .form-actions {
            text-align: right;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-secondary {
            background-color: #95a5a6;
            margin-right: 10px;
        }
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        @media (max-width: 768px) {
            .payment-form {
                flex-direction: column;
            }
            .form-image {
                height: 200px;
            }
            .form-row {
                flex-direction: column;
            }
            .form-group {
                margin-right: 0;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="payment-form">
            <div class="form-image"></div>
            <div class="form-fields">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" required />
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phonenumber">Phone number:</label>
                        <input type="text" id="phonenumber" name="phonenumber" required />
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" placeholder="House Number, Building, Street Name" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="state">State:</label>
                        <select id="state" name="state" required>
                            <option value="Selangor">Selangor</option>
                            <option value="Sarawak">Sarawak</option>
                            <option value="Sabah">Sabah</option>
                            <option value="Kedah">Kedah</option>
                            <option value="N9">N.Sembilan</option>
                            <option value="Perak">Perak</option>
                            <option value="Pahang">Pahang</option>
                            <option value="Kelantan">Kelantan</option>
                            <option value="Terengganu">Terengganu</option>
                            <option value="Perlis">Perlis</option>
                            <option value="Johor">Johor</option>
                            <option value="P.Penang">P.Penang</option>
                            <option value="Melaka">Melaka</option>
                            <option value="KL">Kuala Lumpur</option>
                            <option value="KL">Putrajaya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="poscode">Poscode:</label>
                        <input type="text" id="poscode" name="poscode" placeholder="43000" maxlength="5" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="paymentmethod">Payment method:</label>
                    <select id="paymentmethod" name="paymentmethod" required>
                        <option value="Online Banking">Online Banking</option>
                        <option value="Credit/Debit Card">Credit/Debit Card</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nameoncard">Name on Card:</label>
                        <input type="text" id="nameoncard" name="nameoncard" required />
                    </div>
                    <div class="form-group">
                        <label for="cardnumber">Credit/Debit Card Number:</label>
                        <input type="text" id="cardnumber" name="cardnumber" maxlength="16" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="expirydate">Expiry Date:</label>
                        <input type="text" id="expirydate" name="expirydate" placeholder="MM/YY" maxlength="5" required />
                    </div>
                    <div class="form-group">
                        <label for="cvc">CVC:</label>
                        <input type="text" id="cvc" name="cvc" placeholder="123" maxlength="3" required />
                    </div>
                </div>
                <div class="form-actions">
                    <a href="/cart" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn">Confirm Payment</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>