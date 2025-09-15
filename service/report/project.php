<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $userId = $_GET['user_id'] ?? '';
    $projectCode = $_GET['project_code'] ?? '';
    $from = $_GET['from'] ?? '';
    $to = $_GET['to'] ?? '';

    $params = [];
    $where = [];

    if ($userId) {
        $where[] = "t.user_id = :user_id";
        $params[':user_id'] = $userId;
    }
    if ($projectCode) {
        $where[] = "p.code = :project_code";
        $params[':project_code'] = $projectCode;
    }
    if ($from) {
        $where[] = "DATE(t.start_time) >= :from";
        $params[':from'] = $from;
    }
    if ($to) {
        $where[] = "DATE(t.start_time) <= :to";
        $params[':to'] = $to;
    }

    $sql = "
        SELECT 
            t.id,
            t.start_time,
            t.end_time,
            t.drawing_no,
            t.activity_type,
            t.duration,
            t.note,
            u.name as user_name,
            p.code as project_code,
            p.client_name as project_name
        FROM time_entries t
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN projects p ON t.project_id = p.id
        WHERE t.status = 'completed'
        " . (count($where) ? " AND " . implode(" AND ", $where) : "") . "
        ORDER BY t.start_time ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // summary
    $totalMinutes = array_sum(array_map(function($e){ return (int)($e['duration']/60); }, $entries));
    $summary = [
        'total_minutes' => $totalMinutes
    ];

    echo json_encode([
        'status' => 'success',
        'data' => [
            'entries' => $entries,
            'summary' => $summary
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}