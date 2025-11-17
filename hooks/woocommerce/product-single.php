<?php
/**
 * Theme customizations for WooCommerce single product page layout
 */


add_action('after_setup_theme', 'myshop_setup_single_product_layout');
function myshop_setup_single_product_layout() {
    // Retrait des blocs par défaut
    
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

    // Wrappers Bulma
    add_action('woocommerce_before_single_product', 'myshop_open_wrapper', 1);
    add_action('woocommerce_after_single_product', 'myshop_close_wrapper', 99);

    // Grille + résumé
    add_action('woocommerce_before_single_product_summary', 'myshop_render_two_columns', 9);

    // Accordéon des tabs
    add_action('woocommerce_after_single_product_summary', 'myshop_render_tabs_accordion', 10);

    // Upsells / Related
    add_action('woocommerce_after_single_product_summary', 'myshop_render_upsells_related', 15);

    // Photoswipe si présent
    add_action('woocommerce_after_single_product_summary', 'myshop_render_photoswipe', 99);


}

// ---- Wrappers ----
function myshop_open_wrapper(){
    echo('<section class="section">');
  
}
function myshop_close_wrapper(){
     echo('</section>');
}

// ---- Main Layout ----
function myshop_render_two_columns(){
    wc_get_template(
        'single-product/single-product-layout-bulma.php',   // $template_name (chemin relatif)
        [],                                   // $args
        '',                                   // $template_path (laisse vide)
        myshop_wc_base_path()                 // $default_path (ton dossier /inc/woocommerce/)
    );
}

// ---- Tabs accordion ----
function myshop_render_tabs_accordion(){
    wc_get_template(
        'single-product/tabs-accordion-bulma.php',
        [],
        '',
        myshop_wc_base_path()
    );
}

// ---- Upsells / Related ----
function myshop_render_upsells_related(){
    echo '<div class="mt-6">';
    woocommerce_upsell_display(4, 4);
    woocommerce_output_related_products(['posts_per_page' => 4, 'columns' => 4]);
    echo '</div>';
}

// ---- Photoswipe ----
function myshop_render_photoswipe(){
    if ( locate_template('woocommerce/single-product/photoswipe.php') ) {
        wc_get_template('single-product/photoswipe.php');
    }
}

require_once myshop_wc_base_path() . 'single-product/single-product-reviews-bulma.php';
