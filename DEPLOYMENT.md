# 000webhost Deployment Guide

## Step 1: Export Your Local Database
1. Open XAMPP Control Panel
2. Click "Admin" next to MySQL to open phpMyAdmin
3. Select your database (capstone_tutorials)
4. Click "Export" tab
5. Click "Go" to download the .sql file
6. Save it to your project folder

## Step 2: Sign Up for 000webhost
1. Go to https://www.000webhost.com
2. Click "Sign Up" and register (use Google or email)
3. Verify your email if required

## Step 3: Create a New Website
1. Click "Create New Website"
2. Choose "Upload your own site"
3. Enter a name (e.g., "capstone-tutorials")
4. Your free URL will be: yourname.000webhostapp.com

## Step 4: Upload Files via FileZilla
1. Download FileZilla from https://filezilla-project.org
2. In 000webhost dashboard, go to Settings > FTP Details
3. Note your FTP hostname, username, and password
4. Open FileZilla and connect:
   - Host: ftpupload.net
   - Username: (from 000webhost)
   - Password: (from 000webhost)
   - Port: 21
5. Navigate to `public_html` folder
6. Upload ALL project files EXCEPT sensitive files:
   - DO NOT upload: admin_cookie.txt, session.txt, cookies.txt, etc.
   - Upload: index.php, login.php, course.php, db.php, etc.

## Step 5: Create MySQL Database
1. In 000webhost dashboard, go to Tools > Database Manager
2. Click "Create New Database"
3. Note these details:
   - Database name
   - Database username
   - Database password
   - Host (usually localhost)

## Step 6: Import Your Database
1. In 000webhost, go to phpMyAdmin (Tools > Database Manager > phpMyAdmin)
2. Select your database
3. Click "Import" tab
4. Upload your exported .sql file
5. Click "Go"

## Step 7: Configure Database Connection
Since db.php now uses environment variables, 000webhost should auto-detect.
If needed, create a .user.ini file in public_html:

```ini
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASS=your_database_password
```

## Step 8: Test Your Site
Visit your URL: yourname.000webhostapp.com

Default admin login:
- Username: admin
- Password: admin123

## Troubleshooting
- If you get database errors, double-check your database credentials
- Make sure you imported the database correctly
- Clear browser cache if changes don't appear
- Check 000webhost error logs for PHP errors
