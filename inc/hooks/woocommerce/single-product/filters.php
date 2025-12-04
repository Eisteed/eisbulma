<?php
/**
 * WooCommerce Hook: Single Product Filters
 *
 * Standalone filters for single product page functionality.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Remove stock number from availability text
 */
add_filter('woocommerce_get_availability_text', function ($text, $product) {
    if ($product->is_in_stock()) {
        return __('In stock', 'woocommerce');
    }
    return $text;
}, 10, 2);

/**
 * Remove all product tags on single product pages
 */
add_action('woocommerce_before_single_product', function () {
    if (!is_product()) {
        return;
    }

    global $product;

    if ($product instanceof WC_Product) {
        $product_id = $product->get_id();
        wp_remove_object_terms(
            $product_id,
            get_terms('product_tag', ['fields' => 'ids']),
            'product_tag'
        );
    }
});
