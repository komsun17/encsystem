<?php
session_start();
require '../connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "No user session"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$project_id = $_POST['project_id'] ?? null;
$drawing_id = $_POST['drawing_id'] ?? null;

if (!$project_id || !$drawing_id) {
    echo json_encode(["status" => "error", "message" => "โปรดเลือก Project และ Drawing"]);
    exit;
}

$sql = "INSERT INTO time_logs (user_id, project_id, drawing_id, start_time, status) VALUES (?, ?, ?, NOW(), 'started')";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $project_id, $drawing_id]);

$timelog_id = $pdo->lastInsertId();

echo json_encode(["status" => "success", "message" => "เริ่มบันทึกเวลาแล้ว", "timelog_id" => $timelog_id]);
