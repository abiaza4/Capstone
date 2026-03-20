<?php
require_once '../db.php';

if (!isAdmin()) {
    header("Location: ../login.php?role=admin");
    exit;
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add' || $action == 'edit') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $access_limit = intval($_POST['access_limit'] ?? 100);
        
        if (empty($name)) {
            $message = 'Course name is required';
            $message_type = 'error';
        } else {
            if ($action == 'add') {
                $stmt = $conn->prepare("INSERT INTO courses (name, description, icon, access_limit) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$name, $description, $icon, $access_limit])) {
                    $message = 'Course added successfully!';
                    $message_type = 'success';
                }
            } else {
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("UPDATE courses SET name = ?, description = ?, icon = ?, access_limit = ? WHERE id = ?");
                if ($stmt->execute([$name, $description, $icon, $access_limit, $id])) {
                    $message = 'Course updated successfully!';
                    $message_type = 'success';
                }
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = 'Course deleted successfully!';
        $message_type = 'success';
    }
}

$courses = $conn->query("SELECT * FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$edit_course = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    $edit_course = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Admin</title>
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
                <li><a href="courses.php" class="active"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="videos.php"><i class="fas fa-video"></i> Videos</a></li>
                <li><a href="enrollments.php"><i class="fas fa-user-graduate"></i> Enrollments</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="view_courses.php"><i class="fas fa-eye"></i> View Courses</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <h1><i class="fas fa-book"></i> Manage Courses</h1>
                    <p>Add, edit, or delete courses</p>
                </div>
                <button class="btn" onclick="document.getElementById('courseModal').style.display='flex'">
                    <i class="fas fa-plus"></i> Add Course
                </button>
            </header>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Access Limit</th>
                            <th>Current Access</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo $course['id']; ?></td>
                            <td><img src="<?php echo $course['icon']; ?>" alt="" class="table-icon"></td>
                            <td><strong><?php echo htmlspecialchars($course['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars(substr($course['description'] ?? '', 0, 50)); ?>...</td>
                            <td><?php echo $course['access_limit']; ?></td>
                            <td><?php echo $course['current_access']; ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="courses.php?edit=<?php echo $course['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="courses.php?delete=<?php echo $course['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this course?')"><i class="fas fa-trash"></i> Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <div id="courseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-book"></i> <?php echo $edit_course ? 'Edit Course' : 'Add New Course'; ?></h2>
                <span class="close" onclick="document.getElementById('courseModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $edit_course ? 'edit' : 'add'; ?>">
                <?php if ($edit_course): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_course['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Course Name *</label>
                    <input type="text" name="name" value="<?php echo $edit_course ? htmlspecialchars($edit_course['name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo $edit_course ? htmlspecialchars($edit_course['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Icon URL</label>
                    <input type="text" name="icon" value="<?php echo $edit_course ? htmlspecialchars($edit_course['icon']) : ''; ?>" placeholder="https://...">
                </div>
                
                <div class="form-group">
                    <label>Access Limit (max concurrent users)</label>
                    <input type="number" name="access_limit" value="<?php echo $edit_course ? $edit_course['access_limit'] : 100; ?>" min="1">
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> <?php echo $edit_course ? 'Update Course' : 'Add Course'; ?>
                </button>
            </form>
        </div>
    </div>
    
    <?php if ($edit_course): ?>
    <script>document.getElementById('courseModal').style.display = 'flex';</script>
    <?php endif; ?>
    
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: #14141f;
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .close {
            font-size: 28px;
            cursor: pointer;
            color: #a0a0b0;
        }
        .close:hover { color: #fff; }
        .table-icon { width: 30px; height: 30px; object-fit: contain; }
    </style>
</body>
</html>
