<?php
require_once __DIR__ . "/../config.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "You must be logged in to view saved cars."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$userId = (int) $_SESSION["user_id"];

$sql = "
    SELECT 
        usc.vin_number AS vin,
        usc.saved_at,
        vt.id AS vehicle_type_id,
        vt.slug AS vehicle_slug,
        vt.name AS vehicle_name
    FROM user_saved_car usc
    INNER JOIN vehicle_types vt ON vt.id = usc.vehicle_type_id
    WHERE usc.user_id = ?
    ORDER BY usc.saved_at DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to load saved cars."]);
    exit;
}

$stmt->bind_param("i", $userId);

if (!$stmt->execute()) {
    $stmt->close();
    http_response_code(500);
    echo json_encode(["error" => "Failed to load saved cars."]);
    exit;
}

$result = $stmt->get_result();
$cars = [];

while ($row = $result->fetch_assoc()) {
    $cars[] = [
        "vin" => $row["vin"],
        "saved_at" => $row["saved_at"],
        "vehicle_type_id" => (int) $row["vehicle_type_id"],
        "vehicle_type" => $row["vehicle_slug"] ?: $row["vehicle_name"],
        "vehicle_type_name" => $row["vehicle_name"],
    ];
}

$stmt->close();

echo json_encode([
    "success" => true,
    "cars" => $cars,
]);
