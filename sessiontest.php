<?php
session_start();
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true){
    echo $_SESSION["username"];
}else{
    echo "There's no session available";
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    session_unset();
    session_destroy();
    header("Location: /pharmagains");
    exit;
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
    <button type="submit">Logout</button>
</form>