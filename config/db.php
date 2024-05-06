<?php
class SqlConfig{
    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $conn;

    function __construct(){
        $this->servername = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "pharmagains";
    }

    function DatabaseChecker(){
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password);

        // Check connection
        if ($this->conn -> connect_error){
            die("Connection failed: " . $this->conn -> connect_error);
        }
    
        // Create database
        // Check if the database exists
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'pharmagains'";
        $result = $this->conn->query($sql);
    
        if ($result->num_rows == 0) {
            // Database doesn't exist, create it
            $sql = "CREATE DATABASE pharmagains";
            if ($this->conn->query($sql) === TRUE) {
                // return "Database created successfully";
            } else {
                return "Error creating database: " . $this->conn->error;
            }
        } else {
            // return "Database pharmagains already exists, skipping creation.";
        }

        // Select the db
        $this->conn->select_db($this->dbname);
    }
    
    function TableChecker(){
        // SQL to create table
        // Check if the table exists
        $sql = "SHOW TABLES LIKE 'Users'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE Users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(30) NOT NULL,
                pass VARCHAR(255) NOT NULL,
                sex VARCHAR(10) NOT NULL,
                state VARCHAR(30) NOT NULL,
                email VARCHAR(50),
                aboutYou TEXT,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
        
            if ($this->conn->query($sql) === TRUE) {
                return "Table users created successfully";
            } else {
                return "Error creating table: " . $this->conn->error;
            }
        } else {
            return "Table users already exists, skipping creation.";
        }
    }

    function InsertNewUser($user, $pass, $sex, $state, $email, $aboutYou) {
        // Check if the username already exists in the database
        $sql = "SELECT * FROM Users WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Check if the username already exists
        if ($result->num_rows > 0) {
            return "Error: The username already exists, skipping insertion.";
        } else {
            // Check if any of the values are null
            if (empty($user) || empty($pass) || empty($sex) || empty($state) || empty($email)) {
                return "Error: One or more required fields are empty, skipping insertion.";
            } else {
                // Prepare and bind the SQL statement
                $sql = "INSERT INTO Users (user, pass, sex, state, email, aboutYou)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ssssss", $user, $pass, $sex, $state, $email, $aboutYou);
    
                // Execute the statement
                if ($stmt->execute()) {
                    return "New record created successfully";
                } else {
                    return "Error: " . $sql . "<br />" . $this->conn->error;
                }
            }
        }

        $stmt -> close();
    }

    function LoginValidation($username, $password){
        // Get the username and password from the form
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT user, pass FROM Users WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $_POST["username"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // if (password_verify($password, $row["pass"])) { Hash verify
            if ($password == $row["pass"]) {
                $_SESSION["username"] = $username;
                $_SESSION["logged_in"] = true;
                header("Location: /pharmagains");
                exit();
            } else {
                return $error_message = "Invalid username or password.";
            }
        } else {
            return $error_message = "Invalid username or password.";
        }

        $stmt->close();
    }

    function SearchProducts($userInput){
        
    }

    function __destruct(){
        $this->conn->close();
    }
}
?>
