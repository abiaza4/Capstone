<?php
require_once 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$course_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header('Location: index.php');
    exit;
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll'])) {
    if (!isLoggedIn()) {
        header('Location: login.php?role=member');
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    
    $check_stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $check_stmt->execute([$user_id, $course_id]);
    
    if ($check_stmt->fetch()) {
        $message = 'You are already enrolled in this course';
        $message_type = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'approved')");
        if ($stmt->execute([$user_id, $course_id])) {
            $message = 'Successfully enrolled! You can now access the course videos.';
            $message_type = 'success';
        } else {
            $message = 'Failed to enroll. Please try again.';
            $message_type = 'error';
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM videos WHERE course_id = ? ORDER BY views DESC");
$stmt->execute([$course_id]);
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$enrollment_status = null;
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    $enrollment = $stmt->fetch(PDO::FETCH_ASSOC);
    $enrollment_status = $enrollment ? $enrollment['status'] : null;
}
?>
<?php require_once 'header.php'; ?>

<section class="course-detail-section">
    <div class="container">
        <div class="course-header">
            <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Courses</a>
            
            <div class="course-info">
                <div class="course-icon-large">
                    <img src="<?php echo $course['icon']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                </div>
                <div class="course-details">
                    <h1><?php echo htmlspecialchars($course['name']); ?></h1>
                    <p><?php echo htmlspecialchars($course['description']); ?></p>
                    
                    <div class="course-meta">
                        <span><i class="fas fa-video"></i> <?php echo count($videos); ?> Videos</span>
                        <span><i class="fas fa-users"></i> <?php echo $course['current_access']; ?>/<?php echo $course['access_limit']; ?> Access</span>
                    </div>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?>" style="margin-top: 15px;"><?php echo $message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isLoggedIn() && $enrollment_status): ?>
                        <?php if ($enrollment_status == 'approved'): ?>
                            <div class="enrolled-status approved">
                                <i class="fas fa-check-circle"></i> You are enrolled in this course
                            </div>
                        <?php elseif ($enrollment_status == 'pending'): ?>
                            <div class="enrolled-status pending">
                                <i class="fas fa-clock"></i> Enrollment pending approval
                            </div>
                        <?php endif; ?>
                    <?php elseif (!isLoggedIn()): ?>
                        <a href="login.php?role=member" class="get-started-btn" style="display: inline-block; margin-top: 20px;">
                            <i class="fas fa-user-plus"></i> Login to Enroll
                        </a>
                    <?php elseif (!$enrollment_status): ?>
                        <form method="POST" style="margin-top: 20px;">
                            <button type="submit" name="enroll" class="btn">
                                <i class="fas fa-user-plus"></i> Enroll in this Course
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="videos-section">
            <h2><i class="fas fa-play-circle"></i> Video Tutorials</h2>
            <p class="section-subtitle">Click on any video to start learning</p>
            
            <?php if ($enrollment_status == 'approved' || isAdmin()): ?>
                <div class="videos-grid">
                    <?php foreach ($videos as $video): ?>
                        <a href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($video['youtube_id']); ?>" 
                           target="_blank" 
                           class="video-card">
                            <div class="thumbnail">
                                <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($video['title']); ?>">
                                <span class="play-icon"><i class="fas fa-play"></i></span>
                            </div>
                            <div class="video-info">
                                <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                                <div class="video-stats">
                                    <span><i class="fas fa-eye"></i> <?php echo formatNumber($video['views']); ?></span>
                                    <span><i class="fas fa-thumbs-up"></i> <?php echo formatNumber($video['likes']); ?></span>
                                    <span><i class="fas fa-comment"></i> <?php echo formatNumber($video['comments']); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="enrollment-required">
                    <i class="fas fa-lock"></i>
                    <h3>Enrollment Required</h3>
                    <p>Please enroll in this course to access the video tutorials.</p>
                    <?php if (isLoggedIn() && !$enrollment_status): ?>
                        <form method="POST">
                            <button type="submit" name="enroll" class="btn">
                                <i class="fas fa-user-plus"></i> Enroll Now
                            </button>
                        </form>
                    <?php elseif (!isLoggedIn()): ?>
                        <a href="login.php?role=member" class="btn">
                            <i class="fas fa-sign-in-alt"></i> Login to Enroll
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($videos)): ?>
                <div class="no-data">
                    <i class="fas fa-video-slash"></i>
                    <h3>No videos available yet</h3>
                    <p>Check back soon for tutorials</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.course-detail-section {
    padding-top: 100px;
    min-height: 100vh;
}

.back-link {
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 30px;
    transition: color 0.3s;
}

.back-link:hover {
    color: var(--primary);
}

.course-header {
    margin-bottom: 50px;
}

.course-info {
    display: flex;
    gap: 40px;
    background: var(--dark-card);
    padding: 40px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.course-icon-large {
    flex-shrink: 0;
}

.course-icon-large img {
    width: 120px;
    height: 120px;
    object-fit: contain;
}

.course-details h1 {
    font-size: 2rem;
    margin-bottom: 10px;
}

.course-details > p {
    color: var(--text-muted);
    margin-bottom: 15px;
}

.course-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.course-meta span {
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
}

.enrolled-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 25px;
    margin-top: 15px;
    font-weight: 500;
}

.enrolled-status.approved {
    background: rgba(46, 213, 115, 0.2);
    color: var(--success);
}

.enrolled-status.pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.videos-section h2 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-subtitle {
    color: var(--text-muted);
    margin-bottom: 30px;
}

.enrollment-required {
    text-align: center;
    padding: 40px 30px;
    background: var(--dark-card);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    margin-top: 30px;
}

.enrollment-required i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 15px;
    display: block;
}

.enrollment-required h3 {
    margin-bottom: 10px;
    font-size: 1.3rem;
}

.enrollment-required p {
    color: var(--text-muted);
    margin-bottom: 25px;
    line-height: 1.5;
}

.enrollment-required .btn,
.enrollment-required form {
    margin-top: 15px;
    display: inline-block;
    width: auto;
}

@media (max-width: 768px) {
    .course-info {
        flex-direction: column;
        text-align: center;
    }
    
    .course-icon-large img {
        margin: 0 auto;
    }
    
    .course-meta {
        justify-content: center;
    }
}
</style>

<?php require_once 'footer.php'; ?>
