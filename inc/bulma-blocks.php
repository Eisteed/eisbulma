<?php
    add_theme_support('editor-styles');
    add_editor_style('styles/gutenberg.min.css');
    add_filter('render_block', 'replace_block_classes', 999, 2);

    function get_class_mappings()
    {
        $json_path = get_stylesheet_directory() . '/src/bulma-blocks.json';

        if (!file_exists($json_path)) {
            return [];
        }

        $json_content = file_get_contents($json_path);
        $mapping = json_decode($json_content, true);

        return is_array($mapping) ? $mapping : [];
    }




    function replace_block_classes($block_content, $block)
    {
        $mappings = get_class_mappings();

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

    register_block_style('core/button', [
        'name'  => 'bulma-primary',
        'label' => 'Bulma Link',
        'inline_style' => '.button .is-link'
    ]);