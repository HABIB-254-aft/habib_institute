<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentId = $_SESSION['student_id'];
$studentName = $_SESSION['student_name'];
$course = $_SESSION['student_course'];

$assignment = $_POST['assignment'] ?? '';

if ($assignment === '') {
    header("Location: upload-homework.php?status=error");
    exit;
}

$storageDir = __DIR__ . "/Storage/homework/" . $studentId;

// Create student folder if missing
if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

// Validate upload
if (!isset($_FILES['homework']) || $_FILES['homework']['error'] !== UPLOAD_ERR_OK) {
    header("Location: upload-homework.php?status=error");
    exit;
}

$file = $_FILES['homework'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

$allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

if (!in_array($ext, $allowed)) {
    header("Location: upload-homework.php?status=error");
    exit;
}

// Create unique filename
$newName = $assignment . "_" . time() . "." . $ext;

$dest = $storageDir . "/" . $newName;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    header("Location: upload-homework.php?status=error");
    exit;
}

// Save submission log to database
require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO homework_submissions (student_id, student_name, course, assignment, filename, file_path, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$studentId, $studentName, $course, $assignment, $newName, $dest, date("Y-m-d H:i:s")]);
} catch (PDOException $e) {
    error_log("Homework submission save error: " . $e->getMessage());
}

header("Location: upload-homework.php?status=success");
exit;
