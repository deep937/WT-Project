<?php
/**
 * Student Management System - Dashboard
 * PHP 8.x
 */

require_once 'db.php';

// Handle search
$search = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Fetch students based on database type
$result = null;
$students = [];

if (defined('DB_ERROR')) {
    $db_error = DB_ERROR;
} else {
    try {
        if (DB_TYPE === 'sqlite') {
            $query = "SELECT * FROM students WHERE 
                (first_name LIKE ? OR 
                last_name LIKE ? OR 
                email LIKE ? OR 
                roll_number LIKE ?) 
                ORDER BY id DESC";
            
            $stmt = $conn->prepare($query);
            $search_term = '%' . $search . '%';
            $stmt->execute([$search_term, $search_term, $search_term, $search_term]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $row_count = count($students);
            
        } else if (DB_TYPE === 'mysql') {
            $search_safe = $conn->real_escape_string($search);
            $query = "SELECT * FROM students WHERE 
                (first_name LIKE '%$search_safe%' OR 
                last_name LIKE '%$search_safe%' OR 
                email LIKE '%$search_safe%' OR 
                roll_number LIKE '%$search_safe%') 
                ORDER BY id DESC";
            
            $result = $conn->query($query);
            $row_count = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
    } catch (Exception $e) {
        $db_error = "Error fetching students: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📚 Student Record Management System</h1>
            <p>Manage student records efficiently</p>
        </div>

        <!-- Navigation -->
        <nav class="navbar">
            <a href="index.php" class="nav-link active">Dashboard</a>
            <a href="add_student.php" class="nav-link">Add Student</a>
        </nav>

        <!-- Database Status -->
        <?php if (defined('DB_ERROR')): ?>
            <div class="message message-warning">
                <strong>⚠️ Database Notice:</strong> <?php echo htmlspecialchars($db_error); ?>
                <br>The system is using SQLite database. MySQL can be enabled by starting MySQL server.
            </div>
        <?php else: ?>
            <div class="message message-success">
                <strong>✅ Database Connected:</strong> System is ready (mysql)
            </div>
        <?php endif; ?>

        <!-- Search Section -->
        <div class="search-section">
            <form method="POST" class="search-form">
                <input type="text" name="search" placeholder="Search by name, email, or roll number..." 
                    value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                <button type="submit" class="btn btn-search">Search</button>
                <a href="index.php" class="btn btn-reset">Reset</a>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <h3><?php echo count($students); ?></h3>
                <p>Total Students</p>
            </div>
        </div>

        <!-- Students Table -->
        <div class="table-container">
            <h2>Student Records</h2>
            <?php if (count($students) > 0): ?>
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Roll No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['course'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status status-<?php echo strtolower($row['status']); ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="view_student.php?id=<?php echo $row['id']; ?>" 
                                        class="btn btn-small btn-view">View</a>
                                    <a href="edit_student.php?id=<?php echo $row['id']; ?>" 
                                        class="btn btn-small btn-edit">Edit</a>
                                    <a href="delete_student.php?id=<?php echo $row['id']; ?>" 
                                        class="btn btn-small btn-delete" 
                                        onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No students found. <a href="add_student.php">Add a new student</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php 
if (DB_TYPE === 'mysql') {
    $conn->close();
}
?>
