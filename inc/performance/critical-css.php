<?php
/**
 * Critical CSS - Above-the-fold styles
 *
 * Inlines minimal critical CSS to improve First Contentful Paint (FCP).
 * Uses fallback system only - no build process required.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Inline critical CSS in the head
 *
 * This provides minimal above-the-fold styles to prevent layout shift
 * and ensure immediate rendering of critical UI elements.
 */
function eisbulma_inline_critical_css()
{
	// Skip in admin
	if (is_admin()) {
		return;
	}

	?>
	<style id="critical-css">
		/* Critical CSS - Above the fold styles only */

		/* Anti-FOUC for WooCommerce class injection */
		/* Hide WooCommerce elements until JavaScript adds Bulma classes */
		.woocommerce-form-login,
		.woocommerce-form-register,
		.woocommerce-notices-wrapper {
			opacity: 0;
			transition: opacity 0.2s ease-in;
		}

		/* Show elements once JavaScript is ready */
		.wc-js-ready .woocommerce-form-login,
		.wc-js-ready .woocommerce-form-register,
		.wc-js-ready .woocommerce-notices-wrapper {
			opacity: 1;
		}
	</style>
	<?php
}
add_action('wp_head', 'eisbulma_inline_critical_css', 1);
