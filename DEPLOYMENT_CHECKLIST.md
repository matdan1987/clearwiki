# ClearWiki - Production Deployment Checklist

**Datum**: _________
**Deployed von**: _________
**Version**: _________

## ✅ Vor dem Deployment

### 1. Code-Qualität
- [ ] Alle PHP-Dateien auf Syntax-Fehler geprüft (`php -l`)
- [ ] Keine Debug-Code-Reste vorhanden
- [ ] Keine `var_dump()`, `print_r()` oder ähnliche Debug-Ausgaben
- [ ] Git-Repository ist sauber (keine uncommitted changes)

### 2. Konfiguration
- [ ] `config.php` enthält sichere Produktions-Zugangsdaten
- [ ] Datenbank-Passwort ist sicher (mind. 20 Zeichen, gemischt)
- [ ] `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` korrekt gesetzt
- [ ] Upload-Verzeichnis-Pfad ist korrekt

### 3. Sicherheit
- [ ] Error Display ist deaktiviert (`display_errors = 0`)
- [ ] Error Logging ist aktiviert (`log_errors = 1`)
- [ ] Standard-Admin-Passwort wurde geändert
- [ ] `.htaccess` Dateien sind vorhanden und korrekt
- [ ] Sensible Dateien sind vor direktem Zugriff geschützt
- [ ] HTTPS ist konfiguriert und erzwungen

### 4. Datenbank
- [ ] Datenbank wurde erstellt
- [ ] Schema wurde importiert (`schema.sql`)
- [ ] Datenbank-Benutzer hat nur notwendige Rechte
- [ ] Standard-Einstellungen wurden angepasst
- [ ] Impressum wurde konfiguriert

### 5. Dateisystem
- [ ] Upload-Verzeichnis existiert (`public/uploads/images/`)
- [ ] Upload-Verzeichnis ist beschreibbar (chmod 775)
- [ ] Dateiberechtigungen sind korrekt gesetzt:
  - [ ] Dateien: 644
  - [ ] Verzeichnisse: 755
  - [ ] `config.php`: 600
  - [ ] Upload-Verzeichnis: 775

### 6. Webserver
- [ ] Apache `mod_rewrite` ist aktiviert
- [ ] Virtual Host ist korrekt konfiguriert
- [ ] DocumentRoot zeigt auf `/pfad/zu/clearwiki/public`
- [ ] `AllowOverride All` ist gesetzt
- [ ] SSL-Zertifikat ist installiert (z.B. Let's Encrypt)
- [ ] HTTP wird auf HTTPS umgeleitet

## ✅ Deployment-Schritte

### 7. Code-Upload
- [ ] Code wurde auf Server hochgeladen/gepullt
- [ ] `.git` Verzeichnis wurde entfernt (optional, für Sicherheit)
- [ ] Alle Dateien sind vorhanden

### 8. Abhängigkeiten & Konfiguration
- [ ] PHP-Version ist >= 8.0
- [ ] Alle PHP-Erweiterungen sind installiert (PDO, pdo_mysql, mbstring, gd)
- [ ] MySQL/MariaDB Version ist >= 5.7/10.3
- [ ] Webserver wurde neu gestartet

### 9. Datenbank-Setup
- [ ] Datenbank-Backup wurde erstellt (falls Update)
- [ ] Schema wurde importiert oder migriert
- [ ] Datenbank-Verbindung funktioniert

### 10. Initialer Test
- [ ] Website ist über Browser erreichbar
- [ ] Homepage lädt ohne Fehler
- [ ] Login funktioniert mit Admin-Account
- [ ] Logout funktioniert
- [ ] Artikel-Erstellung funktioniert
- [ ] Bild-Upload funktioniert
- [ ] Sprachumschaltung funktioniert

## ✅ Nach dem Deployment

### 11. Funktionstest
- [ ] Registration funktioniert (wenn aktiviert)
- [ ] Artikel-Bearbeitung funktioniert
- [ ] Artikel-Löschung funktioniert
- [ ] Admin-Panel ist erreichbar
- [ ] Sprachverwaltung funktioniert
- [ ] Alle Links im Footer funktionieren

### 12. Performance & Monitoring
- [ ] Error-Logs wurden überprüft (keine kritischen Fehler)
- [ ] Website-Ladezeit ist akzeptabel (< 3 Sekunden)
- [ ] Alle Bilder und Assets laden korrekt
- [ ] Mobile-Ansicht funktioniert einwandfrei

### 13. Backup & Recovery
- [ ] Backup-Strategie ist implementiert
- [ ] Erstes Backup wurde erstellt:
  - [ ] Datenbank-Backup
  - [ ] Datei-Backup (inkl. Uploads)
- [ ] Backup-Wiederherstellung wurde getestet

### 14. Dokumentation
- [ ] Admin-Zugangsdaten sind dokumentiert (sicher aufbewahrt)
- [ ] Server-Zugangsdaten sind dokumentiert
- [ ] Datenbank-Zugangsdaten sind dokumentiert
- [ ] Deployment-Datum und -Version sind notiert
- [ ] Änderungslog wurde aktualisiert

### 15. Sicherheits-Audit
- [ ] Alle sensiblen Dateien sind geschützt (config.php nicht öffentlich)
- [ ] SQL-Injection-Schutz ist aktiv (Prepared Statements werden verwendet)
- [ ] XSS-Schutz ist aktiv (`htmlspecialchars()` wird verwendet)
- [ ] CSRF-Schutz sollte implementiert werden (zukünftig)
- [ ] Rate-Limiting sollte konfiguriert werden (zukünftig)

## ✅ Produktions-Wartung

### 16. Regelmäßige Aufgaben
- [ ] Backup-Routine eingerichtet (täglich/wöchentlich)
- [ ] Log-Rotation konfiguriert
- [ ] Update-Strategie festgelegt
- [ ] Monitoring eingerichtet (optional: Uptime-Monitoring)

### 17. Support & Kontakt
- [ ] Support-E-Mail ist konfiguriert
- [ ] Fehler-Reporting-System ist eingerichtet (optional)
- [ ] Notfall-Kontaktliste ist erstellt

## 🚨 Kritische Hinweise

**VOR DEM GO-LIVE UNBEDINGT BEACHTEN:**

1. **Passwort ändern**: Standard-Admin-Passwort `admin123` MUSS geändert werden!
2. **HTTPS**: Niemals ohne SSL/TLS in Produktion gehen!
3. **Backups**: Vor jedem Update ein Backup erstellen!
4. **Error Display**: NIEMALS `display_errors = 1` in Produktion!
5. **Debug-Code**: Alle Debug-Ausgaben und Session-Resets entfernen!

## 📝 Notizen

```
_____________________________________________________________________

_____________________________________________________________________

_____________________________________________________________________

_____________________________________________________________________
```

## ✅ Sign-off

**Deployment abgeschlossen von**: ___________________
**Datum & Uhrzeit**: ___________________
**Unterschrift**: ___________________

**Quality Assurance durch**: ___________________
**Datum & Uhrzeit**: ___________________
**Unterschrift**: ___________________

---

**Kopie dieser Checklist aufbewahren für zukünftige Referenz und Audits!**
