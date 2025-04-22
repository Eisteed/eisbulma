<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package eisbulma
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<?php $theme_directory = get_template_directory_uri(); ?>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<!-- Font Awesome -->
	<link rel="preload" href="<?php echo ($theme_directory); ?>/lib/fontawesome/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript>
		<link rel="stylesheet" href="<?php echo ($theme_directory); ?>/lib/fontawesome/css/all.min.css">
	</noscript>

	<!-- Animate on scroll -->
	<link rel="preload" href="<?php echo ($theme_directory); ?>/lib/aos/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript>
		<link rel="stylesheet" href="<?php echo ($theme_directory); ?>/lib/aos/aos.css">
	</noscript>

	<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
	<?php
	if (function_exists('wp_body_open')) {
		wp_body_open();
	}
	?>
	<div id="page" class="site">
		<div class="breakpointdebug"></div>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'eisbulma'); ?></a>
		<header>
			<nav class="navbar pl-5 pr-5 is-primary" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'obulma'); ?>">
				<div class="container">
					<div class="navbar-brand">
						<div class="container p-3">
							<a href="<?php echo get_home_url(); ?>" class="custom-logo-link" rel="home" aria-current="page">
								<?php the_custom_logo(); ?>
							</a>
						</div>

						<a role="button" class="navbar-burger burger" aria-expanded="false" data-target="main-menu">
							<span aria-hidden="true"></span>
							<span aria-hidden="true"></span>
							<span aria-hidden="true"></span>
						</a>

					</div>

					<div id="main-menu" class="navbar-menu">
						<div class="navbar-end">
							<?php
							wp_nav_menu(
								array(
									'theme_location'  => 'menu-1',
									'container'       => '',
									'container_class' => '',
									'container_id'    => '',
									'menu_class'      => '',
									'menu_id'         => '',
									'fallback_cb'     => 'eisbulma_primary_navigation_fallback',
									'items_wrap'      => '%3$s',
									'depth'           => 4,
									'walker'          => new BulmaWP_Navbar_Walker(),
								)
							);
							?>
						</div>
					</div>
				</div>
			</nav>
		</header>

		<main>
			<div id="content" class="site-content">
				<div class="container">
				<?php bulma_custom_breadcrumb(); ?>