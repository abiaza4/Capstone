<?php require_once 'header.php'; ?>

<section class="hero">
    <div class="container">
        <h1>Welcome to CAP<span>stone</span> <span>Group</span></h1>
        <p>Discover the best programming tutorials from expert creators</p>
    </div>
</section>

<main class="container">
    <section class="courses-section">
        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> Explore Our Courses</h2>
        
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
    </section>
</main>

<?php require_once 'footer.php'; ?>
