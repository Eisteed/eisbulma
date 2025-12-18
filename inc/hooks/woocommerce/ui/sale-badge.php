<?php
/**
 * WooCommerce Hook: Sale Badge
 *
 * Customizes the sale flash badge with Bulma tag styling and percentage display.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

add_filter('woocommerce_sale_flash', function ($html, $post, $product) {
    $percentage = '';

    if ($product->is_on_sale() && $product->get_regular_price()) {
        $regular = (float) $product->get_regular_price();
        $sale = (float) $product->get_sale_price();
        if ($regular > 0 && $sale > 0) {
            $percent = round((($regular - $sale) / $regular) * 100);
            $percentage = '-' . $percent . '%';
        }
    }

    return '<span class="onsale tag is-medium is-danger is-position-absolute">Promo ' . esc_html($percentage) . '</span>';
}, 10, 3);
