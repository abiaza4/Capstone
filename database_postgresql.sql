-- Database: capstone_tutorials (PostgreSQL)
-- Run this in Render's PostgreSQL dashboard

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    access_limit INT DEFAULT 100,
    current_access INT DEFAULT 0
);

-- Videos table
CREATE TABLE IF NOT EXISTS videos (
    id SERIAL PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    youtube_id VARCHAR(50),
    video_type VARCHAR(20) DEFAULT 'youtube',
    video_path VARCHAR(255),
    thumbnail_url VARCHAR(255),
    views BIGINT DEFAULT 0,
    likes BIGINT DEFAULT 0,
    comments BIGINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Enrollments table
CREATE TABLE IF NOT EXISTS enrollments (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE (user_id, course_id)
);

-- Course access log for tracking
CREATE TABLE IF NOT EXISTS course_access_log (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ended_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Insert default admin
INSERT INTO admins (username, password) VALUES ('admin', 'admin123');

-- Insert courses
INSERT INTO courses (name, description, icon) VALUES
('Python', 'A high-level programming language known for its simplicity and readability.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg'),
('Java', 'A versatile, object-oriented programming language used for web and mobile development.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg'),
('JavaScript', 'The scripting language for web pages, essential for interactive websites.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg'),
('PHP', 'A server-side scripting language designed for web development.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg'),
('C++', 'A powerful programming language used for system software and performance-critical applications.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cplusplus/cplusplus-original.svg'),
('C#', 'A modern, object-oriented language developed by Microsoft for Windows applications.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/csharp/csharp-original.svg'),
('Ruby', 'A dynamic, reflective programming language focused on simplicity and productivity.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/ruby/ruby-original.svg'),
('Swift', 'Apple''s programming language for iOS and macOS development.', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/swift/swift-original.svg');

-- Insert sample videos
INSERT INTO videos (course_id, title, youtube_id, video_type, thumbnail_url, views, likes, comments) VALUES
(1, 'Python Full Course for Beginners', 'rfscVS0vtbw', 'youtube', 'https://img.youtube.com/vi/rfscVS0vtbw/maxresdefault.jpg', 24000000, 520000, 28000),
(1, 'Python Tutorial - Python for Beginners', 'kqtD5dpn9C8', 'youtube', 'https://img.youtube.com/vi/kqtD5dpn9C8/maxresdefault.jpg', 18000000, 380000, 22000),
(1, 'Learn Python - Full Course for Beginners', 'agvfruY7Z7w', 'youtube', 'https://img.youtube.com/vi/agvfruY7Z7w/maxresdefault.jpg', 15000000, 320000, 18000),
(1, 'Python Crash Course', 'JJmcL1N2KAY', 'youtube', 'https://img.youtube.com/vi/JJmcL1N2KAY/maxresdefault.jpg', 12000000, 280000, 15000),
(1, 'Python Programming Tutorial', 'B9nFMZIcQl0', 'youtube', 'https://img.youtube.com/vi/B9nFMZIcQl0/maxresdefault.jpg', 5000000, 120000, 8000),
(1, 'Advanced Python Tutorial', 'QLM3Y3X6dLo', 'youtube', 'https://img.youtube.com/vi/QLM3Y3X6dLo/maxresdefault.jpg', 3200000, 85000, 5000),
(2, 'Java Tutorial for Beginners', 'aQatrXwNMjQ', 'youtube', 'https://img.youtube.com/vi/aQatrXwNMjQ/maxresdefault.jpg', 15000000, 340000, 19000),
(2, 'Learn Java Programming - Full Course', 'x4hUNqPNlLg', 'youtube', 'https://img.youtube.com/vi/x4hUNqPNlLg/maxresdefault.jpg', 11000000, 250000, 14000),
(2, 'Java Programming Tutorial', 'Y16XlPpBCgg', 'youtube', 'https://img.youtube.com/vi/Y16XlPpBCgg/maxresdefault.jpg', 8000000, 180000, 9500),
(2, 'Java Full Course', 'hBh_CC5y8-c', 'youtube', 'https://img.youtube.com/vi/hBh_CC5y8-c/maxresdefault.jpg', 6500000, 150000, 8000),
(2, 'Java Tutorial for Beginners - Complete Course', 'T1psV5FRp54', 'youtube', 'https://img.youtube.com/vi/T1psV5FRp54/maxresdefault.jpg', 4200000, 95000, 5200),
(2, 'Object Oriented Programming with Java', '8mGfkTwf0k', 'youtube', 'https://img.youtube.com/vi/8mGfkTwf0k/maxresdefault.jpg', 3000000, 72000, 4000),
(3, 'JavaScript Tutorial for Beginners', 'W6NZfCO5SIk', 'youtube', 'https://img.youtube.com/vi/W6NZfCO5SIk/maxresdefault.jpg', 22000000, 480000, 26000),
(3, 'Learn JavaScript - Full Course', 'KgL6hqlP0p4', 'youtube', 'https://img.youtube.com/vi/KgL6hqlP0p4/maxresdefault.jpg', 18000000, 390000, 21000),
(3, 'JavaScript Crash Course', 'hdI2bqOjiy4', 'youtube', 'https://img.youtube.com/vi/hdI2bqOjiy4/maxresdefault.jpg', 12000000, 270000, 15000),
(3, 'JavaScript Programming Tutorial', 'C6UhO4P5Rzo', 'youtube', 'https://img.youtube.com/vi/C6UhO4P5Rzo/maxresdefault.jpg', 5500000, 130000, 7500),
(3, 'Modern JavaScript Tutorial', '0S8lMTaVjr4', 'youtube', 'https://img.youtube.com/vi/0S8lMTaVjr4/maxresdefault.jpg', 4800000, 110000, 6200),
(3, 'JavaScript Fundamentals', '9emXNzqCKyg', 'youtube', 'https://img.youtube.com/vi/9emXNzqCKyg/maxresdefault.jpg', 3500000, 85000, 4800),
(4, 'PHP Tutorial for Beginners', 'OK_JCtrrv-c', 'youtube', 'https://img.youtube.com/vi/OK_JCtrrv-c/maxresdefault.jpg', 12000000, 260000, 14000),
(4, 'Learn PHP - Full Course', 't0syDUSbddE', 'youtube', 'https://img.youtube.com/vi/t0syDUSbddE/maxresdefault.jpg', 9500000, 210000, 12000),
(4, 'PHP Programming Tutorial', '6E7XmT1rB5w', 'youtube', 'https://img.youtube.com/vi/6E7XmT1rB5w/maxresdefault.jpg', 4200000, 95000, 5500),
(4, 'PHP Crash Course', 'aK7G3aG3w0E', 'youtube', 'https://img.youtube.com/vi/aK7G3aG3w0E/maxresdefault.jpg', 3800000, 88000, 4800),
(4, 'PHP & MySQL Tutorial', 'iC8KbuH_Ms0', 'youtube', 'https://img.youtube.com/vi/iC8KbuH_Ms0/maxresdefault.jpg', 3200000, 75000, 4200),
(4, 'Modern PHP Development', 'KdD4lLJpG3M', 'youtube', 'https://img.youtube.com/vi/KdD4lLJpG3M/maxresdefault.jpg', 2800000, 65000, 3600),
(5, 'C++ Tutorial for Beginners', 'vLnPqwA4Q9g', 'youtube', 'https://img.youtube.com/vi/vLnPqwA4Q9g/maxresdefault.jpg', 15000000, 320000, 17000),
(5, 'Learn C++ - Full Course', 'MhYkCHqAT7A', 'youtube', 'https://img.youtube.com/vi/MhYkCHqAT7A/maxresdefault.jpg', 11000000, 240000, 13000),
(5, 'C++ Crash Course', 'G-p2bKDbH7M', 'youtube', 'https://img.youtube.com/vi/G-p2bKDbH7M/maxresdefault.jpg', 6500000, 150000, 8500),
(5, 'C++ Programming Tutorial', 'yG1UbKPEEpQ', 'youtube', 'https://img.youtube.com/vi/yG1UbKPEEpQ/maxresdefault.jpg', 4800000, 110000, 6200),
(5, 'Object Oriented Programming C++', 'AB2c6mu1N5E', 'youtube', 'https://img.youtube.com/vi/AB2c6mu1N5E/maxresdefault.jpg', 3500000, 82000, 4500),
(5, 'C++ Standard Library', 'pI1Lj4nV4oM', 'youtube', 'https://img.youtube.com/vi/pI1Lj4nV4oM/maxresdefault.jpg', 2200000, 52000, 3000),
(6, 'C# Tutorial for Beginners', 'cNfV-ZOROT8', 'youtube', 'https://img.youtube.com/vi/cNfV-ZOROT8/maxresdefault.jpg', 8500000, 190000, 10500),
(6, 'Learn C# - Full Course', 'q_F4n2pyqHM', 'youtube', 'https://img.youtube.com/vi/q_F4n2pyqHM/maxresdefault.jpg', 6200000, 140000, 7800),
(6, 'C# Programming Tutorial', 'pNxMBZBW6QQ', 'youtube', 'https://img.youtube.com/vi/pNxMBZBW6QQ/maxresdefault.jpg', 3800000, 88000, 4800),
(6, 'C# Crash Course', 'gfkTfcpWqAY', 'youtube', 'https://img.youtube.com/vi/gfkTfcpWqAY/maxresdefault.jpg', 3200000, 75000, 4200),
(6, 'C# & Unity Tutorial', 'w4zqR_wO7f0', 'youtube', 'https://img.youtube.com/vi/w4zqR_wO7f0/maxresdefault.jpg', 2800000, 65000, 3600),
(6, 'ASP.NET Core Tutorial', 'lE8NqU9-HQQ', 'youtube', 'https://img.youtube.com/vi/lE8NqU9-HQQ/maxresdefault.jpg', 2100000, 48000, 2800),
(7, 'Ruby Tutorial for Beginners', 'f6G5ZNTV7Xs', 'youtube', 'https://img.youtube.com/vi/f6G5ZNTV7Xs/maxresdefault.jpg', 3200000, 75000, 4200),
(7, 'Learn Ruby - Full Course', 'tis5cjAnjT4', 'youtube', 'https://img.youtube.com/vi/tis5cjAnjT4/maxresdefault.jpg', 2100000, 48000, 2800),
(7, 'Ruby on Rails Tutorial', 'B3FbujgoFWw', 'youtube', 'https://img.youtube.com/vi/B3FbujgoFWw/maxresdefault.jpg', 1800000, 42000, 2400),
(7, 'Ruby Programming Tutorial', 'D8Y2_WNgQ7Q', 'youtube', 'https://img.youtube.com/vi/D8Y2_WNgQ7Q/maxresdefault.jpg', 1500000, 35000, 2000),
(7, 'Ruby Crash Course', 'k5wEm4Cmp6U', 'youtube', 'https://img.youtube.com/vi/k5wEm4Cmp6U/maxresdefault.jpg', 1200000, 28000, 1600),
(7, 'Ruby on Rails 7 Tutorial', 'pPy_Gp2qjqY', 'youtube', 'https://img.youtube.com/vi/pPy_Gp2qjqY/maxresdefault.jpg', 1050000, 24000, 1400),
(8, 'Swift Tutorial for Beginners', 'comQ1sykzlSY', 'youtube', 'https://img.youtube.com/vi/comQ1sykzlSY/maxresdefault.jpg', 5200000, 120000, 6800),
(8, 'Learn Swift - Full Course', 'F2aoj6zAt7Q', 'youtube', 'https://img.youtube.com/vi/F2aoj6zAt7Q/maxresdefault.jpg', 3800000, 88000, 4800),
(8, 'iOS Development with Swift', '9A-emr8qV3c', 'youtube', 'https://img.youtube.com/vi/9A-emr8qV3c/maxresdefault.jpg', 2800000, 65000, 3600),
(8, 'Swift Programming Tutorial', 'Umg_sVWoA7E', 'youtube', 'https://img.youtube.com/vi/Umg_sVWoA7E/maxresdefault.jpg', 2100000, 48000, 2800),
(8, 'SwiftUI Tutorial', 'H9GU7ir2qDM', 'youtube', 'https://img.youtube.com/vi/H9GU7ir2qDM/maxresdefault.jpg', 1800000, 42000, 2400),
(8, 'Swift 5 Tutorial', '4Gj-cTB02y4', 'youtube', 'https://img.youtube.com/vi/4Gj-cTB02y4/maxresdefault.jpg', 1500000, 35000, 2000);
