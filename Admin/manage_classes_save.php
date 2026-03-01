<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$course  = $_POST['course'] ?? '';
$title   = $_POST['title'] ?? '';
$zoom    = $_POST['zoom'] ?? '';
$day     = $_POST['day'] ?? '';
$time    = $_POST['time'] ?? '';
$teacher = $_POST['teacher'] ?? '';

if ($course === '' || $title === '' || $zoom === '' || $day === '' || $time === '' || $teacher === '') {
    header("Location: manage_classes.php?status=error");
    exit;
}

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO online_classes (course, title, zoom_link, day, time, teacher, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$course, $title, $zoom, $day, $time, $teacher, date("Y-m-d H:i:s")]);
} catch (PDOException $e) {
    error_log("Class save error: " . $e->getMessage());
    header("Location: manage_classes.php?status=error");
    exit;
}

header("Location: manage_classes.php?status=saved");
exit;
