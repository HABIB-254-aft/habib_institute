<?php
session_start();

// Protect dashboard
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard – Habib Institute</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<section class="page-header fade-in">
    <h1>Admin Dashboard</h1>
    <p>Welcome, Admin. Choose a section below.</p>
</section>

<section class="services-wrapper fade-in">
    <div class="services-container">

        <div class="service-card">
            <h3>View Applications</h3>
            <p>See all student course applications.</p>
            <a href="view_applications.php" class="hero-btn">Open</a>
        </div>

        <div class="service-card">
            <h3>View Contact Messages</h3>
            <p>Messages received through the contact form.</p>
            <a href="view_messages.php" class="hero-btn">Open</a>
        </div>

        <div class="service-card">
            <h3>View Subscribers</h3>
            <p>Newsletter subscription list.</p>
            <a href="view_subscribers.php" class="hero-btn">Open</a>
        </div>

        <div class="service-card">
            <h3>Logout</h3>
            <p>Securely end your session.</p>
            <a href="logout.php" class="hero-btn">Logout</a>
        </div>

        <div class="service-card">
    <h3>View Student IDs</h3>
    <p>See all generated and saved student IDs.</p>
    <a href="view_ids.php" class="hero-btn">Open</a>
</div>


    </div>
</section>

</body>
</html>
