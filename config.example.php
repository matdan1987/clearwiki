<?php
// config.example.php - Example Configuration File
// COPY THIS FILE TO config.php AND CONFIGURE YOUR SETTINGS

// =====================================================
// DATABASE CONFIGURATION
// =====================================================

// Database host (usually 'localhost')
define('DB_HOST', 'localhost');

// Database name
define('DB_NAME', 'clearwiki');

// Database username
define('DB_USER', 'clearwiki');

// Database password
// IMPORTANT: Change this to a strong, unique password!
// Use at least 20 characters with mixed case, numbers, and special characters
define('DB_PASS', 'CHANGE_THIS_TO_A_SECURE_PASSWORD');

// Database charset (recommended: utf8mb4 for full Unicode support)
define('DB_CHARSET', 'utf8mb4');

// =====================================================
// LANGUAGE SETTINGS
// =====================================================

// Default language for the application
// This is used as fallback if no language is specified in the URL
// Available options: 'de', 'en', 'fr' (or any language code you add)
define('DEFAULT_LANG', 'de');

// Note: Supported languages are now loaded dynamically from the database
// You can manage them through the Admin Panel -> Language Management

// =====================================================
// FILE UPLOAD SETTINGS
// =====================================================

// Upload directory for images (relative to project root)
// Make sure this directory exists and is writable (chmod 775)
define('UPLOAD_DIR', __DIR__ . '/public/uploads/images/');

// Maximum upload file size in bytes
// Default: 5 MB (5 * 1024 * 1024)
// Note: This must also be configured in php.ini:
//   - upload_max_filesize
//   - post_max_size
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

// =====================================================
// SECURITY SETTINGS (Optional)
// =====================================================

// Session configuration (uncomment to use)
// define('SESSION_LIFETIME', 3600); // Session lifetime in seconds (1 hour)
// define('SESSION_COOKIE_SECURE', true); // Only send cookie over HTTPS
// define('SESSION_COOKIE_HTTPONLY', true); // Prevent JavaScript access to session cookie

// =====================================================
// APPLICATION SETTINGS
// =====================================================

// Application URL (without trailing slash)
// Used for generating absolute URLs in emails, etc.
// define('APP_URL', 'https://your-domain.com');

// Application environment
// Options: 'production', 'development', 'testing'
// define('APP_ENV', 'production');

// Debug mode (DO NOT enable in production!)
// define('DEBUG_MODE', false);

// =====================================================
// EMAIL SETTINGS (for future use)
// =====================================================

// SMTP settings for sending emails
// define('SMTP_HOST', 'smtp.your-provider.com');
// define('SMTP_PORT', 587);
// define('SMTP_USERNAME', 'your-email@domain.com');
// define('SMTP_PASSWORD', 'your-smtp-password');
// define('SMTP_FROM_EMAIL', 'noreply@your-domain.com');
// define('SMTP_FROM_NAME', 'ClearWiki');

// =====================================================
// ADDITIONAL SETTINGS
// =====================================================

// Timezone (see: https://www.php.net/manual/en/timezones.php)
// date_default_timezone_set('Europe/Berlin');

// Add any additional custom settings below
// ...
