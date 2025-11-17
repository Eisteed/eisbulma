<?php

/**
 * Functions and definitions
 *
 * @package eisbulma
 */


require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/vite.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Nav Walker.
 */
require get_template_directory() . '/lib/navwalker/navwalker.php';

/**
 * Load Comment Walker.
 */
require get_template_directory() . '/lib/commentwalker/commentwalker.php';

/**
 * Load helpers.
 */
require get_template_directory() . '/inc/helpers.php';

/**
 * Load WebFont using wptt-webfont-loader
 */
require get_template_directory() . '/inc/fonts.php';

/**
 * Load Ajax Search
 */

require get_template_directory() . '/inc/ajaxsearch/main.php';

/**
 * Load WooCommerce Mods Hooks
 */
require get_template_directory() . '/hooks/woocommerce.php';

/**
 * Load Post Archive Mods Hooks
 */
require get_template_directory() . '/hooks/post-archive.php';

/**
 * Load Block style
 */
require get_template_directory() . '/hooks/gutenberg-blocks/bulma-blocks.php';


/**
 * Load debloat (remove unused native wordpress stuff)
 */
require get_template_directory() . '/inc/debloat.php';

/**
 * Load Floating Cart
 */
require get_template_directory() . '/inc/cart/cart.php';

// Hide admin bar on frontend
add_filter('show_admin_bar', '__return_false');

