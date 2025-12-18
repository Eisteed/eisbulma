<?php
/**
 * Theme Functions and Definitions
 *
 * @package eisbulma
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ========================================
 * CORE THEME SETUP
 * ========================================
 */

// Theme setup and configuration
require get_template_directory() . '/inc/setup.php';

// Vite build tool integration
require get_template_directory() . '/inc/vite.php';

/**
 * ========================================
 * PERFORMANCE OPTIMIZATIONS
 * ========================================
 */

// Performance: Debloat, critical CSS, lazy loading, etc.
require get_template_directory() . '/inc/performance/_init.php';

/**
 * ========================================
 * THIRD-PARTY INTEGRATIONS
 * ========================================
 */

// WordPress core customizations (template tags, helpers, customizer)
require get_template_directory() . '/inc/wordpress/_init.php';

// Custom walkers (navigation, comments)
require get_template_directory() . '/inc/walkers/_init.php';

// WebFont loader (GDPR-compliant local fonts)
require get_template_directory() . '/inc/webfont-loader/_init.php';

// WooCommerce integration and customizations
require get_template_directory() . '/inc/hooks/woocommerce.php';

// WordPress hooks and filters
require get_template_directory() . '/inc/hooks/wordpress.php';

// Gutenberg block editor customizations
require get_template_directory() . '/inc/hooks/gutenberg/block-styles.php';

/**
 * ========================================
 * THEME FEATURES
 * ========================================
 */

// Floating cart functionality
require get_template_directory() . '/inc/cart/_init.php';

// AJAX search functionality
require get_template_directory() . '/inc/ajaxsearch/_init.php';