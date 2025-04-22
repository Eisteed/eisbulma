<?php

// Disable woocommerce css
add_filter('woocommerce_enqueue_styles', '__return_false');

// Disable woocommerce blocks css
add_filter( 'woocommerce_should_load_block_styles', '__return_false' );

// Enable register on my account login page
function enable_woocommerce_registration()
{
    update_option('woocommerce_enable_myaccount_registration', 'yes');
}
add_action('init', 'enable_woocommerce_registration');

add_filter('woocommerce_add_to_cart_redirect', '__return_false');

add_filter( 'woocommerce_catalog_orderby', function( $options ) {
    $options['menu_order'] = __( 'Default', 'eisbulma' );
    $options['popularity'] = __( '🔥 Popularité', 'eisbulma' );
    $options['rating']     = __( '⭐ Meilleur note', 'eisbulma' );
    $options['date']       = __( '🆕 Plus récent', 'eisbulma' );
    $options['price']      = __( '⬇️ Prix croissant', 'eisbulma' );
    $options['price-desc'] = __( '⬆️ Prix décroissant', 'eisbulma' );
    return $options;
} );

foreach (glob(__DIR__ . '/woocommerce/*.php') as $file) {
    require_once $file;
}