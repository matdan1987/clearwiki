# ClearWiki - Next Generation Gaming Guide

Ein modernes, mehrsprachiges Wiki-System für Gaming-Communities, entwickelt mit PHP und MySQL.

## 🚀 Features

- ✅ **Mehrsprachigkeit**: Vollständige Unterstützung für mehrere Sprachen (Deutsch, Englisch, Französisch)
- ✅ **Artikel-Verwaltung**: Erstellen, bearbeiten und verwalten Sie Wiki-Artikel
- ✅ **Benutzer-System**: Registrierung, Login, Rollen (Admin, Moderator, Registered)
- ✅ **CKEditor 5**: Moderner WYSIWYG-Editor mit Bild-Upload
- ✅ **Responsive Design**: Optimiert für Desktop und Mobile
- ✅ **Admin-Panel**: Verwaltung von Sprachen, Einstellungen und Inhalten
- ✅ **Kategorien**: Organisieren Sie Ihre Artikel (optional)
- ✅ **Allianzen/Gilden**: Community-Features (optional)
- ✅ **Feature-Toggles**: Aktivieren/Deaktivieren Sie Funktionen nach Bedarf

## 📋 Systemanforderungen

- **PHP**: 8.0 oder höher
- **MySQL**: 5.7 oder höher / MariaDB 10.3 oder höher
- **Webserver**: Apache 2.4+ mit mod_rewrite
- **PHP-Erweiterungen**:
  - PDO
  - pdo_mysql
  - mbstring
  - session
  - gd (für Bild-Upload)

## 🛠️ Installation

### 1. Projekt klonen oder herunterladen

```bash
git clone https://github.com/matdan1987/clearwiki.git
cd clearwiki
```

### 2. Datenbank erstellen

```bash
# MySQL-Konsole öffnen
mysql -u root -p

# Datenbank und Benutzer erstellen
CREATE DATABASE clearwiki CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'clearwiki'@'localhost' IDENTIFIED BY 'IhrSicheresPasswort';
GRANT ALL PRIVILEGES ON clearwiki.* TO 'clearwiki'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Schema importieren
mysql -u clearwiki -p clearwiki < schema.sql

# Zusätzliche Übersetzungen importieren (wichtig!)
mysql -u clearwiki -p clearwiki < additional_translations.sql
```

### 3. Konfiguration anpassen

Kopieren Sie `config.example.php` zu `config.php` und passen Sie die Datenbank-Zugangsdaten an:

```bash
cp config.example.php config.php
nano config.php  # oder ein anderer Editor
```

Ändern Sie folgende Werte in `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clearwiki');
define('DB_USER', 'clearwiki');
define('DB_PASS', 'IhrSicheresPasswort');
```

### 4. Upload-Verzeichnis erstellen

```bash
mkdir -p public/uploads/images
chmod 775 public/uploads/images
```

### 5. Apache-Konfiguration

Stellen Sie sicher, dass `mod_rewrite` aktiviert ist:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**Virtueller Host (Beispiel):**

```apache
<VirtualHost *:80>
    ServerName clearwiki.local
    DocumentRoot /pfad/zu/clearwiki/public

    <Directory /pfad/zu/clearwiki/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/clearwiki-error.log
    CustomLog ${APACHE_LOG_DIR}/clearwiki-access.log combined
</VirtualHost>
```

### 6. Standard-Admin-Zugang

Nach der Installation können Sie sich mit folgenden Zugangsdaten anmelden:

- **Benutzername**: `admin`
- **Passwort**: `admin123`

**⚠️ WICHTIG**: Ändern Sie dieses Passwort sofort nach der ersten Anmeldung!

## 🔒 Sicherheit für Produktion

### Kritische Schritte vor dem Live-Gang:

1. **Admin-Passwort ändern**:
   ```bash
   php generate_hash.php
   # Neues Passwort-Hash generieren und in der Datenbank aktualisieren
   ```

2. **Datenbank-Passwort ändern**:
   - Generieren Sie ein sicheres Passwort (mind. 20 Zeichen)
   - Aktualisieren Sie `config.php`

3. **Dateiberechtigungen setzen**:
   ```bash
   # Dateien: 644, Verzeichnisse: 755
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;

   # Upload-Verzeichnis beschreibbar
   chmod 775 public/uploads/images

   # Sensible Dateien schützen
   chmod 600 config.php
   ```

4. **Error Reporting**:
   - Ist bereits für Produktion konfiguriert in `public/index.php`
   - Fehler werden geloggt, aber nicht angezeigt

5. **HTTPS aktivieren**:
   - Installieren Sie ein SSL-Zertifikat (z.B. Let's Encrypt)
   - Erzwingen Sie HTTPS in der Apache-Konfiguration

6. **Regelmäßige Backups**:
   ```bash
   # Beispiel Backup-Script
   #!/bin/bash
   DATE=$(date +%Y%m%d_%H%M%S)
   mysqldump -u clearwiki -p clearwiki > backup_$DATE.sql
   tar -czf backup_$DATE.tar.gz . --exclude=backup_*.tar.gz
   ```

## 📂 Verzeichnisstruktur

```
clearwiki/
├── config.php              # Datenbank-Konfiguration
├── functions.php           # Zentrale Funktionen
├── schema.sql              # Datenbank-Schema
├── generate_hash.php       # Passwort-Hash-Generator
├── migrate_lang_strings.php # Migrations-Tool für Sprachen
├── .htaccess               # Root .htaccess (Sicherheit)
├── public/                 # Öffentliches Verzeichnis (DocumentRoot)
│   ├── index.php           # Front-Controller
│   ├── .htaccess           # URL Rewriting
│   ├── debug_session.php   # Session-Debug (nur für Entwicklung!)
│   └── uploads/
│       └── images/         # Hochgeladene Bilder
├── views/                  # View-Templates
│   ├── header.php
│   ├── footer.php
│   ├── home.php
│   ├── article_*.php
│   ├── admin_*.php
│   └── ...
└── lang/                   # Veraltete Sprachdateien (optional)
```

## 🎨 Anpassung

### Logo und Favicon

1. Laden Sie Ihr Logo und Favicon in das Verzeichnis `public/uploads/` hoch
2. Gehen Sie im Admin-Panel zu **Einstellungen**
3. Geben Sie die Pfade zu Logo und Favicon an

### Footer-Links

Footer-Links werden in der Datenbank in der Tabelle `footer_links` gespeichert.

### Sprachen

- Neue Sprachen können im Admin-Panel unter **Sprachverwaltung** hinzugefügt werden
- Sprachstrings werden automatisch für neue Sprachen initialisiert

## 🐛 Fehlerbehebung

### Problem: 404-Fehler für alle Seiten

**Lösung**: Stellen Sie sicher, dass `mod_rewrite` aktiviert ist und `.htaccess` Dateien vorhanden sind.

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Problem: Datenbank-Verbindungsfehler

**Lösung**: Überprüfen Sie die Zugangsdaten in `config.php` und stellen Sie sicher, dass MySQL läuft.

```bash
sudo systemctl status mysql
```

### Problem: Bilder können nicht hochgeladen werden

**Lösung**: Überprüfen Sie die Berechtigungen des Upload-Verzeichnisses.

```bash
chmod 775 public/uploads/images
chown www-data:www-data public/uploads/images
```

### Problem: Session-Probleme / Login funktioniert nicht

**Lösung**: Stellen Sie sicher, dass das Session-Verzeichnis beschreibbar ist.

```bash
sudo chmod 1733 /var/lib/php/sessions
```

## 📝 Entwicklung

### Debug-Modus aktivieren

Bearbeiten Sie `public/index.php` und ändern Sie:

```php
// Fehlerreporting für die Entwicklung
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

### Passwort-Hash generieren

```bash
php generate_hash.php
```

### Sprach-Migration

Falls Sie alte Sprachdateien haben:

```bash
php migrate_lang_strings.php
```

## 🤝 Beitragen

Contributions sind willkommen! Bitte erstellen Sie einen Fork und senden Sie Pull Requests.

## 📄 Lizenz

Dieses Projekt ist lizenziert unter der MIT-Lizenz.

## 👨‍💻 Autor

**Daniel Mattick**
- Website: [ClearWiki](https://clearwiki.local)
- GitHub: [@matdan1987](https://github.com/matdan1987)

## 🙏 Danksagungen

- [CKEditor 5](https://ckeditor.com/) - WYSIWYG-Editor
- [Tailwind CSS](https://tailwindcss.com/) - CSS-Framework
- [Font Awesome](https://fontawesome.com/) - Icons

---

**© 2025 ClearWiki - Entwickelt mit ❤️ von Daniel Mattick**
