<?php
/**
 * WooCommerce Hook: Quantity Buttons
 *
 * Adds increment/decrement buttons around quantity input fields with Bulma styling.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

add_action('woocommerce_before_quantity_input_field', 'my_qty_minus_button');
function my_qty_minus_button()
{
    echo '<button type="button" class="button is-small qty-btn-minus"><span class="icon is-small">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256">
                                                <path d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128Z"></path>
                                            </svg></span></button>';
}

add_action('woocommerce_after_quantity_input_field', 'my_qty_plus_button');
function my_qty_plus_button()
{
    echo '<button type="button" class="button is-small qty-btn-plus"><span class="icon is-small">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256">
                                                <path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"></path>
                                            </svg> </span></button>';
}
add_action( 'wp_footer', 'my_qty_buttons_script' );
function my_qty_buttons_script() {

    if ( ! is_product() && ! is_cart() && ! is_checkout() ) {
        return;
    }
    ?>
    <script>
    jQuery(function($) {

        $(document).on('click', '.qty-btn-plus, .qty-btn-minus', function(e) {
            e.preventDefault();

            // Ici, on cible ton <input class="input" type="number" ...>
            var $qty   = $(this).closest('.quantity').find('input[type="number"], input[name="quantity"]').first();

            if (!$qty.length) return;

            var current = parseFloat($qty.val()) || 0;
            var max     = parseFloat($qty.attr('max'));
            var min     = parseFloat($qty.attr('min'));
            var step    = parseFloat($qty.attr('step'));

            if (isNaN(max))  max  = Infinity;
            if (isNaN(min))  min  = 0;
            if (isNaN(step) || step === 0) step = 1;

            if ($(this).hasClass('qty-btn-plus')) {
                // +
                if (current + step > max) {
                    current = max;
                } else {
                    current = current + step;
                }
            } else {
                // -
                if (current - step < min) {
                    current = min;
                } else {
                    current = current - step;
                }
            }

            $qty.val(current).trigger('change');
        });

    });
    </script>
    <?php
}
