<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');
date_default_timezone_set('Asia/Bangkok');

try {
    $userId = $_SESSION['user_id'];
    $timeEntryId = $_POST['time_entry_id'] ?? null;
    $startTime = $_POST['start_time'] ?? null;
    $endTime = $_POST['end_time'] ?? null;
    $notes = $_POST['notes'] ?? '';

    if (!$timeEntryId || !$startTime || !$endTime) {
        throw new Exception('กรุณาระบุข้อมูลให้ครบถ้วน');
    }

    // ตรวจสอบสิทธิ์และการมีอยู่ของ time entry
    $stmt = $conn->prepare("
        SELECT id 
        FROM time_entries 
        WHERE id = :time_entry_id 
        AND user_id = :user_id
    ");
    
    $stmt->execute([
        ':time_entry_id' => $timeEntryId,
        ':user_id' => $userId
    ]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('ไม่พบรายการที่ต้องการแก้ไข');
    }

    // คำนวณระยะเวลา
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);
    $duration = $end->getTimestamp() - $start->getTimestamp();
    $durationMinutes = round($duration / 60);

    if ($duration < 0) {
        throw new Exception('เวลาสิ้นสุดต้องมากกว่าเวลาเริ่มต้น');
    }

    // อัพเดทข้อมูล
    $stmt = $conn->prepare("
        UPDATE time_entries 
        SET start_time = :start_time,
            end_time = :end_time,
            duration = :duration,
            notes = CONCAT(notes, '\nแก้ไขเวลาเมื่อ: ', NOW(), '\n', :edit_note),
            status = 'completed',
            updated_at = NOW()
        WHERE id = :time_entry_id
    ");

    $editNote = "แก้ไขโดย: {$_SESSION['name']}";
    if ($notes) {
        $editNote .= "\nหมายเหตุ: $notes";
    }

    $stmt->execute([
        ':time_entry_id' => $timeEntryId,
        ':start_time' => $startTime,
        ':end_time' => $endTime,
        ':duration' => $durationMinutes,
        ':edit_note' => $editNote
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'อัพเดทข้อมูลเรียบร้อย',
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