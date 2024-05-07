<?php
// Include the SqlConfig class file
require_once '../config/db.php';

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pharmagains/login");
    exit();
}

// Create an instance of the SqlConfig class
$sqlConfig = new SqlConfig();

// Call the GetProfile method to retrieve the user's current profile information
$user_id = $_SESSION['user_id'];
$sqlConfig->DatabaseChecker();
$sqlConfig->TableChecker();
$profile = $sqlConfig->GetProfile($user_id);

// Initialize variables to hold updated profile information
$username = $profile['user'];
$email = $profile['email'];
$sex = $profile['sex'];
$state = $profile['state'];
$aboutYou = $profile['aboutYou'];

$regOutput = ''; // Variable to hold registration output message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $sex = $_POST["sex"];
    $state = $_POST["state"];
    $aboutYou = $_POST["aboutYou"];

    $sqlConfig = new SqlConfig();
    $sqlConfig->DatabaseChecker();
    $sqlConfig->TableChecker();
    $msg = $sqlConfig->UpdateProfile($user_id, $username, $email, $sex, $state, $aboutYou);
    // Update the profile information in the database
    // You need to implement this part
    // $sqlConfig->UpdateProfile($user_id, $username, $email, $sex, $state, $aboutYou);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
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
    <h1 align="center">Edit Profile</h1>
    <div align="center">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <table>
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input type="textfield" name="username" value="<?php echo $username; ?>" required /></td>
                </tr>
                <tr>
                    <td><label for="email">Email</label></td>
                    <td><input type="email" name="email" value="<?php echo $email; ?>" required /></td>
                </tr>
                <!-- Password fields (if you want to allow password update) -->
                <!--
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input type="password" name="password" required /></td>
                </tr>
                <tr>
                    <td><label for="conPass">Confirmed Password</label></td>
                    <td><input type="password" name="conPass" required /></td>
                </tr>
                -->
                <tr>
                    <td><label for="gender">Gender</label></td>
                    <td>
                        <input type="radio" name="sex" value="male" <?php if($sex === 'male') echo 'checked'; ?> required /> Male
                        <br />
                        <input type="radio" name="sex" value="female" <?php if($sex === 'female') echo 'checked'; ?> required /> Female
                    </td>
                </tr>
                <tr>
                    <td><label>State</label></td>
                    <td>
                        <select id="state" name="state" required>
                            <!-- You can dynamically select the state based on the user's profile -->
                            <?php
                            $states = ['Selangor', 'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sarawak', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'];
                            foreach ($states as $state_option) {
                                if ($state === $state_option) {
                                    echo "<option value=\"$state_option\" selected>$state_option</option>";
                                } else {
                                    echo "<option value=\"$state_option\">$state_option</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label>About you</label></td>
                    <td>
                        <textarea name="aboutYou"><?php echo $aboutYou; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button style="width: 100%; padding: 10px; margin: 3px" type="submit">Update Profile</button>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                    </td>
                    <td align="right">
                        <a href="/PharmaGains/">Back to Homepage</a>
                    </td>
                </tr>
            </table>
        </form>
        <?php 
        if(isset($msg)){
            echo $msg;
        }
        ?>
    </div>
</body>
</html>
