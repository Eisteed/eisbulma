<?php
/**
 * Performance Optimizations Loader
 *
 * Loads all performance-related optimizations in the correct order.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Load performance optimization modules
 */

// 1. Critical CSS - Load first (priority 1 in wp_head)
require_once __DIR__ . '/critical-css.php';

// 2. General optimizations - Images, lazy loading, resource hints
require_once __DIR__ . '/optimization.php';

// 3. Debloat - Remove unnecessary WordPress assets
require_once __DIR__ . '/debloat.php';
