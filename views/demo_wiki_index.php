<?php
// views/demo_wiki_index.php - Neues Demo Wiki-Index Inhaltsverzeichnis
// Diese Seite dient als Inhaltsverzeichnis für die Demo-Wiki-Artikel.
global $_SETTINGS, $current_lang, $is_demo_context;
?>
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center glass-effect rounded-2xl shadow-lg">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?= __('wiki_index_title') ?> <span class="text-accent-orange"><?= __('demo_version_suffix') ?></span></h1>
            <p class="text-lg text-text-secondary max-w-2xl mb-8"><?= __('wiki_index_intro_demo') ?? 'Hier finden Sie eine Übersicht aller Kategorien und Artikel in unserer Demo-Umgebung.' ?></p>
            
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <?php if (is_feature_enabled('enable_articles')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/create" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> <?= __('create_article_link') ?> (Demo)
                    </a>
                <?php endif; ?>
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/" class="btn btn-secondary">
                    <i class="fas fa-home mr-2"></i> <?= __('back_to_demo_home') ?? 'Zurück zur Demo-Startseite' ?>
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 gap-8 main-grid mt-8">
            <!-- Main Content Area - Hier wird das Inhaltsverzeichnis angezeigt -->
            <div class="lg:col-span-3 space-y-8">
                <?php if (is_feature_enabled('enable_categories')): ?>
                    <section class="glass-effect rounded-2xl p-6">
                        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                            <i class="fas fa-sitemap text-accent-orange mr-3"></i>
                            <?= __('categories_title') ?>
                        </h2>
                        
                        <div class="space-y-4 text-text-secondary text-lg">
                            <!-- Hauptkategorie: Wiki -->
                            <div class="category-tree-item">
                                <div class="flex items-center cursor-pointer toggle-category" data-target="wiki-content">
                                    <i class="fas fa-chevron-down mr-2 text-accent-orange toggle-icon"></i>
                                    <i class="fas fa-folder mr-2 text-accent-blue"></i>
                                    <span class="font-semibold text-text-primary">Wiki</span>
                                </div>
                                <ul id="wiki-content" class="ml-6 mt-2 space-y-1">
                                    <!-- Unterkategorie: Helden -->
                                    <li class="category-tree-item">
                                        <div class="flex items-center cursor-pointer toggle-category" data-target="heroes-content">
                                            <i class="fas fa-chevron-down mr-2 text-accent-orange toggle-icon"></i>
                                            <i class="fas fa-folder mr-2 text-accent-blue"></i>
                                            <span class="font-semibold text-text-primary"><?= __('heroes_category') ?></span>
                                        </div>
                                        <ul id="heroes-content" class="ml-6 mt-2 space-y-1">
                                            <li><a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/hero-a-full-guide" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('hero_a_full_guide') ?></a></li>
                                            <li><a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/pvp-meta-builds" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('pvp_meta_builds') ?></a></li>
                                            <li><a href="#" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('hero_tier_list') ?? 'Helden-Tierliste' ?></a></li>
                                        </ul>
                                    </li>
                                    <!-- Unterkategorie: Strategien -->
                                    <li class="category-tree-item">
                                        <div class="flex items-center cursor-pointer toggle-category" data-target="strategies-content">
                                            <i class="fas fa-chevron-down mr-2 text-accent-orange toggle-icon"></i>
                                            <i class="fas fa-folder mr-2 text-accent-blue"></i>
                                            <span class="font-semibold text-text-primary"><?= __('strategies_category') ?></span>
                                        </div>
                                        <ul id="strategies-content" class="ml-6 mt-2 space-y-1">
                                            <li><a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/early-game-optimization" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('early_game_optimization') ?></a></li>
                                            <li><a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/resource-management" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('resource_management') ?></a></li>
                                            <li><a href="#" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('pvp_tactics') ?? 'PvP-Taktiken' ?></a></li>
                                        </ul>
                                    </li>
                                    <!-- Unterkategorie: Einheiten -->
                                    <li class="category-tree-item">
                                        <div class="flex items-center cursor-pointer toggle-category" data-target="units-content">
                                            <i class="fas fa-chevron-down mr-2 text-accent-orange toggle-icon"></i>
                                            <i class="fas fa-folder mr-2 text-accent-blue"></i>
                                            <span class="font-semibold text-text-primary"><?= __('units_category') ?></span>
                                        </div>
                                        <ul id="units-content" class="ml-6 mt-2 space-y-1">
                                            <li><a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/air-units-guide" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('air_units_guide') ?></a></li>
                                            <li><a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/demo/article/show/tank-formations" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('tank_formations') ?></a></li>
                                            <li><a href="#" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i><?= __('unit_counter_system') ?? 'Einheiten-Konter-System' ?></a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <!-- Hauptkategorie: Events -->
                            <div class="category-tree-item">
                                <div class="flex items-center cursor-pointer toggle-category" data-target="events-content">
                                    <i class="fas fa-chevron-down mr-2 text-accent-orange toggle-icon"></i>
                                    <i class="fas fa-folder mr-2 text-accent-blue"></i>
                                    <span class="font-semibold text-text-primary">Events</span>
                                </div>
                                <ul id="events-content" class="ml-6 mt-2 space-y-1">
                                    <li><a href="#" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i>Allianz-Krieg</a></li>
                                    <li><a href="#" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i>Weltboss-Event</a></li>
                                </ul>
                            </div>

                            <!-- Hauptkategorie: Allgemeines -->
                            <div class="category-tree-item">
                                <div class="flex items-center cursor-pointer toggle-category" data-target="general-content">
                                    <i class="fas fa-chevron-down mr-2 text-accent-orange toggle-icon"></i>
                                    <i class="fas fa-folder mr-2 text-accent-blue"></i>
                                    <span class="font-semibold text-text-primary">Allgemeines</span>
                                </div>
                                <ul id="general-content" class="ml-6 mt-2 space-y-1">
                                    <li><a href="#" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i>Einführung ins Spiel</a></li>
                                    <li><a href="#" class="hover:text-accent-orange"><i class="fas fa-file-alt mr-2 text-text-muted"></i>Glossar</a></li>
                                </ul>
                            </div>
                        </div>
                    </section>
                <?php else: ?>
                    <section class="glass-effect rounded-2xl p-6">
                        <p class="text-text-secondary text-center py-8"><?= __('categories_disabled_message') ?? 'Kategorien sind derzeit deaktiviert.' ?></p>
                    </section>
                <?php endif; ?>
            </div>
            <!-- Sidebar -->
            <?php load_view('demo_sidebar'); // Lädt die Demo-Sidebar ?>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-category');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const targetContent = document.getElementById(targetId);
            const icon = this.querySelector('.toggle-icon');

            if (targetContent) {
                targetContent.classList.toggle('hidden'); // Tailwind hidden class
                if (targetContent.classList.contains('hidden')) {
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-right');
                } else {
                    icon.classList.replace('fa-chevron-right', 'fa-chevron-down');
                }
            }
        });
    });
});
</script>
