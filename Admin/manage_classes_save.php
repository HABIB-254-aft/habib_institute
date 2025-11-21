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

$folder = __DIR__ . "/../storage/classes";
$file   = $folder . "/" . strtolower(str_replace(" ", "_", $course)) . ".txt";

$entry = $title . "|" . $zoom . "|" . $day . "|" . $time . "|" . $teacher . "\n";

file_put_contents($file, $entry, FILE_APPEND);

header("Location: manage_classes.php?status=saved");
exit;
