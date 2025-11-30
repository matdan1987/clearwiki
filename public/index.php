<?php
// public/index.php - Front-Controller

// Sicherstellen, dass die Session immer aktiv ist und korrekt gestartet wird
// DIESER BLOCK MUSS GANZ AM ANFANG DER DATEI STEHEN!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// NEU: AGGRESSIVER SESSION-RESET FÜR DEBUGGING-ZWECKE!
// DIESEN BLOCK NACH DER DIAGNOSE UNBEDINGT WIEDER ENTFERNEN!
if (isset($_SESSION['user_id'])) { // Nur zerstören, wenn überhaupt eine User-ID gesetzt ist
    session_unset();   // Alle Session-Variablen entfernen
    session_destroy(); // Session zerstören
    session_start();   // Eine neue, leere Session starten
    error_log("DEBUG: Aggressive session reset performed. User was likely logged in.");
} else {
    error_log("DEBUG: No user_id in session. Session is clean.");
}
// ENDE AGGRESSIVER SESSION-RESET FÜR DEBUGGING-ZWECKE


// DEBUGGING START: Zeigt den Zustand der Session bei jedem Request
error_log("DEBUG SESSION (index.php init): " . print_r($_SESSION, true));
// DEBUGGING END

// Fehlerreporting für die Entwicklung
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Die zentrale functions.php laden, die alle weiteren Helfer und die DB-Verbindung enthält
require_once __DIR__ . '/../functions.php';

// --- Dynamische Sprachbestimmung ---
// Ruft die aktiven unterstützten Sprachen aus der Datenbank ab
$supported_lang_codes = get_supported_lang_codes();

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

$current_lang = DEFAULT_LANG;
if (isset($path_parts[0]) && in_array($path_parts[0], $supported_lang_codes)) {
    $current_lang = array_shift($path_parts);
}
// Lade die Sprachstrings für die aktuell bestimmte Sprache aus der Datenbank
load_language_strings_from_db($current_lang);


// Prüfe auf Wartungsmodus
if (is_feature_enabled('maintenance_mode') && !is_admin()) {
    load_view('maintenance', ['current_lang' => $current_lang]);
    exit();
}

// Den restlichen Pfad für das Routing verwenden
$route = implode('/', $path_parts);
if (empty($route)) {
    $route = '/';
}

// Bestimme, ob sich die aktuelle Anfrage im Demo-Kontext befindet
$is_demo_context = (strpos($route, 'demo') === 0);


// Router-Logik: Ordne Routen Funktionen/Controllern zu
$routes = [
    '/' => 'home_page_handler',
    'demo' => 'demo_page_handler',
    'demo/wiki-index' => 'demo_wiki_index_handler',
    'wiki-index' => 'wiki_index_handler',
    'article/show/{slug}' => 'article_show_handler',
    'article/create' => 'article_create_handler',
    'article/edit/{slug}' => 'article_edit_handler',
    'article/delete/{id}' => 'article_delete_handler',
    'article/upload-image' => 'article_image_upload_handler',
    'login' => 'login_handler',
    'logout' => 'logout_handler',
    'register' => 'register_handler',
    'impressum' => 'impressum_handler',
    'admin/dashboard' => 'admin_dashboard_handler',
    'admin/settings' => 'admin_settings_handler',
    'admin/users' => 'admin_users_handler',
    'admin/user/edit/{id}' => 'admin_user_edit_handler',
    'admin/categories' => 'admin_categories_handler',
    'admin/category/edit/{id}' => 'admin_category_edit_handler',
    'admin/alliances' => 'admin_alliances_handler',
    'admin/alliance/edit/{id}' => 'admin_alliance_edit_handler',
    'admin/send-alliance-mail' => 'admin_send_alliance_mail_handler',
    'admin/languages' => 'admin_languages_handler',
    'admin/language/edit/{id}' => 'admin_language_edit_handler',
    'admin/language/strings/{lang_code}' => 'admin_language_strings_handler',
    'admin/language/string/edit/{id}' => 'admin_language_string_edit_handler',
    'admin/language/string/new' => 'admin_language_string_new_handler',
];

// Funktion zur Verarbeitung von Routen mit Platzhaltern
function dispatch_route(string $current_route, array $routes): void {
    global $current_lang, $is_demo_context;

    foreach ($routes as $pattern => $handler) {
        $regex_pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $pattern);
        $regex_pattern = '#^' . $regex_pattern . '$#';

        if (preg_match($regex_pattern, $current_route, $matches)) {
            array_shift($matches);
            $params = $matches;
            preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $pattern, $param_names);
            $named_params = [];
            foreach ($param_names[1] as $index => $name) {
                if (isset($params[$index])) {
                    $named_params[$name] = $params[$index];
                }
            }

            // Prüfe Feature-Toggles
            $route_feature_map = [
                'wiki-index' => 'enable_articles',
                'article/show/{slug}' => 'enable_articles',
                'article/create' => 'enable_articles',
                'article/edit/{slug}' => 'enable_articles',
                'article/delete/{id}' => 'enable_articles',
                'admin/categories' => 'enable_categories',
                'admin/category/edit/{id}' => 'enable_categories',
                'admin/users' => 'enable_users',
                'admin/user/edit/{id}' => 'enable_users',
                'admin/alliances' => 'enable_alliances',
                'admin/alliance/edit/{id}' => 'enable_alliances',
                'register' => 'enable_registration',
                'impressum' => 'enable_impressum',
                'admin/send-alliance-mail' => 'enable_send_alliance_mail',
            ];

            if (isset($route_feature_map[$pattern])) {
                $feature_key = $route_feature_map[$pattern];
                if (!is_feature_enabled($feature_key)) {
                    http_response_code(404);
                    load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
                    exit();
                }
            }

            // Rufe den Handler mit den extrahierten Parametern auf
            if (is_callable($handler)) {
                call_user_func_array($handler, $named_params);
            } else {
                error_log("Route handler is not callable for route: " . $current_route);
                http_response_code(404);
                load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
            }
            return;
        }
    }

    // Wenn keine Route gefunden wurde
    http_response_code(404);
    load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
}

// Handler-Funktionen für die Routen
function home_page_handler(): void {
    global $current_lang, $is_demo_context;
    $data = [
        'title' => __('welcome_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('home', $data);
    load_view('footer', $data);
}

function demo_page_handler(): void {
    global $current_lang, $is_demo_context;
    $data = [
        'title' => __('welcome_title') . ' (' . __('demo_version_suffix') . ')',
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('demo_home', $data);
    load_view('demo_footer', $data);
}

function demo_wiki_index_handler(): void {
    global $current_lang, $is_demo_context;
    $data = [
        'title' => __('wiki_index_title') . ' (' . __('demo_version_suffix') . ')',
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('demo_wiki_index', $data);
    load_view('demo_footer', $data);
}


function wiki_index_handler(): void {
    global $current_lang, $is_demo_context;
    $data = [
        'title' => __('wiki_index_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('home', $data); 
    load_view('footer', $data);
}

function article_show_handler(string $slug): void {
    global $current_lang, $is_demo_context;
    $article = get_article_by_slug($slug, $current_lang);

    if (!$article || ($article['status'] !== 'published' && !is_admin())) {
        http_response_code(404);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }

    $data = [
        'title' => $article['title'],
        'current_lang' => $current_lang,
        'article' => $article,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('article_show', $data);
    load_view('footer', $data);
}

function article_create_handler(): void {
    global $current_lang, $is_demo_context;

    // DEBUGGING START
    error_log("DEBUG: article_create_handler - User logged in: " . (is_logged_in() ? 'Yes' : 'No'));
    $currentUser = get_current_user();
    error_log("DEBUG: article_create_handler - Current User ID: " . ($currentUser ? ($currentUser['id'] ?? 'N/A') : 'N/A'));
    // DEBUGGING END

    if (!is_logged_in()) {
        redirect('/' . $current_lang . '/login');
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'pending';
        
        $author_user_id = $currentUser['id'] ?? null;
        $author_guest_name = null;
        $author_guest_email = null;

        // Validierung
        if (empty($title) || empty($content)) {
            $errors[] = __('article_error_empty_fields');
        }
        
        if ($author_user_id === null) {
            $author_guest_name = $_POST['author_guest_name'] ?? null;
            $author_guest_email = $_POST['author_guest_email'] ?? null;

            if (empty($author_guest_name) || empty($author_guest_email)) {
                 $errors[] = __('article_error_guest_info_missing');
            }
            if (!empty($author_guest_email) && !filter_var($author_guest_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('article_error_invalid_guest_email');
            }
        }

        if (empty($errors)) {
            $article_id = create_article($title, $content, $author_user_id, $author_guest_name, $author_guest_email, $status, $current_lang);
            if ($article_id) {
                $new_article = get_article_by_id($article_id);
                redirect('/' . $current_lang . '/article/show/' . sanitize_output($new_article['slug']) . '?status=success&message=' . urlencode(__('article_created_success')));
            } else {
                $errors[] = __('article_error_create_failed');
            }
        }
    }

    $data = [
        'title' => __('create_article_title'),
        'current_lang' => $current_lang,
        'errors' => $errors,
        'is_demo_context' => $is_demo_context,
        'article' => null
    ];
    load_view('header', $data);
    load_view('article_edit', $data);
    load_view('footer', $data);
}

function article_edit_handler(string $slug): void {
    global $current_lang, $is_demo_context;

    // DEBUGGING START
    error_log("DEBUG: article_edit_handler - User logged in: " . (is_logged_in() ? 'Yes' : 'No'));
    $currentUser = get_current_user();
    error_log("DEBUG: article_edit_handler - Current User ID: " . ($currentUser ? ($currentUser['id'] ?? 'N/A') : 'N/A'));
    // DEBUGGING END

    if (!is_logged_in()) {
        redirect('/' . $current_lang . '/login');
    }

    $article = get_article_by_slug($slug, $current_lang);
    if (!$article) {
        http_response_code(404);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }

    // Berechtigungsprüfung: Nur Admin/Moderator oder der Autor selbst darf bearbeiten
    if (!is_moderator_or_admin() && ($currentUser['id'] ?? null) !== $article['author_user_id']) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'pending';

        // Validierung
        if (empty($title) || empty($content)) {
            $errors[] = __('article_error_empty_fields');
        }

        if (empty($errors)) {
            if (update_article($article['id'], $title, $content, $status, $current_lang)) {
                redirect('/' . $current_lang . '/article/show/' . sanitize_output($slug) . '?status=success&message=' . urlencode(__('article_updated_success')));
            } else {
                $errors[] = __('article_error_update_failed');
            }
        }
        // Nach POST-Request, lade den Artikel neu, um aktuelle Daten und Fehler anzuzeigen
        $article = get_article_by_slug($slug, $current_lang);
    }

    $data = [
        'title' => __('edit_article_title') . ' (' . sanitize_output($article['title']) . ')',
        'current_lang' => $current_lang,
        'article' => $article,
        'errors' => $errors,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('article_edit', $data);
    load_view('footer', $data);
}

function article_delete_handler(string $id): void {
    global $current_lang, $is_demo_context;
    if (!is_logged_in()) {
        redirect('/' . $current_lang . '/login');
    }

    $article = get_article_by_id((int)$id);
    if (!$article) {
        http_response_code(404);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }

    // Berechtigungsprüfung: Nur Admin/Moderator oder der Autor selbst darf löschen
    if (!is_moderator_or_admin() && (get_current_user()['id'] ?? null) !== $article['author_user_id']) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        if (delete_article((int)$id)) {
            redirect('/' . $current_lang . '/wiki-index?status=success&message=' . urlencode(__('article_deleted_success')));
        } else {
            redirect('/' . $current_lang . '/article/show/' . sanitize_output($article['slug']) . '?status=error&message=' . urlencode(__('article_error_delete_failed')));
        }
    } else {
        redirect('/' . $current_lang . '/article/show/' . sanitize_output($article['slug']) . '?status=info&message=' . urlencode(__('article_delete_confirm_prompt')));
    }
}

function article_image_upload_handler(): void {
    header('Content-Type: application/json');

    if (!is_logged_in()) {
        echo json_encode(['error' => ['message' => __('ckeditor_upload_not_logged_in')]]);
        exit();
    }

    $response = handle_ckeditor_image_upload();
    echo json_encode($response);
    exit();
}


function login_handler(): void {
    global $current_lang, $is_demo_context;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $identifier = $_POST['identifier'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $errors[] = __('login_error_empty_fields');
        } else {
            if (login_user($identifier, $password)) {
                $redirect_path = '/' . $current_lang . '/';
                if ($is_demo_context) {
                    $redirect_path .= 'demo/';
                }
                redirect($redirect_path);
            } else {
                $errors[] = __('login_error_invalid_credentials');
            }
        }
    }

    $data = [
        'title' => __('login_title'),
        'current_lang' => $current_lang,
        'errors' => $errors,
        'is_demo_context' => $is_demo_context
    ];
    load_view('login', $data);
}

function logout_handler(): void {
    start_session_if_not_started();
    session_unset();
    session_destroy();
    global $current_lang;
    redirect('/' . $current_lang . '/'); 
}

function register_handler(): void {
    global $current_lang, $is_demo_context;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
            $errors[] = __('registration_error_empty_fields');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = __('registration_error_invalid_email');
        }
        if ($password !== $password_confirm) {
            $errors[] = __('registration_error_password_mismatch');
        }
        if (strlen($password) < 8) {
            $errors[] = __('registration_error_password_too_short');
        }

        if (empty($errors)) {
            if (register_user($username, $email, $password)) {
                login_user($email, $password);
                $redirect_path = '/' . $current_lang . '/';
                if ($is_demo_context) {
                    $redirect_path .= 'demo/';
                }
                redirect($redirect_path);
            } else {
                $errors[] = __('registration_error_user_exists');
            }
        }
    }

    $data = [
        'title' => __('register_title'),
        'current_lang' => $current_lang,
        'errors' => $errors,
        'is_demo_context' => $is_demo_context
    ];
    load_view('register', $data);
}

function impressum_handler(): void {
    global $_SETTINGS, $current_lang, $is_demo_context;
    if (!is_feature_enabled('enable_impressum')) {
        http_response_code(404);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('impressum_title'),
        'impressum_content' => $_SETTINGS['impressum_content'] ?? __('impressum_default_content'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('impressum_title') . "</h2>";
    echo "<div class='text-text-secondary'>" . nl2br(sanitize_output($data['impressum_content'])) . "</div>";
    load_view('footer', $data);
}

function admin_dashboard_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin()) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_dashboard_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_dashboard_title') . "</h2>";
    echo "<p class='text-text-secondary'>Admin-Dashboard-Inhalt kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_settings_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin()) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_settings_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_settings_title') . "</h2>";
    echo "<p class='text-text-secondary'>Admin-Einstellungen-Formular kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_users_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin() || !is_feature_enabled('enable_users')) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_users_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_users_title') . "</h2>";
    echo "<p class='text-text-secondary'>Benutzerverwaltung kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_user_edit_handler(string $id): void {
    global $current_lang, $is_demo_context;
    if (!is_admin() || !is_feature_enabled('enable_users')) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_edit_user_title') . ' (ID: ' . sanitize_output($id) . ')',
        'user_id' => $id,
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_edit_user_title') . ": " . sanitize_output($id) . "</h2>";
    echo "<p class='text-text-secondary'>Benutzerbearbeitungsformular kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_categories_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin() || !is_feature_enabled('enable_categories')) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_categories_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_categories_title') . "</h2>";
    echo "<p class='text-text-secondary'>Kategorienverwaltung kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_category_edit_handler(string $id): void {
    global $current_lang, $is_demo_context;
    if (!is_admin() || !is_feature_enabled('enable_categories')) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_edit_category_title') . ' (ID: ' . sanitize_output($id) . ')',
        'category_id' => $id,
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_edit_category_title') . ": " . sanitize_output($id) . "</h2>";
    echo "<p class='text-text-secondary'>Kategorienbearbeitungsformular kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_alliances_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin() || !is_feature_enabled('enable_alliances')) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_alliances_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_alliances_title') . "</h2>";
    echo "<p class='text-text-secondary'>Allianzverwaltung kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_alliance_edit_handler(string $id): void {
    global $current_lang, $is_demo_context;
    if (!is_admin() || !is_feature_enabled('enable_alliances')) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_edit_alliance_title') . ' (ID: ' . sanitize_output($id) . ')',
        'alliance_id' => $id,
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_edit_alliance_title') . ": " . sanitize_output($id) . "</h2>";
    echo "<p class='text-text-secondary'>Allianzbearbeitungsformular kommt hierhin.</p>";
    load_view('footer', $data);
}

function admin_send_alliance_mail_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin() || !is_feature_enabled('enable_send_alliance_mail')) {
        http_response_code(403);
        load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]);
        exit();
    }
    $data = [
        'title' => __('admin_send_alliance_mail_title'),
        'current_lang' => $current_lang,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    echo "<section class='glass-effect rounded-2xl p-6 mb-8'>";
    echo "<h2 class='text-2xl font-bold text-white mb-4'>" . __('admin_send_alliance_mail_title') . "</h2>";
    echo "<p class='text-text-secondary'>Rundmail-Formular kommt hierhin.</p>";
    load_view('footer', $data);
}

// Handler-Funktionen für die Sprachverwaltung im Admin-Panel (wie zuvor)
function admin_languages_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin()) { http_response_code(403); load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]); exit(); }
    
    $languages = get_all_supported_languages();
    
    $data = [
        'title' => __('admin_languages_title'),
        'current_lang' => $current_lang,
        'languages' => $languages,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('admin_languages', $data);
    load_view('footer', $data);
}

function admin_language_edit_handler(string $id): void {
    global $current_lang, $is_demo_context;
    if (!is_admin()) { http_response_code(403); load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]); exit(); }

    $language = ($id === 'new') ? null : get_supported_language_by_id((int)$id);
    if ($id !== 'new' && !$language) {
        http_response_code(404); load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]); exit();
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lang_code = $_POST['lang_code'] ?? '';
        $lang_name = $_POST['lang_name'] ?? '';
        $is_active = isset($_POST['is_active']) ? true : false;

        if (empty($lang_code) || empty($lang_name)) {
            $errors[] = __('error_empty_fields');
        } elseif ($id === 'new') {
            if (add_supported_language($lang_code, $lang_name, $is_active)) {
                $all_lang_keys = get_all_unique_language_keys();
                foreach ($all_lang_keys as $key) {
                    add_language_string($key, $lang_code, '');
                }
                redirect('/' . $current_lang . '/admin/languages?status=success&message=' . urlencode(__('language_added_success')));
            } else {
                $errors[] = __('error_add_failed');
            }
        } elseif (update_supported_language((int)$id, $lang_code, $lang_name, $is_active)) {
            redirect('/' . $current_lang . '/admin/languages?status=success&message=' . urlencode(__('language_updated_success')));
        } else {
            $errors[] = __('error_update_failed');
        }
    }

    $data = [
        'title' => ($id === 'new') ? __('admin_add_language_title') : __('admin_edit_language_title') . ' (' . sanitize_output($language['lang_name'] ?? '') . ')',
        'current_lang' => $current_lang,
        'language' => $language,
        'errors' => $errors,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('admin_language_form', $data);
    load_view('footer', $data);
}

function admin_language_strings_handler(string $lang_code): void {
    global $current_lang, $is_demo_context;
    if (!is_admin()) { http_response_code(403); load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]); exit(); }

    $strings = get_all_language_strings_for_lang($lang_code);
    
    $data = [
        'title' => __('admin_language_strings_title') . ' (' . strtoupper(sanitize_output($lang_code)) . ')',
        'current_lang' => $current_lang,
        'lang_code' => $lang_code,
        'strings' => $strings,
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('admin_language_strings', $data);
    load_view('footer', $data);
}

function admin_language_string_edit_handler(string $id): void {
    global $current_lang, $is_demo_context;
    if (!is_admin()) { http_response_code(403); load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]); exit(); }

    $string = get_language_string_by_id((int)$id);
    if (!$string) {
        http_response_code(404); load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]); exit();
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lang_key = $_POST['lang_key'] ?? '';
        $lang_code_post = $_POST['lang_code'] ?? '';
        $value = $_POST['value'] ?? '';

        if (empty($lang_key) || empty($lang_code_post) || empty($value)) {
            $errors[] = __('error_empty_fields');
        } elseif (update_language_string((int)$id, $lang_key, $lang_code_post, $value)) {
            redirect('/' . $current_lang . '/admin/language/strings/' . $lang_code_post . '?status=success&message=' . urlencode(__('string_updated_success')));
        } else {
            $errors[] = __('error_update_failed');
        }
    }

    $data = [
        'title' => __('admin_edit_string_title') . ' (' . sanitize_output($string['lang_key']) . ')',
        'current_lang' => $current_lang,
        'string' => $string,
        'errors' => $errors,
        'supported_languages' => get_all_supported_languages(),
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('admin_language_string_form', $data);
    load_view('footer', $data);
}

function admin_language_string_new_handler(): void {
    global $current_lang, $is_demo_context;
    if (!is_admin()) { http_response_code(403); load_view('404', ['current_lang' => $current_lang, 'is_demo_context' => $is_demo_context]); exit(); }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lang_key = $_POST['lang_key'] ?? '';
        $lang_code = $_POST['lang_code'] ?? '';
        $value = $_POST['value'] ?? '';

        if (empty($lang_key) || empty($lang_code) || empty($value)) {
            $errors[] = __('error_empty_fields');
        } elseif (add_language_string($lang_key, $lang_code, $value)) {
            redirect('/' . $current_lang . '/admin/language/strings/' . $lang_code . '?status=success&message=' . urlencode(__('string_added_success')));
        } else {
            $errors[] = __('error_add_failed');
        }
    }

    $data = [
        'title' => __('admin_add_string_title'),
        'current_lang' => $current_lang,
        'errors' => $errors,
        'supported_languages' => get_all_supported_languages(),
        'is_demo_context' => $is_demo_context
    ];
    load_view('header', $data);
    load_view('admin_language_string_form', $data);
    load_view('footer', $data);
}


// Hier wird der Dispatcher aufgerufen
dispatch_route($route, $routes);
