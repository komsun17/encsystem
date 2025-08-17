<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $userId = $_SESSION['user_id'];
    $projectId = $_POST['project_id'] ?? null;
    $drawingId = $_POST['drawing_id'] ?? null;
    $activityType = $_POST['activity_type'] ?? null;
    
    if (!$projectId || !$drawingId || !$activityType) {
        throw new Exception('กรุณาระบุข้อมูลให้ครบถ้วน');
    }

    // ตรวจสอบว่ามี timer ที่ active อยู่หรือไม่
    $stmt = $conn->prepare("SELECT id FROM time_entries WHERE user_id = :user_id AND status = 'active'");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        throw new Exception('คุณมี timer ที่กำลังทำงานอยู่');
    }

    // สร้าง time entry ใหม่
    $stmt = $conn->prepare("
        INSERT INTO time_entries 
        (user_id, project_id, drawing_id, start_time, status, activity_type) 
        VALUES 
        (:user_id, :project_id, :drawing_id, NOW(), 'active', :activity_type)
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':project_id' => $projectId,
        ':drawing_id' => $drawingId,
        ':activity_type' => $activityType
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'เริ่มจับเวลาเรียบร้อย'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}