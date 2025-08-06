<?php
require_once "db_connect.php";
$database = new Database();
$pdo = $database->getConnection();

try {
    //create user table 
    $sql_user = "CREATE TABLE users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(50) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    role ENUM('admin', 'user') NOT NULL
                );";

    $pdo->exec($sql_user);
    echo "Table 'users' created successfully.<br>";

    //create product table 
    $sql_product = "CREATE TABLE products (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(100) NOT NULL,
                        price DECIMAL(10, 2) NOT NULL,
                        quantity_available INT NOT NULL
                    );";

    $pdo->exec($sql_product);
    echo "Table 'products' created successfully.<br>";

    //create transactions table 
    $sql_transactions = "CREATE TABLE transactions (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            user_id INT,
                            product_id INT,
                            quantity INT NOT NULL,
                            total_price DECIMAL(10, 2) NOT NULL,
                            transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (user_id) REFERENCES users(id),
                            FOREIGN KEY (product_id) REFERENCES products(id)
                        );";

    $pdo->exec($sql_transactions);
    echo "Table 'transactionss' created successfully.<br>";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
