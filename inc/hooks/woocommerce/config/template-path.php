<?php
/**
 * WooCommerce Config: Template Path Helper
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

if (!function_exists('myshop_wc_base_path')) {
    /**
     * Get the base path for WooCommerce template overrides
     *
     * @return string Base path with trailing slash
     */
    function myshop_wc_base_path()
    {
        return trailingslashit(get_template_directory()) . 'inc/hooks/woocommerce/';
    }
}
