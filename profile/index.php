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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2980b9;
        }
        
        .links {
            text-align: right;
            margin-top: 15px;
        }
        .links a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Edit Profile</h1>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>" required />

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required />

            <label for="gender">Gender</label>
            <input type="radio" name="sex" value="male" <?php if($sex === 'male') echo 'checked'; ?> required /> Male
            <input type="radio" name="sex" value="female" <?php if($sex === 'female') echo 'checked'; ?> required /> Female

            <label for="state">State</label>
            <select id="state" name="state" required>
                <?php
                $states = ['Selangor', 'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sarawak', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'];
                foreach ($states as $state_option) {
                    echo "<option value=\"$state_option\"" . ($state === $state_option ? ' selected' : '') . ">$state_option</option>";
                }
                ?>
            </select>

            <label for="aboutYou">About you</label>
            <textarea name="aboutYou"><?php echo $aboutYou; ?></textarea>

            <button type="submit">Update Profile</button>

            <div class="links">
                <a href="..">Back to Homepage</a>
            </div>
        </form>
        <?php 
        if(isset($msg)){
            echo "<div class='error-message'>$msg</div>";
        }
        ?>
    </div>
</body>
</html>
