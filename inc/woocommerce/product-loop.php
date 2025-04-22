<?php


// === Layout setup ===
add_filter('post_class', function ($classes, $class, $post_id) {

    if ('product' === get_post_type($post_id) && (is_shop() || is_product_category() || is_product_tag())) {

        $classes = array_filter($classes, fn($c) => strpos($c, 'cell-') === false);
        
        // Ajoute les classes de grille Bulma

    }

    return $classes;
}, 10, 3);

add_filter('woocommerce_product_loop_start', fn($html) => '<section class="section"><ul class="products grid is-gap-5 is-col-min-10">');
add_filter('woocommerce_product_loop_end', fn($html) => '</div></section>');
// === Card wrapper ===
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
add_action('woocommerce_before_shop_loop_item', function () {
    global $product;
    $link = get_permalink($product->get_id());

    echo '<div class="card woocommerce-loop-product__link">';
    echo '<a href="' . esc_url($link) . '" class="product-card-link">';
}, 10);

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 10);
add_action('woocommerce_after_shop_loop_item', function () {
    echo '</a>'; // close the link
    echo '</div>'; // close the card
}, 10);

// === Card image + card content ===
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', function () {
    echo '<div class="card-image">';
    echo woocommerce_get_product_thumbnail(); // includes <img>
    echo '</div>';
    echo '<div class="card-content">';
}, 10);

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', function () {
    global $product;
    $title = $product->get_name();

    echo '<h4 class="woocommerce-loop-product__title mb-0">';
    echo esc_html($title);
    echo '</h4>';
}, 10);


add_filter('woocommerce_get_price_html', function ($price) {
    return '<span class="price subtitle is-6">' . $price . '</span>';
}, 10);

// Close card-content (inside the <a>)
add_action('woocommerce_after_shop_loop_item_title', function () {
    echo '</div>'; // .card-content
}, 10);


add_action('woocommerce_after_shop_loop_item_title', function () {
    echo '<div class="product-footer mt-auto">';
    echo '</div>';
}, 11);

