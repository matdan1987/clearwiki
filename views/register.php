<?php
// views/register.php - Registrierungsformular
// Daten, die an diese View übergeben werden können: $title, $current_lang, $errors (Array von Fehlermeldungen)

// Stelle sicher, dass $current_lang und $errors verfügbar sind
global $current_lang;
$errors = $errors ?? []; // Initialisiere $errors, falls nicht gesetzt
?>

<?php load_view('header', ['title' => $title]); ?>

<div class="flex items-center justify-center min-h-[calc(100vh-16rem)] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-10 bg-card-bg rounded-2xl shadow-2xl glass-effect hover-lift">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                <?= __('register_title') ?>
            </h2>
            <p class="mt-2 text-center text-sm text-text-secondary">
                <?= __('or') ?> 
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/login" class="font-medium text-accent-orange hover:text-orange-400">
                    <?= __('login_link') ?>
                </a>
            </p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-error/20 border border-error text-error px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold"><?= __('registration_failed') ?>:</strong>
                <span class="block sm:inline"><?= implode('<br>', $errors) ?></span>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/register" method="POST">
            <input type="hidden" name="action" value="register">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="username" class="sr-only"><?= __('username_label') ?></label>
                    <input id="username" name="username" type="text" autocomplete="username" required
                           class="appearance-none rounded-none relative block w-full px-3 py-3 border border-border-color placeholder-text-muted text-text-primary focus:outline-none focus:ring-accent-orange focus:border-accent-orange focus:z-10 sm:text-sm bg-secondary-bg rounded-t-md"
                           placeholder="<?= __('username_placeholder') ?>">
                </div>
                <div>
                    <label for="email" class="sr-only"><?= __('email_label') ?></label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-3 border border-border-color placeholder-text-muted text-text-primary focus:outline-none focus:ring-accent-orange focus:border-accent-orange focus:z-10 sm:text-sm bg-secondary-bg"
                           placeholder="<?= __('email_placeholder') ?>">
                </div>
                <div>
                    <label for="password" class="sr-only"><?= __('password_label') ?></label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-3 border border-border-color placeholder-text-muted text-text-primary focus:outline-none focus:ring-accent-orange focus:border-accent-orange focus:z-10 sm:text-sm bg-secondary-bg"
                           placeholder="<?= __('password_placeholder') ?>">
                </div>
                <div>
                    <label for="password_confirm" class="sr-only"><?= __('password_confirm_label') ?></label>
                    <input id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-3 border border-border-color placeholder-text-muted text-text-primary focus:outline-none focus:ring-accent-orange focus:border-accent-orange focus:z-10 sm:text-sm bg-secondary-bg rounded-b-md"
                           placeholder="<?= __('password_confirm_placeholder') ?>">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-accent-orange hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-orange">
                    <?= __('register_button') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php load_view('footer'); ?>
