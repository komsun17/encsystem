<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once('../connect.php');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    $userId = $_SESSION['user_id'];

    // Debug connection
    try {
        $conn->query("SELECT 1");
    } catch (PDOException $e) {
        error_log("Connection test failed: " . $e->getMessage());
        throw new Exception('Database connection test failed');
    }

    // Check if tables exist
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('time_entries', $tables) || 
        !in_array('projects', $tables) || 
        !in_array('drawings', $tables)) {
        throw new Exception('Required tables do not exist');
    }

    $stmt = $conn->prepare("
        SELECT 
            t.id,
            t.start_time,
            t.end_time,
            t.duration_minutes,
            t.activity_type,
            t.note,
            t.status,
            p.name as project_name,
            d.drawing_no
        FROM time_entries t
        LEFT JOIN projects p ON t.project_id = p.id
        LEFT JOIN drawings d ON t.drawing_id = d.id
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