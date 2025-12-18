<?php

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

		add_theme_support('automatic-feed-links');
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');

		// Déclaration des emplacements de menus
		register_nav_menus(array(
			'menu-1'      => esc_html__('Header', 'eisbulma'),
			'footer_menu' => esc_html__('Footer', 'eisbulma'),
		));

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

		add_theme_support('customize-selective-refresh-widgets');

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
		add_theme_support('wp-block-styles');

		// Add support for responsive embeds.
		add_theme_support('responsive-embeds');

		// WooCommerce support
		add_theme_support('woocommerce');
		add_theme_support('wc-product-gallery-zoom');
		add_theme_support('wc-product-gallery-lightbox');
		add_theme_support('wc-product-gallery-slider');
	}

endif;

add_action('after_setup_theme', 'eisbulma_setup');