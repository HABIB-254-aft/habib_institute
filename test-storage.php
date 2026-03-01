<?php
/**
 * Storage System Test Script
 * Tests if the file-based storage system is working correctly
 */

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Storage System Test – Habib Institute</title>
    <link rel='icon' type='image/png' href='./IMG/my logo.png'>
    <link rel='stylesheet' href='./CSS/style.css'>
    <style>
        .test-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: var(--card-bg);
            border-radius: 10px;
            border-left: 6px solid var(--accent-primary);
        }
        .test-item {
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            background: var(--bg-secondary);
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .warning {
            color: #ffc107;
            font-weight: bold;
        }
        .info {
            color: var(--accent-primary);
        }
        pre {
            background: var(--bg-tertiary);
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 12px;
        }
    </style>
</head>
<body>

<section class='page-header fade-in'>
    <h1>Storage System Test</h1>
    <p>Checking if the file-based storage system is working correctly</p>
</section>

<div class='test-container fade-in'>";

$tests = [];
$allPassed = true;

// Test 1: Check if Storage directory exists
echo "<div class='test-item'>";
echo "<h3>Test 1: Storage Directory</h3>";
$storageDir = __DIR__ . "/Storage";
if (is_dir($storageDir)) {
    echo "<p class='success'>✓ Storage directory exists</p>";
    $tests[] = ['name' => 'Storage Directory Exists', 'status' => 'pass'];
} else {
    echo "<p class='error'>✗ Storage directory does not exist</p>";
    echo "<p class='info'>Attempting to create...</p>";
    if (mkdir($storageDir, 0755, true)) {
        echo "<p class='success'>✓ Storage directory created successfully</p>";
        $tests[] = ['name' => 'Storage Directory Created', 'status' => 'pass'];
    } else {
        echo "<p class='error'>✗ Failed to create storage directory</p>";
        $tests[] = ['name' => 'Storage Directory Creation', 'status' => 'fail'];
        $allPassed = false;
    }
}
echo "</div>";

// Test 2: Check if Storage directory is writable
echo "<div class='test-item'>";
echo "<h3>Test 2: Directory Permissions</h3>";
if (is_writable($storageDir)) {
    echo "<p class='success'>✓ Storage directory is writable</p>";
    $tests[] = ['name' => 'Directory Writable', 'status' => 'pass'];
} else {
    echo "<p class='error'>✗ Storage directory is not writable</p>";
    echo "<p class='info'>Current permissions: " . substr(sprintf('%o', fileperms($storageDir)), -4) . "</p>";
    $tests[] = ['name' => 'Directory Writable', 'status' => 'fail'];
    $allPassed = false;
}
echo "</div>";

// Test 3: Test file write operation
echo "<div class='test-item'>";
echo "<h3>Test 3: File Write Operation</h3>";
$testFile = $storageDir . "/test_write.txt";
$testContent = "Test write operation at " . date("Y-m-d H:i:s") . "\n";
if (file_put_contents($testFile, $testContent) !== false) {
    echo "<p class='success'>✓ File write operation successful</p>";
    $tests[] = ['name' => 'File Write', 'status' => 'pass'];
    
    // Clean up test file
    if (unlink($testFile)) {
        echo "<p class='info'>Test file cleaned up</p>";
    }
} else {
    echo "<p class='error'>✗ File write operation failed</p>";
    $tests[] = ['name' => 'File Write', 'status' => 'fail'];
    $allPassed = false;
}
echo "</div>";

// Test 4: Test file read operation
echo "<div class='test-item'>";
echo "<h3>Test 4: File Read Operation</h3>";
$testFile = $storageDir . "/test_read.txt";
$testContent = "Test read operation\n";
file_put_contents($testFile, $testContent);
if (file_exists($testFile) && file_get_contents($testFile) === $testContent) {
    echo "<p class='success'>✓ File read operation successful</p>";
    $tests[] = ['name' => 'File Read', 'status' => 'pass'];
    unlink($testFile);
} else {
    echo "<p class='error'>✗ File read operation failed</p>";
    $tests[] = ['name' => 'File Read', 'status' => 'fail'];
    $allPassed = false;
}
echo "</div>";

// Test 5: Check required storage files
echo "<div class='test-item'>";
echo "<h3>Test 5: Required Storage Files</h3>";
$requiredFiles = [
    'students.txt' => 'Student accounts',
    'applications.txt' => 'Course applications',
    'messages.txt' => 'Contact messages',
    'subscribers.txt' => 'Newsletter subscribers',
    'student_ids.txt' => 'Student IDs',
    'homework_submissions.txt' => 'Homework submissions',
    'password_resets.txt' => 'Password resets'
];

$requiredDirs = [
    'progress' => 'Student progress',
    'homework' => 'Homework files',
    'classes' => 'Online classes',
    'assignments' => 'Assignments'
];

echo "<h4>Files:</h4>";
foreach ($requiredFiles as $file => $description) {
    $filePath = $storageDir . "/" . $file;
    if (file_exists($filePath)) {
        $size = filesize($filePath);
        echo "<p class='success'>✓ $file ($description) - " . number_format($size) . " bytes</p>";
    } else {
        echo "<p class='warning'>⚠ $file ($description) - File doesn't exist (will be created when needed)</p>";
    }
}

echo "<h4>Directories:</h4>";
foreach ($requiredDirs as $dir => $description) {
    $dirPath = $storageDir . "/" . $dir;
    if (is_dir($dirPath)) {
        echo "<p class='success'>✓ $dir/ ($description) - Exists</p>";
    } else {
        echo "<p class='warning'>⚠ $dir/ ($description) - Directory doesn't exist (will be created when needed)</p>";
    }
}
echo "</div>";

// Test 6: Test students.txt format (if exists)
echo "<div class='test-item'>";
echo "<h3>Test 6: Data File Format</h3>";
$studentsFile = $storageDir . "/students.txt";
if (file_exists($studentsFile)) {
    $lines = file($studentsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lineCount = count($lines);
    echo "<p class='info'>Found $lineCount student record(s) in students.txt</p>";
    
    if ($lineCount > 0) {
        $firstLine = $lines[0];
        $parts = explode("|", $firstLine);
        $partCount = count($parts);
        
        if ($partCount >= 7) {
            echo "<p class='success'>✓ File format is correct (7+ fields per record)</p>";
            echo "<p class='info'>Sample record structure: ID|Name|Email|Phone|Course|PasswordHash|CreatedAt</p>";
            $tests[] = ['name' => 'Data Format', 'status' => 'pass'];
        } else {
            echo "<p class='error'>✗ File format issue: Expected 7 fields, found $partCount</p>";
            $tests[] = ['name' => 'Data Format', 'status' => 'fail'];
            $allPassed = false;
        }
    } else {
        echo "<p class='info'>File is empty (no records yet)</p>";
    }
} else {
    echo "<p class='info'>students.txt doesn't exist yet (will be created on first registration)</p>";
}
echo "</div>";

// Test 7: Test directory creation
echo "<div class='test-item'>";
echo "<h3>Test 7: Directory Creation</h3>";
$testDir = $storageDir . "/test_dir_" . time();
if (mkdir($testDir, 0755, true)) {
    echo "<p class='success'>✓ Directory creation successful</p>";
    if (rmdir($testDir)) {
        echo "<p class='info'>Test directory cleaned up</p>";
    }
    $tests[] = ['name' => 'Directory Creation', 'status' => 'pass'];
} else {
    echo "<p class='error'>✗ Directory creation failed</p>";
    $tests[] = ['name' => 'Directory Creation', 'status' => 'fail'];
    $allPassed = false;
}
echo "</div>";

// Test 8: Check PHP file functions
echo "<div class='test-item'>";
echo "<h3>Test 8: PHP File Functions</h3>";
$requiredFunctions = ['file_exists', 'file_get_contents', 'file_put_contents', 'file', 'is_dir', 'is_writable', 'mkdir'];
$missingFunctions = [];
foreach ($requiredFunctions as $func) {
    if (!function_exists($func)) {
        $missingFunctions[] = $func;
    }
}

if (empty($missingFunctions)) {
    echo "<p class='success'>✓ All required PHP file functions are available</p>";
    $tests[] = ['name' => 'PHP Functions', 'status' => 'pass'];
} else {
    echo "<p class='error'>✗ Missing PHP functions: " . implode(", ", $missingFunctions) . "</p>";
    $tests[] = ['name' => 'PHP Functions', 'status' => 'fail'];
    $allPassed = false;
}
echo "</div>";

// Summary
echo "<div class='test-item' style='background: " . ($allPassed ? "#d4edda" : "#f8d7da") . "; border-left: 6px solid " . ($allPassed ? "#28a745" : "#dc3545") . ";'>";
echo "<h2>Test Summary</h2>";
$passed = count(array_filter($tests, function($t) { return $t['status'] === 'pass'; }));
$failed = count(array_filter($tests, function($t) { return $t['status'] === 'fail'; }));
echo "<p><strong>Total Tests: " . count($tests) . "</strong></p>";
echo "<p class='success'>Passed: $passed</p>";
if ($failed > 0) {
    echo "<p class='error'>Failed: $failed</p>";
}

if ($allPassed) {
    echo "<p class='success' style='font-size: 18px; margin-top: 15px;'>✓ All tests passed! Your storage system is working correctly.</p>";
} else {
    echo "<p class='error' style='font-size: 18px; margin-top: 15px;'>✗ Some tests failed. Please check the errors above.</p>";
}
echo "</div>";

// Storage directory listing
echo "<div class='test-item'>";
echo "<h3>Storage Directory Contents</h3>";
if (is_dir($storageDir)) {
    $items = scandir($storageDir);
    $items = array_filter($items, function($item) {
        return $item !== '.' && $item !== '..';
    });
    
    if (count($items) > 0) {
        echo "<pre>";
        foreach ($items as $item) {
            $itemPath = $storageDir . "/" . $item;
            $type = is_dir($itemPath) ? "[DIR]" : "[FILE]";
            $size = is_file($itemPath) ? " - " . number_format(filesize($itemPath)) . " bytes" : "";
            echo "$type $item$size\n";
        }
        echo "</pre>";
    } else {
        echo "<p class='info'>Storage directory is empty</p>";
    }
}
echo "</div>";

echo "</div>";

echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='index.html' class='hero-btn'>Back to Home</a>";
echo "</div>";

echo "</body></html>";
?>

