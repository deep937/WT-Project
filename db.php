<?php
/**
 * Database Configuration and Connection
 * PHP 8.x with SQLite fallback
 */

// First, try to use SQLite database (more reliable for local development)
$db_file = __DIR__ . '/students.db';

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
    
    $conn->exec($create_table);
    
    // Set as SQLite connection indicator
    define('DB_TYPE', 'sqlite');
    
} catch (PDOException $e) {
    // If SQLite fails, try MySQL
    try {
        $mysql_conn = new mysqli('localhost', 'root', '');
        
        if ($mysql_conn->connect_error) {
            throw new Exception("MySQL connection failed: " . $mysql_conn->connect_error);
        }
        
        $conn = $mysql_conn;
        define('DB_TYPE', 'mysql');
        define('DB_NAME', 'student_management_db');
        
        // Create database if not exists
        $create_db = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        if (!$conn->query($create_db)) {
            throw new Exception("Error creating database: " . $conn->error);
        }
        
        // Select database
        $conn->select_db(DB_NAME);
        
        // Create students table if not exists
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
        
        if (!$conn->query($create_table)) {
            throw new Exception("Error creating table: " . $conn->error);
        }
        
    } catch (Exception $ex) {
        // Store connection error for display
        define('DB_ERROR', $ex->getMessage());
        define('DB_TYPE', 'none');
    }
}

?>
