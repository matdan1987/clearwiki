<?php
// views/sidebar.php - Saubere Sidebar für Produktivsystem
// Diese Datei wird von views/home.php (und anderen Haupt-Views) geladen.
global $current_lang;
?>
<aside class="space-y-6">
    <!-- Quick Actions -->
    <?php if (is_logged_in() && is_feature_enabled('enable_articles')): ?>
        <div class="sidebar-section">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-bolt text-orange-500 mr-2"></i>
                <?= __('quick_actions_title') ?>
            </h3>
            <div class="space-y-3">
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/article/create" class="btn btn-primary w-full">
                    <i class="fas fa-plus mr-2"></i><?= __('create_article_link') ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

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
</aside>
