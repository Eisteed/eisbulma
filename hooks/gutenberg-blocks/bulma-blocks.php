<?php



// Editor styles CSS
add_theme_support('editor-styles');
add_editor_style('src/styles/gutenberg-editor-bulma.css');

// Editor button style fix with some additional js
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'gutenberg-editor-bulma-js',
        get_stylesheet_directory_uri() . '/src/js/gutenberg-editor-bulma.js',
        ['wp-hooks', 'wp-compose', 'wp-element'],
        '1.0.0',
        true
    );
});

add_filter('render_block', 'replace_block_classes', 999, 2);

function get_class_mappings()
{
    $json_path = get_stylesheet_directory() . '/src/styles/bulma-blocks.json';

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
    'name'  => 'bulma-link',
    'label' => 'Bulma Link',
    'inline_style' => '.button .is-link'
]);

add_filter('render_block_core/buttons', function (string $content, array $block): string {
    if ($content === '') return $content;

    // Load as HTML fragment
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content);
    libxml_clear_errors();

    $xpath = new DOMXPath($doc);

    // For each .button inside .wp-block-buttons
    foreach ($xpath->query('//div[contains(@class,"wp-block-buttons")]//div[contains(@class,"button")]') as $buttonDiv) {
        /** @var DOMElement $buttonDiv */
        $a = $buttonDiv->getElementsByTagName('a')->item(0);
        if (!$a) continue;

        // Collect classes from <a>
        $aClasses = preg_split('/\s+/', trim($a->getAttribute('class') ?? '')) ?: [];

        // Keep only Gutenberg “has-*” utility classes (adjust the whitelist if needed)
        $keep = array_filter($aClasses, function ($c) {
            return $c !== '' && (
                strpos($c, 'has-') === 0   // e.g. has-vivid-red-background-color, has-text-color, has-link-color
                || $c === 'has-background'
                || $c === 'has-text-color'
                || $c === 'has-link-color'
            );
        });

        if (!$keep) continue;

        // Merge onto parent .button (avoid duplicates)
        $parentClasses = preg_split('/\s+/', trim($buttonDiv->getAttribute('class') ?? '')) ?: [];
        $merged = array_values(array_unique(array_merge($parentClasses, $keep)));
        $buttonDiv->setAttribute('class', implode(' ', $merged));
    }

    // Return fragment without the added <body> wrapper
    $body = $doc->getElementsByTagName('body')->item(0);
    $out  = '';
    foreach ($body->childNodes as $child) {
        $out .= $doc->saveHTML($child);
    }
    return $out;
}, 10, 2);
