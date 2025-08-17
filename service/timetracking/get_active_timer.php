<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT 
            t.id,
            t.start_time,
            t.status,
            t.activity_type,
            p.name as project_name,
            d.drawing_no
        FROM time_entries t
        JOIN projects p ON t.project_id = p.id
        JOIN drawings d ON t.drawing_id = d.id
        WHERE t.user_id = :user_id 
        AND t.status IN ('active', 'paused')
        LIMIT 1
    ");

    $stmt->execute([':user_id' => $userId]);
    $timer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($timer) {
        $startTime = new DateTime($timer['start_time']);
        $currentTime = new DateTime();
        $duration = $currentTime->getTimestamp() - $startTime->getTimestamp();

        $timer['duration_minutes'] = round($duration / 60);
        $timer['duration_formatted'] = sprintf(
            '%02d:%02d:%02d',
            floor($duration / 3600),
            floor(($duration % 3600) / 60),
            $duration % 60
        );
    }

    echo json_encode([
        'status' => 'success',
        'data' => $timer ?: null
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}