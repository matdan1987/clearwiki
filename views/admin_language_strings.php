<?php
// views/admin_language_strings.php - Admin-Panel für Sprachstrings einer Sprache
// Daten: $title, $current_lang, $lang_code, $strings (Array von Strings), $status, $message

global $current_lang, $is_demo_context; // is_demo_context hinzufügen
$strings = $strings ?? []; // Sicherstellen, dass $strings definiert ist
$status = $_GET['status'] ?? '';
$message = $_GET['message'] ?? '';
?>

<?php // Der Header wird bereits vom Dispatcher in index.php geladen, daher hier entfernt ?>
<?php // load_view('header', ['title' => $title, 'current_lang' => $current_lang]); ?>

<div class="main-grid lg:grid-cols-4">
    <div class="lg:col-span-3 space-y-8">
        <section class="glass-effect rounded-2xl p-6 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-font text-accent-orange mr-3"></i>
                <?= __('admin_language_strings_title') ?> (<?= sanitize_output(strtoupper($lang_code)) ?>)
            </h2>

            <?php if ($status === 'success' && $message): ?>
                <div class="bg-success/20 border border-success text-success px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <span class="block sm:inline"><?= sanitize_output($message) ?></span>
                </div>
            <?php endif; ?>
            <?php if ($status === 'error' && $message): ?>
                <div class="bg-error/20 border border-error text-error px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <span class="block sm:inline"><?= sanitize_output($message) ?></span>
                </div>
            <?php endif; ?>

            <div class="flex justify-between items-center mb-4">
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/languages" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i><?= __('back_to_languages') ?>
                </a>
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/language/string/new?lang_code=<?= sanitize_output($lang_code) ?>" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i><?= __('add_new_string') ?>
                </a>
            </div>

            <?php if (!empty($strings)): ?>
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full bg-card-bg text-text-primary">
                        <thead class="bg-secondary-bg">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary rounded-tl-lg">ID</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary">Schlüssel</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary">Wert</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary rounded-tr-lg">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($strings as $string): ?>
                                <tr class="border-t border-border-color hover:bg-secondary-bg transition-colors">
                                    <td class="py-3 px-4 text-sm"><?= sanitize_output($string['id']) ?></td>
                                    <td class="py-3 px-4 text-sm font-mono text-accent-blue"><?= sanitize_output($string['lang_key']) ?></td>
                                    <td class="py-3 px-4 text-sm max-w-xs truncate" title="<?= sanitize_output($string['value']) ?>"><?= sanitize_output($string['value']) ?></td>
                                    <td class="py-3 px-4 text-sm flex items-center space-x-2">
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/language/string/edit/<?= sanitize_output($string['id']) ?>" class="text-accent-blue hover:text-blue-400" title="<?= __('edit_language_string') ?? 'String bearbeiten' ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php /* Löschen-Funktion (später implementieren, ggf. mit Bestätigungsmodal) */ ?>
                                        <?php /* <button onclick="confirmDeleteString(<?= $string['id'] ?>, '<?= sanitize_output($string['lang_key']) ?>')" class="text-error hover:text-red-400" title="Löschen"><i class="fas fa-trash"></i></button> */ ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-text-secondary text-center py-8"><?= __('no_strings_found') ?></p>
            <?php endif; ?>
        </section>
    </div>
    <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
</div>

<?php // Der Footer wird bereits vom Dispatcher in index.php geladen, daher hier entfernt ?>
<?php // load_view('footer', ['current_lang' => $current_lang]); ?>
