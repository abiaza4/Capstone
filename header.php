<?php
require_once 'db.php';

$stmt = $conn->query("SELECT * FROM courses ORDER BY name");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAPstone Group - Programming Tutorials</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">CAP<span>stone</span> <span>Group</span></a>
            
            <div class="search-container">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search courses..." class="search-input">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>
            
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li class="dropdown">
                    <a href="#">Courses <span class="dropdown-arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($courses as $c): ?>
                            <li><a href="course.php?id=<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li class="user-menu">
                        <a href="#" class="user-profile">
                            <div class="user-avatar-small">
                                <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                            </div>
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'My Account'); ?></span>
                            <span class="dropdown-arrow">▼</span>
                        </a>
                        <ul class="user-dropdown">
                            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a href="my_courses.php"><i class="fas fa-book"></i> My Courses</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php elseif (isAdmin()): ?>
                    <li><a href="admin/index.php" class="admin-btn"><i class="fas fa-cog"></i> Admin Panel</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="get-started-btn">Get Started</a></li>
                <?php endif; ?>
            </ul>
            
            <button class="mobile-menu-btn"><i class="fas fa-bars"></i></button>
        </div>
    </nav>
    <main>
