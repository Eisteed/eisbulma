<?php

/**
 * Woo AJAX Filters (Bulma)
 */

// === Layout wrappers (sidebar + grid) ===
add_action('woocommerce_before_shop_loop', function () {
    if (! (is_shop() || is_product_category() || is_product_tag())) return;

    // Mobile filter toggle button
    echo '<div id="filterToggle" class="">
            <div class="is-hidden-desktop p-2 is-flex is-justify-content-center">
                <button id="woo-filter-open" class="button is-primary is-light">
                    <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M40,88H73a32,32,0,0,0,62,0h81a8,8,0,0,0,0-16H135a32,32,0,0,0-62,0H40a8,8,0,0,0,0,16Zm64-24A16,16,0,1,1,88,80,16,16,0,0,1,104,64ZM216,168H199a32,32,0,0,0-62,0H40a8,8,0,0,0,0,16h97a32,32,0,0,0,62,0h17a8,8,0,0,0,0-16Zm-48,24a16,16,0,1,1,16-16A16,16,0,0,1,168,192Z"></path></svg></span>
                    <span>' . esc_html__('Filters', 'eisbulma') . '</span>
                </button>
            </div>
          </div>';

    // Columns wrapper
    echo '
    <section class="section pt-2">
            <div class="columns">
                <aside id="woo-filters" class="column is-3-desktop is-full-mobile">
                
                    ' . woo_bulma_filters_markup() . '
                </aside>
                <div class="colum">
                    <div id="woo-products">
                        <div class="woo-products-inner">';
}, 5);

add_action('woocommerce_after_shop_loop', function () {
    if (! (is_shop() || is_product_category() || is_product_tag())) return;
    echo '          </div>
                    </div>
                </div>
            </div>
            </section>';
}, 50);


// === Filter UI Markup ===
function woo_bulma_filters_markup()
{
    // Build current state from query vars to keep UI in sync
    $current_cat   = get_query_var('product_cat');
    $min_price     = isset($_GET['min_price']) ? sanitize_text_field($_GET['min_price']) : '';
    $max_price     = isset($_GET['max_price']) ? sanitize_text_field($_GET['max_price']) : '';
    $in_stock      = isset($_GET['in_stock']) ? 'checked' : '';
    $on_sale       = isset($_GET['on_sale']) ? 'checked' : '';
    $rating        = isset($_GET['rating']) ? (int) $_GET['rating'] : 0;

    // Categories (top-level)
    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
    ]);

    ob_start();
?>
    <div class="box is-shadowless woo-filter-box">
        <div class="is-hidden-desktop is-flex is-justify-content-flex-end">
            <button id="woo-filter-close" class="delete" aria-label="close"></button>
        </div>
        <h3 class="title is-5 mb-4"><?php echo esc_html__('Filter products', 'eisbulma'); ?></h3>
        <form id="woo-filters-form">
            <?php wp_nonce_field('woo_bulma_filters', 'woo_filters_nonce'); ?>
            <div class="field">
                <!-- Order By -->
                <div class="field">
                    <label class="label"><?php echo esc_html__('Order by', 'eisbulma'); ?></label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="orderby">
                                <option value="menu_order" <?php selected($_GET['orderby'] ?? '', 'menu_order'); ?>>
                                    <?php echo esc_html__('Default', 'eisbulma'); ?>
                                </option>
                                <option value="popularity" <?php selected($_GET['orderby'] ?? '', 'popularity'); ?>>
                                    <?php echo esc_html__('Popularity', 'eisbulma'); ?>
                                </option>
                                <option value="rating" <?php selected($_GET['orderby'] ?? '', 'rating'); ?>>
                                    <?php echo esc_html__('Rating', 'eisbulma'); ?>
                                </option>
                                <option value="date" <?php selected($_GET['orderby'] ?? '', 'date'); ?>>
                                    <?php echo esc_html__('Newest', 'eisbulma'); ?>
                                </option>
                                <option value="price" <?php selected($_GET['orderby'] ?? '', 'price'); ?>>
                                    <?php echo esc_html__('Price: low to high', 'eisbulma'); ?>
                                </option>
                                <option value="price-desc" <?php selected($_GET['orderby'] ?? '', 'price-desc'); ?>>
                                    <?php echo esc_html__('Price: high to low', 'eisbulma'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="control">
                    <button type="button" id="woo-filters-reset" class="button is-light ">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256">
                                <path d="M88,104H40a8,8,0,0,1-8-8V48a8,8,0,0,1,16,0V76.69L62.63,62.06A95.43,95.43,0,0,1,130,33.94h.53a95.36,95.36,0,0,1,67.07,27.33,8,8,0,0,1-11.18,11.44,79.52,79.52,0,0,0-55.89-22.77h-.45A79.56,79.56,0,0,0,73.94,73.37L59.31,88H88a8,8,0,0,1,0,16Zm128,48H168a8,8,0,0,0,0,16h28.69l-14.63,14.63a79.56,79.56,0,0,1-56.13,23.43h-.45a79.52,79.52,0,0,1-55.89-22.77,8,8,0,1,0-11.18,11.44,95.36,95.36,0,0,0,67.07,27.33H126a95.43,95.43,0,0,0,67.36-28.12L208,179.31V208a8,8,0,0,0,16,0V160A8,8,0,0,0,216,152Z"></path>
                            </svg>
                        </span>
                        <span><?php echo esc_html__('Reset', 'eisbulma'); ?></button></span>
                </div>
            </div>
            <!-- Category -->
            <div class="field">
                <label class="label"><?php echo esc_html__('Category', 'eisbulma'); ?></label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="product_cat">
                            <option value=""><?php echo esc_html__('All categories', 'eisbulma'); ?></option>
                            <?php foreach ($terms as $t): ?>
                                <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($current_cat, $t->slug); ?>>
                                    <?php echo esc_html($t->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Price range -->
            <div class="field">
                <label class="label"><?php echo esc_html__('Price range', 'eisbulma'); ?></label>
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input" type="number" step="0.01" min="0" name="min_price" placeholder="Min" value="<?php echo esc_attr($min_price); ?>">
                    </p>
                    <p class="control is-expanded">
                        <input class="input" type="number" step="0.01" min="0" name="max_price" placeholder="Max" value="<?php echo esc_attr($max_price); ?>">
                    </p>
                </div>
            </div>

            <!-- Toggles -->
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox" name="in_stock" value="1" <?php echo $in_stock; ?>>
                    <?php echo esc_html__('In stock', 'eisbulma'); ?>
                </label>
            </div>
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox" name="on_sale" value="1" <?php echo $on_sale; ?>>
                    <?php echo esc_html__('On sale', 'eisbulma'); ?>
                </label>
            </div>

            <!-- Rating -->
            <div class="field">
                <label class="label"><?php echo esc_html__('Minimum rating', 'eisbulma'); ?></label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="rating">
                            <option value="0" <?php selected($rating, 0); ?>><?php echo esc_html__('Any', 'eisbulma'); ?></option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                                    <?php  /* translators: %d: rating number */
                                    printf(esc_html__('%d★', 'eisbulma'), $i); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>


        </form>
    </div>
<?php
    return ob_get_clean();
}

// === Enqueue JS/CSS ===
add_action('wp_enqueue_scripts', function () {
    if (! (is_shop() || is_product_category() || is_product_tag())) return;

    // jQuery seulement ici
    wp_enqueue_script('jquery');

    // Variables AJAX seulement ici (avant eis-main)
    $data = [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('woo_bulma_filters'),
    ];
    wp_add_inline_script(
        'eis-main',
        'window.WooBulmaFilters=' . wp_json_encode($data) . ';',
        'before'
    );
}, 20);

// === AJAX endpoint: build product loop HTML ===
add_action('wp_ajax_woo_bulma_filter_products', 'woo_bulma_filter_products');
add_action('wp_ajax_nopriv_woo_bulma_filter_products', 'woo_bulma_filter_products');

function woo_bulma_filter_products()
{
    check_ajax_referer('woo_bulma_filters', 'nonce');

    $paged = isset($_POST['page']) ? max(1, (int) $_POST['page']) : 1;

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'paged'          => $paged,
        'posts_per_page' => wc_get_default_products_per_row() * wc_get_default_product_rows_per_page(),
    ];
    // Order by
    if (!empty($_POST['orderby'])) {
        $orderby = sanitize_text_field($_POST['orderby']);

        switch ($orderby) {
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'date':
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
                break;

            case 'price':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'ASC';
                break;

            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            default: // menu_order
                $args['orderby'] = 'menu_order title';
                $args['order']   = 'ASC';
                break;
        }
    }
    // Tax: category
    if (!empty($_POST['product_cat'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['product_cat']),
        ];
    }

    // Price range
    $meta_query = [];
    if (isset($_POST['min_price']) && $_POST['min_price'] !== '') {
        $meta_query[] = [
            'key'     => '_price',
            'value'   => floatval($_POST['min_price']),
            'type'    => 'DECIMAL(10,2)',
            'compare' => '>=',
        ];
    }
    if (isset($_POST['max_price']) && $_POST['max_price'] !== '') {
        $meta_query[] = [
            'key'     => '_price',
            'value'   => floatval($_POST['max_price']),
            'type'    => 'DECIMAL(10,2)',
            'compare' => '<=',
        ];
    }

    // In stock
    if (!empty($_POST['in_stock'])) {
        $meta_query[] = [
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
        ];
    }

    // On sale
    if (!empty($_POST['on_sale'])) {
        $on_sale_ids = wc_get_product_ids_on_sale();
        $on_sale_ids[] = 0; // ensure non-empty
        $args['post__in'] = $on_sale_ids;
    }

    // Rating >= X
    if (!empty($_POST['rating'])) {
        $rating = max(1, min(5, (int) $_POST['rating']));
        $meta_query[] = [
            'key'     => '_wc_average_rating',
            'value'   => (float) $rating,
            'type'    => 'DECIMAL(3,2)',
            'compare' => '>=',
        ];
    }

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    // Build query
    $q = new WP_Query($args);

    ob_start();

    if ($q->have_posts()) {
        echo apply_filters('woocommerce_product_loop_start', '<div class="products grid is-gap-2 is-col-min-8">');
        while ($q->have_posts()) {
            $q->the_post();
            wc_get_template_part('content', 'product');
        }
        echo apply_filters('woocommerce_product_loop_end', '</div>');
        // Simple pagination (AJAX aware)
        $big = 999999999; // need an unlikely integer
        $pagination = paginate_links([
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, $paged),
            'total'     => $q->max_num_pages,
            'type'      => 'array',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ]);
        if (is_array($pagination)) {
            echo '<nav class="pagination is-centered" role="navigation" aria-label="pagination">';
            echo '<ul class="pagination-list">';
            foreach ($pagination as $pag) {
                echo '<li>' . $pag . '</li>';
            }
            echo '</ul></nav>';
        }
    } else {
        wc_no_products_found();
    }
    wp_reset_postdata();
    $html = ob_get_clean();
    wp_send_json_success([
        'html' => $html,
    ]);
}
