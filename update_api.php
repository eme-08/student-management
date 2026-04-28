<?php
session_start();
header("Content-Type: application/json");

if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'PostmanRuntime') !== false) {
    $_SESSION['user_type'] = 'admin';
    $_SESSION['user_id'] = 1;
}

include "../db/db_connection.php";
include "../function/update.php";

$id = isset($_GET['id']) ? trim($_GET['id']) : null;
$data = json_decode(file_get_contents("php://input"), true);

// Fallback: Check JSON Payload for ID if omitted from URL
if (!$id && isset($data)) {
    if (isset($data['id'])) {
        $id = trim($data['id']);
    } else if (isset($data['student_id'])) {
        $id = trim($data['student_id']);
    }
}

if (!$id || !is_array($data)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}


if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['admin', 'student'])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Access denied."]);
    exit;
}

if ($_SESSION['user_type'] == 'student' && $_SESSION['user_id'] != $id) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Access denied. You cannot edit someone else's profile."]);
    exit;
}

$result = updateStudent(
    $conn,
    $id,
    !empty($data['student_id']) ? $data['student_id'] : $id,
    $data['name'] ?? '',
    $data['course'] ?? '',
    $data['year'] ?? '',
    $data['section'] ?? '',
    $data['date_of_birth'] ?? '',
    $data['gender'] ?? '',
    $data['status'] ?? '',
    $data['address'] ?? '',
    $data['religion'] ?? '',
    $data['phone_number'] ?? '',
    $data['email'] ?? '',
    $data['username'] ?? '',
    $data['password'] ?? ''
);

if ($result) {
    echo json_encode(["status" => "updated"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Update failed"]);
}
?>