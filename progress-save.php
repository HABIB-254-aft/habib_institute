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

require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDB();
    $pdo->beginTransaction();
    
    // Delete existing progress for this student
    $stmt = $pdo->prepare("DELETE FROM student_progress WHERE student_id = ?");
    $stmt->execute([$studentId]);
    
    // Insert new progress
    $stmt = $pdo->prepare("INSERT INTO student_progress (student_id, lesson_key, status, updated_at) VALUES (?, ?, ?, ?)");
    $updatedAt = date("Y-m-d H:i:s");
    
    for ($i = 1; $i <= $lessonCount; $i++) {
        $key = "lesson_{$i}";
        $status = in_array($i, $completed) ? "done" : "pending";
        $stmt->execute([$studentId, $key, $status, $updatedAt]);
    }
    
    $pdo->commit();
} catch (PDOException $e) {
    if (isset($pdo)) $pdo->rollBack();
    error_log("Progress save error: " . $e->getMessage());
}

header("Location: student-lessons.php?status=saved");
exit;
