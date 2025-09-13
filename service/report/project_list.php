<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

$result = [];
$status = "error";
$message = "";

try {
    $sql = "SELECT code, client_name FROM projects ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = [
            'code' => $row['code'],
            'client_name' => $row['client_name']
        ];
    }
    $status = "success";
} catch (Exception $e) {
    $message = $e->getMessage();
}

echo json_encode([
    'status' => $status,
    'data' => $result,
    'message' => $message
]);