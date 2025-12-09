<?php
require __DIR__ . "/../config.php";

$_SESSION = [];

session_unset();
session_destroy();

// Send them back to the page they came from, or home
$redirect = $_SERVER["HTTP_REFERER"] ?? "index.php";
header("Location: " . $redirect);
exit;
