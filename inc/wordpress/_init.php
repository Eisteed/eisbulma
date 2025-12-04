<?php
/**
 * WordPress core customizations
 *
 * Loads WordPress-specific customizations and template functions.
 *
 * @package eisbulma
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Post display functions (posted_on, posted_by, categories, thumbnail, etc.)
require_once __DIR__ . '/post-display.php';

// Helper/utility functions (pagination, navigation fallback, etc.)
require_once __DIR__ . '/helpers.php';

// Theme customizer
require_once __DIR__ . '/customizer.php';
