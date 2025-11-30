<?php
// views/admin_languages.php - Admin-Panel für Sprachverwaltung
// Daten: $title, $current_lang, $languages (Array von Sprachen), $status, $message

global $current_lang, $is_demo_context; // is_demo_context hinzufügen
$languages = $languages ?? []; // Sicherstellen, dass $languages definiert ist
$status = $_GET['status'] ?? '';
$message = $_GET['message'] ?? '';
?>

<?php // Der Header wird bereits vom Dispatcher in index.php geladen, daher hier entfernt ?>

<div class="main-grid lg:grid-cols-4">
    <div class="lg:col-span-3 space-y-8">
        <section class="glass-effect rounded-2xl p-6 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-globe text-accent-orange mr-3"></i>
                <?= __('admin_languages_title') ?>
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

            <div class="flex justify-end mb-4">
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/language/edit/new" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i><?= __('add_language_button') ?>
                </a>
            </div>

            <?php if (!empty($languages)): ?>
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full bg-card-bg text-text-primary">
                        <thead class="bg-secondary-bg">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary rounded-tl-lg">ID</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary">Code</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary">Name</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary">Aktiv</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-text-secondary rounded-tr-lg">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($languages as $lang): ?>
                                <tr class="border-t border-border-color hover:bg-secondary-bg transition-colors">
                                    <td class="py-3 px-4 text-sm"><?= sanitize_output($lang['id']) ?></td>
                                    <td class="py-3 px-4 text-sm"><?= sanitize_output(strtoupper($lang['lang_code'])) ?></td>
                                    <td class="py-3 px-4 text-sm"><?= sanitize_output($lang['lang_name']) ?></td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $lang['is_active'] ? 'bg-success/20 text-success' : 'bg-error/20 text-error' ?>">
                                            <?= $lang['is_active'] ? __('yes') : __('no') ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm flex items-center space-x-2">
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/language/edit/<?= sanitize_output($lang['id']) ?>" class="text-accent-blue hover:text-blue-400" title="<?= __('edit_language') ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/language/strings/<?= sanitize_output($lang['lang_code']) ?>" class="text-accent-orange hover:text-orange-400" title="<?= __('manage_strings') ?>">
                                            <i class="fas fa-language"></i>
                                        </a>
                                        <?php /* Löschen-Funktion (später implementieren, ggf. mit Bestätigungsmodal) */ ?>
                                        <?php /* <button onclick="confirmDelete(<?= $lang['id'] ?>, '<?= sanitize_output($lang['lang_name']) ?>')" class="text-error hover:text-red-400" title="Löschen"><i class="fas fa-trash"></i></button> */ ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-text-secondary text-center py-8"><?= __('no_languages_found') ?></p>
            <?php endif; ?>
        </section>
    </div>
    <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
</div>

<?php // Der Footer wird bereits vom Dispatcher in index.php geladen, daher hier entfernt ?>
