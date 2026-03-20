<?php
require_once '../db.php';

if (!isAdmin()) {
    header("Location: ../login.php?role=admin");
    exit;
}

$course_id = intval($_GET['id'] ?? 0);

if ($course_id) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmt = $conn->prepare("SELECT * FROM videos WHERE course_id = ? ORDER BY views DESC");
    $stmt->execute([$course_id]);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $courses = $conn->query("SELECT * FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course ? htmlspecialchars($course['name']) : 'View Courses'; ?> - Admin</title>
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
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="view_courses.php" class="active"><i class="fas fa-eye"></i> View Courses</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <h1><i class="fas fa-eye"></i> <?php echo $course ? htmlspecialchars($course['name']) : 'View Courses'; ?></h1>
                    <p><?php echo $course ? htmlspecialchars($course['description']) : 'Browse all available courses'; ?></p>
                </div>
                <?php if ($course): ?>
                    <a href="view_courses.php" class="btn"><i class="fas fa-arrow-left"></i> All Courses</a>
                <?php endif; ?>
            </header>
            
            <?php if ($course): ?>
                <div class="course-stats">
                    <div class="stat-item">
                        <i class="fas fa-video"></i>
                        <span class="stat-value"><?php echo count($videos); ?></span>
                        <span class="stat-label">Videos</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span class="stat-value"><?php echo formatNumber(array_sum(array_column($videos, 'views'))); ?></span>
                        <span class="stat-label">Total Views</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <span class="stat-value"><?php echo $course['current_access']; ?>/<?php echo $course['access_limit']; ?></span>
                        <span class="stat-label">Access Limit</span>
                    </div>
                </div>
                
                <div class="videos-grid">
                    <?php foreach ($videos as $video): ?>
                    <div class="video-card">
                        <div class="thumbnail">
                            <img src="<?php echo $video['thumbnail_url']; ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                            <?php if ($video['video_type'] == 'youtube'): ?>
                                <a href="https://www.youtube.com/watch?v=<?php echo $video['youtube_id']; ?>" target="_blank" class="play-icon">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            <?php else: ?>
                                <a href="../<?php echo $video['video_path']; ?>" target="_blank" class="play-icon">
                                    <i class="fas fa-play"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="video-info">
                            <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                            <div class="video-stats">
                                <span><i class="fas fa-eye"></i> <?php echo formatNumber($video['views']); ?></span>
                                <span><i class="fas fa-thumbs-up"></i> <?php echo formatNumber($video['likes']); ?></span>
                                <span><i class="fas fa-comment"></i> <?php echo formatNumber($video['comments']); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($videos) == 0): ?>
                <div class="no-data">
                    <i class="fas fa-video-slash"></i>
                    <h3>No videos yet</h3>
                    <p>Add videos to this course from the Videos management page</p>
                </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="courses-grid">
                    <?php foreach ($courses as $course): 
                        $video_count = $conn->query("SELECT COUNT(*) FROM videos WHERE course_id = " . $course['id'])->fetchColumn();
                        $total_views = $conn->query("SELECT SUM(views) FROM videos WHERE course_id = " . $course['id'])->fetchColumn();
                    ?>
                        <a href="view_courses.php?id=<?php echo $course['id']; ?>" class="course-card">
                            <div class="course-icon">
                                <img src="<?php echo $course['icon']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            </div>
                            <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <div class="course-meta">
                                <span><i class="fas fa-video"></i> <?php echo $video_count; ?> videos</span>
                                <span><i class="fas fa-eye"></i> <?php echo formatNumber($total_views); ?> views</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <style>
        .course-stats { display: flex; gap: 30px; margin-bottom: 30px; flex-wrap: wrap; }
        .stat-item { background: #14141f; padding: 20px 30px; border-radius: 15px; display: flex; flex-direction: column; align-items: center; gap: 5px; }
        .stat-item i { font-size: 1.5rem; color: #00d4ff; }
        .stat-value { font-size: 1.8rem; font-weight: bold; }
        .stat-label { color: #a0a0b0; font-size: 0.9rem; }
        .course-meta { display: flex; gap: 15px; margin-top: 15px; justify-content: center; }
        .course-meta span { color: #a0a0b0; font-size: 0.85rem; }
        .thumbnail .play-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 3rem; color: #fff; opacity: 0.9; transition: all 0.3s; }
        .thumbnail:hover .play-icon { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
    </style>
</body>
</html>
