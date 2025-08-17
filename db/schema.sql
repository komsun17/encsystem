-- Projects Table
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    client_name VARCHAR(255),
    start_date DATE,
    end_date DATE,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Drawings Table
CREATE TABLE drawings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT,
    drawing_no VARCHAR(100) NOT NULL,
    revision VARCHAR(10),
    description TEXT,
    status ENUM('active', 'archived') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);

-- Time Entries Table
CREATE TABLE time_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    project_id INT,
    drawing_id INT,
    start_time DATETIME,
    end_time DATETIME,
    duration INT,
    activity_type VARCHAR(100),
    status ENUM('active', 'paused', 'completed'),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (drawing_id) REFERENCES drawings(id)
);