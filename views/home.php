<?php
// views/home.php - Standard-Startseite (für zukünftige dynamische Inhalte)
// Diese Datei wird von public/index.php über load_view('home', $data) geladen.
// Globale Variablen sind hier bereits verfügbar.
global $_SETTINGS, $current_lang, $is_demo_context;
?>
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center glass-effect rounded-2xl shadow-lg">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?= __('welcome_title') ?></h1>
            <p class="text-lg text-text-secondary max-w-2xl mb-8"><?= __('welcome_content_clean') ?></p>
            
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <?php if (is_feature_enabled('enable_articles')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/wiki-index" class="btn btn-primary">
                        <i class="fas fa-list mr-2"></i> <?= __('explore_wiki_button') ?>
                    </a>
                <?php endif; ?>
                <?php if (is_logged_in() && is_feature_enabled('enable_articles')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/article/create" class="btn btn-secondary">
                        <i class="fas fa-plus mr-2"></i> <?= __('create_article_link') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 gap-8 main-grid mt-8">
            <!-- Main Content Area - Hier werden später dynamische Inhalte wie "Neueste Artikel", "Beliebte Kategorien" oder "Personalisierte Empfehlungen" geladen -->
            <div class="lg:col-span-3 space-y-8">
                <section class="glass-effect rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-white mb-4"><?= __('dynamic_content_placeholder_title') ?></h2>
                    <p class="text-text-secondary"><?= __('dynamic_content_placeholder_text') ?></p>
                    <!-- Hier werden in Zukunft die echten, dynamischen Inhalte angezeigt -->
                </section>
            </div>
            <!-- Sidebar -->
            <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
        </div>
