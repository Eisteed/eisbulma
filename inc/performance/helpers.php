<?php

/**
 * Performance Helper Functions
 *
 * Utility functions used by performance optimization features.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Check if current page/post has Gutenberg blocks
 *
 * @return bool
 */
function eisbulma_has_blocks()
{
    global $post;

    // If no post, return false
    if (!$post) {
        return false;
    }

    // Check if post content has blocks
    if (function_exists('has_blocks')) {
        return has_blocks($post->post_content);
    }

    // Fallback: Check for block comment markers
    if (!empty($post->post_content)) {
        return strpos($post->post_content, '<!-- wp:') !== false;
    }

    return false;
}

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

    // Check if page contains WooCommerce shortcodes
    global $post;
    if ($post && has_shortcode($post->post_content, 'product_page')) {
        return true;
    }

    // Check post type as fallback for single product pages
    $post_type = get_post_type();

    return is_woocommerce()
        || is_shop()
        || is_product_category()
        || is_product_tag()
        || is_product()
        || is_cart()
        || is_checkout()
        || is_account_page()
        || $post_type === 'product';
}
