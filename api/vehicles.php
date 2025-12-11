<?php
require_once __DIR__ . "/../config.php";

header("Content-Type: application/json");

if (!$conn || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}


$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";

if (strlen($search) > 120) {
    $search = substr($search, 0, 120);
}

try {
    if ($search === "") {
        $stmt = $conn->prepare("SELECT id, slug, description FROM vehicle_types");
        $stmt->execute();
        $result = $stmt->get_result();

    } else {

        $stmt = $conn->prepare(
            "SELECT id, slug, description
             FROM vehicle_types 
             WHERE slug LIKE ?"
        );

        $like = "%" . $search . "%";
        $stmt->bind_param("s", $like);
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