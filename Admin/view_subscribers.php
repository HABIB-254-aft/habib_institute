<?php
session_start();

// SECURITY CHECK – Only admin can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] === false) {
    header("Location: login.php");
    exit;
}

// File location
$subFile = __DIR__ . "/../storage/subscribers.txt";

// Load data
if (!file_exists($subFile)) {
    $subscribers = "No subscribers found.";
} else {
    $subscribers = file_get_contents($subFile);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <form method="post" onsubmit="return confirm('Delete ALL subscribers?');">
    <input type="hidden" name="delete_all" value="1">
    <button class="back-btn" style="background:red; margin-bottom:15px;">🗑 Delete All Subscribers</button>
</form>

if (isset($_POST['delete_all'])) {
    file_put_contents($subFile, "");
    $subscribers = "";
}

    <meta charset="UTF-8">
    <title>View Subscribers – Admin Panel</title>
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
    <h1 style="color:#0d47a1;">📧 Newsletter Subscribers</h1>
    <p>Below is the list of emails subscribed to your newsletter.</p>
</section>

<section class="admin-container fade-in">
    <div class="admin-box">
        <?php 
            if (trim($subscribers) === "") {
                echo "No subscribers available.";
            } else {
                echo htmlspecialchars($subscribers);
            }
        ?>
    </div>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</section>

</body>
</html>
