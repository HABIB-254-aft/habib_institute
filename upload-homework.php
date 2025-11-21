<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentId = $_SESSION['student_id'];
$course = $_SESSION['student_course'];

$status = $_GET['status'] ?? "";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload Homework – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Upload Homework</h1>
    <p>Submit homework for your course: <strong><?= htmlspecialchars($course) ?></strong></p>
</section>

<section class="contact-section fade-in" style="max-width:520px; margin:auto;">

    <?php if ($status === "success"): ?>
        <p style="color:green;">Homework uploaded successfully!</p>
    <?php elseif ($status === "error"): ?>
        <p style="color:red;">Upload failed. Please try again.</p>
    <?php endif; ?>

    <form action="upload-homework-save.php" 
          method="post" 
          enctype="multipart/form-data"
          class="contact-form">

        <label>Select Assignment *</label>
        <select name="assignment" required>
            <option value="">Choose assignment</option>
            <option>Assignment 1</option>
            <option>Assignment 2</option>
            <option>Project Work</option>
            <option>Final Assessment</option>
        </select>

        <label>Upload File (PDF, DOCX, JPG, PNG)</label>
        <input type="file" name="homework" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>

        <button type="submit" class="contact-btn">Upload Homework</button>
    </form>

    <div style="text-align:center; margin-top:10px;">
        <a href="student-dashboard.php">← Back to Dashboard</a>
    </div>

</section>

</body>
</html>
