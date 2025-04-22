<?php

add_filter( 'woocommerce_pagination_args', 'eisbulma_bulma_pagination_args' );
function eisbulma_bulma_pagination_args( $args ) {
    $args['prev_text'] = '←';
    $args['next_text'] = '→';
    $args['type'] = 'list'; // Output as <ul>

    return $args;
}

remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'eisbulma_bulma_pagination', 10 );

function eisbulma_bulma_pagination() {
    $args = apply_filters(
        'woocommerce_pagination_args',
        array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'       => '',
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'total'        => wc_get_loop_prop( 'total_pages' ),
            'prev_text'    => '←',
            'next_text'    => '→',
            'type'         => 'array',
            'end_size'     => 1,
            'mid_size'     => 1,
        )
    );

    if ( $args['total'] <= 1 ) {
        return;
    }

    $links = paginate_links( $args );

    if ( ! empty( $links ) ) {
        echo '<nav class="pagination is-centered" role="navigation" aria-label="pagination">';

        // Previous
        if ( $args['current'] > 1 ) {
            echo '<a class="pagination-previous" href="' . esc_url( get_pagenum_link( $args['current'] - 1 ) ) . '">←</a>';
        } else {
            echo '<a class="pagination-previous" disabled>←</a>';
        }

        // Next
        if ( $args['current'] < $args['total'] ) {
            echo '<a class="pagination-next" href="' . esc_url( get_pagenum_link( $args['current'] + 1 ) ) . '">→</a>';
        } else {
            echo '<a class="pagination-next" disabled>→</a>';
        }

        // Page numbers
        echo '<ul class="pagination-list">';
        foreach ( $links as $link ) {
            if ( strpos( $link, 'current' ) !== false ) {
                echo '<li>' . str_replace( 'page-numbers current', 'pagination-link is-current', $link ) . '</li>';
            } else {
                echo '<li>' . str_replace( 'page-numbers', 'pagination-link', $link ) . '</li>';
            }
        }
        echo '</ul>';
        echo '</nav>';
    }
}
