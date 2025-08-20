<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once('../connect.php');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    $stmt = $conn->prepare("
        SELECT id, name 
        FROM projects 
        WHERE status = 'active' 
        ORDER BY name ASC
    ");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $projects
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}