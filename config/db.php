<?php
class SqlConfig{
    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $conn;

    function __construct(){
        $this->servername = "167.71.207.105";
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
        $sql = "SHOW TABLES LIKE 'User'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE User (
                user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(30) NOT NULL,
                pass VARCHAR(255) NOT NULL,
                sex VARCHAR(10) NOT NULL,
                state VARCHAR(30) NOT NULL,
                email VARCHAR(50),
                aboutYou TEXT,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
        
            if ($this->conn->query($sql) === TRUE) {
                // echo "Table User created successfully";
            } else {
                return "Error creating table: " . $this->conn->error;
            }
        } else {
            // echo "Table User already exists, skipping creation.";
        }

        $sql = "SHOW TABLES LIKE 'Products'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE Products (
                product_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                image VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
        
            if ($this->conn->query($sql) === TRUE) {
                // echo "Table User created successfully";
            } else {
                return "Error creating table: " . $this->conn->error;
            }
        } else {
            // echo "Table User already exists, skipping creation.";
        }

        $sql = "SHOW TABLES LIKE 'Cart'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE Cart (
                cart_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(6) UNSIGNED NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES User(user_id)
            )";
        
            if ($this->conn->query($sql) === TRUE) {
                // echo "Table User created successfully";
            } else {
                return "Error creating table: " . $this->conn->error;
            }
        } else {
            // echo "Table User already exists, skipping creation.";
        }

        $sql = "SHOW TABLES LIKE 'CartItem'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE CartItem (
                cart_item_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                cart_id INT(6) UNSIGNED NOT NULL,
                product_id INT(6) UNSIGNED NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (cart_id) REFERENCES Cart(cart_id),
                FOREIGN KEY (product_id) REFERENCES Products(product_id)
            )";
        
            if ($this->conn->query($sql) === TRUE) {
                // echo "Table User created successfully";
            } else {
                return "Error creating table: " . $this->conn->error;
            }
        } else {
            // echo "Table User already exists, skipping creation.";
        }

        $sql = "SHOW TABLES LIKE 'Orders'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE Orders (
                order_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(6) UNSIGNED NOT NULL,
                total_price DECIMAL(10, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES User(user_id)
            )";
        
            if ($this->conn->query($sql) === TRUE) {
                // echo "Table User created successfully";
            } else {
                return "Error creating table: " . $this->conn->error;
            }
        } else {
            // echo "Table User already exists, skipping creation.";
        }
    }

    function InsertNewUser($user, $pass, $sex, $state, $email, $aboutYou) {
        // Check if the username already exists in the database
        $sql = "SELECT * FROM User WHERE user = ?";
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
                $sql = "INSERT INTO User (user, pass, sex, state, email, aboutYou)
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

        $sql = "SELECT user, pass FROM User WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $_POST["username"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($password == $row["pass"]) {
                $_SESSION["username"] = $username;
                $_SESSION["logged_in"] = true;

                $user_id = $this->getUserIdByUsername($username);
                $cart_id = $this->createCartIfNotExists($user_id);

                $_SESSION["user_id"] = $user_id;
                $_SESSION["cart_id"] = $cart_id;

                header("Location: /");
                exit();
            } else {
                return $error_message = "Invalid username or password.";
            }
        } else {
            return $error_message = "Invalid username or password.";
        }

        $stmt->close();
    }

    function GetProfile($user_id){
        $sql = "SELECT * FROM User WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    function UpdateProfile($user_id, $username, $email, $sex, $state, $aboutYou){
        $sql = "UPDATE User SET user=?, sex=?, state=?, email=?, aboutYou=? WHERE user_id=?";
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param("sssssi", $username, $sex, $state, $email, $aboutYou, $user_id);
    
        $stmt->execute();
    
        $stmt->close();
    
        return "Successfully edited profile";
    }
    

    function getUserIdByUsername($username) {
        $sql = "SELECT user_id FROM User WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row["user_id"];
    }
    
    function createCartIfNotExists($user_id) {
        $sql = "SELECT cart_id FROM Cart WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            // If cart doesn't exist, create it
            $sql = "INSERT INTO Cart (user_id) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            // Retrieve the newly inserted cart_id
            $cart_id = $stmt->insert_id;
            
            // Close the statement
            $stmt->close();
            
            // Return the cart_id
            return $cart_id;
        } else {
            // If cart already exists, fetch and return its cart_id
            $row = $result->fetch_assoc();
            $cart_id = $row["cart_id"];
            $stmt->close();
            return $cart_id;
        }
    }
    

    function AddToCart($cartId, $productId) {
        // First, check if the product already exists in the cart
        $sql = "SELECT cart_item_id, quantity FROM CartItem WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $cartId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // If the product already exists in the cart, update the quantity
            $row = $result->fetch_assoc();
            $cartItemId = $row['cart_item_id'];
            $quantity = $row['quantity'] + 1;
    
            $sql = "UPDATE CartItem SET quantity = ? WHERE cart_item_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $quantity, $cartItemId);
            $stmt->execute();
            $stmt->close();
            return "Quantity updated in cart";
        } else {
            // If the product is not in the cart, insert a new entry
            $sql = "INSERT INTO CartItem (cart_id, product_id, quantity, price) VALUES (?, ?, 1, (SELECT price FROM Products WHERE product_id = ?))";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $cartId, $productId, $productId);
            $stmt->execute();
            $stmt->close();
            return "Successfully added to cart";
        }
    }

    function __destruct(){
        $this->conn->close();
    }
}
?>
