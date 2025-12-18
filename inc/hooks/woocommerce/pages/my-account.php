<?php
/**
 * WooCommerce Hook: My Account Page Layout
 *
 * Adds Bulma layout structure to WooCommerce my account page.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Wrap entire my account page with Bulma layout
 */
add_action('woocommerce_account_navigation', function() {
    // Open section and columns before navigation
    echo '<section class="section"><div class="container"><div class="columns"><div class="column is-one-quarter">';
}, 5);

add_action('woocommerce_account_navigation', function() {
    // Close nav column and open content column after navigation
    echo '</div><div class="column">';
}, 15);

add_action('woocommerce_after_account_content', function() {
    // Close all wrappers after content
    echo '</div></div></div></section>';
}, 999);

/**
 * Add Bulma menu classes to navigation
 */
add_filter('woocommerce_account_menu_item_classes', function($classes, $endpoint) {
    global $wp;

    // Add is-active to current endpoint
    if (isset($wp->query_vars[$endpoint]) || (empty($wp->query_vars) && $endpoint === 'dashboard')) {
        $classes[] = 'is-active';
    }

    return $classes;
}, 10, 2);

/**
 * Replace navigation wrapper classes with Bulma menu classes
 */
add_action('wp_footer', function() {
    if (!is_account_page()) return;
    ?>
    <script>
    (function() {
        const nav = document.querySelector('.woocommerce-MyAccount-navigation');
        if (nav) {
            nav.className = 'woocommerce-MyAccount-navigation menu';
            const ul = nav.querySelector('ul');
            if (ul) ul.className = 'menu-list';
        }

        // Add is-active to links
        document.querySelectorAll('.woocommerce-MyAccount-navigation li.is-active > a').forEach(a => {
            a.classList.add('is-active');
        });
    })();
    </script>
    <?php
});
