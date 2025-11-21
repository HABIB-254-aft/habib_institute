<?php
session_start();

// SECURITY CHECK – Only admin can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$filePath = __DIR__ . "/../storage/student_ids.txt";
$rows = [];

if (file_exists($filePath)) {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) >= 7) {
            $rows[] = $parts; // [ID, Name, Course, Phone, Issue, Expiry, SavedAt]
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student IDs – Admin Panel</title>
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
                <?php foreach ($rows as $columns): ?>
                    <tr>
                        <td><?= htmlspecialchars($columns[0]) ?></td>
                        <td><?= htmlspecialchars($columns[1]) ?></td>
                        <td><?= htmlspecialchars($columns[2]) ?></td>
                        <td><?= htmlspecialchars($columns[3]) ?></td>
                        <td><?= htmlspecialchars($columns[4]) ?></td>
                        <td><?= htmlspecialchars($columns[5]) ?></td>
                        <td><?= htmlspecialchars($columns[6]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</section>

</body>
</html>
