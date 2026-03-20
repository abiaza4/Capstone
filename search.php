<?php
require_once 'header.php';
require_once 'db.php';

$search_query = trim($_GET['q'] ?? '');
$courses = [];

if ($search_query) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE name LIKE ? OR description LIKE ? ORDER BY name");
    $search_param = "%$search_query%";
    $stmt->execute([$search_param, $search_param]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="search-section">
    <div class="container">
        <div class="search-header">
            <h1><i class="fas fa-search"></i> Search Courses</h1>
            <p>Find your perfect programming course</p>
        </div>
        
        <div class="search-form-large">
            <form action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search for courses..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>
        
        <?php if ($search_query): ?>
            <div class="search-results">
                <h2>Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
                
                <?php if (count($courses) > 0): ?>
                    <div class="courses-grid">
                        <?php foreach ($courses as $course): ?>
                            <a href="course.php?id=<?php echo $course['id']; ?>" class="course-card">
                                <div class="course-icon">
                                    <img src="<?php echo $course['icon']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                                </div>
                                <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                <p><?php echo htmlspecialchars($course['description']); ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-search"></i>
                        <h3>No courses found</h3>
                        <p>Try different keywords or browse our courses</p>
                        <a href="index.php" class="btn">Browse All Courses</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="search-suggestions">
                <h2>Popular Courses</h2>
                <div class="courses-grid">
                    <?php 
                    $stmt = $conn->query("SELECT * FROM courses ORDER BY name LIMIT 6");
                    $all_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($all_courses as $course): ?>
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
        <?php endif; ?>
    </div>
</section>

<?php require_once 'footer.php'; ?>
