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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $phoneNumber = $_POST["Phone number"];
    $email = $_POST["Email"];
    $address = $_POST["Address"];
    $state = $_POST["state"];
    $postalCode = $_POST["Poscode"];
    $paymentMethod = $_POST["paymentmethod"];
    $nameOnCard = $_POST["Name on Card"];
    $cardNumber = $_POST["Credit/Debit Card Number"];
    $expiryDate = $_POST["Expiry Date"];
    $cvc = $_POST["CVC"];

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
    <title>Payment</title>
    <style>
      table {
        padding: 15px 0 15px 0;
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
  <body bgcolor="#494343">
    <h1 align="center"><font color="white">Payment</font></h1>
    <div align="center">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table bgcolor="white">
          <tr>
            <td align="left" rowspan="14">
              <img
                src="../public/image/Logo/LOGO.jpg"
                width="400"
                height="400"
              />
            </td>
            <td align="left">First Name:</td>
            <td align="left">Last Name:</td>
          </tr>

          <tr>
            <td align="center">
              <input type="text" name="firstname" required />
            </td>
            <td align="center">
              <input type="text" name="lastname" required />
            </td>
          </tr>

          <tr>
            <td align="left">Phone number:</td>
            <td align="left">Email:</td>
          </tr>
          <tr>
            <td align="center">
              <input type="text" name="Phone number" required />
            </td>
            <td align="center">
              <input type="text" name="Email" required />
            </td>
          </tr>

          <tr>
            <td align="left">Address:</td>
            <td align="left">
              <textarea> House Number, Building, Street Name </textarea>
            </td>
          </tr>

          <tr>
            <td align="left">State:</td>
            <td align="left">Poscode:</td>
          </tr>

          <tr>
            <td align="left">
              <select name="state">
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
            </td>

            <td align="left">
              <input
                type="text"
                name="Poscode"
                placeholder="4300"
                maxlength="5"
                required
              />
            </td>
          </tr>

          <tr></tr>
          <tr>
            <td align="left">Payemnt method:</td>
            <td align="center">
              <select name="state">
                <option value="Online Banking">Online Banking</option>
                <option value="Credit/Debit Card">Credit/Debit Card</option>
              </select>
            </td>
          </tr>

          <tr>
            <td align="left">Name on Card:</td>
            <td align="left">Credit/Debit Card Number:</td>
          </tr>

          <tr>
            <td align="center">
              <input type="text" name="Name on Card" required />
            </td>
            <td align="left">
              <input maxlength="16" name="Credit/Debit Card Number" required />
            </td>
          </tr>

          <tr>
            <td align="left">Expiry Date:</td>
            <td align="left">CVC:</td>
          </tr>

          <tr>
            <td align="left">
              <input
                name="Expiry Date"
                placeholder="10/25"
                type="text"
                maxlength="5"
                required
              />
            </td>
            <td align="left">
              <input name="CVC" placeholder="000" maxlength="3" />
            </td>
          </tr>

          <tr>
            <td align="center" colspan="2">
              <button type="submit">Confirm Payment</button>
              <a href="/cart">
                <button type="button">Cancel</button>
              </a>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </body>
</html>
