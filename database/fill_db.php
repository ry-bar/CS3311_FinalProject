<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$pass = "";
$db = "scrap_database";
$conn = null;

try {
    $conn = new mysqli($host, $user, $pass);

    if ($conn->connect_error) {
        throw new RuntimeException("Failed to connect to DB server: " . $conn->connect_error);
    }

    $sqlDb = "CREATE DATABASE IF NOT EXISTS `$db`
               DEFAULT CHARACTER SET utf8mb4
               COLLATE utf8mb4_unicode_ci";

    if (!$conn->query($sqlDb)) {
        throw new RuntimeException("Could not create database: " . $conn->error);
    }

    echo "<p>Database {$db} created/verified.</p>";

    if (!$conn->select_db($db)) {
        throw new RuntimeException("Could not select database {$db}: " . $conn->error);
    }

    $tables = [
        "CREATE TABLE IF NOT EXISTS users(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(120) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS vehicle_types (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(80) NOT NULL,
            slug VARCHAR(80) NOT NULL UNIQUE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS parts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            vehicle_type_id INT NOT NULL,
            name VARCHAR(150) NOT NULL,
            sku VARCHAR(60) UNIQUE,
            `condition` VARCHAR(40) DEFAULT 'used',
            notes TEXT,
            stock INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id) ON DELETE CASCADE
        )"
    ];

    foreach ($tables as $sqlTable) {
        if (!$conn->query($sqlTable)) {
            throw new RuntimeException("Could not create table: " . $conn->error);
        }
    }

    echo "<p>Tables created/verified.</p>";

    require_once __DIR__ . '/fill_vehicle_types.php';
    require_once __DIR__ . '/fill_parts.php';
    require_once __DIR__ . '/fill_users.php';

    seed_vehicle_types($conn);
    seed_parts($conn);
    seed_users($conn);

    echo "<p>Database seeding complete.</p>";
} catch (Throwable $e) {
    http_response_code(500);
    echo "<p>Setup failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    error_log("fill_db.php error: " . $e->getMessage());
} finally {
    if ($conn instanceof mysqli) {
        $conn->close();
    }
}

?>
