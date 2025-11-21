<?php
session_start();

// Admin security
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$storageDir = __DIR__ . "/../storage/classes";
if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Online Classes – Admin</title>
    <link rel="stylesheet" href="../CSS/style.css">

    <style>
        .admin-container {
            padding: 40px 80px;
        }
        .admin-card {
            background: #f4f7ff;
            padding: 20px;
            border-radius: 10px;
            border-left: 6px solid #0d47a1;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<section class="page-header fade-in">
    <h1>Manage Online Classes</h1>
    <p>Add Zoom links and schedules for each course.</p>
</section>

<section class="admin-container fade-in">

    <?php if ($status === 'saved'): ?>
        <p style="color:green;">Class added successfully!</p>
    <?php endif; ?>

    <div class="admin-card">
        <h3>Add New Online Class</h3>

        <form action="manage_classes_save.php" method="post" class="contact-form">

            <label>Course</label>
            <select name="course" required>
                <option>English Language Training</option>
                <option>Somali Language Classes</option>
                <option>Computer & Digital Literacy</option>
                <option>Coding Bootcamp</option>
                <option>Graphic Design</option>
                <option>IELTS / TOEFL / KSL Prep</option>
                <option>CV Writing & Job Coaching</option>
            </select>

            <label>Class Title</label>
            <input type="text" name="title" required>

            <label>Zoom Link</label>
            <input type="text" name="zoom" required>

            <label>Class Day</label>
            <select name="day" required>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
                <option>Sunday</option>
            </select>

            <label>Class Time</label>
            <input type="text" name="time" placeholder="e.g. 10:00 AM – 12:00 PM" required>

            <label>Teacher Name</label>
            <input type="text" name="teacher" required>

            <button type="submit" class="contact-btn">Save Class</button>
        </form>
    </div>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

</section>

</body>
</html>
