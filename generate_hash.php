<?php
// generate_hash.php - Temporäres Skript zum Generieren eines PHP password_hash()

// WICHTIG: ÄNDERE DIESES PASSWORT zu deinem gewünschten, sicheren Admin-Passwort!
$password_to_hash = 'Asudi8iseg63!!!'; 

// Generiere den Hash
$hashed_password = password_hash($password_to_hash, PASSWORD_DEFAULT);

echo "<h1>PHP Password Hash Generator</h1>";
echo "<p>Plain-text password: <strong>" . htmlspecialchars($password_to_hash) . "</strong></p>";
echo "<p>Generated hash: <strong>" . htmlspecialchars($hashed_password) . "</strong></p>";
echo "<p>Kopieren Sie diesen Hash und fügen Sie ihn in den SQL INSERT Befehl für den Admin-Benutzer ein.</p>";
echo "<p style='color: red; font-weight: bold;'>WICHTIG: Löschen Sie diese Datei ('" . basename(__FILE__) . "') von Ihrem Server, sobald Sie den Hash kopiert haben!</p>";
?>
