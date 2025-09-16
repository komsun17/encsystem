<?php
session_start();
require_once('../connect.php');
header('Content-Type: application/json');

try {
    // ตรวจสอบ user_id: ถ้าเป็นค่าว่างหรือไม่ส่งมา ให้ดึงข้อมูลทุก user
    $filterAllUser = false;
    if (isset($_GET['user_id']) && $_GET['user_id'] !== '' && is_numeric($_GET['user_id'])) {
        $userId = (int)$_GET['user_id'];
    } else if (isset($_GET['user_id']) && $_GET['user_id'] === '') {
        $filterAllUser = true;
        $userId = null;
    } else {
        $userId = $_SESSION['user_id'] ?? null;
    }

    if (!$userId && !$filterAllUser) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    // Debug log ค่าที่รับเข้ามา
    error_log("RAW month=" . (isset($_GET['month']) ? $_GET['month'] : 'NULL'));
    error_log("RAW year=" . (isset($_GET['year']) ? $_GET['year'] : 'NULL'));

    // แปลง month และ year ให้เป็น integer เพื่อให้เทียบกับ MySQL ได้ถูกต้อง
    // รองรับ input type="month" ที่ส่งค่า YYYY-MM
    if (isset($_GET['month']) && preg_match('/^\d{4}-\d{2}$/', $_GET['month'])) {
        list($year, $month) = explode('-', $_GET['month']);
        $month = (int)$month;
        $year = (int)$year;
    } else {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
    }


    // --- ดึงข้อมูลรายวันในเดือน ---
    $sql = "
        SELECT 
            DATE(t.start_time) as work_date,
            SUM(t.duration) as total_minutes,
            COUNT(DISTINCT t.id) as entry_count
        FROM time_entries t
        WHERE MONTH(t.start_time) = :month
        AND YEAR(t.start_time) = :year
        AND t.status = 'completed'
    ";
    if (!$filterAllUser) {
        $sql .= " AND t.user_id = :user_id";
    }
    $sql .= " GROUP BY DATE(t.start_time) ORDER BY work_date ASC";
    $stmt = $conn->prepare($sql);
    $params = [
        ':month' => $month,
        ':year' => $year
    ];
    if (!$filterAllUser) {
        $params[':user_id'] = $userId;
    }
    $stmt->execute($params);
    $dailyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- ดึงข้อมูลสรุปตาม Project ---
    $sql = "
        SELECT 
            p.id,
            p.code,
            p.client_name AS project_name,
            SUM(t.duration) as total_minutes,
            COUNT(DISTINCT t.id) as entry_count
        FROM time_entries t
        JOIN projects p ON t.project_id = p.id
        WHERE MONTH(t.start_time) = :month
        AND YEAR(t.start_time) = :year
        AND t.status = 'completed'
    ";
    if (!$filterAllUser) {
        $sql .= " AND t.user_id = :user_id";
    }
    $sql .= " GROUP BY p.id, p.code, p.client_name ORDER BY total_minutes DESC";
    $stmt = $conn->prepare($sql);
    $params = [
        ':month' => $month,
        ':year' => $year
    ];
    if (!$filterAllUser) {
        $params[':user_id'] = $userId;
    }
    $stmt->execute($params);
    $projectStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- ดึงข้อมูลแต่ละรายการ (entries) ---
    $sql = "
        SELECT 
            t.id,
            t.start_time,
            t.end_time,
            t.drawing_no,
            t.activity_type,
            t.duration,
            t.note,
            t.user_id,
            u.name as user_name,
            p.code as project_code
        FROM time_entries t
        JOIN users u ON t.user_id = u.id
        JOIN projects p ON t.project_id = p.id
        WHERE MONTH(t.start_time) = :month
        AND YEAR(t.start_time) = :year
        AND t.status = 'completed'
    ";
    if (!$filterAllUser) {
        $sql .= " AND t.user_id = :user_id";
    }
    $sql .= " ORDER BY t.start_time ASC";
    $stmt = $conn->prepare($sql);
    $params = [
        ':month' => $month,
        ':year' => $year
    ];
    if (!$filterAllUser) {
        $params[':user_id'] = $userId;
    }
    error_log("user_id=" . ($userId ?? 'ALL') . ", month=$month, year=$year");
    $stmt->execute($params);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("entries_count=" . count($entries));
    if (count($entries) === 0) {
        error_log("SQL Debug: " . print_r($stmt->errorInfo(), true));
    }

    // คำนวณสรุปรวม
    $totalMonthlyMinutes = array_sum(array_column($dailyStats, 'total_minutes'));

    echo json_encode([
        'status' => 'success',
        'data' => [
            'month' => str_pad($month, 2, '0', STR_PAD_LEFT),
            'year' => $year,
            'daily_stats' => $dailyStats,
            'project_stats' => $projectStats,
            'entries' => $entries,
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
