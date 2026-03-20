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
    
    if ($action == 'add') {
        $course_id = intval($_POST['course_id']);
        $title = trim($_POST['title'] ?? '');
        $video_type = $_POST['video_type'];
        $youtube_id = trim($_POST['youtube_id'] ?? '');
        $thumbnail_url = trim($_POST['thumbnail_url'] ?? '');
        
        $video_path = '';
        if ($video_type == 'local' && isset($_FILES['video_file']) && $_FILES['video_file']['error'] == 0) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_name = time() . '_' . basename($_FILES['video_file']['name']);
            move_uploaded_file($_FILES['video_file']['tmp_name'], $upload_dir . $file_name);
            $video_path = 'uploads/' . $file_name;
        }
        
        if (empty($title) || empty($course_id)) {
            $message = 'Course and title are required';
            $message_type = 'error';
        } else {
            if ($video_type == 'youtube' && !empty($youtube_id)) {
                $thumbnail_url = 'https://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg';
            }
            
            $stmt = $conn->prepare("INSERT INTO videos (course_id, title, youtube_id, video_type, video_path, thumbnail_url) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$course_id, $title, $youtube_id, $video_type, $video_path, $thumbnail_url])) {
                $message = 'Video added successfully!';
                $message_type = 'success';
            }
        }
    }
    
    if ($action == 'edit') {
        $id = intval($_POST['id']);
        $course_id = intval($_POST['course_id']);
        $title = trim($_POST['title'] ?? '');
        $youtube_id = trim($_POST['youtube_id'] ?? '');
        $thumbnail_url = trim($_POST['thumbnail_url'] ?? '');
        
        $stmt = $conn->prepare("UPDATE videos SET course_id = ?, title = ?, youtube_id = ?, thumbnail_url = ? WHERE id = ?");
        if ($stmt->execute([$course_id, $title, $youtube_id, $thumbnail_url, $id])) {
            $message = 'Video updated successfully!';
            $message_type = 'success';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT video_path FROM videos WHERE id = ?");
    $stmt->execute([$id]);
    $video = $stmt->fetch();
    if ($video && $video['video_path'] && file_exists('../' . $video['video_path'])) {
        unlink('../' . $video['video_path']);
    }
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = 'Video deleted successfully!';
        $message_type = 'success';
    }
}

$videos = $conn->query("SELECT v.*, c.name as course_name FROM videos v JOIN courses c ON v.course_id = c.id ORDER BY v.id DESC")->fetchAll(PDO::FETCH_ASSOC);
$courses = $conn->query("SELECT * FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$edit_video = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM videos WHERE id = ?");
    $stmt->execute([$id]);
    $edit_video = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Videos - Admin</title>
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
                <li><a href="videos.php" class="active"><i class="fas fa-video"></i> Videos</a></li>
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
                    <h1><i class="fas fa-video"></i> Manage Videos</h1>
                    <p>Add YouTube links or upload videos</p>
                </div>
                <button class="btn" onclick="document.getElementById('videoModal').style.display='flex'">
                    <i class="fas fa-plus"></i> Add Video
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
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Type</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($videos as $video): ?>
                        <tr>
                            <td><?php echo $video['id']; ?></td>
                            <td><img src="<?php echo $video['thumbnail_url']; ?>" alt="" class="table-thumb"></td>
                            <td><strong><?php echo htmlspecialchars($video['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($video['course_name']); ?></td>
                            <td><span class="type-badge"><?php echo ucfirst($video['video_type']); ?></span></td>
                            <td><?php echo number_format($video['views']); ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="videos.php?edit=<?php echo $video['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="videos.php?delete=<?php echo $video['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <div id="videoModal" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2><i class="fas fa-video"></i> <?php echo $edit_video ? 'Edit Video' : 'Add New Video'; ?></h2>
                <span class="close" onclick="document.getElementById('videoModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $edit_video ? 'edit' : 'add'; ?>">
                <?php if ($edit_video): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_video['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Course *</label>
                    <select name="course_id" required>
                        <option value="">Select a course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>" <?php echo ($edit_video && $edit_video['course_id'] == $course['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($course['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Video Title *</label>
                    <input type="text" name="title" value="<?php echo $edit_video ? htmlspecialchars($edit_video['title']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Video Type</label>
                    <div class="upload-options">
                        <label class="upload-option <?php echo (!$edit_video || $edit_video['video_type'] == 'youtube') ? 'active' : ''; ?>">
                            <input type="radio" name="video_type" value="youtube" <?php echo (!$edit_video || $edit_video['video_type'] == 'youtube') ? 'checked' : ''; ?> onchange="toggleVideoType()">
                            <div class="upload-option-icon"><i class="fab fa-youtube"></i></div>
                            <div>YouTube Link</div>
                        </label>
                        <label class="upload-option <?php echo ($edit_video && $edit_video['video_type'] == 'local') ? 'active' : ''; ?>">
                            <input type="radio" name="video_type" value="local" <?php echo ($edit_video && $edit_video['video_type'] == 'local') ? 'checked' : ''; ?> onchange="toggleVideoType()">
                            <div class="upload-option-icon"><i class="fas fa-upload"></i></div>
                            <div>Upload File</div>
                        </label>
                    </div>
                </div>
                
                <div id="youtubeFields">
                    <div class="form-group">
                        <label>YouTube Video ID</label>
                        <input type="text" name="youtube_id" value="<?php echo $edit_video ? htmlspecialchars($edit_video['youtube_id']) : ''; ?>" placeholder="e.g., rfscVS0vtbw">
                        <small>Enter the video ID from YouTube URL (youtube.com/watch?v=VIDEO_ID)</small>
                    </div>
                </div>
                
                <div id="localFields" style="display: none;">
                    <div class="form-group">
                        <label>Upload Video File</label>
                        <input type="file" name="video_file" accept="video/*">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Thumbnail URL (optional)</label>
                    <input type="text" name="thumbnail_url" value="<?php echo $edit_video ? htmlspecialchars($edit_video['thumbnail_url']) : ''; ?>" placeholder="https://...">
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> <?php echo $edit_video ? 'Update Video' : 'Add Video'; ?>
                </button>
            </form>
        </div>
    </div>
    
    <script>
    function toggleVideoType() {
        const type = document.querySelector('input[name="video_type"]:checked').value;
        document.getElementById('youtubeFields').style.display = type === 'youtube' ? 'block' : 'none';
        document.getElementById('localFields').style.display = type === 'local' ? 'block' : 'none';
    }
    </script>
    
    <?php if ($edit_video): ?>
    <script>document.getElementById('videoModal').style.display = 'flex';</script>
    <?php endif; ?>
    
    <style>
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); align-items: center; justify-content: center; }
        .modal-content { background: #14141f; padding: 30px; border-radius: 15px; width: 100%; max-width: 500px; border: 1px solid rgba(255, 255, 255, 0.1); }
        .modal-large { max-width: 600px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .close { font-size: 28px; cursor: pointer; color: #a0a0b0; }
        .close:hover { color: #fff; }
        .table-thumb { width: 60px; height: 40px; object-fit: cover; border-radius: 5px; }
        .type-badge { background: rgba(0, 212, 255, 0.2); color: #00d4ff; padding: 4px 10px; border-radius: 15px; font-size: 0.8rem; }
    </style>
</body>
</html>
