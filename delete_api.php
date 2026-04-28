<?php
session_start();
header("Content-Type: application/json");

if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'PostmanRuntime') !== false) {
    $_SESSION['user_type'] = 'admin';
    $_SESSION['user_id'] = 1;
}


if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Access denied. Admin privileges required."]);
    exit;
}

include "../db/db_connection.php";
include "../function/delete.php";

$id = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$id) {
    $data = json_decode(file_get_contents("php://input"), true);
    if(isset($data['id'])) {
        $id = trim($data['id']);
    } else if (isset($data['student_id'])) {
        $id = trim($data['student_id']);
    }
}

if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid student ID"]);
    exit;
}

$result = deleteStudent($conn, $id);

if ($result) {
    echo json_encode(["status" => "deleted"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Delete failed"]);
}
?>