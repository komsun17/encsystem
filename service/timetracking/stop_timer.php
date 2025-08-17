<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $userId = $_SESSION['user_id'];
    $timeEntryId = $_POST['time_entry_id'] ?? null;
    $notes = $_POST['notes'] ?? '';

    if (!$timeEntryId) {
        throw new Exception('กรุณาระบุ time entry ID');
    }

    // ตรวจสอบว่ามี timer ที่ระบุหรือไม่
    $stmt = $conn->prepare("
        SELECT id, start_time, status 
        FROM time_entries 
        WHERE id = :time_entry_id 
        AND user_id = :user_id 
        AND status IN ('active', 'paused')
    ");
    
    $stmt->execute([
        ':time_entry_id' => $timeEntryId,
        ':user_id' => $userId
    ]);

    $timeEntry = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$timeEntry) {
        throw new Exception('ไม่พบรายการที่กำลังทำงานอยู่');
    }

    // คำนวณระยะเวลาที่ใช้ (ในหน่วยนาที)
    $startTime = new DateTime($timeEntry['start_time']);
    $endTime = new DateTime();
    $duration = $endTime->getTimestamp() - $startTime->getTimestamp();
    $durationMinutes = round($duration / 60);

    // อัพเดทข้อมูล time entry
    $stmt = $conn->prepare("
        UPDATE time_entries 
        SET status = 'completed',
            end_time = NOW(),
            duration = :duration,
            notes = :notes,
            updated_at = NOW()
        WHERE id = :time_entry_id
    ");

    $stmt->execute([
        ':time_entry_id' => $timeEntryId,
        ':duration' => $durationMinutes,
        ':notes' => $notes
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'บันทึกเวลาเรียบร้อย',
        'data' => [
            'duration_minutes' => $durationMinutes
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}