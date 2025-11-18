
<div class="embla  is-fullwidth-breakout">
    <div class="embla__viewport embla-carousel-header">
        <div class="embla__container">
            <?php
            $params = [
                'limit'   => -1
            ];
            $slides = pods('slide', $params);
            $current_date = date('Y-m-d');

            if ($slides->total() > 0) :
                while ($slides->fetch()) :
                    $date_debut = $slides->field('date_de_debut');
                    $date_fin = $slides->field('date_de_fin');

                    $date_debut = $date_debut ? date('Y-m-d', strtotime($date_debut)) : null;
                    $date_fin = $date_fin ? date('Y-m-d', strtotime($date_fin)) : null;

                    if (empty($date_debut) || empty($date_fin) || ($current_date >= $date_debut && $current_date <= $date_fin)) :
                        $bg_color = $slides->field('couleur_de_fond');
                        $bg_image = pods_image_url($slides->field('fond'), 'full');
            ?>
                     
                        <div class="embla__slide" style="background-color: <?php echo esc_attr($bg_color); ?>; background-image: url('<?php echo esc_url($bg_image); ?>');">
                        <?php if ($slides->field('bouton_lien')) : ?>
                            <a href="<?php echo esc_url($slides->field('bouton_lien')); ?>" style="all:unset; width:100%;height:100%;position:fixed;z-index:5"> </a>
                        <?php endif; ?>
                            <div class="embla-slide-wrapper">
                                <?php if ($slides->field('image')) : ?>
                                    <div class="image-content">
                                        <figure class="slider-image">
                                            <?php echo wp_get_attachment_image(pods_image_id_from_field($slides->field('image')), 'medium'); ?>
                                        </figure>
                                    </div>
                                <?php endif; ?>

                                <div class="text-content box">
                                    <h4 class="slider-title"><?php echo esc_html($slides->field('titre')); ?></h4>
                                    <p class="slider-paragraph"><?php echo wp_kses_post($slides->field('paragraphe')); ?></p>
                                </div>
                            </div>
                        </div>                                       
            <?php
                    endif;
                endwhile;
            endif;
            ?>
        </div>
    </div>

    <!-- Boutons navigation -->
    <button class="embla__button embla__button--prev" type="button" aria-label="Slide précédent">
    </button>
    <button class="embla__button embla__button--next" type="button" aria-label="Slide suivant">
    </button>

    <!-- Pagination -->
    <div class="embla__dots"></div>
</div>
