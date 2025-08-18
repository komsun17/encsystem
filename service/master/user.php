<?php
session_start();
header('Content-Type: application/json');
require_once('../connect.php');

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? 'list';
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        throw new Exception('กรุณาเข้าสู่ระบบ');
    }

    switch($action) {
        case 'create':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'user';

            if (!$username || !$password || !$name) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }

            // ตรวจสอบ username ซ้ำ
            $stmt = $conn->prepare("
                SELECT id FROM users 
                WHERE username = :username 
                AND status != 'deleted'
            ");
            $stmt->execute([':username' => $username]);

            if ($stmt->rowCount() > 0) {
                throw new Exception('Username นี้มีผู้ใช้งานแล้ว');
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO users (
                    username,
                    password,
                    name,
                    email,
                    role,
                    status,
                    created_by
                ) VALUES (
                    :username,
                    :password,
                    :name,
                    :email,
                    :role,
                    'active',
                    :created_by
                )
            ");

            $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':name' => $name,
                ':email' => $email,
                ':role' => $role,
                ':created_by' => $userId
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => 'เพิ่มผู้ใช้งานเรียบร้อย',
                'data' => ['id' => $conn->lastInsertId()]
            ]);
            break;

        case 'update':
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? '';
            $status = $_POST['status'] ?? 'active';
            $password = $_POST['password'] ?? '';

            if (!$id || !$name) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }

            $sql = "
                UPDATE users 
                SET name = :name,
                    email = :email,
                    role = :role,
                    status = :status,
                    updated_at = NOW()
            ";

            $params = [
                ':id' => $id,
                ':name' => $name,
                ':email' => $email,
                ':role' => $role,
                ':status' => $status
            ];

            // Update password only if provided
            if ($password) {
                $sql .= ", password = :password";
                $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $sql .= " WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            echo json_encode([
                'status' => 'success',
                'message' => 'อัพเดทข้อมูลผู้ใช้งานเรียบร้อย'
            ]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? '';

            if (!$id) {
                throw new Exception('ไม่พบรหัสผู้ใช้งาน');
            }

            // Soft delete
            $stmt = $conn->prepare("
                UPDATE users 
                SET status = 'deleted',
                    updated_at = NOW()
                WHERE id = :id
            ");

            $stmt->execute([':id' => $id]);

            echo json_encode([
                'status' => 'success',
                'message' => 'ลบผู้ใช้งานเรียบร้อย'
            ]);
            break;

        case 'get':
            $id = $_GET['id'] ?? '';

            if (!$id) {
                throw new Exception('ไม่พบรหัสผู้ใช้งาน');
            }

            $stmt = $conn->prepare("
                SELECT id, username, name, email, role, status, 
                       created_at, updated_at
                FROM users
                WHERE id = :id
            ");

            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'data' => $user
            ]);
            break;

        default: // list
            $status = $_GET['status'] ?? 'active';
            $search = $_GET['search'] ?? '';

            $sql = "
                SELECT id, username, name, email, role, status, 
                       created_at, updated_at
                FROM users
                WHERE status = :status
            ";

            if ($search) {
                $sql .= " AND (name LIKE :search OR username LIKE :search OR email LIKE :search)";
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

} catch(Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}