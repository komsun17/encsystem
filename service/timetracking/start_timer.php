<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once('../connect.php');
date_default_timezone_set('Asia/Bangkok');

try {
    // Debug: Log all input methods
    error_log('=== START TIMER DEBUG ===');
    error_log('REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD']);
    error_log('CONTENT_TYPE: ' . ($_SERVER['CONTENT_TYPE'] ?? 'not set'));
    error_log('Raw input: ' . file_get_contents('php://input'));
    error_log('$_POST: ' . print_r($_POST, true));
    error_log('$_GET: ' . print_r($_GET, true));
    error_log('$_REQUEST: ' . print_r($_REQUEST, true));

    if (!isset($_SESSION['user_id'])) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    // Try different ways to get the data
    $input_data = null;
    if (!empty($_POST)) {
        $input_data = $_POST;
        error_log('Using $_POST data');
    } else {
        // Try to parse raw input
        $raw_input = file_get_contents('php://input');
        if (!empty($raw_input)) {
            // Try to parse as URL encoded
            parse_str($raw_input, $parsed_data);
            if (!empty($parsed_data)) {
                $input_data = $parsed_data;
                error_log('Using parsed raw input');
            } else {
                // Try to parse as JSON
                $json_data = json_decode($raw_input, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $input_data = $json_data;
                    error_log('Using JSON parsed data');
                }
            }
        }
    }

    if (empty($input_data)) {
        throw new Exception('ไม่พบข้อมูลที่ส่งมา');
    }

    error_log('Final input_data: ' . print_r($input_data, true));

    // Validate required fields
    if (empty($input_data['project_id'])) {
        throw new Exception('กรุณาเลือกโครงการ');
    }

    if (empty($input_data['drawing_no'])) {
        throw new Exception('กรุณาระบุ Drawing No.');
    }

    if (empty($input_data['activity_type'])) {
        throw new Exception('กรุณาเลือกประเภทกิจกรรม');
    }

    // Insert time entry
    $stmt = $conn->prepare("
        INSERT INTO time_entries (
            user_id, project_id, drawing_no, 
            activity_type, start_time, note, status
        ) VALUES (
            :user_id, :project_id, :drawing_no,
            :activity_type, NOW(), :note, 'active'
        )
    ");

    $result = $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':project_id' => $input_data['project_id'],
        ':drawing_no' => $input_data['drawing_no'],
        ':activity_type' => $input_data['activity_type'],
        ':note' => $input_data['note'] ?? ''
    ]);

    if (!$result) {
        throw new Exception('ไม่สามารถบันทึกข้อมูลได้');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'เริ่มจับเวลาแล้ว',
        'data' => ['id' => $conn->lastInsertId()]
    ]);

} catch (Exception $e) {
    error_log('Start Timer Error: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'debug' => [
            'post' => $_POST,
            'input_data' => $input_data ?? null
        ]
    ]);
}
?>