<?php
/**
 * Helper functions
 *
 * @package eisbulma
 */

/**
 * Numeric pagination.
 *
 * @since 1.0.0
 */
function eisbulma_pagination() {
	global $wp_query;

	if ( is_singular() ) {
		return;
	}

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	$links = array();

	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 1;
	}

	echo '<nav class="pagination" role="navigation" aria-label="pagination">';
	echo '<ul class="pagination-list">';

	// Previous button
	if ( get_previous_posts_link() ) {
		printf( '<li>%s</li>', wp_kses_post( get_previous_posts_link( '<a class="pagination-previous" aria-label="Previous page">' . esc_html__( 'Previous', 'eisbulma' ) . '</a>' ) ) );
	}

	if ( ! in_array( 1, $links, true ) ) {
		$class        = 1 === $paged ? ' is-current' : '';
		$aria_current = 1 === $paged ? ' aria-current="page"' : '';
		printf( '<li><a href="%s" class="pagination-link%s"%s>%s</a></li>', esc_url( get_pagenum_link( 1 ) ), $class, $aria_current, '1' );

		if ( ! in_array( 2, $links, true ) ) {
			echo '<li><span class="pagination-ellipsis">&hellip;</span></li>' . "\n";
		}
	}

	sort( $links );

	foreach ( (array) $links as $link ) {
		$class        = $paged === $link ? ' is-current' : '';
		$aria_current = $paged === $link ? ' aria-current="page"' : '';
		printf( '<li><a href="%s" class="pagination-link%s"%s>%s</a></li>', esc_url( get_pagenum_link( $link ) ), $class, $aria_current, $link );
	}

	if ( ! in_array( $max, $links, true ) ) {
		if ( ! in_array( $max - 1, $links, true ) ) {
			echo '<li><span class="pagination-ellipsis">&hellip;</span></li>' . "\n";
		}

		$class        = $paged === $max ? ' is-current' : '';
		$aria_current = $paged === $max ? ' aria-current="page"' : '';
		printf( '<li><a href="%s" class="pagination-link%s"%s>%s</a></li>', esc_url( get_pagenum_link( $max ) ), $class, $aria_current, $max );
	}

	// Next button
	$next_link = get_next_posts_link( esc_html__( 'Next', 'eisbulma' ) );
	if ( $next_link ) {
		printf( '<li><a class="pagination-next" aria-label="Next page" href="%s">%s</a></li>', esc_url( get_pagenum_link( $paged + 1 ) ), esc_html__( 'Next', 'eisbulma' ) );
	}

	echo '</ul>';
	echo '</nav>';
}

/**
 * Fallback for primary navigation.
 *
 * @since 1.0.0
 */
function eisbulma_primary_navigation_fallback() {
	echo '<div class="navbar-menu">';
	echo '<a class="navbar-item" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'eisbulma' ) . '</a>';

	$qargs = array(
		'posts_per_page' => 4,
		'post_type'      => 'page',
		'orderby'        => 'name',
		'order'          => 'ASC',
	);

	$the_query = new WP_Query( $qargs );

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			the_title( '<a class="navbar-item" href="' . esc_url( get_permalink() ) . '">', '</a>' );
		}

		wp_reset_postdata();
	}

	echo '</div>';
}


// Add delete to comment
if ( ! function_exists( 'eisbulma_get_delete_comment_link' ) ) {
  function eisbulma_get_delete_comment_link( $comment_id = 0 ) {
    $comment = get_comment( $comment_id );
    if ( ! $comment ) {
      return '';
    }

    // URL d’admin pour mettre le commentaire à la corbeille
    $url = admin_url( "comment.php?action=trash&c={$comment->comment_ID}" );
    $url = wp_nonce_url( $url, "trash-comment_{$comment->comment_ID}" );

    return $url;
  }
}


add_filter('get_custom_logo', function ($html) {
	// retire width="…" et height="…" de la balise <img>
	return preg_replace('/(width|height)=["\']\d*["\']\s?/', '', $html);
});