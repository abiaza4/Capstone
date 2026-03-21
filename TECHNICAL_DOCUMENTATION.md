# CAPstone Group E-Learning Platform
## Technical Documentation

---

## 1. Introduction

CAPstone Group E-Learning Platform is a web-based application designed to provide programming tutorials to students worldwide. The platform allows users to browse courses, enroll in programs, and watch video tutorials. Administrators have full control over managing courses, videos, enrollments, and users.

The system is built using PHP with PostgreSQL database. It features a modern dark-themed user interface with responsive design for optimal viewing on desktop and mobile devices.

---

## 2. System Overview

CAPstone Group E-Learning Platform serves as a centralized hub for programming education. The system consists of two main interfaces: the public-facing user interface and the administrative backend.

The user interface allows visitors to browse available programming courses, create accounts, enroll in courses, and access video tutorials. The administrative backend provides administrators with tools to manage all aspects of the platform including courses, video content, user enrollments, and user accounts.

The platform currently offers eight programming languages: Python, Java, JavaScript, PHP, C++, C#, Ruby, and Swift. Each course contains multiple video tutorials with a maximum enrollment capacity per course.

---

## 3. User Features

### 3.1 Registration and Account Creation

New users can create an account by providing their full name, email address, and password. The registration form includes client-side validation to ensure passwords match and meet minimum length requirements. The system checks for existing email addresses to prevent duplicate accounts. All passwords are securely hashed using PHP's password_hash function before storage.

### 3.2 Login and Authentication

Users access the login page by clicking the "Get Started" button in the navigation. The login form accepts email and password credentials. An optional "Remember Me" checkbox stores the user's email in a cookie for convenience on future visits. The system verifies credentials against the database and creates a session upon successful authentication.

### 3.3 Course Browsing

The homepage displays all available courses in a grid layout. Each course card shows the programming language icon, course name, and a brief description. Users can click on any course card to view detailed course information including video listings and enrollment status.

### 3.4 Course Enrollment

Users can enroll in courses from the course detail page. The enrollment system creates a record linking the user to the selected course. By default, enrollments are set to approved status, allowing immediate access to course videos. Users can view their enrolled courses from the dashboard and dedicated my courses page.

### 3.5 Video Watching

Enrolled users can access video tutorials from the course detail page. Videos are displayed as clickable cards showing thumbnails, titles, view counts, and engagement metrics. Clicking a video opens it in a new browser tab. Non-enrolled users see a lock screen prompting them to enroll before accessing content.

### 3.6 User Dashboard

The user dashboard provides a personalized overview of the user's learning journey. It displays statistics including enrolled courses count, videos watched, and learning time. The dashboard also shows currently enrolled courses and recommends additional courses the user might be interested in.

### 3.7 Profile Management

Users can update their profile information including their display name. A change password feature requires users to verify their current password before setting a new one. The profile page displays user avatar (generated from first letter of name), account creation date, and registration email.

### 3.8 Search Functionality

The search feature allows users to find courses by entering keywords. The search queries course names and descriptions to return matching results. If no matches are found, the system displays a message and shows popular courses as alternatives.

### 3.9 Contact Information

The contact page provides multiple ways to reach the organization including physical address, email addresses, phone numbers, and business hours. A contact form allows users to send messages directly through the website.

---

## 4. Admin Features

### 4.1 Admin Authentication

Administrators access the login page using their admin credentials. The system checks the admins table and supports login using either username or email. Upon successful authentication, administrators are redirected to the admin dashboard.

### 4.2 Dashboard Overview

The admin dashboard provides a comprehensive overview of platform activity. It displays real-time statistics including total users, total courses, total videos, total video views, and pending enrollment requests. The dashboard also shows recent user registrations and recent enrollment activity in tabular format.

### 4.3 Course Management

Administrators can add new courses by providing a course name, description, icon URL, and access limit. The edit functionality allows modification of any course attribute. Course deletion requires confirmation and cascades to associated video records. The course management interface displays all courses in a table format with action buttons for edit and delete operations.

### 4.4 Video Management

Video management supports adding tutorials to existing courses. Administrators can select the target course, enter video title, choose video type (YouTube or local upload), and provide either a YouTube video ID or upload a local video file. The system auto-generates YouTube thumbnails from video IDs. Edit functionality allows modification of all video attributes. Delete operations remove the video record and associated local files if applicable.

### 4.5 Enrollment Management

The enrollment management interface displays all user enrollments organized by status: pending, approved, rejected, and all. Administrators can approve or reject pending enrollment requests. Approved enrollments grant users immediate access to course videos. Rejected enrollments deny access. Administrators can also delete enrollment records.

### 4.6 User Management

User management provides a searchable list of all registered users. Administrators can view user details including name, email, enrollment count, and registration date. The interface includes a link to view all courses enrolled by a specific user. Administrators can delete user accounts which also removes associated enrollment records.

---

## 5. Database Structure

### 5.1 Users Table

The users table stores registered user accounts. It contains columns for user ID (auto-incrementing primary key), name (varchar 100 characters), email (varchar 100 characters, unique), password (varchar 255 characters, hashed), and created_at timestamp. This table is the primary source for user authentication and profile information.

### 5.2 Admins Table

The admins table contains administrative accounts. Fields include ID (auto-incrementing primary key), username (varchar 50 characters, unique), email (varchar 255 characters), password (varchar 255 characters, hashed), and created_at timestamp. The default admin account credentials are admin with password admin123.

### 5.3 Courses Table

The courses table stores course information. It includes ID (auto-incrementing primary key), name (varchar 100 characters), description (text field), icon (varchar 255 characters for image URL), access_limit (integer default 100 for concurrent user capacity), current_access (integer default 0 tracking current users), and created_at timestamp.

### 5.4 Videos Table

The videos table contains tutorial video records. Fields include ID (auto-incrementing primary key), course_id (foreign key to courses table), title (varchar 255 characters), youtube_id (varchar 50 characters for YouTube videos), video_type (enum indicating YouTube or local), video_path (varchar 255 characters for local file location), thumbnail_url (varchar 255 characters), views (bigint default 0), likes (bigint default 0), comments (bigint default 0), and created_at timestamp.

### 5.5 Enrollments Table

The enrollments table tracks user course registrations. It contains ID (auto-incrementing primary key), user_id (foreign key to users table), course_id (foreign key to courses table), status (varchar indicating pending, approved, or rejected), and enrolled_at timestamp. A unique constraint on user_id and course_id combination prevents duplicate enrollments.

### 5.6 Course Access Log Table

The course_access_log table records when users access courses. Fields include ID (auto-incrementing primary key), user_id (foreign key to users table), course_id (foreign key to courses table), started_at timestamp, and ended_at timestamp (nullable for ongoing sessions). This table enables tracking of user engagement and learning time.

---

## 6. File Structure

### 6.1 Root Directory Files

The root directory contains the main application files. The index.php file serves as the homepage displaying all courses. The login.php and register.php files handle user authentication. The logout.php file destroys sessions and redirects to the homepage. The course.php file displays individual course details and video listings. The dashboard.php file provides the authenticated user dashboard. The my_courses.php file lists user enrollments. The profile.php file handles user profile management. The search.php file processes course searches. The contact.php and about.php files provide informational pages.

### 6.2 Admin Directory

The admin directory contains administrative interface files. The index.php file serves as the admin dashboard. The courses.php file manages course CRUD operations. The videos.php file manages video CRUD operations. The enrollments.php file handles enrollment approvals and rejections. The users.php file lists and manages user accounts. The user_courses.php file displays specific user enrollment details. The view_courses.php file allows browsing courses in admin context. The logout.php file handles admin session termination.

### 6.3 Supporting Files

The db.php file establishes database connections using PDO and provides helper functions including formatNumber for number formatting, isLoggedIn and isAdmin for authentication checks, and redirect for navigation. The style.css file contains all styling rules for consistent visual presentation across the application. The database.sql file contains MySQL schema definitions. The database_postgresql.sql file contains PostgreSQL schema definitions for production deployment.

---

## 7. Authentication System

### 7.1 User Login Process

The login process begins when users submit credentials on the login page. The system queries the users table using the provided email address. If a matching user is found, the system verifies the password using password_verify function. On successful verification, the system sets session variables including user_id, user_name, and user_email. If the remember me checkbox was selected, the system sets a cookie containing the user's email for 30 days.

### 7.2 Admin Login Process

Admin login follows a similar pattern but queries the admins table. The system supports login using either username or email address. Upon successful authentication, session variables including admin_id and admin_username are set. The system includes backward compatibility for plain text passwords by checking both hashed and plain text comparisons and automatically upgrades plain text passwords to secure hashes.

### 7.3 Session Management

Sessions begin automatically when db.php is included in any page. The isLoggedIn function checks for the presence of user_id in the session array. The isAdmin function checks for admin_id in the session array. Protected pages verify these conditions and redirect unauthorized users to the login page. The logout process destroys session data and clears remember me cookies.

### 7.4 Password Security

Passwords are hashed using PHP's password_hash function with the default bcrypt algorithm. The password_verify function compares submitted passwords against stored hashes. The system automatically salts passwords during hashing. Minimum password requirements include a minimum of six characters enforced during registration.

---

## 8. User Flow

### 8.1 New User Registration Flow

A new user visits the homepage and clicks Get Started. The user navigates to the registration page and fills out the form with name, email, and password. The system validates inputs and checks for existing email. Upon successful registration, the user is redirected to the login page. The user enters credentials and gains access to the dashboard.

### 8.2 Course Enrollment Flow

An authenticated user browses courses on the homepage. The user clicks on a course card to view course details. The system checks the user's enrollment status. If not enrolled, the user sees an enroll button. Clicking enroll creates an enrollment record with approved status. The user immediately gains access to view course videos. The course appears in the user's dashboard and my courses page.

### 8.3 Video Access Control

The system enforces video access through enrollment checks. When a user visits a course page, the system queries the enrollments table for an approved enrollment record linking the user to the course. Enrolled users see all video cards displayed on the course page. Non-enrolled users see an enrollment required message with a lock icon. Clicking enroll redirects non-enrolled users to the login page if not authenticated.

---

## 9. Deployment

### 9.1 Local Development Setup

For local development using XAMPP, place the project files in the htdocs directory. Create a MySQL database named capstone_tutorials using phpMyAdmin. Import the database.sql schema file to create all required tables. Update db.php connection settings for local environment. Access the application at localhost/Capstone.

### 9.2 Production Deployment (Render)

For deployment to Render, create a new Web Service connected to the GitHub repository. Configure environment variables including DB_HOST, DB_NAME, DB_USER, and DB_PASS for PostgreSQL connection. Create a PostgreSQL database on Render and import the database_postgresql.sql schema. Ensure the build command is empty since this is a PHP application. Set the start command to serve the public directory or configure Apache/Nginx appropriately.

### 9.3 Database Configuration

The application uses PDO for database connections. The db.php file reads connection parameters from environment variables with fallback defaults. For production, set all database credentials as environment variables in the hosting platform. Never commit database credentials to version control. The database schema supports both MySQL for development and PostgreSQL for production.

---

## 10. Default Credentials

The default admin account is created during database initialization. The admin username is admin and the password is admin123. It is strongly recommended to change these credentials immediately after initial deployment. Create new admin accounts with secure passwords following password best practices.

---

## 11. Troubleshooting

### 11.1 Common Issues

Database connection errors indicate incorrect credentials or unreachable database servers. Verify environment variables are set correctly and database is accessible. Login failures may result from incorrect credentials or expired sessions. Clear browser cookies and try again. Missing pages or 404 errors suggest incorrect file paths or missing deployment files. Verify all files were uploaded successfully.

### 11.2 Debugging

Enable error reporting during development by configuring PHP display_errors. Check Apache or web server error logs for runtime issues. Verify database tables exist and contain expected data. Test database connections separately before testing application logic.

---

## 12. Future Improvements

The platform could benefit from several enhancements. Video progress tracking would allow users to resume videos from where they left off. A rating and review system would enable user feedback on courses and videos. Discussion forums or comment sections would foster community interaction. Progress certificates for course completion would provide tangible achievements. Mobile applications for iOS and Android would expand accessibility. Integration with video platforms beyond YouTube would increase content options.

---

## 13. Conclusion

CAPstone Group E-Learning Platform provides a comprehensive solution for programming education delivery. The modular architecture allows easy expansion and maintenance. The dual-interface design separates user and administrative functionality for clear user experience. The PostgreSQL backend ensures reliable data storage and retrieval. The responsive design accommodates various devices and screen sizes. The platform serves as a solid foundation for delivering quality programming tutorials to learners worldwide.
