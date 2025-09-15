<?php
header('Content-Type: application/json');
require_once('../connect.php');

// เชื่อมต่อฐานข้อมูล NAV
try {
    $navConn = new PDO(
        "mysql:host=mysql;dbname=thaisinto_sync_nav;charset=utf8mb4",
        "devuser", // ปรับตาม user จริง
        "devpass", // ปรับตาม password จริง
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'เชื่อมต่อฐานข้อมูล NAV ไม่สำเร็จ: ' . $e->getMessage()]);
    exit;
}

try {
    // ดึง Project Code ที่ขึ้นต้นด้วย B,E,M,P,K,T เท่านั้น
    $stmt = $navConn->prepare("
        SELECT DISTINCT project_code, customer_name 
        FROM sales_header 
        WHERE project_code IS NOT NULL 
          AND project_code != ''
          AND LEFT(project_code,1) IN ('B','E','M','P','K','T')
    ");
    $stmt->execute();
    $navProjects = $stmt->fetchAll();

    // ดึง Project Code ที่มีอยู่แล้วใน projects
    $stmt2 = $conn->prepare("SELECT code FROM projects");
    $stmt2->execute();
    $existing = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0);

    $inserted = 0;
    foreach ($navProjects as $proj) {
        if (!in_array($proj['project_code'], $existing)) {
            // เพิ่มเข้า projects โดยไม่ต้อง sync start_date, end_date, status
            $ins = $conn->prepare("INSERT INTO projects (code, client_name) VALUES (?, ?)");
            $ins->execute([$proj['project_code'], $proj['customer_name']]);
            $inserted++;
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => "Sync สำเร็จ เพิ่มใหม่ $inserted รายการ"
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
