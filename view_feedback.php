<?php
/**
 * View Guest Lecture Feedback
 * PHP 8.x
 */

require_once 'db.php';

// Fetch all feedback with student details
$feedback = [];
try {
    if (DB_TYPE === 'sqlite') {
        $stmt = $conn->query("SELECT f.id, f.student_roll_number, f.guest_lecture_title, f.lecture_date, f.rating, f.comments, f.submitted_at, 
                              s.first_name, s.last_name 
                              FROM guest_lecture_feedback f 
                              LEFT JOIN students s ON f.student_roll_number = s.roll_number 
                              ORDER BY f.submitted_at DESC");
        $feedback = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else if (DB_TYPE === 'mysql') {
        $result = $conn->query("SELECT f.id, f.student_roll_number, f.guest_lecture_title, f.lecture_date, f.rating, f.comments, f.submitted_at, 
                                s.first_name, s.last_name 
                                FROM guest_lecture_feedback f 
                                LEFT JOIN students s ON f.student_roll_number = s.roll_number 
                                ORDER BY f.submitted_at DESC");
        while ($row = $result->fetch_assoc()) {
            $feedback[] = $row;
        }
    }
} catch (Exception $e) {
    $db_error = "Error fetching feedback: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Lecture Feedback</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📊 Guest Lecture Feedback</h1>
            <p>View all submitted feedback</p>
        </div>

        <!-- Navigation -->
        <nav class="navbar">
            <a href="index.php" class="nav-link">Dashboard</a>
            <a href="add_student.php" class="nav-link">Add Student</a>
            <a href="guest_feedback.php" class="nav-link">Give Feedback</a>
            <a href="view_feedback.php" class="nav-link active">View Feedback</a>
        </nav>

        <?php if (isset($db_error)): ?>
            <div class="message message-error">
                <strong>❌ Error:</strong> <?php echo htmlspecialchars($db_error); ?>
            </div>
        <?php endif; ?>

        <!-- Feedback Table -->
        <div class="table-container">
            <h2>Feedback List</h2>
            <?php if (count($feedback) > 0): ?>
                <table class="feedback-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Lecture Title</th>
                            <th>Date</th>
                            <th>Rating</th>
                            <th>Comments</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feedback as $row): ?>
                            <tr>
                                <td>
                                    <?php 
                                    if (!empty($row['first_name']) && !empty($row['last_name'])) {
                                        echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name'] . ' (' . $row['student_roll_number'] . ')');
                                    } else {
                                        echo htmlspecialchars($row['student_roll_number'] . ' (Student not found)');
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['guest_lecture_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['lecture_date']); ?></td>
                                <td>
                                    <div class="rating-display">
                                        <?php echo str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']); ?>
                                    </div>
                                </td>
                                <td><?php echo nl2br(htmlspecialchars($row['comments'] ?? '')); ?></td>
                                <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No feedback submitted yet. <a href="guest_feedback.php">Submit feedback</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>