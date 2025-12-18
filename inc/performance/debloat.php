<?php

/**
 * Performance Debloat Loader
 *
 * Loads all performance optimization modules in a structured way.
 * Each module handles a specific aspect of WordPress debloating.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Load performance optimization modules
 */

// Helper functions (must be loaded first as other files depend on them)
require_once get_template_directory() . '/inc/performance/helpers.php';

// Scripts optimization
require_once get_template_directory() . '/inc/performance/scripts-debloat.php';

// Styles optimization
require_once get_template_directory() . '/inc/performance/styles-debloat.php';

// WordPress head cleanup
require_once get_template_directory() . '/inc/performance/wordpress-cleanup.php';
