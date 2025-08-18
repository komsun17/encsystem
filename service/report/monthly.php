<?php
session_start();
require_once('../connect.php');
header('Content-Type: application/json');

try {
    $userId = $_SESSION['user_id'] ?? null;
    
    if (!$userId) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    $month = $_GET['month'] ?? date('m');
    $year = $_GET['year'] ?? date('Y');

    // ดึงข้อมูลรายวันในเดือน
    $stmt = $conn->prepare("
        SELECT 
            DATE(t.start_time) as work_date,
            SUM(t.duration) as total_minutes,
            COUNT(DISTINCT t.id) as entry_count
        FROM time_entries t
        WHERE t.user_id = :user_id 
        AND MONTH(t.start_time) = :month
        AND YEAR(t.start_time) = :year
        AND t.status = 'completed'
        GROUP BY DATE(t.start_time)
        ORDER BY work_date ASC
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':month' => $month,
        ':year' => $year
    ]);

    $dailyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสรุปตาม Project
    $stmt = $conn->prepare("
        SELECT 
            p.id,
            p.name,
            SUM(t.duration) as total_minutes,
            COUNT(DISTINCT t.id) as entry_count
        FROM time_entries t
        JOIN projects p ON t.project_id = p.id
        WHERE t.user_id = :user_id 
        AND MONTH(t.start_time) = :month
        AND YEAR(t.start_time) = :year
        AND t.status = 'completed'
        GROUP BY p.id, p.name
        ORDER BY total_minutes DESC
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':month' => $month,
        ':year' => $year
    ]);

    $projectStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // คำนวณสรุปรวม
    $totalMonthlyMinutes = array_sum(array_column($dailyStats, 'total_minutes'));

    echo json_encode([
        'status' => 'success',
        'data' => [
            'month' => $month,
            'year' => $year,
            'daily_stats' => $dailyStats,
            'project_stats' => $projectStats,
            'summary' => [
                'total_minutes' => $totalMonthlyMinutes,
                'total_hours' => round($totalMonthlyMinutes / 60, 2),
                'working_days' => count($dailyStats)
            ]
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}