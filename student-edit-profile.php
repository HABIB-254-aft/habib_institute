<?php
session_start();

// Only logged-in students allowed
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentId = $_SESSION['student_id'];
$studentName = $_SESSION['student_name'];
$studentCourse = $_SESSION['student_course'];

$storageDir = __DIR__ . "/storage";
$studentsFile = $storageDir . "/students.txt";

$studentEmail = "";
$studentPhone = "";
$createdAt = "";

// Fetch full student info
if (file_exists($studentsFile)) {
    $lines = file($studentsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($id, $name, $email, $phone, $course, $hash, $created) = explode("|", $line);

        if ($id === $studentId) {
            $studentEmail = $email;
            $studentPhone = $phone;
            $studentCourse = $course;
            $createdAt = $created;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Profile – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Edit Profile</h1>
    <p>Update your personal details and account information.</p>
</section>

<section class="contact-section fade-in" style="max-width:520px; margin:auto;">

    <form action="student-save-profile.php" method="post" class="contact-form">

        <label>Full Name *</label>
        <input type="text" name="name" value="<?= htmlspecialchars($studentName) ?>" required>

        <label>Email (cannot change)</label>
        <input type="email" value="<?= htmlspecialchars($studentEmail) ?>" disabled>

        <input type="hidden" name="email" value="<?= htmlspecialchars($studentEmail) ?>">

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($studentPhone) ?>">

        <label>Course *</label>
        <select name="course" required>
            <option <?= $studentCourse=="English Language Training" ? "selected" : "" ?>>English Language Training</option>
            <option <?= $studentCourse=="Somali Language Classes" ? "selected" : "" ?>>Somali Language Classes</option>
            <option <?= $studentCourse=="Computer & Digital Literacy" ? "selected" : "" ?>>Computer & Digital Literacy</option>
            <option <?= $studentCourse=="Coding Bootcamp" ? "selected" : "" ?>>Coding Bootcamp</option>
            <option <?= $studentCourse=="Graphic Design" ? "selected" : "" ?>>Graphic Design</option>
            <option <?= $studentCourse=="IELTS / TOEFL / KSL Prep" ? "selected" : "" ?>>IELIELTS / TOEFL / KSL Prep</option>
            <option <?= $studentCourse=="CV Writing & Job Coaching" ? "selected" : "" ?>>CV Writing & Job Coaching</option>
        </select>

        <hr>

        <label>Change Password (optional)</label>
        <input type="password" name="new_password" placeholder="Leave blank to keep current password">

        <button type="submit" class="contact-btn">Save Changes</button>

    </form>

    <div style="margin-top:14px; text-align:center;">
        <a href="student-dashboard.php">← Back to Dashboard</a>
    </div>

</section>

</body>
</html>
