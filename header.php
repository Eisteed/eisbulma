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
	<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
	<?php
	if (function_exists('wp_body_open')) {
		wp_body_open();
	}
	?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'eisbulma'); ?></a>
		<header>
			<nav id="navigation-top-menu" class="navbar is-fixed-top has-centered-menu " role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'obulma'); ?>" style="transform:translateY(0);">
				<div class="container">
					<div class="navbar-brand">
						<div class="navbar-item">

							<?php the_custom_logo(); ?>

						</div>

						<div class="is-flex-grow-1 is-hidden-tablet"></div>

						<div class="navbar-item search-bar pl-2 pr-2 is-hidden-tablet">
							<span class="icon is-medium">
								<a href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : wc_get_page_permalink( 'cart' ) ); ?>" class="is-flex">
									<svg height="32" width="32" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
										<path fill="#000" d="M222.14 58.87A8 8 0 0 0 216 56H54.68l-4.89-26.86A16 16 0 0 0 34.05 16H16a8 8 0 0 0 0 16h18l25.56 140.29a24 24 0 0 0 5.33 11.27a28 28 0 1 0 44.4 8.44h45.42a27.75 27.75 0 0 0-2.71 12a28 28 0 1 0 28-28H83.17a8 8 0 0 1-7.87-6.57L72.13 152h116a24 24 0 0 0 23.61-19.71l12.16-66.86a8 8 0 0 0-1.76-6.56ZM180 192a12 12 0 1 1-12 12a12 12 0 0 1 12-12Zm-96 0a12 12 0 1 1-12 12a12 12 0 0 1 12-12Z" fill="currentColor" />
									</svg>
								</a></span>
						</div>
						<div class="navbar-item search-bar pl-4 pr-4 is-hidden-tablet">
							<span class="icon is-medium">
								<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '' ); ?>" class="is-flex">
									<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256">
										<path fill="#000" d="M128 24a104 104 0 1 0 104 104A104.11 104.11 0 0 0 128 24ZM74.08 197.5a64 64 0 0 1 107.84 0a87.83 87.83 0 0 1-107.84 0ZM96 120a32 32 0 1 1 32 32a32 32 0 0 1-32-32Zm97.76 66.41a79.66 79.66 0 0 0-36.06-28.75a48 48 0 1 0-59.4 0a79.66 79.66 0 0 0-36.06 28.75a88 88 0 1 1 131.52 0Z" fill="currentColor" />
									</svg>
								</a></span>
						</div>

						<a role="button" class="navbar-burger burger" aria-expanded="false" data-target="main-menu">
							<span aria-hidden="true"></span>
							<span aria-hidden="true"></span>
							<span aria-hidden="true"></span>
							<span aria-hidden="true"></span>
						</a>

					</div>

					<div id="main-menu" class="navbar-menu is-fullheight">
						<div class="navbar-start">
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
						<div class="navbar-end">
							<div class="navbar-item">
								<?php get_search_form(); ?>
							</div>
						</div>

					</div>
				</div>
			</nav>
		</header>

		<main id="main">
			<div id="mainContainer" class="container">
				<?php 
				if ( ! ( is_product() || is_cart() || is_checkout() ||  is_account_page() || is_404() ) ) :
					get_template_part( 'template-parts/embla-carousel-header');
				endif; 
				?>