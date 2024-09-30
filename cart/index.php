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
    <title>Cart</title>
    <style>
        table {
            border: 1px solid black;
            padding: 10px;
            margin: 10px;
        }

        td {
            padding-right: 15px;
            padding-left: 15px;
        }

        th {
            padding: 10px;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1 align="center">Shopping cart</h1>
    <div align="center">
        <table>
            <tr>
                <th colspan="2">Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td>
                        <img src="<?php echo "../" . $item['image']; ?>" width="100" height="100" />
                    </td>
                    <td><?php echo $item['name']; ?></td>
                    <td>
                    <form method="post" action="updateQuantity.php">
                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                        <div>
                            <button type="submit" name="action" value="decrement">-</button>
                                <input type="number" name="quantity" min="0" max="10" value="<?php echo $item['quantity']; ?>">
                            <button type="submit" name="action" value="increment">+</button>
                        </div>
                    </form>
                    </td>
                    <td>$<?php echo $item['price']; ?></td>
                    <td>
                    <a href="removeFromCart.php?cart_item_id=<?php echo $item['cart_item_id']; ?>" onclick="return confirm('Are you sure?'); return false;">Delete</a>
                </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <hr />
                    </td>
                </tr>
            <?php endforeach; ?>

            <!-- Start of User Input -->
            <tr>
                <td align="left" colspan="3">Total</td>
                <td>
                    $<?php
                    $total = 0;
                    foreach ($cartItems as $item) {
                        $total += $item['price'] * $item['quantity'];
                    }
                    echo $total;
                    ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="center" colspan="5">
                    <a href="../payment">
                        <?php if($result->num_rows > 0){
                        echo "<button style='width: 100%; padding: 10px; margin: 3px' type='button'>Submit</button>";
                        }
                        ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="5">
                    <a href="/">
                        <button style="width: 100%; padding: 10px; margin: 3px" type="button">Continue Shopping</button>
                    </a>
                </td>
            </tr>
            <!-- End of User Input -->
        </table>
    </div>
</body>
</html>