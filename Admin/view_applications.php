<?php
session_start();

// SECURITY CHECK – Only admin can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// File location
$appFile = __DIR__ . "/../storage/applications.txt";

// If file doesn't exist
if (!file_exists($appFile)) {
    $applications = "No applications found.";
} else {
    $applications = file_get_contents($appFile);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<!-- DELETE ALL APPLICATIONS -->
<form method="post" onsubmit="return confirm('Are you sure you want to delete ALL applications?');">
    <input type="hidden" name="delete_all" value="1">
    <button class="back-btn" style="background:red; margin-bottom:15px;">🗑 Delete All Applications</button>
</form>

// DELETE ALL HANDLER
if (isset($_POST['delete_all'])) {
    file_put_contents($appFile, ""); // empty the file
    $applications = "";
}


    <meta charset="UTF-8">
    <title>View Applications – Admin Panel</title>
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
    <div class="admin-box">
        <?php 
            if (trim($applications) === "") {
                echo "No applications available.";
            } else {
                echo htmlspecialchars($applications);
            }
        ?>
    </div>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</section>

</body>
</html>
