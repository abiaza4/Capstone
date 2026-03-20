<?php require_once 'header.php'; ?>

<section class="page-content">
    <div class="container">
        <h1><i class="fas fa-envelope"></i> Contact Us</h1>
        
        <div class="contact-content">
            <p class="contact-intro">We'd love to hear from you! Get in touch with us for any questions, feedback, or inquiries.</p>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Our Location</h3>
                    <p>123 Tech Street<br>Silicon Valley, CA 94000</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Us</h3>
                    <p>support@awarelearn.com<br>info@awarelearn.com</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Call Us</h3>
                    <p>+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Business Hours</h3>
                    <p>Mon - Fri: 9AM - 6PM<br>Sat - Sun: Closed</p>
                </div>
            </div>
            
            <div class="contact-form-section">
                <h2>Send us a Message</h2>
                <form class="contact-form" method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user"></i> Your Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Your Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject"><i class="fas fa-heading"></i> Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Enter subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message"><i class="fas fa-comment"></i> Message</label>
                        <textarea id="message" name="message" rows="6" placeholder="Enter your message" required></textarea>
                    </div>
                    <button type="submit" class="btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
            
            <div class="social-section">
                <h2>Follow Us</h2>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
