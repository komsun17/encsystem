composer config -g disable-tls true
composer require phpoffice/phpspreadsheet<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $userId = $_SESSION['user_id'];
    $month = $_GET['month'] ?? date('m');
    $year = $_GET['year'] ?? date('Y');

    // ดึงข้อมูลรายวันในเดือน
    $stmt = $conn->prepare("
        SELECT 
            DATE(start_time) as work_date,
            SUM(duration) as total_minutes,
            COUNT(DISTINCT project_id) as project_count
        FROM time_entries
        WHERE user_id = :user_id 
        AND MONTH(start_time) = :month
        AND YEAR(start_time) = :year
        AND status = 'completed'
        GROUP BY DATE(start_time)
        ORDER BY work_date ASC
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':month' => $month,
        ':year' => $year
    ]);

    $dailyStats = $stmt->fetchAll();

    // ดึงข้อมูลสรุปตาม Project
    $stmt = $conn->prepare("
        SELECT 
            p.name as project_name,
            SUM(t.duration) as total_minutes,
            COUNT(DISTINCT DATE(t.start_time)) as work_days
        FROM time_entries t
        JOIN projects p ON t.project_id = p.id
        WHERE t.user_id = :user_id 
        AND MONTH(t.start_time) = :month
        AND YEAR(t.start_time) = :year
        AND t.status = 'completed'
        GROUP BY p.id
        ORDER BY total_minutes DESC
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':month' => $month,
        ':year' => $year
    ]);

    $projectStats = $stmt->fetchAll();

    // คำนวณเวลารวมทั้งเดือน
    $totalMonthlyMinutes = array_sum(array_column($dailyStats, 'total_minutes'));
    
    // คำนวณ % การใช้เวลาแต่ละ Project
    foreach ($projectStats as &$project) {
        $project['percentage'] = round(($project['total_minutes'] / $totalMonthlyMinutes) * 100, 2);
        $project['total_hours'] = round($project['total_minutes'] / 60, 2);
    }

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