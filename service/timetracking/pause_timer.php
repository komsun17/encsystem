<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');
date_default_timezone_set('Asia/Bangkok');

try {
    $userId = $_SESSION['user_id'];
    $timeEntryId = $_POST['time_entry_id'] ?? null;

    if (!$timeEntryId) {
        throw new Exception('กรุณาระบุ time entry ID');
    }

    // ตรวจสอบว่ามี timer ที่ระบุหรือไม่ และเป็นของ user นี้หรือไม่
    $stmt = $conn->prepare("
        SELECT id, status 
        FROM time_entries 
        WHERE id = :time_entry_id 
        AND user_id = :user_id 
        AND status = 'active'
    ");
    
    $stmt->execute([
        ':time_entry_id' => $timeEntryId,
        ':user_id' => $userId
    ]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('ไม่พบรายการที่กำลังทำงานอยู่');
    }

    // อัพเดทสถานะเป็น paused
    $stmt = $conn->prepare("
        UPDATE time_entries 
        SET status = 'paused',
            updated_at = NOW()
        WHERE id = :time_entry_id
    ");

    $stmt->execute([':time_entry_id' => $timeEntryId]);

    echo json_encode([
        'status' => 'success',
        'message' => 'หยุดการจับเวลาชั่วคราวเรียบร้อย'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}