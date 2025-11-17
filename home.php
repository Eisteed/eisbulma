<?php

/**
 * Template for Blog Posts listing (page set as "Posts page")
 *
 * @package eisbulma
 */
// Change excerpt length (word count)
function eisbulma_custom_excerpt_length($length)
{
    return 15; // <-- nombre de mots
}
add_filter('excerpt_length', 'eisbulma_custom_excerpt_length', 999);

// Optionnel : modifier le "more"
function eisbulma_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'eisbulma_excerpt_more');

get_header();
?>

<div class="container py-5">
    <?php if (have_posts()) : ?>

        <div class="columns is-multiline is-variable is-5">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <div class="column is-one-third-desktop is-half-tablet is-full-mobile">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image">
                                <figure class="image  card-image-wrapper">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php
                                        the_post_thumbnail(
                                            'medium',
                                            array('class' => 'card-image-thumb')
                                        );
                                        ?>
                                    </a>
                                </figure>
                            </div>
                        <?php endif; ?>

                        <div class="card-content">
                            <header class="entry-header mb-4">
                                <?php
                                the_title(
                                    '<h2 class="title is-2"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="has-text-dark">',
                                    '</a></h2>'
                                );
                                ?>
                                <div class="container">
                                    <?php eisbulma_posted_on(); ?>
                                </div>

                            </header>
                            <a href="<?php the_permalink(); ?>" class="">
                                <div class="entry-content content">
                                    <?php the_excerpt(); ?>
                                </div>
                                <div class="container mt-5">
                                    <?php eisbulma_categories(); ?>
                                </div>
                        </div>
                        </a>
                    </article>
                </div>
            <?php
            endwhile;
            ?>
        </div>

        <?php eisbulma_pagination(); ?>

    <?php else : ?>

        <p><?php esc_html_e('No posts found.', 'eisbulma'); ?></p>

    <?php endif; ?>
</div>

<?php
get_footer();
