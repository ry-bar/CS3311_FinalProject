<?php

$host = "localhost";
$user = "root";
$pass = "";

$db = "auth_users";

$conn = new mysqli($host, $user, $pass);

if($conn->connect_error){
    die("Failed to connect to DB:".$conn->connect_error);
}

$sql_db = "CREATE DATABASE IF NOT EXISTS $db
            DEFAULT CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci";

if(!$conn->query($sql_db)){
    die("Could not create DB:".$conn->error);
}

echo "<p>Database created/checked</p>";

$conn->select_db($db);

$sql_table = "CREATE TABLE IF NOT EXISTS users(
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(120) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";

if(!$conn->query($sql_table)){
    die("Could not create Table:".$conn->error);
}

echo "<p>Table created/checked</p>";

echo "<p>Database setup complete.</p>";

$conn->close();

?>