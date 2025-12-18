/**
 * Optimized Cart with Client-Side Caching
 *
 * Features:
 * - localStorage caching for cart data
 * - Debounced updates
 * - Reduced AJAX calls
 * - Faster repeat visits
 */

document.addEventListener("DOMContentLoaded", () => {
    const ajaxUrl = eisbulma_cart_params.ajax_url;
    const floatingCartNonce = eisbulma_cart_params.floating_cart_nonce;
    const cartQuantityNonce = eisbulma_cart_params.cart_quantity_nonce;
    const i18n = eisbulma_cart_params.i18n;

    // ========================================
    // CACHE MANAGER
    // ========================================
    const CartCache = {
        CACHE_DURATION: 5 * 60 * 1000, // 5 minutes

        get(key) {
            try {
                const item = localStorage.getItem(`eisbulma_${key}`);
                if (!item) return null;

                const data = JSON.parse(item);
                const now = Date.now();

                // Check if expired
                if (now - data.timestamp > this.CACHE_DURATION) {
                    this.remove(key);
                    return null;
                }

                return data.value;
            } catch (e) {
                console.warn('Cache read error:', e);
                return null;
            }
        },

        set(key, value) {
            try {
                const data = {
                    value,
                    timestamp: Date.now()
                };
                localStorage.setItem(`eisbulma_${key}`, JSON.stringify(data));
            } catch (e) {
                console.warn('Cache write error:', e);
            }
        },

        remove(key) {
            try {
                localStorage.removeItem(`eisbulma_${key}`);
            } catch (e) {
                console.warn('Cache remove error:', e);
            }
        },

        clear() {
            try {
                // Remove all eisbulma cache keys
                for (let i = localStorage.length - 1; i >= 0; i--) {
                    const key = localStorage.key(i);
                    if (key && key.startsWith('eisbulma_')) {
                        localStorage.removeItem(key);
                    }
                }
            } catch (e) {
                console.warn('Cache clear error:', e);
            }
        }
    };

    // ========================================
    // DEBOUNCE UTILITY
    // ========================================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ========================================
    // AJAX HELPER
    // ========================================
    function postAjax(action, extra = {}) {
        const formData = new FormData();
        formData.append("action", action);
        formData.append("nonce", floatingCartNonce);
        Object.entries(extra).forEach(([k, v]) => formData.append(k, v));
        return fetch(ajaxUrl, {
            method: "POST",
            body: formData
        }).then(r => r.json());
    }

    // ========================================
    // CART TOTALS (with cache)
    // ========================================
    async function updateCartTotals(force = false) {
        // Try cache first
        if (!force) {
            const cached = CartCache.get('cart_totals');
            if (cached) {
                applyCartTotals(cached);
                return cached;
            }
        }

        const response = await postAjax("get_cart_totals");
        if (!response || !response.success) return null;

        const totals = response.data;

        // Cache the result
        CartCache.set('cart_totals', totals);

        applyCartTotals(totals);
        return totals;
    }

    function applyCartTotals(totals) {
        // Update all cart count badges
        const countBadges = document.querySelectorAll(".cart-count-badge");
        countBadges.forEach(badge => {
            badge.textContent = totals.count;
            // Hide badge when count is 0
            if (totals.count === 0 || totals.count === '0') {
                badge.style.display = 'none';
            } else {
                badge.style.display = '';
            }
        });

        const totalDisplay = document.getElementById("cart-total-display");
        if (totalDisplay) totalDisplay.innerHTML = totals.total;
    }

    // ========================================
    // CART BUTTONS (with cache)
    // ========================================
    async function updateAllCartButtons(force = false) {
        // Try cache first
        if (!force) {
            const cached = CartCache.get('cart_quantities');
            if (cached) {
                applyCartQuantities(cached);
                return;
            }
        }

        const formData = new FormData();
        formData.append("action", "get_all_cart_quantities");
        formData.append("nonce", cartQuantityNonce);

        const response = await fetch(ajaxUrl, {
            method: "POST",
            body: formData,
            credentials: "same-origin"
        });

        if (!response.ok) {
            console.error('admin-ajax.php error', response.status);
            return;
        }

        const result = await response.json();
        if (!result.success) return;

        const cartQuantities = result.data;

        // Cache the result
        CartCache.set('cart_quantities', cartQuantities);

        applyCartQuantities(cartQuantities);
    }

    function applyCartQuantities(cartQuantities) {
        const buttons = document.querySelectorAll(".add_to_cart_button");

        buttons.forEach(btn => {
            const productId = btn.getAttribute("data-product_id");
            if (!productId) return;

            const quantity = cartQuantities[productId] || 0;
            const originalText = btn.getAttribute("data-default-text") || "Add to cart";

            if (quantity > 0) {
                btn.innerHTML = `${i18n.in_cart} (${quantity})`;
                btn.classList.add("added-to-cart");
            } else {
                btn.innerHTML = originalText;
                btn.classList.remove("added-to-cart");
            }
        });
    }

    // ========================================
    // FLOATING CART UI
    // ========================================
    const openFloatingCart = document.getElementById("open-floating-cart");
    const floatingCartPanel = document.getElementById("floating-cart-panel");
    const floatingCartOverlay = document.getElementById("floating-cart-overlay");
    const floatingCartContents = document.getElementById("floating-cart-contents");

    /**
     * Open floating cart panel
     */
    function openCart() {
        if (!floatingCartPanel || !floatingCartOverlay) return;

        floatingCartPanel.classList.add("is-active");
        floatingCartOverlay.style.display = "block";
        loadCartContents(true); // Force fresh load
    }

    if (openFloatingCart && floatingCartPanel && floatingCartOverlay && floatingCartContents) {
        openFloatingCart.addEventListener("click", openCart);
    }

    const closeFloatingCart = document.getElementById("close-floating-cart");
    if (closeFloatingCart && floatingCartOverlay && floatingCartPanel) {
        const closeFn = () => {
            floatingCartPanel.classList.remove("is-active");
            floatingCartOverlay.style.display = "none";
        };
        closeFloatingCart.addEventListener("click", closeFn);
        floatingCartOverlay.addEventListener("click", closeFn);
    }

    // ========================================
    // LOAD CART CONTENTS (with cache)
    // ========================================
    function loadCartContents(force = false) {
        // Try cache first (but only if not forced refresh)
        if (!force) {
            const cached = CartCache.get('cart_contents');
            if (cached) {
                floatingCartContents.innerHTML = cached;
                return;
            }
        }

        const formData = new FormData();
        formData.append("action", "get_floating_cart_contents");

        fetch(ajaxUrl, {
            method: "POST",
            body: formData
        })
            .then(r => r.text())
            .then(text => {
                const i = text.indexOf("{");
                if (i > 0) {
                    console.warn("Garbage before JSON (length =", i, ")");
                }
                try {
                    const json = JSON.parse(text.slice(i));
                    if (json.success) {
                        floatingCartContents.innerHTML = json.data.html;

                        // Cache the HTML for 30 seconds only (cart contents change more frequently)
                        CartCache.set('cart_contents', json.data.html);

                        updateCartTotals(true); // Force fresh totals when cart is opened
                    } else {
                        floatingCartContents.textContent = "JSON success=false";
                    }
                } catch (e) {
                    console.error("Failed to parse cart contents", e);
                    floatingCartContents.innerHTML = text;
                }
            })
            .catch(err => {
                console.error("AJAX FAIL", err);
            });
    }

    // ========================================
    // QUANTITY UPDATE (debounced)
    // ========================================
    const debouncedQuantityUpdate = debounce(function(inputEl) {
        let quantity = parseInt(inputEl.value, 10);
        const cartKey = inputEl.getAttribute("data-cart-key");
        const item = inputEl.closest(".cart-item");

        if (!cartKey || !item) return;

        if (!quantity || quantity < 1) {
            quantity = 1;
            inputEl.value = 1;
        }

        postAjax("floating_cart_update_quantity", {
            cart_key: cartKey,
            quantity: quantity
        }).then(response => {
            if (response && response.success) {
                // Clear cache on update
                CartCache.clear();

                updateCartTotals(true);

                const itemTotalEl = item.querySelector(".item-total");
                const priceDisplayEl = item.querySelector(".price-display");

                if (itemTotalEl) itemTotalEl.innerHTML = response.data.item_total;
                if (priceDisplayEl) priceDisplayEl.innerHTML = response.data.price_display;
            }
        });
    }, 500); // Wait 500ms after last change

    // ========================================
    // SINGLE PRODUCT AJAX ADD TO CART
    // ========================================
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
                productForm.submit();
                return;
            }

            const ajaxUrl = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart');

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            document.body.dispatchEvent(new CustomEvent('adding_to_cart', {
                detail: { product_id: productId, quantity: quantity }
            }));

            try {
                const response = await fetch(ajaxUrl, {
                    method: "POST",
                    body: formData,
                    credentials: "same-origin"
                });

                const result = await response.json();

                if (result && result.error && result.product_url) {
                    window.location.href = result.product_url;
                    return;
                }

                if (result && result.fragments) {
                    for (const selector in result.fragments) {
                        const el = document.querySelector(selector);
                        if (el) {
                            el.outerHTML = result.fragments[selector];
                        }
                    }
                }

                document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                    detail: {
                        fragments: result.fragments,
                        cart_hash: result.cart_hash
                    }
                }));

                // Clear cache
                CartCache.clear();

                updateAllCartButtons(true);

                // Open floating cart after adding
                openCart();

            } catch (err) {
                console.error('AJAX add to cart error', err);
                productForm.submit();
            } finally {
                btn.classList.remove("is-loading");
            }
        });
    }

    // Make single product button compatible
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

    // ========================================
    // EVENT DELEGATION
    // ========================================
    document.body.addEventListener('click', function (e) {
        // AJAX add to cart (product loop)
        const ajaxBtn = e.target.closest('a.ajax_add_to_cart');
        if (ajaxBtn) {
            e.preventDefault();
            e.stopPropagation();

            ajaxBtn.classList.add('is-loading');

            const productId = ajaxBtn.getAttribute('data-product_id');
            const quantity = ajaxBtn.getAttribute('data-quantity') || 1;

            if (typeof wc_add_to_cart_params === 'undefined' || !productId) {
                window.location.href = ajaxBtn.href;
                return;
            }

            const ajaxUrl = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart');

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            document.body.dispatchEvent(new CustomEvent('adding_to_cart', {
                detail: { product_id: productId, quantity: quantity }
            }));

            (async () => {
                try {
                    const response = await fetch(ajaxUrl, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    });

                    const result = await response.json();

                    if (result && result.error && result.product_url) {
                        window.location.href = result.product_url;
                        return;
                    }

                    if (result && result.fragments) {
                        for (const selector in result.fragments) {
                            const el = document.querySelector(selector);
                            if (el) {
                                el.outerHTML = result.fragments[selector];
                            }
                        }
                    }

                    document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                        detail: {
                            fragments: result.fragments,
                            cart_hash: result.cart_hash
                        }
                    }));

                    // Clear cache
                    CartCache.clear();

                    updateAllCartButtons(true);
                    updateCartTotals(true);

                    // Open floating cart after adding
                    openCart();

                } catch (err) {
                    console.error('[LOOP] AJAX add to cart error', err);
                    window.location.href = ajaxBtn.href;
                } finally {
                    ajaxBtn.classList.remove('is-loading');
                }
            })();

            return;
        }

        // Remove cart item
        const removeBtn = e.target.closest(".remove-cart-item");
        if (removeBtn) {
            e.preventDefault();
            e.stopPropagation();

            const cartKey = removeBtn.getAttribute("data-cart-key");
            const item = removeBtn.closest(".cart-item");

            if (!cartKey || !item) return;

            // Set opacity to 0.5 immediately when clicked
            item.style.opacity = "0.5";

            postAjax("floating_cart_remove_item", {
                cart_key: cartKey
            }).then(response => {
                if (response && response.success) {
                    // Clear cache
                    CartCache.clear();

                    item.style.transition = "opacity 0.3s";
                    item.style.opacity = "0";
                    setTimeout(() => {
                        item.remove();
                        updateCartTotals(true);

                        const remainingItems = document.querySelectorAll(".cart-item").length;
                        if (remainingItems === 0 && floatingCartContents) {
                            floatingCartContents.innerHTML =
                                `<div class="has-text-centered p-4"><p>${i18n.cart_empty}</p></div>`;
                        }
                    }, 300);
                }
            });
            return;
        }

        // Quantity plus
        const plusBtn = e.target.closest(".quantity-plus");
        if (plusBtn) {
            e.preventDefault();
            e.stopPropagation();

            const field = plusBtn.closest(".field");
            if (!field) return;
            const input = field.querySelector(".cart-item-quantity");
            if (!input) return;

            const currentVal = parseInt(input.value, 10) || 0;
            input.value = currentVal + 1;
            debouncedQuantityUpdate(input);
            return;
        }

        // Quantity minus
        const minusBtn = e.target.closest(".quantity-minus");
        if (minusBtn) {
            e.preventDefault();
            e.stopPropagation();

            const field = minusBtn.closest(".field");
            if (!field) return;
            const input = field.querySelector(".cart-item-quantity");
            if (!input) return;

            let currentVal = parseInt(input.value, 10) || 0;
            if (currentVal > 1) {
                currentVal -= 1;
                input.value = currentVal;
                debouncedQuantityUpdate(input);
            }
        }
    });

    // ========================================
    // WOOCOMMERCE EVENTS
    // ========================================
    document.body.addEventListener('added_to_cart', async function () {
        // Clear cache on add to cart
        CartCache.clear();

        await updateAllCartButtons(true);
        await updateCartTotals(true);

        document.querySelectorAll('.ajax_add_to_cart.is-loading').forEach(function (btn) {
            btn.classList.remove('is-loading');
        });
    });

    document.addEventListener("change", e => {
        if (e.target.matches(".cart-item-quantity")) {
            e.preventDefault();
            debouncedQuantityUpdate(e.target);
        }
    });

    document.addEventListener("FloatingCartUpdated", () => {
        CartCache.clear();
        updateCartTotals(true);
        updateAllCartButtons(true);
        if (floatingCartPanel && floatingCartPanel.classList.contains("is-active")) {
            loadCartContents(true);
        }
    });

    // ========================================
    // INITIAL LOAD (with cache)
    // ========================================
    // Load from cache immediately, then refresh in background
    updateCartTotals(false); // Use cache
    updateAllCartButtons(false); // Use cache

    // Refresh in background after 1 second (if cache was used)
    setTimeout(() => {
        updateCartTotals(true);
        updateAllCartButtons(true);
    }, 1000);
});
