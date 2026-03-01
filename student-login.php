<?php
session_start();
require_once __DIR__ . '/config/database.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id, name, email, phone, course, password_hash FROM students WHERE email = ?");
        $stmt->execute([$email]);
        $student = $stmt->fetch();
        
        if ($student) {
            if (password_verify($password, $student['password_hash'])) {
                $_SESSION['student_logged_in'] = true;
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['student_course'] = $student['course'];
                header("Location: student-dashboard.php");
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Email not found.";
        }
    } catch (PDOException $e) {
        $error = "Login failed. Please try again.";
        error_log("Login error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Login – Habib Institute</title>
    <link rel="icon" type="image/png" href="./IMG/my logo.png">
    <link rel="apple-touch-icon" href="./IMG/my logo.png">
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Student Login</h1>
    <p>Access your student portal</p>
</section>

<section class="contact-section fade-in" style="max-width:400px; margin:auto;">

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" class="contact-form">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="contact-btn">Login</button>
    </form>

    <div style="margin-top:10px; text-align:center;">
    <a href="forgot-password.php">Forgot your password?</a>
</div>


    <div style="margin-top:14px; text-align:center;">
        No account? <a href="student-register.php">Register</a>
    </div>

</section>

</body>
</html>
