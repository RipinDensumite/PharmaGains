<?php
// Include the SqlConfig class file
require_once '../config/db.php';
session_start();

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true){
    header("Location: /");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = $_POST["username"];
  $email = $_POST["email"];
  $pass = $_POST["password"];
  $conPass = $_POST["conPass"];
  // $pass = password_hash($_POST["password"], PASSWORD_DEFAULT); Hash password
  $sex = $_POST["sex"];
  $state = $_POST["state"];
  $aboutYou = $_POST["aboutYou"];

  if($pass != $conPass){
    $regOutput = "Passwords do not match. Please try again.";
  }else{
    // Create an instance of the SqlConfig class
    $sqlConfig = new SqlConfig();

    // Call the methods of the SqlConfig class
    $sqlConfig->DatabaseChecker();
    $sqlConfig->TableChecker();
    $regOutput = $sqlConfig->InsertNewUser($user, $pass, $sex, $state, $email, $aboutYou);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
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
  </head>
  <body>
    <h1 align="center">Register</h1>
    <div align="center">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table>
          <tr>
            <td><label for="username">Username</label></td>
            <td><input type="textfield" name="username" required /></td>
          </tr>
          <tr>
            <td><label for="email">Email</label></td>
            <td><input type="email" name="email" required /></td>
          </tr>
          <tr>
            <td><label for="password">Password</label></td>
            <td><input type="password" name="password" required /></td>
          </tr>
          <tr>
            <td><label for="conPass">Confirmed Password</label></td>
            <td><input type="password" name="conPass" required /></td>
          </tr>
          <tr>
          <tr>
            <td><label for="gender">Gender</label></td>
            <td>
              <input type="radio" name="sex" value="male" required />
              <?xml version="1.0" encoding="UTF-8"?><svg
                width="15px"
                height="15px"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                color="#000000"
              >
                <path
                  d="M14.2323 9.74707C13.1474 8.66733 11.6516 8 10 8C6.68629 8 4 10.6863 4 14C4 17.3137 6.68629 20 10 20C13.3137 20 16 17.3137 16 14C16 12.3379 15.3242 10.8337 14.2323 9.74707ZM14.2323 9.74707L20 4M20 4H16M20 4V8"
                  stroke="#000000"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </svg>
              Male
              <br />
            </td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="radio" name="sex" value="female" required />
              <?xml version="1.0" encoding="UTF-8"?><svg
                width="15px"
                height="15px"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                color="#000000"
              >
                <path
                  d="M12 15C15.3137 15 18 12.3137 18 9C18 5.68629 15.3137 3 12 3C8.68629 3 6 5.68629 6 9C6 12.3137 8.68629 15 12 15ZM12 15V19M12 21V19M12 19H10M12 19H14"
                  stroke="#000000"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </svg>
              Female
            </td>
          </tr>
          <tr>
            <td><label>State</label></td>
            <td>
              <select id="state" name="state" required>
                <option value="selangor">Selangor</option>
                <option value="Johor">Johor</option>
                <option value="Kedah">Kedah</option>
                <option value="Kelantan">Kelantan</option>
                <option value="Melaka">Melaka</option>
                <option value="Negeri Sembilan">Negeri Sembilan</option>
                <option value="Pahang">Pahang</option>
                <option value="Penang">Penang</option>
                <option value="Perak">Perak</option>
                <option value="Perlis">Perlis</option>
                <option value="Sarawak">Sarawak</option>
                <option value="Terengganu">Terengganu</option>
                <option value="Kuala Lumpur">Kuala Lumpur</option>
                <option value="Labuan">Labuan</option>
                <option value="Putrajaya">Putrajaya</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label>About you</label>
            </td>
            <td>
              <textarea name="aboutYou"></textarea>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input type="checkbox" name="ageVerify" required />
              <label for="ageVerify"
                >Please make sure that you are 18 years old</label
              >
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <button
                style="width: 100%; padding: 10px; margin: 3px"
                type="submit"
              >
                Register
              </button>
            </td>
          </tr>
          <tr>
            <td align="left">
              <a href="../login">Login</a>
            </td>
            <td align="right">
              <a href="..">Back homepage</a>
            </td>
          </tr>
        </table>
      </form>
      <?php 
        if(isset($regOutput)){
          echo $regOutput;
        }
      ?>
    </div>
  </body>
</html>
