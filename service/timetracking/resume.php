<?php
session_start();
require '../connect.php';

header('Content-Type: application/json');

$timelog_id = $_POST['timelog_id'] ?? null;
if (!$timelog_id) {
    echo json_encode(["status" => "error", "message" => "ข้อมูล timelog_id ไม่ถูกต้อง"]);
    exit;
}

// อัพเดต pause_periods ที่ยังไม่มี pause_end ให้มี pause_end = NOW()
$sql = "UPDATE pause_periods SET pause_end = NOW() WHERE timelog_id = ? AND pause_end IS NULL";
$pdo->prepare($sql)->execute([$timelog_id]);

// คำนวณเวลาหยุดพักรวม (นาที)
$sql = "SELECT TIMESTAMPDIFF(MINUTE, pause_start, pause_end) AS pause_time FROM pause_periods WHERE timelog_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$timelog_id]);
$pauses = $stmt->fetchAll();

$total_pause = 0;
foreach ($pauses as $pause) {
    $total_pause += $pause['pause_time'] ?? 0;
}

// อัพเดตสถานะ time_logs เป็น started และอัพเดต total_pause_minutes
$sql = "UPDATE time_logs SET status='started', total_pause_minutes = ? WHERE id = ?";
$pdo->prepare($sql)->execute([$total_pause, $timelog_id]);

echo json_encode(["status" => "success", "message" => "กลับมาทำงานต่อแล้ว"]);
