<?php
/**
 * WooCommerce Hook: Login/Register Form Styling
 *
 * Adds Bulma styling to login and registration forms using hooks only.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;


/**
 * Wrap individual login form with column
 */
add_action('woocommerce_login_form_start', function() {
    $registration_enabled = 'yes' === get_option('woocommerce_enable_myaccount_registration');


    echo '<h2 class="title is-4">' . esc_html__('Login', 'woocommerce') . '</h2>
            <div class="box-wrapper">';
}, 1);

add_action('woocommerce_login_form_end', function() {
    echo '</div>';
}, 999);

/**
 * Wrap register form with column
 */
add_action('woocommerce_register_form_start', function() {
    echo '
            <h2 class="title is-4">' . esc_html__('Register', 'woocommerce') . '</h2>
            <div class="box-wrapper">';
}, 1);

add_action('woocommerce_register_form_end', function() {
    echo '</div>';
}, 999);

/**
 * Add Bulma classes to login/register form fields
 */
add_filter('woocommerce_form_field_args', function($args, $key, $value) {
    // Only apply on login/register pages
    if (!is_account_page() || is_user_logged_in()) {
        return $args;
    }

    // Add Bulma field wrapper class
    if (isset($args['class']) && is_array($args['class'])) {
        $args['class'][] = 'field';
    } else {
        $args['class'] = ['field'];
    }

    // Add Bulma input classes
    if (isset($args['input_class'])) {
        if (is_array($args['input_class'])) {
            $args['input_class'][] = 'input';
        } else {
            $args['input_class'] .= ' input';
        }
    } else {
        $args['input_class'] = ['input'];
    }

    // Add Bulma label class
    if (isset($args['label_class'])) {
        if (is_array($args['label_class'])) {
            $args['label_class'][] = 'label';
        } else {
            $args['label_class'] .= ' label';
        }
    } else {
        $args['label_class'] = ['label'];
    }

    return $args;
}, 10, 3);

/**
 * Note: Bulma class injection for login/register forms is handled by
 * src/js/styles/class-inject.js which automatically applies classes and structure
 * via MutationObserver for better performance and maintainability.
 */
