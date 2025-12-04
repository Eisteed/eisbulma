<?php
/**
 * WooCommerce Hook: Notifications
 *
 * Replaces WooCommerce notification styles with Bulma message components.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Format WooCommerce notifications with Bulma styles
 */
add_filter('woocommerce_add_message', 'bulma_wc_format_success_notice', 50);
add_filter('woocommerce_add_error', 'bulma_wc_format_error_notice', 50);

add_filter('woocommerce_add_notice', 'bulma_wc_format_info_notice', 50);

function bulma_wc_format_success_notice($message) {
    return '<article class="message is-success m-5">
                <div class="message-body" role="alert" style="z-index:10;">' . wp_kses_post($message) . '</div><div class="is-clearfix"></div></article>';
}

function bulma_wc_format_error_notice($message) {
    return '<div class="notification is-danger is-light mb-3" role="alert" style="z-index:3;">' . wp_kses_post($message) . '</div>';
}

// Pour 'woocommerce_add_notice', on considère que c'est une "info"
function bulma_wc_format_info_notice($message) {
    return '<article class="message is-info">
                <div class="message-body" role="alert" style="z-index:3;">' . wp_kses_post($message) . '</div></article>';
}

// Affichage des notices là où tu le souhaites
remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
add_action('woocommerce_before_single_product', function () {
    wc_print_notices();
}, 10);


// Ajouter un bouton "Retour à la boutique" uniquement sur la page vide
add_action('woocommerce_no_products_found', function () {
    $shop_url = wc_get_page_permalink('shop');
    echo '<div class="has-text-centered mt-5">
        <a href="' . esc_url($shop_url) . '" class="button is-primary is-light">
            ← Retour à la boutique
        </a>
    </div>';
}, 20);