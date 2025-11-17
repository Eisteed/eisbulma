<?php
// woocommerce/single-product/tabs-accordion-bulma.php
defined('ABSPATH') || exit;

/**
 * Accordéon sans JS via <details>/<summary>.
 * – “Description” en premier et ouverte par défaut.
 */
$tabs = apply_filters( 'woocommerce_product_tabs', [] );
if ( empty( $tabs ) ) return;

// Mettre Description en premier si présent
if ( isset( $tabs['description'] ) ) {
    $first = ['description' => $tabs['description']];
    unset( $tabs['description'] );
    $tabs = $first + $tabs;
}

echo '<div class="product-accordion">';

$i = 0;
foreach ( $tabs as $key => $tab ) {
    $label = isset($tab['title']) ? $tab['title'] : ucfirst($key);
    $open  = ($i === 0 && $key === 'description') ? ' open' : '';

    echo '<details class="accordion-item mb-4"'.$open.'>';
        echo '<summary class="button is-fullwidth is-justify-content-space-between" style="width:95%">';
            echo '<span>'. wp_kses_post($label) .'</span>';
            echo '<span class="icon" aria-hidden="true">▾</span>';
        echo '</summary>';

        echo '<div class="accordion-content content">';
            if ( isset($tab['callback']) && is_callable($tab['callback']) ) {
                call_user_func( $tab['callback'], $key, $tab );
            }
        echo '</div>';
    echo '</details>';

    $i++;
}
echo '</div>';