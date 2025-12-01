<?php
// views/footer.php - Sauberer Footer für Produktivsystem mit dynamischen Links
// Diese Datei wird von views/home.php (und anderen Haupt-Views) geladen.
global $_SETTINGS, $current_lang;

// Dynamische Footer-Links abrufen
$footer_links = get_footer_links();
?>
    </main><!-- Main Content End -->

    <!-- Footer -->
    <footer class="glass-effect border-t border-gray-800 mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="font-semibold text-white mb-4"><?= sanitize_output($_SETTINGS['wiki_name'] ?? 'ClearWiki') ?></h4>
                    <p class="text-gray-400 text-sm"><?= sanitize_output($_SETTINGS['wiki_slogan'] ?? __('default_footer_slogan')) ?></p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4"><?= __('footer_wiki_title') ?></h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <?php foreach ($footer_links['wiki'] as $link): ?>
                            <?php if ($link['is_active']): ?>
                                <li><a href="<?= htmlspecialchars($link['url']) ?>" class="hover:text-orange-500"><?= __($link['text_key']) ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4"><?= __('footer_community_title') ?></h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <?php foreach ($footer_links['community'] as $link): ?>
                            <?php if ($link['is_active']): ?>
                                <li><a href="<?= htmlspecialchars($link['url']) ?>" class="hover:text-orange-500"><?= __($link['text_key']) ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4"><?= __('footer_legal_title') ?></h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <?php foreach ($footer_links['legal'] as $link): ?>
                            <?php if ($link['is_active']): ?>
                                <li><a href="<?= htmlspecialchars($link['url']) ?>" class="hover:text-orange-500"><?= __($link['text_key']) ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400 text-sm">
                    <?= sanitize_output($_SETTINGS['footer_text'] ?? ('© ' . date('Y') . ' ClearWiki - Entwickelt mit ❤️ von Daniel Mattick.')) ?>
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating Action Button -->
    <?php if (is_feature_enabled('enable_articles') && is_logged_in()): ?>
        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/article/create" class="floating-action" title="<?= __('create_article_short') ?>">
            <i class="fas fa-plus text-xl"></i>
        </a>
    <?php endif; ?>

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuButton');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            
            function toggleMobileMenu() {
                mobileMenuOverlay.classList.toggle('hidden'); // Toggle hidden class for overlay
                mobileMenuOverlay.querySelector('.mobile-menu-content').classList.toggle('translate-x-full'); // Toggle slide effect
                
                const icon = mobileMenuBtn.querySelector('i');
                if (mobileMenuOverlay.classList.contains('hidden')) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleMobileMenu);
            }
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', function(e) {
                    // Close when clicking on overlay background, not on the menu content itself
                    if (e.target === mobileMenuOverlay) { 
                        toggleMobileMenu();
                    }
                });
            }

            // Search functionality with debounce
            const searchInputs = document.querySelectorAll('.search-input');
            let searchTimeout;
            
            searchInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch(e.target.value);
                    }, 300);
                });

                // Search on Enter
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        performSearch(e.target.value);
                    }
                });
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Category cards interaction
            const categoryCards = document.querySelectorAll('.category-card');
            categoryCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'A') {
                        const firstLink = card.querySelector('a');
                        if (firstLink) {
                            firstLink.click();
                        }
                    }
                });
            });

            // Loading states for dynamic content
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (this.href && this.href.includes('#')) {
                        e.preventDefault();
                        addLoadingState(this);
                        
                        // Simulate loading
                        setTimeout(() => {
                            removeLoadingState(this);
                            // Note: Loading state simulation removed - actual navigation is preferred
                    }
                });
            });

            // Auto-hide notifications
            const notifications = document.querySelectorAll('.notification-dot');
            notifications.forEach(dot => {
                setTimeout(() => {
                    dot.style.animation = 'none';
                    dot.style.opacity = '0.5';
                }, 10000);
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K for search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const firstSearchInput = document.querySelector('.search-input');
                    if (firstSearchInput) {
                        firstSearchInput.focus();
                        firstSearchInput.select();
                    }
                }

                // Ctrl/Cmd + N for new article
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    const createLink = document.querySelector('a[href*="/article/create"]');
                    if (createLink) {
                        createLink.click();
                    }
                }

                // Escape to close mobile menu
                if (e.key === 'Escape') {
                    // Close mobile menu if open
                    if (mobileMenuOverlay && !mobileMenuOverlay.classList.contains('hidden')) {
                        toggleMobileMenu();
                    }
                }
            });

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Animate cards on scroll
            const animatedElements = document.querySelectorAll('.category-card, .stat-card, .sidebar-section');
            animatedElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });

            // Auto-refresh stats (This function is only relevant for the demo_footer)
            // It will only execute if the element with id 'server-response-time' exists.
            setInterval(function() {
                const responseTimeElement = document.querySelector('#server-response-time');
                if (responseTimeElement) {
                    // Only call updateStats if the element is present (i.e., on demo_footer)
                    updateStats(); 
                }
            }, 30000); 

            // Initialize tooltips for better UX
            initializeTooltips();

            // Load user preferences
            loadUserPreferences();
        });

        // Search function
        function performSearch(query) {
            if (query.length < 2) return;
            
            console.log('Searching for:', query);
            
            // Show search results overlay
            const searchResults = createSearchResults(query);
            showSearchResults(searchResults);
        }

        // Create search results (demo)
        function createSearchResults(query) {
            return [
                { title: `Artikel zu "${query}"`, type: 'article', category: 'Helden' },
                { title: `Guide: ${query} Strategien`, type: 'guide', category: 'Strategien' },
                { title: `${query} FAQ`, type: 'faq', category: 'Hilfe' }
            ];
        }

        // Show toast notification
        function showToast(message, type = 'info', duration = 4000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColor = {
                'success': 'bg-green-600',
                'error': 'bg-red-600',
                'warning': 'bg-yellow-600',
                'info': 'bg-blue-600'
            }[type] || 'bg-blue-600';

            const icon = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle',
                'warning': 'fas fa-exclamation-triangle',
                'info': 'fas fa-info-circle'
            }[type] || 'fas fa-info-circle';

            toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300`;
            toast.innerHTML = `
                <i class="${icon}"></i>
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            // Auto remove
            setTimeout(() => {
                toast.style.transform = 'translateX(full)';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // Update stats (demo)
        // This function is only relevant for the demo_footer, so it will be called there.
        function updateStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const currentValue = parseInt(stat.textContent.replace(/[^\d]/g, ''));
                const newValue = currentValue + Math.floor(Math.random() * 5);
                animateNumber(stat, currentValue, newValue);
            });
        }

        // Animate number changes
        function animateNumber(element, start, end) {
            const duration = 1000;
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const current = Math.floor(start + (end - start) * progress);
                element.textContent = current.toLocaleString();
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            requestAnimationFrame(update);
        }

        // Initialize tooltips
        function initializeTooltips() {
            const elementsWithTooltips = document.querySelectorAll('[title]');
            elementsWithTooltips.forEach(element => {
                element.addEventListener('mouseenter', showTooltip);
                element.addEventListener('mouseleave', hideTooltip);
            });
        }

        // Show tooltip
        function showTooltip(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute bg-gray-800 text-white text-sm px-3 py-2 rounded-lg z-50 pointer-events-none';
            tooltip.textContent = e.target.title;
            tooltip.id = 'tooltip';
            
            document.body.appendChild(tooltip);
            
            const rect = e.target.getBoundingClientRect();
            tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            
            // Remove title to prevent default tooltip
            e.target.dataset.originalTitle = e.target.title;
            e.target.removeAttribute('title');
        }

        // Hide tooltip
        function hideTooltip(e) {
            const tooltip = document.getElementById('tooltip');
            if (tooltip) {
                tooltip.remove();
            }
            
            // Restore original title
            if (e.target.dataset.originalTitle) {
                e.target.title = e.target.dataset.originalTitle;
                delete e.target.dataset.originalTitle;
            }
        }

        // Load user preferences
        function loadUserPreferences() {
            // Demo: Load saved theme, language, etc.
            const preferences = {
                theme: 'dark',
                language: 'de',
                autoSave: true,
                notifications: true
            };
            
            console.log('User preferences loaded:', preferences);
        }

        // Show search results overlay
        function showSearchResults(results) {
            const existingOverlay = document.getElementById('search-overlay');
            if (existingOverlay) {
                existingOverlay.remove();
            }
            
            const overlay = document.createElement('div');
            overlay.id = 'search-overlay';
            overlay.className = 'absolute top-full left-0 right-0 bg-gray-800 border border-gray-600 rounded-lg mt-2 p-4 z-50 shadow-2xl';
            
            overlay.innerHTML = `
                <div class="space-y-2">
                    ${results.map(result => `
                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-700 rounded-lg cursor-pointer">
                            <i class="fas fa-file-alt text-gray-400"></i>
                            <div class="flex-1">
                                <div class="text-white text-sm">${result.title}</div>
                                <div class="text-gray-400 text-xs">${result.category}</div>
                            </div>
                            <span class="text-xs text-gray-500">${result.type}</span>
                        </div>
                    `).join('')}
                </div>
                <div class="border-t border-gray-600 mt-4 pt-3 text-center">
                    <a href="#" class="text-orange-500 text-sm hover:text-orange-400">Alle Ergebnisse anzeigen →</a>
                </div>
            `;
            
            const searchContainer = document.querySelector('.search-container');
            searchContainer.style.position = 'relative';
            searchContainer.appendChild(overlay);
            
            // Close on click outside
            setTimeout(() => {
                document.addEventListener('click', function closeSearch(e) {
                    if (!searchContainer.contains(e.target)) {
                        overlay.remove();
                        document.removeEventListener('click', closeSearch);
                    }
                });
            }, 100);
        }

        // Performance monitoring
        // This function is only relevant for the demo_footer, so it will be called there.
        function updatePerformanceTime() {
            const loadTime = performance.now();
            console.log(`Page loaded in ${Math.round(loadTime)}ms`);
            
            // Update footer with actual load time
            const responseTimeElement = document.querySelector('#server-response-time'); // This ID is in demo_footer.php
            if (responseTimeElement) {
                responseTimeElement.textContent = `${Math.round(loadTime)}ms`;
            }
        }
        window.addEventListener('load', updatePerformanceTime);
    </script>
</body>
</html>
