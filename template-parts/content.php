<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eisbulma
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php

		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				eisbulma_posted_on();
				eisbulma_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php eisbulma_post_thumbnail(); ?>

	<div class="entry-content content">
		<?php the_excerpt(); ?>
		<div class="more-link">
			<a href="<?php the_permalink(); ?>" class="button is-link"><?php esc_html_e( 'Read More', 'eisbulma' ); ?></a>
		</div><!-- .more-link -->
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php eisbulma_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
