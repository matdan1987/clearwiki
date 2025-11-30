-- ClearWiki Database Schema
-- Erstellt: 2025-11-30
-- Beschreibung: Vollständiges Datenbankschema für ClearWiki

-- Datenbank erstellen (falls nicht vorhanden)
CREATE DATABASE IF NOT EXISTS clearwiki DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE clearwiki;

-- =====================================================
-- TABELLE: users
-- Speichert Benutzerkonten mit Rollen und Allianz-Zuordnung
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('registered', 'moderator', 'admin') DEFAULT 'registered' NOT NULL,
    alliance_id INT DEFAULT NULL,
    is_leader BOOLEAN DEFAULT FALSE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_alliance (alliance_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: supported_languages
-- Speichert die unterstützten Sprachen des Wikis
-- =====================================================
CREATE TABLE IF NOT EXISTS supported_languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lang_code VARCHAR(10) NOT NULL UNIQUE,
    lang_name VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_lang_code (lang_code),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: language_strings
-- Speichert alle Übersetzungsstrings für die Mehrsprachigkeit
-- =====================================================
CREATE TABLE IF NOT EXISTS language_strings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lang_key VARCHAR(100) NOT NULL,
    lang_code VARCHAR(10) NOT NULL,
    value TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    UNIQUE KEY unique_lang_key_code (lang_key, lang_code),
    INDEX idx_lang_code (lang_code),
    INDEX idx_lang_key (lang_key),
    FOREIGN KEY (lang_code) REFERENCES supported_languages(lang_code) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: articles
-- Speichert Wiki-Artikel mit Mehrsprachigkeit
-- =====================================================
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    author_user_id INT DEFAULT NULL,
    author_guest_name VARCHAR(100) DEFAULT NULL,
    author_guest_email VARCHAR(100) DEFAULT NULL,
    status ENUM('pending', 'published', 'archived') DEFAULT 'pending' NOT NULL,
    lang_code VARCHAR(10) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    published_at DATETIME DEFAULT NULL,
    UNIQUE KEY unique_slug_lang (slug, lang_code),
    INDEX idx_status (status),
    INDEX idx_lang_code (lang_code),
    INDEX idx_author_user (author_user_id),
    INDEX idx_published_at (published_at),
    FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (lang_code) REFERENCES supported_languages(lang_code) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: categories
-- Speichert Kategorien für Artikel (optional, für zukünftige Erweiterung)
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    parent_id INT DEFAULT NULL,
    lang_code VARCHAR(10) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id),
    INDEX idx_lang_code (lang_code),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (lang_code) REFERENCES supported_languages(lang_code) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: article_categories
-- Verknüpfungstabelle zwischen Artikeln und Kategorien
-- =====================================================
CREATE TABLE IF NOT EXISTS article_categories (
    article_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: alliances
-- Speichert Allianzen/Gilden (optional)
-- =====================================================
CREATE TABLE IF NOT EXISTS alliances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    tag VARCHAR(10) NOT NULL UNIQUE,
    description TEXT,
    leader_user_id INT DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_name (name),
    INDEX idx_tag (tag),
    FOREIGN KEY (leader_user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: settings
-- Speichert globale Wiki-Einstellungen und Feature-Toggles
-- =====================================================
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY DEFAULT 1,
    wiki_name VARCHAR(100) DEFAULT 'ClearWiki',
    wiki_slogan VARCHAR(255) DEFAULT 'Next Generation Gaming Guide',
    display_wiki_name BOOLEAN DEFAULT TRUE,
    display_slogan BOOLEAN DEFAULT TRUE,
    display_logo BOOLEAN DEFAULT TRUE,
    logo_height_px INT DEFAULT 40,
    logo_path VARCHAR(255) DEFAULT NULL,
    favicon_path VARCHAR(255) DEFAULT NULL,
    footer_text TEXT,
    impressum_content TEXT,
    maintenance_mode BOOLEAN DEFAULT FALSE,
    enable_articles BOOLEAN DEFAULT TRUE,
    enable_categories BOOLEAN DEFAULT TRUE,
    enable_users BOOLEAN DEFAULT TRUE,
    enable_alliances BOOLEAN DEFAULT TRUE,
    enable_registration BOOLEAN DEFAULT TRUE,
    enable_pending_contributions BOOLEAN DEFAULT TRUE,
    enable_impressum BOOLEAN DEFAULT TRUE,
    enable_send_alliance_mail BOOLEAN DEFAULT TRUE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CHECK (id = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE: footer_links
-- Speichert Links für den Footer
-- =====================================================
CREATE TABLE IF NOT EXISTS footer_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text_key VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    section ENUM('wiki', 'community', 'legal') NOT NULL,
    order_by INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_section (section),
    INDEX idx_is_active (is_active),
    INDEX idx_order (order_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INITIAL-DATEN: Unterstützte Sprachen
-- =====================================================
INSERT IGNORE INTO supported_languages (lang_code, lang_name, is_active, created_at, updated_at) VALUES
('de', 'Deutsch', TRUE, NOW(), NOW()),
('en', 'English', TRUE, NOW(), NOW()),
('fr', 'Français', TRUE, NOW(), NOW());

-- =====================================================
-- INITIAL-DATEN: Basis-Sprachstrings für Deutsch
-- =====================================================
INSERT IGNORE INTO language_strings (lang_key, lang_code, value, created_at, updated_at) VALUES
-- Allgemeine Strings
('welcome_title', 'de', 'Willkommen bei ClearWiki', NOW(), NOW()),
('demo_version_suffix', 'de', 'Demo-Version', NOW(), NOW()),
('wiki_index_title', 'de', 'Wiki-Index', NOW(), NOW()),
('home', 'de', 'Startseite', NOW(), NOW()),
('articles', 'de', 'Artikel', NOW(), NOW()),
('categories', 'de', 'Kategorien', NOW(), NOW()),
('users', 'de', 'Benutzer', NOW(), NOW()),
('login', 'de', 'Anmelden', NOW(), NOW()),
('logout', 'de', 'Abmelden', NOW(), NOW()),
('register', 'de', 'Registrieren', NOW(), NOW()),
('admin', 'de', 'Administration', NOW(), NOW()),

-- Login/Register
('login_title', 'de', 'Anmeldung', NOW(), NOW()),
('register_title', 'de', 'Registrierung', NOW(), NOW()),
('login_error_empty_fields', 'de', 'Bitte füllen Sie alle Felder aus.', NOW(), NOW()),
('login_error_invalid_credentials', 'de', 'Ungültige Anmeldedaten.', NOW(), NOW()),
('registration_error_empty_fields', 'de', 'Bitte füllen Sie alle Felder aus.', NOW(), NOW()),
('registration_error_invalid_email', 'de', 'Ungültige E-Mail-Adresse.', NOW(), NOW()),
('registration_error_password_mismatch', 'de', 'Passwörter stimmen nicht überein.', NOW(), NOW()),
('registration_error_password_too_short', 'de', 'Passwort muss mindestens 8 Zeichen lang sein.', NOW(), NOW()),
('registration_error_user_exists', 'de', 'Benutzername oder E-Mail bereits vergeben.', NOW(), NOW()),

-- Artikel
('create_article_title', 'de', 'Neuen Artikel erstellen', NOW(), NOW()),
('edit_article_title', 'de', 'Artikel bearbeiten', NOW(), NOW()),
('article_error_empty_fields', 'de', 'Titel und Inhalt dürfen nicht leer sein.', NOW(), NOW()),
('article_error_guest_info_missing', 'de', 'Bitte geben Sie Name und E-Mail an.', NOW(), NOW()),
('article_error_invalid_guest_email', 'de', 'Ungültige E-Mail-Adresse.', NOW(), NOW()),
('article_created_success', 'de', 'Artikel erfolgreich erstellt.', NOW(), NOW()),
('article_updated_success', 'de', 'Artikel erfolgreich aktualisiert.', NOW(), NOW()),
('article_deleted_success', 'de', 'Artikel erfolgreich gelöscht.', NOW(), NOW()),
('article_error_create_failed', 'de', 'Fehler beim Erstellen des Artikels.', NOW(), NOW()),
('article_error_update_failed', 'de', 'Fehler beim Aktualisieren des Artikels.', NOW(), NOW()),
('article_error_delete_failed', 'de', 'Fehler beim Löschen des Artikels.', NOW(), NOW()),
('article_delete_confirm_prompt', 'de', 'Möchten Sie diesen Artikel wirklich löschen?', NOW(), NOW()),

-- CKEditor Upload
('ckeditor_upload_no_file', 'de', 'Keine Datei hochgeladen.', NOW(), NOW()),
('ckeditor_upload_error_code', 'de', 'Upload-Fehler: {code}', NOW(), NOW()),
('ckeditor_upload_size_exceeded', 'de', 'Datei zu groß. Maximal {max_size} erlaubt.', NOW(), NOW()),
('ckeditor_upload_invalid_type', 'de', 'Ungültiger Dateityp. Nur Bilder erlaubt.', NOW(), NOW()),
('ckeditor_upload_move_failed', 'de', 'Fehler beim Speichern der Datei.', NOW(), NOW()),
('ckeditor_upload_not_logged_in', 'de', 'Sie müssen angemeldet sein, um Bilder hochzuladen.', NOW(), NOW()),

-- Admin
('admin_dashboard_title', 'de', 'Admin-Dashboard', NOW(), NOW()),
('admin_settings_title', 'de', 'Einstellungen', NOW(), NOW()),
('admin_users_title', 'de', 'Benutzerverwaltung', NOW(), NOW()),
('admin_categories_title', 'de', 'Kategorienverwaltung', NOW(), NOW()),
('admin_alliances_title', 'de', 'Allianzverwaltung', NOW(), NOW()),
('admin_languages_title', 'de', 'Sprachverwaltung', NOW(), NOW()),
('admin_language_strings_title', 'de', 'Sprachstrings', NOW(), NOW()),
('admin_edit_user_title', 'de', 'Benutzer bearbeiten', NOW(), NOW()),
('admin_edit_category_title', 'de', 'Kategorie bearbeiten', NOW(), NOW()),
('admin_edit_alliance_title', 'de', 'Allianz bearbeiten', NOW(), NOW()),
('admin_edit_language_title', 'de', 'Sprache bearbeiten', NOW(), NOW()),
('admin_add_language_title', 'de', 'Neue Sprache hinzufügen', NOW(), NOW()),
('admin_edit_string_title', 'de', 'Sprachstring bearbeiten', NOW(), NOW()),
('admin_add_string_title', 'de', 'Neuen Sprachstring hinzufügen', NOW(), NOW()),
('admin_send_alliance_mail_title', 'de', 'Rundmail senden', NOW(), NOW()),
('language_added_success', 'de', 'Sprache erfolgreich hinzugefügt.', NOW(), NOW()),
('language_updated_success', 'de', 'Sprache erfolgreich aktualisiert.', NOW(), NOW()),
('string_added_success', 'de', 'Sprachstring erfolgreich hinzugefügt.', NOW(), NOW()),
('string_updated_success', 'de', 'Sprachstring erfolgreich aktualisiert.', NOW(), NOW()),
('error_empty_fields', 'de', 'Bitte füllen Sie alle Felder aus.', NOW(), NOW()),
('error_add_failed', 'de', 'Fehler beim Hinzufügen.', NOW(), NOW()),
('error_update_failed', 'de', 'Fehler beim Aktualisieren.', NOW(), NOW()),

-- Impressum
('impressum_title', 'de', 'Impressum', NOW(), NOW()),
('impressum_default_content', 'de', 'Bitte konfigurieren Sie das Impressum im Admin-Panel.', NOW(), NOW());

-- =====================================================
-- INITIAL-DATEN: Basis-Sprachstrings für Englisch
-- =====================================================
INSERT IGNORE INTO language_strings (lang_key, lang_code, value, created_at, updated_at) VALUES
('welcome_title', 'en', 'Welcome to ClearWiki', NOW(), NOW()),
('demo_version_suffix', 'en', 'Demo Version', NOW(), NOW()),
('wiki_index_title', 'en', 'Wiki Index', NOW(), NOW()),
('home', 'en', 'Home', NOW(), NOW()),
('articles', 'en', 'Articles', NOW(), NOW()),
('categories', 'en', 'Categories', NOW(), NOW()),
('users', 'en', 'Users', NOW(), NOW()),
('login', 'en', 'Login', NOW(), NOW()),
('logout', 'en', 'Logout', NOW(), NOW()),
('register', 'en', 'Register', NOW(), NOW()),
('admin', 'en', 'Administration', NOW(), NOW()),
('login_title', 'en', 'Login', NOW(), NOW()),
('register_title', 'en', 'Registration', NOW(), NOW()),
('login_error_empty_fields', 'en', 'Please fill in all fields.', NOW(), NOW()),
('login_error_invalid_credentials', 'en', 'Invalid credentials.', NOW(), NOW()),
('registration_error_empty_fields', 'en', 'Please fill in all fields.', NOW(), NOW()),
('registration_error_invalid_email', 'en', 'Invalid email address.', NOW(), NOW()),
('registration_error_password_mismatch', 'en', 'Passwords do not match.', NOW(), NOW()),
('registration_error_password_too_short', 'en', 'Password must be at least 8 characters.', NOW(), NOW()),
('registration_error_user_exists', 'en', 'Username or email already exists.', NOW(), NOW()),
('impressum_title', 'en', 'Imprint', NOW(), NOW());

-- =====================================================
-- INITIAL-DATEN: Standard-Einstellungen
-- =====================================================
INSERT IGNORE INTO settings (
    id, wiki_name, wiki_slogan, display_wiki_name, display_slogan, display_logo,
    logo_height_px, footer_text, impressum_content, maintenance_mode,
    enable_articles, enable_categories, enable_users, enable_alliances,
    enable_registration, enable_pending_contributions, enable_impressum,
    enable_send_alliance_mail, created_at, updated_at
) VALUES (
    1, 'ClearWiki', 'Next Generation Gaming Guide', TRUE, TRUE, TRUE,
    40, '© 2025 ClearWiki - Entwickelt mit ❤️ von Daniel Mattick.',
    'Bitte konfigurieren Sie das Impressum im Admin-Panel.', FALSE,
    TRUE, TRUE, TRUE, TRUE,
    TRUE, TRUE, TRUE,
    TRUE, NOW(), NOW()
);

-- =====================================================
-- INITIAL-DATEN: Standard Admin-Benutzer
-- Passwort: admin123 (BITTE IN PRODUKTION ÄNDERN!)
-- =====================================================
INSERT IGNORE INTO users (username, email, password, role, created_at, updated_at) VALUES
('admin', 'admin@clearwiki.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW());

-- =====================================================
-- HINWEISE FÜR DIE PRODUKTION
-- =====================================================
-- 1. Ändern Sie die Datenbank-Zugangsdaten in config.php
-- 2. Erstellen Sie einen neuen Admin-Benutzer und löschen Sie den Standard-Admin
-- 3. Passen Sie die Einstellungen in der 'settings' Tabelle an
-- 4. Konfigurieren Sie das Impressum
-- 5. Richten Sie regelmäßige Backups ein
