-- Additional Language Strings for ClearWiki
-- Import this file after schema.sql to add all missing translations
-- Usage: mysql -u clearwiki -p clearwiki < additional_translations.sql

USE clearwiki;

-- =====================================================
-- Additional German Language Strings
-- =====================================================
INSERT IGNORE INTO language_strings (lang_key, lang_code, value, created_at, updated_at) VALUES
-- Home Page
('welcome_content_clean', 'de', 'Willkommen in Ihrem Next-Generation Gaming Wiki! Entdecken Sie Guides, Strategien und Community-Inhalte.', NOW(), NOW()),
('explore_wiki_button', 'de', 'Wiki durchsuchen', NOW(), NOW()),
('create_article_link', 'de', 'Artikel erstellen', NOW(), NOW()),
('dynamic_content_placeholder_title', 'de', 'Dynamische Inhalte', NOW(), NOW()),
('dynamic_content_placeholder_text', 'de', 'Hier werden später die neuesten Artikel, Kategorien und Updates angezeigt.', NOW(), NOW()),

-- Admin Area
('add_language_button', 'de', 'Sprache hinzufügen', NOW(), NOW()),
('yes', 'de', 'Ja', NOW(), NOW()),
('no', 'de', 'Nein', NOW(), NOW()),
('edit_language', 'de', 'Sprache bearbeiten', NOW(), NOW()),
('manage_strings', 'de', 'Strings verwalten', NOW(), NOW()),
('no_languages_found', 'de', 'Keine Sprachen gefunden.', NOW(), NOW()),
('back_to_languages', 'de', 'Zurück zu Sprachen', NOW(), NOW()),
('add_new_string', 'de', 'Neuen String hinzufügen', NOW(), NOW()),
('no_strings_found', 'de', 'Keine Sprachstrings gefunden.', NOW(), NOW()),
('edit_string', 'de', 'String bearbeiten', NOW(), NOW()),
('language_code_label', 'de', 'Sprachcode', NOW(), NOW()),
('language_name_label', 'de', 'Sprachname', NOW(), NOW()),
('is_active_label', 'de', 'Aktiv', NOW(), NOW()),
('save_button', 'de', 'Speichern', NOW(), NOW()),
('cancel_button', 'de', 'Abbrechen', NOW(), NOW()),
('error_occurred', 'de', 'Fehler aufgetreten', NOW(), NOW()),
('language_code_readonly_hint', 'de', 'Sprachcode kann nach Erstellung nicht geändert werden.', NOW(), NOW()),

-- Demo Page
('articles_count', 'de', 'Artikel', NOW(), NOW()),
('categories_count', 'de', 'Kategorien', NOW(), NOW()),
('authors_count', 'de', 'Autoren', NOW(), NOW()),
('views_today', 'de', 'Aufrufe heute', NOW(), NOW()),
('featured_articles_title', 'de', 'Empfohlene Artikel', NOW(), NOW()),
('view_all', 'de', 'Alle anzeigen', NOW(), NOW()),
('hero_guide_title', 'de', 'Held A - Vollständiger Guide', NOW(), NOW()),
('hero_guide_description', 'de', 'Meistern Sie Held A mit diesem umfassenden Guide zu Skills, Builds und Taktiken.', NOW(), NOW()),
('tag_heroes', 'de', 'Helden', NOW(), NOW()),
('tag_guide', 'de', 'Guide', NOW(), NOW()),
('tag_events', 'de', 'Events', NOW(), NOW()),
('tag_alliance', 'de', 'Allianz', NOW(), NOW()),
('alliance_championship_title', 'de', 'Allianz-Meisterschaft 2024', NOW(), NOW()),
('alliance_championship_description', 'de', 'Alle Infos zum größten Event des Jahres - Belohnungen, Strategien und Zeitplan.', NOW(), NOW()),
('categories_title', 'de', 'Kategorien', NOW(), NOW()),
('heroes_category', 'de', 'Helden', NOW(), NOW()),
('hero_a_full_guide', 'de', 'Held A - Vollständiger Guide', NOW(), NOW()),
('pvp_meta_builds', 'de', 'PvP Meta-Builds', NOW(), NOW()),
('new_tag', 'de', 'Neu', NOW(), NOW()),
('hot_tag', 'de', 'Heiß', NOW(), NOW()),
('strategies_category', 'de', 'Strategien', NOW(), NOW()),
('early_game_optimization', 'de', 'Early-Game-Optimierung', NOW(), NOW()),
('resource_management', 'de', 'Ressourcenverwaltung', NOW(), NOW()),
('units_category', 'de', 'Einheiten', NOW(), NOW()),
('air_units_guide', 'de', 'Lufteinheiten-Guide', NOW(), NOW()),
('tank_formations', 'de', 'Panzer-Formationen', NOW(), NOW()),
('hero_tier_list', 'de', 'Helden-Tierliste', NOW(), NOW()),
('pvp_tactics', 'de', 'PvP-Taktiken', NOW(), NOW()),
('unit_counter_system', 'de', 'Einheiten-Konter-System', NOW(), NOW()),
('categories_disabled_message', 'de', 'Kategorien sind derzeit deaktiviert.', NOW(), NOW()),

-- Navigation and Footer
('default_footer_slogan', 'de', 'Ihr ultimativer Gaming-Guide', NOW(), NOW()),
('footer_wiki_title', 'de', 'Wiki', NOW(), NOW()),
('footer_community_title', 'de', 'Community', NOW(), NOW()),
('footer_legal_title', 'de', 'Rechtliches', NOW(), NOW()),
('create_article_short', 'de', 'Erstellen', NOW(), NOW()),
('wiki_index_intro_demo', 'de', 'Hier finden Sie eine Übersicht aller Kategorien und Artikel in unserer Demo-Umgebung.', NOW(), NOW()),
('back_to_demo_home', 'de', 'Zurück zur Demo-Startseite', NOW(), NOW()),

-- Maintenance
('maintenance_mode_title', 'de', 'Wartungsmodus', NOW(), NOW()),
('maintenance_mode_message', 'de', 'Das Wiki befindet sich derzeit im Wartungsmodus', NOW(), NOW()),
('try_again_later', 'de', 'Bitte versuchen Sie es später erneut.', NOW(), NOW()),

-- Registration
('or', 'de', 'oder', NOW(), NOW()),
('login_link', 'de', 'Anmelden', NOW(), NOW()),
('registration_failed', 'de', 'Registrierung fehlgeschlagen', NOW(), NOW()),
('username_label', 'de', 'Benutzername', NOW(), NOW()),
('username_placeholder', 'de', 'Ihr Benutzername', NOW(), NOW()),
('email_label', 'de', 'E-Mail', NOW(), NOW()),
('email_placeholder', 'de', 'ihre@email.de', NOW(), NOW()),
('password_label', 'de', 'Passwort', NOW(), NOW()),
('password_placeholder', 'de', 'Ihr Passwort', NOW(), NOW()),
('password_confirm_label', 'de', 'Passwort bestätigen', NOW(), NOW()),
('password_confirm_placeholder', 'de', 'Passwort wiederholen', NOW(), NOW()),
('register_button', 'de', 'Registrieren', NOW(), NOW()),

-- 404 Page
('page_not_found_title', 'de', 'Seite nicht gefunden', NOW(), NOW()),
('page_not_found_message', 'de', 'Die angeforderte Seite konnte nicht gefunden werden.', NOW(), NOW()),
('back_to_homepage', 'de', 'Zurück zur Startseite', NOW(), NOW()),

-- Header/Navigation
('your_ultimate_guide', 'de', 'Ihr ultimativer Guide', NOW(), NOW()),
('search_articles_categories', 'de', 'Artikel & Kategorien durchsuchen...', NOW(), NOW()),
('home_link', 'de', 'Startseite', NOW(), NOW()),
('wiki_index_link', 'de', 'Wiki-Index', NOW(), NOW()),
('updates_link', 'de', 'Updates', NOW(), NOW()),
('register_link', 'de', 'Registrieren', NOW(), NOW()),
('admin_dashboard_link', 'de', 'Admin-Dashboard', NOW(), NOW()),
('my_profile', 'de', 'Mein Profil', NOW(), NOW()),
('search_short', 'de', 'Suchen...', NOW(), NOW()),

-- Article Edit
('title_label', 'de', 'Titel', NOW(), NOW()),
('article_title_placeholder', 'de', 'Geben Sie den Artikel-Titel ein...', NOW(), NOW()),
('content_label', 'de', 'Inhalt', NOW(), NOW()),
('article_content_placeholder', 'de', 'Schreiben Sie Ihren Artikel hier...', NOW(), NOW()),
('guest_author_name_label', 'de', 'Gastautor Name', NOW(), NOW()),
('guest_author_name_placeholder', 'de', 'Name des Gastautors', NOW(), NOW()),
('guest_author_email_label', 'de', 'Gastautor E-Mail', NOW(), NOW()),
('guest_author_email_placeholder', 'de', 'E-Mail des Gastautors', NOW(), NOW()),
('language_label', 'de', 'Sprache', NOW(), NOW()),
('status_label', 'de', 'Status', NOW(), NOW()),
('status_pending', 'de', 'Ausstehend', NOW(), NOW()),
('status_published', 'de', 'Veröffentlicht', NOW(), NOW()),

-- Sidebar
('quick_actions_title', 'de', 'Schnellaktionen', NOW(), NOW()),
('edit_drafts_link', 'de', 'Entwürfe bearbeiten', NOW(), NOW()),
('recent_activity_title', 'de', 'Letzte Aktivitäten', NOW(), NOW()),
('recent_activity_placeholder_sidebar', 'de', 'Noch keine Aktivitäten vorhanden.', NOW(), NOW()),
('admin_area_title', 'de', 'Admin-Bereich', NOW(), NOW()),
('admin_users_link', 'de', 'Benutzerverwaltung', NOW(), NOW()),
('admin_categories_link', 'de', 'Kategorien', NOW(), NOW()),
('admin_alliances_link', 'de', 'Allianzen', NOW(), NOW()),
('admin_send_alliance_mail_link', 'de', 'Rundmail senden', NOW(), NOW()),
('community_title', 'de', 'Community', NOW(), NOW()),
('community_stats_placeholder_sidebar', 'de', 'Community-Statistiken werden hier angezeigt.', NOW(), NOW());

-- =====================================================
-- Additional English Language Strings
-- =====================================================
INSERT IGNORE INTO language_strings (lang_key, lang_code, value, created_at, updated_at) VALUES
-- Home Page
('welcome_content_clean', 'en', 'Welcome to your Next-Generation Gaming Wiki! Discover guides, strategies, and community content.', NOW(), NOW()),
('explore_wiki_button', 'en', 'Explore Wiki', NOW(), NOW()),
('create_article_link', 'en', 'Create Article', NOW(), NOW()),
('dynamic_content_placeholder_title', 'en', 'Dynamic Content', NOW(), NOW()),
('dynamic_content_placeholder_text', 'en', 'Latest articles, categories, and updates will be displayed here.', NOW(), NOW()),

-- Admin Area
('add_language_button', 'en', 'Add Language', NOW(), NOW()),
('yes', 'en', 'Yes', NOW(), NOW()),
('no', 'en', 'No', NOW(), NOW()),
('edit_language', 'en', 'Edit Language', NOW(), NOW()),
('manage_strings', 'en', 'Manage Strings', NOW(), NOW()),
('no_languages_found', 'en', 'No languages found.', NOW(), NOW()),
('back_to_languages', 'en', 'Back to Languages', NOW(), NOW()),
('add_new_string', 'en', 'Add New String', NOW(), NOW()),
('no_strings_found', 'en', 'No language strings found.', NOW(), NOW()),
('edit_string', 'en', 'Edit String', NOW(), NOW()),
('language_code_label', 'en', 'Language Code', NOW(), NOW()),
('language_name_label', 'en', 'Language Name', NOW(), NOW()),
('is_active_label', 'en', 'Active', NOW(), NOW()),
('save_button', 'en', 'Save', NOW(), NOW()),
('cancel_button', 'en', 'Cancel', NOW(), NOW()),
('error_occurred', 'en', 'Error Occurred', NOW(), NOW()),
('language_code_readonly_hint', 'en', 'Language code cannot be changed after creation.', NOW(), NOW()),

-- Demo Page
('articles_count', 'en', 'Articles', NOW(), NOW()),
('categories_count', 'en', 'Categories', NOW(), NOW()),
('authors_count', 'en', 'Authors', NOW(), NOW()),
('views_today', 'en', 'Views Today', NOW(), NOW()),
('featured_articles_title', 'en', 'Featured Articles', NOW(), NOW()),
('view_all', 'en', 'View All', NOW(), NOW()),
('hero_guide_title', 'en', 'Hero A - Complete Guide', NOW(), NOW()),
('hero_guide_description', 'en', 'Master Hero A with this comprehensive guide to skills, builds, and tactics.', NOW(), NOW()),
('tag_heroes', 'en', 'Heroes', NOW(), NOW()),
('tag_guide', 'en', 'Guide', NOW(), NOW()),
('tag_events', 'en', 'Events', NOW(), NOW()),
('tag_alliance', 'en', 'Alliance', NOW(), NOW()),
('alliance_championship_title', 'en', 'Alliance Championship 2024', NOW(), NOW()),
('alliance_championship_description', 'en', 'Everything about the biggest event of the year - rewards, strategies, and schedule.', NOW(), NOW()),
('categories_title', 'en', 'Categories', NOW(), NOW()),
('heroes_category', 'en', 'Heroes', NOW(), NOW()),
('hero_a_full_guide', 'en', 'Hero A - Complete Guide', NOW(), NOW()),
('pvp_meta_builds', 'en', 'PvP Meta Builds', NOW(), NOW()),
('new_tag', 'en', 'New', NOW(), NOW()),
('hot_tag', 'en', 'Hot', NOW(), NOW()),
('strategies_category', 'en', 'Strategies', NOW(), NOW()),
('early_game_optimization', 'en', 'Early Game Optimization', NOW(), NOW()),
('resource_management', 'en', 'Resource Management', NOW(), NOW()),
('units_category', 'en', 'Units', NOW(), NOW()),
('air_units_guide', 'en', 'Air Units Guide', NOW(), NOW()),
('tank_formations', 'en', 'Tank Formations', NOW(), NOW()),
('hero_tier_list', 'en', 'Hero Tier List', NOW(), NOW()),
('pvp_tactics', 'en', 'PvP Tactics', NOW(), NOW()),
('unit_counter_system', 'en', 'Unit Counter System', NOW(), NOW()),
('categories_disabled_message', 'en', 'Categories are currently disabled.', NOW(), NOW()),

-- Navigation and Footer
('default_footer_slogan', 'en', 'Your ultimate gaming guide', NOW(), NOW()),
('footer_wiki_title', 'en', 'Wiki', NOW(), NOW()),
('footer_community_title', 'en', 'Community', NOW(), NOW()),
('footer_legal_title', 'en', 'Legal', NOW(), NOW()),
('create_article_short', 'en', 'Create', NOW(), NOW()),
('wiki_index_intro_demo', 'en', 'Find an overview of all categories and articles in our demo environment.', NOW(), NOW()),
('back_to_demo_home', 'en', 'Back to Demo Home', NOW(), NOW()),

-- Maintenance
('maintenance_mode_title', 'en', 'Maintenance Mode', NOW(), NOW()),
('maintenance_mode_message', 'en', 'The wiki is currently in maintenance mode', NOW(), NOW()),
('try_again_later', 'en', 'Please try again later.', NOW(), NOW()),

-- Registration
('or', 'en', 'or', NOW(), NOW()),
('login_link', 'en', 'Login', NOW(), NOW()),
('registration_failed', 'en', 'Registration Failed', NOW(), NOW()),
('username_label', 'en', 'Username', NOW(), NOW()),
('username_placeholder', 'en', 'Your username', NOW(), NOW()),
('email_label', 'en', 'Email', NOW(), NOW()),
('email_placeholder', 'en', 'your@email.com', NOW(), NOW()),
('password_label', 'en', 'Password', NOW(), NOW()),
('password_placeholder', 'en', 'Your password', NOW(), NOW()),
('password_confirm_label', 'en', 'Confirm Password', NOW(), NOW()),
('password_confirm_placeholder', 'en', 'Repeat password', NOW(), NOW()),
('register_button', 'en', 'Register', NOW(), NOW()),

-- 404 Page
('page_not_found_title', 'en', 'Page Not Found', NOW(), NOW()),
('page_not_found_message', 'en', 'The requested page could not be found.', NOW(), NOW()),
('back_to_homepage', 'en', 'Back to Homepage', NOW(), NOW()),

-- Header/Navigation
('your_ultimate_guide', 'en', 'Your ultimate guide', NOW(), NOW()),
('search_articles_categories', 'en', 'Search articles & categories...', NOW(), NOW()),
('home_link', 'en', 'Home', NOW(), NOW()),
('wiki_index_link', 'en', 'Wiki Index', NOW(), NOW()),
('updates_link', 'en', 'Updates', NOW(), NOW()),
('register_link', 'en', 'Register', NOW(), NOW()),
('admin_dashboard_link', 'en', 'Admin Dashboard', NOW(), NOW()),
('my_profile', 'en', 'My Profile', NOW(), NOW()),
('search_short', 'en', 'Search...', NOW(), NOW()),

-- Article Edit
('title_label', 'en', 'Title', NOW(), NOW()),
('article_title_placeholder', 'en', 'Enter article title...', NOW(), NOW()),
('content_label', 'en', 'Content', NOW(), NOW()),
('article_content_placeholder', 'en', 'Write your article here...', NOW(), NOW()),
('guest_author_name_label', 'en', 'Guest Author Name', NOW(), NOW()),
('guest_author_name_placeholder', 'en', 'Guest author name', NOW(), NOW()),
('guest_author_email_label', 'en', 'Guest Author Email', NOW(), NOW()),
('guest_author_email_placeholder', 'en', 'Guest author email', NOW(), NOW()),
('language_label', 'en', 'Language', NOW(), NOW()),
('status_label', 'en', 'Status', NOW(), NOW()),
('status_pending', 'en', 'Pending', NOW(), NOW()),
('status_published', 'en', 'Published', NOW(), NOW()),

-- Sidebar
('quick_actions_title', 'en', 'Quick Actions', NOW(), NOW()),
('edit_drafts_link', 'en', 'Edit Drafts', NOW(), NOW()),
('recent_activity_title', 'en', 'Recent Activity', NOW(), NOW()),
('recent_activity_placeholder_sidebar', 'en', 'No recent activities yet.', NOW(), NOW()),
('admin_area_title', 'en', 'Admin Area', NOW(), NOW()),
('admin_users_link', 'en', 'User Management', NOW(), NOW()),
('admin_categories_link', 'en', 'Categories', NOW(), NOW()),
('admin_alliances_link', 'en', 'Alliances', NOW(), NOW()),
('admin_send_alliance_mail_link', 'en', 'Send Bulk Mail', NOW(), NOW()),
('community_title', 'en', 'Community', NOW(), NOW()),
('community_stats_placeholder_sidebar', 'en', 'Community statistics will be displayed here.', NOW(), NOW());

-- =====================================================
-- COMPLETED
-- =====================================================
