<?php

require_once get_template_directory() .  '/inc/ajaxsearch/class-search-query.php';
require_once get_template_directory() .  '/inc/ajaxsearch/ajax-handler.php';
require_once get_template_directory() . '/inc/ajaxsearch/settings.php';



function eisbulma_ajax_search_vars() {
    $enable_ajax = get_option('wp_ajax_search_enable_ajax', true);
    $loading_method = get_option('wp_ajax_search_loading_method', 'pagination');

    wp_localize_script(
        'eis-main',
        'wp_ajax_search',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_ajax_search_nonce'),
            'enable_ajax' => $enable_ajax,
            'loading_method' => $loading_method,
        )
    );
}
add_action('wp_enqueue_scripts', 'eisbulma_ajax_search_vars');

WP_AJAX_Search_Query::init();
WP_AJAX_Search_Ajax::init();
WP_AJAX_Search_Settings::init();
