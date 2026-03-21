<?php
require_once 'db.php';

echo "<h2>Database Connection: SUCCESS</h2>";

// Check if admins table exists
try {
    $tables = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "<h3>Tables in database:</h3><ul>";
    while ($row = $tables->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . $row['table_name'] . "</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "<p>Error checking tables: " . $e->getMessage() . "</p>";
}

// Check if admins table exists and has data
try {
    $admins = $conn->query("SELECT * FROM admins");
    echo "<h3>Admins:</h3>";
    if ($admins->rowCount() > 0) {
        while ($admin = $admins->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>ID: {$admin['id']}<br>";
            echo "Username: {$admin['username']}<br>";
            echo "Password length: " . strlen($admin['password']) . " chars<br>";
            echo "Password starts with: " . substr($admin['password'], 0, 10) . "...</p>";
        }
    } else {
        echo "<p>No admins found!</p>";
    }
} catch (Exception $e) {
    echo "<p>Error querying admins: " . $e->getMessage() . "</p>";
}

// Try login check
echo "<h3>Login Test:</h3>";
$username = 'groupadmin@gmail.com';
$password = 'groupadmin001';
$stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
if ($admin) {
    echo "<p>Found admin: YES</p>";
    echo "<p>Password matches (plain): " . ($admin['password'] === $password ? 'YES' : 'NO') . "</p>";
} else {
    echo "<p>Found admin: NO - Username not found in database</p>";
}
?>
