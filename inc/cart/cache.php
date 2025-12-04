<?php
/**
 * Cart Caching Layer
 *
 * Implements transient-based caching for cart data to reduce database queries
 * and improve performance on repeat visits.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class EisBulma_Cart_Cache
{
	/**
	 * Cache duration in seconds (5 minutes)
	 */
	const CACHE_DURATION = 300;

	/**
	 * Get cart data with caching
	 *
	 * @return array Cart data (quantities, count, total)
	 */
	public static function get_cart_data()
	{
		if (!function_exists('WC') || !WC()->cart) {
			return self::get_empty_cart_data();
		}

		// Try to get from cache first
		$cache_key = self::get_cache_key();
		$cached = get_transient($cache_key);

		if ($cached !== false) {
			return $cached;
		}

		// Generate cart data
		$cart_data = self::generate_cart_data();

		// Cache for 5 minutes
		set_transient($cache_key, $cart_data, self::CACHE_DURATION);

		return $cart_data;
	}

	/**
	 * Generate cart data from WooCommerce cart
	 *
	 * @return array
	 */
	private static function generate_cart_data()
	{
		$quantities = [];
		$cart = WC()->cart->get_cart();

		foreach ($cart as $cart_item) {
			$product_id = $cart_item['product_id'];
			$quantities[$product_id] = ($quantities[$product_id] ?? 0) + $cart_item['quantity'];
		}

		return [
			'quantities' => $quantities,
			'count' => WC()->cart->get_cart_contents_count(),
			'total' => html_entity_decode(strip_tags(WC()->cart->get_cart_total()), ENT_QUOTES, 'UTF-8'),
			'timestamp' => time()
		];
	}

	/**
	 * Get empty cart data structure
	 *
	 * @return array
	 */
	private static function get_empty_cart_data()
	{
		return [
			'quantities' => [],
			'count' => 0,
			'total' => wc_price(0),
			'timestamp' => time()
		];
	}

	/**
	 * Get cache key for current user/session
	 *
	 * @return string
	 */
	private static function get_cache_key()
	{
		$user_id = get_current_user_id();
		$session_key = WC()->session ? WC()->session->get_customer_id() : 'guest';

		return sprintf('eisbulma_cart_%s_%s', $user_id, md5($session_key));
	}

	/**
	 * Clear cart cache
	 */
	public static function clear_cache()
	{
		$cache_key = self::get_cache_key();
		delete_transient($cache_key);
	}

	/**
	 * Clear cache on cart change
	 */
	public static function init_cache_clearing()
	{
		// Clear cache when cart is updated
		add_action('woocommerce_cart_item_removed', [__CLASS__, 'clear_cache']);
		add_action('woocommerce_cart_item_restored', [__CLASS__, 'clear_cache']);
		add_action('woocommerce_after_cart_item_quantity_update', [__CLASS__, 'clear_cache']);
		add_action('woocommerce_add_to_cart', [__CLASS__, 'clear_cache']);
		add_action('woocommerce_cart_emptied', [__CLASS__, 'clear_cache']);
	}
}

// Initialize cache clearing hooks
EisBulma_Cart_Cache::init_cache_clearing();
