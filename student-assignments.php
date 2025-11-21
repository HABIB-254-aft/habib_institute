<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentCourse = $_SESSION['student_course'];

// Map course to its assignment file
$courseFiles = [
    "English Language Training" => "english.txt",
    "Somali Language Classes" => "somali.txt",
    "Computer & Digital Literacy" => "digital.txt",
    "Coding Bootcamp" => "coding.txt",
    "Graphic Design" => "design.txt",
    "IELTS / TOEFL / KSL Prep" => "ielts.txt",
    "CV Writing & Job Coaching" => "cv.txt",
];

$storageDir      = __DIR__ . "/storage/assignments";
$assignmentFile  = $storageDir . "/" . ($courseFiles[$studentCourse] ?? "");
$assignments     = [];

if (file_exists($assignmentFile)) {
    $lines = file($assignmentFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        list($title, $desc, $file, $deadline) = explode("|", $line);
        $assignments[] = [
            "title" => $title,
            "desc" => $desc,
            "file" => $file,
            "deadline" => $deadline
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Assignments – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>My Assignments</h1>
    <p>Your course: <strong><?= htmlspecialchars($studentCourse) ?></strong></p>
</section>

<section class="services-wrapper fade-in">
    <div class="services-container">
        <?php if (empty($assignments)): ?>
            <p>No assignments posted yet.</p>
        <?php else: ?>

            <?php foreach ($assignments as $a): ?>
                <div class="service-card">
                    <h3><?= htmlspecialchars($a['title']) ?></h3>
                    <p><?= htmlspecialchars($a['desc']) ?></p>
                    <p><strong>Deadline:</strong> <?= htmlspecialchars($a['deadline']) ?></p>
                    <a href="<?= htmlspecialchars($a['file']) ?>" class="hero-btn" download>Download Assignment</a>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</section>

</body>
</html>
