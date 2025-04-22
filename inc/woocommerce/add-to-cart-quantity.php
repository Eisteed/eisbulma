<?php
/**
 * Product Quantity in add to cart button
 *
 * @since 1.0.0
 */
add_filter('woocommerce_loop_add_to_cart_link', function ($button, $product, $args) {
    $cart = WC()->cart->get_cart();
    $quantity = 0;

    foreach ($cart as $cart_item) {
        if ($cart_item['product_id'] == $product->get_id()) {
            $quantity = $cart_item['quantity'];
            break;
        }
    }

    $product_id = $product->get_id();
    $product_url = '?add-to-cart=' . $product_id;

    $classes = implode(' ', array_filter([
        'button',
        'product_type_' . $product->get_type(),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button ajax_add_to_cart' : '',
    ]));

    $default_text = $product->add_to_cart_text();
    $button_text = $quantity > 0
        ? sprintf(__('In Cart (%d)', 'woocommerce'), $quantity)
        : $default_text;

    $button = sprintf(
        '<a href="%s" data-quantity="1" data-product_id="%d" data-product_sku="%s" class="%s" data-default-text="%s">%s</a>',
        esc_url($product_url),
        esc_attr($product_id),
        esc_attr($product->get_sku()),
        esc_attr($classes),
        esc_attr($default_text),
        esc_html($button_text)
    );

    return $button;
}, 10, 3);

add_action('wp_ajax_get_cart_product_quantity', 'get_cart_product_quantity');
add_action('wp_ajax_nopriv_get_cart_product_quantity', 'get_cart_product_quantity');

function get_cart_product_quantity()
{
    if (!isset($_POST['product_id'])) {
        wp_send_json_error('Missing product_id');
    }

    $product_id = intval($_POST['product_id']);
    $quantity = 0;

    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] === $product_id) {
            $quantity = $cart_item['quantity'];
            break;
        }
    }

    wp_send_json_success(['quantity' => $quantity]);
}
add_action('wp_footer', function () {
    ?>
    <script>
        jQuery(function ($) {

            function updateAllCartButtons() {
                $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                    action: 'get_all_cart_quantities'
                }, function (response) {
                    if (response.success) {
                        const cartQuantities = response.data;

                        $('.add_to_cart_button').each(function () {
                            const $btn = $(this);
                            const product_id = $btn.data('product_id');

                            if (!product_id) return;

                            const quantity = cartQuantities[product_id] || 0;

                            // Get original WooCommerce text from data attribute
                            const originalText = $btn.data('default-text') || 'Add to cart';

                            if (quantity > 0) {
                                $btn.html('In Cart (' + quantity + ')');
                                $btn.addClass('added-to-cart');
                            } else {
                                $btn.html(originalText);
                                $btn.removeClass('added-to-cart');
                            }
                        });
                    }
                });
            }

            // On WooCommerce add to cart
            $('body').on('added_to_cart', function () {
                updateAllCartButtons();
            });

            // On Addonify floating cart update
            document.addEventListener("addonifyFloatingCartUpdated", function () {
                updateAllCartButtons();
            });
        });
    </script>
    <?php
});


add_action('wp_ajax_get_all_cart_quantities', 'get_all_cart_quantities');
add_action('wp_ajax_nopriv_get_all_cart_quantities', 'get_all_cart_quantities');

function get_all_cart_quantities() {
    $quantities = [];

    foreach (WC()->cart->get_cart() as $cart_item) {
        $product_id = $cart_item['product_id'];
        $quantities[$product_id] = ($quantities[$product_id] ?? 0) + $cart_item['quantity'];
    }

    wp_send_json_success($quantities);
}
