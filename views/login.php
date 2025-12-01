<?php
// views/login.php - Login-Formular
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
                <?= __('login_title') ?>
            </h2>
            <p class="mt-2 text-center text-sm text-text-secondary">
                <?= __('or') ?> 
                <?php if (is_feature_enabled('enable_registration')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/register" class="font-medium text-accent-orange hover:text-orange-400">
                        <?= __('register_link') ?>
                    </a>
                <?php endif; ?>
            </p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-error/20 border border-error text-error px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold"><?= __('login_failed') ?>:</strong>
                <span class="block sm:inline"><?= implode('<br>', $errors) ?></span>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/login" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="identifier" class="sr-only"><?= __('username_email_label') ?></label>
                    <input id="identifier" name="identifier" type="text" autocomplete="username" required
                           class="appearance-none rounded-none relative block w-full px-3 py-3 border border-border-color placeholder-text-muted text-text-primary focus:outline-none focus:ring-accent-orange focus:border-accent-orange focus:z-10 sm:text-sm bg-secondary-bg rounded-t-md"
                           placeholder="<?= __('username_email_placeholder') ?>">
                </div>
                <div>
                    <label for="password" class="sr-only"><?= __('password_label') ?></label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-3 border border-border-color placeholder-text-muted text-text-primary focus:outline-none focus:ring-accent-orange focus:border-accent-orange focus:z-10 sm:text-sm bg-secondary-bg rounded-b-md"
                           placeholder="<?= __('password_placeholder') ?>">
                </div>
            </div>

            <!-- Forgot password feature not yet implemented -->
            <?php if (false): // Disabled until password reset is implemented ?>
            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <a href="/<?= htmlspecialchars($current_lang) ?>/forgot-password" class="font-medium text-accent-blue hover:text-blue-400">
                        <?= __('forgot_password_link') ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-accent-orange hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-orange">
                    <?= __('login_button') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php load_view('footer'); ?>
