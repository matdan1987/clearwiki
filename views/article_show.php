<?php
// views/article_show.php - Anzeige eines einzelnen Artikels
// Daten: $title, $current_lang, $article (Array mit Artikeldaten), $is_demo_context

global $current_lang, $is_demo_context;
$article = $article ?? null; // Sicherstellen, dass $article definiert ist

// Wenn kein Artikel übergeben wurde oder der Artikel nicht veröffentlicht ist (und kein Admin)
if (!$article || ($article['status'] !== 'published' && !is_admin())) {
    http_response_code(404);
    load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]);
    exit();
}

// Artikeldaten für die Anzeige vorbereiten
$article_title = sanitize_output($article['title']);
// CKEditor-Inhalt wird als HTML gespeichert, daher hier nicht mit htmlspecialchars escapen
$article_content = $article['content']; 
$article_author = sanitize_output($article['author_user_id'] ? get_user_by_id($article['author_user_id'])['username'] : $article['author_guest_name']);
$article_published_at = $article['published_at'] ? date('d.m.Y H:i', strtotime($article['published_at'])) : __('not_published_yet');
$article_updated_at = $article['updated_at'] ? date('d.m.Y H:i', strtotime($article['updated_at'])) : __('not_updated_yet');

?>

<div class="main-grid lg:grid-cols-4">
    <div class="lg:col-span-3 space-y-8">
        <section class="glass-effect rounded-2xl p-6 mb-8">
            <h1 class="text-3xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-book-reader text-accent-orange mr-3"></i>
                <?= $article_title ?>
                <?php if (is_admin()): // Nur Admins sehen den Status, wenn nicht veröffentlicht ?>
                    <?php if ($article['status'] !== 'published'): ?>
                        <span class="ml-4 px-3 py-1 rounded-full text-sm font-semibold bg-warning/20 text-warning">
                            <?= __('status_pending') ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </h1>

            <div class="text-sm text-text-secondary mb-6 border-b border-border-color pb-4">
                <p>
                    <?= __('author') ?>: <span class="text-accent-blue"><?= $article_author ?></span>
                    | <?= __('published_on') ?>: <?= $article_published_at ?>
                    | <?= __('last_updated_on') ?>: <?= $article_updated_at ?>
                </p>
                <?php if (is_logged_in() && (is_moderator_or_admin() || (get_current_user()['id'] === $article['author_user_id']))): ?>
                    <div class="mt-2">
                        <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?><?= $is_demo_context ? '/demo' : '' ?>/article/edit/<?= sanitize_output($article['slug']) ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit mr-2"></i><?= __('edit_article_button') ?>
                        </a>
                        <?php /* Löschen-Button (später mit Bestätigung) */ ?>
                        <?php /* <button class="btn btn-secondary btn-sm ml-2"><i class="fas fa-trash mr-2"></i><?= __('delete_article_button') ?></button> */ ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="article-content text-text-primary leading-relaxed text-lg ck-content">
                <?= $article_content ?>
            </div>
            
            <?php // Hier könnten Tags, Kategorien oder Kommentare folgen ?>
        </section>
    </div>
    <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
</div>
