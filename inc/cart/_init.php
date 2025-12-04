<?php

/**
 * Cart related functions
 *
 * @since 1.0.0
 */

// Prevent redirect after adding a product
add_filter('woocommerce_add_to_cart_redirect', 'wp_get_referer' );

add_action('wp_enqueue_scripts', function() {
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
    }
});

// Load cache layer first
require_once  'cache.php';

// Load cart functionality
require_once  'add-to-cart-quantity.php';
require_once  'floating-cart.php';