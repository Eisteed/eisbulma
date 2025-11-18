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
        error_log("manifest.json introuvable à $p");
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
        error_log("Entrée manifest non trouvée : $entry");
        return null;
    }

    $base = get_theme_file_uri('dist/');
    $js   = !empty($found['file']) ? $base . $found['file'] : null;
    $css  = !empty($found['css']) ? array_map(fn($f) => $base . $f, $found['css']) : [];

    return ['js' => $js, 'css' => $css];
}

function isViteRunning($host = 'localhost', $port = 5173, $timeout = 0.05): bool
{
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($fp) {
        fclose($fp);
        echo "<script>console.log('[EisBulma] Vite Running : Dev mode');</script>";
        return true;
    }
    echo "<script>console.log('[EisBulma] Vite offline : Production mode');</script>";
    return false;
}


function eisbulma_scripts()
{

    

    wp_enqueue_style('eisbulma-style', get_stylesheet_uri(), [], null);
    // don't fuck up ajax calls (admin-ajax.php)
    if (wp_doing_ajax()) {
        return;
    }
    $is_dev = defined('WP_ENV') && WP_ENV === 'development';
   
    if ($is_dev && isViteRunning(){
        $origin = 'https://' . parse_url(home_url(), PHP_URL_HOST) . ':5173';
        wp_enqueue_script('vite-client', $origin . '/@vite/client', [], null, true);
        wp_script_add_data('vite-client', 'type', 'module');
        wp_enqueue_script('eis-main', $origin . '/src/js/main.js', [], null, true);
        wp_script_add_data('eis-main', 'type', 'module');
        // Main.js will load all our scss
        }
    } else {
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
                wp_enqueue_script('eis-main', $entry['js'], [], null, true);
                wp_script_add_data('eis-main', 'type', 'module');
            }
        }
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'eisbulma_scripts', 10);


add_action('enqueue_block_editor_assets', function () {
    $is_dev = defined('WP_ENV') && WP_ENV === 'development';

    if ($is_dev && isViteRunning(){
        // DEV MODE
        $origin = 'https://' . parse_url(home_url(), PHP_URL_HOST) . ':5173';

        // Vite client for HMR
        wp_enqueue_script('vite-client', $origin . '/@vite/client', [], null, true);
        wp_script_add_data('vite-client', 'type', 'module');
    } else {
        // PROD MODE
        $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            // Enqueue CSS file generated from the build
            if (isset($entry['css']) && is_array($entry['css'])) {
                foreach ($entry['css'] as $css_file) {
                    wp_enqueue_style(
                        'theme-editor',
                        get_template_directory_uri() . '/dist/' . $css_file,
                        ['wp-edit-blocks'],
                        null
                    );
                }
            }
        }
    }
}, 10);


// Add type="module" to vite scripts
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    $module_handles = ['vite-client', 'eis-main', 'eis-editor'];
    if (in_array($handle, $module_handles, true)) {
        return str_replace('<script ', '<script type="module" ', $tag);
    }
    return $tag;
}, 10, 3);
