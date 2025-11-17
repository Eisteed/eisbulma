<?php

function eisbulma_font()
{

	require_once get_theme_file_path('lib/webfont-loader/wptt-webfont-loader.php');
	wp_enqueue_style(
		'Poppins',
		wptt_get_webfont_url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap'),
		array(),
		'1.0'
	);
}
add_action('wp_enqueue_scripts', 'eisbulma_font');

