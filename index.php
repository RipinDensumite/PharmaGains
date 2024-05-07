<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmagains";
$prodNum = 0;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM Products";
$result = $conn->query($sql);

// Store products in an array
$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prodNum++;
        $products[] = array(
            'image' => $row['image'],
            'name' => $row['name'],
            'price' => $row['price'],
            'product_id' => $row['product_id']
        );
    }
}

// Function to filter products based on search query
function filterProducts($products, $searchQuery) {
    $filteredProducts = array();
    foreach ($products as $product) {
        // Check if the product name contains the search query
        if (stripos($product['name'], $searchQuery) !== false) {
            $filteredProducts[] = $product;
        }
    }
    return $filteredProducts;
}

// Check if search form is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // Filter products based on the search query
    $products = filterProducts($products, $searchQuery);
    $prodNum = count($products);
}

// Check if search form is submitted
if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $cart_id = $_SESSION['cart_id'];
    require_once 'config/db.php';

    $sqlConfig = new SqlConfig();

    // Call the methods of the SqlConfig class
    $sqlConfig->DatabaseChecker();
    $sqlConfig->TableChecker();
    $msg = $sqlConfig->AddToCart($cart_id, $product_id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Home</title>
    <style>
        button {
            cursor: pointer;
        }
    </style>
</head>

<body bgcolor="black">
<nav style="display: flex; flex-wrap: no-wrap; justify-content: space-between; align-items: center;">
    <h1><a href="/pharmagains" style="text-decoration: none; color: white;">PharmaGains</a></h1>
    <div>
        <form style="display: inline;">
            <input type="text" name="search"/>
            <button type="submit">Search</button>
        </form>
        <?php 
        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true){
            echo "<span style='color: white;'> Welcome back : ". $_SESSION['username'] . "</span>";
        }
        ?>
        <?php
        if (!(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true)){
            echo "<a href='login'>";
            echo    "<button>Login</button>";
            echo "</a>";
        }
        ?>
        <?php
        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true){
            echo "<a href='endSession.php'>";
            echo    "<button>logout</button>";
            echo "</a>";
            echo "<a href='profile'>";
            echo    "<button>Profile</button>";
            echo "</a>";
        }
        ?>
        <a href="register">
            <button>Register</button>
        </a>
        <a href="cart">
            <button>Cart</button>
        </a>
    </div>
</nav>
<br/>
<marquee attribute_name="attribute_value" ....more attributes>
    <font color="white">Welcome to PharmaGains Market</font>
</marquee>
<section>
    <h1 align="center">
        <font color="white"><b> Welcome to</b></font>
    </h1>
    <h2 align="center">
        <font color="white">Pharma Gains Market</font>
    </h2>
    <p align="center">
        <font color="white">
        <?php echo "total products : " . $prodNum; ?>
        </font>
    </p>
    <p align="center">
        <font color="white">
        <?php 
        if(isset($msg)){
            echo $msg;
        }
         ?>
        </font>
    </p>
    <!-- Display filtered products -->
    <table align="center" border="4" bgcolor="#734FB7">
        <?php foreach ($products as $index => $product): ?>
            <?php if ($index % 3 === 0): ?>
                <tr>
            <?php endif; ?>
            <td>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <img src="<?php echo $product['image']; ?>" width="200" height="200"/>
                    <span style="color: white; font-weight: bold;"><?php echo $product['name']; ?></span>
                    <span style="color: white; font-weight: bold;"><?php echo "RM " . $product['price']; ?></span>
                    <form method="get" style="display:flex;flex-direction:column;width:100%;">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit">Add to cart</button>
                    </form>    
                </div>
            </td>
            <?php if (($index + 1) % 3 === 0 || $index === count($products) - 1): ?>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</section>
</body>

</html>
