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
// Include the SqlConfig class file
require_once '../config/db.php';

// Create an instance of the SqlConfig class
$sqlConfig = new SqlConfig();

// Call the methods of the SqlConfig class
$sqlConfig->DatabaseChecker();
$sqlConfig->TableChecker();
$sqlConfig->InsertNewUser($user, $pass, $sex, $state, $email, $aboutYou);
$sqlConfig->CloseCon();
?>