<?php
require_once '../db.php';

if (!isAdmin()) {
    header("Location: ../login.php?role=admin");
    exit;
}

$message = '';
$message_type = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE enrollments SET status = 'approved' WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Enrollment approved successfully!';
            $message_type = 'success';
        }
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE enrollments SET status = 'rejected' WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Enrollment rejected!';
            $message_type = 'success';
        }
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM enrollments WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Enrollment deleted!';
            $message_type = 'success';
        }
    }
}

$filter = $_GET['filter'] ?? 'pending';
$where = $filter != 'all' ? "WHERE e.status = '$filter'" : "";
$enrollments = $conn->query("SELECT e.*, u.name as user_name, u.email as user_email, c.name as course_name, c.icon as course_icon 
    FROM enrollments e 
    JOIN users u ON e.user_id = u.id 
    JOIN courses c ON e.course_id = c.id 
    $where
    ORDER BY e.enrolled_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$stats = [
    'pending' => $conn->query("SELECT COUNT(*) FROM enrollments WHERE status = 'pending'")->fetchColumn(),
    'approved' => $conn->query("SELECT COUNT(*) FROM enrollments WHERE status = 'approved'")->fetchColumn(),
    'rejected' => $conn->query("SELECT COUNT(*) FROM enrollments WHERE status = 'rejected'")->fetchColumn(),
    'all' => $conn->query("SELECT COUNT(*) FROM enrollments")->fetchColumn()
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments - Admin</title>
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
                <li><a href="enrollments.php" class="active"><i class="fas fa-user-graduate"></i> Enrollments 
                    <?php if ($stats['pending'] > 0): ?><span class="badge"><?php echo $stats['pending']; ?></span><?php endif; ?>
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
                    <h1><i class="fas fa-user-graduate"></i> Manage Enrollments</h1>
                    <p>Approve or reject course enrollments</p>
                </div>
            </header>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="filter-tabs">
                <a href="enrollments.php?filter=pending" class="tab <?php echo $filter == 'pending' ? 'active' : ''; ?>">
                    <i class="fas fa-clock"></i> Pending <span class="tab-count"><?php echo $stats['pending']; ?></span>
                </a>
                <a href="enrollments.php?filter=approved" class="tab <?php echo $filter == 'approved' ? 'active' : ''; ?>">
                    <i class="fas fa-check-circle"></i> Approved <span class="tab-count"><?php echo $stats['approved']; ?></span>
                </a>
                <a href="enrollments.php?filter=rejected" class="tab <?php echo $filter == 'rejected' ? 'active' : ''; ?>">
                    <i class="fas fa-times-circle"></i> Rejected <span class="tab-count"><?php echo $stats['rejected']; ?></span>
                </a>
                <a href="enrollments.php?filter=all" class="tab <?php echo $filter == 'all' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i> All <span class="tab-count"><?php echo $stats['all']; ?></span>
                </a>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($enrollments) > 0): ?>
                            <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td><?php echo $enrollment['id']; ?></td>
                                <td>
                                    <div class="user-cell">
                                        <strong><?php echo htmlspecialchars($enrollment['user_name']); ?></strong>
                                        <span><?php echo htmlspecialchars($enrollment['user_email']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="course-cell">
                                        <img src="<?php echo $enrollment['course_icon']; ?>" alt="">
                                        <span><?php echo htmlspecialchars($enrollment['course_name']); ?></span>
                                    </div>
                                </td>
                                <td><span class="status-badge <?php echo $enrollment['status']; ?>"><?php echo ucfirst($enrollment['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($enrollment['enrolled_at'])); ?></td>
                                <td>
                                    <?php if ($enrollment['status'] == 'pending'): ?>
                                    <div class="action-btns">
                                        <a href="enrollments.php?action=approve&id=<?php echo $enrollment['id']; ?>" class="edit-btn" title="Approve">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                        <a href="enrollments.php?action=reject&id=<?php echo $enrollment['id']; ?>" class="delete-btn" title="Reject">
                                            <i class="fas fa-times"></i> Reject
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="action-btns">
                                        <a href="enrollments.php?action=delete&id=<?php echo $enrollment['id']; ?>" class="delete-btn" onclick="return confirm('Delete this enrollment?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #a0a0b0;"></i>
                                    <p style="margin-top: 10px;">No enrollments found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <style>
        .filter-tabs { display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
        .filter-tabs .tab {
            background: #14141f; padding: 12px 20px; border-radius: 10px; color: #a0a0b0;
            display: flex; align-items: center; gap: 8px; transition: all 0.3s;
        }
        .filter-tabs .tab:hover, .filter-tabs .tab.active {
            background: rgba(0, 212, 255, 0.1); color: #00d4ff;
        }
        .tab-count { background: rgba(255, 255, 255, 0.1); padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; }
        .user-cell { display: flex; flex-direction: column; }
        .user-cell span { font-size: 0.8rem; color: #a0a0b0; }
        .course-cell { display: flex; align-items: center; gap: 10px; }
        .course-cell img { width: 30px; height: 30px; object-fit: contain; }
        .status-badge { padding: 5px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: 600; }
        .status-badge.pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
        .status-badge.approved { background: rgba(46, 213, 115, 0.2); color: #2ed573; }
        .status-badge.rejected { background: rgba(255, 71, 87, 0.2); color: #ff4757; }
    </style>
</body>
</html>
