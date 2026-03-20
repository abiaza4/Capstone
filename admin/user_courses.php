<?php
require_once '../db.php';

if (!isAdmin()) {
    header("Location: ../login.php?role=admin");
    exit;
}

$user_id = intval($_GET['id'] ?? 0);
if (!$user_id) {
    header("Location: users.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: users.php");
    exit;
}

$enrollments = $conn->query("SELECT e.*, c.name as course_name, c.icon as course_icon, c.description
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.user_id = $user_id
    ORDER BY e.enrolled_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Courses - Admin</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <a href="index.php" class="admin-logo">CAP<span>stone</span> <span>Group</span></a>
            <span class="admin-label">Admin Panel</span>
            
            <ul class="admin-menu">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="videos.php"><i class="fas fa-video"></i> Videos</a></li>
                <li><a href="enrollments.php"><i class="fas fa-user-graduate"></i> Enrollments</a></li>
                <li><a href="users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="view_courses.php"><i class="fas fa-eye"></i> View Courses</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <h1><i class="fas fa-user"></i> <?php echo htmlspecialchars($user['name']); ?>'s Courses</h1>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <a href="users.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Users</a>
            </header>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Enrolled Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($enrollments) > 0): ?>
                            <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td>
                                    <div class="course-cell">
                                        <img src="<?php echo $enrollment['course_icon']; ?>" alt="">
                                        <strong><?php echo htmlspecialchars($enrollment['course_name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars(substr($enrollment['description'], 0, 60)); ?>...</td>
                                <td><span class="status-badge <?php echo $enrollment['status']; ?>"><?php echo ucfirst($enrollment['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($enrollment['enrolled_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-book-open" style="font-size: 3rem; color: #a0a0b0;"></i>
                                    <p style="margin-top: 10px;">No enrollments yet</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <style>
        .course-cell { display: flex; align-items: center; gap: 10px; }
        .course-cell img { width: 30px; height: 30px; object-fit: contain; }
        .status-badge { padding: 5px 12px; border-radius: 15px; font-size: 0.8rem; }
        .status-badge.pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
        .status-badge.approved { background: rgba(46, 213, 115, 0.2); color: #2ed573; }
        .status-badge.rejected { background: rgba(255, 71, 87, 0.2); color: #ff4757; }
    </style>
</body>
</html>
