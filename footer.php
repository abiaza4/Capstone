    </main>
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="index.php" class="footer-logo">CAP<span>stone</span> <span>Group</span></a>
                    <p>Empowering coders worldwide with free, high-quality programming tutorials.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                        <li><a href="search.php"><i class="fas fa-search"></i> Search</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h3>Courses</h3>
                    <ul>
                        <li><a href="course.php?id=1"><i class="fab fa-python"></i> Python</a></li>
                        <li><a href="course.php?id=2"><i class="fab fa-java"></i> Java</a></li>
                        <li><a href="course.php?id=3"><i class="fab fa-js"></i> JavaScript</a></li>
                        <li><a href="course.php?id=4"><i class="fab fa-php"></i> PHP</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h3>Account</h3>
                    <ul>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a href="my_courses.php"><i class="fas fa-book"></i> My Courses</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 CAPstone Group. All rights reserved. Made with <i class="fas fa-heart"></i> for learners worldwide.</p>
            </div>
        </div>
    </footer>
</body>
</html>
