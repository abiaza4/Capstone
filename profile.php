<?php
require_once 'header.php';
require_once 'db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($name)) {
        $error = 'Name cannot be empty';
    } else {
        if (!empty($current_password)) {
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!password_verify($current_password, $user['password'])) {
                $error = 'Current password is incorrect';
            } elseif ($new_password !== $confirm_password) {
                $error = 'New passwords do not match';
            } elseif (strlen($new_password) < 6) {
                $error = 'Password must be at least 6 characters';
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
                $stmt->execute([$name, $hashed_password, $user_id]);
                $_SESSION['user_name'] = $name;
                $success = 'Profile updated successfully!';
            }
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
            $stmt->execute([$name, $user_id]);
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully!';
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<section class="page-content">
    <div class="container">
        <h1><i class="fas fa-user-cog"></i> Profile Settings</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="profile-container">
            <div class="profile-header-card">
                <div class="profile-avatar-large">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><i class="fas fa-calendar"></i> Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
            
            <div class="profile-form-section">
                <h2>Update Profile</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        <small>Email cannot be changed</small>
                    </div>
                    
                    <h3>Change Password (Optional)</h3>
                    
                    <div class="form-group">
                        <label for="current_password"><i class="fas fa-lock"></i> Current Password</label>
                        <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
