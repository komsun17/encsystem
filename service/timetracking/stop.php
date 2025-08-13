<?php
session_start();
require '../connect.php';

header('Content-Type: application/json');

$timelog_id = $_POST['timelog_id'] ?? null;
if (!$timelog_id) {
    echo json_encode(["status" => "error", "message" => "ข้อมูล timelog_id ไม่ถูกต้อง"]);
    exit;
}

// อัพเดต end_time = NOW() และสถานะเป็น finished
$sql = "UPDATE time_logs SET end_time = NOW(), status='finished' WHERE id = ?";
$pdo->prepare($sql)->execute([$timelog_id]);

echo json_encode(["status" => "success", "message" => "บันทึกเวลาสำเร็จ"]);
