<?php
session_start();
require_once __DIR__ . '/config/database.php';

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $course   = trim($_POST['course'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === "" || $email === "" || $password === "" || $course === "") {
        $message = "Please fill all required fields.";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
        } else {
            try {
                $pdo = getDB();
                
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $message = "Email already registered.";
                } else {
                    // Register new student
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $studentId = uniqid("STD");
                    $createdAt = date("Y-m-d H:i:s");
                    
                    $stmt = $pdo->prepare("INSERT INTO students (id, name, email, phone, course, password_hash, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$studentId, $name, $email, $phone, $course, $hash, $createdAt]);
                    
                    $success = true;
                    $message = "Registration successful! You can now log in.";
                }
            } catch (PDOException $e) {
                $message = "Registration failed. Please try again.";
                error_log("Registration error: " . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Registration – Habib Institute</title>
    <link rel="icon" type="image/png" href="./IMG/my logo.png">
    <link rel="apple-touch-icon" href="./IMG/my logo.png">
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Student Registration</h1>
    <p>Create your student account to access the portal.</p>
</section>

<section class="contact-section fade-in" style="max-width:480px; margin:auto;">

    <?php if ($message): ?>
        <p style="color:<?= $success ? 'green' : 'red' ?>;"><?= $message ?></p>
    <?php endif; ?>

    <form method="post" class="contact-form">
        <label>Full Name *</label>
        <input type="text" name="name" required>

        <label>Email *</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Choose Course *</label>
        <select name="course" required>
            <option value="">Select Course</option>
            <option>English Language Training</option>
            <option>Somali Language Classes</option>
            <option>Computer & Digital Literacy</option>
            <option>Coding Bootcamp</option>
            <option>Graphic Design</option>
            <option>IELTS / TOEFL / KSL Prep</option>
            <option>CV Writing & Job Coaching</option>
        </select>

        <label>Password *</label>
        <input type="password" name="password" required>

        <button type="submit" class="contact-btn">Register</button>
    </form>

    <div style="margin-top:14px; text-align:center;">
        Already have an account? <a href="student-login.php">Login</a>
    </div>

</section>

</body>
</html>
