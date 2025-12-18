<?php
/**
 * WooCommerce Hooks Loader
 *
 * Loads all WooCommerce customizations organized by feature area.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// ============================================
// Configuration
// ============================================
require_once __DIR__ . '/woocommerce/config/disable-styles.php';
require_once __DIR__ . '/woocommerce/config/registration.php';
require_once __DIR__ . '/woocommerce/config/template-path.php';

// ============================================
// UI Components
// ============================================
require_once __DIR__ . '/woocommerce/ui/breadcrumbs.php';
require_once __DIR__ . '/woocommerce/ui/form-fields.php';
require_once __DIR__ . '/woocommerce/ui/notifications.php';
require_once __DIR__ . '/woocommerce/ui/pagination.php';
require_once __DIR__ . '/woocommerce/ui/sale-badge.php';

// ============================================
// Shop Loop (Product Archive)
// ============================================
require_once __DIR__ . '/woocommerce/shop-loop/layout.php';
require_once __DIR__ . '/woocommerce/shop-loop/ajax-filters.php';

// ============================================
// Single Product
// ============================================
require_once __DIR__ . '/woocommerce/single-product/layout.php';
require_once __DIR__ . '/woocommerce/single-product/filters.php';
require_once __DIR__ . '/woocommerce/single-product/quantity-buttons.php';
require_once __DIR__ . '/woocommerce/single-product/reviews.php';

// ============================================
// Pages
// ============================================
require_once __DIR__ . '/woocommerce/pages/my-account.php';
require_once __DIR__ . '/woocommerce/pages/login-form.php';
