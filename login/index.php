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
      table {
        border: 1px solid black;
        padding: 10px;
        margin: 10px;
      }
      input {
        padding: 5px;
      }
      button {
        padding: 5px;
        background-color: green;
        color: white;
        border: none;
        cursor: pointer;
      }
      button:hover {
        background-color: darkgreen;
      }
      a {
        text-decoration: none;
        color: #4a5568;
      }
    </style>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/gh/iconoir-icons/iconoir@main/css/iconoir.css"
    />
  </head>
  <body>
    <h1 align="center">Login</h1>
    <div align="center">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
          <tr>
            <td>
              <svg
                width="24px"
                height="24px"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                color="#000000"
              >
                <path
                  d="M5 20V19C5 15.134 8.13401 12 12 12V12C15.866 12 19 15.134 19 19V20"
                  stroke="#000000"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
                <path
                  d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z"
                  stroke="#000000"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </svg>
              <label for="username">Username</label>
            </td>
            <td>
              <input type="textfield" name="username" required />
            </td>
          </tr>
          <tr>
            <td>
              <svg
                width="24px"
                height="24px"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                color="#000000"
              >
                <path
                  d="M16 12H17.4C17.7314 12 18 12.2686 18 12.6V19.4C18 19.7314 17.7314 20 17.4 20H6.6C6.26863 20 6 19.7314 6 19.4V12.6C6 12.2686 6.26863 12 6.6 12H8M16 12V8C16 6.66667 15.2 4 12 4C8.8 4 8 6.66667 8 8V12M16 12H8"
                  stroke="#000000"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </svg>
              <label for="password">Password</label>
            </td>
            <td><input type="password" name="password" required /></td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <button
                style="width: 100%; padding: 10px; margin: 3px"
                type="submit"
              >
                Login
              </button>
            </td>
          </tr>
          <?php if (isset($error_message) && !empty($error_message)) { ?>
          <tr>
            <td colspan="2" align="center" style="color: red;">
              <?php echo $error_message; ?>
            </td>
          </tr>
          <?php } ?>
          <tr>
            <td align="left">
              <a href="../register">register</a>
            </td>
            <td align="right">
              <a href="..">Back homepage</a>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </body>
</html>
