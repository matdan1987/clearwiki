<?php
// functions.php - FILE-BASED System (NO DATABASE!)
// Zentrale Funktionen für dateibasierte Speicherung

// Konfiguration laden
require_once __DIR__ . '/config.php';

// =====================================================
// FILE OPERATIONS - Core Helper Functions
// =====================================================

/**
 * Liest JSON-Daten aus einer Datei
 */
function read_json_file(string $filepath): array {
    if (!file_exists($filepath)) {
        return [];
    }

    $content = file_get_contents($filepath);
    if ($content === false) {
        error_log("Failed to read file: $filepath");
        return [];
    }

    $data = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error in $filepath: " . json_last_error_msg());
        return [];
    }

    return $data ?? [];
}

/**
 * Schreibt JSON-Daten in eine Datei
 */
function write_json_file(string $filepath, array $data): bool {
    // Verzeichnis erstellen, falls nicht vorhanden
    $dir = dirname($filepath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        error_log("JSON encode error: " . json_last_error_msg());
        return false;
    }

    // Mit File-Lock schreiben
    if (defined('FILE_LOCK_ENABLED') && FILE_LOCK_ENABLED) {
        $result = file_put_contents($filepath, $json, LOCK_EX);
    } else {
        $result = file_put_contents($filepath, $json);
    }

    return $result !== false;
}

/**
 * Generiert eine neue eindeutige ID
 */
function generate_new_id(array $items): int {
    if (empty($items)) {
        return 1;
    }

    $maxId = 0;
    foreach ($items as $item) {
        if (isset($item['id']) && $item['id'] > $maxId) {
            $maxId = $item['id'];
        }
    }

    return $maxId + 1;
}

// =====================================================
// BASIC HELPER FUNCTIONS
// =====================================================

/**
 * Sanitisiert eine Zeichenkette, um XSS-Angriffe zu verhindern.
 */
function sanitize_output(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Leitet den Benutzer auf eine andere Seite um.
 */
function redirect(string $location): void {
    header("Location: " . $location);
    exit();
}

/**
 * Lädt eine View-Datei.
 */
function load_view(string $view_name, array $data = []): void {
    extract($data);

    $view_path = __DIR__ . '/views/' . $view_name . '.php';

    if (file_exists($view_path)) {
        require $view_path;
    } else {
        error_log("View not found: " . $view_path);
        if ($view_name !== '404') {
            http_response_code(404);
            require __DIR__ . '/views/404.php';
        } else {
            die("Error: The 404 page itself could not be found.");
        }
        exit();
    }
}

/**
 * Startet eine PHP-Session, falls noch keine gestartet wurde.
 */
function start_session_if_not_started(): void {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

start_session_if_not_started();

// =====================================================
// LANGUAGE FUNCTIONS
// =====================================================

$_LANG = [];
$_SUPPORTED_LANG_CODES = [];

/**
 * Ruft alle aktiven unterstützten Sprachen ab.
 */
function get_supported_lang_codes(): array {
    global $_SUPPORTED_LANG_CODES;

    if (empty($_SUPPORTED_LANG_CODES)) {
        $languages = read_json_file(SUPPORTED_LANGUAGES_FILE);
        $_SUPPORTED_LANG_CODES = array_column(
            array_filter($languages, fn($lang) => $lang['is_active'] ?? false),
            'lang_code'
        );

        if (empty($_SUPPORTED_LANG_CODES)) {
            $_SUPPORTED_LANG_CODES = ['de', 'en', 'fr'];
        }
    }

    return $_SUPPORTED_LANG_CODES;
}

/**
 * Lädt die Sprachstrings für die gegebene Sprache aus der Datei.
 */
function load_language_strings_from_db(string $lang_code): void {
    global $_LANG;

    $lang_file = LANGUAGE_FILES_DIR . $lang_code . '.php';

    if (file_exists($lang_file)) {
        $_LANG = require $lang_file;
    } else {
        // Fallback auf DEFAULT_LANG
        $fallback_file = LANGUAGE_FILES_DIR . DEFAULT_LANG . '.php';
        if (file_exists($fallback_file)) {
            $_LANG = require $fallback_file;
        } else {
            $_LANG = [];
        }
    }
}

/**
 * Übersetzt einen Sprachschlüssel.
 */
function __(?string $key, array $placeholders = []): string {
    global $_LANG;

    if ($key === null) {
        return '';
    }

    $text = $_LANG[$key] ?? $key;

    foreach ($placeholders as $placeholder => $value) {
        $text = str_replace('{' . $placeholder . '}', $value, $text);
    }

    return $text;
}

// =====================================================
// SETTINGS FUNCTIONS
// =====================================================

$_SETTINGS = [];

/**
 * Lädt die globalen Einstellungen.
 */
function load_global_settings(): void {
    global $_SETTINGS;

    $_SETTINGS = read_json_file(SETTINGS_FILE);

    if (empty($_SETTINGS)) {
        $_SETTINGS = [
            'id' => 1,
            'wiki_name' => 'ClearWiki',
            'wiki_slogan' => 'Next Generation Gaming Guide',
            'display_wiki_name' => true,
            'display_slogan' => true,
            'display_logo' => true,
            'logo_height_px' => 40,
            'logo_path' => null,
            'favicon_path' => null,
            'footer_text' => '© ' . date('Y') . ' ClearWiki - Entwickelt mit ❤️ von Daniel Mattick.',
            'impressum_content' => 'Inhalt des Impressums hier. Bitte im Admin-Panel anpassen.',
            'maintenance_mode' => false,
            'enable_articles' => true,
            'enable_categories' => true,
            'enable_users' => true,
            'enable_alliances' => true,
            'enable_registration' => true,
            'enable_pending_contributions' => true,
            'enable_impressum' => true,
            'enable_send_alliance_mail' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        write_json_file(SETTINGS_FILE, $_SETTINGS);
    }
}

/**
 * Überprüft, ob ein bestimmtes Feature aktiv ist.
 */
function is_feature_enabled(string $feature_key): bool {
    global $_SETTINGS;

    if (is_admin() && $feature_key !== 'maintenance_mode') {
        return true;
    }

    return (bool)($_SETTINGS[$feature_key] ?? false);
}

/**
 * Ruft alle aktiven Footer-Links ab.
 */
function get_footer_links(): array {
    $links = read_json_file(FOOTER_LINKS_FILE);

    $grouped_links = [
        'wiki' => [],
        'community' => [],
        'legal' => []
    ];

    foreach ($links as $link) {
        if (($link['is_active'] ?? false) && isset($grouped_links[$link['section']])) {
            $grouped_links[$link['section']][] = $link;
        }
    }

    return $grouped_links;
}

// =====================================================
// USER FUNCTIONS
// =====================================================

/**
 * Registriert einen neuen Benutzer.
 */
function register_user(string $username, string $email, string $password, string $role = 'registered'): bool {
    $users = read_json_file(USERS_FILE);

    // Prüfe ob Benutzer bereits existiert
    foreach ($users as $user) {
        if ($user['username'] === $username || $user['email'] === $email) {
            return false;
        }
    }

    // Neuen Benutzer erstellen
    $new_user = [
        'id' => generate_new_id($users),
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'alliance_id' => null,
        'is_leader' => false,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $users[] = $new_user;

    return write_json_file(USERS_FILE, $users);
}

/**
 * Meldet einen Benutzer an.
 */
function login_user(string $identifier, string $password): bool {
    $users = read_json_file(USERS_FILE);

    foreach ($users as $user) {
        if (($user['username'] === $identifier || $user['email'] === $identifier)
            && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            session_regenerate_id(true);
            return true;
        }
    }

    return false;
}

/**
 * Gibt den aktuell angemeldeten Benutzer zurück.
 */
function get_current_user(): ?array {
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null) {
        if (!isset($_SESSION['username']) || !isset($_SESSION['user_role'])) {
            $user_data = get_user_by_id((int)$_SESSION['user_id']);
            if ($user_data) {
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['user_role'] = $user_data['role'];
                return [
                    'id' => (int)$_SESSION['user_id'],
                    'username' => $_SESSION['username'],
                    'role' => $_SESSION['user_role']
                ];
            } else {
                session_unset();
                session_destroy();
                return null;
            }
        }

        return [
            'id' => (int)$_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ];
    }

    return null;
}

/**
 * Überprüft, ob ein Benutzer angemeldet ist.
 */
function is_logged_in(): bool {
    return get_current_user() !== null;
}

/**
 * Gibt die Rolle des aktuell angemeldeten Benutzers zurück.
 */
function get_user_role(): ?string {
    $user = get_current_user();
    return $user['role'] ?? null;
}

/**
 * Überprüft, ob der angemeldete Benutzer ein Admin ist.
 */
function is_admin(): bool {
    return get_user_role() === 'admin';
}

/**
 * Überprüft, ob der angemeldete Benutzer ein Moderator oder Admin ist.
 */
function is_moderator_or_admin(): bool {
    $role = get_user_role();
    return $role === 'moderator' || $role === 'admin';
}

/**
 * Ruft Benutzerdaten anhand der ID ab.
 */
function get_user_by_id(int $user_id): ?array {
    $users = read_json_file(USERS_FILE);

    foreach ($users as $user) {
        if ($user['id'] === $user_id) {
            return $user;
        }
    }

    return null;
}

// =====================================================
// ARTICLE FUNCTIONS
// =====================================================

/**
 * Erstellt einen neuen Artikel.
 */
function create_article(
    string $title,
    string $content,
    ?int $author_user_id,
    ?string $author_guest_name,
    ?string $author_guest_email,
    string $status,
    string $lang_code
): int|false {
    $articles = read_json_file(ARTICLES_FILE);

    $slug = generate_unique_slug($title, $lang_code);

    $new_article = [
        'id' => generate_new_id($articles),
        'title' => $title,
        'slug' => $slug,
        'content' => $content,
        'author_user_id' => $author_user_id,
        'author_guest_name' => $author_guest_name,
        'author_guest_email' => $author_guest_email,
        'status' => $status,
        'lang_code' => $lang_code,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'published_at' => ($status === 'published') ? date('Y-m-d H:i:s') : null
    ];

    $articles[] = $new_article;

    if (write_json_file(ARTICLES_FILE, $articles)) {
        return $new_article['id'];
    }

    return false;
}

/**
 * Aktualisiert einen bestehenden Artikel.
 */
function update_article(
    int $id,
    string $title,
    string $content,
    string $status,
    string $lang_code
): bool {
    $articles = read_json_file(ARTICLES_FILE);

    foreach ($articles as &$article) {
        if ($article['id'] === $id) {
            $article['title'] = $title;
            $article['content'] = $content;
            $article['status'] = $status;
            $article['lang_code'] = $lang_code;
            $article['updated_at'] = date('Y-m-d H:i:s');

            // Set published_at if status changes to published
            if ($status === 'published' && $article['published_at'] === null) {
                $article['published_at'] = date('Y-m-d H:i:s');
            }

            return write_json_file(ARTICLES_FILE, $articles);
        }
    }

    return false;
}

/**
 * Löscht einen Artikel anhand seiner ID.
 */
function delete_article(int $id): bool {
    $articles = read_json_file(ARTICLES_FILE);

    $filtered = array_filter($articles, fn($article) => $article['id'] !== $id);

    if (count($filtered) < count($articles)) {
        return write_json_file(ARTICLES_FILE, array_values($filtered));
    }

    return false;
}

/**
 * Ruft einen Artikel anhand seines Slugs und Sprachcodes ab.
 */
function get_article_by_slug(string $slug, string $lang_code): ?array {
    $articles = read_json_file(ARTICLES_FILE);

    foreach ($articles as $article) {
        if ($article['slug'] === $slug && $article['lang_code'] === $lang_code) {
            return $article;
        }
    }

    return null;
}

/**
 * Ruft einen Artikel anhand seiner ID ab.
 */
function get_article_by_id(int $id): ?array {
    $articles = read_json_file(ARTICLES_FILE);

    foreach ($articles as $article) {
        if ($article['id'] === $id) {
            return $article;
        }
    }

    return null;
}

/**
 * Generiert einen URL-freundlichen Slug aus einem Titel.
 */
function generate_slug(string $title): string {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    return $slug;
}

/**
 * Generiert einen einzigartigen Slug für einen Artikel.
 */
function generate_unique_slug(string $title, string $lang_code): string {
    $base_slug = generate_slug($title);
    $slug = $base_slug;
    $counter = 1;

    $articles = read_json_file(ARTICLES_FILE);

    while (true) {
        $exists = false;
        foreach ($articles as $article) {
            if ($article['slug'] === $slug && $article['lang_code'] === $lang_code) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            break;
        }

        $slug = $base_slug . '-' . $counter++;
    }

    return $slug;
}

/**
 * Behandelt den Bild-Upload für CKEditor 5.
 */
function handle_ckeditor_image_upload(): array {
    if (!isset($_FILES['upload'])) {
        return ['error' => ['message' => __('ckeditor_upload_no_file')]];
    }

    $file = $_FILES['upload'];

    // Validierung des Uploads
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => ['message' => __('ckeditor_upload_error_code', ['code' => $file['error']])]];
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['error' => ['message' => __('ckeditor_upload_size_exceeded', ['max_size' => round(MAX_UPLOAD_SIZE / (1024 * 1024)) . 'MB'])]];
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        return ['error' => ['message' => __('ckeditor_upload_invalid_type')]];
    }

    // Zielverzeichnis erstellen, falls nicht vorhanden
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0775, true);
    }

    // Eindeutigen Dateinamen generieren
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;
    $public_path = '/public/uploads/images/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['url' => $public_path];
    } else {
        return ['error' => ['message' => __('ckeditor_upload_move_failed')]];
    }
}

// =====================================================
// LANGUAGE MANAGEMENT FUNCTIONS (Admin)
// =====================================================

/**
 * Ruft alle unterstützten Sprachen ab.
 */
function get_all_supported_languages(): array {
    $languages = read_json_file(SUPPORTED_LANGUAGES_FILE);
    return $languages;
}

/**
 * Ruft alle Sprachstrings für eine Sprache ab.
 */
function get_all_language_strings_for_lang(string $lang_code): array {
    $lang_file = LANGUAGE_FILES_DIR . $lang_code . '.php';

    if (!file_exists($lang_file)) {
        return [];
    }

    $strings = require $lang_file;

    // Convert to format expected by admin (with IDs)
    $result = [];
    $id = 1;
    foreach ($strings as $key => $value) {
        $result[] = [
            'id' => $id++,
            'lang_key' => $key,
            'lang_code' => $lang_code,
            'value' => $value
        ];
    }

    return $result;
}

/**
 * Fügt einen neuen Sprachstring hinzu.
 */
function add_language_string(string $lang_key, string $lang_code, string $value): bool {
    $lang_file = LANGUAGE_FILES_DIR . $lang_code . '.php';

    // Lade existierende Strings
    $strings = [];
    if (file_exists($lang_file)) {
        $strings = require $lang_file;
    }

    // Füge neuen String hinzu
    $strings[$lang_key] = $value;

    // Sortiere alphabetisch
    ksort($strings);

    // Schreibe zurück
    return write_language_file($lang_file, $strings);
}

/**
 * Aktualisiert einen Sprachstring.
 */
function update_language_string(int $id, string $lang_key, string $lang_code, string $value): bool {
    // Für file-based System ignorieren wir die ID und verwenden nur den Key
    return add_language_string($lang_key, $lang_code, $value);
}

/**
 * Löscht einen Sprachstring.
 */
function delete_language_string(int $id): bool {
    // Für file-based System können wir ohne lang_code und key nicht löschen
    // Diese Funktion wird vom Admin-Panel aufgerufen, aber wir brauchen mehr Kontext
    return false;
}

/**
 * Ruft einen Sprachstring anhand seiner ID ab.
 */
function get_language_string_by_id(int $id): ?array {
    // Für file-based System haben wir keine IDs
    return null;
}

/**
 * Ruft alle eindeutigen Sprachschlüssel ab.
 */
function get_all_unique_language_keys(): array {
    $supported_langs = get_supported_lang_codes();
    $all_keys = [];

    foreach ($supported_langs as $lang_code) {
        $lang_file = LANGUAGE_FILES_DIR . $lang_code . '.php';
        if (file_exists($lang_file)) {
            $strings = require $lang_file;
            $all_keys = array_merge($all_keys, array_keys($strings));
        }
    }

    return array_unique($all_keys);
}

/**
 * Schreibt Sprachstrings in eine PHP-Datei.
 */
function write_language_file(string $filepath, array $strings): bool {
    $dir = dirname($filepath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $php_content = "<?php\n// Language file - Auto-generated\nreturn [\n";

    foreach ($strings as $key => $value) {
        $escaped_key = addslashes($key);
        $escaped_value = addslashes($value);
        $php_content .= "    '{$escaped_key}' => '{$escaped_value}',\n";
    }

    $php_content .= "];\n";

    return file_put_contents($filepath, $php_content) !== false;
}

// =====================================================
// INITIALIZATION
// =====================================================

// Lade die globalen Einstellungen beim Start
load_global_settings();

// Lade die Sprachstrings für DEFAULT_LANG (wird in index.php überschrieben)
load_language_strings_from_db(DEFAULT_LANG);
