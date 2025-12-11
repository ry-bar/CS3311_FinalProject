<?php
require_once __DIR__ . "/../config.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "You must be logged in to save vehicles."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$rawInput = file_get_contents("php://input");
$payload = json_decode($rawInput, true);

$vin = isset($payload["vin"]) ? trim($payload["vin"]) : "";
$vehicleTypeId = isset($payload["vehicle_type_id"]) ? (int) $payload["vehicle_type_id"] : 0;
$userId = (int) $_SESSION["user_id"];

if ($vin === "" || strlen($vin) !== 17) {
    http_response_code(422);
    echo json_encode(["error" => "VIN is required and must be 17 characters."]);
    exit;
}

if ($vehicleTypeId <= 0) {
    http_response_code(422);
    echo json_encode(["error" => "A valid vehicle type is required."]);
    exit;
}

$typeStmt = $conn->prepare("SELECT id FROM vehicle_types WHERE id = ?");
if (!$typeStmt) {
    http_response_code(500);
    echo json_encode(["error" => "Unable to prepare vehicle type lookup."]);
    exit;
}

$typeStmt->bind_param("i", $vehicleTypeId);
$typeStmt->execute();
$typeStmt->store_result();

if ($typeStmt->num_rows === 0) {
    $typeStmt->close();
    http_response_code(404);
    echo json_encode(["error" => "Vehicle type not found."]);
    exit;
}
$typeStmt->close();

$saveStmt = $conn->prepare(
    "INSERT INTO user_saved_car (vin_number, user_id, vehicle_type_id)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE vehicle_type_id = VALUES(vehicle_type_id)"
);

if (!$saveStmt) {
    http_response_code(500);
    echo json_encode(["error" => "Unable to prepare save statement."]);
    exit;
}

$saveStmt->bind_param("sii", $vin, $userId, $vehicleTypeId);

if ($saveStmt->execute()) {
    echo json_encode([
        "success" => true,
        "vin" => $vin,
        "vehicle_type_id" => $vehicleTypeId,
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Saving the vehicle failed."]);
}

$saveStmt->close();
