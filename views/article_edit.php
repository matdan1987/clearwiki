<?php
// views/article_edit.php - Formular zum Erstellen und Bearbeiten von Artikeln
// Daten: $title, $current_lang, $article (für Bearbeitung), $errors, $categories (für Dropdown)

global $current_lang, $is_demo_context;
$article = $article ?? null; // Null für "neu erstellen"
$errors = $errors ?? [];
$categories = $categories ?? []; // Sicherstellen, dass $categories definiert ist

$is_edit = ($article !== null);

// Formular-Aktion basierend darauf, ob ein Artikel bearbeitet oder neu erstellt wird
$form_action = $is_edit ?
    '/' . htmlspecialchars($current_lang ?? DEFAULT_LANG) . '/article/edit/' . sanitize_output($article['slug']) :
    '/' . htmlspecialchars($current_lang ?? DEFAULT_LANG) . '/article/create';

// Standardwerte für das Formular
$article_title = sanitize_output($article['title'] ?? '');
$article_content = htmlspecialchars($article['content'] ?? ''); // CKEditor benötigt unescaped HTML, aber für die Anzeige im Textfeld escaped
$article_status = $article['status'] ?? 'pending';
$article_lang_code = sanitize_output($article['lang_code'] ?? ($current_lang ?? DEFAULT_LANG));
$author_user_id = $article['author_user_id'] ?? null;
$author_guest_name = sanitize_output($article['author_guest_name'] ?? '');
$author_guest_email = sanitize_output($article['author_guest_email'] ?? ''); // Sicherstellen, dass es korrekt initialisiert wird

// Wenn ein Benutzer angemeldet ist, werden die Gastautor-Felder ausgeblendet
$current_user = get_current_user();
$display_guest_author_fields = ($current_user === null); // Nur anzeigen, wenn KEIN Benutzer angemeldet ist
?>

<style>
    /* CKEditor 5 Dark Theme Anpassungen */
    /* Dies wird direkt in der View eingebettet, um spezifische CKEditor-Elemente zu stylen */

    /* Hauptinhaltsbereich des Editors */
    .ck-editor__editable_inline {
        background-color: var(--secondary-bg) !important; /* Dunkler Hintergrund */
        color: var(--text-primary) !important; /* Heller Text */
        border: 1px solid var(--border-color) !important;
        border-radius: 0.5rem;
        padding: 1rem;
        min-height: 300px; /* Mindesthöhe für den Editor */
    }

    /* Toolbar des Editors */
    .ck.ck-toolbar {
        background-color: var(--card-bg) !important; /* Dunklerer Hintergrund für die Toolbar */
        border: 1px solid var(--border-color) !important;
        border-bottom: none !important;
        border-radius: 0.5rem 0.5rem 0 0;
        color: var(--text-secondary) !important;
    }

    /* Buttons und Dropdowns in der Toolbar */
    .ck.ck-toolbar .ck-button,
    .ck.ck-toolbar .ck-dropdown__button {
        color: var(--text-primary) !important; /* Textfarbe für Buttons */
        background-color: transparent !important;
    }

    .ck.ck-toolbar .ck-button:hover,
    .ck.ck-toolbar .ck-dropdown__button:hover {
        background-color: rgba(255, 107, 53, 0.1) !important; /* Leichter Orange-Hover */
        color: var(--accent-orange) !important;
    }

    .ck.ck-toolbar .ck-button.ck-on,
    .ck.ck-toolbar .ck-dropdown__button.ck-on {
        background-color: var(--accent-orange) !important; /* Aktiver/ausgewählter Button */
        color: var(--primary-bg) !important;
    }

    /* Dropdown-Listen (z.B. Paragraph, Überschriften) */
    .ck.ck-list {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }

    .ck.ck-list__item .ck-button {
        color: var(--text-primary) !important;
    }

    .ck.ck-list__item .ck-button:hover {
        background-color: rgba(255, 107, 53, 0.1) !important;
        color: var(--accent-orange) !important;
    }

    /* Links im Editor */
    .ck-content a {
        color: var(--accent-blue) !important; /* Blaue Farbe für Links */
        text-decoration: underline;
    }

    /* Blockquotes */
    .ck-content blockquote {
        border-left: 5px solid var(--accent-orange) !important;
        color: var(--text-secondary) !important;
    }

    /* Tabellen */
    .ck-content table {
        border-collapse: collapse;
        width: 100%;
        margin: 1em 0;
    }
    .ck-content table td, .ck-content table th {
        border: 1px solid var(--border-color) !important;
        padding: 8px;
        color: var(--text-primary) !important;
    }
    .ck-content table th {
        background-color: var(--secondary-bg) !important;
        color: var(--accent-orange) !important;
    }

    /* Placeholder Text */
    .ck-placeholder {
        color: var(--text-muted) !important;
    }

    /* Fokus-Rahmen (kann auch über Tailwind gesteuert werden) */
    .ck.ck-editor__main .ck-editor__editable:focus {
        border-color: var(--accent-orange) !important;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1) !important;
    }

    /* CKEditor Elemente, die direkt im HTML gerendert werden (für article_show.php) */
    .ck-content {
        color: var(--text-primary);
        line-height: 1.6;
    }
    .ck-content h1, .ck-content h2, .ck-content h3, .ck-content h4, .ck-content h5, .ck-content h6 {
        font-family: 'Orbitron', sans-serif;
        color: var(--accent-orange);
        margin-top: 1.5em;
        margin-bottom: 0.5em;
    }
    .ck-content p {
        margin-bottom: 1em;
    }
    .ck-content ul, .ck-content ol {
        margin-left: 1.5em;
        margin-bottom: 1em;
        list-style-position: outside;
    }
    .ck-content ul {
        list-style-type: disc;
    }
    .ck-content ol {
        list-style-type: decimal;
    }
    .ck-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1em 0;
        display: block; /* Für Zentrierung bei align-full */
    }
    .ck-content figure.image-style-alignLeft {
        float: left;
        margin-right: 1em;
    }
    .ck-content figure.image-style-alignRight {
        float: right;
        margin-left: 1em;
    }
    .ck-content figure.image {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }
    .ck-content figcaption {
        font-size: 0.875em;
        color: var(--text-muted);
        text-align: center;
        margin-top: 0.5em;
    }
    .ck-content .media {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        overflow: hidden;
        margin: 1em 0;
    }
    .ck-content .media iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Fix für Eingabefelder und Select-Boxen */
    input[type="text"].shadow,
    input[type="email"].shadow,
    textarea.shadow,
    select.shadow {
        background-color: var(--secondary-bg) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }
    /* Placeholder-Textfarbe anpassen */
    input[type="text"].shadow::placeholder,
    input[type="email"].shadow::placeholder,
    textarea.shadow::placeholder {
        color: var(--text-muted) !important;
    }

</style>

<div class="main-grid lg:grid-cols-4">
    <div class="lg:col-span-3 space-y-8">
        <section class="glass-effect rounded-2xl p-6 mb-8">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-file-alt text-accent-orange mr-3"></i>
                <?= $title ?>
            </h2>

            <?php if (!empty($errors)): ?>
                <div class="bg-error/20 border border-error text-error px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold"><?= __('error_occurred') ?>:</strong>
                    <span class="block sm:inline"><?= implode('<br>', $errors) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= $form_action ?>" method="POST" class="space-y-6">
                <div>
                    <label for="title" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('title_label') ?>
                    </label>
                    <input type="text" id="title" name="title" value="<?= $article_title ?>"
                           class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                           placeholder="<?= __('article_title_placeholder') ?>" required>
                </div>

                <div>
                    <label for="editor" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('content_label') ?? 'Inhalt' ?>
                    </label>
                    <textarea id="editor" name="content"
                              class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                              placeholder="<?= __('article_content_placeholder') ?>"><?= $article_content ?></textarea>
                </div>

                <?php if ($display_guest_author_fields): ?>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="author_guest_name" class="block text-text-secondary text-sm font-bold mb-2">
                                <?= __('guest_author_name_label') ?? 'Gastautor Name' ?>
                            </label>
                            <input type="text" id="author_guest_name" name="author_guest_name" value="<?= $author_guest_name ?>"
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                                   placeholder="<?= __('guest_author_name_placeholder') ?? 'Name des Gastautors' ?>">
                        </div>
                        <div>
                            <label for="author_guest_email" class="block text-text-secondary text-sm font-bold mb-2">
                                <?= __('guest_author_email_label') ?? 'Gastautor E-Mail' ?>
                            </label>
                            <input type="email" id="author_guest_email" name="author_guest_email" value="<?= $author_guest_email ?>"
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color"
                                   placeholder="<?= __('guest_author_email_placeholder') ?? 'E-Mail des Gastautors' ?>">
                        </div>
                    </div>
                <?php endif; ?>

                <div>
                    <label for="lang_code" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('language_label') ?? 'Sprache' ?>
                    </label>
                    <select id="lang_code" name="lang_code"
                            class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color">
                        <?php foreach (get_all_supported_languages() as $lang): // Alle unterstützten Sprachen laden ?>
                            <option value="<?= sanitize_output($lang['lang_code']) ?>"
                                <?= ($article_lang_code === $lang['lang_code']) ? 'selected' : '' ?>>
                                <?= sanitize_output($lang['lang_name']) ?> (<?= sanitize_output($lang['lang_code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-text-secondary text-sm font-bold mb-2">
                        <?= __('status_label') ?? 'Status' ?>
                    </label>
                    <select id="status" name="status"
                            class="shadow appearance-none border rounded w-full py-3 px-4 text-text-primary leading-tight focus:outline-none focus:shadow-outline bg-secondary-bg border-border-color">
                        <option value="pending" <?= ($article_status === 'pending') ? 'selected' : '' ?>><?= __('status_pending') ?? 'Ausstehend' ?></option>
                        <option value="published" <?= ($article_status === 'published') ? 'selected' : '' ?>><?= __('status_published') ?? 'Veröffentlicht' ?></option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i><?= __('save_button') ?>
                    </button>
                    <a href="/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/admin/articles" class="btn btn-secondary">
                        <?= __('cancel_button') ?>
                    </a>
                </div>
            </form>
        </section>
    </div>
    <?php load_view('sidebar', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context ?? false]); ?>
</div>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            // CKEditor Konfiguration
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'indent', 'outdent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
                ]
            },
            language: '<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>', // Sprache des Editors anpassen
            image: {
                toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],
                styles: [ 'full', 'alignLeft', 'alignRight' ]
            },
            table: {
                contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
            },
            mediaEmbed: {
                // Ermöglicht responsive YouTube-Video-Embeds
                previewsInData: true,
                removeProviders: [ 'instagram', 'twitter', 'googleMaps', 'flickr', 'vimeo' ], // Entferne unnötige Anbieter
                extraProviders: [
                    {
                        name: 'youtube',
                        url: /^(?:m\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)(?:&.*)?$|youtu\.be\/([a-zA-Z0-9_-]+)(?:&.*)?$/,
                        html: match => {
                            const id = match[1] || match[2];
                            return (
                                '<div style="position: relative; padding-bottom: 100%; height: 0; padding-bottom: 56.25%;">' +
                                '<iframe src="https://www.youtube.com/embed/' + id + '" ' +
                                'style="position: absolute; width: 100%; height: 100%; top: 0; left: 0;" ' +
                                'frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>' +
                                '</iframe>' +
                                '</div>'
                            );
                        }
                    }
                ]
            },
            // CKEditor 5 Upload-Adapter Konfiguration
            // Dieser Adapter sendet Bild-Uploads an unseren PHP-Backend-Endpunkt
            simpleUpload: {
                uploadUrl: '/<?= htmlspecialchars($current_lang ?? DEFAULT_LANG) ?>/article/upload-image', // Unser Upload-Endpunkt
                headers: {
                    // Optional: Füge hier CSRF-Token oder andere Header hinzu
                    // 'X-CSRF-TOKEN': 'DeineCSRFTokenHier',
                }
            }
        } )
        .then( editor => {
            window.editor = editor; // Editor-Instanz global verfügbar machen, falls benötigt
        } )
        .catch( error => {
            console.error( 'There was a problem initializing the editor.', error );
        } );
</script>
