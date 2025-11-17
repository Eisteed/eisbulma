<?php
add_filter('woocommerce_pagination_args', 'eisbulma_bulma_pagination_args');
function eisbulma_bulma_pagination_args($args)
{
    $args['prev_text'] = '←';
    $args['next_text'] = '→';
    // Remove this line or change to 'array' - this was causing the issue
    // $args['type'] = 'list'; 
    return $args;
}

remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action('woocommerce_after_shop_loop', 'eisbulma_bulma_pagination', 10);

function eisbulma_bulma_pagination()
{
    // Get the global WP_Query object
    global $wp_query;

    // Alternative way to get total pages if wc_get_loop_prop doesn't work
    $total_pages = wc_get_loop_prop('total_pages');
    if (! $total_pages) {
        $total_pages = $wp_query->max_num_pages;
    }

    $args = apply_filters(
        'woocommerce_pagination_args',
        array(
            'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'       => '',
            'current'      => max(1, get_query_var('paged')),
            'total'        => $total_pages,
            'prev_text'    => '←',
            'next_text'    => '→',
            'type'         => 'array',
            'end_size'     => 1,
            'mid_size'     => 1,
        )
    );

    // Debug: Let's see what values we're getting
    error_log('Total pages: ' . $args['total']);
    error_log('Current page: ' . $args['current']);
    error_log('WP Query max_num_pages: ' . $wp_query->max_num_pages);
    error_log('WC Loop total_pages: ' . wc_get_loop_prop('total_pages'));

    if ($args['total'] <= 1) {
        error_log('Pagination not shown - total pages: ' . $args['total']);
        return;
    }

    $links = paginate_links($args);

    // Debug: Let's see what paginate_links returns
    error_log('Links type: ' . gettype($links));
    error_log('Links content: ' . print_r($links, true));

    // Fix: Check if $links is an array and not empty
    if (! empty($links) && is_array($links)) {
        error_log('Pagination HTML will be output');
        echo '<nav class="pagination is-centered" role="navigation" aria-label="pagination">';


        // Page numbers
        echo '<ul class="pagination-list">';
        foreach ($links as $link) {
            if (strpos($link, 'current') !== false) {
                echo '<li>' . str_replace('page-numbers current', 'pagination-link is-current', $link) . '</li>';
            } else {
                echo '<li>' . str_replace('page-numbers', 'pagination-link', $link) . '</li>';
            }
        }
        echo '</ul>';
        echo '</nav>';
    }
}
