<?php
// migrate_lang_strings.php - Temporäres Skript zum Importieren von Sprachstrings in die Datenbank

// Stelle sicher, dass functions.php geladen wird, um DB-Verbindung und Funktionen zu haben
// Korrigierter Pfad: functions.php liegt im übergeordneten Verzeichnis von public/
require_once __DIR__ . '/../functions.php';

// Deaktiviere Fehlerreporting für die Ausgabe, falls du es im Browser aufrufst
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Sprachstrings-Migration starten...</h1>";

$pdo = get_db_connection();

$languages_to_migrate = [
    'de' => __DIR__ . '/../lang/de.php', // Pfad auch hier anpassen, da lang/ auch im Hauptverzeichnis ist
    'en' => __DIR__ . '/../lang/en.php', // Pfad auch hier anpassen
    'fr' => __DIR__ . '/../lang/fr.php', // Pfad auch hier anpassen
];

foreach ($languages_to_migrate as $lang_code => $file_path) {
    if (file_exists($file_path)) {
        $strings = require $file_path;
        echo "<h2>Migriere Sprache: " . htmlspecialchars($lang_code) . "</h2>";
        $count = 0;
        foreach ($strings as $key => $value) {
            // Prüfen, ob der String bereits existiert, um Duplikate zu vermeiden
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM language_strings WHERE lang_key = :lang_key AND lang_code = :lang_code");
            $stmt_check->execute([':lang_key' => $key, ':lang_code' => $lang_code]);
            if ($stmt_check->fetchColumn() == 0) {
                if (add_language_string($key, $lang_code, $value)) {
                    echo "  - Hinzugefügt: " . htmlspecialchars($key) . "<br>";
                    $count++;
                } else {
                    echo "  - FEHLER beim Hinzufügen: " . htmlspecialchars($key) . "<br>";
                }
            } else {
                echo "  - Übersprungen (existiert bereits): " . htmlspecialchars($key) . "<br>";
            }
        }
        echo "<p>Total hinzugefügt für " . htmlspecialchars($lang_code) . ": " . $count . "</p>";
    } else {
        echo "<p style='color: red;'>Fehler: Sprachdatei nicht gefunden: " . htmlspecialchars($file_path) . "</p>";
    }
}

echo "<h1>Migration abgeschlossen.</h1>";
echo "<p>Bitte löschen Sie diese Datei ('" . basename(__FILE__) . "') von Ihrem Server, sobald die Migration erfolgreich war.</p>";
?>
