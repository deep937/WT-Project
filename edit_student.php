<?php
/**
 * Edit Student Record
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

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $country = $_POST['country'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    $course = $_POST['course'] ?? '';
    $enrollment_date = $_POST['enrollment_date'] ?? '';
    $status = $_POST['status'] ?? 'Active';

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $message = 'Please fill in all required fields!';
        $message_type = 'error';
    } else {
        try {
            if (DB_TYPE === 'sqlite') {
                $update_query = "UPDATE students SET 
                    first_name = ?,
                    last_name = ?,
                    email = ?,
                    phone = ?,
                    date_of_birth = ?,
                    gender = ?,
                    address = ?,
                    city = ?,
                    state = ?,
                    country = ?,
                    postal_code = ?,
                    course = ?,
                    enrollment_date = ?,
                    status = ?
                    WHERE id = ?";
                
                $stmt = $conn->prepare($update_query);
                $stmt->execute([$first_name, $last_name, $email, $phone, $dob, $gender, $address, 
                    $city, $state, $country, $postal_code, $course, $enrollment_date, $status, $student_id]);
                
                $message = 'Student updated successfully!';
                $message_type = 'success';
                
                // Refresh student data
                $query = "SELECT * FROM students WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$student_id]);
                $student = $stmt->fetch(PDO::FETCH_ASSOC);
                
            } else if (DB_TYPE === 'mysql') {
                $first_name = $conn->real_escape_string($first_name);
                $last_name = $conn->real_escape_string($last_name);
                $email = $conn->real_escape_string($email);
                $phone = $conn->real_escape_string($phone);
                $dob = $conn->real_escape_string($dob);
                $gender = $conn->real_escape_string($gender);
                $address = $conn->real_escape_string($address);
                $city = $conn->real_escape_string($city);
                $state = $conn->real_escape_string($state);
                $country = $conn->real_escape_string($country);
                $postal_code = $conn->real_escape_string($postal_code);
                $course = $conn->real_escape_string($course);
                $enrollment_date = $conn->real_escape_string($enrollment_date);
                $status = $conn->real_escape_string($status);
                
                $update_query = "UPDATE students SET 
                    first_name = '$first_name',
                    last_name = '$last_name',
                    email = '$email',
                    phone = '$phone',
                    date_of_birth = '$dob',
                    gender = '$gender',
                    address = '$address',
                    city = '$city',
                    state = '$state',
                    country = '$country',
                    postal_code = '$postal_code',
                    course = '$course',
                    enrollment_date = '$enrollment_date',
                    status = '$status'
                    WHERE id = $student_id";

                if ($conn->query($update_query)) {
                    $message = 'Student updated successfully!';
                    $message_type = 'success';
                    // Refresh student data
                    $result = $conn->query("SELECT * FROM students WHERE id = $student_id");
                    $student = $result->fetch_assoc();
                } else {
                    $message = 'Error updating student: ' . $conn->error;
                    $message_type = 'error';
                }
            }
        } catch (Exception $e) {
            $message = 'Error updating student: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Management System</title>
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

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <div class="message message-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Form Section -->
        <div class="form-container">
            <h2>Edit Student Record</h2>
            <form method="POST" class="student-form">
                <!-- Row 1 -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="roll_number">Roll Number (Read-only)</label>
                        <input type="text" id="roll_number" name="roll_number" 
                            value="<?php echo htmlspecialchars($student['roll_number']); ?>" 
                            readonly disabled>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" 
                            value="<?php echo htmlspecialchars($student['first_name']); ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" 
                            value="<?php echo htmlspecialchars($student['last_name']); ?>" 
                            required>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                            value="<?php echo htmlspecialchars($student['email']); ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" 
                            value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" 
                            value="<?php echo htmlspecialchars($student['date_of_birth'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo ($student['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="course">Course</label>
                        <input type="text" id="course" name="course" 
                            value="<?php echo htmlspecialchars($student['course'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date</label>
                        <input type="date" id="enrollment_date" name="enrollment_date" 
                            value="<?php echo htmlspecialchars($student['enrollment_date'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Row 4 - Address -->
                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                </div>

                <!-- Row 5 - City, State, Country -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" 
                            value="<?php echo htmlspecialchars($student['city'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" 
                            value="<?php echo htmlspecialchars($student['state'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" 
                            value="<?php echo htmlspecialchars($student['country'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Row 6 - Postal Code & Status -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" 
                            value="<?php echo htmlspecialchars($student['postal_code'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Active" <?php echo ($student['status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo ($student['status'] === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="Graduated" <?php echo ($student['status'] === 'Graduated') ? 'selected' : ''; ?>>Graduated</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="view_student.php?id=<?php echo $student['id']; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php 
if (DB_TYPE === 'mysql') {
    $conn->close();
}
?>
