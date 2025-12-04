<?php
/**
 * WooCommerce Config: Disable Default Styles
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Disable default WooCommerce CSS
add_filter('woocommerce_enqueue_styles', '__return_false');

// Optionally disable WooCommerce block styles
// add_filter('woocommerce_should_load_block_styles', '__return_false');
