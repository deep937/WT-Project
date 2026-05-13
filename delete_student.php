<?php
/**
 * Delete Student Record
 * PHP 8.x
 */

require_once 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$student_id = intval($_GET['id']);

try {
    if (DB_TYPE === 'sqlite') {
        $delete_query = "DELETE FROM students WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->execute([$student_id]);
        header('Location: index.php?message=Student deleted successfully');
        
    } else if (DB_TYPE === 'mysql') {
        $delete_query = "DELETE FROM students WHERE id = $student_id";
        if ($conn->query($delete_query)) {
            header('Location: index.php?message=Student deleted successfully');
        } else {
            header('Location: index.php?message=Error deleting student');
        }
    }
} catch (Exception $e) {
    header('Location: index.php?message=Error deleting student');
}

if (DB_TYPE === 'mysql') {
    $conn->close();
}
?>
