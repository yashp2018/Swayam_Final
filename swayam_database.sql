/* =========================================
   swayam — Import-ready SQL
   - Creates database and tables (InnoDB, utf8mb4)
   - Disables FK checks during DDL
   - Adds useful UNIQUE constraints
   - Safe INSERTs (ON DUPLICATE KEY UPDATE / INSERT IGNORE)
   ========================================= */

-- Create DB and use it
CREATE DATABASE IF NOT EXISTS swayam
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE swayam;

-- Temporarily disable foreign key checks while creating tables
SET FOREIGN_KEY_CHECKS = 0;

-- ================
-- ADMIN USERS TABLE
-- ================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role ENUM('admin', 'super_admin') DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================
-- ADMIN SESSIONS TABLE
-- =====================
CREATE TABLE IF NOT EXISTS admin_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================
-- GENERAL USERS TABLE
-- ================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','promoter','admin') DEFAULT 'user',
    status ENUM('active','suspended') DEFAULT 'active',
    language ENUM('en','hi','mr') DEFAULT 'en',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- CONTENT SUBMISSIONS TABLE
-- (references users.id for user_id and admin_id)
-- ============================
CREATE TABLE IF NOT EXISTS content_submissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content LONGTEXT,
    type ENUM('audio','video','text','story') NOT NULL,
    category VARCHAR(50) NOT NULL,
    language ENUM('en','hi','mr') NOT NULL,
    media_url VARCHAR(500),
    thumbnail_url VARCHAR(500),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    admin_id INT NULL,
    admin_note TEXT NULL,
    approved_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================
-- CATEGORIES TABLE
-- ================
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name_en VARCHAR(100) NOT NULL,
    name_hi VARCHAR(100),
    name_mr VARCHAR(100),
    icon VARCHAR(50),
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_categories_name_en (name_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================
-- RETREAT PROGRAMS TABLE
-- =====================
CREATE TABLE IF NOT EXISTS retreat_programs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    duration_days INT NOT NULL,
    description TEXT,
    daily_schedule JSON,
    banner_text TEXT,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_retreats_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================
-- TRANSLATIONS TABLE
-- =====================
CREATE TABLE IF NOT EXISTS translations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(100) NOT NULL,
    lang_en TEXT,
    lang_hi TEXT,
    lang_mr TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_translations_key (key_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ================================
-- DEFAULT / SEED DATA (safe inserts)
-- ================================

-- Default admin user (admin_users)
INSERT INTO admin_users (email, password_hash, name, role) VALUES 
(
    'admin@swayamnashik.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'System Administrator',
    'super_admin'
)
ON DUPLICATE KEY UPDATE
    password_hash = VALUES(password_hash),
    name = VALUES(name),
    role = VALUES(role);

-- Default admin user for authentication in users table
INSERT INTO users (name, email, password, role, status) VALUES 
(
    'SWAYAM Administrator',
    'admin@mail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    'active'
)
ON DUPLICATE KEY UPDATE
    password = VALUES(password),
    status = VALUES(status),
    name = VALUES(name),
    role = VALUES(role);

-- Default categories (INSERT IGNORE to avoid duplicates if name_en is unique)
INSERT IGNORE INTO categories (name_en, name_hi, name_mr, icon) VALUES
('Spirituality', 'अध्यात्म', 'अध्यात्म', 'fas fa-om'),
('Meditation', 'ध्यान', 'ध्यान', 'fas fa-leaf'),
('Yoga', 'योग', 'योग', 'fas fa-spa'),
('Personality Development', 'व्यक्तित्व विकास', 'व्यक्तिमत्व विकास', 'fas fa-user-graduate'),
('Emotional Healing', 'भावनात्मक उपचार', 'भावनिक उपचार', 'fas fa-heart-pulse'),
('Ancient Wisdom', 'प्राचीन ज्ञान', 'प्राचीन ज्ञान', 'fas fa-scroll'),
('Life Stories', 'जीवन कहानियाँ', 'जीवन कथा', 'fas fa-book-heart');

-- Retreat programs (use ON DUPLICATE KEY UPDATE because name is unique)
INSERT INTO retreat_programs (name, duration_days, description, daily_schedule) VALUES
('Spiritual Detox', 3, '3-day program for digital detox and mindfulness',
    '{"5:30": "Morning Meditation", "7:00": "Yoga", "8:30": "Breakfast"}'),
('Balanced Living', 5, '5-day lifestyle program',
    '{"5:30": "Meditation", "7:00": "Yoga", "10:00": "Farm Activities"}'),
('Deep Transformation', 10, '10-day spiritual program',
    '{"5:00": "Silent Meditation", "6:30": "Advanced Yoga"}')
ON DUPLICATE KEY UPDATE
    duration_days = VALUES(duration_days),
    description = VALUES(description),
    daily_schedule = VALUES(daily_schedule);

-- (Optional) Example translation keys — adjust as needed
INSERT INTO translations (key_name, lang_en, lang_hi, lang_mr) VALUES
('welcome_message', 'Welcome to Swayam', 'स्वागतम', 'स्वागत'),
('book_now', 'Book Now', 'अब बुक करें', 'आता बुक करा')
ON DUPLICATE KEY UPDATE
    lang_en = VALUES(lang_en),
    lang_hi = VALUES(lang_hi),
    lang_mr = VALUES(lang_mr);

-- Note: content_submissions rows typically depend on actual users and media — no sample inserts included here.

-- Done.