<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');
require_once('../helpers/validator.php');

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? 'list';
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    switch ($action) {
        case 'create':
            $rules = [
                'project_id' => ['required' => true],
                'drawing_no' => ['required' => true, 'max' => 100],
                'revision' => ['max' => 10]
            ];

            if (!$validator->validate($_POST, $rules)) {
                throw new Exception(json_encode($validator->getErrors()));
            }

            // Check for duplicate drawing number
            $stmt = $conn->prepare("
                SELECT id FROM drawings 
                WHERE drawing_no = :drawing_no 
                AND project_id = :project_id
                AND status != 'deleted'
            ");

            $stmt->execute([
                ':drawing_no' => $_POST['drawing_no'],
                ':project_id' => $_POST['project_id']
            ]);

            if ($stmt->rowCount() > 0) {
                throw new Exception('Drawing No. นี้มีอยู่ในระบบแล้ว');
            }
            $projectId = $_POST['project_id'] ?? '';
            $drawingNo = $_POST['drawing_no'] ?? '';
            $revision = $_POST['revision'] ?? '';
            $description = $_POST['description'] ?? '';

            if (!$projectId || !$drawingNo) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }

            // ตรวจสอบว่า Drawing No. ซ้ำหรือไม่
            $stmt = $conn->prepare("
                SELECT id FROM drawings 
                WHERE drawing_no = :drawing_no 
                AND project_id = :project_id
                AND status != 'deleted'
            ");
            $stmt->execute([
                ':drawing_no' => $drawingNo,
                ':project_id' => $projectId
            ]);

            if ($stmt->rowCount() > 0) {
                throw new Exception('Drawing No. นี้มีอยู่ในระบบแล้ว');
            }

            $stmt = $conn->prepare("
                INSERT INTO drawings (
                    project_id,
                    drawing_no,
                    revision,
                    description,
                    status,
                    created_by
                ) VALUES (
                    :project_id,
                    :drawing_no,
                    :revision,
                    :description,
                    'active',
                    :created_by
                )
            ");

            $stmt->execute([
                ':project_id' => $projectId,
                ':drawing_no' => $drawingNo,
                ':revision' => $revision,
                ':description' => $description,
                ':created_by' => $userId
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'บันทึกข้อมูล Drawing เรียบร้อย',
                'data' => ['id' => $conn->lastInsertId()]
            ]);
            break;

        case 'update':
            $id = $_POST['id'] ?? '';
            $drawingNo = $_POST['drawing_no'] ?? '';
            $revision = $_POST['revision'] ?? '';
            $description = $_POST['description'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if (!$id || !$drawingNo) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }

            $stmt = $conn->prepare("
                UPDATE drawings 
                SET drawing_no = :drawing_no,
                    revision = :revision,
                    description = :description,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => $id,
                ':drawing_no' => $drawingNo,
                ':revision' => $revision,
                ':description' => $description,
                ':status' => $status
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'อัพเดทข้อมูล Drawing เรียบร้อย'
            ]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? '';

            if (!$id) {
                throw new Exception('ไม่พบรหัส Drawing');
            }

            $stmt = $conn->prepare("
                UPDATE drawings 
                SET status = 'deleted',
                    updated_at = NOW()
                WHERE id = :id
            ");

            $stmt->execute([':id' => $id]);

            echo json_encode([
                'status' => 'success',
                'message' => 'ลบข้อมูล Drawing เรียบร้อย'
            ]);
            break;

        case 'get':
            $id = $_GET['id'] ?? '';

            if (!$id) {
                throw new Exception('ไม่พบรหัส Drawing');
            }

            $stmt = $conn->prepare("
                SELECT d.*, p.name as project_name
                FROM drawings d
                JOIN projects p ON d.project_id = p.id
                WHERE d.id = :id
            ");

            $stmt->execute([':id' => $id]);
            $drawing = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'data' => $drawing
            ]);
            break;

        default: // list
            $projectId = $_GET['project_id'] ?? '';
            $status = $_GET['status'] ?? 'active';
            $search = $_GET['search'] ?? '';

            $sql = "
                SELECT d.*, p.name as project_name
                FROM drawings d
                JOIN projects p ON d.project_id = p.id
                WHERE d.status = :status
            ";

            if ($projectId) {
                $sql .= " AND d.project_id = :project_id";
            }

            if ($search) {
                $sql .= " AND (d.drawing_no LIKE :search OR d.description LIKE :search)";
            }

            $sql .= " ORDER BY d.created_at DESC";

            $stmt = $conn->prepare($sql);

            $params = [':status' => $status];
            if ($projectId) {
                $params[':project_id'] = $projectId;
            }
            if ($search) {
                $params[':search'] = "%$search%";
            }

            $stmt->execute($params);

            echo json_encode([
                'status' => 'success',
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
