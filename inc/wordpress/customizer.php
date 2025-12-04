<?php

/**
 * Theme Customizer
 *
 * @package eisbulma
 */

/**
 * Setup theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function eisbulma_customize_register($wp_customize)
{
	$wp_customize->get_setting('blogname')->transport        = 'refresh';
	$wp_customize->get_setting('blogdescription')->transport = 'refresh';

	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->get_setting('blogname')->transport        = 'postMessage';
		$wp_customize->get_setting('blogdescription')->transport = 'postMessage';

		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'eisbulma_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'eisbulma_customize_partial_blogdescription',
			)
		);
	}

	eisbulma_social($wp_customize);
	eisbulma_contact($wp_customize);
	hide_sections_customizer($wp_customize);
}

add_action('customize_register', 'eisbulma_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function eisbulma_customize_partial_blogname()
{
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function eisbulma_customize_partial_blogdescription()
{
	bloginfo('description');
}

function eisbulma_social($wp_customize)
{
	// Section Social Links
	$wp_customize->add_section('eisbulma_social_links', array(
		'title'    => __('Social Links', 'eisbulma'),
		'priority' => 30,
	));

	// Champs
	$socials = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'tiktok'    => 'TikTok',
		'x'         => 'X (Twitter)',
		'youtube'   => 'Youtube',
		'pinterest' => 'Pinterest',
		'linkedin'  => 'LinkedIn',
		'whatsapp'  => 'WhatsApp',
	);

	foreach ($socials as $id => $label) {

		$wp_customize->add_setting("eisbulma_{$id}_link", array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		));

		$wp_customize->add_control("eisbulma_{$id}_link", array(
			'label'   => __($label, 'eisbulma'),
			'section' => 'eisbulma_social_links',
			'type'    => 'url',
		));
	}
}
function eisbulma_contact($wp_customize)
{

	// Section
	$wp_customize->add_section('eisbulma_contact_section', array(
		'title'       => __('Contact', 'eisbulma'),
		'priority'    => 30,
		'description' => __('Contact information', 'eisbulma'),
	));

	// Téléphone
	$wp_customize->add_setting('eisbulma_contact_phone', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('eisbulma_contact_phone', array(
		'label'   => __('Phone', 'eisbulma'),
		'section' => 'eisbulma_contact_section',
		'type'    => 'text',
	));

	// Email
	$wp_customize->add_setting('eisbulma_contact_email', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_email',
	));

	$wp_customize->add_control('eisbulma_contact_email', array(
		'label'   => __('Email', 'eisbulma'),
		'section' => 'eisbulma_contact_section',
		'type'    => 'text',
	));

	// Adresse postale
	$wp_customize->add_setting('eisbulma_contact_address', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	));

	$wp_customize->add_control('eisbulma_contact_address', array(
		'label'   => __('Address', 'eisbulma'),
		'section' => 'eisbulma_contact_section',
		'type'    => 'textarea',
	));
}

function hide_sections_customizer($wp_customize)
{
	// Masquer l'onglet Couleurs
	$wp_customize->remove_section('colors');

	// Masquer l'onglet Image d'entête
	$wp_customize->remove_section('header_image');

	// Masquer l'onglet Image d'arrière-plan
	$wp_customize->remove_section('background_image');
}
