<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$studentCourse = $_SESSION['student_course'];

require_once __DIR__ . '/config/database.php';

$assignments = [];
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT title, description, file_path, deadline FROM assignments WHERE course = ?");
    $stmt->execute([$studentCourse]);
    $results = $stmt->fetchAll();
    
    foreach ($results as $row) {
        $assignments[] = [
            "title" => $row['title'],
            "desc" => $row['description'],
            "file" => $row['file_path'],
            "deadline" => $row['deadline']
        ];
    }
} catch (PDOException $e) {
    error_log("Assignments load error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Assignments – Habib Institute</title>
    <link rel="icon" type="image/png" href="./IMG/my logo.png">
    <link rel="apple-touch-icon" href="./IMG/my logo.png">
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
