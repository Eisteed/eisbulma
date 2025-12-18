<?php

/**
 * WordPress Head Cleanup Functions
 *
 * Removes unnecessary meta tags and links from wp_head.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Remove emoji detection script
 */
function eisbulma_remove_emoji_script()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
}
add_action('init', 'eisbulma_remove_emoji_script');

/**
 * Remove WordPress version from head
 */
remove_action('wp_head', 'wp_generator');

/**
 * Remove REST API link from head
 */
remove_action('wp_head', 'rest_output_link_wp_head');

/**
 * Remove shortlink from head
 */
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Remove RSD link from head
 */
remove_action('wp_head', 'rsd_link');

/**
 * Remove Windows Live Writer manifest link
 */
remove_action('wp_head', 'wlwmanifest_link');
