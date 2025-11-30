<?php
// config.php - Globale Konfigurationseinstellungen

// Datenbank-Zugangsdaten
define('DB_HOST', 'localhost');
define('DB_NAME', 'clearwiki');
define('DB_USER', 'clearwiki');
define('DB_PASS', 'Asudi8iseg63!!!');
define('DB_CHARSET', 'utf8mb4');

// Sprachen
define('DEFAULT_LANG', 'de'); // Standardsprache der Anwendung (bleibt als Fallback)
// define('SUPPORTED_LANGS', ['de', 'en', 'fr']); // Diese Konstante wird nun dynamisch aus der DB geladen

// Upload-Einstellungen
define('UPLOAD_DIR', __DIR__ . '/public/uploads/images/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

// Weitere allgemeine Einstellungen können hier hinzugefügt werden
