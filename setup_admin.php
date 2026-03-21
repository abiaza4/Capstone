<?php
require_once 'db.php';

// Update admin with email and password
$email = 'groupadmin@gmail.com';
$password = password_hash('group001', PASSWORD_DEFAULT);

try {
    // Add email column if not exists
    $conn->exec("ALTER TABLE admins ADD COLUMN IF NOT EXISTS email VARCHAR(255)");
} catch (Exception $e) {
    // Column might already exist
}

// Update the admin
$stmt = $conn->prepare("UPDATE admins SET username = ?, email = ?, password = ? WHERE id = 1");
$stmt->execute([$email, $email, $password]);

echo "Admin updated successfully!<br>";
echo "Email: $email<br>";
echo "Password: group001<br>";
echo "<br><a href='login.php'>Go to Login</a>";
?>
