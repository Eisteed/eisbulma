<?php
/**
 * Performance Optimization Functions
 *
 * Improves theme performance with lazy loading, resource hints, and optimizations.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Add preconnect and DNS prefetch hints for external resources
 */
function eisbulma_resource_hints($urls, $relation_type)
{
	// Only add hints on frontend
	if (is_admin()) {
		return $urls;
	}

	switch ($relation_type) {
		case 'preconnect':
			// Preconnect to critical third-party origins
			// Add your CDN or external font/API domains here
			// Example: $urls[] = 'https://fonts.googleapis.com';
			break;

		case 'dns-prefetch':
			// DNS prefetch for less critical resources
			if (class_exists('WooCommerce')) {
				// Add WooCommerce-related domains if needed
			}
			break;
	}

	return $urls;
}
add_filter('wp_resource_hints', 'eisbulma_resource_hints', 10, 2);

/**
 * Add native lazy loading to images
 */
function eisbulma_add_lazy_loading($attr, $attachment, $size)
{
	// Skip if in admin or if already has loading attribute
	if (is_admin() || isset($attr['loading'])) {
		return $attr;
	}

	// Add native lazy loading
	$attr['loading'] = 'lazy';

	// Add decoding="async" for better performance
	$attr['decoding'] = 'async';

	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'eisbulma_add_lazy_loading', 10, 3);

/**
 * Add lazy loading to content images
 */
function eisbulma_content_images_lazy_loading($content)
{
	// Skip if in admin or feed
	if (is_admin() || is_feed()) {
		return $content;
	}

	// Add loading="lazy" and decoding="async" to img tags
	$content = preg_replace(
		'/<img(?![^>]*\bloading\s*=)([^>]*)>/i',
		'<img loading="lazy" decoding="async"$1>',
		$content
	);

	return $content;
}
add_filter('the_content', 'eisbulma_content_images_lazy_loading', 20);
add_filter('post_thumbnail_html', 'eisbulma_content_images_lazy_loading', 20);
add_filter('get_avatar', 'eisbulma_content_images_lazy_loading', 20);

/**
 * Add fetchpriority="high" to hero/featured images
 */
function eisbulma_priority_hints($attr, $attachment, $size)
{
	// Skip if in admin
	if (is_admin()) {
		return $attr;
	}

	// Add high priority to featured images on single posts/products
	if ((is_single() || is_singular('product')) && has_post_thumbnail()) {
		$thumbnail_id = get_post_thumbnail_id();
		if ($attachment->ID === $thumbnail_id) {
			$attr['fetchpriority'] = 'high';
			// Remove lazy loading from LCP image
			unset($attr['loading']);
		}
	}

	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'eisbulma_priority_hints', 15, 3);

/**
 * Preload critical assets
 */
function eisbulma_preload_assets()
{
	// Skip in admin
	if (is_admin()) {
		return;
	}

	// Preload the logo if it exists
	$custom_logo_id = get_theme_mod('custom_logo');
	if ($custom_logo_id) {
		$logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
		if ($logo_url) {
			echo '<link rel="preload" as="image" href="' . esc_url($logo_url) . '" fetchpriority="high">' . "\n";
		}
	}

	// Preload featured image on single posts/products (LCP optimization)
	if ((is_single() || is_singular('product')) && has_post_thumbnail()) {
		$thumbnail_url = get_the_post_thumbnail_url(null, 'large');
		if ($thumbnail_url) {
			echo '<link rel="preload" as="image" href="' . esc_url($thumbnail_url) . '" fetchpriority="high">' . "\n";
		}
	}
}
add_action('wp_head', 'eisbulma_preload_assets', 1);

/**
 * Add responsive image sizes
 */
function eisbulma_custom_image_sizes()
{
	// Add custom image sizes for better responsive images
	add_image_size('hero-large', 1920, 1080, false);
	add_image_size('hero-medium', 1200, 675, false);
	add_image_size('hero-small', 768, 432, false);
}
add_action('after_setup_theme', 'eisbulma_custom_image_sizes');

/**
 * Enable responsive images in content
 */
function eisbulma_responsive_images($attr, $attachment, $size)
{
	// Skip if in admin
	if (is_admin()) {
		return $attr;
	}

	// WordPress handles srcset automatically, just ensure it's not disabled
	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'eisbulma_responsive_images', 10, 3);

/**
 * Add modern image format support hints
 */
function eisbulma_image_format_hints()
{
	// Future: Add AVIF/WebP support detection and hints
	// This is a placeholder for when you want to implement next-gen formats
}
