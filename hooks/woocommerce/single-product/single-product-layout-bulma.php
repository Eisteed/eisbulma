<?php
// woocommerce/single-product/layout-bulma.php
defined('ABSPATH') || exit;
global $product; ?>


    <div class="columns is-variable is-6">

        <!-- Colonne gauche : galerie -->
        <div class="column is-12-mobile is-half-tablet ">
            <?php woocommerce_show_product_images(); ?>
        </div>

        <!-- Colonne droite : résumé -->
        <div class="column is-12-mobile is-half-tablet">
            <div class="container">
                <?php woocommerce_template_single_title(); ?>

                <div class="container p-1">
                    <?php woocommerce_template_single_rating(); ?>
                </div>

                <div class="container p-1">
                    <?php if ($product && $product->is_on_sale()) : ?>
                        <span class="tag is-medium is-primary sale-badge mb-2">
                            <?php echo esc_html__('Sale', 'woocommerce'); ?>
                        </span>
                    <?php endif; ?>
                    <br/>
                    <span class="tag is-primary is-light is-medium">
                        <?php woocommerce_template_single_price(); ?>
                    </span>
                </div>

                <div class="content mb-4">
                    <?php woocommerce_template_single_excerpt(); ?>
                </div>

                <div class="box mb-5">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>

                <div class="is-size-7 has-text-grey mb-3">
                    <?php woocommerce_template_single_meta(); ?>
                </div>

                <div class="mt-2">
                    <?php woocommerce_template_single_sharing(); ?>
                </div>
            </div>
        </div>

    </div>

<div class="is-clearfix mb-5"></div>