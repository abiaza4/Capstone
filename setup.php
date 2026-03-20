<?php
require_once 'db.php';

echo "<h1>Setting up Database...</h1>";

try {
    $conn->exec("ALTER TABLE enrollments ADD COLUMN IF NOT EXISTS status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    echo "<p>✓ Added status column to enrollments</p>";
} catch (Exception $e) {
    echo "<p>Status column might already exist: " . $e->getMessage() . "</p>";
}

try {
    $conn->exec("ALTER TABLE courses ADD COLUMN IF NOT EXISTS access_limit INT DEFAULT 100");
    echo "<p>✓ Added access_limit column to courses</p>";
} catch (Exception $e) {
    echo "<p>Access limit column might already exist: " . $e->getMessage() . "</p>";
}

try {
    $conn->exec("ALTER TABLE courses ADD COLUMN IF NOT EXISTS current_access INT DEFAULT 0");
    echo "<p>✓ Added current_access column to courses</p>";
} catch (Exception $e) {
    echo "<p>Current access column might already exist: " . $e->getMessage() . "</p>";
}

try {
    $conn->exec("CREATE TABLE IF NOT EXISTS course_access_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ended_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )");
    echo "<p>✓ Created course_access_log table</p>";
} catch (Exception $e) {
    echo "<p>Table might already exist: " . $e->getMessage() . "</p>";
}

$stmt = $conn->query("SELECT COUNT(*) as cnt FROM enrollments");
$row = $stmt->fetch();
if ($row['cnt'] > 0) {
    $check_status = $conn->query("DESCRIBE enrollments");
    $has_status = false;
    while ($col = $check_status->fetch()) {
        if ($col['Field'] == 'status') {
            $has_status = true;
            break;
        }
    }
    
    if ($has_status) {
        $conn->exec("UPDATE enrollments SET status = 'approved' WHERE status = 'pending' OR status IS NULL");
        echo "<p>✓ Updated existing enrollments to approved</p>";
    }
}

echo "<h2>Database setup complete!</h2>";
echo "<p><a href='index.php'>Go to Home</a> | <a href='admin/'>Go to Admin Panel</a></p>";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body { font-family: Arial; background: #0a0a0f; color: #fff; padding: 40px; }
        h1 { color: #00d4ff; }
        h2 { color: #2ed573; }
        p { color: #a0a0b0; }
        a { color: #00d4ff; }
    </style>
</head>
<body>
</body>
</html>
