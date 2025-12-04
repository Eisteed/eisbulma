<?php
/**
 * WooCommerce Config: Registration Settings
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Enable registration on my account login page
add_action('init', function () {
    update_option('woocommerce_enable_myaccount_registration', 'yes');
});
