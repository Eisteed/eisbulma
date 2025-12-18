<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package eisbulma
 */

get_header();
?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php if (have_posts()) : ?>


			<h1 class="entry-header block">
				<?php
				/* translators: %s: search query. */
				printf(esc_html__('Search Results for: %s', 'eisbulma'), '<span>' . get_search_query() . '</span>');
				?>
			</h1>
			<!-- .page-header -->
			<div class="container is-flex is-align-items-center">
				<?php get_search_form(); ?>
			</div>
			<div class="section search-results grid is-col-min-16 is-gap-3">
				<?php
				/* Start the Loop */
				while (have_posts()) :
					the_post();
					get_template_part('template-parts/content', 'search');
				endwhile;
				?>
			</div>
		<?php
			eisbulma_pagination();
		else :
			get_template_part('template-parts/content', 'none');
		endif;
		?>

	</main><!-- #main -->
</section><!-- #primary -->

<?php

get_footer();
