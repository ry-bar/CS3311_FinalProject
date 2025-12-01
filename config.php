<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "scrap_database";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Failed to connect to DB:".$conn->connect_error);
}

?>