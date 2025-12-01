<?php
// config.php - File-Based Configuration (NO DATABASE REQUIRED!)

// =====================================================
// FILE STORAGE SETTINGS
// =====================================================

// Data directory (where all data files are stored)
define('DATA_DIR', __DIR__ . '/data/');

// Data file paths
define('USERS_FILE', DATA_DIR . 'users/users.json');
define('ARTICLES_FILE', DATA_DIR . 'articles/articles.json');
define('SETTINGS_FILE', DATA_DIR . 'settings/global.json');
define('FOOTER_LINKS_FILE', DATA_DIR . 'settings/footer_links.json');
define('SUPPORTED_LANGUAGES_FILE', DATA_DIR . 'languages/supported.json');
define('LANGUAGE_FILES_DIR', DATA_DIR . 'languages/');

// =====================================================
// LANGUAGE SETTINGS
// =====================================================

// Default language for the application
define('DEFAULT_LANG', 'de');

// =====================================================
// FILE UPLOAD SETTINGS
// =====================================================

// Upload directory for images
define('UPLOAD_DIR', __DIR__ . '/public/uploads/images/');

// Maximum upload file size in bytes (5 MB)
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

// =====================================================
// SESSION SETTINGS
// =====================================================

// Session lifetime in seconds (1 hour)
define('SESSION_LIFETIME', 3600);

// =====================================================
// SECURITY SETTINGS
// =====================================================

// Enable file locking for concurrent writes
define('FILE_LOCK_ENABLED', true);

// Auto-create data directories if they don't exist
define('AUTO_CREATE_DIRS', true);
