<?php
// Student ID saver
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: student-id.html");
    exit;
}

$name      = trim($_POST['name'] ?? '');
$course    = trim($_POST['course'] ?? '');
$studentId = trim($_POST['student_id'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$issue     = trim($_POST['issue'] ?? '');
$expiry    = trim($_POST['expiry'] ?? '');

$errors = [];

if ($name === '')      $errors[] = "Name is required.";
if ($studentId === '') $errors[] = "Student ID is missing.";

if (!empty($errors)) {
    $errorMsg = implode(" ", $errors);
}

require_once __DIR__ . '/config/database.php';

if (empty($errors)) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("INSERT INTO student_ids (student_id, name, course, phone, issue_date, expiry_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$studentId, $name, $course, $phone, $issue, $expiry, date("Y-m-d H:i:s")]);
        $saved = true;
    } catch (PDOException $e) {
        $saved = false;
        error_log("Student ID save error: " . $e->getMessage());
    }
} else {
    $saved = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student ID Saved – Habib Institute</title>
    <link rel="icon" type="image/png" href="./IMG/my logo.png">
    <link rel="apple-touch-icon" href="./IMG/my logo.png">
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>
        <?php
        if ($saved) {
            echo "✅ Student ID Saved";
        } else {
            echo "❌ Could Not Save ID";
        }
        ?>
    </h1>
    <p>
        <?php
        if ($saved) {
            echo "The student ID has been saved to the system successfully.";
        } else {
            echo "An error occurred: " . ($errorMsg ?? "Unknown error.");
        }
        ?>
    </p>
</section>

<section class="success-section fade-in">
    <div class="success-box">
        <?php if ($saved): ?>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Student ID:</strong> <?= htmlspecialchars($studentId) ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($course) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
            <p><strong>Issue:</strong> <?= htmlspecialchars($issue) ?></p>
            <p><strong>Expiry:</strong> <?= htmlspecialchars($expiry) ?></p>
        <?php endif; ?>

        <div style="margin-top:18px;">
            <a href="student-id.html" class="success-link">Generate Another ID</a> |
            <a href="verify-id.php" class="success-link">Verify an ID</a> |
            <a href="index.html" class="success-link">Back to Home</a>
        </div>
    </div>
</section>

</body>
</html>
