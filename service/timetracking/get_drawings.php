<?php
session_start();
require '../connect.php';
date_default_timezone_set('Asia/Bangkok');

if (!isset($_GET['project_id'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Missing project_id"
    ]);
    exit;
}

$project_id = $_GET['project_id'];

$sql = "SELECT id, drawing_name FROM drawings WHERE project_id = ? ORDER BY drawing_name";
$stmt = $conn->prepare($sql);
$stmt->execute([$project_id]);
$drawings = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "response" => $drawings
]);
