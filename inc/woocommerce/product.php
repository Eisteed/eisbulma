<?php 
add_action('woocommerce_before_single_product_summary', function () {
    echo '<div class="columns">';
    echo '<div class="column is-half">';
}, 9); 

add_action('woocommerce_before_single_product_summary', function () {
    echo '</div><div class="column is-half">';
}, 21); 

add_action('woocommerce_after_single_product_summary', function () {
    echo '</div></div>'; 
}, 10); 

