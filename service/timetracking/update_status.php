<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once('../connect.php');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Please login');
    }
    if (empty($_POST['timelog_id']) || empty($_POST['status'])) {
        throw new Exception('Missing data');
    }

    $status = $_POST['status'];
    $note = $_POST['note'] ?? '';
    $timelog_id = $_POST['timelog_id'];
    $user_id = $_SESSION['user_id'];

    // Debug: log POST
    // file_put_contents(__DIR__ . '/debug.log', print_r($_POST, true), FILE_APPEND);

    if ($status === 'paused' || $status === 'completed') {
        $stmt = $conn->prepare("
            UPDATE time_entries 
            SET status = :status, 
                end_time = NOW(), 
                duration = TIMESTAMPDIFF(SECOND, start_time, NOW()), 
                note = :note 
            WHERE id = :id AND user_id = :user_id
        ");
    } else {
        $stmt = $conn->prepare("
            UPDATE time_entries 
            SET status = :status, 
                note = :note 
            WHERE id = :id AND user_id = :user_id
        ");
    }

    $stmt->execute([
        ':status' => $status,
        ':note' => $note,
        ':id' => $timelog_id,
        ':user_id' => $user_id
    ]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('No record updated. Check timelog_id and user_id.');
    }

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}