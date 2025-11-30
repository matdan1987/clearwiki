<?php
// views/maintenance.php - Wartungsmodus
global $current_lang; // Stellt sicher, dass $current_lang verfügbar ist
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('maintenance_mode_title') ?> | ClearWiki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #e0e0e0;
            background-color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        h1 {
            font-family: 'Orbitron', sans-serif;
            color: #ff6b35; /* Angepasst an accent-orange */
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        p {
            color: #a0a0a0;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div>
        <h1><?= __('maintenance_mode_message') ?></h1>
        <p><?= __('try_again_later') ?></p>
    </div>
</body>
</html>