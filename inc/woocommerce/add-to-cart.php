<?php

add_filter('woocommerce_loop_add_to_cart_link', function ($html, $product, $args = []) {
    // Les classes Bulma à ajouter
    $additional_classes = 'button is-fullwidth';

    // Ajoute les classes à l'attribut "class" sans remplacer ce qu’il y a déjà
    $html = preg_replace_callback(
        '/class="([^"]*)"/',
        function ($matches) use ($additional_classes) {
            $existing_classes = $matches[1];
            return 'class="' . esc_attr(trim($existing_classes . ' ' . $additional_classes)) . '"';
        },
        $html
    );

    return $html;
}, 10, 3);