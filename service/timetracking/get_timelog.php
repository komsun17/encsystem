<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once('../connect.php');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Please login');
    }

    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT 
            t.id,
            t.start_time,
            t.end_time,
            t.activity_type,
            t.note,
            t.status,
            t.drawing_no,
            t.duration, -- duration in seconds
            p.name as project_name
        FROM time_entries t
        LEFT JOIN projects p ON t.project_id = p.id
        WHERE t.user_id = :user_id
        ORDER BY t.start_time DESC
    ");

    $stmt->execute([':user_id' => $userId]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
        'recordsTotal' => count($data),
        'recordsFiltered' => count($data),
        'data' => $data,
        'status' => 'success'
    ]);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'draw' => 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Application Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'draw' => 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}