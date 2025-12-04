<?php

/**
 * Theme Debloat Functions
 *
 * Removes unnecessary WordPress scripts and styles to improve performance.
 * Keeps necessary scripts on WooCommerce pages.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Check if current page is a WooCommerce page
 *
 * @return bool
 */
function eisbulma_is_woocommerce_page()
{
    if (!class_exists('WooCommerce')) {
        return false;
    }

    return is_woocommerce()
        || is_shop()
        || is_product_category()
        || is_product_tag()
        || is_product()
        || is_cart()
        || is_checkout()
        || is_account_page();
}

/**
 * Dequeue unnecessary scripts and styles
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

    // Remove WooCommerce brands styles on non-WooCommerce pages
    if (!$is_woocommerce) {
        wp_dequeue_style('wc-brands');
    }

    // Remove emoji scripts (always)
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove embed script (always)
    wp_dequeue_script('wp-embed');

    // Remove block library CSS if not using block editor (always)
    // Comment these out if you use Gutenberg blocks on regular posts/pages
    //wp_dequeue_style('wp-block-library');
    //wp_dequeue_style('wp-block-library-theme');
    //wp_dequeue_style('classic-theme-styles');
    //wp_dequeue_style('global-styles');

    // Remove dashicons for non-logged-in users
    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
        wp_deregister_style('dashicons');
    }

    // Only keep Boxtal scripts on checkout and cart pages
      if (!is_checkout() && !is_cart()) {
          wp_dequeue_script('bw_maplibre_gl');
          wp_deregister_script('bw_maplibre_gl');
          wp_dequeue_style('bw_maplibre_gl');
          wp_deregister_style('bw_maplibre_gl');
      }

}
add_action('wp_enqueue_scripts', 'eisbulma_debloat_scripts', 20);

/**
 * Remove WordPress version from head
 */
remove_action('wp_head', 'wp_generator');

/**
 * Remove REST API link from head
 */
remove_action('wp_head', 'rest_output_link_wp_head');

/**
 * Remove shortlink from head
 */
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Remove RSD link from head
 */
remove_action('wp_head', 'rsd_link');

/**
 * Remove Windows Live Writer manifest link
 */
remove_action('wp_head', 'wlwmanifest_link');
