<?php
session_start();

// SECURITY CHECK – Only admin can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$applications = [];
try {
    $pdo = getDB();
    
    // DELETE ALL HANDLER
    if (isset($_POST['delete_all'])) {
        $pdo->exec("DELETE FROM applications");
    }
    
    $stmt = $pdo->query("SELECT * FROM applications ORDER BY created_at DESC");
    $applications = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Applications view error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="UTF-8">
    <title>View Applications – Admin Panel</title>
    <link rel="icon" type="image/png" href="../IMG/my logo.png">
    <link rel="apple-touch-icon" href="../IMG/my logo.png">
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .admin-container {
            padding: 40px 80px;
        }
        .admin-box {
            background: #f4f7ff;
            padding: 20px;
            border-radius: 10px;
            border-left: 6px solid #0d47a1;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            white-space: pre-wrap;
            font-family: Consolas, monospace;
            font-size: 14px;
            max-height: 600px;
            overflow-y: auto;
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
    <h1 style="color:#0d47a1;">📄 All Applications</h1>
    <p>Below are all course applications submitted by students.</p>
</section>

<section class="admin-container fade-in">
    <!-- DELETE ALL APPLICATIONS -->
    <form method="post" onsubmit="return confirm('Are you sure you want to delete ALL applications?');">
        <input type="hidden" name="delete_all" value="1">
        <button class="back-btn" style="background:red; margin-bottom:15px;">🗑 Delete All Applications</button>
    </form>

    <div class="admin-box">
        <?php 
            if (empty($applications)) {
                echo "No applications available.";
            } else {
                foreach ($applications as $app) {
                    echo "----- APPLICATION -----\n";
                    echo "Name: " . htmlspecialchars($app['name']) . "\n";
                    echo "Email: " . htmlspecialchars($app['email']) . "\n";
                    echo "Phone: " . htmlspecialchars($app['phone']) . "\n";
                    echo "Course: " . htmlspecialchars($app['course']) . "\n";
                    echo "Message: " . htmlspecialchars($app['message']) . "\n";
                    echo "Date: " . htmlspecialchars($app['created_at']) . "\n";
                    echo "-------------------------\n\n";
                }
            }
        ?>
    </div>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</section>

</body>
</html>
