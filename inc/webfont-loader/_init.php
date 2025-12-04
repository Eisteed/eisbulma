<?php
/**
 * Load all custom walkers.
 *
 * @package EisBulma
 * @since 1.0.0
 */


defined('ABSPATH') || exit;

require_once __DIR__ . '/wptt-webfont-loader.php';

function eisbulma_font()
{

	wp_enqueue_style(
		'Kumbh-Sans',
		wptt_get_webfont_url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap'),
		array(),
		'1.0'
	);
}
add_action('wp_enqueue_scripts', 'eisbulma_font');
