<?php
// views/demo_sidebar.php - Sidebar für Demo-Seite mit statischen Daten
// Diese Datei wird von views/demo_home.php geladen.
global $current_lang;
?>
<aside class="space-y-6">
    <!-- Quick Actions -->
    <div class="sidebar-section">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-bolt text-orange-500 mr-2"></i>
            <?= __('quick_actions_title') ?>
        </h3>
        <div class="space-y-3">
            <?php if (is_logged_in() && is_feature_enabled('enable_articles')): ?>
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/article/create" class="btn btn-primary w-full">
                    <i class="fas fa-plus mr-2"></i><?= __('create_article_link') ?>
                </a>
            <?php endif; ?>
            <?php if (is_logged_in() && is_feature_enabled('enable_pending_contributions')): ?>
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/my-contributions" class="btn btn-secondary w-full">
                    <i class="fas fa-edit mr-2"></i><?= __('edit_drafts_link') ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Activity (Statische Demodaten) -->
    <div class="sidebar-section">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-clock text-orange-500 mr-2"></i>
            <?= __('recent_activity_title') ?>
        </h3>
        <div class="space-y-3">
            <div class="flex items-center space-x-3 p-2 hover:bg-gray-800 rounded-lg transition-colors">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-plus text-white text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white truncate">Neuer Artikel erstellt</p>
                    <p class="text-xs text-gray-400">vor 15 Min.</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 p-2 hover:bg-gray-800 rounded-lg transition-colors">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-edit text-white text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white truncate">Guide aktualisiert</p>
                    <p class="text-xs text-gray-400">vor 1 Std.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Panel -->
    <?php if (is_admin()): ?>
        <div class="sidebar-section">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-user-shield text-orange-500 mr-2"></i>
                <?= __('admin_area_title') ?>
            </h3>
            <div class="space-y-2">
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/dashboard" class="block text-gray-300 hover:text-orange-500 text-sm py-2 px-3 rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i><?= __('admin_dashboard_link') ?>
                </a>
                <?php if (is_feature_enabled('enable_users')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/users" class="block text-gray-300 hover:text-orange-500 text-sm py-2 px-3 rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-users mr-2"></i><?= __('admin_users_link') ?>
                    </a>
                <?php endif; ?>
                <?php if (is_feature_enabled('enable_categories')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/categories" class="block text-gray-300 hover:text-orange-500 text-sm py-2 px-3 rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-tags mr-2"></i><?= __('admin_categories_link') ?>
                    </a>
                <?php endif; ?>
                <?php if (is_feature_enabled('enable_alliances')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/alliances" class="block text-gray-300 hover:text-orange-500 text-sm py-2 px-3 rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-handshake mr-2"></i><?= __('admin_alliances_link') ?>
                    </a>
                <?php endif; ?>
                <?php if (is_feature_enabled('enable_send_alliance_mail')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/send-alliance-mail" class="block text-gray-300 hover:text-orange-500 text-sm py-2 px-3 rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-envelope mr-2"></i><?= __('admin_send_alliance_mail_link') ?>
                    </a>
                <?php endif; ?>
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/settings" class="block text-gray-300 hover:text-orange-500 text-sm py-2 px-3 rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-cog mr-2"></i><?= __('admin_settings_title') ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Community Stats (Statische Demodaten) -->
    <div class="sidebar-section">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-users text-orange-500 mr-2"></i>
            <?= __('community_title') ?>
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm"><?= __('online_now') ?></span>
                <span class="text-green-500 font-semibold">42</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm"><?= __('active_today') ?></span>
                <span class="text-blue-500 font-semibold">189</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm"><?= __('total_members') ?></span>
                <span class="text-orange-500 font-semibold">2.8k</span>
            </div>
        </div>
    </div>
</aside>
