<?php
/**
 * WooCommerce Hook: Form Fields
 *
 * Adds Bulma CSS classes to WooCommerce form inputs and textareas.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Add Bulma classes to form field inputs
 */
add_filter('woocommerce_form_field_args', function ($args, $key, $value) {
    // Pour les champs input
    if (isset($args['type']) && in_array($args['type'], ['text', 'email', 'tel', 'password', 'number'])) {
        if (isset($args['input_class'])) {
            if (is_array($args['input_class'])) {
                $args['input_class'][] = 'input';
            } else {
                $args['input_class'] .= ' input';
            }
        } else {
            $args['input_class'] = array('input');
        }
    }

    // Pour les champs textarea
    if (isset($args['type']) && $args['type'] === 'textarea') {
        if (isset($args['input_class'])) {
            if (is_array($args['input_class'])) {
                $args['input_class'][] = 'textarea';
            } else {
                $args['input_class'] .= ' textarea';
            }
        } else {
            $args['input_class'] = array('textarea');
        }
    }

    return $args;
}, 10, 3);

// Ajouter la classe 'input' a la quantité sur la page produit
add_filter('woocommerce_quantity_input_args', 'add_input_class_to_quantity', 10, 2);
function add_input_class_to_quantity($args, $product)
{
        $args['classes'] = 'input';

    return $args;
}

// Hook pour ajouter la classe textarea aux reviews
add_filter('comment_form_field_comment', 'add_textarea_class_to_review_comment');

function add_textarea_class_to_review_comment($field)
{
    // Remplacer la balise textarea pour ajouter la classe
    $field = str_replace('<textarea', '<textarea class="textarea"', $field);
    return $field;
}

