<?php

/**
 * Functions and definitions
 *
 * @package eisbulma
 */

if (! defined('eisbulma_VERSION')) {
	define('eisbulma_VERSION', '1.0.0');
}

if (! function_exists('eisbulma_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function eisbulma_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on eisbulma, use a find and replace
		 * to change 'eisbulma' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('eisbulma', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__('Primary', 'eisbulma'),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'eisbulma_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		add_theme_support(
			'custom-header',
			apply_filters(
				'eisbulma_custom_header_args',
				array(
					'default-image'      => '',
					'default-text-color' => '000000',
					'width'              => 1900,
					'height'             => 450,
					'flex-height'        => true,
					'header-text'        => false,
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Load default block styles.
		//add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support('responsive-embeds');



	}

endif;

add_action('after_setup_theme', 'eisbulma_setup');

/**
 * Enqueue scripts and styles.
 */
function eisbulma_scripts()
{
	$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

	// Style (JS and CSS)
	wp_enqueue_style('eisbulma-style', get_stylesheet_uri(), array(), eisbulma_VERSION);
	wp_enqueue_style('eisbulma-theme', get_template_directory_uri() . '/styles/theme' . $min . '.css', array('eisbulma-style'), eisbulma_VERSION);
	wp_enqueue_script('eisbulma-bulma', get_template_directory_uri() . '/js/bulma.js', array('jquery'), eisbulma_VERSION, true);
	wp_enqueue_script('eisbulma-custom', get_template_directory_uri() . '/js/custom' . $min . '.js', array('jquery'), eisbulma_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}

add_action('wp_enqueue_scripts', 'eisbulma_scripts');

function mytheme_enqueue_editor_styles()
{
	$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
	add_editor_style('eisbulma-custom', get_template_directory_uri() . '/styles/theme' . $min . '.css', array('eisbulma-style'), eisbulma_VERSION);
}
add_action('after_setup_theme', 'mytheme_enqueue_editor_styles');


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
 * Load WooCommerce Mods
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Load Breadcrumb
 */

require get_template_directory() . '/inc/breadcrumb.php';


/**
 * Load Block style
 */
require get_template_directory() . '/inc/bulma-blocks.php';
