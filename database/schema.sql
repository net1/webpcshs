-- ระบบบริหารกิจการดิจิทัล (DigM-I) Database Schema
-- Princess Chulabhorn's College Kamphaeng Phet

CREATE DATABASE IF NOT EXISTS digm_system DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE digm_system;

-- ตารางหอพัก (Dormitories)
CREATE TABLE dormitories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางนักเรียน (Students)
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    grade VARCHAR(10) NOT NULL,
    dormitory_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dormitory_id) REFERENCES dormitories(id) ON DELETE RESTRICT,
    INDEX idx_student_id (student_id),
    INDEX idx_dormitory (dormitory_id),
    INDEX idx_name (firstname, lastname)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางบันทึกทั่วไป (Records)
CREATE TABLE records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_type ENUM(
        'dormitory',
        'food-stock',
        'food-distribute',
        'hospital',
        'uniform',
        'duty',
        'repair',
        'inventory-receive',
        'inventory-issue',
        'permission',
        'student-leave'
    ) NOT NULL,
    record_date DATE NOT NULL,
    dormitory_id INT NULL,
    details TEXT,
    recorder VARCHAR(100) NOT NULL,
    report_status ENUM('รอรับรองรายงาน', 'รับรองรายงาน', 'ไม่รับรองรายงาน') DEFAULT 'รอรับรองรายงาน',
    approval_name VARCHAR(100) NULL,
    approval_details TEXT NULL,
    approval_date DATE NULL,
    approval_timestamp TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dormitory_id) REFERENCES dormitories(id) ON DELETE SET NULL,
    INDEX idx_type (record_type),
    INDEX idx_date (record_date),
    INDEX idx_status (report_status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางนักเรียนในบันทึก (Record Students) - สำหรับบันทึกที่เกี่ยวกับหลายนักเรียน
CREATE TABLE record_students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    student_id INT NULL,
    student_name VARCHAR(200),
    status VARCHAR(50),
    reason TEXT,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    INDEX idx_record (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางอาหารเสริม (Food Supplements)
CREATE TABLE food_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    food_type ENUM('นม', 'ขนมปัง') NOT NULL,
    transaction_type ENUM('รับ', 'จ่าย') NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    INDEX idx_record (record_id),
    INDEX idx_type (food_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางโรงพยาบาล (Hospital Records)
CREATE TABLE hospital_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    status ENUM('เข้าโรงพยาบาล', 'ออกโรงพยาบาล') NOT NULL,
    hospital_name VARCHAR(200) NOT NULL,
    symptoms TEXT NOT NULL,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    INDEX idx_record (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางแจ้งซ่อม (Repair Requests)
CREATE TABLE repair_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    location VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL,
    problem TEXT NOT NULL,
    urgency ENUM('ด่วนมาก', 'ด่วน', 'ปกติ') NOT NULL,
    repair_status ENUM('รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น') DEFAULT 'รอดำเนินการ',
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    INDEX idx_record (record_id),
    INDEX idx_status (repair_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางคลังวัสดุ/ครุภัณฑ์ (Inventory)
CREATE TABLE inventory_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    item_name VARCHAR(200) NOT NULL,
    category ENUM('วัสดุ', 'ครุภัณฑ์') NOT NULL,
    transaction_type ENUM('รับ', 'จ่าย') NOT NULL,
    quantity INT NOT NULL,
    unit VARCHAR(50) NOT NULL,
    issued_to VARCHAR(200) NULL,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    INDEX idx_record (record_id),
    INDEX idx_item (item_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางขอ��นุญาต (Permission Requests)
CREATE TABLE permission_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    subject TEXT NOT NULL,
    purpose TEXT NOT NULL,
    date_from DATE NOT NULL,
    date_to DATE NOT NULL,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    INDEX idx_record (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางขออนุญาตลา (Student Leave)
CREATE TABLE student_leave_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    leave_type VARCHAR(100) NOT NULL,
    student_name VARCHAR(200) NOT NULL,
    date_from DATE NOT NULL,
    date_to DATE NOT NULL,
    parent_name VARCHAR(200) NOT NULL,
    parent_phone VARCHAR(20) NOT NULL,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    INDEX idx_record (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางผู้ใช้ (Users) - สำหรับระบบ authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    fullname VARCHAR(200) NOT NULL,
    role ENUM('admin', 'teacher', 'staff') DEFAULT 'staff',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ตารางการตั้งค่าระบบ (System Settings)
CREATE TABLE system_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert ข้อมูลเริ่มต้น
INSERT INTO system_settings (setting_key, setting_value) VALUES
('system_title', 'ระบบบริหารกิจการดิจิทัล (DigM-I)'),
('school_name', 'โรงเรียนวิทยาศาสตร์จุฬาภรณราชวิทยาลัย กำแพงเพชร'),
('approval_code', '2812'),
('max_records', '999');

-- Insert ผู้ใช้เริ่มต้น (password: admin123)
INSERT INTO users (username, password_hash, fullname, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ผู้ดูแลระบบ', 'admin');

-- Insert หอพักตัวอย่าง
INSERT INTO dormitories (name) VALUES
('หอ 1 (หญิง)'),
('หอ 2 (หญิง)'),
('หอ 3 (ชาย)'),
('หอ 4 (ชาย)'),
('หอ 5 (ชาย)');

-- View สำหรับดูสถิติ
CREATE VIEW dashboard_stats AS
SELECT
    (SELECT COUNT(*) FROM records WHERE record_type = 'dormitory') as dormitory_count,
    (SELECT COUNT(*) FROM records WHERE record_type IN ('food-stock', 'food-distribute')) as food_count,
    (SELECT COUNT(*) FROM records WHERE record_type = 'hospital') as hospital_count,
    (SELECT COUNT(*) FROM records) as total_count,
    (SELECT COUNT(*) FROM students) as student_count,
    (SELECT COUNT(*) FROM dormitories) as dormitory_list_count;

-- View สำหรับสต็อกอาหาร
CREATE VIEW food_stock_summary AS
SELECT
    food_type,
    SUM(CASE WHEN transaction_type = 'รับ' THEN quantity ELSE -quantity END) as current_stock
FROM food_records
GROUP BY food_type;

-- View สำหรับสต็อกวัสดุ/ครุภัณฑ์
CREATE VIEW inventory_stock_summary AS
SELECT
    item_name,
    category,
    unit,
    SUM(CASE WHEN transaction_type = 'รับ' THEN quantity ELSE -quantity END) as current_stock
FROM inventory_records
GROUP BY item_name, category, unit
HAVING current_stock > 0;
