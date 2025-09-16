<?php
// Secure session cookie settings
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();
header('Content-Type: application/json');

require_once('../connect.php');


// Input validation (basic)
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!preg_match('/^[A-Za-z0-9_@.\-]{3,50}$/', $username)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ชื่อผู้ใช้ไม่ถูกต้อง'
    ]);
    exit;
}

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


    // Regenerate session id after successful login
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['name'] = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
    $_SESSION['role'] = $user['role'];
    $_SESSION['AD_LOGIN'] = $user['updated_at'];

    echo json_encode([
        'status' => 'success',
        'message' => 'เข้าสู่ระบบเรียบร้อย',
        'user' => [
            'id' => $user['id'],
            // Output escaping for name and username
            'name' => htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'),
            'username' => htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'),
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
