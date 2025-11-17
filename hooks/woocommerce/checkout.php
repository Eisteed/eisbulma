<?php /**
 * Layout checkout en Bulma columns sans override de template
 */
add_action( 'woocommerce_before_checkout_form', function( $checkout ) {
    // wrapper global Bulma (optionnel si ton thème le gère déjà)
    echo '<section class="section"><div class="container">';
}, 5 );

add_action( 'woocommerce_after_checkout_form', function( $checkout ) {
    echo '</div></section>';
}, 50 );

/**
 * Ouverture colonnes Bulma avant les détails client
 */
add_action( 'woocommerce_checkout_before_customer_details', function() {
    // ouverture des colonnes + colonne gauche (infos client)
    echo '<div class="columns is-variable is-6">';
    echo '<div class="column is-two-thirds">';
}, 5 );

/**
 * Après les détails client : on ferme la colonne gauche
 * et on ouvre la colonne droite (récap + paiement).
 */
add_action( 'woocommerce_checkout_after_customer_details', function() {
    // fin colonne gauche
    echo '</div>';
    
    // début colonne droite (le H3 "Votre commande" + #order_review viennent dedans)
    echo '<div class="column is-one-third">';
}, 5 );

/**
 * Fermeture de la colonne droite + wrapper columns après le récap
 */
add_action( 'woocommerce_checkout_after_order_review', function() {
    echo '</div></div>'; // </div.column> + </div.columns>
}, 50 );



/**
 * Bouton Commander en Bulma
 */
add_filter( 'woocommerce_order_button_html', function( $button_html ) {
    // on remplace les classes Woo par Bulma
    $button_html = str_replace(
        array( 'class="button alt', "class='button alt" ),
        array( 'class="button is-secondary is-fullwidth', "class='button is-secondary is-fullwidth" ),
        $button_html
    );

    return $button_html;
});
