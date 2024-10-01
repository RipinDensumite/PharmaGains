<?php

class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'rootpassword';
    private $dbname = 'pharmagains';
    public $conn;

    public function __construct() {
        // Create connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function TableChecker() {
        // SQL to check and create tables
        $tables = [
            'User' => "CREATE TABLE User (
                user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(30) NOT NULL,
                pass VARCHAR(255) NOT NULL,
                sex VARCHAR(10) NOT NULL,
                state VARCHAR(30) NOT NULL,
                email VARCHAR(50),
                aboutYou TEXT,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'Products' => "CREATE TABLE Products (
                product_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                image VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            'Cart' => "CREATE TABLE Cart (
                cart_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(6) UNSIGNED NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES User(user_id)
            )",
            'CartItem' => "CREATE TABLE CartItem (
                cart_item_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                cart_id INT(6) UNSIGNED NOT NULL,
                product_id INT(6) UNSIGNED NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (cart_id) REFERENCES Cart(cart_id),
                FOREIGN KEY (product_id) REFERENCES Products(product_id)
            )",
            'Orders' => "CREATE TABLE Orders (
                order_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(6) UNSIGNED NOT NULL,
                total_price DECIMAL(10, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES User(user_id)
            )"
        ];

        foreach ($tables as $tableName => $sql) {
            $this->createTable($tableName, $sql);
        }
    }

    private function createTable($tableName, $sql) {
        $checkTableQuery = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableQuery);

        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            if ($this->conn->query($sql) === TRUE) {
                echo "Table $tableName created successfully.<br>";
            } else {
                echo "Error creating table $tableName: " . $this->conn->error . "<br>";
            }
        } else {
            echo "Table $tableName already exists, skipping creation.<br>";
        }
    }
}

// Create a new instance of the Database class and check for tables
$database = new Database();
$database->TableChecker();

?>
