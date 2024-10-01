<?php
session_start();

$servername = "167.71.207.105:3306";
$username = "root";
$password = "pharma12345";
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
        if (stripos($product['name'], $searchQuery) !== false) {
            $filteredProducts[] = $product;
        }
    }
    return $filteredProducts;
}

// Check if search form is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $products = filterProducts($products, $searchQuery);
    $prodNum = count($products);
}

// Check if add to cart form is submitted
if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $cart_id = $_SESSION['cart_id'];
    require_once 'config/db.php';

    $sqlConfig = new SqlConfig();

    $sqlConfig->DatabaseChecker();
    $sqlConfig->TableChecker();
    $msg = $sqlConfig->AddToCart($cart_id, $product_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaGains Market</title>
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
        
        /* Navigation */
        nav {
            background-color: #3498db;
            color: white;
            padding: 1rem;
        }
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .nav-logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .nav-links a, .nav-links button {
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-links a:hover, .nav-links button:hover {
            background-color: #2980b9;
        }
        .search-form {
            display: flex;
        }
        .search-form input {
            padding: 0.5rem;
            border: none;
            border-radius: 4px 0 0 4px;
        }
        .search-form button {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
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
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-info {
            padding: 1rem;
        }
        .product-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .product-price {
            color: #2980b9;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .add-to-cart {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .add-to-cart:hover {
            background-color: #2980b9;
        }
        
        /* Footer */
        footer {
            background-color: #34495e;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
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
            .search-form {
                width: 100%;
            }
            .search-form input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="/" class="nav-logo">PharmaGains</a>
            <div class="nav-links">
                <form class="search-form" method="get">
                    <input type="text" name="search" placeholder="Search products...">
                    <button type="submit">Search</button>
                </form>
                <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true): ?>
                    <span>Welcome, <?php echo $_SESSION['username']; ?></span>
                    <a href="profile">Profile</a>
                    <a href="endSession.php">Logout</a>
                <?php else: ?>
                    <a href="login">Login</a>
                    <a href="register">Register</a>
                <?php endif; ?>
                <a href="/cart">Cart</a>
            </div>
        </div>
    </nav>

    <header>
        <h1>Welcome to PharmaGains Market</h1>
        <p>Your trusted source for quality pharmaceuticals</p>
        <p>Total products: <?php echo $prodNum; ?></p>
    </header>

    <main>
        <?php if(isset($msg)): ?>
            <div style="background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem;">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                    <div class="product-info">
                        <h2 class="product-name"><?php echo $product['name']; ?></h2>
                        <p class="product-price">RM <?php echo $product['price']; ?></p>
                        <form method="get">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <button type="submit" class="add-to-cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 PharmaGains Market. All rights reserved.</p>
    </footer>
</body>
</html>