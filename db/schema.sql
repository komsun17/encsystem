-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS encsystem CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE encsystem;

-- ตารางผู้ใช้งาน
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'engineer') DEFAULT 'engineer',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ตารางโปรเจกต์
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_code VARCHAR(50) NOT NULL UNIQUE,
    project_name VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ตาราง Drawing
CREATE TABLE drawings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    drawing_code VARCHAR(50) NOT NULL,
    drawing_name VARCHAR(255) NOT NULL,
    project_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- ตารางบันทึกเวลา
CREATE TABLE time_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    drawing_id INT DEFAULT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME,
    duration_minutes INT GENERATED ALWAYS AS (
        TIMESTAMPDIFF(MINUTE, start_time, end_time)
    ) STORED,
    note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (drawing_id) REFERENCES drawings(id) ON DELETE SET NULL
);
