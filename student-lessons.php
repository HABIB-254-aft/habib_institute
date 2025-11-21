<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentId    = $_SESSION['student_id'];
$studentName  = $_SESSION['student_name'];
$studentCourse= $_SESSION['student_course'];

// Smart placeholder lessons per course
$lessonsMap = [
    "English Language Training"       => 12,
    "Somali Language Classes"         => 10,
    "Computer & Digital Literacy"     => 14,
    "Coding Bootcamp"                 => 20,
    "Graphic Design"                  => 14,
    "IELTS / TOEFL / KSL Prep"        => 15,
    "CV Writing & Job Coaching"       => 6,
];

$totalLessons = $lessonsMap[$studentCourse] ?? 0;

// Load existing progress
$progressDir  = __DIR__ . "/storage/progress";
if (!is_dir($progressDir)) mkdir($progressDir, 0755, true);

$progressFile = $progressDir . "/{$studentId}.txt";
$progressData = [];

if (file_exists($progressFile)) {
    $lines = file($progressFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, "=") !== false) {
            list($key, $val) = explode("=", $line, 2);
            $progressData[$key] = $val;
        }
    }
}
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Lessons – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>My Lessons</h1>
    <p>Track your progress for <strong><?= htmlspecialchars($studentCourse) ?></strong></p>
</section>

<section class="services-wrapper fade-in" style="max-width:700px; margin:auto;">

    <?php if ($status === 'saved'): ?>
        <p style="color:green; text-align:center;">Progress saved successfully.</p>
    <?php endif; ?>

    <?php if ($totalLessons === 0): ?>
        <p>No lessons configured for this course yet.</p>
    <?php else: ?>
        <form action="progress-save.php" method="post">
            <input type="hidden" name="lesson_count" value="<?= $totalLessons ?>">

            <div class="services-container">
                <?php
                $completedCount = 0;
                for ($i = 1; $i <= $totalLessons; $i++):
                    $key = "lesson_{$i}";
                    $isDone = isset($progressData[$key]) && $progressData[$key] === "done";
                    if ($isDone) $completedCount++;
                ?>
                    <div class="service-card">
                        <h3>Lesson <?= $i ?></h3>
                        <p>Lesson topic placeholder for this course.</p>
                        <label style="font-size:14px;">
                            <input type="checkbox" name="completed_lessons[]" value="<?= $i ?>" <?= $isDone ? 'checked' : '' ?>>
                            Mark as completed
                        </label>
                    </div>
                <?php endfor; ?>
            </div>

            <div style="text-align:center; margin-top:20px;">
                <button type="submit" class="contact-btn" style="max-width:260px;">Save Progress</button>
            </div>
        </form>
    <?php endif; ?>

    <div style="text-align:center; margin-top:15px;">
        <a href="student-dashboard.php">← Back to Dashboard</a>
    </div>

</section>

</body>
</html>
