<?php
/**
 * WooCommerce Hook: Product Reviews
 *
 * Removes reviews from tabs and renders them separately with Bulma styling.
 *
 * @package EisBulma
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

add_filter('woocommerce_product_tabs', function ($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}, 20);

add_action('woocommerce_after_single_product_summary', 'myshop_render_reviews_section', 15);
function myshop_render_reviews_section()
{
    global $product;
    if (!comments_open()) return;

    $comments = get_comments([
        'post_id' => $product->get_id(),
        'status'  => 'approve',
        'type'    => 'review',
    ]);

    $count = get_comments_number($product->get_id());
?>
    <section id="reviews" class="section">
        <div class="container">
            <h3 class="title is-4 mb-5">
                <?php printf(_n('%s avis', '%s avis', $count, 'woocommerce'), $count); ?>
            </h3>

            <?php if ($comments) : ?>
                <div
                    class="embla embla--4-per-view mb-5"
                    data-embla='{
          "loop": true
        }'>
                    <div class="embla__viewport">
                        <div class="embla__container">
                            <?php
                            // chaque élément rendu par my_bulma_review_walker() = .embla__slide
                            wp_list_comments([
                                'max_depth'   => 6,
                                'avatar_size' => 16,
                                'callback'    => 'my_bulma_review_walker',
                            ], $comments);
                            ?>
                        </div>
                    </div>

                </div>
            <?php else : ?>
                <p><?php echo esc_html__('Aucun avis pour le moment.', 'woocommerce'); ?></p>
            <?php endif; ?>

            <?php
            // formulaire (inchangé)
            comment_form([
                'title_reply'          => esc_html__('Ajouter un avis', 'woocommerce'),
                'class_submit'         => 'button is-link',
                'label_submit'         => esc_html__('Soumettre', 'woocommerce'),
                'comment_notes_before' => '',
                'title_reply_before'   => '<h4 class="title is-5 ">',
                'title_reply_after'    => '</h4>',
                'fields' => [
                    'author' => '<div class="field">
              <label class="label" for="author">' . esc_html__('Nom', 'woocommerce') . ' <span class="required">*</span></label>
              <div class="control"><input id="author" name="author" class="input" type="text" required></div>
            </div>',
                    'email'  => '<div class="field">
              <label class="label" for="email">' . esc_html__('Email', 'woocommerce') . ' <span class="required">*</span></label>
              <div class="control"><input id="email" name="email" class="input" type="email" required></div>
            </div>',
                ],
                'comment_field' => '
            <div class="field comment-form-rating"> <label class="label" for="rating" id="comment-form-rating-label">' . esc_html__('Votre note', 'woocommerce') . ' <span class="required">*</span> </label> <p class="stars"> <span role="group" aria-labelledby="comment-form-rating-label"> <a class="star-1" role="radio" aria-checked="false" href="#">1&nbsp;' . esc_html__('étoile sur 5', 'woocommerce') . '</a> <a class="star-2" role="radio" aria-checked="false" href="#">2&nbsp;' . esc_html__('étoiles sur 5', 'woocommerce') . '</a> <a class="star-3" role="radio" aria-checked="false" href="#">3&nbsp;' . esc_html__('étoiles sur 5', 'woocommerce') . '</a> <a class="star-4" role="radio" aria-checked="false" href="#">4&nbsp;' . esc_html__('étoiles sur 5', 'woocommerce') . '</a> <a class="star-5" role="radio" aria-checked="false" href="#">5&nbsp;' . esc_html__('étoiles sur 5', 'woocommerce') . '</a> </span> </p> </div>
            <div class="field">
              <label class="label" for="comment">' . esc_html__('Votre avis', 'woocommerce') . ' <span class="required">*</span></label>
              <div class="control"><textarea class="textarea" id="comment" name="comment" rows="5" required></textarea></div>
            </div>',
            ]);
            ?>
        </div>
    </section>



<?php
}


function my_bulma_review_walker($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    $rating = (int) get_comment_meta($comment->comment_ID, 'rating', true);
?>
    <div id="comment-<?php comment_ID(); ?>" class="embla__slide p-1">
        <div class="message is-small">
            <div class="message-header">
                <?php echo esc_html(get_comment_author()); ?>
                <p><?php echo esc_html(get_comment_date()); ?></p>
            </div>
            <div class="message-body">
                <?php if ($rating) echo wc_get_rating_html($rating); ?>
                <p><?php comment_text(); ?></p>
            </div>
        </div>
    </div>
<?php
}
