<?php
session_start();
require '../connect.php';
header('Content-Type: application/json');

$timelog_id = $_GET['id'] ?? 0; // ต้องตรงกับ URL

$stmt = $conn->prepare("SELECT *, 
        TIMESTAMPDIFF(SECOND, start_time, IFNULL(end_time, NOW())) AS total_seconds
        FROM time_logs WHERE id = ?");
$stmt->execute([$timelog_id]);
$log = $stmt->fetch(PDO::FETCH_ASSOC);

if ($log) {
    echo json_encode(['status' => 'success', 'response' => $log]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบรายการ']);
}
