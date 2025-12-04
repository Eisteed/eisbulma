<?php /* Template Name: Page Pro */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<?php $theme_directory = get_template_directory_uri(); ?>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    if (function_exists('wp_body_open')) {
        wp_body_open();
    }
    ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'eisbulma'); ?></a>
        <header class="is-flex is-justify-content-center is-align-items-center mt-5">
            <?php
            $custom_logo_id = get_theme_mod('custom_logo');

            if ($custom_logo_id) {
                $logo = wp_get_attachment_image($custom_logo_id, 'full', false, ['class' => 'custom-logo m-auto']);
                echo $logo; // img only, no link
            } ?>
        </header>

        <main id="main">
            <div id="mainContainer" class="container">
                <?php
                if (have_posts()) :
                    if (is_home() && ! is_front_page()) :
                ?>
                        <header>
                            <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                        </header>
                <?php
                    endif;

                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
                        get_template_part('template-parts/content', get_post_type());
                    endwhile;

                    eisbulma_pagination();
                else :
                    get_template_part('template-parts/content', 'none');
                endif;
                ?>

                <?php

                get_footer();
