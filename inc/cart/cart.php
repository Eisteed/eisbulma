<?php

add_filter('woocommerce_add_to_cart_redirect', '__return_false');

require_once  'add-to-cart-quantity.php';
require_once  'add-to-cart.php';
require_once  'floating-cart.php';