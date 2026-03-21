<?php
require_once 'db.php';

// This script resets the admin password
// Run once and delete after use

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    
    if (!empty($username) && !empty($newPassword)) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
        $stmt->execute([$hashed, $username]);
        echo "<p style='color:green'>Password updated for: $username</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Admin Password</title>
</head>
<body>
    <h2>Reset Admin Password</h2>
    <form method="POST">
        <label>Username: <input type="text" name="username" value="groupadmin@gmail.com"></label><br><br>
        <label>New Password: <input type="text" name="newPassword" value="groupadmin001"></label><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
