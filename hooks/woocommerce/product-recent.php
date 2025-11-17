<?php 
$recent_products = wc_get_products([
    'limit' => 4,
    'orderby' => 'date',
    'order' => 'DESC',
]);

foreach ( $recent_products as $product ) {
    setup_postdata( $product->get_id() );
    wc_get_template_part( 'content', 'product' );
}
wp_reset_postdata();