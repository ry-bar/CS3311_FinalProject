<?php
require __DIR__ . "/../config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true) ?? [];

$email    = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

$errors = [];

if ($email === "" || $password === "") {
    $errors[] = "Email and password are required.";
}

if ($errors) {
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}

// Look up user
$stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "errors" => ["Database error."]]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $username, $password_hash);

if ($stmt->fetch()) {
    if (password_verify($password, $password_hash)) {
        // Login ok
        $_SESSION["user_id"]  = $id;
        $_SESSION["username"] = $username;

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "errors" => ["Invalid email or password."]]);
    }
} else {
    echo json_encode(["success" => false, "errors" => ["Invalid email or password."]]);
}

$stmt->close();
