<?php
require_once get_stylesheet_directory() . '/inc/ajaxsearch/class-search-query.php';
require_once get_stylesheet_directory() . '/inc/ajaxsearch/ajax-handler.php';
require_once get_stylesheet_directory() . '/inc/ajaxsearch/settings.php';

function eisbulma_ajax_search_vars() {
    //  "dummy" script just for vars
    wp_register_script('eisbulma-ajax-search-vars', false);
    wp_enqueue_script('eisbulma-ajax-search-vars');
    
    wp_localize_script(
        'eisbulma-ajax-search-vars',
        'wp_ajax_search',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_ajax_search_nonce'),
            'enable_ajax' => true,
            'loading_method' => 'pagination'
        )
    );
}
add_action('wp_enqueue_scripts', 'eisbulma_ajax_search_vars');

WP_AJAX_Search_Query::init();
WP_AJAX_Search_Ajax::init();
WP_AJAX_Search_Settings::init();
