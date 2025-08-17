<?php

// เริ่ม session เฉพาะถ้ายังไม่ได้เริ่ม
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
date_default_timezone_set('Asia/Bangkok');

/** Class Database สำหรับติดต่อฐานข้อมูล */
class Database
{
    private $host = "mysql";
    private $dbname = "encsystem";
    private $username = "devuser";
    private $password = "devpass"; // <== ถ้ามีรหัสผ่านให้ใส่ตรงนี้
    private $conn = null;

    public function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            echo "❌ ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $exception->getMessage();
            exit();
        }
        return $this->conn; 
    }
}

/** ประกาศ Instance ของ Class Database */
$Database = new Database();
$conn = $Database->connect();
