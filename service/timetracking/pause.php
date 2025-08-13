<?php
session_start();
require '../connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "No user session"]);
    exit;
}

$timelog_id = $_POST['timelog_id'] ?? null;
if (!$timelog_id) {
    echo json_encode(["status" => "error", "message" => "ข้อมูล timelog_id ไม่ถูกต้อง"]);
    exit;
}

// อัพเดตสถานะ time_logs เป็น paused
$sql = "UPDATE time_logs SET status='paused' WHERE id = ?";
$pdo->prepare($sql)->execute([$timelog_id]);

// เพิ่ม record pause_periods เริ่ม pause_start
$sql = "INSERT INTO pause_periods (timelog_id, pause_start) VALUES (?, NOW())";
$pdo->prepare($sql)->execute([$timelog_id]);

echo json_encode(["status" => "success", "message" => "เริ่มหยุดพักแล้ว"]);
