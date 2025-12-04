<?php
/**
 * WooCommerce Hook: Single Product Layout Setup
 *
 * Removes default WooCommerce actions and replaces them with Bulma-styled layouts.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

add_action('after_setup_theme', 'myshop_setup_single_product_layout');

/**
 * Setup single product layout hooks
 */
function myshop_setup_single_product_layout()
{
    // Remove default blocks
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

    // Add Bulma wrappers
    add_action('woocommerce_before_single_product', 'myshop_open_wrapper', 1);
    add_action('woocommerce_after_single_product', 'myshop_close_wrapper', 99);

    // Add custom layout sections
    add_action('woocommerce_before_single_product_summary', 'myshop_render_two_columns', 9);
    add_action('woocommerce_after_single_product_summary', 'myshop_render_tabs_accordion', 10);
    add_action('woocommerce_after_single_product_summary', 'myshop_render_upsells_related', 15);
    add_action('woocommerce_after_single_product_summary', 'myshop_render_photoswipe', 99);
}

/**
 * Open section wrapper
 */
function myshop_open_wrapper()
{
    echo '<section class="section">';
}

/**
 * Close section wrapper
 */
function myshop_close_wrapper()
{
    echo '</section>';
}

/**
 * Render two-column layout (gallery + summary)
 */
function myshop_render_two_columns()
{
    wc_get_template(
        'single-product/hero.php',
        [],
        '',
        myshop_wc_base_path()
    );
}

/**
 * Render tabs as accordion
 */
function myshop_render_tabs_accordion()
{
    wc_get_template(
        'single-product/tabs-accordion.php',
        [],
        '',
        myshop_wc_base_path()
    );
}

/**
 * Render upsells and related products
 */
function myshop_render_upsells_related()
{
    echo '<div class="mt-6">';
    woocommerce_upsell_display(4, 4);
    woocommerce_output_related_products(['posts_per_page' => 4, 'columns' => 4]);
    echo '</div>';
}

/**
 * Render photoswipe if template exists
 */
function myshop_render_photoswipe()
{
    if (locate_template('woocommerce/single-product/photoswipe.php')) {
        wc_get_template('single-product/photoswipe.php');
    }
}
