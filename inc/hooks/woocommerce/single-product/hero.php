<?php
/**
 * WooCommerce Template: Single Product Gallery Layout
 *
 * Two-column layout for single product page with Bulma grid.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

global $product;
?>


<div class="columns is-variable is-6">

    <!-- Colonne gauche : galerie -->
    <div class="column is-12-mobile is-half-tablet ">
        <?php woocommerce_show_product_images(); ?>
    </div>

    <!-- Colonne droite : résumé -->
    <div class="column is-12-mobile is-half-tablet ">
        <div class="container">
            <div class="is-flex is-flex-direction-column is-gap-2">
                <?php woocommerce_template_single_title(); ?>


                <?php woocommerce_template_single_rating(); ?>


                <div class="container">
                    <?php if ($product && $product->is_on_sale()) : ?>
                        <span class="tag is-medium is-primary sale-badge mb-2">
                            <?php echo esc_html__('Sale', 'woocommerce'); ?>
                        </span>
                    <?php endif; ?>

                    <span class="tag is-primary is-light is-medium">
                        <?php woocommerce_template_single_price(); ?>
                    </span>
                </div>



                <?php 
                if (wc_get_product()->get_short_description()) {
                    echo '<div class="box">';
                    woocommerce_template_single_excerpt();
                    echo '</div>';
                } 
                ?>


                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>

            <div class="is-size-7 has-text-grey mt-3">
                <?php woocommerce_template_single_meta(); ?>
            </div>
        </div>
    </div>

</div>

