<?php
// If navigate through link without form request from html site, redirect to register page
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /PharmaGains/register');
    exit;
}
?>

<?php
$user = $_POST["username"];
$email = $_POST["email"];
$pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
$sex = $_POST["sex"];
$state = $_POST["state"];
$aboutYou = $_POST["aboutYou"];

echo "$user<br />";
echo "$email<br />";
echo "$pass<br />";
echo "$sex<br />";
echo "$state<br />";
echo "$aboutYou<br />";
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmagains";

// *Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// *Check connection
if ($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

// *Create database
// Check if the database exists
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'pharmagains'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Database doesn't exist, create it
    $sql = "CREATE DATABASE pharmagains";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: " . $conn->error;
    }
} else {
    echo "Database pharmagains already exists, skipping creation.";
    echo "<br>";
}

// *sql to create table
// Check if the table exists
$sql = "SHOW TABLES LIKE 'Users'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Table doesn't exist, create it
    $sql = "CREATE TABLE Users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user VARCHAR(30) NOT NULL,
        pass VARCHAR(255) NOT NULL,
        sex VARCHAR(10) NOT NULL,
        state VARCHAR(30) NOT NULL,
        email VARCHAR(50),
        aboutYou TEXT,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Table users created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
} else {
    echo "Table users already exists, skipping creation.";
    echo "<br>";
}

// *Insert value
// Check if the username already exists in the database
$sql = "SELECT * FROM Users WHERE user = ?";
$stmt = $conn->prepare($sql);
$stmt -> bind_param("s", $user);
$stmt -> execute();
$result = $stmt -> get_result();

// Check if the username already exists
if ($result -> num_rows > 0){
    echo "Error: The username already exists, skipping insertion.";
}else{
    // Check if any of the values are null
    if (empty($user) || empty($pass) || empty($sex) || empty($state) || empty($email)){
        echo "Error: One or more required fields are empty, skipping insertion.";
    } else {
        $sql = "INSERT INTO Users (user, pass, sex, state, email, aboutYou)
            VALUES ('$user', '$pass', '$sex', '$state', '$email', '$aboutYou')";

        if ($conn -> query($sql) === TRUE){
            echo "New record created successfully";
        }else {
            echo "Error: " . $sql . "<br />" . $conn -> error;
        }
    }
}

// Close connection
$stmt -> close();
$conn -> close();
?>