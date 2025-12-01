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
            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-8">
                <?php if (is_feature_enabled('enable_articles')): ?>
                    <section class="glass-effect rounded-2xl p-6">
                        <h2 class="text-2xl font-bold text-white mb-4">
                            <i class="fas fa-book-open text-orange-500 mr-2"></i><?= __('getting_started_title') ?>
                        </h2>
                        <div class="text-text-secondary space-y-4">
                            <p><?= __('getting_started_intro') ?></p>
                            <div class="grid md:grid-cols-2 gap-4 mt-6">
                                <div class="bg-gray-800 bg-opacity-50 p-4 rounded-lg">
                                    <h3 class="text-white font-semibold mb-2">
                                        <i class="fas fa-search text-blue-400 mr-2"></i><?= __('explore_content_title') ?>
                                    </h3>
                                    <p class="text-sm text-text-secondary mb-3"><?= __('explore_content_text') ?></p>
                                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/wiki-index" class="text-orange-500 hover:text-orange-400 text-sm">
                                        <?= __('browse_wiki') ?> →
                                    </a>
                                </div>
                                <?php if (is_feature_enabled('enable_registration') || is_logged_in()): ?>
                                    <div class="bg-gray-800 bg-opacity-50 p-4 rounded-lg">
                                        <h3 class="text-white font-semibold mb-2">
                                            <i class="fas fa-pen text-green-400 mr-2"></i><?= __('contribute_title') ?>
                                        </h3>
                                        <p class="text-sm text-text-secondary mb-3"><?= __('contribute_text') ?></p>
                                        <?php if (is_logged_in()): ?>
                                            <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/article/create" class="text-orange-500 hover:text-orange-400 text-sm">
                                                <?= __('create_first_article') ?> →
                                            </a>
                                        <?php else: ?>
                                            <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/register" class="text-orange-500 hover:text-orange-400 text-sm">
                                                <?= __('register_to_contribute') ?> →
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
            <!-- Sidebar -->
            <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
        </div>
