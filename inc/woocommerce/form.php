<?php // Remplace classes des <input> type text, number, email, etc.
add_filter('woocommerce_form_field_args', function ($args, $key, $value) {
    if (isset($args['input_class']) && is_array($args['input_class'])) {
        $args['input_class'][] = 'input';
    }

    if (isset($args['class']) && is_array($args['class']) && $args['type'] === 'select') {
        $args['class'][] = 'select';
    }

    return $args;
}, 10, 3);