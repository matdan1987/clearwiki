<?php
// views/header.php
// Stellt sicher, dass globale Variablen wie $_SETTINGS, $current_lang, $is_demo_context verfügbar sind
global $_SETTINGS, $current_lang, $is_demo_context;
$is_demo_context = $is_demo_context ?? false; // Sicherstellen, dass die Variable immer gesetzt ist
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize_output($title ?? ($_SETTINGS['wiki_name'] ?? 'ClearWiki')) ?> | ClearWiki - Next Generation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary-bg: #0a0a0a;
            --secondary-bg: #111111;
            --card-bg: #1a1a1a;
            --border-color: #333333;
            --accent-orange: #ff6b35;
            --accent-blue: #4dabf7;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-muted: #666666;
            --success: #51cf66;
            --warning: #ffd43b;
            --error: #ff6b6b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-bg) 0%, var(--secondary-bg) 100%);
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex; /* Für Footer am unteren Rand */
            flex-direction: column; /* Für Footer am unteren Rand */
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Orbitron', sans-serif;
        }

        .glass-effect {
            background: rgba(26, 26, 26, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(51, 51, 51, 0.3);
        }

        .glow-effect {
            box-shadow: 0 0 20px rgba(255, 107, 53, 0.1);
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--accent-orange), #ff8a65);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }

        .btn-secondary {
            background: rgba(77, 171, 247, 0.1);
            color: var(--accent-blue);
            border: 1px solid var(--accent-blue);
        }

        .btn-secondary:hover {
            background: var(--accent-blue);
            color: white;
        }

        .search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(26, 26, 26, 0.8);
            border: 2px solid transparent;
            border-radius: 50px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .category-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-orange), var(--accent-blue));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .category-card:hover::before {
            opacity: 1;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            border-color: var(--accent-orange);
        }

        .article-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .article-item:hover {
            background: rgba(255, 107, 53, 0.05);
            border-color: rgba(255, 107, 53, 0.2);
        }

        .article-meta {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-left: auto;
        }

        .sidebar-section {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .mobile-menu {
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-nav {
                display: none;
            }
            
            .mobile-menu {
                display: block;
            }
            
            .main-grid {
                grid-template-columns: 1fr;
            }
            
            .search-container {
                max-width: 100%;
                margin: 1rem 0;
            }
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--card-bg), #2a2a2a);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-orange);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--accent-orange), #ff8a65);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 32px rgba(255, 107, 53, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .floating-action:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 40px rgba(255, 107, 53, 0.5);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .breadcrumb a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb a:hover {
            color: var(--accent-orange);
        }

        .tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(77, 171, 247, 0.1);
            color: var(--accent-blue);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.25rem;
        }

        .notification-dot {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 8px;
            height: 8px;
            background: var(--error);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #333 25%, #444 50%, #333 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="glass-effect sticky top-0 z-50 border-b border-gray-800">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book-open text-white text-lg"></i>
                        </div>
                        <div class="absolute -inset-1 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-300"></div>
                    </div>
                    <div>
                        <?php if (isset($_SETTINGS['display_wiki_name']) && $_SETTINGS['display_wiki_name']): ?>
                            <h1 class="text-xl font-bold text-white"><?= sanitize_output($_SETTINGS['wiki_name'] ?? 'ClearWiki') ?></h1>
                        <?php endif; ?>
                        <?php if (isset($_SETTINGS['display_slogan']) && $_SETTINGS['display_slogan']): ?>
                            <p class="text-xs text-gray-400"><?= sanitize_output($_SETTINGS['wiki_slogan'] ?? __('your_ultimate_guide')) ?></p>
                        <?php endif; ?>
                    </div>
                </a>

                <!-- Search Bar -->
                <div class="search-container hidden md:block">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="<?= __('search_articles_categories') ?>">
                </div>

                <!-- Desktop Navigation -->
                <nav class="desktop-nav hidden md:flex items-center space-x-6">
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/" class="text-gray-300 hover:text-orange-500 transition-colors">
                        <i class="fas fa-home mr-2"></i><?= __('home_link') ?>
                    </a>
                    <?php if (is_feature_enabled('enable_articles')): ?>
                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/wiki-index" class="text-gray-300 hover:text-orange-500 transition-colors">
                            <i class="fas fa-list mr-2"></i><?= __('wiki_index_link') ?>
                        </a>
                    <?php endif; ?>
                    <?php if (is_feature_enabled('enable_articles') && is_logged_in()): ?>
                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/article/create" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i><?= __('create_article_link') ?>
                        </a>
                    <?php endif; ?>
                    <div class="flex items-center space-x-2">
                        <?php if (!is_logged_in()): ?>
                            <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/login" class="btn btn-secondary"><?= __('login_link') ?></a>
                            <?php if (is_feature_enabled('enable_registration')): ?>
                                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/register" class="btn btn-primary"><?= __('register_link') ?></a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/logout" class="btn btn-secondary"><?= __('logout') ?></a>
                            <?php if (is_admin()): ?>
                                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/dashboard" class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center" title="<?= __('admin_dashboard_link') ?>">
                                    <i class="fas fa-user-shield text-white text-sm"></i>
                                </a>
                            <?php else: ?>
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center" title="<?= __('my_profile') ?>">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="mobile-menu md:hidden text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Search -->
            <div class="md:hidden mt-4">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="<?= __('search_short') ?>">
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay (beibehalten für erweiterte Funktionalität) -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-end z-50 p-4 hidden">
        <div class="mobile-menu-content bg-gray-900 rounded-lg shadow-lg w-full max-w-xs h-full overflow-y-auto p-6 transform translate-x-full transition-transform duration-300">
            <div class="flex justify-end mb-4">
                <button class="text-gray-400 hover:text-white" onclick="toggleMobileMenu()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="flex flex-col space-y-4">
                <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/" class="text-gray-300 hover:text-accent-orange text-lg">
                    <i class="fas fa-home mr-3"></i><?= __('home_link') ?>
                </a>
                <?php if (is_feature_enabled('enable_articles')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/wiki-index" class="text-gray-300 hover:text-accent-orange text-lg">
                        <i class="fas fa-list mr-3"></i><?= __('wiki_index_link') ?>
                    </a>
                <?php endif; ?>
                <?php if (is_feature_enabled('enable_articles') && is_logged_in()): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/article/create" class="text-gray-300 hover:text-accent-orange text-lg">
                        <i class="fas fa-plus-circle mr-3"></i><?= __('create_article_link') ?>
                    </a>
                <?php endif; ?>
                <?php if (!is_logged_in()): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/login" class="text-gray-300 hover:text-accent-orange text-lg">
                        <i class="fas fa-sign-in-alt mr-3"></i><?= __('login_link') ?>
                    </a>
                    <?php if (is_feature_enabled('enable_registration')): ?>
                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/register" class="btn btn-primary"><?= __('register_link') ?></a>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (is_admin()): ?>
                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/dashboard" class="text-gray-300 hover:text-accent-orange text-lg">
                            <i class="fas fa-shield-alt mr-3"></i><?= __('admin_dashboard_link') ?>
                        </a>
                    <?php endif; ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/logout" class="text-gray-300 hover:text-accent-orange text-lg">
                        <i class="fas fa-sign-out-alt mr-3"></i><?= __('logout') ?>
                    </a>
                <?php endif; ?>
                <?php if (is_feature_enabled('enable_impressum')): ?>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/impressum" class="text-gray-300 hover:text-accent-orange text-lg">
                        <i class="fas fa-info-circle mr-3"></i><?= __('impressum_title') ?>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <!-- Main Content Start -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/"><i class="fas fa-home"></i> <?= __('home_link') ?></a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span><?= sanitize_output($title ?? __('wiki_index_title')) ?></span>
        </nav>
