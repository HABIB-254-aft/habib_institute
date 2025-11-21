<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentId = $_SESSION['student_id'];

$lessonCount = isset($_POST['lesson_count']) ? (int)$_POST['lesson_count'] : 0;
$completed   = $_POST['completed_lessons'] ?? [];

$completed = array_map('intval', $completed);

$progressDir = __DIR__ . "/storage/progress";
if (!is_dir($progressDir)) mkdir($progressDir, 0755, true);

$progressFile = $progressDir . "/{$studentId}.txt";

$lines = [];

for ($i = 1; $i <= $lessonCount; $i++) {
    $key = "lesson_{$i}";
    $status = in_array($i, $completed) ? "done" : "pending";
    $lines[] = $key . "=" . $status;
}

file_put_contents($progressFile, implode("\n", $lines) . "\n");

header("Location: student-lessons.php?status=saved");
exit;
