<?php
// views/admin_language_string_form.php - Formular zum Hinzufügen/Bearbeiten eines Sprachstrings
// Daten: $title, $current_lang, $string (für Bearbeitung), $errors, $supported_languages

global $current_lang, $is_demo_context; // is_demo_context hinzufügen
$string = $string ?? null; // Null für "neu hinzufügen"
$errors = $errors ?? [];
$supported_languages = $supported_languages ?? []; // Sicherstellen, dass definiert

$is_edit = ($string !== null);

$form_action = $is_edit ?
    '/' . htmlspecialchars($current_lang ?? DEFAULT_LANG) . '/admin/language/string/edit/' . sanitize_output($string['id']) :
    '/' . htmlspecialchars($current_lang ?? DEFAULT_LANG) . '/admin/language/string/new';

$lang_key_val = sanitize_output($string['lang_key'] ?? '');
$lang_code_val = sanitize_output($string['lang_code'] ?? ($_GET['lang_code'] ?? '')); // Vorbelegung bei Neuanlage
$value_val = sanitize_output($string['value'] ?? '');

?>

<?php // Der Header wird bereits vom Dispatcher in index.php geladen, daher hier entfernt ?>
<?php // load_view('header', ['title' => $title, 'current_lang' => $current_lang]); ?>

<div class="main-grid lg:grid-cols-4">
    <div class="lg:col-span-3 space-y-8">
        <section class="glass-effect rounded-2xl p-6 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-code text-accent-orange mr-3"></i>
                <?= $title ?>
            </h2>

            <?php if (!empty($errors)): ?>
                <div class="bg-error/20 border border-error text-error px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold"><?= __('error_occurred') ?>:</strong>
                    <span class="block sm:inline"><?= implode('<br>', $errors) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= $form_action ?>" method="POST" class="space-y-6">
                <div>
                    <label for="lang_key" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('lang_key_label') ?>
                    </label>
                    <input type="text" id="lang_key" name="lang_key" value="<?= $lang_key_val ?>"
                           class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                           placeholder="<?= __('lang_key_label') ?>" required>
                </div>

                <div>
                    <label for="lang_code" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('language_code_label') ?>
                    </label>
                    <?php if ($is_edit): ?>
                        <input type="text" id="lang_code" name="lang_code" value="<?= $lang_code_val ?>"
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight bg-secondary-bg border-border-color cursor-not-allowed"
                               readonly>
                        <p class="text-text-muted text-xs mt-1"><?= __('language_code_readonly_hint') ?? 'Sprachcode kann nach Erstellung nicht geändert werden.' ?></p>
                    <?php else: ?>
                        <select id="lang_code" name="lang_code" required
                                class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color">
                            <?php foreach ($supported_languages as $lang): ?>
                                <option value="<?= sanitize_output($lang['lang_code']) ?>"
                                    <?= ($lang_code_val === $lang['lang_code']) ? 'selected' : '' ?>>
                                    <?= sanitize_output($lang['lang_name']) ?> (<?= sanitize_output($lang['lang_code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="value" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('lang_value_label') ?>
                    </label>
                    <textarea id="value" name="value" rows="5"
                              class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                              placeholder="<?= __('lang_value_label') ?>" required><?= $value_val ?></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i><?= __('save_button') ?>
                    </button>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/language/strings/<?= sanitize_output($lang_code_val) ?>" class="btn btn-secondary">
                        <?= __('cancel_button') ?>
                    </a>
                </div>
            </form>
        </section>
    </div>
    <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
</div>

<?php // Der Footer wird bereits vom Dispatcher in index.php geladen, daher hier entfernt ?>
<?php // load_view('footer', ['current_lang' => $current_lang]); ?>
