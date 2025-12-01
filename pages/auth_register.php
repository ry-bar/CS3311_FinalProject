<?php
require __DIR__ . "/../config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true) ?? [];

$username  = trim($data["username"] ?? "");
$email     = trim($data["email"] ?? "");
$password  = $data["password"]  ?? "";
$password2 = $data["password2"] ?? "";

$errors = [];

// Basic validation
if ($username === "" || $email === "" || $password === "" || $password2 === "") {
    $errors[] = "All fields are required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if ($password !== $password2) {
    $errors[] = "Passwords do not match.";
}

if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
}

if ($errors) {
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "errors" => ["Database error."]]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "errors" => ["That email is already registered."]]);
    $stmt->close();
    exit;
}
$stmt->close();

// Insert new user
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?,?,?)");
if (!$stmt) {
    echo json_encode(["success" => false, "errors" => ["Database error."]]);
    exit;
}

$stmt->bind_param("sss", $username, $email, $hash);

if ($stmt->execute()) {
    // Auto-login after register
    $_SESSION["user_id"]   = $stmt->insert_id;
    $_SESSION["username"]  = $username;

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "errors" => ["Could not create account."]]);
}

$stmt->close();
