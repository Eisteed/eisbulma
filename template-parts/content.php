<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eisbulma
 */

?>
<div class="container m-5 pt-5 pb-5">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<?php eisbulma_post_thumbnail(); ?>
	<header class="entry-header">
		<?php

		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="is-flex is-gap-1">
				<?php
				eisbulma_posted_on();
				//eisbulma_posted_by();
				eisbulma_entry_footer();
				?>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content content">
		<?php the_excerpt(); ?>
		<div class="more-link">
			<a href="<?php the_permalink(); ?>" class="button is-link"><?php esc_html_e( 'Read More', 'eisbulma' ); ?></a>
		</div><!-- .more-link -->
	</div><!-- .entry-content -->

<!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
</div>