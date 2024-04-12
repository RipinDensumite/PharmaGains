<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmagains";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user, pass FROM Users WHERE user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_POST["username"]);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($_POST["password"], $row["pass"])) {
        header("Location: /pharmagains");
        exit();
    } else {
        header("Location: .");
        exit();
    }
} else {
    header("Location: .");
    exit();
}

$stmt->close();
$conn->close();
?>