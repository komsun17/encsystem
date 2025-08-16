<?php
session_start();
require '../connect.php';
date_default_timezone_set('Asia/Bangkok'); // บนทุกไฟล์ที่ใช้เวลา
$start_time = date('Y-m-d H:i:s');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "No user session"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$project_id = $_POST['project_id'] ?? null;
$drawing_id = $_POST['drawing_id'] ?? null;
$note = $_POST['note'] ?? '';
$status = 'running';

if (!$project_id || !$drawing_id) {
    echo json_encode(["status" => "error", "message" => "โปรดเลือก Project และ Drawing"]);
    exit;
}

$sql = "INSERT INTO time_logs (user_id, project_id, drawing_id, start_time, note, status) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id, $project_id, $drawing_id, $start_time, $note]);

$timelog_id = $conn->lastInsertId();

echo json_encode(["status" => "success", "message" => "เริ่มบันทึกเวลาแล้ว", "timelog_id" => $timelog_id]);
