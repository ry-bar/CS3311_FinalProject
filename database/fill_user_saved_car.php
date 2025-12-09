<?php

$users_saved_car = [[
    'vin_number' => '1HGCM82633A123456',
    'user_id' => 1,
    'vehicle_type_id' => 1
], [
    'vin_number' => '1FTFW1EF1EFA12345',
    'user_id' => 2,
    'vehicle_type_id' => 2
], [
    'vin_number' => 'JS1GN7EA5J2101234',
    'user_id' => 3,
    'vehicle_type_id' => 3
], [
    'vin_number' => '5UXWX9C59H0D12345',
    'user_id' => 4,
    'vehicle_type_id' => 4
]];

function seed_user_saved_car(mysqli $conn): void
{
    global $users_saved_car;

    $sql = "INSERT INTO user_saved_car (vin_number, user_id, vehicle_type_id)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                user_id = VALUES(user_id),
                vehicle_type_id = VALUES(vehicle_type_id)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare user_saved_car statement: " . $conn->error);
    }

    foreach ($users_saved_car as $entry) {
        $stmt->bind_param('sii', $entry['vin_number'], $entry['user_id'], $entry['vehicle_type_id']);

        if ($stmt->execute()) {
            echo "<p>Inserted/updated saved car VIN {$entry['vin_number']} for user ID {$entry['user_id']}.</p>";
        } else {
            echo "<p>Failed inserting VIN {$entry['vin_number']}: {$stmt->error}</p>";
        }
    }

    $stmt->close();
}

if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    require __DIR__ . '/../config.php';
    seed_user_saved_car($conn);
    $conn->close();
}

?>