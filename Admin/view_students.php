<?php
session_start();

// SECURITY CHECK – Only admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$students = [];
try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, name, email, phone, course, created_at FROM students ORDER BY created_at DESC");
    $students = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Students view error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students – Admin Panel</title>
    <link rel="icon" type="image/png" href="../IMG/my logo.png">
    <link rel="apple-touch-icon" href="../IMG/my logo.png">
    <link rel="stylesheet" href="../CSS/style.css">

    <style>
        .admin-container {
            padding: 40px 80px;
        }

        .student-card {
            background: #f4f7ff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            border-left: 6px solid #0d47a1;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .student-card h3 {
            color: #0d47a1;
            margin: 0 0 8px;
        }

        .student-card p {
            margin: 4px 0;
            font-size: 14px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 18px;
            background: #0d47a1;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .back-btn:hover {
            background: #06306d;
        }
    </style>
</head>

<body>

<section class="page-header fade-in">
    <h1 style="color:#0d47a1;">👨‍🎓 Registered Students</h1>
    <p>All students registered through the student portal.</p>
</section>

<section class="admin-container fade-in">

    <?php if (empty($students)): ?>
        <p>No registered students yet.</p>
    <?php else: ?>
        <?php foreach ($students as $s): ?>
            <div class="student-card">
                <h3><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['id']) ?>)</h3>

                <p><strong>Email:</strong> <?= htmlspecialchars($s['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($s['phone']) ?></p>
                <p><strong>Course:</strong> <?= htmlspecialchars($s['course']) ?></p>
                <p><strong>Registered:</strong> <?= htmlspecialchars($s['created']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</section>

</body>
</html>
