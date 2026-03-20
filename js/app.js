const users = [];

function isLoggedIn() {
    return localStorage.getItem('currentUser') !== null;
}

function getCurrentUser() {
    const userData = localStorage.getItem('currentUser');
    return userData ? JSON.parse(userData) : null;
}

function login(email, password) {
    const storedUsers = JSON.parse(localStorage.getItem('users') || '[]');
    const user = storedUsers.find(u => u.email === email && u.password === password);
    
    if (user) {
        localStorage.setItem('currentUser', JSON.stringify(user));
        return { success: true, user: user };
    }
    
    const demoUser = storedUsers.find(u => u.email === 'demo@example.com');
    if (demoUser && demoUser.password === 'demo123') {
        localStorage.setItem('currentUser', JSON.stringify(demoUser));
        return { success: true, user: demoUser };
    }
    
    return { success: false, message: 'Invalid email or password' };
}

function register(name, email, password) {
    const storedUsers = JSON.parse(localStorage.getItem('users') || '[]');
    
    if (storedUsers.some(u => u.email === email)) {
        return { success: false, message: 'Email already registered' };
    }
    
    const newUser = {
        id: Date.now(),
        name: name,
        email: email,
        password: password,
        enrolledCourses: [],
        joinedAt: new Date().toISOString()
    };
    
    storedUsers.push(newUser);
    localStorage.setItem('users', JSON.stringify(storedUsers));
    localStorage.setItem('currentUser', JSON.stringify(newUser));
    
    return { success: true, user: newUser };
}

function logout() {
    localStorage.removeItem('currentUser');
}

function enrollInCourse(courseId) {
    const user = getCurrentUser();
    if (!user) return false;
    
    const storedUsers = JSON.parse(localStorage.getItem('users') || '[]');
    const userIndex = storedUsers.findIndex(u => u.id === user.id);
    
    if (userIndex !== -1) {
        if (!storedUsers[userIndex].enrolledCourses) {
            storedUsers[userIndex].enrolledCourses = [];
        }
        if (!storedUsers[userIndex].enrolledCourses.includes(courseId)) {
            storedUsers[userIndex].enrolledCourses.push(courseId);
            localStorage.setItem('users', JSON.stringify(storedUsers));
            localStorage.setItem('currentUser', JSON.stringify(storedUsers[userIndex]));
            return true;
        }
    }
    return false;
}

function isEnrolled(courseId) {
    const user = getCurrentUser();
    if (!user) return false;
    return user.enrolledCourses && user.enrolledCourses.includes(courseId);
}

function getEnrolledCourses() {
    const user = getCurrentUser();
    if (!user || !user.enrolledCourses) return [];
    return courses.filter(c => user.enrolledCourses.includes(c.id));
}

function updateProfile(name, email) {
    const user = getCurrentUser();
    if (!user) return false;
    
    const storedUsers = JSON.parse(localStorage.getItem('users') || '[]');
    const userIndex = storedUsers.findIndex(u => u.id === user.id);
    
    if (userIndex !== -1) {
        storedUsers[userIndex].name = name;
        storedUsers[userIndex].email = email;
        localStorage.setItem('users', JSON.stringify(storedUsers));
        localStorage.setItem('currentUser', JSON.stringify(storedUsers[userIndex]));
        return true;
    }
    return false;
}

function initDemoUser() {
    const storedUsers = JSON.parse(localStorage.getItem('users') || '[]');
    if (!storedUsers.some(u => u.email === 'demo@example.com')) {
        storedUsers.push({
            id: 1,
            name: 'Demo User',
            email: 'demo@example.com',
            password: 'demo123',
            enrolledCourses: [1, 3],
            joinedAt: '2024-01-01T00:00:00.000Z'
        });
        localStorage.setItem('users', JSON.stringify(storedUsers));
    }
}

function searchCourses(query) {
    query = query.toLowerCase();
    const results = [];
    
    courses.forEach(course => {
        if (course.name.toLowerCase().includes(query)) {
            results.push({ type: 'course', item: course });
        }
        
        course.videos.forEach(video => {
            if (video.title.toLowerCase().includes(query)) {
                results.push({ type: 'video', item: video, course: course });
            }
        });
    });
    
    return results;
}

function getNavbarContent() {
    const user = getCurrentUser();
    if (user) {
        const initials = user.name.charAt(0).toUpperCase();
        return `
            <li class="user-menu">
                <a href="#" class="user-profile">
                    <div class="user-avatar-small">${initials}</div>
                    <span class="user-name">${user.name}</span>
                    <span class="dropdown-arrow">▼</span>
                </a>
                <ul class="user-dropdown">
                    <li><a href="dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="profile.html"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="#" onclick="logout(); window.location.href='index.html'; return false;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        `;
    }
    return `<li><a href="login.html" class="get-started-btn">Get Started</a></li>`;
}

function getFooterContent() {
    const user = getCurrentUser();
    return `
        <div class="footer-links">
            <h3>Account</h3>
            <ul>
                ${user ? `
                    <li><a href="dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="profile.html"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="#" onclick="logout(); window.location.href='index.html'; return false;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                ` : `
                    <li><a href="login.html"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="register.html"><i class="fas fa-user-plus"></i> Register</a></li>
                `}
            </ul>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', function() {
    initDemoUser();
    
    const navbarContent = document.getElementById('navbarContent');
    if (navbarContent) {
        navbarContent.innerHTML = getNavbarContent();
    }
    
    const footerContent = document.getElementById('footerAccount');
    if (footerContent) {
        footerContent.innerHTML = getFooterContent();
    }
});
