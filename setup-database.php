<?php
/**
 * Database Setup Script
 * Run this file once to create the database and tables
 * 
 * Usage: php setup-database.php
 * Or visit in browser: http://your-domain/setup-database.php
 */

// Check if running from command line or browser
$isCLI = php_sapi_name() === 'cli';

if (!$isCLI) {
    // Browser mode - show HTML output
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Database Setup – Habib Institute</title>
        <link rel='icon' type='image/png' href='./IMG/my logo.png'>
        <link rel='stylesheet' href='./CSS/style.css'>
        <style>
            .setup-container {
                max-width: 800px;
                margin: 40px auto;
                padding: 30px;
                background: var(--card-bg);
                border-radius: 10px;
                border-left: 6px solid var(--accent-primary);
            }
            .success { color: #28a745; font-weight: bold; }
            .error { color: #dc3545; font-weight: bold; }
            .info { color: var(--accent-primary); }
            pre { background: var(--bg-tertiary); padding: 15px; border-radius: 6px; overflow-x: auto; }
        </style>
    </head>
    <body>
    <section class='page-header fade-in'>
        <h1>Database Setup</h1>
        <p>Setting up database for Habib Institute</p>
    </section>
    <div class='setup-container fade-in'>";
}

// Load database config
require_once __DIR__ . '/config/database.php';

try {
    // First, connect without database to create it
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    if (!$isCLI) echo "<p class='success'>✓ Database created/verified: " . DB_NAME . "</p>";
    else echo "✓ Database created/verified: " . DB_NAME . "\n";
    
    // Now connect to the database
    $pdo = getDB();
    
    // Read and execute schema
    $sqlFile = __DIR__ . '/config/schema.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Remove CREATE DATABASE and USE statements (already done)
        $sql = preg_replace('/CREATE DATABASE.*?;/i', '', $sql);
        $sql = preg_replace('/USE.*?;/i', '', $sql);
        
        // Split by semicolons and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        if (!$isCLI) {
            echo "<p class='success'>✓ All database tables created successfully!</p>";
            echo "<h3>Created Tables:</h3><ul>";
            echo "<li>students</li>";
            echo "<li>applications</li>";
            echo "<li>messages</li>";
            echo "<li>subscribers</li>";
            echo "<li>student_ids</li>";
            echo "<li>homework_submissions</li>";
            echo "<li>password_resets</li>";
            echo "<li>student_progress</li>";
            echo "<li>online_classes</li>";
            echo "<li>assignments</li>";
            echo "</ul>";
            
            echo "<p class='info'><strong>Next Steps:</strong></p>";
            echo "<ol>";
            echo "<li>Update your database credentials in <code>config/database.php</code> if needed</li>";
            echo "<li>Run the migration script to move existing file data to database (if you have existing data)</li>";
            echo "<li>Delete this setup file for security: <code>setup-database.php</code></li>";
            echo "</ol>";
            
            echo "<div style='margin-top: 20px;'>";
            echo "<a href='migrate-data.php' class='hero-btn'>Migrate Existing Data</a> ";
            echo "<a href='index.html' class='hero-btn' style='background: var(--accent-secondary);'>Back to Home</a>";
            echo "</div>";
        } else {
            echo "✓ All database tables created successfully!\n";
        }
    } else {
        throw new Exception("Schema file not found: " . $sqlFile);
    }
    
} catch (PDOException $e) {
    $error = "Database setup failed: " . $e->getMessage();
    if (!$isCLI) {
        echo "<p class='error'>✗ $error</p>";
        echo "<p class='info'>Please check your database credentials in <code>config/database.php</code></p>";
    } else {
        echo "✗ $error\n";
    }
    exit(1);
} catch (Exception $e) {
    $error = $e->getMessage();
    if (!$isCLI) {
        echo "<p class='error'>✗ $error</p>";
    } else {
        echo "✗ $error\n";
    }
    exit(1);
}

if (!$isCLI) {
    echo "</div></body></html>";
}

