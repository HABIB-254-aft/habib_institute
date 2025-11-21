<?php
session_start();

// Ensure storage folder exists
$storageDir = __DIR__ . "/storage";
if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

$filePath = $storageDir . "/students.txt";
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
            // Check if email already exists
            if (file_exists($filePath)) {
                $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    list($id, $sname, $semail) = explode("|", $line);
                    if (strcasecmp($semail, $email) === 0) {
                        $message = "Email already registered.";
                        break;
                    }
                }
            }

            if ($message === "") {
                $hash = password_hash($password, PASSWORD_BCRYPT);

                $studentId = uniqid("STD");

                $data = $studentId . "|" . $name . "|" . $email . "|" . $phone . "|" . $course . "|" . $hash . "|" . date("Y-m-d H:i:s") . "\n";

                file_put_contents($filePath, $data, FILE_APPEND);

                $success = true;
                $message = "Registration successful! You can now log in.";
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
