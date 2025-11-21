<?php
session_start();

// SECURITY: Admin only
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$logFile = __DIR__ . "/../storage/homework_submissions.txt";
$submissions = [];

if (file_exists($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Newest first
    $lines = array_reverse($lines);

    foreach ($lines as $line) {
        list($id, $name, $course, $assignment, $filename, $time) = explode("|", $line);

        $submissions[] = [
            "id"        => $id,
            "name"      => $name,
            "course"    => $course,
            "assignment"=> $assignment,
            "filename"  => $filename,
            "time"      => $time
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homework Submissions – Admin Panel</title>
    <link rel="stylesheet" href="../CSS/style.css">

    <style>
        .admin-container {
            padding: 40px 80px;
        }

        .submission-card {
            background: #f4f7ff;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 6px solid #0d47a1;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .submission-card h3 {
            margin: 0 0 8px;
            color: #0d47a1;
        }

        .submission-card p {
            margin: 4px 0;
            font-size: 14px;
        }

        .download-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background: #0d47a1;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .download-btn:hover {
            background: #06306d;
        }

        .back-btn {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 18px;
            background: #444;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<section class="page-header fade-in">
    <h1 style="color:#0d47a1;">📚 Homework Submissions</h1>
    <p>View all student homework uploads.</p>
</section>

<section class="admin-container fade-in">

    <?php if (empty($submissions)): ?>
        <p>No homework submissions yet.</p>
    <?php else: ?>
        <?php foreach ($submissions as $s): ?>
            <div class="submission-card">
                <h3><?= htmlspecialchars($s['assignment']) ?></h3>

                <p><strong>Student:</strong> <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['id']) ?>)</p>
                <p><strong>Course:</strong> <?= htmlspecialchars($s['course']) ?></p>
                <p><strong>Submitted:</strong> <?= htmlspecialchars($s['time']) ?></p>

                <?php 
                $filePath = "../storage/homework/" . $s['id'] . "/" . $s['filename']; 
                ?>

                <a class="download-btn" href="<?= $filePath ?>" download>📥 Download File</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</section>

</body>
</html>
