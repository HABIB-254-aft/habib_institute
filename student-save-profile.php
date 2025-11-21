<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentId = $_SESSION['student_id'];
$name      = trim($_POST['name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$course    = trim($_POST['course'] ?? '');
$newPass   = trim($_POST['new_password'] ?? '');

if ($name === '' || $email === '' || $course === '') {
    die("All required fields must be filled.");
}

$storageDir = __DIR__ . "/storage";
$studentsFile = $storageDir . "/students.txt";

// Modify students.txt
if (file_exists($studentsFile)) {
    $lines = file($studentsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $newLines = [];

    foreach ($lines as $line) {
        list($id, $sname, $semail, $sphone, $scourse, $hash, $createdAt) = explode("|", $line);

        if ($id === $studentId) {
            // Update details
            $updatedHash = $hash;
            if ($newPass !== "") {
                $updatedHash = password_hash($newPass, PASSWORD_BCRYPT);
            }

            $line = $id . "|" . $name . "|" . $email . "|" . $phone . "|" . $course . "|" . $updatedHash . "|" . $createdAt;

            // Update session variables
            $_SESSION['student_name'] = $name;
            $_SESSION['student_course'] = $course;
        }

        $newLines[] = $line;
    }

    file_put_contents($studentsFile, implode("\n", $newLines) . "\n");
}

header("Location: student-edit-profile.php?updated=1");
exit;
