<?php
/**
 * WooCommerce Hook: Breadcrumbs
 *
 * Customizes WooCommerce breadcrumbs with Bulma styling.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Remove default WooCommerce breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Add custom breadcrumbs
add_action('template_redirect', function() {
    if (!is_shop()) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    }
});

function custom_woocommerce_breadcrumb()
{
    $args = array(
        'delimiter'   => '',
        'wrap_before' => '<nav class="breadcrumb" aria-label="breadcrumbs"><ul>',
        'wrap_after'  => '</ul></nav>',
        'before'      => '<li>',
        'after'       => '</li>',
        'home'        => __('Home', 'woocommerce'),
    );

    // Start output buffering to capture the breadcrumb HTML
    ob_start();

    add_filter('woocommerce_breadcrumb_defaults', function ($defaults) use ($args) {
        return wp_parse_args($args, $defaults);
    });

    woocommerce_breadcrumb($args);

    // Get the breadcrumb HTML
    $breadcrumb_html = ob_get_clean();

    // Modify the last <li> to add the is-active class
    $breadcrumb_html = add_active_class_to_last_li($breadcrumb_html);

    echo $breadcrumb_html;
}

function add_active_class_to_last_li($html)
{
    // Find all <li> tags and their content
    if (preg_match_all('/<li[^>]*>.*?<\/li>/', $html, $li_matches, PREG_OFFSET_CAPTURE)) {
        // Get the last <li> element (full content)
        $last_li_full = end($li_matches[0]);
        $last_li_content = $last_li_full[0];
        $last_li_position = $last_li_full[1];

        // Extract just the opening <li> tag
        if (preg_match('/<li[^>]*>/', $last_li_content, $li_tag_matches)) {
            $last_li_tag = $li_tag_matches[0];

            // Add is-active class to the <li> tag
            if (preg_match('/class=["\']([^"\']*)["\']/', $last_li_tag, $class_matches)) {
                // Add to existing class
                $existing_classes = $class_matches[1];
                $new_li_tag = str_replace($class_matches[0], 'class="' . $existing_classes . ' is-active"', $last_li_tag);
            } else {
                // Add new class attribute
                $new_li_tag = str_replace('<li', '<li class="is-active"', $last_li_tag);
            }

            // Check if the last breadcrumb item has a link or is just text
            if (preg_match('/<a\s+href=["\'][^"\']*["\'][^>]*>([^<]+)<\/a>/', $last_li_content, $link_matches)) {
                // It has a link, keep it as is but update the <li> tag
                $new_li_content = str_replace($last_li_tag, $new_li_tag, $last_li_content);
            } else {
                // It's just text, wrap it in a link with href="#"
                if (preg_match('/<li[^>]*>([^<]+)<\/li>/', $last_li_content, $text_matches)) {
                    $breadcrumb_text = trim($text_matches[1]);
                    $new_li_content = $new_li_tag . '<a href="#">' . $breadcrumb_text . '</a></li>';
                } else {
                    // Fallback: just update the <li> tag
                    $new_li_content = str_replace($last_li_tag, $new_li_tag, $last_li_content);
                }
            }

            // Replace the last <li> element in the HTML
            $html = substr_replace($html, $new_li_content, $last_li_position, strlen($last_li_content));
        }
    }

    return $html;
}