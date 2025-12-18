<?php

/**
 * Styles Debloat Functions
 *
 * Manages CSS files removal and optimization.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Dequeue unnecessary styles
 */
function eisbulma_debloat_styles()
{
    // Never debloat in admin
    if (is_admin()) {
        return;
    }

    // Check if it's a WooCommerce page
    $is_woocommerce = eisbulma_is_woocommerce_page();

    // Remove WooCommerce brands styles on non-WooCommerce pages
    if (!$is_woocommerce) {
        wp_dequeue_style('wc-brands');
    }

    // Remove block library CSS if page doesn't have blocks
    if (!eisbulma_has_blocks()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('classic-theme-styles');
        wp_dequeue_style('global-styles');
    }

    // Remove dashicons for non-logged-in users
    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
        wp_deregister_style('dashicons');
    }

    // Only keep Boxtal styles on checkout and cart pages
    if (!is_checkout() && !is_cart()) {
        wp_dequeue_style('bw_maplibre_gl');
        wp_deregister_style('bw_maplibre_gl');
    }
}
add_action('wp_enqueue_scripts', 'eisbulma_debloat_styles', 20);

/**
 * Remove emoji styles
 */
function eisbulma_remove_emoji_styles()
{
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'eisbulma_remove_emoji_styles');
