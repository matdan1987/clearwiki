<?php
// functions.php - Zentrale Funktionen und Logik

// Konfiguration laden
if (!function_exists('get_db_connection')) {
    require_once __DIR__ . '/config.php';
}


/**
 * Stellt eine Verbindung zur Datenbank her und gibt das PDO-Objekt zurück.
 * Bei einem Fehler wird eine Exception geworfen oder ein Fehler gemeldet.
 *
 * @return PDO Die PDO-Datenbankverbindung.
 * @throws PDOException Wenn die Verbindung fehlschlägt.
 */
if (!function_exists('get_db_connection')) {
    function get_db_connection(): PDO {
        static $pdo = null;

        if ($pdo === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                die("Database connection failed. Please try again later. (Error: " . $e->getMessage() . ")");
            }
        }
        return $pdo;
    }
}

/**
 * Sanitisiert eine Zeichenkette, um XSS-Angriffe zu verhindern.
 * Wandelt spezielle Zeichen in HTML-Entitäten um.
 *
 * @param string|null $string Die zu sanierende Zeichenkette.
 * @return string Die sanierte Zeichenkette.
 */
if (!function_exists('sanitize_output')) {
    function sanitize_output(?string $string): string {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Leitet den Benutzer auf eine andere Seite um.
 * Beendet die Skriptausführung nach der Umleitung.
 *
 * @param string $location Die URL, zu der umgeleitet werden soll.
 */
if (!function_exists('redirect')) {
    function redirect(string $location): void {
        header("Location: " . $location);
        exit();
    }
}

/**
 * Lädt eine View-Datei.
 * Daten können als assoziatives Array übergeben werden und werden in der View als Variablen verfügbar sein.
 *
 * @param string $view_name Der Name der View-Datei (ohne .php).
 * @param array $data Ein optionales Array von Daten, die an die View übergeben werden sollen.
 */
if (!function_exists('load_view')) {
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
}

/**
 * Startet eine PHP-Session, falls noch keine gestartet wurde.
 */
if (!function_exists('start_session_if_not_started')) {
    function start_session_if_not_started(): void {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}

start_session_if_not_started();

// --- Funktionen für die Mehrsprachigkeit (aus der Datenbank) ---
$_LANG = [];
$_SUPPORTED_LANG_CODES = [];

/**
 * Ruft alle aktiven unterstützten Sprachen aus der Datenbank ab.
 * @return array Ein Array von Sprachcodes (z.B. ['de', 'en', 'fr']).
 */
if (!function_exists('get_supported_lang_codes')) {
    function get_supported_lang_codes(): array {
        global $_SUPPORTED_LANG_CODES;
        if (empty($_SUPPORTED_LANG_CODES)) {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->query("SELECT lang_code FROM supported_languages WHERE is_active = TRUE ORDER BY lang_name ASC");
                $_SUPPORTED_LANG_CODES = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            } catch (PDOException $e) {
                error_log("Failed to load supported languages: " . $e->getMessage());
                $_SUPPORTED_LANG_CODES = ['de', 'en', 'fr'];
            }
        }
        return $_SUPPORTED_LANG_CODES;
    }
}

/**
 * Lädt die Sprachstrings für die gegebene Sprache aus der Datenbank.
 * @param string $lang_code Der Sprachcode (z.B. 'de', 'en').
 */
if (!function_exists('load_language_strings_from_db')) {
    function load_language_strings_from_db(string $lang_code): void {
        global $_LANG;
        $_LANG = [];

        try {
            $pdo = get_db_connection();
            $stmt = $pdo->prepare("SELECT lang_key, value FROM language_strings WHERE lang_code = :lang_code");
            $stmt->execute([':lang_code' => $lang_code]);
            $strings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($strings as $string) {
                $_LANG[$string['lang_key']] = $string['value'];
            }
        } catch (PDOException $e) {
            error_log("Failed to load language strings for " . $lang_code . ": " . $e->getMessage());
            $_LANG = [];
        }
    }
}

/**
 * Übersetzt einen Sprachschlüssel.
 * @param string|null $key Der Schlüssel des zu übersetzenden Textes.
 * @param array $placeholders Optionale Platzhalter, die ersetzt werden sollen.
 * @return string Der übersetzte Text.
 */
if (!function_exists('__')) {
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
}

// --- CRUD-Funktionen für Sprachstrings ---

/**
 * Ruft alle Sprachstrings für eine bestimmte Sprache ab.
 * @param string $lang_code Der Sprachcode.
 * @return array Ein Array von Sprachstrings.
 */
if (!function_exists('get_all_language_strings_for_lang')) {
    function get_all_language_strings_for_lang(string $lang_code): array {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT id, lang_key, value FROM language_strings WHERE lang_code = :lang_code ORDER BY lang_key ASC");
        $stmt->execute([':lang_code' => $lang_code]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Ruft einen einzelnen Sprachstring anhand seiner ID ab.
 * @param int $id Die ID des Strings.
 * @return array|null Der String als assoziatives Array oder null.
 */
if (!function_exists('get_language_string_by_id')) {
    function get_language_string_by_id(int $id): ?array {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM language_strings WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

/**
 * Fügt einen neuen Sprachstring hinzu.
 * @param string $lang_key Der Schlüssel.
 * @param string $lang_code Der Sprachcode.
 * @param string $value Der Wert.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('add_language_string')) {
    function add_language_string(string $lang_key, string $lang_code, string $value): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("INSERT INTO language_strings (lang_key, lang_code, value, created_at, updated_at) VALUES (:lang_key, :lang_code, :value, NOW(), NOW())");
            return $stmt->execute([
                ':lang_key' => $lang_key,
                ':lang_code' => $lang_code,
                ':value' => $value
            ]);
        } catch (PDOException $e) {
            error_log("Error adding language string: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Aktualisiert einen Sprachstring.
 * @param int $id Die ID des Strings.
 * @param string $lang_key Der neue Schlüssel.
 * @param string $lang_code Der neue Sprachcode.
 * @param string $value Der neue Wert.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('update_language_string')) {
    function update_language_string(int $id, string $lang_key, string $lang_code, string $value): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("UPDATE language_strings SET lang_key = :lang_key, lang_code = :lang_code, value = :value, updated_at = NOW() WHERE id = :id");
            return $stmt->execute([
                ':lang_key' => $lang_key,
                ':lang_code' => $lang_code,
                ':value' => $value,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating language string: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Löscht einen Sprachstring.
 * @param int $id Die ID des Strings.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('delete_language_string')) {
    function delete_language_string(int $id): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("DELETE FROM language_strings WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting language string: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Ruft alle einzigartigen Sprachschlüssel (lang_key) aus der Datenbank ab.
 * Nützlich, um beim Hinzufügen einer neuen Sprache alle bestehenden Schlüssel zu initialisieren.
 * @return array Ein Array von einzigartigen lang_key-Strings.
 */
if (!function_exists('get_all_unique_language_keys')) {
    function get_all_unique_language_keys(): array {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT DISTINCT lang_key FROM language_strings ORDER BY lang_key ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}


// --- CRUD-Funktionen für unterstützte Sprachen (supported_languages) ---

/**
 * Ruft alle unterstützten Sprachen ab (aktiv und inaktiv).
 * @return array Ein Array von Sprachen.
 */
if (!function_exists('get_all_supported_languages')) {
    function get_all_supported_languages(): array {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT id, lang_code, lang_name, is_active FROM supported_languages ORDER BY lang_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Ruft eine einzelne unterstützte Sprache anhand ihrer ID ab.
 * @param int $id Die ID der Sprache.
 * @return array|null Die Sprache als assoziatives Array oder null.
 */
if (!function_exists('get_supported_language_by_id')) {
    function get_supported_language_by_id(int $id): ?array {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM supported_languages WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

/**
 * Fügt eine neue unterstützte Sprache hinzu.
 * @param string $lang_code Der Sprachcode.
 * @param string $lang_name Der Anzeigename.
 * @param bool $is_active Ob die Sprache aktiv ist.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('add_supported_language')) {
    function add_supported_language(string $lang_code, string $lang_name, bool $is_active): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("INSERT INTO supported_languages (lang_code, lang_name, is_active, created_at, updated_at) VALUES (:lang_code, :lang_name, :is_active, NOW(), NOW())");
            return $stmt->execute([
                ':lang_code' => $lang_code,
                ':lang_name' => $lang_name,
                ':is_active' => $is_active ? 1 : 0
            ]);
        } catch (PDOException $e) {
            error_log("Error adding supported language: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Aktualisiert eine unterstützte Sprache.
 * @param int $id Die ID der Sprache.
 * @param string $lang_code Der neue Sprachcode.
 * @param string $lang_name Der neue Anzeigename.
 * @param bool $is_active Ob die Sprache aktiv ist.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('update_supported_language')) {
    function update_supported_language(int $id, string $lang_code, string $lang_name, bool $is_active): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("UPDATE supported_languages SET lang_code = :lang_code, lang_name = :lang_name, is_active = :is_active, updated_at = NOW() WHERE id = :id");
            return $stmt->execute([
                ':lang_code' => $lang_code,
                ':lang_name' => $lang_name,
                ':is_active' => $is_active ? 1 : 0,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating supported language: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Löscht eine unterstützte Sprache.
 * @param int $id Die ID der Sprache.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('delete_supported_language')) {
    function delete_supported_language(int $id): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("DELETE FROM supported_languages WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting supported language: " . $e->getMessage());
            return false;
        }
    }
}


// --- Authentifizierungs- und Benutzerfunktionen ---

/**
 * Registriert einen neuen Benutzer in der Datenbank.
 *
 * @param string $username Der Benutzername.
 * @param string $email Die E-Mail-Adresse.
 * @param string $password Das Klartext-Passwort.
 * @param string $role Die Rolle des Benutzers ('registered', 'moderator', 'admin').
 * @return bool True bei Erfolg, False bei Fehler (z.B. Benutzername/E-Mail existiert bereits).
 */
if (!function_exists('register_user')) {
    function register_user(string $username, string $email, string $password, string $role = 'registered'): bool {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetchColumn() > 0) { return false; }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at, updated_at) VALUES (:username, :email, :password, :role, NOW(), NOW())");
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed_password,
                ':role' => $role
            ]);
        } catch (PDOException $e) { error_log("Error registering user: " . $e->getMessage()); return false; }
    }
}

/**
 * Meldet einen Benutzer an, indem die Anmeldeinformationen überprüft werden.
 * Bei Erfolg wird die Benutzer-ID und Rolle in der Session gespeichert.
 *
 * @param string $identifier Benutzername oder E-Mail.
 * @param string $password Das Klartext-Passwort.
 * @return bool True bei erfolgreichem Login, False sonst.
 */
if (!function_exists('login_user')) {
    function login_user(string $identifier, string $password): bool {
        $pdo = get_db_connection();

        $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = :username_param OR email = :email_param");
        $stmt->execute([
            ':username_param' => $identifier,
            ':email_param' => $identifier
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            session_regenerate_id(true);
            return true;
        }
        return false;
    }
}

/**
 * Gibt den aktuell angemeldeten Benutzer zurück.
 * @return array|null Das Benutzer-Array oder null, wenn nicht angemeldet.
 */
if (!function_exists('get_current_user')) {
    function get_current_user(): ?array {
        // Überprüfe, ob die user_id in der Session gesetzt ist und nicht null ist
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null) {
            // Wenn der Benutzername oder die Rolle nicht gesetzt ist, lade sie aus der DB
            // Dies kann passieren, wenn die Session erneuert wird oder nur die ID gesetzt ist
            if (!isset($_SESSION['username']) || !isset($_SESSION['user_role'])) {
                // Lade Benutzerdaten aus der Datenbank, um die Session zu vervollständigen
                $user_data = get_user_by_id((int)$_SESSION['user_id']);
                if ($user_data) {
                    // Session-Variablen aktualisieren
                    $_SESSION['username'] = $user_data['username'];
                    $_SESSION['user_role'] = $user_data['role'];
                    return [
                        'id' => (int)$_SESSION['user_id'],
                        'username' => $_SESSION['username'],
                        'role' => $_SESSION['user_role']
                    ];
                } else {
                    // Benutzer nicht in DB gefunden, Session ungültig machen
                    session_unset();
                    session_destroy();
                    return null;
                }
            }
            // Wenn alle Daten in der Session sind, gib sie zurück
            return [
                'id' => (int)$_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['user_role']
            ];
        }
        // Wenn user_id nicht gesetzt ist, ist niemand angemeldet
        return null;
    }
}

/**
 * Überprüft, ob ein Benutzer angemeldet ist.
 * @return bool True, wenn angemeldet, sonst False.
 */
if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool {
        // Ruft get_current_user auf, um sicherzustellen, dass die Session-Daten vollständig sind
        return get_current_user() !== null;
    }
}

/**
 * Gibt die Rolle des aktuell angemeldeten Benutzers zurück.
 * @return string|null Die Rolle ('registered', 'moderator', 'admin') oder null, wenn nicht angemeldet.
 */
if (!function_exists('get_user_role')) {
    function get_user_role(): ?string {
        $user = get_current_user();
        return $user['role'] ?? null;
    }
}

/**
 * Überprüft, ob der angemeldete Benutzer ein Admin ist.
 * @return bool
 */
if (!function_exists('is_admin')) {
    function is_admin(): bool { return get_user_role() === 'admin'; }
}

/**
 * Überprüft, ob der angemeldete Benutzer ein Moderator oder Admin ist.
 * @return bool
 */
if (!function_exists('is_moderator_or_admin')) {
    function is_moderator_or_admin(): bool {
        $role = get_user_role();
        return $role === 'moderator' || $role === 'admin';
    }
}

/**
 * Ruft Benutzerdaten anhand der ID ab.
 * @param int $user_id Die ID des Benutzers.
 * @return array|null Das Benutzer-Array oder null, wenn nicht gefunden.
 */
if (!function_exists('get_user_by_id')) {
    function get_user_by_id(int $user_id): ?array {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT id, username, email, role, alliance_id, is_leader FROM users WHERE id = :id");
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

/**
 * Ruft Benutzerdaten anhand von Benutzername oder E-Mail ab.
 * @param string $identifier Benutzername oder E-Mail.
 * @return array|null Das Benutzer-Array oder null, wenn nicht gefunden.
 */
if (!function_exists('get_user_by_username_or_email')) {
    function get_user_by_username_or_email(string $identifier): ?array {
        $pdo = get_db_connection(); $stmt = $pdo->prepare("SELECT id, username, email, role, alliance_id, is_leader FROM users WHERE username = :identifier OR email = :identifier"); $stmt->execute([':identifier' => $identifier]); return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// --- ARTIKELVERWALTUNG FUNKTIONEN ---

/**
 * Erstellt einen neuen Artikel.
 *
 * @param string $title
 * @param string $content
 * @param int|null $author_user_id
 * @param string|null $author_guest_name
 * @param string|null $author_guest_email
 * @param string $status
 * @param string $lang_code
 * @return int|false Die ID des neuen Artikels bei Erfolg, sonst false.
 */
if (!function_exists('create_article')) {
    function create_article(
        string $title,
        string $content,
        ?int $author_user_id,
        ?string $author_guest_name,
        ?string $author_guest_email,
        string $status,
        string $lang_code
    ): int|false {
        $pdo = get_db_connection();
        $slug = generate_unique_slug($title, $lang_code);

        try {
            $stmt = $pdo->prepare("INSERT INTO articles (title, slug, content, author_user_id, author_guest_name, author_guest_email, status, lang_code, created_at, updated_at, published_at) VALUES (:title, :slug, :content, :author_user_id, :author_guest_name, :author_guest_email, :status, :lang_code, NOW(), NOW(), :published_at)");
            
            $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;

            $stmt->execute([
                ':title' => $title,
                ':slug' => $slug,
                ':content' => $content,
                ':author_user_id' => $author_user_id,
                ':author_guest_name' => $author_guest_name,
                ':author_guest_email' => $author_guest_email,
                ':status' => $status,
                ':lang_code' => $lang_code,
                ':published_at' => $published_at
            ]);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating article: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Aktualisiert einen bestehenden Artikel.
 *
 * @param int $id
 * @param string $title
 * @param string $content
 * @param string $status
 * @param string $lang_code
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('update_article')) {
    function update_article(
        int $id,
        string $title,
        string $content,
        string $status,
        string $lang_code
    ): bool {
        $pdo = get_db_connection();
        // Slug wird bei Update nicht geändert, da er Unique Key ist.
        // Wenn Titel geändert wird und Slug auch geändert werden soll, müsste dies separat behandelt werden.

        try {
            $stmt = $pdo->prepare("UPDATE articles SET title = :title, content = :content, status = :status, lang_code = :lang_code, updated_at = NOW(), published_at = CASE WHEN status = 'pending' AND :new_status = 'published' THEN NOW() ELSE published_at END WHERE id = :id");
            
            return $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':status' => $status,
                ':new_status' => $status, // Für CASE-Statement
                ':lang_code' => $lang_code,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating article: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Löscht einen Artikel anhand seiner ID.
 *
 * @param int $id
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('delete_article')) {
    function delete_article(int $id): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting article: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Ruft einen Artikel anhand seines Slugs und Sprachcodes ab.
 *
 * @param string $slug
 * @param string $lang_code
 * @return array|null Artikeldaten oder null, wenn nicht gefunden.
 */
if (!function_exists('get_article_by_slug')) {
    function get_article_by_slug(string $slug, string $lang_code): ?array {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE slug = :slug AND lang_code = :lang_code");
        $stmt->execute([':slug' => $slug, ':lang_code' => $lang_code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

/**
 * Ruft einen Artikel anhand seiner ID ab.
 *
 * @param int $id
 * @return array|null Artikeldaten oder null, wenn nicht gefunden.
 */
if (!function_exists('get_article_by_id')) {
    function get_article_by_id(int $id): ?array {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

/**
 * Generiert einen URL-freundlichen Slug aus einem Titel.
 *
 * @param string $title
 * @return string Der generierte Slug.
 */
if (!function_exists('generate_slug')) {
    function generate_slug(string $title): string {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        return $slug;
    }
}

/**
 * Generiert einen einzigartigen Slug für einen Artikel, indem ein Suffix hinzugefügt wird,
 * falls der Slug bereits existiert.
 *
 * @param string $title
 * @param string $lang_code
 * @return string Der einzigartige Slug.
 */
if (!function_exists('generate_unique_slug')) {
    function generate_unique_slug(string $title, string $lang_code): string {
        $base_slug = generate_slug($title);
        $slug = $base_slug;
        $counter = 1;
        $pdo = get_db_connection();

        while (true) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug AND lang_code = :lang_code");
            $stmt->execute([':slug' => $slug, ':lang_code' => $lang_code]);
            if ($stmt->fetchColumn() == 0) {
                break; // Slug ist einzigartig
            }
            $slug = $base_slug . '-' . $counter++;
        }
        return $slug;
    }
}

/**
 * Behandelt den Bild-Upload für CKEditor 5.
 * Erwartet eine Datei im $_FILES['upload'] und gibt eine JSON-Antwort zurück.
 *
 * @return array Ein assoziatives Array mit 'url' bei Erfolg oder 'error' bei Fehler.
 */
if (!function_exists('handle_ckeditor_image_upload')) {
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
        $public_path = '/public/uploads/images/' . $filename; // Relativer Pfad für den Browser

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['url' => $public_path];
        } else {
            return ['error' => ['message' => __('ckeditor_upload_move_failed')]];
        }
    }
}


// --- Globale Einstellungen und Feature-Toggles ---
$_SETTINGS = [];

/**
 * Lädt die globalen Einstellungen aus der Datenbank.
 */
if (!function_exists('load_global_settings')) {
    function load_global_settings(): void {
        global $_SETTINGS;
        try {
            $pdo = get_db_connection(); $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1"); $_SETTINGS = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$_SETTINGS) {
                $_SETTINGS = [
                    'id' => 1, 'wiki_name' => 'ClearWiki', 'wiki_slogan' => 'Next Generation Gaming Guide',
                    'display_wiki_name' => true, 'display_slogan' => true, 'display_logo' => true,
                    'logo_height_px' => 40, 'logo_path' => null, 'favicon_path' => null,
                    'footer_text' => '© ' . date('Y') . ' ClearWiki - Entwickelt mit ❤️ von Daniel Mattick.',
                    'impressum_content' => 'Inhalt des Impressums hier. Bitte im Admin-Panel anpassen.', 'maintenance_mode' => false,
                    'enable_articles' => true, 'enable_categories' => true, 'enable_users' => true,
                    'enable_alliances' => true, 'enable_registration' => true, 'enable_pending_contributions' => true,
                    'enable_impressum' => true, 'enable_send_alliance_mail' => true,
                    'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
                ];
                $insert_stmt = $pdo->prepare("INSERT IGNORE INTO settings (id, wiki_name, wiki_slogan, display_wiki_name, display_slogan, display_logo, logo_height_px, logo_path, favicon_path, footer_text, impressum_content, maintenance_mode, enable_articles, enable_categories, enable_users, enable_alliances, enable_registration, enable_pending_contributions, enable_impressum, enable_send_alliance_mail, created_at, updated_at) VALUES (:id, :wiki_name, :wiki_slogan, :display_wiki_name, :display_slogan, :display_logo, :logo_height_px, :logo_path, :favicon_path, :footer_text, :impressum_content, :maintenance_mode, :enable_articles, :enable_categories, :enable_users, :enable_alliances, :enable_registration, :enable_pending_contributions, :enable_impressum, :enable_send_alliance_mail, NOW(), NOW())");
                $insert_stmt->execute(array_intersect_key($_SETTINGS, array_flip([
                    'id', 'wiki_name', 'wiki_slogan', 'display_wiki_name', 'display_slogan', 'display_logo',
                    'logo_height_px', 'logo_path', 'favicon_path', 'footer_text', 'impressum_content',
                    'maintenance_mode', 'enable_articles', 'enable_categories', 'enable_users',
                    'enable_alliances', 'enable_registration', 'enable_pending_contributions',
                    'enable_impressum', 'enable_send_alliance_mail'
                ])));
            }
        } catch (PDOException $e) { error_log("Failed to load settings: " . $e->getMessage());
            $_SETTINGS = [
                'id' => 1, 'wiki_name' => 'ClearWiki', 'wiki_slogan' => 'Next Generation Gaming Guide',
                'display_wiki_name' => true, 'display_slogan' => true, 'display_logo' => true,
                'logo_height_px' => 40, 'logo_path' => null, 'favicon_path' => null,
                'footer_text' => '© ' . date('Y') . ' ClearWiki - Entwickelt mit ❤️ von Daniel Mattick.',
                'impressum_content' => 'Inhalt des Impressums hier. Bitte im Admin-Panel anpassen.', 'maintenance_mode' => false,
                'enable_articles' => true, 'enable_categories' => true, 'enable_users' => true,
                'enable_alliances' => true, 'enable_registration' => true, 'enable_pending_contributions' => true,
                'enable_impressum' => true, 'enable_send_alliance_mail' => true,
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ];
        }
    }
}

/**
 * Überprüft, ob ein bestimmtes Feature aktiv ist.
 * @param string $feature_key Der Schlüssel des Features (z.B. 'enable_articles').
 * @return bool True, wenn das Feature aktiv ist, sonst False.
 */
if (!function_exists('is_feature_enabled')) {
    function is_feature_enabled(string $feature_key): bool {
        global $_SETTINGS;
        if (is_admin() && $feature_key !== 'maintenance_mode') { return true; }
        return (bool)($_SETTINGS[$feature_key] ?? false);
    }
}

// --- Funktionen für Footer-Links ---

/**
 * Ruft alle aktiven Footer-Links ab, sortiert nach Sektion und Reihenfolge.
 * @return array Ein assoziatives Array von Links, gruppiert nach Sektion.
 */
if (!function_exists('get_footer_links')) {
    function get_footer_links(): array {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT text_key, url, section, is_active FROM footer_links WHERE is_active = TRUE ORDER BY section, order_by ASC");
        $raw_links = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped_links = [
            'wiki' => [],
            'community' => [],
            'legal' => []
        ];

        foreach ($raw_links as $link) {
            if (isset($grouped_links[$link['section']])) {
                $grouped_links[$link['section']][] = $link;
            }
        }
        return $grouped_links;
    }
}

/**
 * Ruft einen spezifischen Footer-Link anhand seiner ID ab.
 * @param int $id Die ID des Footer-Links.
 * @return array|null Der Link als assoziatives Array oder null, wenn nicht gefunden.
 */
if (!function_exists('get_footer_link_by_id')) {
    function get_footer_link_by_id(int $id): ?array {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM footer_links WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

/**
 * Fügt einen neuen Footer-Link hinzu.
 * @param string $text_key Der Sprachschlüssel für den Linktext.
 * @param string $url Die URL des Links.
 * @param string $section Die Sektion ('wiki', 'community', 'legal').
 * @param int $order_by Die Reihenfolge.
 * @param bool $is_active Ob der Link aktiv ist.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('add_footer_link')) {
    function add_footer_link(string $text_key, string $url, string $section, int $order_by, bool $is_active): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("INSERT INTO footer_links (text_key, url, section, order_by, is_active, created_at, updated_at) VALUES (:text_key, :url, :section, :order_by, :is_active, NOW(), NOW())");
            return $stmt->execute([
                ':text_key' => $text_key,
                ':url' => $url,
                ':section' => $section,
                ':order_by' => $order_by,
                ':is_active' => $is_active ? 1 : 0
            ]);
        } catch (PDOException $e) {
            error_log("Error adding footer link: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Aktualisiert einen bestehenden Footer-Link.
 * @param int $id Die ID des Links.
 * @param string $text_key Der neue Sprachschlüssel.
 * @param string $url Die neue URL.
 * @param string $section Die neue Sektion.
 * @param int $order_by Die neue Reihenfolge.
 * @param bool $is_active Ob der Link aktiv ist.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('update_footer_link')) {
    function update_footer_link(int $id, string $text_key, string $url, string $section, int $order_by, bool $is_active): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("UPDATE footer_links SET text_key = :text_key, url = :url, section = :section, order_by = :order_by, is_active = :is_active, updated_at = NOW() WHERE id = :id");
            return $stmt->execute([
                ':text_key' => $text_key,
                ':url' => $url,
                ':section' => $section,
                ':order_by' => $order_by,
                ':is_active' => $is_active ? 1 : 0,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating footer link: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Löscht einen Footer-Link anhand seiner ID.
 * @param int $id Die ID des Links.
 * @return bool True bei Erfolg, False bei Fehler.
 */
if (!function_exists('delete_footer_link')) {
    function delete_footer_link(int $id): bool {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->prepare("DELETE FROM footer_links WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting footer link: " . $e->getMessage());
            return false;
        }
    }
}


// Lade die globalen Einstellungen beim Start der functions.php
load_global_settings();

// Lade die Sprachstrings für die aktuelle Sprache aus der Datenbank
// WICHTIG: Dies ersetzt das frühere load_language_file(DEFAULT_LANG);
// Die Variable $current_lang wird im index.php gesetzt und ist hier noch nicht verfügbar,
// daher muss der Aufruf von load_language_strings_from_db in index.php erfolgen,
// nachdem $current_lang bestimmt wurde.
// Hier laden wir nur einen initialen Satz von Strings, falls __ vor dem Haupt-Load aufgerufen wird.
load_language_strings_from_db(DEFAULT_LANG);
