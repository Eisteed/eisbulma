<? function bulma_custom_breadcrumb() {
    global $post;

    // Check: if it's a single page with NO parent, skip rendering
    if (is_page() && !$post->post_parent) {
        return; // Do not output breadcrumb
    }

    echo '<nav class="breadcrumb is-small" aria-label="breadcrumbs">';
    echo '<ul>';

    // Home link
    echo '<li><a href="' . home_url() . '">Home</a></li>';

    if ( class_exists( 'WooCommerce' ) ) {
    if (is_product_category()) {
        $current_cat = get_queried_object();
        $ancestors = get_ancestors($current_cat->term_id, 'product_cat');
        if (!empty($ancestors)) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_term($ancestor_id, 'product_cat');
                echo '<li><a href="' . get_term_link($ancestor) . '">' . $ancestor->name . '</a></li>';
            }
        }
        echo '<li class="is-active"><a href="#" aria-current="page">' . single_term_title('', false) . '</a></li>';
    }
}

    // WooCommerce Single Product
    elseif (is_singular('product')) {
        $terms = get_the_terms($post->ID, 'product_cat');
        if (!empty($terms)) {
            $term = array_shift($terms);
            $ancestors = get_ancestors($term->term_id, 'product_cat');
            if (!empty($ancestors)) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor_id) {
                    $ancestor = get_term($ancestor_id, 'product_cat');
                    echo '<li><a href="' . get_term_link($ancestor) . '">' . $ancestor->name . '</a></li>';
                }
            }
            echo '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
        }
        echo '<li class="is-active"><a href="#" aria-current="page">' . get_the_title() . '</a></li>';
    }

    // Regular Category Archive
    elseif (is_category()) {
        echo '<li><a href="#">' . single_cat_title('', false) . '</a></li>';
    }

    // Single Blog Post
    elseif (is_single() && get_post_type() === 'post') {
        $categories = get_the_category();
        if (!empty($categories)) {
            echo '<li><a href="' . get_category_link($categories[0]->term_id) . '">' . $categories[0]->name . '</a></li>';
        }
        echo '<li class="is-active"><a href="#" aria-current="page">' . get_the_title() . '</a></li>';
    }

    // Pages WITH parents
    elseif (is_page()) {
        $parent_id  = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
            $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb) echo $crumb;
        echo '<li class="is-active"><a href="#" aria-current="page">' . get_the_title() . '</a></li>';
    }

    // Search
    elseif (is_search()) {
        echo '<li class="is-active"><a href="#" aria-current="page">Search results for: ' . get_search_query() . '</a></li>';
    }

    // 404
    elseif (is_404()) {
        echo '<li class="is-active"><a href="#" aria-current="page">404 - Page Not Found</a></li>';
    }

    echo '</ul>';
    echo '</nav>';
}
