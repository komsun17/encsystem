<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $stmt = $conn->prepare("SELECT id, name FROM users ORDER BY name ASC");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}