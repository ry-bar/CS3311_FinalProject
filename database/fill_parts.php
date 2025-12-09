<?php

function seed_parts(mysqli $conn): void
{
    $parts = [
        ['vehicle_slug' => 'trucks', 'name' => 'Tailgate', 'sku' => 'TRK-TG-001', 'condition' => 'used', 'notes' => 'Some dents, latch intact', 'stock' => 1],
        ['vehicle_slug' => 'trucks', 'name' => 'Driver Door', 'sku' => 'TRK-DD-001', 'condition' => 'used', 'notes' => 'Includes window and mirror', 'stock' => 1],
        ['vehicle_slug' => 'trucks', 'name' => 'Passenger Door', 'sku' => 'TRK-PD-001', 'condition' => 'used', 'notes' => 'Some rust near bottom', 'stock' => 1],
        ['vehicle_slug' => 'car', 'name' => 'Trunk Lid', 'sku' => 'CAR-TL-001', 'condition' => 'used', 'notes' => 'Paint faded', 'stock' => 1],
        ['vehicle_slug' => 'car', 'name' => 'Hood', 'sku' => 'CAR-HD-001', 'condition' => 'used', 'notes' => 'Minor scratches', 'stock' => 1],
        ['vehicle_slug' => 'car', 'name' => 'Driver Door', 'sku' => 'CAR-DD-001', 'condition' => 'used', 'notes' => 'Window motor untested', 'stock' => 1],
        ['vehicle_slug' => 'motor_cycle', 'name' => 'Handlebar', 'sku' => 'MC-HB-001', 'condition' => 'used', 'notes' => 'Grip wear', 'stock' => 1],
        ['vehicle_slug' => 'motor_cycle', 'name' => 'Front Fender', 'sku' => 'MC-FF-001', 'condition' => 'used', 'notes' => 'Some scuffs', 'stock' => 1],
        ['vehicle_slug' => 'motor_cycle', 'name' => 'Seat', 'sku' => 'MC-ST-001', 'condition' => 'used', 'notes' => 'Torn cover', 'stock' => 1],
    ];

    $vehicleLookupSql = "SELECT id FROM vehicle_types WHERE slug = ?";
    $vehicleLookupStmt = $conn->prepare($vehicleLookupSql);

    if (!$vehicleLookupStmt) {
        die("Failed to prepare vehicle lookup: " . $conn->error);
    }

    $insertSql = "INSERT INTO parts (vehicle_type_id, name, sku, `condition`, notes, stock)
                  VALUES (?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE
                      vehicle_type_id = VALUES(vehicle_type_id),
                      name = VALUES(name),
                      `condition` = VALUES(`condition`),
                      notes = VALUES(notes),
                      stock = VALUES(stock)";

    $insertStmt = $conn->prepare($insertSql);

    if (!$insertStmt) {
        die("Failed to prepare parts insert: " . $conn->error);
    }

    foreach ($parts as $part) {
        $slug = $part['vehicle_slug'];
        $vehicleLookupStmt->bind_param('s', $slug);
        $vehicleLookupStmt->execute();
        $vehicleLookupStmt->bind_result($vehicleTypeId);
        $vehicleLookupStmt->store_result();

        if (!$vehicleLookupStmt->fetch()) {
            echo "<p>Skipped {$part['name']} ({$part['sku']}): vehicle type '{$slug}' not found.</p>";
            $vehicleLookupStmt->free_result();
            continue;
        }

        $vehicleLookupStmt->free_result();

        $insertStmt->bind_param(
            'issssi',
            $vehicleTypeId,
            $part['name'],
            $part['sku'],
            $part['condition'],
            $part['notes'],
            $part['stock']
        );

        if ($insertStmt->execute()) {
            echo "<p>Inserted/updated part {$part['name']} ({$part['sku']}).</p>";
        } else {
            echo "<p>Failed inserting {$part['sku']}: {$insertStmt->error}</p>";
        }
    }

    $vehicleLookupStmt->close();
    $insertStmt->close();
}

if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    require __DIR__ . '/../config.php';
    seed_parts($conn);
    $conn->close();
}

?>
