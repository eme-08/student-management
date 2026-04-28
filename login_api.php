<?php
session_start();
header("Content-Type: application/json");

include "../db/db_connection.php";
include "../function/login.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data) || empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Username and password are required"
    ]);
    exit;
}

$username = trim($data['username']);
$password = trim($data['password']);

$user = loginUser($conn, $username, $password);

if ($user) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['user_id'] = $user['user_id'];

    echo json_encode([
        "status" => "success",
        "user_type" => $user['user_type'],
        "user_id" => $user['user_id']
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid username or password"
    ]);
}
?>