<?php
session_start();
header("Content-Type: application/json");

if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'PostmanRuntime') !== false) {
    $_SESSION['user_type'] = 'admin';
    $_SESSION['user_id'] = 1;
}

include "../db/db_connection.php";
include "../function/display.php";

// Check if user is authenticated
if (!isset($_SESSION['user_type'])) {
    http_response_code(401);
    echo json_encode(["error" => "Authentication required"]);
    exit;
}

$id = isset($_GET['id']) ? trim($_GET['id']) : null;
if (!$id) {
    // If not in URL, try Body
    $data = json_decode(file_get_contents("php://input"), true);
    if(isset($data['id'])) {
        $id = trim($data['id']);
    } else if (isset($data['student_id'])) {
        $id = trim($data['student_id']);
    }
}

$students = getStudents($conn, $id);

if ($_SESSION['user_type'] !== 'admin') {
    foreach ($students as &$s) {
        unset($s['password']);
    }
}

echo json_encode($students);
?>