<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $userId = $_SESSION['user_id'];
    $date = $_GET['date'] ?? date('Y-m-d');

    $stmt = $conn->prepare("
        SELECT 
            t.id,
            t.start_time,
            t.end_time,
            t.duration,
            t.activity_type,
            t.notes,
            p.name as project_name,
            d.drawing_no
        FROM time_entries t
        JOIN projects p ON t.project_id = p.id
        JOIN drawings d ON t.drawing_id = d.id
        WHERE t.user_id = :user_id 
        AND DATE(t.start_time) = :date
        AND t.status = 'completed'
        ORDER BY t.start_time ASC
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':date' => $date
    ]);

    $entries = $stmt->fetchAll();
    
    // คำนวณสรุปเวลารวม
    $totalMinutes = array_sum(array_column($entries, 'duration'));
    $totalHours = round($totalMinutes / 60, 2);

    // จัดกลุ่มตาม project
    $projectSummary = [];
    foreach ($entries as $entry) {
        $projectName = $entry['project_name'];
        if (!isset($projectSummary[$projectName])) {
            $projectSummary[$projectName] = 0;
        }
        $projectSummary[$projectName] += $entry['duration'];
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'date' => $date,
            'entries' => $entries,
            'summary' => [
                'total_minutes' => $totalMinutes,
                'total_hours' => $totalHours,
                'by_project' => $projectSummary
            ]
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}