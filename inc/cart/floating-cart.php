<?php

/**
 * Plugin Name: Bulma Floating Cart
 * Description: A floating cart for WooCommerce using Bulma CSS
 * Version: 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BulmaFloatingCart
{

        public function __construct()
        {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_footer', array($this, 'render_floating_cart'));
    
            // AJAX handlers for cart operations
            add_action('wp_ajax_floating_cart_remove_item', array($this, 'remove_cart_item'));
            add_action('wp_ajax_nopriv_floating_cart_remove_item', array($this, 'remove_cart_item'));
    
            add_action('wp_ajax_floating_cart_update_quantity', array($this, 'update_cart_quantity'));
            add_action('wp_ajax_nopriv_floating_cart_update_quantity', array($this, 'update_cart_quantity'));
    
            add_action('wp_ajax_get_floating_cart_contents', array($this, 'get_cart_contents'));
            add_action('wp_ajax_nopriv_get_floating_cart_contents', array($this, 'get_cart_contents'));
    
            add_action('wp_ajax_get_cart_totals', array($this, 'get_cart_totals'));
            add_action('wp_ajax_nopriv_get_cart_totals', array($this, 'get_cart_totals'));
        }
    
        public function enqueue_scripts()
        {
            if (!is_admin()) {
                // wp_enqueue_script('jquery'); // no more jquery
            }
        }
    
        public function render_floating_cart()
        {
            if (!function_exists('WC') || is_admin()) {
                return;
            }
    
            $cart_count = WC()->cart->get_cart_contents_count();
            $cart_total = WC()->cart->get_cart_total();
    ?>
    
            <!-- Floating Cart Icon -->
            <div id="floating-cart-icon" class="is-fixed" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
                <button class="button is-secondary is-medium is-rounded" id="open-floating-cart">
                    <span class="icon m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#f0f0f0" viewBox="0 0 256 256">
                            <path d="M230.14,58.87A8,8,0,0,0,224,56H62.68L56.6,22.57A8,8,0,0,0,48.73,16H24a8,8,0,0,0,0,16h18L67.56,172.29a24,24,0,0,0,5.33,11.27,28,28,0,1,0,44.4,8.44h45.42A27.75,27.75,0,0,0,160,204a28,28,0,1,0,28-28H91.17a8,8,0,0,1-7.87-6.57L80.13,152h116a24,24,0,0,0,23.61-19.71l12.16-66.86A8,8,0,0,0,230.14,58.87ZM104,204a12,12,0,1,1-12-12A12,12,0,0,1,104,204Zm96,0a12,12,0,1,1-12-12A12,12,0,0,1,200,204Z"></path>
                        </svg> </span>
                    <span class="tag is-small is-position-absolute cart-count-badge" style="right: -0.3rem;top: -0.3rem;border: 1px solid #434343;"><?php echo $cart_count; ?></span>
                </button>
            </div>
    
            <!-- Floating Cart Panel -->
            <div id="floating-cart-panel" class="is-fixed">
    
                <!-- Cart Header -->
                <div class="box" style="border-radius: 0; border-bottom: 1px solid #dbdbdb; margin: 0;">
                    <div class="level is-mobile">
                        <div class="level-left">
                            <div class="level-item">
                                <h4 class="title is-5"><?php esc_html_e('Shopping Cart', 'eisbulma'); ?></h4>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button class="delete is-medium has-background-black" id="close-floating-cart"></button>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Cart Contents -->
                <div id="floating-cart-contents" class="p-4">
                    <span class="loader"></span>
                    <!-- Cart items will be loaded here via AJAX -->
                </div>
    
                <!-- Cart Footer -->
                <div class="box">
                    <div class="level is-mobile mb-3">
                        <div class="level-left">
                            <div class="level-item">
                                <strong><?php esc_html_e('Total :', 'eisbulma'); ?> </strong>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <span class="tag is-secondary is-medium" id="cart-total-display"><?php echo $cart_total; ?></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="buttons">
                        <a href="<?php echo wc_get_cart_url(); ?>" class="button is-light is-fullwidth">
                            <span class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </span>
                            <span><?php esc_html_e('View Cart', 'eisbulma'); ?></span>
                        </a>
                        <a href="<?php echo wc_get_checkout_url(); ?>" class="button is-secondary is-fullwidth">
                            <span class="icon">
                                <i class="fas fa-credit-card"></i>
                            </span>
                            <span><?php esc_html_e('Checkout', 'eisbulma'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
    
            <!-- Overlay -->
            <div id="floating-cart-overlay" class="modal-background" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1000;backdrop-filter: blur(2px);"></div>
    


        <?php
    }

    public function get_cart_contents()
    {
        if (!function_exists('WC')) {
            wp_send_json_error('WooCommerce not available');
        }

        $cart = WC()->cart->get_cart();

        if (empty($cart)) {
            wp_send_json_success(array(
                'html' => '<div class="has-text-centered p-4"><p>'. esc_html(__('Your cart is empty', 'eisbulma')) .'</p></div>'
            ));
        }

        ob_start();

        foreach ($cart as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];

            if (!$product || !$product->exists()) {
                continue;
            }

            $product_name = $product->get_name();
            $product_price = $product->get_price();
            $product_subtotal = WC()->cart->get_product_subtotal($product, $quantity);
            $product_image = $product->get_image(array(50, 50));
            $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';

            // Format price display (Price x Quantity) - strip HTML tags and decode entities
            $formatted_price = html_entity_decode(strip_tags(wc_price($product_price)), ENT_QUOTES, 'UTF-8');
            $price_display = $formatted_price . ' × ' . $quantity;
        ?>

            <div class="cart-item box mb-3" style="position: relative;">
                <!-- Delete button in top right -->
                <button class="remove-cart-item has-text-danger p-3"
                    data-cart-key="<?php echo esc_attr($cart_item_key); ?>"
                    title="<?php esc_attr_e('Remove item', 'eisbulma'); ?>"
                    style="position: absolute; top: 0px; right: 5px; z-index: 10;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#8e1414ff" viewBox="0 0 256 256">
                        <path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"></path>
                    </svg>
                </button>

                <div class="columns is-mobile is-vcentered">
                    <!-- Product Image -->
                    <div class="column is-narrow">
                        <?php if ($product_permalink): ?>
                            <a href="<?php echo esc_url($product_permalink); ?>">
                                <?php echo $product_image; ?>
                            </a>
                        <?php else: ?>
                            <?php echo $product_image; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Product Details -->
                    <div class="column">
                        <div class="content">
                            <?php if ($product_permalink): ?>
                                <a href="<?php echo esc_url($product_permalink); ?>" class="has-text-dark">
                                    <strong><?php echo esc_html($product_name); ?></strong>
                                </a>
                            <?php else: ?>
                                <strong><?php echo esc_html($product_name); ?></strong>
                            <?php endif; ?>

                            <br>
                            <small class="has-text-grey price-display">
                                <?php echo $price_display; ?>
                            </small>

                            <!-- Quantity Controls -->
                            <div class="field has-addons mt-2">
                                <div class="control">
                                    <button class="button is-small quantity-minus">
                                        <span class="icon is-small">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256">
                                                <path d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128Z"></path>
                                            </svg></span>
                                    </button>
                                </div>
                                <div class="control">
                                    <input type="number"
                                        class="input is-small cart-item-quantity has-text-centered"
                                        value="<?php echo esc_attr($quantity); ?>"
                                        min="1"
                                        data-cart-key="<?php echo esc_attr($cart_item_key); ?>"
                                        style="max-width: 60px;">
                                </div>
                                <div class="control">
                                    <button class="button is-small quantity-plus">
                                        <span class="icon is-small">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256">
                                                <path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"></path>
                                            </svg> </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item Total -->
                    <div class="column is-narrow has-text-right">
                        <strong class="item-total"><?php echo $product_subtotal; ?></strong>
                    </div>
                </div>
            </div>

<?php
        }

        $html = ob_get_clean();

        wp_send_json_success(array('html' => $html));
    }

    public function remove_cart_item()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'floating_cart_nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        if (!isset($_POST['cart_key'])) {
            wp_send_json_error('Missing cart key');
        }

        $cart_key = sanitize_text_field($_POST['cart_key']);

        if (WC()->cart->remove_cart_item($cart_key)) {
            wp_send_json_success('Item removed');
        } else {
            wp_send_json_error('Failed to remove item');
        }
    }

    public function update_cart_quantity()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'floating_cart_nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        if (!isset($_POST['cart_key']) || !isset($_POST['quantity'])) {
            wp_send_json_error('Missing parameters');
        }

        $cart_key = sanitize_text_field($_POST['cart_key']);
        $quantity = intval($_POST['quantity']);

        if ($quantity < 1) {
            wp_send_json_error('Invalid quantity');
        }

        if (WC()->cart->set_quantity($cart_key, $quantity)) {
            $cart_item = WC()->cart->get_cart_item($cart_key);
            $product = $cart_item['data'];
            $item_total = WC()->cart->get_product_subtotal($product, $quantity);

            // Format price display (Price x Quantity) - strip HTML tags and decode entities
            $product_price = $product->get_price();
            $formatted_price = html_entity_decode(strip_tags(wc_price($product_price)), ENT_QUOTES, 'UTF-8');
            $price_display = $formatted_price . ' × ' . $quantity;

            wp_send_json_success(array(
                'item_total' => html_entity_decode(strip_tags($item_total), ENT_QUOTES, 'UTF-8'),
                'price_display' => $price_display
            ));
        } else {
            wp_send_json_error('Failed to update quantity');
        }
    }

    public function get_cart_totals()
    {
        if (!function_exists('WC')) {
            wp_send_json_error('WooCommerce not available');
        }

        // Use cached cart data for better performance
        $cart_data = EisBulma_Cart_Cache::get_cart_data();

        wp_send_json_success(array(
            'count' => $cart_data['count'],
            'total' => $cart_data['total']
        ));
    }
}

// Initialize the plugin
$bulma_floating_cart = new BulmaFloatingCart();
?>