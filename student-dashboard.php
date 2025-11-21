<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$name   = $_SESSION['student_name'];
$course = $_SESSION['student_course'];
$id     = $_SESSION['student_id'];

// Progress calculation
$lessonsMap = [
    "English Language Training"       => 12,
    "Somali Language Classes"         => 10,
    "Computer & Digital Literacy"     => 14,
    "Coding Bootcamp"                 => 20,
    "Graphic Design"                  => 14,
    "IELTS / TOEFL / KSL Prep"        => 15,
    "CV Writing & Job Coaching"       => 6,
];

$totalLessons = $lessonsMap[$course] ?? 0;
$completedLessons = 0;

// Load progress file
$progressDir = __DIR__ . "/storage/progress";
$progressFile = $progressDir . "/{$id}.txt";

if ($totalLessons > 0 && file_exists($progressFile)) {
    $lines = file($progressFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, "=") !== false) {
            list($key, $val) = explode("=", $line, 2);
            if (strpos($key, "lesson_") === 0 && trim($val) === "done") {
                $completedLessons++;
            }
        }
    }
}

$progressPercent = ($totalLessons > 0)
    ? round(($completedLessons / $totalLessons) * 100)
    : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <style>
        .progress-bar-wrapper {
            width: 100%;
            background: #e3e8ff;
            border-radius: 999px;
            overflow: hidden;
            height: 14px;
            margin-top: 6px;
        }
        .progress-bar-fill {
            height: 100%;
            background: #0d47a1;
            width: 0;
            transition: width 0.4s ease;
        }
    </style>
</head>
<body>

<section class="page-header fade-in">
    <h1>Welcome, <?= htmlspecialchars($name) ?></h1>
    <p>Your student dashboard for <strong><?= htmlspecialchars($course) ?></strong></p>
</section>

<section class="services-wrapper fade-in">
    <div class="services-container">

        <!-- Progress Card -->
        <div class="service-card">
            <h3>Course Progress</h3>
            <?php if ($totalLessons === 0): ?>
                <p>Progress tracking not configured for this course yet.</p>
            <?php else: ?>
                <p>
                    Lessons completed:
                    <strong><?= $completedLessons ?> / <?= $totalLessons ?></strong>
                    (<?= $progressPercent ?>%)
                </p>
                <div class="progress-bar-wrapper">
                    <div class="progress-bar-fill" style="width: <?= $progressPercent ?>%;"></div>
                </div>
                <p style="margin-top:8px; font-size:14px;">
                    Keep learning to reach 100% completion!
                </p>
                <a href="student-lessons.php" class="hero-btn" style="margin-top:8px; display:inline-block;">
                    View / Update Lessons
                </a>
            <?php endif; ?>
        </div>

        <!-- Profile Card -->
        <div class="service-card">
            <h3>My Profile</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($course) ?></p>
            <p><strong>Student ID:</strong> <?= htmlspecialchars($id) ?></p>
            <a href="student-edit-profile.php" class="hero-btn">Edit Profile</a>
        </div>

        <!-- My Courses -->
        <div class="service-card">
            <h3>My Assignments</h3>
            <p>Download and work on your course assignments.</p>
            <a href="student-assignments.php" class="hero-btn">View Assignments</a>
        </div>

        <!-- Student ID -->
        <div class="service-card">
            <h3>Student ID</h3>
            <p>Generate or verify your official student ID card.</p>
            <a href="student-id.html" class="hero-btn">Generate ID</a>
            <a href="verify-id.php" class="hero-btn" style="margin-top:6px;">Verify ID</a>
        </div>

        <!-- Online Classes -->
        <div class="service-card">
            <h3>Online Classes</h3>
            <p>Join your scheduled live Zoom lessons.</p>
            <a href="student-classes.php" class="hero-btn">View Online Classes</a>
        </div>

        <!-- Upload Homework -->
        <div class="service-card">
            <h3>Upload Homework</h3>
            <p>Submit assignments and projects for marking.</p>
            <a href="upload-homework.php" class="hero-btn">Upload Now</a>
        </div>

        <!-- Downloads -->
        <div class="service-card">
            <h3>Downloads</h3>
            <p>Access key documents and resources.</p>
            <a href="prospectus.pdf" class="hero-btn" download>Download Prospectus</a>
        </div>

        <!-- Logout -->
        <div class="service-card">
            <h3>Logout</h3>
            <p>End your session securely.</p>
            <a href="student-logout.php" class="hero-btn">Logout</a>
        </div>

    </div>
</section>

</body>
</html>
