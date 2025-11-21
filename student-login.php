<?php
session_start();

$storageDir = __DIR__ . "/storage";
$filePath = $storageDir . "/students.txt";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            list($id, $name, $semail, $sphone, $scourse, $hash, $createdAt) = explode("|", $line);

            if (strcasecmp($email, $semail) === 0) {
                if (password_verify($password, $hash)) {
                    $_SESSION['student_logged_in'] = true;
                    $_SESSION['student_id'] = $id;
                    $_SESSION['student_name'] = $name;
                    $_SESSION['student_course'] = $scourse;
                    header("Location: student-dashboard.php");
                    exit;
                } else {
                    $error = "Incorrect password.";
                }
            }
        }

        if ($error === "") $error = "Email not found.";
    } else {
        $error = "No student accounts found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Login – Habib Institute</title>
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
