<?php
$username = $_POST["username"];
$password = $_POST["password"];

$validUsers = [
    "ahmad" => "1234",
    "thayyib" => "4321"
];

if (array_key_exists($username, $validUsers) && $validUsers[$username] === $password) {
    header("Location: /pharmagains");
    exit();
} else {
    header("Location: .");
    exit();
}
?>