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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        form {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select,
        textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            color: #007bff;
            text-decoration: none;
            margin-top: 10px;
            display: block;
            text-align: center;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-top: 10px;
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
</head>
<body>
    <h1>Register</h1>
    <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" required />

            <label for="email">Email</label>
            <input type="email" name="email" required />

            <label for="password">Password</label>
            <input type="password" name="password" required />

            <label for="conPass">Confirmed Password</label>
            <input type="password" name="conPass" required />

            <label for="gender">Gender</label>
            <div style="display: flex; flex-direction: row; justify-content: space-evenly;">
              <label><input type="radio" name="sex" value="male" required /> Male</label>
              <label><input type="radio" name="sex" value="female" required /> Female</label>
            </div>

            <label for="state">State</label>
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

            <label>About you</label>
            <textarea name="aboutYou" rows="3"></textarea>

            <label>
                <input type="checkbox" name="ageVerify" required />
                Please make sure that you are 18 years old
            </label>

            <button type="submit">Register</button>

            <div class="links">
              <a href="../login">Login</a>
              <a href="..">Back to homepage</a>
            </div>
        </form>

        <?php if (isset($regOutput)) { ?>
            <div class="error-message"><?php echo $regOutput; ?></div>
        <?php } ?>
    </div>
</body>
</html>
