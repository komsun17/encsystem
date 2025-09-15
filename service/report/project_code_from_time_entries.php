<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    // ดึง project_code ที่มีใน time_entries เท่านั้น
    $stmt = $conn->prepare("
        SELECT DISTINCT p.code
        FROM time_entries t
        LEFT JOIN projects p ON t.project_id = p.id
        WHERE t.project_id IS NOT NULL AND p.code IS NOT NULL AND p.code != ''
        ORDER BY p.code ASC
    ");
    $stmt->execute();
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = ['code' => $row['code']];
    }
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}