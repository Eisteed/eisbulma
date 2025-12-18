<?php

/**
 * Product Quantity in add to cart button
 *
 * @since 1.0.0
 */

add_filter('woocommerce_loop_add_to_cart_link', function ($button, $product, $args) {
    if (is_admin() && ! wp_doing_ajax()) {
        return $button;
    }

    // on ne touche qu'aux produits simples achetables & en stock
    if (
        ! $product->is_purchasable() ||
        ! $product->is_in_stock() ||
        ! $product->is_type('simple')
    ) {
        return $button; // on laisse Woo gérer (variables, groupés, etc.)
    }

    static $cart_quantities = null;

    if ($cart_quantities === null) {
        // Use cached cart data for better performance
        $cart_data = EisBulma_Cart_Cache::get_cart_data();
        $cart_quantities = $cart_data['quantities'];
    }

    $product_id = $product->get_id();
    $quantity   = $cart_quantities[$product_id] ?? 0;

    // ici on prend l’URL WooCommerce, pas un ?add-to-cart= forcé
    $product_url = $product->add_to_cart_url();

    $classes = implode(' ', array_filter([
        'button',
        'is-secondary',
        'product_type_' . $product->get_type(),           // product_type_simple
        'add_to_cart_button',
        'ajax_add_to_cart',
    ]));

    $default_text = $product->add_to_cart_text();
    $button_text  = $quantity > 0
        // translators: %d is the product quantity currently in the cart.
        ? sprintf(__('In Cart (%d)', 'eisbulma'), $quantity)
        : $default_text;

    return sprintf(
        '<a href="%s" data-quantity="1" data-product_id="%d" data-product_sku="%s" class="%s" data-default-text="%s">%s</a>',
        esc_url($product_url),
        esc_attr($product_id),
        esc_attr($product->get_sku()),
        esc_attr($classes),
        esc_attr($default_text),
        esc_html($button_text)
    );
}, 10, 3);


add_action('wp_ajax_get_all_cart_quantities', 'get_all_cart_quantities');
add_action('wp_ajax_nopriv_get_all_cart_quantities', 'get_all_cart_quantities');

function get_all_cart_quantities()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'cart_quantity_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Use cached cart data for better performance
    $cart_data = EisBulma_Cart_Cache::get_cart_data();

    wp_send_json_success($cart_data['quantities']);
}
