<?php
require_once '../config/db.php';
// Start the session
session_start();

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true){
    header("Location: /");
    exit;
}

$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Create an instance of the SqlConfig class
  $sqlConfig = new SqlConfig();

  // Call the methods of the SqlConfig class
  $sqlConfig->DatabaseChecker();
  $sqlConfig->TableChecker();
  $error_message = $sqlConfig->LoginValidation($username, $password);
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
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
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            flex: 1;
            font-weight: bold;
            margin-right: 10px;
            color: #555;
        }
        .form-group input {
            flex: 2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }
        button {
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: red;
            margin: 10px 0;
            text-align: center;
        }
        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .links a {
            text-decoration: none;
            color: #3498db;
            transition: color 0.3s;
        }
        .links a:hover {
            color: #2980b9;
        }
    </style>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/gh/iconoir-icons/iconoir@main/css/iconoir.css"
    />
</head>
<body>
    <div>
        <h1 align="center">Login</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required />
            </div>
            <button type="submit">Login</button>
            <?php if (isset($error_message) && !empty($error_message)) { ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>
            <div class="links">
                <a href="../register">Register</a>
                <a href="..">Back to homepage</a>
            </div>
        </form>
    </div>
</body>
</html>
