<?php 
// api that returns vehicles or parts based on some bs
require_once __DIR__ . "/../config.php";

header("Content-Type: application/json");

if (!$conn || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}


$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$vType = isset($_GET["vehicle_type"]) ? (int) $_GET["vehicle_type"] : 0;

try {
    if ($search === "" && $vType === 0) {
        $stmt = $conn->prepare("SELECT parts.name, parts.sku, parts.condition, parts.notes, vehicle_types.name as vehicle_type_name FROM parts JOIN vehicle_types ON parts.vehicle_type_id = vehicle_types.id");
        $stmt->execute();
        $result = $stmt->get_result();
        
    } else if ($vType > 0 && $search === "") {
            $stmt = $conn->prepare("SELECT parts.name, parts.sku, parts.condition, parts.notes, vehicle_types.name as vehicle_type_name FROM parts JOIN vehicle_types ON parts.vehicle_type_id = vehicle_types.id WHERE parts.vehicle_type_id = ?");
            $stmt->bind_param("i", $vType);
            $stmt->execute();
            $result = $stmt->get_result();
    } else if( $vType === 0 && $search !== "") {
        $stmt = $conn->prepare(
            "SELECT parts.name, parts.sku, parts.condition, parts.notes, vehicle_types.name as vehicle_type_name
             FROM parts 
             JOIN vehicle_types ON parts.vehicle_type_id = vehicle_types.id
             WHERE parts.name LIKE ? OR parts.sku LIKE ?"

        );

        $like = "%" . $search . "%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $stmt = $conn->prepare(
            "SELECT parts.name, parts.sku, parts.condition, parts.notes, vehicle_types.name as vehicle_type_name
             FROM parts 
             JOIN vehicle_types ON parts.vehicle_type_id = vehicle_types.id
             WHERE (parts.name LIKE ? OR parts.sku LIKE ?) AND parts.vehicle_type_id = ?"

        );

        $like = "%" . $search . "%";
        $stmt->bind_param("ssi", $like, $like, $vType);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    $rows = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(["results" => $rows]);
    exit;

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode(["error" => "Query failed"]);
    exit;
}

?>