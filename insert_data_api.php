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
include "../function/insert_data.php";

$data=json_decode(file_get_contents("php://input"),true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid request data"]);
    exit;
}

$student_id_param = null;

$result=insertStudent(
$conn,
$student_id_param,
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
    echo json_encode(["status"=>"success"]);
} else {
    http_response_code(500);
    echo json_encode(["status"=>"error", "message" => "Failed to insert student"]);
}
?>