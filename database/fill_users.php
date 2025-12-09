<?php

function seed_users(mysqli $conn): void
{
    $users = [
        ['username' => 'john_doe', 'email' => 'john@example.com', 'password' => 'password123'],
        ['username' => 'jane_smith', 'email' => 'jane@example.com', 'password' => 'securepass'],
        ['username' => 'mike_ross', 'email' => 'mike@example.com', 'password' => 'harvey123'],
        ['username' => 'rachel_zane', 'email' => 'rachel@example.com', 'password' => 'legalEagle'],
    ];

    $sql = "INSERT INTO users (username, email, password_hash)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                email = VALUES(email),
                password_hash = VALUES(password_hash)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare users statement: " . $conn->error);
    }

    foreach ($users as $user) {
        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('sss', $user['username'], $user['email'], $hash);

        if ($stmt->execute()) {
            echo "<p>Inserted/updated user {$user['username']} ({$user['email']}).</p>";
        } else {
            echo "<p>Failed inserting {$user['username']}: {$stmt->error}</p>";
        }
    }

    $stmt->close();
}

if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    require __DIR__ . '/../config.php';
    seed_users($conn);
    $conn->close();
}

?>
