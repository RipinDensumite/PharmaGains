<?php
session_start();

echo "username : " . $_SESSION['username'] . "<br>";
echo "user Id : " . $_SESSION['user_id'] . "<br>";
echo "log in : " . $_SESSION['logged_in'] . "<br>";
echo "cart-id : " . $_SESSION['cart_id'] . "<br>";
?>