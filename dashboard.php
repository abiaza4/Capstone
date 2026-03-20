<?php
require_once 'header.php';
require_once 'db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND status = 'approved'");
$stmt->execute([$user_id]);
$enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_courses = count($enrolled_courses);

$stmt = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $stmt->fetch()['total'];
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header">
            <div class="welcome-section">
                <h1><i class="fas fa-tachometer-alt"></i> Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                <p>Continue your learning journey</p>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-book"></i>
                <h3><?php echo $total_courses; ?></h3>
                <p>Enrolled Courses</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-play-circle"></i>
                <h3>0</h3>
                <p>Videos Watched</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3>0h</h3>
                <p>Learning Time</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3><?php echo $total_users; ?></h3>
                <p>Total Students</p>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="content-section">
                <h2><i class="fas fa-graduation-cap"></i> My Enrolled Courses</h2>
                
                <?php if (count($enrolled_courses) > 0): ?>
                    <div class="courses-grid">
                        <?php foreach ($enrolled_courses as $enrollment):
                            $course_stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
                            $course_stmt->execute([$enrollment['course_id']]);
                            $course = $course_stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <a href="course.php?id=<?php echo $course['id']; ?>" class="course-card">
                                <div class="course-icon">
                                    <img src="<?php echo $course['icon']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                                </div>
                                <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                <p><?php echo htmlspecialchars($course['description']); ?></p>
                                <span class="enrolled-badge"><i class="fas fa-check-circle"></i> Enrolled</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-book-open"></i>
                        <h3>No courses yet</h3>
                        <p>Browse our courses and enroll to start learning</p>
                        <a href="index.php" class="btn">Browse Courses</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="content-section">
                <h2><i class="fas fa-fire"></i> Recommended for You</h2>
                <div class="courses-grid">
                    <?php 
                    $stmt = $conn->query("SELECT * FROM courses ORDER BY RANDOM() LIMIT 3");
                    $recommended = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($recommended as $course): ?>
                        <a href="course.php?id=<?php echo $course['id']; ?>" class="course-card">
                            <div class="course-icon">
                                <img src="<?php echo $course['icon']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            </div>
                            <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
