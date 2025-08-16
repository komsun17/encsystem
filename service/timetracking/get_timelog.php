<?php
session_start();
require '../connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No user session"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT tl.id, u.username, p.project_name, d.drawing_name, tl.start_time, tl.end_time, tl.total_pause_minutes, tl.status, tl.note
        FROM time_logs tl
        LEFT JOIN users u ON tl.user_id = u.id
        LEFT JOIN projects p ON tl.project_id = p.id
        LEFT JOIN drawings d ON tl.drawing_id = d.id
        WHERE tl.user_id = ?
        ORDER BY tl.start_time DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$timelogs = $stmt->fetchAll();

echo json_encode([
    "status" => "success",
    "response" => $timelogs
]);
