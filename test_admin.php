<?php
require_once 'db.php';

$stmt = $conn->prepare("SELECT id, username, password FROM admins LIMIT 5");
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Admins in database:</h2>";
foreach ($admins as $admin) {
    echo "<p>ID: {$admin['id']}<br>";
    echo "Username: {$admin['username']}<br>";
    echo "Password (raw): {$admin['password']}<br>";
    echo "Is hashed: " . (strpos($admin['password'], '$2y$') === 0 ? 'Yes' : 'No') . "</p>";
    echo "<hr>";
}

echo "<h2>Test login credentials:</h2>";
echo "<p>Username: groupadmin@gmail.com<br>";
echo "Password: groupadmin001</p>";

echo "<h2>Test results:</h2>";
$testStmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
$testStmt->execute(['groupadmin@gmail.com']);
$found = $testStmt->fetch(PDO::FETCH_ASSOC);
if ($found) {
    echo "<p style='color:green'>User found!</p>";
    echo "<p>Password match (plain): " . ($found['password'] === 'groupadmin001' ? 'Yes' : 'No') . "</p>";
} else {
    echo "<p style='color:red'>User NOT found!</p>";
}
?>
