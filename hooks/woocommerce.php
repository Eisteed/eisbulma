<?php

// Disable woocommerce css
add_filter('woocommerce_enqueue_styles', '__return_false');

// Disable woocommerce blocks css
//add_filter( 'woocommerce_should_load_block_styles', '__return_false' );

// Enable register on my account login page
function enable_woocommerce_registration()
{
    update_option('woocommerce_enable_myaccount_registration', 'yes');
}
add_action('init', 'enable_woocommerce_registration');

if ( ! function_exists( 'myshop_wc_base_path' ) ) {
    function myshop_wc_base_path() {
        return trailingslashit( get_stylesheet_directory() ) . 'hooks/woocommerce/';
    }
}

foreach (glob(__DIR__ . '/woocommerce/*.php') as $file) {
    require_once $file;
}
