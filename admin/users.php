<?php
require_once '../db.php';

if (!isAdmin()) {
    header("Location: ../login.php?role=admin");
    exit;
}

$message = '';
$message_type = '';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = 'User deleted successfully!';
        $message_type = 'success';
    }
}

$search = $_GET['search'] ?? '';
$where = $search ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%'" : "";
$users = $conn->query("SELECT * FROM users $where ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as &$user) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM enrollments WHERE user_id = ? AND status = 'approved'");
    $stmt->execute([$user['id']]);
    $user['enrolled_courses'] = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
                    <h1><i class="fas fa-users"></i> Manage Users</h1>
                    <p>View and manage registered users</p>
                </div>
                <form method="GET" class="search-form-admin">
                    <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </header>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Courses Enrolled</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar-table"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                                    <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><span class="course-count"><?php echo $user['enrolled_courses']; ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="user_courses.php?id=<?php echo $user['id']; ?>" class="edit-btn" title="View Courses">
                                        <i class="fas fa-book"></i>
                                    </a>
                                    <a href="users.php?delete=<?php echo $user['id']; ?>" class="delete-btn" onclick="return confirm('Delete this user?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (count($users) == 0): ?>
            <div class="no-data">
                <i class="fas fa-users"></i>
                <h3>No users found</h3>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <style>
        .search-form-admin { display: flex; gap: 10px; }
        .search-form-admin input { background: #14141f; border: 1px solid rgba(255,255,255,0.1); padding: 10px 15px; border-radius: 8px; color: #fff; }
        .search-form-admin button { background: #00d4ff; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; color: #0a0a0f; }
        .user-cell { display: flex; align-items: center; gap: 10px; }
        .user-avatar-table { width: 35px; height: 35px; background: linear-gradient(135deg, #00d4ff, #7b2cbf); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .course-count { background: rgba(0, 212, 255, 0.2); color: #00d4ff; padding: 5px 12px; border-radius: 15px; font-size: 0.85rem; }
    </style>
</body>
</html>
