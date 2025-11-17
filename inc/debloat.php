<?php 
function mytheme_maybe_dequeue_jquery() {
    if ( is_admin() ) {
        return;
    }

    // On garde jQuery sur les pages WooCommerce
    if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        return;
    }

    // Ailleurs uniquement
    wp_dequeue_script( 'jquery' );
    wp_deregister_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'mytheme_maybe_dequeue_jquery', 20 );