<?php
/**
 * Guest Lecture Feedback Form
 * PHP 8.x
 */

require_once 'db.php';

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    // Get form data
    $student_roll_number = trim($_POST['student_roll_number'] ?? '');
    $guest_lecture_title = trim($_POST['guest_lecture_title'] ?? '');
    $lecture_date = trim($_POST['lecture_date'] ?? '');
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comments = trim($_POST['comments'] ?? '');

    // Validate
    if (empty($student_roll_number) || empty($guest_lecture_title) || empty($lecture_date) || $rating < 1 || $rating > 5) {
        $error_message = 'Please fill all required fields correctly.';
    } else {
        try {
            if (DB_TYPE === 'sqlite') {
                $stmt = $conn->prepare("INSERT INTO guest_lecture_feedback (student_roll_number, guest_lecture_title, lecture_date, rating, comments) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$student_roll_number, $guest_lecture_title, $lecture_date, $rating, $comments]);
            } else if (DB_TYPE === 'mysql') {
                $student_roll_number_safe = $conn->real_escape_string($student_roll_number);
                $guest_lecture_title_safe = $conn->real_escape_string($guest_lecture_title);
                $lecture_date_safe = $conn->real_escape_string($lecture_date);
                $rating_safe = (int)$rating;
                $comments_safe = $conn->real_escape_string($comments);

                $query = "INSERT INTO guest_lecture_feedback (student_roll_number, guest_lecture_title, lecture_date, rating, comments) 
                          VALUES ('$student_roll_number_safe', '$guest_lecture_title_safe', '$lecture_date_safe', $rating_safe, '$comments_safe')";
                $conn->query($query);
            }
            $success_message = 'Thank you for your feedback!';
        } catch (Exception $e) {
            $error_message = 'Error submitting feedback: ' . $e->getMessage();
        }
    }
}

// Fetch students for dropdown (optional, we can also use input)
$students = [];
try {
    if (DB_TYPE === 'sqlite') {
        $stmt = $conn->query("SELECT roll_number, first_name, last_name FROM students ORDER BY first_name, last_name");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else if (DB_TYPE === 'mysql') {
        $result = $conn->query("SELECT roll_number, first_name, last_name FROM students ORDER BY first_name, last_name");
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
} catch (Exception $e) {
    // If we can't fetch students, we'll still show the form as a text input
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
            <h1>📝 Guest Lecture Feedback</h1>
            <p>Share your thoughts on the guest lecture</p>
        </div>

        <!-- Navigation -->
        <nav class="navbar">
            <a href="index.php" class="nav-link">Dashboard</a>
            <a href="add_student.php" class="nav-link">Add Student</a>
            <a href="guest_feedback.php" class="nav-link active">Guest Lecture Feedback</a>
        </nav>

        <?php if ($success_message): ?>
            <div class="message message-success">
                <strong>✅ Success:</strong> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="message message-error">
                <strong>❌ Error:</strong> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Feedback Form -->
        <div class="form-container">
            <h2>Submit Feedback</h2>
            <form method="POST" class="feedback-form">
                <div class="form-group">
                    <label for="student_roll_number">Student Roll Number *</label>
                    <?php if (!empty($students)): ?>
                        <select id="student_roll_number" name="student_roll_number" required class="form-input">
                            <option value="">Select your roll number</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo htmlspecialchars($student['roll_number']); ?>">
                                    <?php echo htmlspecialchars($student['roll_number'] . ' - ' . $student['first_name'] . ' ' . $student['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="text" id="student_roll_number" name="student_roll_number" placeholder="Enter your roll number" required class="form-input">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="guest_lecture_title">Guest Lecture Title *</label>
                    <input type="text" id="guest_lecture_title" name="guest_lecture_title" required class="form-input" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="lecture_date">Lecture Date *</label>
                    <input type="date" id="lecture_date" name="lecture_date" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="rating">Rating *</label>
                    <div class="rating-group">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <label class="rating-label">
                                <input type="radio" name="rating" value="<?php echo $i; ?>" class="rating-input" <?php echo (isset($_POST['rating']) && $_POST['rating'] == $i) ? 'checked' : ''; ?> required>
                                <span class="rating-stars"><?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i); ?></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comments">Comments (Optional)</label>
                    <textarea id="comments" name="comments" rows="4" class="form-input" placeholder="Share your thoughts about the lecture..."></textarea>
                </div>

                <button type="submit" name="submit_feedback" class="btn btn-primary">Submit Feedback</button>
                <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
            </form>
        </div>
    </div>
</body>
</html>