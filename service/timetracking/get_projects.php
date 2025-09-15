<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once('../connect.php');
date_default_timezone_set('Asia/Bangkok');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    $stmt = $conn->prepare("
        SELECT id, code, client_name
        FROM projects
        WHERE code IS NOT NULL AND code != ''
        ORDER BY code ASC
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