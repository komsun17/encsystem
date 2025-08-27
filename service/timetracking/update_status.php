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

    // ตรวจสอบสถานะเดิม
    $stmt = $conn->prepare("SELECT status FROM time_entries WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $timelog_id, ':user_id' => $user_id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($current && $current['status'] === 'completed' && $status !== 'completed') {
        throw new Exception('Cannot change status after Finished.');
    }

    if ($status === 'paused') {
        // Pause: set pause_start = NOW()
        $stmt = $conn->prepare("
            UPDATE time_entries 
            SET status = :status, pause_start = NOW(), note = :note 
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([
            ':status' => $status,
            ':note' => $note,
            ':id' => $timelog_id,
            ':user_id' => $user_id
        ]);
    } elseif ($status === 'active') {
        // Resume: pause_duration = pause_duration + (NOW() - pause_start), pause_start = NULL
        $stmt = $conn->prepare("
            UPDATE time_entries 
            SET status = :status, 
                pause_duration = pause_duration + TIMESTAMPDIFF(SECOND, pause_start, NOW()), 
                pause_start = NULL, 
                note = :note 
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([
            ':status' => $status,
            ':note' => $note,
            ':id' => $timelog_id,
            ':user_id' => $user_id
        ]);
    } elseif ($status === 'completed') {
        // Finish: set end_time = NOW(), duration = (end_time - start_time) - pause_duration
        $stmt = $conn->prepare("
            UPDATE time_entries 
            SET status = :status, 
                end_time = NOW(), 
                duration = TIMESTAMPDIFF(SECOND, start_time, NOW()) - pause_duration, 
                pause_start = NULL, 
                note = :note 
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([
            ':status' => $status,
            ':note' => $note,
            ':id' => $timelog_id,
            ':user_id' => $user_id
        ]);
    } else {
        // Just update status and note
        $stmt = $conn->prepare("
            UPDATE time_entries 
            SET status = :status, note = :note 
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([
            ':status' => $status,
            ':note' => $note,
            ':id' => $timelog_id,
            ':user_id' => $user_id
        ]);
    }

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
