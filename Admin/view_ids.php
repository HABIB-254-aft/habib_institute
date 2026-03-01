<?php
session_start();

// SECURITY CHECK – Only admin can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$rows = [];
try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT student_id, name, course, phone, issue_date, expiry_date, created_at FROM student_ids ORDER BY created_at DESC");
    $rows = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Student IDs view error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student IDs – Admin Panel</title>
    <link rel="icon" type="image/png" href="../IMG/my logo.png">
    <link rel="apple-touch-icon" href="../IMG/my logo.png">
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .admin-container {
            padding: 40px 80px;
        }
        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        }
        .admin-table th,
        .admin-table td {
            padding: 8px 10px;
            border: 1px solid #e0e0e0;
            font-size: 13px;
        }
        .admin-table thead {
            background: #0d47a1;
            color: #ffffff;
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
    <h1 style="color:#0d47a1;">🎓 Student IDs</h1>
    <p>All IDs generated and saved through the Student ID Generator.</p>
</section>

<section class="admin-container fade-in">
    <?php if (empty($rows)): ?>
        <p>No student IDs saved yet.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Phone</th>
                    <th>Issue</th>
                    <th>Expiry</th>
                    <th>Saved At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['issue_date']) ?></td>
                        <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</section>

</body>
</html>
