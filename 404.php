<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package eisbulma
 */

get_header();
?>

<section class="section has-text-centered">
	<h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'eisbulma'); ?></h1>
	<div class="content">
		<p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'eisbulma'); ?></p>
	</div>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button"><?php esc_html_e( 'Home', 'eisbulma' ); ?></a>

</section>

<?php
get_footer();
