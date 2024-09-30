<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: /login");
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

// Retrieve the user's cart items
$sql = "SELECT CartItem.cart_item_id, Products.name, Products.price, CartItem.quantity, Products.image
        FROM CartItem
        JOIN Cart ON CartItem.cart_id = Cart.cart_id
        JOIN Products ON CartItem.product_id = Products.product_id
        WHERE Cart.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = array(
            'cart_item_id' => $row['cart_item_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'image' => $row['image']
        );
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shopping Cart - PharmaGains</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .cart {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .cart-header {
            background-color: #3498db;
            color: #fff;
            padding: 15px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #ecf0f1;
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .quantity-control {
            display: flex;
            align-items: center;
        }
        .quantity-control button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .quantity-control button:hover {
            background-color: #2980b9;
        }
        .quantity-control input {
            width: 40px;
            text-align: center;
            margin: 0 10px;
        }
        .remove-item {
            color: #e74c3c;
            cursor: pointer;
            transition: color 0.3s;
        }
        .remove-item:hover {
            color: #c0392b;
        }
        .cart-summary {
            background-color: #ecf0f1;
            padding: 15px;
            text-align: right;
        }
        .cart-total {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-secondary {
            background-color: #95a5a6;
        }
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="cart">
            <div class="cart-header">
                <div>Products in your cart</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr class="cart-item">
                            <td style="display: flex; align-items: center;">
                                <img style="margin-right: 10px;" src="<?php echo "../" . $item['image']; ?>" alt="<?php echo $item['name']; ?>" />
                                <div style="display: flex; flex-direction: column; ">
                                    <span><?php echo $item['name']; ?></span>
                                    <span><?php echo "RM " . $item['price']; ?></span>
                                </div>
                            </td>
                            <td>
                                <form method="post" action="updateQuantity.php" class="quantity-control">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <button type="submit" name="action" value="decrement">-</button>
                                    <input type="number" name="quantity" min="0" max="10" value="<?php echo $item['quantity']; ?>">
                                    <button type="submit" name="action" value="increment">+</button>
                                </form>
                            </td>
                            <td>RM <?php echo $item['price'] * $item['quantity']; ?></td>
                            <td>
                                <a href="removeFromCart.php?cart_item_id=<?php echo $item['cart_item_id']; ?>" class="remove-item" onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-summary">
                <div class="cart-total">
                    Total: RM <?php
                    $total = 0;
                    foreach ($cartItems as $item) {
                        $total += $item['price'] * $item['quantity'];
                    }
                    echo $total;
                    ?>
                </div>
                <?php if($result->num_rows > 0): ?>
                    <a href="../payment" class="btn">Proceed to Checkout</a>
                <?php endif; ?>
                <a href="/" class="btn btn-secondary">Continue Shopping</a>
            </div>
        </div>
    </div>
</body>
</html>
