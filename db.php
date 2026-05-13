<?php
/*
 * Database Configuration and Connection
 * Reads configuration from environment variables when available.
 * Defaults to SQLite for local development when DB_TYPE is not set.
 */

// Read environment overrides
$env_db_type = getenv('DB_TYPE') ?: 'mysql';

// Helper to mark DB error
function db_error($msg) {
    define('DB_ERROR', $msg);
    define('DB_TYPE', 'none');
}

if ($env_db_type && strtolower($env_db_type) === 'mysql') {
    // Use MySQL from environment variables
    $db_host = getenv('DB_HOST') ?: '127.0.0.1';
    $db_port = getenv('DB_PORT') ?: 3306;
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'student_management_db';

    try {
        $mysqli = new mysqli($db_host, $db_user, $db_pass, '', (int)$db_port);
        if ($mysqli->connect_error) {
            throw new Exception('MySQL connection failed: ' . $mysqli->connect_error);
        }

        // Create database if not exists
        $create_db = "CREATE DATABASE IF NOT EXISTS `" . $mysqli->real_escape_string($db_name) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if (!$mysqli->query($create_db)) {
            throw new Exception('Error creating database: ' . $mysqli->error);
        }

        $mysqli->select_db($db_name);

        // Create table if not exists
        $create_table = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            roll_number VARCHAR(20) UNIQUE NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            phone VARCHAR(20),
            date_of_birth DATE,
            gender ENUM('Male', 'Female', 'Other'),
            address TEXT,
            city VARCHAR(50),
            state VARCHAR(50),
            country VARCHAR(50),
            postal_code VARCHAR(10),
            course VARCHAR(100),
            enrollment_date DATE,
            status ENUM('Active', 'Inactive', 'Graduated') DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        if (!$mysqli->query($create_table)) {
            throw new Exception('Error creating table: ' . $mysqli->error);
        }

        // Create guest lecture feedback table if not exists
        $create_feedback_table = "CREATE TABLE IF NOT EXISTS guest_lecture_feedback (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_roll_number VARCHAR(20) NOT NULL,
            guest_lecture_title VARCHAR(255) NOT NULL,
            lecture_date DATE NOT NULL,
            rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
            comments TEXT,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (student_roll_number) REFERENCES students(roll_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        if (!$mysqli->query($create_feedback_table)) {
            throw new Exception('Error creating feedback table: ' . $mysqli->error);
        }

        $conn = $mysqli;
        define('DB_TYPE', 'mysql');

    } catch (Exception $e) {
        db_error($e->getMessage());
    }

} else {
    // Default to SQLite (local-friendly)
    $db_file = getenv('SQLITE_DB') ?: __DIR__ . '/students.db';

    try {
        $conn = new PDO('sqlite:' . $db_file);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create students table if not exists (SQLite version)
        $create_table = "CREATE TABLE IF NOT EXISTS students (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            roll_number TEXT UNIQUE NOT NULL,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            phone TEXT,
            date_of_birth DATE,
            gender TEXT,
            address TEXT,
            city TEXT,
            state TEXT,
            country TEXT,
            postal_code TEXT,
            course TEXT,
            enrollment_date DATE,
            status TEXT DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        // Create guest lecture feedback table if not exists (SQLite version)
        $create_feedback_table = "CREATE TABLE IF NOT EXISTS guest_lecture_feedback (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_roll_number TEXT NOT NULL,
            guest_lecture_title TEXT NOT NULL,
            lecture_date DATE NOT NULL,
            rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
            comments TEXT,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (student_roll_number) REFERENCES students(roll_number)
        )";

        $conn->exec($create_table);

        define('DB_TYPE', 'sqlite');

    } catch (PDOException $e) {
        db_error($e->getMessage());
    }
}

?>
