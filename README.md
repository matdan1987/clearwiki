# ClearWiki - Next Generation Gaming Guide

Ein modernes, mehrsprachiges Wiki-System für Gaming-Communities, entwickelt mit PHP. **Keine Datenbank erforderlich!** Alle Daten werden in JSON-Dateien gespeichert.

## 🚀 Features

- ✅ **Keine Datenbank nötig**: Läuft komplett dateibasiert mit JSON-Storage
- ✅ **Einfache Installation**: Einfach hochladen und loslegen - keine komplizierte Einrichtung!
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
- **Webserver**: Apache 2.4+ mit mod_rewrite
- **PHP-Erweiterungen**:
  - json
  - mbstring
  - session
  - gd (für Bild-Upload)

## 🛠️ Installation

**Super einfach! Keine Datenbank-Einrichtung nötig.**

### 1. Projekt klonen oder herunterladen

```bash
git clone https://github.com/matdan1987/clearwiki.git
cd clearwiki
```

### 2. Dateiberechtigungen setzen

```bash
# Upload-Verzeichnis beschreibbar machen
chmod 775 public/uploads/images

# Data-Verzeichnis beschreibbar machen
chmod -R 775 data/
```

**Das war's!** Die Daten-Verzeichnisse und JSON-Dateien existieren bereits mit Standardwerten.

### 3. Apache-Konfiguration

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

### 4. Standard-Admin-Zugang

Nach der Installation können Sie sich mit folgenden Zugangsdaten anmelden:

- **Benutzername**: `admin`
- **Passwort**: `password`

**⚠️ WICHTIG**: Ändern Sie dieses Passwort sofort nach der ersten Anmeldung im Admin-Panel!

## 🔒 Sicherheit für Produktion

### Kritische Schritte vor dem Live-Gang:

1. **Admin-Passwort ändern**:
   - Melden Sie sich als Admin an
   - Gehen Sie zum Admin-Panel → Benutzerverwaltung
   - Ändern Sie das Admin-Passwort

2. **Dateiberechtigungen setzen**:
   ```bash
   # Dateien: 644, Verzeichnisse: 755
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;

   # Upload-Verzeichnis beschreibbar
   chmod 775 public/uploads/images

   # Data-Verzeichnis beschreibbar
   chmod -R 775 data/

   # Sensible Dateien schützen
   chmod 600 config.php
   ```

3. **Error Reporting**:
   - Ist bereits für Produktion konfiguriert in `public/index.php`
   - Fehler werden geloggt, aber nicht angezeigt

4. **HTTPS aktivieren**:
   - Installieren Sie ein SSL-Zertifikat (z.B. Let's Encrypt)
   - Erzwingen Sie HTTPS in der Apache-Konfiguration

5. **Regelmäßige Backups**:
   ```bash
   # Beispiel Backup-Script
   #!/bin/bash
   DATE=$(date +%Y%m%d_%H%M%S)
   tar -czf backup_$DATE.tar.gz data/ public/uploads/ --exclude=backup_*.tar.gz
   ```

   **Wichtig**: Sichern Sie regelmäßig das `data/` Verzeichnis - hier sind alle Ihre Inhalte gespeichert!

## 📂 Verzeichnisstruktur

```
clearwiki/
├── config.php              # System-Konfiguration (Dateipfade)
├── functions.php           # Zentrale Funktionen (file-based)
├── .htaccess               # Root .htaccess (Sicherheit)
├── data/                   # 🔥 ALLE DATEN HIER (JSON & PHP)
│   ├── users/
│   │   └── users.json      # Benutzer-Daten
│   ├── articles/
│   │   └── articles.json   # Wiki-Artikel
│   ├── settings/
│   │   ├── global.json     # Globale Einstellungen
│   │   └── footer_links.json
│   └── languages/
│       ├── supported.json  # Verfügbare Sprachen
│       ├── de.php          # Deutsche Übersetzungen
│       ├── en.php          # Englische Übersetzungen
│       └── fr.php          # Französische Übersetzungen
├── public/                 # Öffentliches Verzeichnis (DocumentRoot)
│   ├── index.php           # Front-Controller
│   ├── .htaccess           # URL Rewriting
│   └── uploads/
│       └── images/         # Hochgeladene Bilder
└── views/                  # View-Templates
    ├── header.php
    ├── footer.php
    ├── home.php
    ├── article_*.php
    ├── admin_*.php
    └── ...
```

## 🎨 Anpassung

### Logo und Favicon

1. Laden Sie Ihr Logo und Favicon in das Verzeichnis `public/uploads/` hoch
2. Gehen Sie im Admin-Panel zu **Einstellungen**
3. Geben Sie die Pfade zu Logo und Favicon an

### Footer-Links

Footer-Links werden in `data/settings/footer_links.json` gespeichert und können im Admin-Panel verwaltet werden.

### Sprachen

- Neue Sprachen können im Admin-Panel unter **Sprachverwaltung** hinzugefügt werden
- Sprachstrings werden in PHP-Dateien gespeichert: `data/languages/{lang_code}.php`

## 🐛 Fehlerbehebung

### Problem: 404-Fehler für alle Seiten

**Lösung**: Stellen Sie sicher, dass `mod_rewrite` aktiviert ist und `.htaccess` Dateien vorhanden sind.

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Problem: Daten werden nicht gespeichert

**Lösung**: Überprüfen Sie die Schreibrechte für das `data/` Verzeichnis.

```bash
chmod -R 775 data/
chown -R www-data:www-data data/
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

### Daten-Struktur

Alle Daten liegen in JSON-Dateien im `data/` Verzeichnis:

- **Benutzer**: `data/users/users.json`
- **Artikel**: `data/articles/articles.json`
- **Einstellungen**: `data/settings/global.json`
- **Sprachen**: `data/languages/{lang_code}.php`

Sie können diese Dateien direkt bearbeiten (z.B. für Bulk-Änderungen) oder über das Admin-Panel verwalten.

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
