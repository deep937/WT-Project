<?php
/**
 * View Student Details
 * PHP 8.x
 */

require_once 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$student_id = intval($_GET['id']);
$student = null;

try {
    if (DB_TYPE === 'sqlite') {
        $query = "SELECT * FROM students WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } else if (DB_TYPE === 'mysql') {
        $query = "SELECT * FROM students WHERE id = $student_id";
        $result = $conn->query($query);
        $student = $result->fetch_assoc();
    }
} catch (Exception $e) {
    header('Location: index.php');
    exit;
}

if (!$student) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student - Student Management System</title>
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
            <a href="index.php" class="nav-link">Dashboard</a>
            <a href="add_student.php" class="nav-link">Add Student</a>
        </nav>

        <!-- View Student Section -->
        <div class="view-container">
            <div class="view-header">
                <h2>Student Details</h2>
                <div class="view-actions">
                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="index.php" class="btn btn-secondary">Back</a>
                </div>
            </div>

            <div class="student-details">
                <div class="detail-section">
                    <h3>Personal Information</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label>Roll Number:</label>
                            <p><?php echo htmlspecialchars($student['roll_number']); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Name:</label>
                            <p><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Email:</label>
                            <p><?php echo htmlspecialchars($student['email']); ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label>Phone:</label>
                            <p><?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Date of Birth:</label>
                            <p><?php echo htmlspecialchars($student['date_of_birth'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Gender:</label>
                            <p><?php echo htmlspecialchars($student['gender'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Address Information</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label>Address:</label>
                            <p><?php echo htmlspecialchars($student['address'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label>City:</label>
                            <p><?php echo htmlspecialchars($student['city'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>State:</label>
                            <p><?php echo htmlspecialchars($student['state'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Country:</label>
                            <p><?php echo htmlspecialchars($student['country'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label>Postal Code:</label>
                            <p><?php echo htmlspecialchars($student['postal_code'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Academic Information</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label>Course:</label>
                            <p><?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Enrollment Date:</label>
                            <p><?php echo htmlspecialchars($student['enrollment_date'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Status:</label>
                            <p><span class="status status-<?php echo strtolower($student['status']); ?>">
                                <?php echo htmlspecialchars($student['status']); ?>
                            </span></p>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>System Information</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label>Created At:</label>
                            <p><?php echo htmlspecialchars($student['created_at']); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Updated At:</label>
                            <p><?php echo htmlspecialchars($student['updated_at']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php 
if (DB_TYPE === 'mysql') {
    $conn->close();
}
?>
