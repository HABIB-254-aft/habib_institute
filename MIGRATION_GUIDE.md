# Database Migration Guide

## Overview
Your application has been successfully migrated from file-based storage to MySQL database.

## Setup Instructions

### 1. Configure Database
Edit `config/database.php` and update these values:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'habib_institute');
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
```

### 2. Create Database and Tables
Run the setup script:
- **Via Browser:** Visit `http://your-domain/setup-database.php`
- **Via Command Line:** `php setup-database.php`

This will:
- Create the database `habib_institute`
- Create all necessary tables
- Set up proper indexes

### 3. Migrate Existing Data (Optional)
If you have existing data in the `Storage/` directory, run:
- Visit `http://your-domain/migrate-data.php` (if created)

## Database Schema

### Tables Created:
1. **students** - Student accounts
2. **applications** - Course applications
3. **messages** - Contact form messages
4. **subscribers** - Newsletter subscribers
5. **student_ids** - Generated student IDs
6. **homework_submissions** - Homework uploads
7. **password_resets** - Password reset tokens
8. **student_progress** - Lesson progress tracking
9. **online_classes** - Online class schedules
10. **assignments** - Course assignments

## Files Updated

All PHP files have been updated to use the database:
- ✅ Student registration and login
- ✅ Application and contact forms
- ✅ Newsletter subscription
- ✅ Student dashboard and profile
- ✅ Progress tracking
- ✅ Password reset
- ✅ All admin pages
- ✅ Homework submissions
- ✅ Online classes
- ✅ Assignments

## Testing

1. Test student registration
2. Test login
3. Submit a contact form
4. Check admin dashboard
5. Upload homework
6. Track progress

## Backup

**Important:** Always backup your database regularly:
```bash
mysqldump -u root -p habib_institute > backup.sql
```

## Notes

- The old `Storage/` directory can be kept for reference but is no longer used
- All file operations have been replaced with database queries
- Error logging is enabled for debugging
- Prepared statements are used for security (SQL injection prevention)

## Troubleshooting

**Connection Error:**
- Check database credentials in `config/database.php`
- Ensure MySQL is running
- Verify database user has proper permissions

**Table Not Found:**
- Run `setup-database.php` again

**Data Not Showing:**
- Check error logs
- Verify database connection
- Ensure tables were created successfully

