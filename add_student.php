<?php
/**
 * Add New Student - Form Page
 * PHP 8.x
 */

require_once 'db.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $roll_number = $_POST['roll_number'] ?? '';
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

    // Validate required fields
    if (empty($roll_number) || empty($first_name) || empty($last_name) || empty($email)) {
        $message = 'Please fill in all required fields!';
        $message_type = 'error';
    } else {
        try {
            if (DB_TYPE === 'sqlite') {
                $insert_query = "INSERT INTO students (roll_number, first_name, last_name, email, phone, 
                    date_of_birth, gender, address, city, state, country, postal_code, course, 
                    enrollment_date, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($insert_query);
                $stmt->execute([$roll_number, $first_name, $last_name, $email, $phone, $dob, 
                    $gender, $address, $city, $state, $country, $postal_code, $course, 
                    $enrollment_date, $status]);
                
                $message = 'Student added successfully!';
                $message_type = 'success';
                $_POST = [];
                
            } else if (DB_TYPE === 'mysql') {
                $roll_number = $conn->real_escape_string($roll_number);
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
                
                $insert_query = "INSERT INTO students (roll_number, first_name, last_name, email, phone, 
                    date_of_birth, gender, address, city, state, country, postal_code, course, 
                    enrollment_date, status) 
                    VALUES ('$roll_number', '$first_name', '$last_name', '$email', '$phone', '$dob', 
                    '$gender', '$address', '$city', '$state', '$country', '$postal_code', '$course', 
                    '$enrollment_date', '$status')";

                if ($conn->query($insert_query)) {
                    $message = 'Student added successfully!';
                    $message_type = 'success';
                    $_POST = [];
                } else {
                    if (strpos($conn->error, 'Duplicate entry') !== false) {
                        $message = 'Error: Roll number or email already exists!';
                    } else {
                        $message = 'Error adding student: ' . $conn->error;
                    }
                    $message_type = 'error';
                }
            }
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                $message = 'Error: Roll number or email already exists!';
            } else {
                $message = 'Error adding student: ' . $e->getMessage();
            }
            $message_type = 'error';
        } catch (Exception $e) {
            $message = 'Error adding student: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Management System</title>
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
            <a href="add_student.php" class="nav-link active">Add Student</a>
        </nav>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <div class="message message-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Form Section -->
        <div class="form-container">
            <h2>Add New Student</h2>
            <form method="POST" class="student-form">
                <!-- Row 1 -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="roll_number">Roll Number *</label>
                        <input type="text" id="roll_number" name="roll_number" 
                            value="<?php echo htmlspecialchars($_POST['roll_number'] ?? ''); ?>" 
                            placeholder="e.g., STU001" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" 
                            value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" 
                            placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" 
                            value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" 
                            placeholder="Doe" required>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                            placeholder="john.doe@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" 
                            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" 
                            placeholder="+1 (555) 123-4567">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" 
                            value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo (($_POST['gender'] ?? '') === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="course">Course</label>
                        <input type="text" id="course" name="course" 
                            value="<?php echo htmlspecialchars($_POST['course'] ?? ''); ?>" 
                            placeholder="e.g., Bachelor of Science">
                    </div>
                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date</label>
                        <input type="date" id="enrollment_date" name="enrollment_date" 
                            value="<?php echo htmlspecialchars($_POST['enrollment_date'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Row 4 - Address -->
                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" placeholder="Street address" 
                        rows="3"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>

                <!-- Row 5 - City, State, Country -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" 
                            value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>" 
                            placeholder="City">
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" 
                            value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>" 
                            placeholder="State">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" 
                            value="<?php echo htmlspecialchars($_POST['country'] ?? ''); ?>" 
                            placeholder="Country">
                    </div>
                </div>

                <!-- Row 6 - Postal Code & Status -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" 
                            value="<?php echo htmlspecialchars($_POST['postal_code'] ?? ''); ?>" 
                            placeholder="12345">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Active" <?php echo (($_POST['status'] ?? 'Active') === 'Active') ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo (($_POST['status'] ?? '') === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="Graduated" <?php echo (($_POST['status'] ?? '') === 'Graduated') ? 'selected' : ''; ?>>Graduated</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Add Student</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
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
