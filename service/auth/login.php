<?php
session_start();
header('Content-Type: application/json');

require_once('../connect.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    echo json_encode([
        'status' => 'error',
        'message' => 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน'
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, name, username, password, role, updated_at FROM users WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ไม่พบชื่อผู้ใช้ในระบบ'
        ]);
        exit;
    }

    // ถ้าใช้ hash
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'รหัสผ่านไม่ถูกต้อง'
        ]);
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['AD_LOGIN'] = $user['updated_at'];

    echo json_encode([
        'status' => 'success',
        'message' => 'เข้าสู่ระบบเรียบร้อย',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
            'role' => $user['role']
        ]
    ]);
    exit;
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
    exit;
}
