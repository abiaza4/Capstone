<?php
require_once 'header.php';
require_once 'db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ?");
$stmt->execute([$user_id]);
$all_enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="page-content">
    <div class="container">
        <h1><i class="fas fa-book"></i> My Courses</h1>
        
        <div class="courses-tabs">
            <a href="#approved" class="tab active">Enrolled</a>
            <a href="#pending" class="tab">Pending</a>
        </div>
        
        <div class="tab-content" id="approved">
            <h2>Approved Courses</h2>
            <?php 
            $approved = array_filter($all_enrollments, function($e) { return $e['status'] == 'approved'; });
            if (count($approved) > 0): ?>
                <div class="courses-grid">
                    <?php foreach ($approved as $enrollment):
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
                            <span class="enrolled-badge approved"><i class="fas fa-check-circle"></i> Approved</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-book-open"></i>
                    <h3>No approved courses yet</h3>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="tab-content" id="pending" style="display: none;">
            <h2>Pending Enrollment</h2>
            <?php 
            $pending = array_filter($all_enrollments, function($e) { return $e['status'] == 'pending'; });
            if (count($pending) > 0): ?>
                <div class="courses-grid">
                    <?php foreach ($pending as $enrollment):
                        $course_stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
                        $course_stmt->execute([$enrollment['course_id']]);
                        $course = $course_stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <div class="course-card">
                            <div class="course-icon">
                                <img src="<?php echo $course['icon']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            </div>
                            <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <span class="enrolled-badge pending"><i class="fas fa-clock"></i> Pending Approval</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-clock"></i>
                    <h3>No pending enrollments</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        this.classList.add('active');
        document.querySelector(this.getAttribute('href')).style.display = 'block';
    });
});
</script>

<?php require_once 'footer.php'; ?>
