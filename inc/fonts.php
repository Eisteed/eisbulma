<?php

function eisbulma_font()
{

	require_once get_theme_file_path('lib/webfont-loader/wptt-webfont-loader.php');
	wp_enqueue_style(
		'Inter',
		wptt_get_webfont_url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap'),
		array(),
		'1.0'
	);
}
add_action('wp_enqueue_scripts', 'eisbulma_font');
