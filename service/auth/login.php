<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../connect.php'; // ตรวจสอบว่า path และ connect.php มี $conn แล้ว

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $stmt = $conn->prepare("SELECT * FROM users WHERE userName = :username");
    $stmt->execute(array(":username" => $_POST['username']));
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if (!empty($row) && password_verify($_POST['password'], $row->password)) {

        $_SESSION['AD_ID'] = $row->id;
        $_SESSION['AD_FIRSTNAME'] = $row->name;
        $_SESSION['AD_USERNAME'] = $row->username;
        $_SESSION['AD_STATUS'] = $row->role;
        $_SESSION['AD_LOGIN'] = $row->updated_at;

        $count = $conn->exec("UPDATE [dbo].[tbl_users] SET updated_at = '" . date("Y-m-d H:i:s") . "' WHERE u_id = $row->id");

        if ($count) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Login Success!'));
        } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'Update Login Failed!'));
        }

    } else {
        http_response_code(401);
        echo json_encode(array('status' => false, 'message' => 'Unauthorized!'));
    }

} else {
    http_response_code(405);
    echo json_encode(array('status' => false, 'message' => 'Method Not Allowed!'));
}
