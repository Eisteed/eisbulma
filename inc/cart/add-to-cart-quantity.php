<?php

/**
 * Product Quantity in add to cart button
 *
 * @since 1.0.0
 */

add_filter('woocommerce_loop_add_to_cart_link', function ($button, $product, $args) {
    // Ne rien faire dans l'admin (éditeur de blocs, etc.), sauf en AJAX
    if (is_admin() && ! wp_doing_ajax()) {
        return $button;
    }

    $quantity = 0;

    // Vérifier que WooCommerce et le panier sont disponibles
    if (function_exists('WC') && WC()->cart) {
        $cart = WC()->cart->get_cart();

        foreach ($cart as $cart_item) {
            if ($cart_item['product_id'] == $product->get_id()) {
                $quantity = $cart_item['quantity'];
                break;
            }
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
        /* translators: %s: item number in cart. */
        ? sprintf(__('In Cart (%d)', 'eisbulma'), $quantity)
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
        document.addEventListener("DOMContentLoaded", () => {

            // Rendre le bouton single product compatible
            const singleForm = document.querySelector("form.cart");
            if (singleForm) {
                const submitBtn = singleForm.querySelector('button[name="add-to-cart"]');
                const singleBtn = singleForm.querySelector(".single_add_to_cart_button");

                if (submitBtn && singleBtn) {
                    const productId = submitBtn.value;

                    singleBtn.classList.add("add_to_cart_button");
                    singleBtn.setAttribute("data-product_id", productId);

                    if (!singleBtn.getAttribute("data-default-text")) {
                        singleBtn.setAttribute("data-default-text", singleBtn.textContent.trim());
                    }
                }
            }

            async function updateAllCartButtons() {
                const formData = new FormData();
                formData.append("action", "get_all_cart_quantities");

                const response = await fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                    method: "POST",
                    body: formData,
                    credentials: "same-origin"
                });
                // TEMPORAIREMENT POUR DEBUG :
                if (!response.ok) {
                    console.log("caca");
                    const text = await response.text();
                    console.error('admin-ajax.php error', response.status, text);
                    return;
                }
                const result = await response.json();
                if (!result.success) return;

                const cartQuantities = result.data;
                const buttons = document.querySelectorAll(".add_to_cart_button");

                buttons.forEach(btn => {
                    const productId = btn.getAttribute("data-product_id");
                    if (!productId) return;

                    const quantity = cartQuantities[productId] || 0;
                    const originalText = btn.getAttribute("data-default-text") || "Add to cart";

                    if (quantity > 0) {
                        btn.innerHTML = `<?php esc_html_e('In Cart', 'eisbulma'); ?> (${quantity})`;
                        btn.classList.add("added-to-cart");
                    } else {
                        btn.innerHTML = originalText;
                        btn.classList.remove("added-to-cart");
                    }
                });
            }

            // ---- AJAX ADD TO CART sur la page produit ----
            const productForm = document.querySelector("form.cart");
            if (productForm && typeof wc_add_to_cart_params !== 'undefined') {

                productForm.addEventListener("submit", async (e) => {
                    e.preventDefault();

                    const btn = productForm.querySelector(".single_add_to_cart_button");
                    const quantityInput = productForm.querySelector('input[name="quantity"]');
                    const addToCartInput = productForm.querySelector('button[name="add-to-cart"], input[name="add-to-cart"]');

                    const productId = addToCartInput ? addToCartInput.value : (btn ? btn.getAttribute("data-product_id") : null);
                    const quantity = quantityInput ? quantityInput.value : 1;
                    btn.classList.add("is-loading");
                    if (!productId) {
                        // fallback : si jamais
                        productForm.submit();
                        return;
                    }

                    // URL AJAX WooCommerce
                    const ajaxUrl = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart');

                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('quantity', quantity);

                    // Optionnel : trigger équivalent Woo "adding_to_cart"
                    document.body.dispatchEvent(new CustomEvent('adding_to_cart', {
                        detail: {
                            product_id: productId,
                            quantity: quantity
                        }
                    }));

                    try {
                        const response = await fetch(ajaxUrl, {
                            method: "POST",
                            body: formData,
                            credentials: "same-origin"
                        });

                        const result = await response.json();

                        // Si Woo renvoie une URL produit en cas d’erreur (ex: produit non achetable)
                        if (result && result.error && result.product_url) {
                            window.location.href = result.product_url;
                            return;
                        }

                        // Mise à jour du mini-panier / fragments si présents
                        if (result && result.fragments) {
                            for (const selector in result.fragments) {
                                const el = document.querySelector(selector);
                                if (el) {
                                    el.outerHTML = result.fragments[selector];
                                }
                            }
                        }

                        // Trigger "added_to_cart" pour ton script + plugins (floating cart, etc.)
                        document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                            detail: {
                                fragments: result.fragments,
                                cart_hash: result.cart_hash
                            }
                        }));
                        document.dispatchEvent(new CustomEvent('addonifyFloatingCartUpdated'));

                        // Met à jour les labels "In Cart (x)" partout
                        updateAllCartButtons();

                    } catch (err) {
                        console.error('AJAX add to cart error', err);
                        // fallback
                        productForm.submit();
                    } finally {
                        btn.classList.remove("is-loading");
                    }
                });
            }

            // // Mise à jour après clic sur un bouton du loop (sans jQuery)
            document.body.addEventListener("click", (event) => {
                const btn = event.target.closest(".add_to_cart_button.ajax_add_to_cart");
                if (!btn) return;

                // Loader optionnel sur les boutons du loop
                btn.classList.add("is-loading");

                // On laisse Woo faire son AJAX, puis on met à jour nos textes
                setTimeout(() => {
                    updateAllCartButtons();
                    btn.classList.remove("is-loading");
                }, 800); // tu peux ajuster 600–1000ms
            });
            document.addEventListener("addonifyFloatingCartUpdated", updateAllCartButtons);

            // Mise à jour initiale au chargement
            updateAllCartButtons();
        });
    </script>
<?php
});


add_action('wp_ajax_get_all_cart_quantities', 'get_all_cart_quantities');
add_action('wp_ajax_nopriv_get_all_cart_quantities', 'get_all_cart_quantities');

function get_all_cart_quantities()
{
    $quantities = [];

    foreach (WC()->cart->get_cart() as $cart_item) {
        $product_id = $cart_item['product_id'];
        $quantities[$product_id] = ($quantities[$product_id] ?? 0) + $cart_item['quantity'];
    }

    wp_send_json_success($quantities);
}
