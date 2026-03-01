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

require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDB();
    
    // Update student details
    if ($newPass !== "") {
        $hash = password_hash($newPass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ?, course = ?, password_hash = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $course, $hash, $studentId]);
    } else {
        $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ?, course = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $course, $studentId]);
    }
    
    // Update session variables
    $_SESSION['student_name'] = $name;
    $_SESSION['student_course'] = $course;
} catch (PDOException $e) {
    die("Error updating profile: " . $e->getMessage());
}

header("Location: student-edit-profile.php?updated=1");
exit;
