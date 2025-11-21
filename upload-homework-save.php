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

$storageDir = __DIR__ . "/storage/homework/" . $studentId;

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

// Save submission log
$log = __DIR__ . "/storage/homework_submissions.txt";
$entry = $studentId . "|" . $studentName . "|" . $course . "|" . $assignment . "|" . $newName . "|" . date("Y-m-d H:i:s") . "\n";

file_put_contents($log, $entry, FILE_APPEND);

header("Location: upload-homework.php?status=success");
exit;
