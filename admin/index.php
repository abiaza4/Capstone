<?php
require_once '../db.php';

if (!isAdmin()) {
    header("Location: ../login.php?role=admin");
    exit;
}

$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_courses = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$total_videos = $conn->query("SELECT COUNT(*) FROM videos")->fetchColumn();
$total_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments WHERE status = 'approved'")->fetchColumn();
$pending_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments WHERE status = 'pending'")->fetchColumn();

$recent_users = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$recent_enrollments = $conn->query("SELECT e.*, u.name as user_name, c.name as course_name 
    FROM enrollments e 
    JOIN users u ON e.user_id = u.id 
    JOIN courses c ON e.course_id = c.id 
    ORDER BY e.enrolled_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$total_views = $conn->query("SELECT SUM(views) FROM videos")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CAPstone Group</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <a href="index.php" class="admin-logo">CAP<span>stone</span> <span>Group</span></a>
            <span class="admin-label">Admin Panel</span>
            
            <ul class="admin-menu">
                <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="videos.php"><i class="fas fa-video"></i> Videos</a></li>
                <li><a href="enrollments.php"><i class="fas fa-user-graduate"></i> Enrollments 
                    <?php if ($pending_enrollments > 0): ?><span class="badge"><?php echo $pending_enrollments; ?></span><?php endif; ?>
                </a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="view_courses.php"><i class="fas fa-eye"></i> View Courses</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
                </div>
                <div class="admin-actions">
                    <span class="admin-date"><i class="fas fa-calendar"></i> <?php echo date('F d, Y'); ?></span>
                </div>
            </header>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(0, 212, 255, 0.2);">
                        <i class="fas fa-users" style="color: #00d4ff;"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo formatNumber($total_users); ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(123, 44, 191, 0.2);">
                        <i class="fas fa-book" style="color: #7b2cbf;"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $total_courses; ?></h3>
                        <p>Courses</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(255, 71, 87, 0.2);">
                        <i class="fas fa-video" style="color: #ff4757;"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $total_videos; ?></h3>
                        <p>Videos</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(46, 213, 115, 0.2);">
                        <i class="fas fa-eye" style="color: #2ed573;"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo formatNumber($total_views); ?></h3>
                        <p>Total Views</p>
                    </div>
                </div>
            </div>
            
            <div class="admin-grid">
                <div class="admin-card">
                    <div class="card-header">
                        <h2><i class="fas fa-user-plus"></i> Recent Users</h2>
                        <a href="users.php" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="card-header">
                        <h2><i class="fas fa-user-graduate"></i> Recent Enrollments</h2>
                        <a href="enrollments.php" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_enrollments as $enrollment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($enrollment['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($enrollment['course_name']); ?></td>
                                    <td><span class="status-badge <?php echo $enrollment['status']; ?>"><?php echo ucfirst($enrollment['status']); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="quick-actions">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                <div class="actions-grid">
                    <a href="courses.php?action=add" class="action-card">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Course</span>
                    </a>
                    <a href="videos.php?action=add" class="action-card">
                        <i class="fas fa-upload"></i>
                        <span>Add Video</span>
                    </a>
                    <a href="enrollments.php" class="action-card">
                        <i class="fas fa-check-circle"></i>
                        <span>Pending Enrollments</span>
                    </a>
                    <a href="view_courses.php" class="action-card">
                        <i class="fas fa-play"></i>
                        <span>View Courses</span>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
