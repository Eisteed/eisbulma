<?
//////////////////////////////////////////////
//  VITE DEV & BUILD						//
//	Use vite dev server if running			//
//  Else enqueue built file using manifest 	//
//////////////////////////////////////////////
function eis_manifest_entry($entry)
{
    $p = wp_normalize_path(get_theme_file_path('dist/.vite/manifest.json'));
    if (!file_exists($p)) {
        error_log("manifest.json cannot be found at $p");
        return null;
    }
    $m = json_decode(file_get_contents($p), true);
    if (!$m) return null;
    $found = $m[$entry] ?? null;
    if (!$found) {
        foreach ($m as $key => $value) {
            if (str_ends_with($key, basename($entry))) {
                $found = $value;
                break;
            }
        }
    }
    if (!$found) {
        error_log("manifest not found : $entry");
        return null;
    }
    $base = get_theme_file_uri('dist/');
    $js   = !empty($found['file']) ? $base . $found['file'] : null;
    $css  = !empty($found['css']) ? array_map(fn($f) => $base . $f, $found['css']) : [];
    return ['js' => $js, 'css' => $css];
}
function is_dev(): bool
{
    // Use WordPress native environment type (WP 5.5+)
    if (function_exists('wp_get_environment_type')) {
        $env = wp_get_environment_type();
        // Debug: Log the environment type

        // Accept both 'local' and 'development' for dev mode
        return in_array($env, ['local', 'development'], true);
    }

    // Fallback to WP_ENV for older WP or Bedrock/Local setups
    if (defined('WP_ENV')) {

        return in_array(WP_ENV, ['local', 'development'], true);
    }

    // Default to production (safe default)

    return false;
}

function is_ViteRunning($host = 'localhost', $port = 5173, $timeout = 0.1): bool
{
    // Only check if Vite is running in development environment
    if (!is_dev()) {

        return false;
    }

    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($fp) {
        fclose($fp);

        return true;
    }

    return false;
}
function eisbulma_scripts()
{
    wp_enqueue_style('eisbulma-style', get_stylesheet_uri(), [], null);
    // don't fuck up ajax calls (admin-ajax.php)
    if (wp_doing_ajax()) {
        return;
    }
    // FLOATING CART
    $cart_params = [
        'ajax_url' => admin_url('admin-ajax.php'),
        'floating_cart_nonce' => wp_create_nonce('floating_cart_nonce'),
        'cart_quantity_nonce' => wp_create_nonce('cart_quantity_nonce'),
        'i18n' => [
            'in_cart' => esc_html__('In Cart', 'eisbulma'),
            'cart_empty' => esc_html__('Your cart is empty', 'eisbulma'),
        ],
    ];


    // AJAX SEARCH
    $ajax_i18n = [
        'searching'      => esc_html__('Searching...', 'eisbulma'),
        'found_results'  => esc_html__('Found {count} results for "{term}"', 'eisbulma'),
    ];


    if (is_ViteRunning()) {
        echo "<script>console.log('[EisBulma] Dev mode');</script>";
        $vite_server_url = home_url() . ':5173';
        // replace http with ws, https with wss for the client
        $vite_server_url = str_replace(['http://', 'https://'], ['', ''], $vite_server_url);
        // class-inject loads early but as type="module" it won't block rendering
        wp_enqueue_script('eis-class-inject', 'https://' . $vite_server_url . '/src/js/class-inject.js', [], null, false);  // head

        wp_enqueue_script('vite-client', 'https://' . $vite_server_url . '/@vite/client', [], null, true);
        wp_enqueue_script('eis-main', 'https://' . $vite_server_url . '/src/js/main.js',  null,  null, false);
        // Main.js will load all our scss

    } else {
        echo "<script>console.log('[EisBulma] Production mode');</script>";

        // 1) class-inject en premier
        $class_entry = eis_manifest_entry('src/js/class-inject.js');
        if ($class_entry && !empty($class_entry['js'])) {
            wp_enqueue_script(
                'eis-class-inject',
                $class_entry['js'],
                [],
                null,
                false // head
            );
        }

        $entry = eis_manifest_entry('src/js/main.js');
        if ($entry) {
            if (!empty($entry['css'])) {
                foreach ($entry['css'] as $index => $href) {
                    wp_enqueue_style(
                        'eis-main-css-' . $index,
                        $href,
                        [],
                        null
                    );
                }
            }

            if (!empty($entry['js'])) {
                wp_enqueue_script('eis-main', $entry['js'],  null, null, true);
            }
        }
    }
    wp_set_script_translations(
        'eis-main',              // même handle que wp_enqueue_script
        'eisbulma',              // ton text domain
        get_theme_file_path('languages')                  // chemin vers les fichiers de traduction
    );
    wp_localize_script('eis-main', 'eisbulma_cart_params', $cart_params);
    wp_localize_script('eis-main', 'eisbulma_i18n', $ajax_i18n);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    $ok = wp_set_script_translations(
        'eis-main',
        'eisbulma',
        get_theme_file_path('languages')  // ou get_theme_file_path('languages') selon ton setup
    );
}
add_action('wp_enqueue_scripts', 'eisbulma_scripts', 10);

// Add type="module" to vite scripts (module scripts are automatically deferred)
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    $module_handles = ['vite-client', 'eis-main', 'eis-editor', 'eis-cart', 'eis-class-inject'];
    if (in_array($handle, $module_handles, true)) {
        // type="module" automatically defers execution and maintains order
        return str_replace('<script ', '<script type="module" ', $tag);
    }
    return $tag;
}, 10, 3);

add_action('enqueue_block_editor_assets', function () {
    $vite_server_url = home_url() . ':5173';
    // replace http with ws, https with wss for the client
    $vite_server_url = str_replace(['http://', 'https://'], ['', ''], $vite_server_url);
    if (is_ViteRunning()) {
        // DEV MODE
        wp_enqueue_script('vite-client', 'https://' . $vite_server_url . '/@vite/client', [], null, true);
        wp_enqueue_script('eis-editor', 'https://' . $vite_server_url . '/src/js/gutenberg-bulma.js', ['wp-blocks', 'wp-element', 'wp-components', 'wp-editor'], null, true);
    } else {
        // PROD MODE
        $entry = eis_manifest_entry('src/js/gutenberg-bulma.js');
        if ($entry) {
            if (!empty($entry['css'])) {
                foreach ($entry['css'] as $index => $href) {
                    wp_enqueue_style(
                        'eis-editor-css-' . $index,
                        $href,
                        ['wp-edit-blocks'],
                        null
                    );
                }
            }
            if (!empty($entry['js'])) {
                wp_enqueue_script('eis-editor', $entry['js'], ['wp-blocks', 'wp-element', 'wp-components', 'wp-editor'], null, true);
            }
        }
    }
}, 10);
