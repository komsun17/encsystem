<?php
session_start();
require_once('../connect.php');
require_once('../helpers/validator.php');
header('Content-Type: application/json');


try {
    $action = $_POST['action'] ?? $_GET['action'] ?? 'list';
    $userId = $_SESSION['user_id'] ?? null;
    $validator = new Validator();

    if (!$userId) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    switch ($action) {
        case 'create':
            $rules = [
                'name' => ['required' => true, 'max' => 100],
                'client_name' => ['max' => 100],
                'start_date' => ['required' => true, 'date' => true]
            ];

            if (!$validator->validate($_POST, $rules)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'errors' => $validator->getErrors()
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $name = $_POST['name'] ?? '';
            $clientName = $_POST['client_name'] ?? '';
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';

            if (!$name || !$startDate) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }

            $stmt = $conn->prepare("
                INSERT INTO projects (
                    name, 
                    client_name, 
                    start_date, 
                    end_date,
                    status,
                    created_by
                ) VALUES (
                    :name,
                    :client_name,
                    :start_date,
                    :end_date,
                    'active',
                    :created_by
                )
            ");

            $stmt->execute([
                ':name' => $name,
                ':client_name' => $clientName,
                ':start_date' => $startDate,
                ':end_date' => $endDate ?: null,
                ':created_by' => $userId
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'บันทึกข้อมูลโครงการเรียบร้อย',
                'data' => ['id' => $conn->lastInsertId()]
            ]);
            break;

        case 'update':
            $rules = [
                'id' => ['required' => true],
                'name' => ['required' => true, 'max' => 100],
                'client_name' => ['max' => 100],
                'start_date' => ['required' => true, 'date' => true]
            ];

            if (!$validator->validate($_POST, $rules)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'errors' => $validator->getErrors()
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $clientName = $_POST['client_name'] ?? '';
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if (!$id || !$name || !$startDate) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }

            $stmt = $conn->prepare("
                UPDATE projects 
                SET name = :name,
                    client_name = :client_name,
                    start_date = :start_date,
                    end_date = :end_date,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':client_name' => $clientName,
                ':start_date' => $startDate,
                ':end_date' => $endDate ?: null,
                ':status' => $status
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'อัพเดทข้อมูลโครงการเรียบร้อย'
            ]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? '';

            if (!$id) {
                throw new Exception('ไม่พบรหัสโครงการ');
            }

            // Soft delete - just update status
            $stmt = $conn->prepare("
                UPDATE projects 
                SET status = 'deleted',
                    updated_at = NOW()
                WHERE id = :id
            ");

            $stmt->execute([':id' => $id]);

            echo json_encode([
                'status' => 'success',
                'message' => 'ลบข้อมูลโครงการเรียบร้อย'
            ]);
            break;

        case 'get':
            $id = $_GET['id'] ?? '';

            if (!$id) {
                throw new Exception('ไม่พบรหัสโครงการ');
            }

            $stmt = $conn->prepare("
                SELECT 
                    id, name, client_name, 
                    start_date, end_date, status,
                    created_at, updated_at
                FROM projects
                WHERE id = :id
            ");

            $stmt->execute([':id' => $id]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'data' => $project
            ]);
            break;

        default: // list
            $status = $_GET['status'] ?? 'active';
            $search = $_GET['search'] ?? '';

            $sql = "
                SELECT 
                    id, name, client_name, 
                    start_date, end_date, status,
                    created_at, updated_at
                FROM projects
                WHERE status = :status
            ";

            if ($search) {
                $sql .= " AND (name LIKE :search OR client_name LIKE :search)";
            }

            $sql .= " ORDER BY created_at DESC";

            $stmt = $conn->prepare($sql);

            $params = [':status' => $status];
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
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
