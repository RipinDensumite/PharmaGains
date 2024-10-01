<?php
$servername = "167.71.207.105";
$username = "root";
$password = "pharma12345";
$dbname = "pharmagains";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Array of products
$products = [
    ['image' => 'public\image\products\FitFirst protein.jpg', 'name' => 'FIT PROTEIN', 'price' => 130.00],
    ['image' => 'public\image\products\Fit Gainer.jpg', 'name' => 'FIT GAINER', 'price' => 155.00],
    ['image' => 'public\image\products\Fit Creatine.jpg', 'name' => 'FIT CREATINE', 'price' => 90.00],
    ['image' => 'public\image\products\USN chocolate.jpg', 'name' => 'USN CHOCOLATE', 'price' => 240.00],
    ['image' => 'public\image\products\USN Vanilla.jpg', 'name' => 'USN VANILLA', 'price' => 240.00],
    ['image' => 'public\image\products\USN strawberry.jpg', 'name' => 'USN STRAWBERRY', 'price' => 240.00],
    ['image' => 'public\image\products\USN mass chocolate.jpg', 'name' => 'USN MASS CHOCOLATE', 'price' => 240.00],
    ['image' => 'public\image\products\USN mass vanilla.jpg', 'name' => 'USN MASS VANILLA', 'price' => 240.00],
    ['image' => 'public\image\products\USN mass strawberry.jpg', 'name' => 'USN MASS STRAWBERRY', 'price' => 240.00],
    ['image' => 'public\image\products\MMX Creatine.jpg', 'name' => 'MMX CREATINE', 'price' => 90.00],
    ['image' => 'public\image\products\MMX mass.jpg', 'name' => 'MMX GAINER', 'price' => 240.00],
    ['image' => 'public\image\products\MMX.jpg', 'name' => 'MMX PROTEIN', 'price' => 220.00],
    ['image' => 'public\image\products\Creatin.jpg', 'name' => 'USN PURE CREATINE', 'price' => 80.00],
    ['image' => 'public\image\products\BCAA.jpg', 'name' => 'BCAA', 'price' => 90.00],
];

// Insert products into the table
foreach ($products as $product) {
    $sql = "INSERT INTO Products (image, name, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $product['image'], $product['name'], $product['price']);

    if ($stmt->execute()) {
        echo "Product inserted successfully: " . $product['name'] . "<br>";
    } else {
        echo "Error inserting product: " . $conn->error . "<br>";
    }

    $stmt->close();
}

$conn->close();
?>
