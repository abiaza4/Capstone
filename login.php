<?php
require_once 'db.php';

$error = '';
$success = '';

// Check if already logged in
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

if (isAdmin()) {
    header("Location: admin/index.php");
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        // Check users table first
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            if ($remember) {
                setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), '/');
            } else {
                setcookie('user_email', '', time() - 3600, '/');
            }
            
            header("Location: dashboard.php");
            exit;
        }
        
        // Check admins table
        $stmt = $conn->prepare("SELECT id, username, email, password FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            if ($remember) {
                setcookie('admin_email', $email, time() + (30 * 24 * 60 * 60), '/');
            } else {
                setcookie('admin_email', '', time() - 3600, '/');
            }
            
            header("Location: admin/index.php");
            exit;
        }
        
        // No match found
        $error = 'Invalid email or password';
    }
}

// Get remembered email
$remembered_email = htmlspecialchars($_COOKIE['user_email'] ?? $_COOKIE['admin_email'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CAPstone Group</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <a href="index.php" class="auth-logo">CAP<span>stone</span> <span>Group</span></a>
            <h2>Welcome Back</h2>
            <p class="auth-subtitle">Login to access your account</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="email" name="email" 
                           placeholder="Enter your email" 
                           value="<?php echo $remembered_email; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Enter your password" 
                           required>
                </div>
                
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" 
                           <?php echo !empty($remembered_email) ? 'checked' : ''; ?>>
                    <label for="remember">Remember Me</label>
                </div>
                
                <button type="submit" class="btn btn-full">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
</body>
</html>

<style>
.remember-me {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 15px 0;
    color: var(--text-muted);
}

.remember-me input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary);
}

.remember-me label {
    cursor: pointer;
    user-select: none;
}
</style>
