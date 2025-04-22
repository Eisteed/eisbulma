<?php
class EisBulmaBlocks
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'remove_block_styles'], 20);
        add_action('after_setup_theme', [$this, 'setup_editor_styles']);
        add_filter('render_block', [$this, 'replace_block_classes'], 10, 2);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_script']);
        add_filter('should_load_block_editor_scripts_and_styles', '__return_false');
        add_action('enqueue_block_assets', [$this, 'mytheme_block_assets']);
        add_action('init', [$this, 'remove_global_styles_inline_css']);
    }
    public function remove_global_styles_inline_css()
    {
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    }

    public function remove_block_styles()
    {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
        wp_deregister_style('wp-block-editor');
        wp_deregister_style('wp-edit-blocks');
        wp_register_style('wp-edit-blocks', '');
    }

    function mytheme_block_assets()
    {

        wp_deregister_style('wp-block-library');
        wp_register_style('wp-block-library', '');
    }


    public function setup_editor_styles()
    {
        add_editor_style('styles/theme.min.css');
    }

    public function replace_block_classes($block_content, $block)
    {
        $mappings = $this->get_class_mappings();

        foreach ($mappings as $wp_class => $bulma_class) {
            // Replace class names with proper spacing to avoid false positives
            $block_content = preg_replace(
                '/class=("|\')(.*?)\b' . preg_quote($wp_class, '/') . '\b(.*?)(\1)/',
                'class=$1$2' . $bulma_class . '$3$4',
                $block_content
            );
        }

        return $block_content;
    }

    /**
     * Map of WordPress block classes to Bulma classes
     */
    public function get_class_mappings()
    {
        $json_path = get_stylesheet_directory() . '/src/bulma-blocks.json';

        if (!file_exists($json_path)) {
            return [];
        }

        $json_content = file_get_contents($json_path);
        $mapping = json_decode($json_content, true);

        return is_array($mapping) ? $mapping : [];
    }

    public function enqueue_editor_script()
    {
        wp_enqueue_script(
            'bulma-editor-js',
            get_stylesheet_directory_uri() . '/js/bulma-blocks.js',
            ['wp-dom-ready', 'wp-edit-post'],
            filemtime(get_stylesheet_directory() . '/js/bulma-blocks.js'),
            true
        );

        // Passer l'URL du fichier JSON à JS
        wp_localize_script('bulma-editor-js', 'bulmaBlocks', [
            'jsonUrl' => get_stylesheet_directory_uri() . '/src/bulma-blocks.json',
        ]);
    }
}

new EisBulmaBlocks();
