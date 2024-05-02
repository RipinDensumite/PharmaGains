<?php
session_start();

// Define your products array
$products = [
    [
        'image' => 'public\image\products\FitFirst protein.jpg',
        'name' => 'FIT PROTEIN',
        'price' => 130.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\Fit Gainer.jpg',
        'name' => 'FIT GAINER',
        'price' => 155.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\Fit Creatine.jpg',
        'name' => 'FIT CREATINE',
        'price' => 90.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\USN chocolate.jpg',
        'name' => 'USN CHOCOLATE',
        'price' => 240.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\USN Vanilla.jpg',
        'name' => 'USN VANILLA',
        'price' => 240.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\USN strawberry.jpg',
        'name' => 'USN STRAWBERRY',
        'price' => 240.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\USN mass chocolate.jpg',
        'name' => 'USN MASS CHOCOLATE',
        'price' => 240.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\USN mass vanilla.jpg',
        'name' => 'USN MASS VANILLA',
        'price' => 240.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\USN mass strawberry.jpg',
        'name' => 'USN MASS STRAWBERRY',
        'price' => 240.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\MMX Creatine.jpg',
        'name' => 'MMX CREATINE',
        'price' => 90.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\MMX mass.jpg',
        'name' => 'MMX GAINER',
        'price' => 240.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\MMX.jpg',
        'name' => 'MMX PROTEIN',
        'price' => 220.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\USN pure creatine.jpg',
        'name' => 'USN PURE CREATINE',
        'price' => 80.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\BCAA.jpg',
        'name' => 'BCAA',
        'price' => 90.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
    [
        'image' => 'public\image\products\Steroid PharmaGain.jpg',
        'name' => 'MEDICINE',
        'price' => 200.00,
        'buttons' => '<b><button>Buy Now</button></b> <b><button>Add to cart</button></b>'
    ],
];

// Function to filter products based on search query
function filterProducts($products, $searchQuery) {
    $filteredProducts = [];
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
    <h1 style="color: white;">PharmaGains</h1>
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
            echo "<a href='sessiontest.php'>";
            echo    "<button>logout</button>";
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
                    <div style="display:flex;flex-direction:column;width:100%;">
                        <button>Buy Now</button>
                        <button>Add to cart</button>
                    </div>
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
