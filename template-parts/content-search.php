<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eisbulma
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="cell box">
 		<?php eisbulma_post_thumbnail(); ?>
			<?php the_title(sprintf('<h3 class="title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>'); ?>

		<div class="entry-summary content">
			<?php echo wp_trim_words(get_the_excerpt(), 20); ?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer">
			<?php eisbulma_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->