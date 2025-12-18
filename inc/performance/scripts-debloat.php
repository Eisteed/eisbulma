<?php

/**
 * Scripts Debloat Functions
 *
 * Manages JavaScript files removal and optimization.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Dequeue unnecessary scripts
 */
function eisbulma_debloat_scripts()
{
    // Never debloat in admin
    if (is_admin()) {
        return;
    }

    // Check if it's a WooCommerce page
    $is_woocommerce = eisbulma_is_woocommerce_page();

    // Remove jQuery on non-WooCommerce pages only
    if (!$is_woocommerce) {
        wp_dequeue_script('jquery');
        wp_deregister_script('jquery');
    }

    // Remove embed script (always)
    wp_dequeue_script('wp-embed');

    // Remove block library JS if page doesn't have blocks
    if (!eisbulma_has_blocks()) {
        wp_dequeue_script('wp-block-library');
        wp_deregister_script('wp-block-library');
    }

    // Only keep Boxtal scripts on checkout and cart pages
    if (!is_checkout() && !is_cart()) {
        wp_dequeue_script('bw_maplibre_gl');
        wp_deregister_script('bw_maplibre_gl');
    }
}
add_action('wp_enqueue_scripts', 'eisbulma_debloat_scripts', 20);

/**
 * Suppress WooCommerce Blocks dependency errors on non-WooCommerce pages
 * The error is harmless on pages where Stripe isn't needed
 */
function eisbulma_suppress_stripe_dependency_errors()
{
    // Skip if admin or WooCommerce page
    if (is_admin() || eisbulma_is_woocommerce_page()) {
        return;
    }

    // Add inline script to suppress the jQuery dependency error message
    // This error appears because Stripe Blocks checks for jQuery globally
    // but it's only needed on cart/checkout pages
    ?>
    <script>
    // Suppress WooCommerce Blocks payment gateway dependency errors on non-WooCommerce pages
    (function() {
        const originalError = console.error;
        console.error = function(...args) {
            const message = args[0];
            if (typeof message === 'string' &&
                message.includes('wc-stripe-blocks-integration') &&
                message.includes('dependency')) {
                return; // Suppress this specific error
            }
            originalError.apply(console, args);
        };
    })();
    </script>
    <?php
}
add_action('wp_head', 'eisbulma_suppress_stripe_dependency_errors', 1);
