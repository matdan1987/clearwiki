<?php
// debug_session.php - Temporäres Skript zur Session-Diagnose

// Schritt 1: Session immer starten
// Dies ist entscheidend, um zu sehen, ob PHP überhaupt eine Session starten kann.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "<p style='color: green;'>Session wurde gestartet.</p>";
} else {
    echo "<p style='color: blue;'>Session ist bereits aktiv.</p>";
}

// Fehlerreporting für die Entwicklung (für dieses Skript)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pfad zur functions.php (im übergeordneten Verzeichnis)
$functions_path = __DIR__ . '/../functions.php';

// Schritt 2: functions.php laden (falls vorhanden)
// Wir laden functions.php hier, um get_current_user() und is_logged_in() nutzen zu können.
if (file_exists($functions_path)) {
    require_once $functions_path;
    echo "<p style='color: green;'>functions.php wurde geladen.</p>";
} else {
    echo "<p style='color: red;'>FEHLER: functions.php nicht gefunden unter: " . htmlspecialchars($functions_path) . "</p>";
    echo "<p style='color: red;'>Bitte stellen Sie sicher, dass functions.php im Hauptverzeichnis (eine Ebene über public/) liegt.</p>";
    exit(); // Abbruch, wenn functions.php nicht gefunden wird
}

echo "<h1>Session Debugging Tool</h1>";
echo "<hr>";

// Schritt 3: Aktuellen Zustand der $_SESSION anzeigen
echo "<h2>1. Aktueller Zustand von \$_SESSION:</h2>";
echo "<pre style='background-color: #1a1a1a; color: #e0e0e0; padding: 10px; border-radius: 5px;'>";
print_r($_SESSION);
echo "</pre>";

// Schritt 4: Status über is_logged_in() und get_current_user() anzeigen
echo "<h2>2. Anmeldestatus (via functions.php):</h2>";
$isLoggedIn = is_logged_in();
$currentUser = get_current_user();

echo "<p>is_logged_in(): <strong>" . ($isLoggedIn ? 'TRUE' : 'FALSE') . "</strong></p>";
echo "<p>get_current_user(): ";
echo "<pre style='background-color: #1a1a1a; color: #e0e0e0; padding: 10px; border-radius: 5px;'>";
print_r($currentUser);
echo "</pre>";

// Schritt 5: Formular zum Setzen/Löschen von Session-Daten
echo "<h2>3. Session-Aktionen:</h2>";

// Formular zum Setzen von Test-Session-Daten
echo "<h3>Test-Session-Daten setzen:</h3>";
echo "<form method='post' action=''>";
echo "<input type='hidden' name='action' value='set_session'>";
echo "<label for='test_user_id'>Test User ID:</label>";
echo "<input type='text' id='test_user_id' name='test_user_id' value='" . htmlspecialchars($_SESSION['user_id'] ?? '1') . "' style='background-color: #2a2a2a; color: #e0e0e0; border: 1px solid #4a4a4a; padding: 5px; margin: 5px; border-radius: 3px;'><br>";
echo "<label for='test_username'>Test Username:</label>";
echo "<input type='text' id='test_username' name='test_username' value='" . htmlspecialchars($_SESSION['username'] ?? 'testuser') . "' style='background-color: #2a2a2a; color: #e0e0e0; border: 1px solid #4a4a4a; padding: 5px; margin: 5px; border-radius: 3px;'><br>";
echo "<label for='test_user_role'>Test Role:</label>";
echo "<input type='text' id='test_user_role' name='test_user_role' value='" . htmlspecialchars($_SESSION['user_role'] ?? 'admin') . "' style='background-color: #2a2a2a; color: #e0e0e0; border: 1px solid #4a4a4a; padding: 5px; margin: 5px; border-radius: 3px;'><br>";
echo "<button type='submit' style='background-color: #ff6b35; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;'>Session-Daten setzen</button>";
echo "</form>";

// Formular zum Löschen der Session
echo "<h3>Session löschen:</h3>";
echo "<form method='post' action=''>";
echo "<input type='hidden' name='action' value='clear_session'>";
echo "<button type='submit' style='background-color: #dc2626; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;'>Session löschen (Logout simulieren)</button>";
echo "</form>";

// Verarbeitung der Formular-Aktionen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'set_session') {
            $_SESSION['user_id'] = (int)($_POST['test_user_id'] ?? 0);
            $_SESSION['username'] = $_POST['test_username'] ?? '';
            $_SESSION['user_role'] = $_POST['test_user_role'] ?? '';
            echo "<p style='color: green;'>Session-Daten gesetzt. Bitte Seite neu laden, um den Zustand zu überprüfen.</p>";
        } elseif ($_POST['action'] === 'clear_session') {
            session_unset();
            session_destroy();
            // session_start(); // Nicht hier starten, da der nächste Request dies tun sollte
            echo "<p style='color: green;'>Session gelöscht. Bitte Seite neu laden, um den Zustand zu überprüfen.</p>";
        }
    }
    // WICHTIG: Nach POST-Request immer umleiten, um erneutes Absenden zu verhindern
    // header("Location: " . $_SERVER['PHP_SELF']); // Dies würde die Debug-Ausgabe löschen
    // exit();
}

echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>WICHTIG: Löschen Sie diese Datei ('" . basename(__FILE__) . "') von Ihrem Server, sobald die Diagnose abgeschlossen ist!</p>";
?>
