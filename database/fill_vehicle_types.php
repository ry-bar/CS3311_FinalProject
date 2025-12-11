<?php

function seed_vehicle_types(mysqli $conn): void
{
    $vehicleTypes = [
        ['name' => 'Cars', 'slug' => 'car', 'description' => 'Standard passenger vehicles'],
        ['name' => 'Trucks', 'slug' => 'truck', 'description' => 'Light and heavy duty pickup trucks'],
        ['name' => 'Motorcycles', 'slug' => 'motorcycle', 'description' => 'Street and off-road bikes'],
        ['name' => 'SUVs', 'slug' => 'suv', 'description' => 'Sport utility vehicles']
    ];

    $sql = "INSERT INTO vehicle_types (name, slug, description)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                description = VALUES(description)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Failed to prepare vehicle_types statement: " . $conn->error);
    }

    foreach ($vehicleTypes as $type) {
        $stmt->bind_param('sss', $type['name'], $type['slug'], $type['description']);

        if ($stmt->execute()) {
            echo "<p>Inserted/updated vehicle type: {$type['name']} ({$type['slug']})</p>";
        } else {
            echo "<p>Failed for {$type['slug']}: {$stmt->error}</p>";
        }
    }

    $stmt->close();
}

if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    require __DIR__ . '/../config.php';
    seed_vehicle_types($conn);
    $conn->close();
}

?>
