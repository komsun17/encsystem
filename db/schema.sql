-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS encsystem CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE encsystem;

-- ตารางผู้ใช้งาน
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('engineer','admin')
);

-- ตารางโปรเจกต์
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_code VARCHAR(50) UNIQUE,
    project_name VARCHAR(100),
    start_date DATE,
    end_date DATE
);

-- ตาราง Drawing
CREATE TABLE drawings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    drawing_code VARCHAR(50),
    drawing_name VARCHAR(100),
    project_id INT,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);

-- ตารางบันทึกเวลาหลัก
CREATE TABLE time_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    drawing_id INT,
    task_name VARCHAR(255),
    start_time DATETIME NOT NULL,
    end_time DATETIME,
    duration_minutes INT,
    status ENUM('running','paused','stopped') DEFAULT 'running',
    note TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (drawing_id) REFERENCES drawings(id)
);

-- ตารางเก็บช่วงเวลาย่อย
CREATE TABLE time_log_segments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    time_log_id INT NOT NULL,
    segment_start DATETIME NOT NULL,
    segment_end DATETIME,
    FOREIGN KEY (time_log_id) REFERENCES time_logs(id)
);
