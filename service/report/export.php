<?php
// สร้างไฟล์ Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
require_once('../connect.php');
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

try {
    $userId = $_SESSION['user_id'];
    $month = $_GET['month'] ?? date('m');
    $year = $_GET['year'] ?? date('Y');

    // สร้าง Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ตั้งค่าหัวรายงาน
    $sheet->setCellValue('A1', 'รายงานการทำงานประจำเดือน ' . date("F Y", strtotime("$year-$month-01")));
    $sheet->mergeCells('A1:G1');

    // หัวตาราง
    $headers = ['วันที่', 'โครงการ', 'Drawing No.', 'กิจกรรม', 'เวลาเริ่ม', 'เวลาสิ้นสุด', 'ชั่วโมงทำงาน'];
    foreach ($headers as $idx => $header) {
        $sheet->setCellValue(chr(65 + $idx) . '3', $header);
    }

    // ดึงข้อมูลรายละเอียดการทำงาน
    $stmt = $conn->prepare("
        SELECT 
            DATE(t.start_time) as work_date,
            p.name as project_name,
            d.drawing_no,
            t.activity_type,
            t.start_time,
            t.end_time,
            t.duration
        FROM time_entries t
        JOIN projects p ON t.project_id = p.id
        JOIN drawings d ON t.drawing_id = d.id
        WHERE t.user_id = :user_id 
        AND MONTH(t.start_time) = :month
        AND YEAR(t.start_time) = :year
        AND t.status = 'completed'
        ORDER BY t.start_time ASC
    ");

    $stmt->execute([
        ':user_id' => $userId,
        ':month' => $month,
        ':year' => $year
    ]);

    $row = 4;
    while ($entry = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $row, date('d/m/Y', strtotime($entry['work_date'])));
        $sheet->setCellValue('B' . $row, $entry['project_name']);
        $sheet->setCellValue('C' . $row, $entry['drawing_no']);
        $sheet->setCellValue('D' . $row, $entry['activity_type']);
        $sheet->setCellValue('E' . $row, date('H:i', strtotime($entry['start_time'])));
        $sheet->setCellValue('F' . $row, date('H:i', strtotime($entry['end_time'])));
        $sheet->setCellValue('G' . $row, round($entry['duration'] / 60, 2));
        $row++;
    }

    // จัดรูปแบบตาราง
    $lastRow = $row - 1;
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);
    $sheet->getStyle('A3:G3')->getFont()->setBold(true);
    $sheet->getStyle('A3:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // เพิ่มสีพื้นหลังหัวตาราง
    $sheet->getStyle('A3:G3')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setRGB('CCCCCC');

    // จัดรูปแบบแถวสรุป
    $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
    $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

    // เพิ่มสรุปรายโครงการ
    $row += 2;
    $sheet->setCellValue('A' . $row, 'สรุปรายโครงการ');
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);

    $row++;
    $sheet->setCellValue('A' . $row, 'โครงการ');
    $sheet->setCellValue('B' . $row, 'ชั่วโมงทำงาน');
    $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

    // ดึงข้อมูลสรุปรายโครงการ
    $stmt = $conn->prepare("
        SELECT 
            p.name as project_name,
            SUM(t.duration) as total_minutes
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

    $startSummaryRow = $row + 1;
    while ($project = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row++;
        $sheet->setCellValue('A' . $row, $project['project_name']);
        $sheet->setCellValue('B' . $row, round($project['total_minutes'] / 60, 2));
    }

    // จัดรูปแบบตารางสรุป
    $sheet->getStyle('B' . $startSummaryRow . ':B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle('A' . ($startSummaryRow - 1) . ':B' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    // ปรับความกว้างคอลัมน์อัตโนมัติ
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // สร้างไฟล์ Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="timesheet_' . $year . '_' . $month . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
