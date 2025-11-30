<?php
// views/admin_language_form.php - Formular zum Hinzufügen/Bearbeiten einer Sprache
// Daten: $title, $current_lang, $language (für Bearbeitung), $errors

global $current_lang, $is_demo_context; // is_demo_context hinzufügen
$language = $language ?? null; // Null für "neu hinzufügen"
$errors = $errors ?? [];
$is_edit = ($language !== null);

$form_action = $is_edit ?
    '/' . htmlspecialchars($current_lang ?? DEFAULT_LANG) . '/admin/language/edit/' . sanitize_output($language['id']) :
    '/' . htmlspecialchars($current_lang ?? DEFAULT_LANG) . '/admin/language/edit/new';

$lang_code_val = sanitize_output($language['lang_code'] ?? '');
$lang_name_val = sanitize_output($language['lang_name'] ?? '');
$is_active_checked = ($language['is_active'] ?? true) ? 'checked' : ''; // Standardmäßig aktiv bei neu

?>

<div class="main-grid lg:grid-cols-4">
    <div class="lg:col-span-3 space-y-8">
        <section class="glass-effect rounded-2xl p-6 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-edit text-accent-orange mr-3"></i>
                <?= $title ?>
            </h2>

            <?php if (!empty($errors)): ?>
                <div class="bg-error/20 border border-error text-error px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold"><?= __('error_occurred') ?? 'Fehler aufgetreten' ?>:</strong>
                    <span class="block sm:inline"><?= implode('<br>', $errors) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= $form_action ?>" method="POST" class="space-y-6">
                <div>
                    <label for="lang_code" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('language_code_label') ?>
                    </label>
                    <input type="text" id="lang_code" name="lang_code" value="<?= $lang_code_val ?>"
                           class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                           placeholder="<?= __('language_code_label') ?>" required <?= $is_edit ? 'readonly' : '' ?>>
                    <?php if ($is_edit): ?>
                        <p class="text-text-muted text-xs mt-1"><?= __('language_code_readonly_hint') ?? 'Sprachcode kann nach Erstellung nicht geändert werden.' ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="lang_name" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('language_name_label') ?>
                    </label>
                    <input type="text" id="lang_name" name="lang_name" value="<?= $lang_name_val ?>"
                           class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                           placeholder="<?= __('language_name_label') ?>" required>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= $is_active_checked ?>
                           class="form-checkbox h-5 w-5 text-accent-orange rounded border-gray-600 bg-secondary-bg focus:ring-accent-orange">
                    <label for="is_active" class="ml-2 text-text-secondary text-sm">
                        <?= __('is_active_label') ?>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i><?= __('save_button') ?>
                    </button>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/languages" class="btn btn-secondary">
                        <?= __('cancel_button') ?>
                    </a>
                </div>
            </form>
        </section>
    </div>
    <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
</div>
