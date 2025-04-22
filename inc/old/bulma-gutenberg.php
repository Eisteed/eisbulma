<?php
function lr_theme_features() {

    // Add support for block styles.
    add_theme_support( 'wp-block-styles');
 
    // Enqueue editor styles.
    add_editor_style( get_stylesheet_directory() . 'styles/gutenberg.min.css' );
 
 }
 
 add_action('after_setup_theme', 'lr_theme_features');

// Remove block content styles while preserving editor interface
function disable_gutenberg_content_styles() {


    // Filter editor settings to disable default styles
    add_filter('block_editor_settings_all', function($editor_settings) {
        // Remove default editor styles while keeping editor functional
        $editor_settings['styles'] = [];
        
        // Disable custom color options
        $editor_settings['disableCustomColors'] = true;
        $editor_settings['disableCustomGradients'] = true;
        $editor_settings['disableCustomFontSizes'] = true;
        
        return $editor_settings;
    });
}
add_action('admin_init', 'disable_gutenberg_content_styles');

// Remove editor style support
function remove_editor_style_support() {
    remove_theme_support('editor-styles');
}
add_action('after_setup_theme', 'remove_editor_style_support', 999);