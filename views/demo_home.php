<?php
// views/demo_home.php - Demo-Startseite mit vollständigen statischen Beispieldaten
// Diese Datei enthält den Hauptinhalt der Startseite mit statischen Beispieldaten.
// Sie wird von public/index.php über load_view('demo_home', $data) geladen.
// Globale Variablen sind hier bereits verfügbar.
global $_SETTINGS, $current_lang;
?>
        <!-- Stats Overview (Statische Demodaten) -->
        <div class="stats-grid mb-8">
            <div class="stat-card hover-lift">
                <div class="stat-number">247</div>
                <div class="stat-label"><?= __('articles_count') ?></div>
            </div>
            <div class="stat-card hover-lift">
                <div class="stat-number">12</div>
                <div class="stat-label"><?= __('categories_count') ?></div>
            </div>
            <div class="stat-card hover-lift">
                <div class="stat-number">89</div>
                <div class="stat-label"><?= __('authors_count') ?></div>
            </div>
            <div class="stat-card hover-lift">
                <div class="stat-number">1.2k</div>
                <div class="stat-label"><?= __('views_today') ?></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 gap-8 main-grid">
            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Featured Articles (Statische Demodaten) -->
                <?php if (is_feature_enabled('enable_articles')): ?>
                    <section class="glass-effect rounded-2xl p-6 glow-effect">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-star text-orange-500 mr-3"></i>
                                <?= __('featured_articles_title') ?>
                            </h2>
                            <!-- Dieser Link verweist auf die Demo Wiki-Index Seite selbst -->
                            <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/wiki-index" class="text-orange-500 hover:text-orange-400 text-sm"><?= __('view_all') ?> →</a>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <article class="category-card hover-lift">
                                <div class="flex items-start space-x-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-shield-alt text-white text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-white mb-2"><?= __('hero_guide_title') ?></h3>
                                        <p class="text-gray-400 text-sm mb-3"><?= __('hero_guide_description') ?></p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex space-x-1">
                                                <span class="tag"><?= __('tag_heroes') ?></span>
                                                <span class="tag"><?= __('tag_guide') ?></span>
                                            </div>
                                            <span class="text-xs text-gray-500"><?= __('x_days_ago', ['x' => 2]) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <article class="category-card hover-lift">
                                <div class="flex items-start space-x-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-trophy text-white text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-white mb-2"><?= __('alliance_championship_title') ?></h3>
                                        <p class="text-gray-400 text-sm mb-3"><?= __('alliance_championship_description') ?></p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex space-x-1">
                                                <span class="tag"><?= __('tag_events') ?></span>
                                                <span class="tag"><?= __('tag_alliance') ?></span>
                                            </div>
                                            <span class="text-xs text-gray-500"><?= __('x_day_ago', ['x' => 1]) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Categories (Statische Demodaten) -->
                <?php if (is_feature_enabled('enable_categories')): ?>
                    <section class="glass-effect rounded-2xl p-6">
                        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                            <i class="fas fa-sitemap text-orange-500 mr-3"></i>
                            <?= __('categories_title') ?>
                        </h2>
                        
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <article class="category-card hover-lift">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-ninja text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white"><?= __('heroes_category') ?></h3>
                                        <p class="text-xs text-gray-400"><?= __('x_articles', ['x' => 47]) ?></p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="article-item">
                                        <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/hero-a-full-guide" class="text-gray-300 hover:text-orange-500 text-sm flex-1"><?= __('hero_a_full_guide') ?></a>
                                        <span class="article-meta"><?= __('new_tag') ?></span>
                                    </div>
                                    <div class="article-item">
                                        <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/pvp-meta-builds" class="text-gray-300 hover:text-orange-500 text-sm flex-1"><?= __('pvp_meta_builds') ?></a>
                                        <span class="article-meta"><?= __('hot_tag') ?></span>
                                    </div>
                                </div>
                            </article>

                            <article class="category-card hover-lift">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chess text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white"><?= __('strategies_category') ?></h3>
                                        <p class="text-xs text-gray-400"><?= __('x_articles', ['x' => 32]) ?></p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="article-item">
                                        <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/early-game-optimization" class="text-gray-300 hover:text-orange-500 text-sm flex-1"><?= __('early_game_optimization') ?></a>
                                        <span class="article-meta"><?= __('time_ago_short', ['time' => '3h']) ?></span>
                                    </div>
                                    <div class="article-item">
                                        <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/resource-management" class="text-gray-300 hover:text-orange-500 text-sm flex-1"><?= __('resource_management') ?></a>
                                        <span class="article-meta"><?= __('time_ago_short', ['time' => '1d']) ?></span>
                                    </div>
                                </div>
                            </article>

                            <article class="category-card hover-lift">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-rocket text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white"><?= __('units_category') ?></h3>
                                        <p class="text-xs text-gray-400"><?= __('x_articles', ['x' => 28]) ?></p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="article-item">
                                        <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/air-units-guide" class="text-gray-300 hover:text-orange-500 text-sm flex-1"><?= __('air_units_guide') ?></a>
                                        <span class="article-meta"><?= __('time_ago_short', ['time' => '5h']) ?></span>
                                    </div>
                                    <div class="article-item">
                                        <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/tank-formations" class="text-gray-300 hover:text-orange-500 text-sm flex-1"><?= __('tank_formations') ?></a>
                                        <span class="article-meta"><?= __('time_ago_short', ['time' => '2d']) ?></span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <?php load_view('demo_sidebar'); // Lädt die Demo-Sidebar ?>
